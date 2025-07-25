<?php

namespace App\Filament\Resources\ProductColorResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProductColorResource;

class EditProductColor extends EditRecord
{
    protected static string $resource = ProductColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
