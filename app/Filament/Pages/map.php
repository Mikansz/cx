<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class map extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map';
    
    protected static ?string $navigationLabel = 'Peta Lokasi';
    
    protected static ?string $title = 'Peta Lokasi Absensi';

    protected static string $view = 'filament.pages.map';
}
