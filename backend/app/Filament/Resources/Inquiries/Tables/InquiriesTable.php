<?php

namespace App\Filament\Resources\Inquiries\Tables;

use App\Enums\InquiryStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

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
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telepon')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('project_type')
                    ->label('Jenis proyek')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Masuk')->dateTime('d M Y H:i')
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status penanganan')
                    ->options(InquiryStatus::class),
            ])
            ->recordActions([
                EditAction::make(),
            ])
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
