<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Models\Inquiry;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentInquiries extends TableWidget
{
    protected static ?int $sort = 40;

    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return auth()->user()?->role->canViewInquiries() ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Permintaan terbaru')
            ->description('Prioritaskan status Baru dan Prospek potensial.')
            ->query(Inquiry::query()->latest())
            ->columns([
                TextColumn::make('name')
                    ->label('Kontak')
                    ->description(fn (Inquiry $record): string => $record->company ?: $record->project_type)
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Masuk')
                    ->since()
                    ->dateTimeTooltip('d M Y H:i'),
            ])
            ->recordUrl(fn (Inquiry $record): string => InquiryResource::getUrl('edit', ['record' => $record]))
            ->paginated([5]);
    }
}
