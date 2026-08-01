<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use App\Models\User;
use App\Rules\ValidCmsImage;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas pengguna')
                    ->schema([
                        FileUpload::make('avatar_path')
                            ->label('Foto profil')
                            ->avatar()
                            ->image()
                            ->disk('public')
                            ->directory('cms/avatars')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->rules([new ValidCmsImage(600, 600, 1, '1:1')])
                            ->maxSize(2048)
                            ->imagePreviewHeight('200')
                            ->panelLayout('integrated')
                            ->imageEditor()
                            ->openable()
                            ->downloadable()
                            ->helperText('Rasio 1:1, ideal 800 x 800 piksel. Maksimal 2 MB.')
                            ->columnSpanFull(),
                        TextInput::make('name')
                            ->label('Nama lengkap')
                            ->placeholder('Nama pengguna CMS')
                            ->required()->maxLength(100),
                        TextInput::make('email')
                            ->label('Alamat email')
                            ->placeholder('nama@perusahaan.co.id')
                            ->email()
                            ->required()->unique(ignoreRecord: true)->maxLength(255),
                        TextInput::make('password')
                            ->label('Kata sandi sementara')
                            ->placeholder('Minimal 12 karakter')
                            ->password()
                            ->revealable(false)
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->rule(Password::min(12)->mixedCase()->letters()->numbers()->symbols())
                            ->helperText('Wajib memakai huruf besar, huruf kecil, angka, dan simbol. Kosongkan saat edit jika tidak ingin mengganti.'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Akses CMS')
                    ->description('Berikan akses minimum sesuai tugas pengguna.')
                    ->schema([
                        Select::make('role')
                            ->label('Peran')
                            ->options(collect(UserRole::cases())->mapWithKeys(fn (UserRole $role) => [$role->value => $role->label()]))
                            ->helperText('Editor mengelola draf. Content Manager dapat menerbitkan. Super Admin juga mengelola pengguna dan audit.')
                            ->default(UserRole::Editor)
                            ->disabled(fn (?User $record): bool => $record?->is(auth()->user()) ?? false)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Akun aktif')
                            ->helperText('Nonaktifkan untuk segera mencabut akses login.')
                            ->default(true)
                            ->disabled(fn (?User $record): bool => $record?->is(auth()->user()) ?? false),
                        DateTimePicker::make('last_login_at')
                            ->label('Login terakhir')
                            ->placeholder('Belum pernah login')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
