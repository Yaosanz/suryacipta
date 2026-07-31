<?php

namespace App\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Spatie\Activitylog\Models\Activity;

class RecentActivity extends TableWidget
{
    protected static ?int $sort = 50;

    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return auth()->user()?->role->canManageUsers() ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Aktivitas terbaru')
            ->description('Jejak perubahan penting untuk kebutuhan audit.')
            ->query(Activity::query()->with('causer')->latest())
            ->columns([
                TextColumn::make('subject_type')
                    ->label('Data')
                    ->formatStateUsing(fn (?string $state): string => match (class_basename($state ?? '-')) {
                        'BlogPost' => 'Artikel',
                        'Faq' => 'FAQ',
                        'Inquiry' => 'Permintaan',
                        'MediaAsset' => 'Media',
                        'PageSection' => 'Bagian halaman',
                        'PortfolioProject' => 'Portofolio',
                        'Product' => 'Produk',
                        'SiteSetting' => 'Profil website',
                        'Testimonial' => 'Testimoni',
                        'TeamMember' => 'Anggota tim',
                        'User' => 'Pengguna',
                        default => class_basename($state ?? '-'),
                    }),
                TextColumn::make('event')
                    ->label('Aksi')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'created' => 'Dibuat',
                        'updated' => 'Diubah',
                        'deleted' => 'Dihapus',
                        'restored' => 'Dipulihkan',
                        default => ucfirst($state ?? '-'),
                    }),
                TextColumn::make('causer.name')
                    ->label('Oleh')
                    ->placeholder('Sistem'),
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->since()
                    ->dateTimeTooltip('d M Y H:i'),
            ])
            ->paginated([5]);
    }
}
