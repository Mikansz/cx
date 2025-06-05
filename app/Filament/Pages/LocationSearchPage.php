<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class LocationSearchPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    
    protected static ?string $navigationLabel = 'Pencarian Lokasi';
    
    protected static ?string $title = 'Pencarian Lokasi';
    
    protected static ?string $navigationGroup = 'Manajemen Lokasi';
    
    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.location-search';

    public static function canAccess(): bool
    {
        return Auth::user()->hasAnyRole(['super_admin', 'hrd', 'cfo']);
    }

    public function getTitle(): string
    {
        return 'Pencarian Lokasi';
    }

    public function getSubheading(): ?string
    {
        return 'Cari dan temukan lokasi dengan mudah menggunakan peta interaktif';
    }
}
