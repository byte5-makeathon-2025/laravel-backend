<?php

namespace App\Filament\Resources\Wishes\Schemas;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WishForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->rows(4),
                Select::make('priority')
                    ->options([
                        'high' => 'High',
                        'medium' => 'Medium',
                        'low' => 'Low',
                    ])
                    ->default('medium')
                    ->required(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'granted' => 'Granted',
                        'denied' => 'Denied',
                    ])
                    ->default('pending')
                    ->required(),
                Fieldset::make('Product Information')
                    ->schema([
                        TextInput::make('product_name')
                            ->label('Product Name')
                            ->maxLength(500),
                        TextInput::make('product_sku')
                            ->label('Product SKU')
                            ->maxLength(50),
                        Placeholder::make('product_image_preview')
                            ->label('Product Image')
                            ->content(fn ($record) => $record?->product_image
                                ? view('filament.components.product-image', ['url' => $record->product_image])
                                : 'No image available'),
                        TextInput::make('product_image')
                            ->label('Product Image URL')
                            ->url()
                            ->maxLength(1000),
                        TextInput::make('product_weight')
                            ->label('Weight (kg)')
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('product_price')
                            ->label('Price (USD)')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0),
                    ]),
                Fieldset::make('Wish Creator')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('street')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('house_number')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('postal_code')
                            ->required()
                            ->maxLength(20),
                        TextInput::make('city')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('country')
                            ->required()
                            ->maxLength(100),
                    ]),
            ]);
    }
}
