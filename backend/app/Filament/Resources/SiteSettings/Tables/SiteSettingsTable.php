<?php

namespace App\Filament\Resources\SiteSettings\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class SiteSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->disk('public')
                    ->imageHeight(64)
                    ->imageWidth(96),
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
                ViewAction::make()->label('Lihat profil')->button()->outlined(),
                EditAction::make()->label('Ubah identitas')->button(),
            ], position: RecordActionsPosition::BeforeColumns)
            ->emptyStateHeading('Identitas website belum diisi')
            ->emptyStateDescription('Tambahkan profil perusahaan agar informasi dasar dapat ditampilkan pada website.');
    }
}
