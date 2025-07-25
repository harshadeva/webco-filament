<?php

namespace App\Filament\Resources\ProductColorResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ProductColorResource;

class CreateProductColor extends CreateRecord
{
    protected static string $resource = ProductColorResource::class;
}
