<?php

namespace App\Filament\Resources\Activities\Tables;

use App\Models\User;
use App\Support\ActivityPresenter;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
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
                    ->formatStateUsing(fn (?string $state): string => ActivityPresenter::subjectType($state))
                    ->searchable(),
                TextColumn::make('properties.subject_label')
                    ->label('Data')
                    ->placeholder('-')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('subject_id')
                    ->label('ID data')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('event')
                    ->label('Aksi')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => ActivityPresenter::event($state))
                    ->searchable(),
                TextColumn::make('causer.name')
                    ->label('Pengguna')
                    ->placeholder('Sistem')
                    ->description(fn ($record): ?string => $record->properties->get('actor_email')),
                TextColumn::make('properties.ip_address')
                    ->label('Alamat IP')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Waktu (WIB)')
                    ->dateTime('d M Y H:i:s')
                    ->timezone(config('app.display_timezone'))
                    ->description(fn ($record): string => $record->created_at->diffForHumans())
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('event')
                    ->label('Jenis aksi')
                    ->options([
                        'created' => 'Dibuat',
                        'updated' => 'Diubah',
                        'deleted' => 'Dihapus',
                        'restored' => 'Dipulihkan',
                        'login' => 'Login',
                        'logout' => 'Logout',
                    ]),
                SelectFilter::make('log_name')
                    ->label('Area')
                    ->options([
                        'authentication' => 'Autentikasi',
                        'content' => 'Konten',
                        'inquiries' => 'Prospek',
                        'media' => 'Media',
                        'settings' => 'Pengaturan',
                        'users' => 'Pengguna',
                    ]),
                SelectFilter::make('causer_id')
                    ->label('Pengguna')
                    ->options(fn (): array => User::query()->orderBy('name')->pluck('name', 'id')->all()),
                Filter::make('created_at')
                    ->label('Rentang waktu')
                    ->schema([
                        DatePicker::make('from')->label('Dari tanggal'),
                        DatePicker::make('until')->label('Sampai tanggal'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['from'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
                        ->when($data['until'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '<=', $date))),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat')
                    ->button()
                    ->outlined(),
            ], position: RecordActionsPosition::BeforeColumns)
            ->emptyStateHeading('Belum ada aktivitas')
            ->emptyStateDescription('Perubahan penting pada konten dan pengguna akan tercatat di sini.')
            ->defaultSort('created_at', 'desc');
    }
}
