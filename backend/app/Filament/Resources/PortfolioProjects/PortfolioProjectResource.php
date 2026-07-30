<?php

namespace App\Filament\Resources\PortfolioProjects;

use App\Filament\Resources\PortfolioProjects\Pages\CreatePortfolioProject;
use App\Filament\Resources\PortfolioProjects\Pages\EditPortfolioProject;
use App\Filament\Resources\PortfolioProjects\Pages\ListPortfolioProjects;
use App\Filament\Resources\PortfolioProjects\Schemas\PortfolioProjectForm;
use App\Filament\Resources\PortfolioProjects\Tables\PortfolioProjectsTable;
use App\Models\PortfolioProject;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PortfolioProjectResource extends Resource
{
    protected static ?string $model = PortfolioProject::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static string|\UnitEnum|null $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Portofolio';

    protected static ?int $navigationSort = 30;

    protected static ?string $modelLabel = 'proyek';

    protected static ?string $pluralModelLabel = 'portofolio';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return PortfolioProjectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PortfolioProjectsTable::configure($table);
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
            'index' => ListPortfolioProjects::route('/'),
            'create' => CreatePortfolioProject::route('/create'),
            'edit' => EditPortfolioProject::route('/{record}/edit'),
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
