<?php

namespace App\Filament\Resources\PageSections\Tables;

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

class PageSectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('page')
                    ->label('Halaman')
                    ->formatStateUsing(fn (string $state): string => $state === 'home' ? 'Halaman utama' : ucfirst($state))
                    ->badge()
                    ->searchable(),
                TextColumn::make('section_key')
                    ->label('Bagian')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'hero' => 'Pembuka (Hero)',
                        'about' => 'Tentang perusahaan',
                        'products' => 'Katalog produk',
                        'advantages' => 'Keunggulan perusahaan',
                        'process' => 'Proses kerja',
                        'portfolio' => 'Portofolio',
                        'testimonials' => 'Testimoni',
                        'team' => 'Tim perusahaan',
                        'cta' => 'Ajakan menghubungi',
                        'contact' => 'Kontak dan formulir',
                        default => ucfirst($state),
                    })
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Judul')
                    ->limit(60)
                    ->searchable(),
                ImageColumn::make('image_path')
                    ->label('Gambar')
                    ->disk('public')
                    ->imageHeight(64)
                    ->imageWidth(96),
                IconColumn::make('video_path')
                    ->label('Video')
                    ->boolean(),
                IconColumn::make('is_enabled')
                    ->label('Tampil')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
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
                TernaryFilter::make('is_enabled')
                    ->label('Status tampilan')
                    ->trueLabel('Sedang tampil')
                    ->falseLabel('Disembunyikan'),
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
            ->emptyStateHeading('Belum ada bagian halaman')
            ->emptyStateDescription('Tambahkan bagian pembuka, tentang perusahaan, atau ajakan menghubungi.')
            ->defaultSort('sort_order');
    }
}
