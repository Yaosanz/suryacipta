<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard operasional';

    public function getSubheading(): string|Htmlable|null
    {
        return 'Pantau prospek, kesiapan konten, dan aktivitas pengelolaan website dalam satu tampilan.';
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'xl' => 2,
        ];
    }
}
