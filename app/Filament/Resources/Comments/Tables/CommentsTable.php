<?php

namespace App\Filament\Resources\Comments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Enums\CommentStatus;
use Filament\Tables\Table;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('author.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('content')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (CommentStatus $state): string => match ($state) {
                        CommentStatus::Pending => 'warning',
                        CommentStatus::Approved => 'success',
                        CommentStatus::Rejected => 'danger',
                    })
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(CommentStatus::class),
                SelectFilter::make('post')
                    ->relationship('post', 'title'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
