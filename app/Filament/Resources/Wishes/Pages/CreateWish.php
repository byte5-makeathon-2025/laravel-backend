<?php

namespace App\Filament\Resources\Wishes\Pages;

use App\Filament\Resources\Wishes\WishResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWish extends CreateRecord
{
    protected static string $resource = WishResource::class;
}
