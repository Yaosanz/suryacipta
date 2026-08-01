<?php

namespace App\Filament\Resources\PageSections\Schemas;

use App\Filament\Forms\CmsImageUpload;
use App\Filament\Forms\CmsMediaPreview;
use App\Filament\Forms\CmsVideoUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
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
                            ->options([
                                'home' => 'Halaman utama',
                                'products' => 'Halaman produk',
                                'portfolio' => 'Halaman portofolio',
                                'blog' => 'Halaman artikel',
                                'about' => 'Halaman tentang kami',
                                'contact' => 'Halaman kontak',
                            ])
                            ->default('home')
                            ->live()
                            ->afterStateUpdated(fn (Set $set): mixed => $set('section_key', null))
                            ->required(),
                        Select::make('section_key')
                            ->label('Bagian halaman')
                            ->options(fn (Get $get): array => match ($get('page')) {
                                'home' => [
                                    'hero' => 'Bagian pembuka (Hero)',
                                    'about' => 'Tentang perusahaan',
                                    'products' => 'Katalog produk',
                                    'advantages' => 'Keunggulan perusahaan',
                                    'process' => 'Proses kerja',
                                    'portfolio' => 'Portofolio',
                                    'testimonials' => 'Testimoni',
                                    'team' => 'Tim perusahaan',
                                    'cta' => 'Ajakan menghubungi',
                                    'contact' => 'Formulir dan informasi kontak',
                                ],
                                'products' => [
                                    'hero' => 'Bagian pembuka',
                                    'detail' => 'Penjelasan detail produk',
                                    'support' => 'Estimator dan FAQ',
                                ],
                                'portfolio' => ['hero' => 'Bagian pembuka'],
                                'blog' => ['hero' => 'Bagian pembuka'],
                                'about' => [
                                    'hero' => 'Bagian pembuka',
                                    'story' => 'Perjalanan perusahaan',
                                    'values' => 'Visi, misi, dan nilai',
                                    'team' => 'Tim perusahaan',
                                ],
                                'contact' => [
                                    'hero' => 'Bagian pembuka',
                                    'contact' => 'Formulir dan informasi kontak',
                                    'faq' => 'Pertanyaan umum',
                                ],
                                default => [],
                            })
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
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Detail bagian pembuka')
                    ->description('Atur informasi ringkas yang tampil di atas dan di bawah tombol utama.')
                    ->schema([
                        TextInput::make('content.badge')
                            ->label('Label kepercayaan')
                            ->placeholder('Contoh: Berpengalaman lebih dari 25 tahun')
                            ->maxLength(120)
                            ->columnSpanFull(),
                        TextInput::make('content.secondaryCtaLabel')
                            ->label('Teks tombol kedua')
                            ->placeholder('Lihat Portofolio')
                            ->maxLength(80),
                        TextInput::make('content.secondaryCtaUrl')
                            ->label('Tujuan tombol kedua')
                            ->placeholder('/portofolio')
                            ->helperText('Gunakan alamat internal berawalan / atau URL HTTPS.')
                            ->maxLength(255),
                        Repeater::make('content.highlights')
                            ->label('Poin ringkas')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Poin')
                                    ->placeholder('Contoh: Produksi dan instalasi')
                                    ->required()
                                    ->maxLength(100),
                            ])
                            ->addActionLabel('Tambah poin')
                            ->minItems(1)
                            ->maxItems(4)
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get): bool => $get('page') === 'home' && $get('section_key') === 'hero')
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Sorotan profil perusahaan')
                    ->description('Informasi lokasi dan kemampuan utama yang tampil pada bagian Tentang Kami.')
                    ->schema([
                        TextInput::make('content.locationTitle')
                            ->label('Judul lokasi')
                            ->placeholder('Cibinong')
                            ->maxLength(80),
                        TextInput::make('content.locationText')
                            ->label('Keterangan lokasi')
                            ->placeholder('Basis produksi strategis untuk Jabodetabek')
                            ->maxLength(160),
                        Repeater::make('content.features')
                            ->label('Kemampuan utama')
                            ->schema([
                                Select::make('icon')
                                    ->label('Ikon')
                                    ->options([
                                        'factory' => 'Pabrik / produksi',
                                        'drafting' => 'Desain / gambar kerja',
                                        'quality' => 'Kontrol kualitas',
                                        'delivery' => 'Pengiriman',
                                    ])
                                    ->default('factory')
                                    ->required(),
                                TextInput::make('title')
                                    ->label('Judul')
                                    ->required()
                                    ->maxLength(100),
                                Textarea::make('text')
                                    ->label('Penjelasan')
                                    ->required()
                                    ->rows(2)
                                    ->maxLength(260)
                                    ->columnSpanFull(),
                            ])
                            ->addActionLabel('Tambah kemampuan')
                            ->minItems(1)
                            ->maxItems(4)
                            ->columns(2)
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get): bool => $get('page') === 'home' && $get('section_key') === 'about')
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Daftar keunggulan')
                    ->description('Gunakan kalimat singkat yang konkret dan dapat dibuktikan.')
                    ->schema([
                        Repeater::make('content.items')
                            ->label('Keunggulan')
                            ->schema([
                                Select::make('icon')
                                    ->label('Ikon')
                                    ->options([
                                        'factory' => 'Produksi',
                                        'palette' => 'Desain custom',
                                        'clock' => 'Jadwal',
                                        'weather' => 'Ketahanan',
                                        'delivery' => 'Pengiriman',
                                        'quality' => 'Kontrol kualitas',
                                    ])
                                    ->required(),
                                TextInput::make('title')
                                    ->label('Judul')
                                    ->required()
                                    ->maxLength(100),
                                Textarea::make('text')
                                    ->label('Penjelasan')
                                    ->required()
                                    ->rows(2)
                                    ->maxLength(260)
                                    ->columnSpanFull(),
                            ])
                            ->addActionLabel('Tambah keunggulan')
                            ->minItems(1)
                            ->maxItems(8)
                            ->columns(2)
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get): bool => $get('page') === 'home' && $get('section_key') === 'advantages')
                    ->columnSpanFull(),
                Section::make('Tahapan proses')
                    ->description('Urutan pada daftar ini sama dengan urutan yang tampil di website.')
                    ->schema([
                        Repeater::make('content.items')
                            ->label('Tahapan')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Nama tahapan')
                                    ->required()
                                    ->maxLength(100),
                                Textarea::make('text')
                                    ->label('Penjelasan')
                                    ->required()
                                    ->rows(2)
                                    ->maxLength(260),
                            ])
                            ->addActionLabel('Tambah tahapan')
                            ->minItems(1)
                            ->maxItems(8)
                            ->columns(2)
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get): bool => $get('page') === 'home' && $get('section_key') === 'process')
                    ->columnSpanFull(),
                Section::make('Cerita perusahaan')
                    ->description('Konten ini tampil pada halaman Tentang Kami setelah bagian pembuka.')
                    ->schema([
                        Textarea::make('content.secondaryDescription')
                            ->label('Paragraf lanjutan')
                            ->placeholder('Jelaskan lokasi, cakupan layanan, atau perjalanan perusahaan.')
                            ->rows(4)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get): bool => $get('page') === 'about' && $get('section_key') === 'story')
                    ->columnSpanFull(),
                Section::make('Visi, misi, dan nilai')
                    ->description('Gunakan pernyataan yang singkat, spesifik, dan relevan dengan operasional perusahaan.')
                    ->schema([
                        Textarea::make('content.vision')
                            ->label('Visi')
                            ->rows(3)
                            ->required()
                            ->maxLength(600),
                        Textarea::make('content.mission')
                            ->label('Misi')
                            ->rows(3)
                            ->required()
                            ->maxLength(600),
                        Repeater::make('content.items')
                            ->label('Nilai kerja')
                            ->schema([
                                Select::make('icon')
                                    ->label('Ikon')
                                    ->options([
                                        'quality' => 'Kualitas',
                                        'collaboration' => 'Kolaborasi',
                                        'solution' => 'Solutif',
                                        'responsibility' => 'Tanggung jawab',
                                    ])
                                    ->required(),
                                TextInput::make('title')
                                    ->label('Nama nilai')
                                    ->required()
                                    ->maxLength(100),
                                Textarea::make('text')
                                    ->label('Penjelasan')
                                    ->required()
                                    ->rows(2)
                                    ->maxLength(260)
                                    ->columnSpanFull(),
                            ])
                            ->addActionLabel('Tambah nilai')
                            ->minItems(1)
                            ->maxItems(8)
                            ->columns(2)
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get): bool => $get('page') === 'about' && $get('section_key') === 'values')
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Estimator kebutuhan panel')
                    ->description('Atur teks dan nilai awal kalkulator. Rumus tetap menggunakan allowance yang dipilih.')
                    ->schema([
                        TextInput::make('content.estimator.title')
                            ->label('Judul estimator')
                            ->required()
                            ->maxLength(120),
                        TextInput::make('content.estimator.allowancePercent')
                            ->label('Allowance (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(30)
                            ->required(),
                        Textarea::make('content.estimator.description')
                            ->label('Penjelasan estimator')
                            ->rows(3)
                            ->maxLength(300)
                            ->columnSpanFull(),
                        TextInput::make('content.estimator.areaLabel')
                            ->label('Label luas area')
                            ->required()
                            ->maxLength(80),
                        TextInput::make('content.estimator.panelAreaLabel')
                            ->label('Label luas panel')
                            ->required()
                            ->maxLength(80),
                        TextInput::make('content.estimator.resultLabel')
                            ->label('Label hasil')
                            ->required()
                            ->maxLength(80),
                        TextInput::make('content.estimator.defaultArea')
                            ->label('Nilai awal luas area')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        TextInput::make('content.estimator.defaultPanelArea')
                            ->label('Nilai awal luas panel')
                            ->numeric()
                            ->minValue(0.1)
                            ->required(),
                    ])
                    ->visible(fn (Get $get): bool => $get('page') === 'products' && $get('section_key') === 'support')
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Detail kontak')
                    ->description('Atur teks panel lokasi dan pilihan pada formulir permintaan penawaran.')
                    ->schema([
                        TextInput::make('content.sidebarTitle')
                            ->label('Judul panel lokasi')
                            ->placeholder('Workshop Cibinong')
                            ->maxLength(120),
                        TextInput::make('content.mapUrl')
                            ->label('URL Google Maps embed')
                            ->placeholder('https://www.google.com/maps?...&output=embed')
                            ->url()
                            ->maxLength(500),
                        Textarea::make('content.sidebarDescription')
                            ->label('Penjelasan panel lokasi')
                            ->rows(3)
                            ->maxLength(300)
                            ->columnSpanFull(),
                        Repeater::make('content.projectTypes')
                            ->label('Pilihan jenis proyek')
                            ->schema([
                                TextInput::make('label')
                                    ->label('Jenis proyek')
                                    ->required()
                                    ->maxLength(100),
                            ])
                            ->addActionLabel('Tambah jenis proyek')
                            ->minItems(1)
                            ->maxItems(12),
                        Repeater::make('content.budgetRanges')
                            ->label('Pilihan rentang anggaran')
                            ->schema([
                                TextInput::make('label')
                                    ->label('Rentang anggaran')
                                    ->required()
                                    ->maxLength(100),
                            ])
                            ->addActionLabel('Tambah rentang')
                            ->minItems(1)
                            ->maxItems(10),
                    ])
                    ->visible(fn (Get $get): bool => $get('section_key') === 'contact')
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Media')
                    ->description(fn (Get $get): string => match (true) {
                        $get('section_key') === 'hero' => 'Hero: rasio 16:9, rekomendasi 1920 x 1080 piksel.',
                        in_array($get('section_key'), ['about', 'story'], true) => 'Profil/cerita: rasio 5:4, rekomendasi 1600 x 1280 piksel.',
                        default => 'Unggah hanya bila bagian ini memang menampilkan gambar.',
                    })
                    ->schema([
                        CmsImageUpload::make(
                            'image_path',
                            'Gambar bagian',
                            'cms/sections',
                            aspectRatio: '16:9',
                            recommendedSize: '1920 x 1080',
                            minWidth: 1600,
                            minHeight: 900,
                            validationRatio: null,
                        )
                            ->imageEditorAspectRatioOptions(['16:9', '5:4'])
                            ->helperText(fn (Get $get): string => $get('section_key') === 'hero'
                                ? 'Rasio 16:9. Rekomendasi 1920 x 1080 piksel, JPG/WebP maksimal 5 MB.'
                                : 'Rasio 5:4. Rekomendasi 1600 x 1280 piksel, JPG/WebP maksimal 5 MB.'),
                        CmsMediaPreview::make('image_path', altField: 'image_alt'),
                        TextInput::make('image_alt')
                            ->label('Deskripsi gambar')
                            ->placeholder('Jelaskan objek utama yang terlihat pada gambar')
                            ->maxLength(180),
                        CmsVideoUpload::make('video_path', 'Video latar', 'cms/sections/videos')
                            ->visible(fn (Get $get): bool => in_array($get('section_key'), ['hero', 'about', 'story'], true)),
                        CmsMediaPreview::make('video_path', 'video', 'video_alt'),
                        TextInput::make('video_alt')
                            ->label('Deskripsi video')
                            ->placeholder('Contoh: Proses produksi elemen GRC di workshop')
                            ->maxLength(180)
                            ->visible(fn (Get $get): bool => in_array($get('section_key'), ['hero', 'about', 'story'], true)),
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
