<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required()->unique(ignoreRecord: true)->alphaDash()->maxLength(160),
                TextInput::make('name')
                    ->required()->maxLength(160),
                TextInput::make('category')
                    ->required()->maxLength(100),
                Textarea::make('short_description')
                    ->required()->maxLength(500)->rows(3)
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->rows(6)
                    ->columnSpanFull(),
                TagsInput::make('features')
                    ->placeholder('Tambahkan keunggulan')
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->label('Gambar produk')
                    ->disk('public')->directory('cms/products')->visibility('public')
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
                TextInput::make('seo_title')->maxLength(60),
                Textarea::make('seo_description')
                    ->maxLength(160)->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
