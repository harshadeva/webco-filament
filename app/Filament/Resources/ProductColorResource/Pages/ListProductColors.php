<?php

namespace App\Filament\Resources\ProductColorResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProductColorResource;

class ListProductColors extends ListRecords
{
    protected static string $resource = ProductColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
