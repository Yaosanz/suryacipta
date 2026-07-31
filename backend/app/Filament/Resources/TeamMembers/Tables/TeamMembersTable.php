<?php

namespace App\Filament\Resources\TeamMembers\Tables;

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

class TeamMembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo_path')->label('Foto')->disk('public')->circular()->imageSize(64),
                TextColumn::make('name')->label('Nama')->searchable(),
                TextColumn::make('role')->label('Jabatan')->searchable(),
                IconColumn::make('is_published')->label('Tampil')->boolean(),
                TextColumn::make('sort_order')->label('Urutan')->numeric()->sortable(),
                TextColumn::make('updated_at')->label('Diperbarui')->since()->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Status tayang')
                    ->trueLabel('Sudah tampil')
                    ->falseLabel('Belum tampil'),
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
            ->emptyStateHeading('Belum ada profil tim')
            ->emptyStateDescription('Tambahkan anggota tim yang mewakili fungsi utama perusahaan.')
            ->defaultSort('sort_order');
    }
}
