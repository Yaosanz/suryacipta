<?php

namespace App\Filament\Resources\Activities\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('log_name')
                    ->label('Area')
                    ->badge()
                    ->searchable(),
                TextColumn::make('subject_type')
                    ->label('Jenis data')
                    ->formatStateUsing(fn (?string $state): string => match (class_basename($state ?? '-')) {
                        'BlogPost' => 'Artikel',
                        'Faq' => 'FAQ',
                        'Inquiry' => 'Permintaan penawaran',
                        'MediaAsset' => 'Media',
                        'PageSection' => 'Bagian halaman',
                        'PortfolioProject' => 'Portofolio',
                        'Product' => 'Produk',
                        'SiteSetting' => 'Profil website',
                        'Testimonial' => 'Testimoni',
                        'User' => 'Pengguna',
                        default => class_basename($state ?? '-'),
                    })
                    ->searchable(),
                TextColumn::make('subject_id')
                    ->label('ID data')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('event')
                    ->label('Aksi')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'created' => 'Dibuat',
                        'updated' => 'Diubah',
                        'deleted' => 'Dihapus',
                        'restored' => 'Dipulihkan',
                        default => ucfirst($state ?? '-'),
                    })
                    ->searchable(),
                TextColumn::make('causer.name')
                    ->label('Pengguna')
                    ->placeholder('Sistem'),
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->emptyStateHeading('Belum ada aktivitas')
            ->emptyStateDescription('Perubahan penting pada konten dan pengguna akan tercatat di sini.')
            ->defaultSort('created_at', 'desc');
    }
}
