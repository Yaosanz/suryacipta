<?php

namespace App\Filament\Pages\Auth;

use App\Rules\ValidCmsImage;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    protected static ?string $title = 'Profil akun';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Foto profil')
                    ->description('Foto membantu tim mengenali pengguna yang melakukan perubahan konten.')
                    ->schema([
                        FileUpload::make('avatar_path')
                            ->label('Foto pengguna')
                            ->avatar()
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('cms/avatars')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->rules([new ValidCmsImage(600, 600, 1, '1:1')])
                            ->maxSize(2048)
                            ->imagePreviewHeight('200')
                            ->panelLayout('integrated')
                            ->openable()
                            ->downloadable()
                            ->helperText('Rasio 1:1, ideal 800 x 800 piksel. JPG, PNG, atau WebP maksimal 2 MB.'),
                    ])
                    ->columnSpanFull(),
                Section::make('Informasi akun')
                    ->description('Nama tampil pada riwayat aktivitas. Perubahan email atau kata sandi memerlukan verifikasi.')
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getCurrentPasswordFormComponent(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
