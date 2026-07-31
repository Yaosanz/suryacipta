<?php

namespace App\Filament\Resources\Inquiries;

use App\Enums\InquiryStatus;
use App\Filament\Resources\Inquiries\Pages\EditInquiry;
use App\Filament\Resources\Inquiries\Pages\ListInquiries;
use App\Filament\Resources\Inquiries\Pages\ViewInquiry;
use App\Filament\Resources\Inquiries\Schemas\InquiryForm;
use App\Filament\Resources\Inquiries\Tables\InquiriesTable;
use App\Models\Inquiry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelopeOpen;

    protected static string|\UnitEnum|null $navigationGroup = 'Prospek';

    protected static ?string $navigationLabel = 'Permintaan Penawaran';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'permintaan penawaran';

    protected static ?string $pluralModelLabel = 'permintaan penawaran';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        $count = Inquiry::query()->where('status', InquiryStatus::New)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Permintaan baru yang belum ditindaklanjuti';
    }

    public static function form(Schema $schema): Schema
    {
        return InquiryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InquiriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInquiries::route('/'),
            'view' => ViewInquiry::route('/{record}'),
            'edit' => EditInquiry::route('/{record}/edit'),
        ];
    }
}
