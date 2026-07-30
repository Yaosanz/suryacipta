<?php

namespace App\Filament\Resources\MediaAssets\Schemas;

use App\Filament\Forms\CmsImageUpload;
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
                Section::make('Unggah gambar')
                    ->description('Simpan aset yang siap digunakan pada konten website.')
                    ->schema([
                        CmsImageUpload::make('file_path', 'Berkas gambar', 'cms/media')
                            ->required(),
                        TextInput::make('alt_text')
                            ->label('Deskripsi gambar')
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
