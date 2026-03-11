<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\User;
use App\Enums\PostStatus;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = -5;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return [
                Stat::make('Total Berita', Post::count())
                    ->description('Seluruh berita di sistem')
                    ->descriptionIcon('heroicon-m-document-text')
                    ->color('primary'),
                Stat::make('Total Penulis', User::role('reporter')->count())
                    ->description('Reporter aktif')
                    ->descriptionIcon('heroicon-m-users')
                    ->color('success'),
                Stat::make('Berita Terbit', Post::where('status', PostStatus::Published)->count())
                    ->description('Sudah tayang ke publik')
                    ->descriptionIcon('heroicon-m-globe-alt')
                    ->color('info'),
            ];
        }

        if ($user->hasRole('editor')) {
            return [
                Stat::make('Antrean Tinjauan', Post::where('status', PostStatus::Pending)->count())
                    ->description('Perlu segera ditinjau')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
                Stat::make('Berita Disetujui', Post::where('status', PostStatus::Approved)->count())
                    ->description('Siap untuk diselesaikan')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('success'),
                Stat::make('Total Artikel', Post::count())
                    ->description('Keseluruhan artikel')
                    ->descriptionIcon('heroicon-m-document-duplicate')
                    ->color('gray'),
            ];
        }

        if ($user->hasRole('reporter')) {
            return [
                Stat::make('Berita Saya', Post::where('user_id', $user->id)->count())
                    ->description('Total karya Anda')
                    ->descriptionIcon('heroicon-m-pencil-square')
                    ->color('primary'),
                Stat::make('Sudah Terbit', Post::where('user_id', $user->id)->where('status', PostStatus::Published)->count())
                    ->description('Berita Anda yang tayang')
                    ->descriptionIcon('heroicon-m-check-badge')
                    ->color('success'),
                Stat::make('Ditolak / Perlu Revisi', Post::where('user_id', $user->id)->where('status', PostStatus::Rejected)->count())
                    ->description('Memerlukan perhatian')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('danger'),
            ];
        }

        return [];
    }
}
