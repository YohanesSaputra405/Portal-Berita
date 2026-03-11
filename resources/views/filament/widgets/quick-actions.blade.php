<x-filament-widgets::widget>
    <style>
        .quick-action-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .dark .quick-action-card {
            background: #0f172a;
            border-color: rgba(255,255,255,0.05);
            box-shadow: none;
        }
        .quick-action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #6366f1;
        }
    </style>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        @foreach($this->getActions() as $action)
            <a href="{{ $action['url'] }}" style="text-decoration: none; display: block; height: 100%;">
                <div class="quick-action-card" style="display: flex; flex-direction: column; height: 100%; padding: 1.5rem; border-radius: 1rem;">
                    {{-- Icon Container --}}
                    @php
                        $iconBg = match($action['color']) {
                            'primary' => '#4f46e5',
                            'warning' => '#f59e0b',
                            'info' => '#3b82f6',
                            'success' => '#10b981',
                            default => '#64748b',
                        };
                    @endphp
                    <div style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: {{ $iconBg }}; color: white; margin-bottom: 1.25rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <x-filament::icon :icon="$action['icon']" style="width: 24px; height: 24px;" />
                    </div>
                    
                    {{-- Content --}}
                    <div style="flex: 1;">
                        <h3 style="font-size: 1.25rem; font-weight: 800; color: inherit; margin: 0 0 0.5rem 0;">
                            {{ $action['label'] }}
                        </h3>
                        <p style="font-size: 0.875rem; font-weight: 500; color: #64748b; line-height: 1.5; margin: 0;">
                            {{ $action['description'] }}
                        </p>
                    </div>

                    {{-- Legend --}}
                    <div style="margin-top: 1.5rem; display: flex; align-items: center; gap: 4px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6366f1;">
                        <span>Mulai</span>
                        <x-filament::icon icon="heroicon-m-chevron-right" style="width: 14px; height: 14px;" />
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</x-filament-widgets::widget>
