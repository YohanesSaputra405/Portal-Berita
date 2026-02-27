<?php

namespace App\Filament\Resources\Posts\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'histories';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('actor.name')->label('Actor'),
                TextColumn::make('old_status'),
                TextColumn::make('new_status'),
                TextColumn::make('note')->limit(50),
                TextColumn::make('created_at')->since(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}