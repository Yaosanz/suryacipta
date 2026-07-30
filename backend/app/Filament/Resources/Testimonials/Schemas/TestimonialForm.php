<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()->maxLength(160),
                TextInput::make('company_role')->maxLength(180),
                Textarea::make('quote')
                    ->required()->maxLength(1000)->rows(5)
                    ->columnSpanFull(),
                TextInput::make('rating')
                    ->required()
                    ->numeric()->minValue(1)->maxValue(5)
                    ->default(5),
                Toggle::make('is_published')
                    ->default(false)
                    ->disabled(fn (): bool => ! auth()->user()->role->canPublish()),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()->minValue(0)->maxValue(65535)
                    ->default(0),
            ]);
    }
}
