<?php

namespace App\Filament\Resources\BlogPosts\Tables;

use App\Enums\PublicationStatus;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BlogPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul artikel')
                    ->limit(60)
                    ->searchable(),
                ImageColumn::make('featured_image_path')
                    ->label('Gambar')
                    ->disk('public')
                    ->imageHeight(64)
                    ->imageWidth(96),
                IconColumn::make('video_path')
                    ->label('Video')
                    ->boolean(),
                TextColumn::make('author.name')
                    ->label('Penulis')
                    ->placeholder('Sistem')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
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
                SelectFilter::make('status')
                    ->label('Status artikel')
                    ->options(PublicationStatus::class),
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
            ->emptyStateHeading('Belum ada artikel')
            ->emptyStateDescription('Buat artikel edukatif untuk membantu calon pelanggan memahami produk GRC.')
            ->defaultSort('updated_at', 'desc');
    }
}
