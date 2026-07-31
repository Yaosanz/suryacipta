<?php

namespace App\Filament\Resources\Inquiries\Schemas;

use App\Enums\InquiryStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kontak calon pelanggan')
                    ->description('Data ini berasal dari formulir website dan sengaja tidak dapat diubah.')
                    ->schema([
                        TextInput::make('name')->label('Nama')->disabled()->dehydrated(false),
                        TextInput::make('company')->label('Perusahaan')->placeholder('Tidak diisi')->disabled()->dehydrated(false),
                        TextInput::make('email')->label('Email')->email()->disabled()->dehydrated(false),
                        TextInput::make('phone')->label('Nomor telepon')->tel()->disabled()->dehydrated(false),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Kebutuhan proyek')
                    ->schema([
                        TextInput::make('project_type')->label('Jenis proyek')->disabled()->dehydrated(false),
                        TextInput::make('estimated_area')->label('Perkiraan luas')->placeholder('Tidak diisi')->disabled()->dehydrated(false),
                        TextInput::make('budget_range')->label('Kisaran anggaran')->placeholder('Tidak diisi')->disabled()->dehydrated(false),
                        Textarea::make('message')
                            ->label('Pesan')
                            ->disabled()
                            ->dehydrated(false)
                            ->rows(8)
                            ->columnSpanFull(),
                        TextInput::make('attachment_path')
                            ->label('Lampiran')
                            ->placeholder('Tidak ada lampiran')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Tindak lanjut')
                    ->description('Perbarui status agar tim mengetahui progres penanganan permintaan ini.')
                    ->schema([
                        Select::make('status')
                            ->label('Status penanganan')
                            ->options(InquiryStatus::class)
                            ->required(),
                        TextInput::make('handledBy.name')
                            ->label('Ditangani oleh')
                            ->placeholder('Belum ditangani')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('source')
                            ->label('Sumber')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
