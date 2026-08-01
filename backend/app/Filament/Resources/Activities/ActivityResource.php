<?php

namespace App\Filament\Resources\Activities;

use App\Filament\Resources\Activities\Pages\ListActivities;
use App\Filament\Resources\Activities\Pages\ViewActivity;
use App\Filament\Resources\Activities\Tables\ActivitiesTable;
use App\Support\ActivityPresenter;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';

    protected static ?string $navigationLabel = 'Riwayat Aktivitas';

    protected static ?int $navigationSort = 30;

    protected static ?string $modelLabel = 'aktivitas';

    protected static ?string $pluralModelLabel = 'riwayat aktivitas';

    public static function table(Table $table): Table
    {
        return ActivitiesTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ringkasan aktivitas')
                    ->schema([
                        TextEntry::make('event')
                            ->label('Aksi')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => ActivityPresenter::event($state)),
                        TextEntry::make('log_name')->label('Area')->badge(),
                        TextEntry::make('causer.name')->label('Dilakukan oleh')->placeholder('Sistem'),
                        TextEntry::make('properties.actor_email')->label('Email pelaku')->placeholder('-'),
                        TextEntry::make('properties.actor_role')->label('Peran pelaku')->placeholder('-'),
                        TextEntry::make('created_at')
                            ->label('Waktu (WIB)')
                            ->dateTime('d M Y H:i:s')
                            ->timezone(config('app.display_timezone')),
                        TextEntry::make('subject_type')
                            ->label('Jenis data')
                            ->formatStateUsing(fn (?string $state): string => ActivityPresenter::subjectType($state)),
                        TextEntry::make('subject_id')->label('ID data'),
                        TextEntry::make('properties.subject_label')->label('Nama data')->placeholder('-'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Perubahan tercatat')
                    ->description('Informasi teknis ini membantu pemeriksaan saat terjadi perubahan data.')
                    ->schema([
                        ViewEntry::make('attribute_changes')
                            ->label('Detail perubahan')
                            ->view('filament.components.activity-changes')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Konteks keamanan')
                    ->description('Digunakan untuk penelusuran kejadian dan pemeriksaan akses.')
                    ->schema([
                        TextEntry::make('properties.ip_address')->label('Alamat IP')->placeholder('-'),
                        TextEntry::make('properties.http_method')->label('Metode HTTP')->placeholder('-'),
                        TextEntry::make('properties.route')->label('Rute')->placeholder('-'),
                        TextEntry::make('properties.user_agent')
                            ->label('Browser / perangkat')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->columnSpanFull(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivities::route('/'),
            'view' => ViewActivity::route('/{record}'),
        ];
    }
}
