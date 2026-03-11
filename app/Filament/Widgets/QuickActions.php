<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Tags\TagResource;
use Filament\Widgets\Widget;

class QuickActions extends Widget
{
    protected string $view = 'filament.widgets.quick-actions';

    protected static ?int $sort = 0;

    protected int | string | array $columnSpan = 'full';

    public function getActions(): array
    {
        $user = auth()->user();
        $actions = [];

        if ($user->hasRole('reporter')) {
            $actions[] = [
                'label' => 'Tulis Berita Baru',
                'icon' => 'heroicon-o-pencil-square',
                'url' => PostResource::getUrl('create'),
                'color' => 'primary',
                'description' => 'Mulai draf artikel baru Anda.',
            ];
        }

        if ($user->hasRole('editor')) {
            $actions[] = [
                'label' => 'Tinjau Antrean Berita',
                'icon' => 'heroicon-o-magnifying-glass',
                'url' => PostResource::getUrl('index'),
                'color' => 'warning',
                'description' => 'Lihat berita yang menunggu persetujuan.',
            ];
        }

        if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
            $actions[] = [
                'label' => 'Kelola Kategori',
                'icon' => 'heroicon-o-folder',
                'url' => CategoryResource::getUrl('index'),
                'color' => 'info',
                'description' => 'Tambah atau ubah kategori berita.',
            ];
            $actions[] = [
                'label' => 'Kelola Tag',
                'icon' => 'heroicon-o-tag',
                'url' => TagResource::getUrl('index'),
                'color' => 'success',
                'description' => 'Organisir label berita dengan tag.',
            ];
        }

        return $actions;
    }
}
