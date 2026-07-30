<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use App\Filament\Forms\CmsImageUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas perusahaan')->schema([
                    TextInput::make('company_name')
                        ->label('Nama resmi perusahaan')
                        ->placeholder('CV. Surya Cipta Estetica Mandiri')
                        ->required()
                        ->maxLength(180),
                    TextInput::make('short_name')
                        ->label('Nama singkat')
                        ->placeholder('Surya Cipta Estetica')
                        ->required()
                        ->maxLength(100),
                    TextInput::make('tagline')
                        ->label('Slogan perusahaan')
                        ->placeholder('Contoh: Spesialis GRC dari desain hingga terpasang')
                        ->required()
                        ->maxLength(200)
                        ->columnSpanFull(),
                    Textarea::make('description')
                        ->label('Deskripsi perusahaan')
                        ->placeholder('Jelaskan bidang usaha, pengalaman, area layanan, dan keunggulan perusahaan.')
                        ->required()
                        ->rows(5)
                        ->columnSpanFull(),
                    CmsImageUpload::make('logo_path', 'Logo perusahaan', 'cms/branding', 2048),
                    TextInput::make('logo_alt')
                        ->label('Deskripsi logo')
                        ->placeholder('Logo CV. Surya Cipta Estetica Mandiri')
                        ->maxLength(180),
                ])->description('Informasi ini digunakan pada header, footer, dan metadata website.')
                    ->columns(2)->columnSpanFull(),
                Section::make('Kontak')->schema([
                    TextInput::make('phone')
                        ->label('Nomor telepon')
                        ->placeholder('0812 8233 3021')
                        ->required()->tel()->maxLength(32),
                    TextInput::make('whatsapp')
                        ->label('Nomor WhatsApp')
                        ->placeholder('6281282333021')
                        ->helperText('Gunakan kode negara tanpa tanda +, spasi, atau tanda hubung.')
                        ->required()->maxLength(32),
                    TextInput::make('email')
                        ->label('Email perusahaan')
                        ->placeholder('info@perusahaan.co.id')
                        ->required()->email()->maxLength(255),
                    TextInput::make('business_hours')
                        ->label('Jam operasional')
                        ->placeholder('Senin - Sabtu, 08.00 - 17.00 WIB')
                        ->maxLength(180),
                ])->description('Kontak publik yang digunakan pada tombol telepon, WhatsApp, dan formulir.')
                    ->columns(2)->columnSpanFull(),
                Section::make('Alamat kantor')->schema([
                    TextInput::make('address.street')
                        ->label('Jalan dan nomor')
                        ->placeholder('Jl. Raya Bogor Km. 40,5, Pabuaran'),
                    TextInput::make('address.city')
                        ->label('Kota atau kecamatan')
                        ->placeholder('Cibinong'),
                    TextInput::make('address.regency')
                        ->label('Kabupaten')
                        ->placeholder('Bogor'),
                    TextInput::make('address.province')
                        ->label('Provinsi')
                        ->placeholder('Jawa Barat'),
                    TextInput::make('address.postalCode')
                        ->label('Kode pos')
                        ->placeholder('16914'),
                    TextInput::make('address.country')
                        ->label('Negara')
                        ->placeholder('Indonesia'),
                ])->columns(2)->columnSpanFull(),
                Section::make('Media sosial')->schema([
                    TextInput::make('social_links.instagram')
                        ->label('Instagram')
                        ->placeholder('https://instagram.com/namaakun')
                        ->url(),
                    TextInput::make('social_links.facebook')
                        ->label('Facebook')
                        ->placeholder('https://facebook.com/namaakun')
                        ->url(),
                    TextInput::make('social_links.youtube')
                        ->label('YouTube')
                        ->placeholder('https://youtube.com/@namaakun')
                        ->url(),
                    TextInput::make('social_links.linkedin')
                        ->label('LinkedIn')
                        ->placeholder('https://linkedin.com/company/nama-perusahaan')
                        ->url(),
                ])->description('Opsional. Gunakan alamat lengkap HTTPS.')
                    ->columns(2)->collapsed()->columnSpanFull(),
                Section::make('Statistik')->schema([
                    Repeater::make('statistics')
                        ->label('Angka pencapaian')
                        ->helperText('Contoh: 25+ Tahun pengalaman atau 200+ Proyek selesai.')
                        ->schema([
                            TextInput::make('value')
                                ->label('Angka')
                                ->placeholder('25+')
                                ->required()->maxLength(24),
                            TextInput::make('label')
                                ->label('Keterangan')
                                ->placeholder('Tahun pengalaman')
                                ->required()->maxLength(100),
                        ])
                        ->addActionLabel('Tambah statistik')
                        ->columns(2)->maxItems(8)->columnSpanFull(),
                ])->description('Tampilkan angka yang dapat dibuktikan dan relevan bagi calon pelanggan.')
                    ->columnSpanFull(),
                Section::make('Optimasi mesin pencari')->schema([
                    TextInput::make('default_seo_title')
                        ->label('Judul utama SEO')
                        ->placeholder('Surya Cipta Estetica - Spesialis GRC Indonesia')
                        ->helperText('Maksimal 60 karakter.')
                        ->maxLength(60)->columnSpanFull(),
                    Textarea::make('default_seo_description')
                        ->label('Deskripsi utama SEO')
                        ->placeholder('Jelaskan layanan utama dan area layanan perusahaan secara ringkas.')
                        ->helperText('Maksimal 160 karakter.')
                        ->maxLength(160)->rows(3)->columnSpanFull(),
                ])->collapsed()->columnSpanFull(),
            ]);
    }
}
