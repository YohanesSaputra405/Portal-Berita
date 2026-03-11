<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeBanner extends Widget
{
    protected string $view = 'filament.widgets.welcome-banner';
    
    protected static ?int $sort = -10;

    protected int | string | array $columnSpan = 'full';

    public function getRoleData(): array
    {
        $role = auth()->user()->getRoleNames()->first();

        return match ($role) {
            'super_admin' => ['label' => 'Super Admin', 'color' => 'danger'],
            'admin' => ['label' => 'Admin', 'color' => 'primary'],
            'editor' => ['label' => 'Editor', 'color' => 'warning'],
            'reporter' => ['label' => 'Reporter', 'color' => 'success'],
            default => ['label' => 'User', 'color' => 'gray'],
        };
    }
}
