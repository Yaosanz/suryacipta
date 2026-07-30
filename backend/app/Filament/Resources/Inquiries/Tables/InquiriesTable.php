<?php

namespace App\Filament\Resources\Inquiries\Tables;

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
                    ->searchable(),
                TextColumn::make('company')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('project_type')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()->searchable(),
                TextColumn::make('created_at')
                    ->label('Masuk')->dateTime('d M Y H:i')
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'new' => 'Baru',
                    'contacted' => 'Sudah dihubungi',
                    'qualified' => 'Terkualifikasi',
                    'closed' => 'Selesai',
                    'spam' => 'Spam',
                ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])->defaultSort('created_at', 'desc');
    }
}
