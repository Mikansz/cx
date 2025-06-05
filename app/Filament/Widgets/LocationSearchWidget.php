<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class LocationSearchWidget extends Widget
{
    protected static string $view = 'filament.widgets.location-search-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 10;

    public static function canView(): bool
    {
        return Auth::user()->hasAnyRole(['super_admin', 'hrd', 'cfo']);
    }

    public function getHeading(): ?string
    {
        return 'Pencarian Lokasi Cepat';
    }
}
