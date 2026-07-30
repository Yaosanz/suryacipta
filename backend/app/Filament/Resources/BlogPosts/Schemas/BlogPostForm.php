<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use App\Enums\PublicationStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required()->unique(ignoreRecord: true)->alphaDash()->maxLength(160),
                TextInput::make('title')
                    ->required()->maxLength(180),
                Textarea::make('excerpt')
                    ->required()->maxLength(500)->rows(3)
                    ->columnSpanFull(),
                MarkdownEditor::make('content')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('featured_image_path')
                    ->label('Gambar utama')
                    ->disk('public')->directory('cms/blog')->visibility('public')
                    ->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)->imageEditor()->orientImagesFromExif()
                    ->preventFilePathTampering(),
                TextInput::make('featured_image_alt')
                    ->label('Teks alternatif gambar')->maxLength(180),
                Select::make('author_id')
                    ->relationship('author', 'name'),
                Select::make('status')
                    ->options(PublicationStatus::class)
                    ->default('draft')
                    ->required()
                    ->disabled(fn (): bool => ! auth()->user()->role->canPublish()),
                DateTimePicker::make('published_at'),
                TextInput::make('seo_title')->maxLength(60),
                Textarea::make('seo_description')
                    ->maxLength(160)->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
