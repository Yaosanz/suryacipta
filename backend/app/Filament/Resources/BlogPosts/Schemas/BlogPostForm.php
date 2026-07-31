<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use App\Enums\PublicationStatus;
use App\Filament\Forms\CmsImageUpload;
use App\Filament\Forms\CmsMediaPreview;
use App\Filament\Forms\CmsVideoUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Isi artikel')
                    ->description('Tulis judul yang jelas, ringkasan singkat, lalu isi artikel secara lengkap.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul artikel')
                            ->placeholder('Contoh: Cara Memilih Panel GRC untuk Fasad')
                            ->required()
                            ->maxLength(180)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, Set $set, string $operation): void {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state ?? ''));
                                }
                            }),
                        TextInput::make('slug')
                            ->label('Alamat URL')
                            ->placeholder('cara-memilih-panel-grc')
                            ->helperText('Dibuat otomatis dari judul. Hindari mengubahnya setelah artikel diterbitkan.')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->alphaDash()
                            ->maxLength(160),
                        Textarea::make('excerpt')
                            ->label('Ringkasan artikel')
                            ->placeholder('Tuliskan inti artikel dalam 1-2 kalimat.')
                            ->required()
                            ->maxLength(500)
                            ->rows(3)
                            ->columnSpanFull(),
                        MarkdownEditor::make('content')
                            ->label('Isi artikel')
                            ->helperText('Gunakan heading untuk membagi pembahasan agar mudah dibaca.')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Media utama')
                    ->schema([
                        CmsImageUpload::make(
                            'featured_image_path',
                            'Foto artikel',
                            'cms/blog',
                            aspectRatio: '16:9',
                            recommendedSize: '1600 x 900',
                            minWidth: 1280,
                            minHeight: 720,
                            validationRatio: 16 / 9,
                        ),
                        CmsMediaPreview::make('featured_image_path', altField: 'featured_image_alt'),
                        TextInput::make('featured_image_alt')
                            ->label('Deskripsi gambar')
                            ->placeholder('Contoh: Pemasangan panel GRC pada fasad bangunan')
                            ->maxLength(180),
                        CmsVideoUpload::make('video_path', 'Video artikel', 'cms/blog/videos'),
                        CmsMediaPreview::make('video_path', 'video', 'video_alt'),
                        TextInput::make('video_alt')
                            ->label('Deskripsi video')
                            ->placeholder('Contoh: Penjelasan tahapan pemasangan panel GRC')
                            ->maxLength(180),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Penulis dan publikasi')
                    ->schema([
                        Select::make('author_id')
                            ->label('Penulis')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Gunakan pengguna yang sedang login'),
                        Select::make('status')
                            ->label('Status artikel')
                            ->options(PublicationStatus::class)
                            ->default(PublicationStatus::Draft)
                            ->required()
                            ->disabled(fn (): bool => ! (auth()->user()?->role->canPublish() ?? false)),
                        DateTimePicker::make('published_at')
                            ->label('Jadwal terbit')
                            ->helperText('Kosongkan untuk menerbitkan saat status diubah menjadi “Sudah terbit”.'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Optimasi mesin pencari')
                    ->description('Opsional. Kosongkan untuk menggunakan judul dan ringkasan artikel.')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('Judul SEO')
                            ->maxLength(60)
                            ->columnSpanFull(),
                        Textarea::make('seo_description')
                            ->label('Deskripsi SEO')
                            ->maxLength(160)
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsed()
                    ->columnSpanFull(),
            ]);
    }
}
