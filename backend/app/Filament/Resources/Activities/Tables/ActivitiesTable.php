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
                    ->searchable(),
                TextColumn::make('subject_type')
                    ->label('Jenis data')
                    ->formatStateUsing(fn (?string $state): string => class_basename($state ?? '-'))
                    ->searchable(),
                TextColumn::make('subject_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('event')
                    ->label('Aksi')
                    ->badge()
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
            ->defaultSort('created_at', 'desc');
    }
}
