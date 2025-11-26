<?php

namespace App\Filament\Resources\Wishes\Schemas;

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
                    ->required()
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
                TextInput::make('name')
                    ->label('Wish Creator')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
