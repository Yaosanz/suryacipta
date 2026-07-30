<?php

namespace App\Filament\Resources\PortfolioProjects\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PortfolioProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required()->unique(ignoreRecord: true)->alphaDash()->maxLength(160),
                TextInput::make('title')
                    ->required()->maxLength(160),
                TextInput::make('client_name')->maxLength(160),
                TextInput::make('location')
                    ->required()->maxLength(160),
                TextInput::make('category')
                    ->required()->maxLength(100),
                TextInput::make('year')
                    ->numeric()->minValue(1900)->maxValue((int) date('Y') + 2),
                Textarea::make('description')
                    ->required()->maxLength(1000)->rows(5)
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->label('Gambar proyek')
                    ->disk('public')->directory('cms/portfolio')->visibility('public')
                    ->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)->imageEditor()->orientImagesFromExif()
                    ->preventFilePathTampering(),
                TextInput::make('image_alt')
                    ->label('Teks alternatif gambar')->maxLength(180),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_featured')
                    ->default(false),
                Toggle::make('is_published')
                    ->default(false)
                    ->disabled(fn (): bool => ! auth()->user()->role->canPublish()),
                DateTimePicker::make('published_at'),
            ]);
    }
}
