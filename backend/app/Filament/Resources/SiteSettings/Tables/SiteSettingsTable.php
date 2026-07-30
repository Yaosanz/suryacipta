<?php

namespace App\Filament\Resources\SiteSettings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiteSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_name')
                    ->label('Nama perusahaan')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->copyable(),
                TextColumn::make('phone')
                    ->label('Telepon'),
                TextColumn::make('updated_at')
                    ->label('Terakhir diperbarui')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->label('Ubah identitas'),
            ])
            ->emptyStateHeading('Identitas website belum diisi')
            ->emptyStateDescription('Tambahkan profil perusahaan agar informasi dasar dapat ditampilkan pada website.');
    }
}
