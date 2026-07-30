<?php

namespace App\Filament\Resources\MediaAssets\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MediaAssetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('disk')->default('public'),
                FileUpload::make('file_path')
                    ->label('Berkas gambar')->required()
                    ->disk('public')->directory('cms/media')->visibility('public')
                    ->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)->imageEditor()->orientImagesFromExif()
                    ->preventFilePathTampering(),
                TextInput::make('alt_text')
                    ->label('Teks alternatif')->required()->maxLength(180),
                Textarea::make('caption')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }
}
