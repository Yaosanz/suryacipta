<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('category')->maxLength(100),
                TextInput::make('question')
                    ->required()->maxLength(255)->columnSpanFull(),
                Textarea::make('answer')
                    ->required()->rows(5)
                    ->columnSpanFull(),
                Toggle::make('is_published')
                    ->default(false)
                    ->disabled(fn (): bool => ! auth()->user()->role->canPublish()),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->minValue(0)->maxValue(65535)->default(0),
            ]);
    }
}
