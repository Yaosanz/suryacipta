<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use App\Filament\Forms\CmsImageUpload;
use App\Filament\Forms\CmsMediaPreview;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Profil anggota tim')
                ->description('Informasi ini ditampilkan pada profil perusahaan. Gunakan nama dan jabatan yang telah disetujui.')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama lengkap')
                        ->placeholder('Contoh: Budi Santoso')
                        ->required()
                        ->maxLength(160),
                    TextInput::make('role')
                        ->label('Jabatan')
                        ->placeholder('Contoh: Kepala Produksi')
                        ->required()
                        ->maxLength(160),
                    Textarea::make('bio')
                        ->label('Ringkasan profesional')
                        ->placeholder('Jelaskan tanggung jawab, pengalaman, dan kontribusi anggota tim dalam 2-3 kalimat.')
                        ->maxLength(600)
                        ->rows(4)
                        ->columnSpanFull(),
                    CmsImageUpload::make(
                        'photo_path',
                        'Foto profesional',
                        'cms/team',
                        recommendedSize: '1200 x 900',
                    ),
                    CmsMediaPreview::make('photo_path', altField: 'photo_alt'),
                    TextInput::make('photo_alt')
                        ->label('Deskripsi foto')
                        ->placeholder('Contoh: Budi Santoso, Kepala Produksi')
                        ->maxLength(180),
                    TextInput::make('linkedin_url')
                        ->label('Profil LinkedIn')
                        ->placeholder('https://www.linkedin.com/in/nama')
                        ->url()
                        ->maxLength(255)
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->columnSpanFull(),
            Section::make('Penayangan')
                ->schema([
                    Toggle::make('is_published')
                        ->label('Tampilkan di website')
                        ->helperText('Pastikan anggota tim telah menyetujui publikasi profil dan foto.')
                        ->default(false)
                        ->disabled(fn (): bool => ! (auth()->user()?->role->canPublish() ?? false)),
                    TextInput::make('sort_order')
                        ->label('Urutan tampil')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(65535)
                        ->default(0)
                        ->required(),
                ])
                ->columns(2)
                ->columnSpanFull(),
        ]);
    }
}
