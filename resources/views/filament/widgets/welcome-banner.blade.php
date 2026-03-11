@php
    $roleData = $this->getRoleData();
    $gradient = match($roleData['color']) {
        'primary' => 'linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%)',
        'danger' => 'linear-gradient(135deg, #e11d48 0%, #f43f5e 100%)',
        'warning' => 'linear-gradient(135deg, #f59e0b 0%, #ea580c 100%)',
        'success' => 'linear-gradient(135deg, #059669 0%, #10b981 100%)',
        default => 'linear-gradient(135deg, #334155 0%, #475569 100%)',
    };
@endphp

<x-filament-widgets::widget>
    <div style="background: {{ $gradient }}; border-radius: 1.5rem; padding: 2.5rem; position: relative; overflow: hidden; color: white; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);">
        {{-- Decorative background shapes --}}
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: white; opacity: 0.1; border-radius: 50%; blur: 40px;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>

        <div style="position: relative; z-index: 10; display: flex; flex-direction: row; align-items: center; gap: 2rem; flex-wrap: wrap;">
            {{-- Avatar Section --}}
            <div style="flex-shrink: 0;">
                <div style="padding: 4px; background: rgba(255,255,255,0.2); border-radius: 50%; backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.3); display: flex;">
                    <img 
                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=FFFFFF&background=6366f1&size=160" 
                        alt="{{ auth()->user()->name }}"
                        style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.4); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);"
                    >
                </div>
            </div>

            {{-- Content Section --}}
            <div style="flex: 1; min-width: 250px;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; flex-wrap: wrap;">
                    <div style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; background: rgba(0,0,0,0.2); padding: 4px 12px; border-radius: 9999px; display: flex; align-items: center; gap: 4px; border: 1px solid rgba(255,255,255,0.1);">
                        <x-filament::icon icon="heroicon-m-calendar" style="width: 14px; height: 14px;" />
                        {{ now()->translatedFormat('d F Y') }}
                    </div>
                    
                    <div style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 9999px; display: flex; align-items: center; gap: 4px; border: 1px solid rgba(255,255,255,0.1);">
                        <x-filament::icon icon="heroicon-m-user" style="width: 14px; height: 14px;" />
                        {{ $roleData['label'] }}
                    </div>
                </div>

                <h2 style="font-size: 2.25rem; font-weight: 900; line-height: 1; letter-spacing: -0.025em; margin: 0; color: white; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋
                </h2>
                
                <p style="margin-top: 0.75rem; font-size: 1.125rem; font-weight: 500; color: rgba(255,255,255,0.9); max-w: 600px; line-height: 1.5;">
                    Senang melihat Anda kembali. Mari buat konten berita yang informatif dan berkualitas hari ini.
                </p>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
