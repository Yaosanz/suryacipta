<?php

namespace App\Filament\Resources\MediaAssets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class MediaAssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('file_path')->label('Pratinjau')->disk('public'),
                TextColumn::make('alt_text')
                    ->label('Deskripsi gambar')
                    ->limit(60)
                    ->searchable(),
                TextColumn::make('mime_type')
                    ->label('Format')
                    ->formatStateUsing(fn (string $state): string => strtoupper(str_replace('image/', '', $state)))
                    ->badge(),
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
                EditAction::make(),
            ])
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
