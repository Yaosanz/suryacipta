<?php

namespace App\Filament\Resources\MediaAssets\Schemas;

use App\Filament\Forms\CmsMediaPreview;
use App\Filament\Forms\CmsMediaUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MediaAssetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('disk')->default('public'),
                Section::make('Unggah media')
                    ->description('Simpan aset yang siap digunakan pada konten website.')
                    ->schema([
                        CmsMediaUpload::make('file_path', 'Berkas gambar atau video', 'cms/media')
                            ->required(),
                        CmsMediaPreview::make('file_path', altField: 'alt_text', mimeField: 'mime_type'),
                        TextInput::make('alt_text')
                            ->label('Deskripsi media')
                            ->placeholder('Contoh: Ornamen GRC bermotif geometris warna putih')
                            ->helperText('Deskripsi singkat ini membantu aksesibilitas dan SEO.')
                            ->required()
                            ->maxLength(180),
                        Textarea::make('caption')
                            ->label('Keterangan tambahan')
                            ->placeholder('Opsional: sumber foto atau keterangan penggunaan.')
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
