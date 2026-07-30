<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pertanyaan dan jawaban')
                    ->description('Gunakan bahasa yang biasa ditanyakan calon pelanggan.')
                    ->schema([
                        TextInput::make('category')
                            ->label('Kategori')
                            ->placeholder('Contoh: Pemesanan, Pemasangan, atau Perawatan')
                            ->maxLength(100),
                        TextInput::make('question')
                            ->label('Pertanyaan')
                            ->placeholder('Contoh: Apakah melayani pemasangan di luar Jabodetabek?')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('answer')
                            ->label('Jawaban')
                            ->placeholder('Berikan jawaban langsung, ringkas, dan mudah dipahami.')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
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
