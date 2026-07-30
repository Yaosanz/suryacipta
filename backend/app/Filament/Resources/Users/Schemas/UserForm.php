<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()->maxLength(100),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()->unique(ignoreRecord: true)->maxLength(255),
                TextInput::make('password')
                    ->password()
                    ->revealable(false)
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->rule(Password::min(12)->mixedCase()->letters()->numbers()->symbols())
                    ->helperText('Kosongkan saat edit untuk mempertahankan password.'),
                Select::make('role')
                    ->options(collect(UserRole::cases())->mapWithKeys(fn (UserRole $role) => [$role->value => $role->label()]))
                    ->default('editor')
                    ->disabled(fn (?User $record): bool => $record?->is(auth()->user()) ?? false)
                    ->required(),
                Toggle::make('is_active')
                    ->default(true)
                    ->disabled(fn (?User $record): bool => $record?->is(auth()->user()) ?? false),
                DateTimePicker::make('last_login_at')->disabled()->dehydrated(false),
            ]);
    }
}
