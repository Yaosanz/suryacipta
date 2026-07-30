<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas perusahaan')->schema([
                    TextInput::make('company_name')->required()->maxLength(180),
                    TextInput::make('short_name')->required()->maxLength(100),
                    TextInput::make('tagline')->required()->maxLength(200)->columnSpanFull(),
                    Textarea::make('description')->required()->rows(5)->columnSpanFull(),
                    FileUpload::make('logo_path')
                        ->disk('public')->directory('cms/branding')->visibility('public')
                        ->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(2048)->imageEditor()->preventFilePathTampering(),
                    TextInput::make('logo_alt')->maxLength(180),
                ])->columns(2)->columnSpanFull(),
                Section::make('Kontak')->schema([
                    TextInput::make('phone')->required()->tel()->maxLength(32),
                    TextInput::make('whatsapp')->required()->maxLength(32),
                    TextInput::make('email')->required()->email()->maxLength(255),
                    TextInput::make('business_hours')->maxLength(180),
                    KeyValue::make('address')->keyLabel('Bagian')->valueLabel('Alamat')->columnSpanFull(),
                    KeyValue::make('social_links')->keyLabel('Platform')->valueLabel('URL')->columnSpanFull(),
                ])->columns(2)->columnSpanFull(),
                Section::make('Statistik')->schema([
                    Repeater::make('statistics')->schema([
                        TextInput::make('value')->required()->maxLength(24),
                        TextInput::make('label')->required()->maxLength(100),
                    ])->columns(2)->maxItems(8)->columnSpanFull(),
                ])->columnSpanFull(),
                Section::make('SEO bawaan')->schema([
                    TextInput::make('default_seo_title')->maxLength(60)->columnSpanFull(),
                    Textarea::make('default_seo_description')->maxLength(160)->rows(3)->columnSpanFull(),
                ])->columnSpanFull(),
            ]);
    }
}
