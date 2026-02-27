<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        $user = Filament::auth()->user();

        return $schema
            ->components([

                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur:true)
                    ->afterStateUpdated(fn ($state, callable $set) =>
                        $set('slug', Str::slug($state))
                    ),

                TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Textarea::make('excerpt')
                    ->required()
                    ->maxLength(500)
                    ->rows(3),

                RichEditor::make('content')
                    ->required()
                    ->columnSpanFull(),

                FileUpload::make('featured_image')
                    ->image()
                    ->directory('posts')
                    ->maxSize(2048),

                // ======================
                // CATEGORY
                // ======================
                Select::make('categories')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->preload()
                    ->required(),

                // ======================
                // TAG
                // ======================
                Select::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->preload(),

                // ======================
                // BREAKING NEWS
                // Hanya Admin & Super Admin
                // ======================
                Toggle::make('is_breaking_news')
                    ->visible(fn() =>
                        $user->can('post.publish')
                    )
                    ->default(false),
            ])
            ->columns(2);
    }
}