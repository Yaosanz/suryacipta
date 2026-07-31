<?php

namespace App\Filament\Resources\Inquiries\Tables;

use App\Enums\InquiryStatus;
use App\Models\Inquiry;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;

class InquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('company')
                    ->label('Perusahaan')
                    ->placeholder('Tidak diisi')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->copyable()
                    ->url(fn (string $state): string => 'mailto:'.$state)
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telepon')
                    ->copyable()
                    ->url(fn (string $state): string => 'tel:'.preg_replace('/[^+0-9]/', '', $state))
                    ->searchable(),
                TextColumn::make('project_type')
                    ->label('Jenis proyek')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('handledBy.name')
                    ->label('Ditangani oleh')
                    ->placeholder('Belum ditangani')
                    ->description(fn (Inquiry $record): ?string => $record->handled_at?->diffForHumans())
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Masuk')->dateTime('d M Y H:i')
                    ->sortable()
                    ->since()
                    ->dateTimeTooltip('d M Y H:i'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status penanganan')
                    ->options(InquiryStatus::class),
            ])
            ->recordActions([
                ViewAction::make()->label('Lihat')->button()->outlined(),
                Action::make('handle')
                    ->label('Tangani')
                    ->icon('heroicon-o-hand-raised')
                    ->color('warning')
                    ->button()
                    ->authorize(fn (Inquiry $record): bool => Gate::allows('update', $record))
                    ->visible(fn (Inquiry $record): bool => $record->status === InquiryStatus::New)
                    ->action(fn (Inquiry $record) => $record->update([
                        'status' => InquiryStatus::Contacted,
                        'handled_by' => auth()->id(),
                        'handled_at' => now(),
                    ]))
                    ->successNotificationTitle('Prospek ditandai sedang ditangani'),
                Action::make('qualify')
                    ->label('Prospek potensial')
                    ->icon('heroicon-o-star')
                    ->color('success')
                    ->button()
                    ->authorize(fn (Inquiry $record): bool => Gate::allows('update', $record))
                    ->visible(fn (Inquiry $record): bool => $record->status === InquiryStatus::Contacted)
                    ->action(fn (Inquiry $record) => $record->update(['status' => InquiryStatus::Qualified]))
                    ->successNotificationTitle('Status prospek berhasil diperbarui'),
                Action::make('complete')
                    ->label('Selesaikan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->button()
                    ->requiresConfirmation()
                    ->authorize(fn (Inquiry $record): bool => Gate::allows('update', $record))
                    ->visible(fn (Inquiry $record): bool => in_array($record->status, [InquiryStatus::Contacted, InquiryStatus::Qualified], true))
                    ->action(fn (Inquiry $record) => $record->update(['status' => InquiryStatus::Closed]))
                    ->successNotificationTitle('Prospek telah diselesaikan'),
                EditAction::make()->label('Ubah status')->button(),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada permintaan penawaran')
            ->emptyStateDescription('Pesan dari formulir kontak website akan muncul di sini.')
            ->defaultSort('created_at', 'desc');
    }
}
