<?php

namespace App\Filament\Resources\MediaAssets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class MediaAssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ViewColumn::make('file_path')
                    ->label('Pratinjau')
                    ->view('filament.tables.columns.media-preview'),
                TextColumn::make('alt_text')
                    ->label('Deskripsi gambar')
                    ->limit(60)
                    ->searchable(),
                TextColumn::make('mime_type')
                    ->label('Format')
                    ->formatStateUsing(fn (string $state): string => strtoupper(str_replace(['image/', 'video/'], '', $state)))
                    ->badge(),
                TextColumn::make('dimensions')
                    ->label('Resolusi')
                    ->state(fn ($record): string => filled($record->width) && filled($record->height)
                        ? "{$record->width} x {$record->height}"
                        : '-'),
                TextColumn::make('duration')
                    ->label('Durasi')
                    ->formatStateUsing(fn (?float $state): string => filled($state) ? number_format($state, 1).' detik' : '-'),
                TextColumn::make('size')
                    ->label('Ukuran')
                    ->formatStateUsing(fn (int $state): string => number_format($state / 1024, 1).' KB')
                    ->sortable(),
                TextColumn::make('original_name')
                    ->label('Nama penyimpanan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()->label('Lihat')->button()->outlined(),
                EditAction::make()->label('Ubah')->button(),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Pustaka media masih kosong')
            ->emptyStateDescription('Unggah gambar yang siap digunakan pada konten website.')
            ->defaultSort('created_at', 'desc');
    }
}
