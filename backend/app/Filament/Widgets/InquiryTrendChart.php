<?php

namespace App\Filament\Widgets;

use App\Models\Inquiry;
use Filament\Widgets\ChartWidget;

class InquiryTrendChart extends ChartWidget
{
    protected static ?int $sort = 20;

    protected ?string $heading = 'Tren permintaan penawaran';

    protected ?string $description = 'Jumlah formulir yang masuk dari landing page.';

    protected string $color = 'primary';

    protected ?string $maxHeight = '280px';

    protected bool $isCollapsible = true;

    public ?string $filter = '30';

    public static function canView(): bool
    {
        return auth()->user()?->role->canViewInquiries() ?? false;
    }

    protected function getFilters(): ?array
    {
        return [
            '7' => '7 hari',
            '30' => '30 hari',
            '90' => '90 hari',
        ];
    }

    protected function getData(): array
    {
        $days = max(7, min(90, (int) $this->filter));
        $dates = collect(range($days - 1, 0))
            ->map(fn (int $offset) => today()->subDays($offset));

        return [
            'datasets' => [[
                'label' => 'Permintaan masuk',
                'data' => $dates
                    ->map(fn ($date): int => Inquiry::query()->whereDate('created_at', $date)->count())
                    ->all(),
                'borderColor' => '#0f766e',
                'backgroundColor' => 'rgba(15, 118, 110, 0.14)',
                'fill' => true,
                'tension' => 0.35,
            ]],
            'labels' => $dates->map(fn ($date): string => $date->format('d M'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
