<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi pelanggan')
                    ->description('Pastikan pelanggan telah menyetujui testimoni untuk ditampilkan.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama pelanggan')
                            ->placeholder('Contoh: Budi Santoso')
                            ->required()
                            ->maxLength(160),
                        TextInput::make('company_role')
                            ->label('Jabatan atau perusahaan')
                            ->placeholder('Contoh: Manajer Proyek, PT Contoh')
                            ->maxLength(180),
                        Textarea::make('quote')
                            ->label('Isi testimoni')
                            ->placeholder('Tuliskan pengalaman pelanggan menggunakan produk atau layanan perusahaan.')
                            ->required()
                            ->maxLength(1000)
                            ->rows(5)
                            ->columnSpanFull(),
                        TextInput::make('rating')
                            ->label('Penilaian')
                            ->helperText('Gunakan angka 1 sampai 5.')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->default(5),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Penayangan')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Tampilkan di website')
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
