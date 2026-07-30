<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Filament\Forms\CmsImageUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi produk')
                    ->description('Informasi utama yang dibaca pengunjung pada katalog dan halaman detail produk.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama produk')
                            ->placeholder('Contoh: Panel GRC Motif Kayu')
                            ->required()
                            ->maxLength(160)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, Set $set, string $operation): void {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state ?? ''));
                                }
                            }),
                        TextInput::make('category')
                            ->label('Kategori')
                            ->placeholder('Contoh: Panel Fasad')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('slug')
                            ->label('Alamat URL')
                            ->placeholder('panel-grc-motif-kayu')
                            ->helperText('Dibuat otomatis dari nama. Hindari mengubahnya setelah produk diterbitkan.')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->alphaDash()
                            ->maxLength(160),
                        Textarea::make('short_description')
                            ->label('Ringkasan')
                            ->placeholder('Jelaskan manfaat utama produk dalam 1-2 kalimat.')
                            ->helperText('Tampil pada kartu produk. Maksimal 500 karakter.')
                            ->required()
                            ->maxLength(500)
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Deskripsi lengkap')
                            ->placeholder('Jelaskan bahan, kegunaan, pilihan ukuran, dan layanan pemasangan.')
                            ->rows(6)
                            ->columnSpanFull(),
                        TagsInput::make('features')
                            ->label('Keunggulan')
                            ->placeholder('Ketik satu keunggulan lalu tekan Enter')
                            ->helperText('Contoh: tahan cuaca, bobot ringan, ukuran dapat disesuaikan.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Gambar produk')
                    ->description('Gunakan foto produk yang jelas dan tambahkan deskripsi gambar untuk aksesibilitas.')
                    ->schema([
                        CmsImageUpload::make('image_path', 'Foto produk', 'cms/products'),
                        TextInput::make('image_alt')
                            ->label('Deskripsi gambar')
                            ->placeholder('Contoh: Panel GRC putih terpasang pada fasad gedung')
                            ->helperText('Jelaskan isi gambar, bukan nama file.')
                            ->maxLength(180),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Penayangan')
                    ->description('Atur posisi dan visibilitas produk di website.')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Tampilkan di website')
                            ->helperText('Aktifkan setelah seluruh informasi siap dilihat publik.')
                            ->default(false)
                            ->disabled(fn (): bool => ! (auth()->user()?->role->canPublish() ?? false)),
                        Toggle::make('is_featured')
                            ->label('Jadikan produk unggulan')
                            ->helperText('Produk unggulan mendapat prioritas pada halaman utama.')
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
                            ->label('Jadwal terbit')
                            ->helperText('Kosongkan untuk menggunakan waktu saat produk diterbitkan.'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Optimasi mesin pencari')
                    ->description('Opsional. Kosongkan untuk menggunakan nama dan ringkasan produk.')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('Judul SEO')
                            ->placeholder('Panel GRC Motif Kayu untuk Fasad')
                            ->maxLength(60)
                            ->columnSpanFull(),
                        Textarea::make('seo_description')
                            ->label('Deskripsi SEO')
                            ->placeholder('Ringkasan singkat yang menarik untuk hasil pencarian Google.')
                            ->maxLength(160)
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsed()
                    ->columnSpanFull(),
            ]);
    }
}
