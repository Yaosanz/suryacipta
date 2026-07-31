<?php

namespace App\Filament\Resources\PageSections;

use App\Filament\Resources\PageSections\Pages\CreatePageSection;
use App\Filament\Resources\PageSections\Pages\EditPageSection;
use App\Filament\Resources\PageSections\Pages\ListPageSections;
use App\Filament\Resources\PageSections\Pages\ViewPageSection;
use App\Filament\Resources\PageSections\Schemas\PageSectionForm;
use App\Filament\Resources\PageSections\Tables\PageSectionsTable;
use App\Models\PageSection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PageSectionResource extends Resource
{
    protected static ?string $model = PageSection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;

    protected static string|\UnitEnum|null $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Bagian Halaman';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'bagian halaman';

    protected static ?string $pluralModelLabel = 'bagian halaman';

    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationBadge(): ?string
    {
        $count = PageSection::query()->where('is_enabled', false)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'gray';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Bagian halaman yang sedang disembunyikan';
    }

    public static function form(Schema $schema): Schema
    {
        return PageSectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PageSectionsTable::configure($table);
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
            'index' => ListPageSections::route('/'),
            'create' => CreatePageSection::route('/create'),
            'view' => ViewPageSection::route('/{record}'),
            'edit' => EditPageSection::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
