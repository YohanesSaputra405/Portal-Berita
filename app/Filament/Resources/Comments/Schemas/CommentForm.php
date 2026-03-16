<?php

namespace App\Filament\Resources\Comments\Schemas;

use App\Enums\CommentStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('post_id')
                    ->relationship('post', 'title')
                    ->required(),
                Select::make('user_id')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->required(),
                Select::make('parent_id')
                    ->relationship('parent', 'content')
                    ->searchable()
                    ->placeholder('None (Root Comment)'),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(CommentStatus::class)
                    ->default(CommentStatus::Approved->value)
                    ->required(),
            ]);
    }
}
