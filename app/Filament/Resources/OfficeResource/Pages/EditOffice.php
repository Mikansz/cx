<?php

namespace App\Filament\Resources\OfficeResource\Pages;

use App\Filament\Resources\OfficeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;

class EditOffice extends EditRecord
{
    use FooterScript;
    
    protected static string $resource = OfficeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Hapus'),
        ];
    }
    
    public function getHeadComponents(): array
    {
        return [
            // Add search script
        ];
    }
    
    // Footer is provided by the FooterScript trait
}
