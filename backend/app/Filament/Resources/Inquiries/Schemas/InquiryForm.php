<?php

namespace App\Filament\Resources\Inquiries\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->disabled()->dehydrated(false),
                TextInput::make('company')->disabled()->dehydrated(false),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->disabled()->dehydrated(false),
                TextInput::make('phone')
                    ->tel()
                    ->disabled()->dehydrated(false),
                TextInput::make('project_type')
                    ->disabled()->dehydrated(false),
                TextInput::make('estimated_area')->disabled()->dehydrated(false),
                TextInput::make('budget_range')->disabled()->dehydrated(false),
                Textarea::make('message')
                    ->disabled()->dehydrated(false)->rows(8)
                    ->columnSpanFull(),
                TextInput::make('attachment_path')->disabled()->dehydrated(false),
                Select::make('status')
                    ->options([
                        'new' => 'Baru',
                        'contacted' => 'Sudah dihubungi',
                        'qualified' => 'Terkualifikasi',
                        'closed' => 'Selesai',
                        'spam' => 'Spam',
                    ])->required(),
                TextInput::make('source')->disabled()->dehydrated(false),
            ]);
    }
}
