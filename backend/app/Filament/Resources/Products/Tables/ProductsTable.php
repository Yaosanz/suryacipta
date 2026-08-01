<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama produk')
                    ->searchable(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->searchable(),
                ImageColumn::make('image_path')
                    ->label('Foto')
                    ->disk('public')
                    ->imageHeight(64)
                    ->imageWidth(96),
                IconColumn::make('video_path')
                    ->label('Video')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),
                IconColumn::make('is_published')
                    ->label('Tampil')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('Waktu terbit')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum terbit')
                    ->sortable(),
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
                TernaryFilter::make('is_published')
                    ->label('Status tayang')
                    ->trueLabel('Sudah tampil')
                    ->falseLabel('Belum tampil'),
                TernaryFilter::make('is_featured')
                    ->label('Produk unggulan')
                    ->trueLabel('Unggulan')
                    ->falseLabel('Bukan unggulan'),
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
            ->emptyStateHeading('Belum ada produk')
            ->emptyStateDescription('Tambahkan produk pertama untuk mulai mengisi katalog website.')
            ->defaultSort('sort_order');
    }
}
