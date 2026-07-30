<?php

namespace App\Filament\Resources\PageSections\Schemas;

use App\Filament\Forms\CmsImageUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;

class PageSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Lokasi konten')
                    ->description('Pilih bagian website yang ingin diubah. Setiap bagian hanya boleh dibuat satu kali.')
                    ->schema([
                        Select::make('page')
                            ->label('Halaman')
                            ->options(['home' => 'Halaman utama'])
                            ->default('home')
                            ->required(),
                        Select::make('section_key')
                            ->label('Bagian halaman')
                            ->options([
                                'hero' => 'Bagian pembuka (Hero)',
                                'about' => 'Tentang perusahaan',
                                'cta' => 'Ajakan menghubungi',
                            ])
                            ->placeholder('Pilih bagian yang akan diatur')
                            ->required()
                            ->unique(
                                ignoreRecord: true,
                                modifyRuleUsing: fn (Unique $rule, Get $get): Unique => $rule->where('page', $get('page')),
                            ),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Teks dan tombol')
                    ->description('Isi hanya bagian yang diperlukan. Pratinjau akhir dapat dilihat pada landing page.')
                    ->schema([
                        TextInput::make('eyebrow')
                            ->label('Teks kecil di atas judul')
                            ->placeholder('Contoh: Spesialis GRC Indonesia')
                            ->maxLength(100),
                        TextInput::make('title')
                            ->label('Judul utama')
                            ->placeholder('Masukkan judul yang singkat dan mudah dipahami')
                            ->required()
                            ->maxLength(200)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Penjelasan')
                            ->placeholder('Tuliskan penjelasan pendukung dalam 1-3 kalimat.')
                            ->rows(4)
                            ->columnSpanFull(),
                        TextInput::make('primary_cta_label')
                            ->label('Teks tombol utama')
                            ->placeholder('Contoh: Minta Penawaran')
                            ->maxLength(80),
                        TextInput::make('primary_cta_url')
                            ->label('Tujuan tombol')
                            ->placeholder('Contoh: /kontak atau https://wa.me/628...')
                            ->helperText('Gunakan alamat internal yang diawali / atau URL HTTPS.')
                            ->maxLength(255),
                        KeyValue::make('content')
                            ->label('Konten tambahan')
                            ->helperText('Khusus kebutuhan lanjutan. Biarkan kosong bila tidak diperlukan.')
                            ->keyLabel('Nama data')
                            ->valueLabel('Isi')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Gambar')
                    ->schema([
                        CmsImageUpload::make('image_path', 'Gambar bagian', 'cms/sections'),
                        TextInput::make('image_alt')
                            ->label('Deskripsi gambar')
                            ->placeholder('Jelaskan objek utama yang terlihat pada gambar')
                            ->maxLength(180),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Penayangan')
                    ->schema([
                        Toggle::make('is_enabled')
                            ->label('Tampilkan bagian ini')
                            ->default(false)
                            ->disabled(fn (): bool => ! (auth()->user()?->role->canPublish() ?? false)),
                        TextInput::make('sort_order')
                            ->label('Urutan tampil')
                            ->helperText('Angka lebih kecil tampil lebih dahulu.')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(65535)
                            ->default(0),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
