<?php

namespace App\Filament\Resources\PortfolioProjects\Schemas;

use App\Filament\Forms\CmsImageUpload;
use App\Filament\Forms\CmsMediaPreview;
use App\Filament\Forms\CmsVideoUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PortfolioProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi proyek')
                    ->description('Data ini ditampilkan sebagai bukti pengalaman perusahaan pada halaman portofolio.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Nama proyek')
                            ->placeholder('Contoh: Renovasi Kubah Masjid Al-Ikhlas')
                            ->required()
                            ->maxLength(160)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, Set $set, string $operation): void {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state ?? ''));
                                }
                            }),
                        TextInput::make('slug')
                            ->label('Alamat URL')
                            ->placeholder('renovasi-kubah-masjid-al-ikhlas')
                            ->helperText('Dibuat otomatis dari nama proyek.')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->alphaDash()
                            ->maxLength(160),
                        TextInput::make('client_name')
                            ->label('Nama klien')
                            ->placeholder('Kosongkan bila nama klien bersifat rahasia')
                            ->maxLength(160),
                        TextInput::make('location')
                            ->label('Lokasi proyek')
                            ->placeholder('Contoh: Bogor, Jawa Barat')
                            ->required()
                            ->maxLength(160),
                        TextInput::make('category')
                            ->label('Kategori proyek')
                            ->placeholder('Contoh: Kubah Masjid')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('year')
                            ->label('Tahun pengerjaan')
                            ->placeholder((string) date('Y'))
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue((int) date('Y') + 2),
                        Textarea::make('description')
                            ->label('Ringkasan pekerjaan')
                            ->placeholder('Jelaskan kebutuhan klien, lingkup pekerjaan, dan hasil proyek.')
                            ->required()
                            ->maxLength(1000)
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Dokumentasi')
                    ->schema([
                        CmsImageUpload::make('image_path', 'Foto proyek', 'cms/portfolio'),
                        CmsMediaPreview::make('image_path', altField: 'image_alt'),
                        TextInput::make('image_alt')
                            ->label('Deskripsi gambar')
                            ->placeholder('Contoh: Kubah GRC putih setelah pemasangan selesai')
                            ->maxLength(180),
                        CmsVideoUpload::make('video_path', 'Video proyek', 'cms/portfolio/videos'),
                        CmsMediaPreview::make('video_path', 'video', 'video_alt'),
                        TextInput::make('video_alt')
                            ->label('Deskripsi video')
                            ->placeholder('Contoh: Dokumentasi hasil pemasangan kubah dari sisi luar')
                            ->maxLength(180),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Penayangan')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Tampilkan di website')
                            ->default(false)
                            ->disabled(fn (): bool => ! (auth()->user()?->role->canPublish() ?? false)),
                        Toggle::make('is_featured')
                            ->label('Jadikan proyek unggulan')
                            ->helperText('Proyek unggulan mendapat prioritas pada halaman utama.')
                            ->default(false),
                        TextInput::make('sort_order')
                            ->label('Urutan tampil')
                            ->helperText('Angka lebih kecil tampil lebih dahulu.')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(65535)
                            ->default(0),
                        DateTimePicker::make('published_at')
                            ->label('Jadwal terbit'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
