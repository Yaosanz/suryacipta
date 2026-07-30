<?php

namespace App\Filament\Resources\PageSections\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PageSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('page')
                    ->required()->alphaDash()->maxLength(64)
                    ->default('home'),
                TextInput::make('section_key')
                    ->required()->alphaDash()->maxLength(64),
                TextInput::make('eyebrow')->maxLength(100),
                TextInput::make('title')
                    ->required()->maxLength(200)->columnSpanFull(),
                Textarea::make('description')
                    ->rows(4)
                    ->columnSpanFull(),
                KeyValue::make('content')
                    ->label('Konten tambahan')
                    ->keyLabel('Kunci')->valueLabel('Nilai')
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->label('Gambar section')
                    ->disk('public')->directory('cms/sections')->visibility('public')
                    ->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)->imageEditor()->orientImagesFromExif()
                    ->preventFilePathTampering(),
                TextInput::make('image_alt')->label('Teks alternatif gambar')->maxLength(180),
                TextInput::make('primary_cta_label')->maxLength(80),
                TextInput::make('primary_cta_url')
                    ->maxLength(255),
                Toggle::make('is_enabled')
                    ->default(true)
                    ->disabled(fn (): bool => ! auth()->user()->role->canPublish()),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()->minValue(0)->maxValue(65535)
                    ->default(0),
            ]);
    }
}
