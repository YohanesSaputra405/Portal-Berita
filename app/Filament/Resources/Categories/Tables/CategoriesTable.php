<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable(),

                TextColumn::make('posts_count')
                    ->counts('posts')
                    ->label('Jumlah Artikel'),

                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                ->visible(fn () =>
                        Filament::auth()->user()?->can('category.update')
                    ),

                DeleteAction::make()
                    ->visible(fn () =>
                        Filament::auth()->user()?->can('category.delete')
                    )
                    ->before(function ($record) {

                        if ($record->posts()->count() > 0) {

                            Notification::make()
                                ->title('Tidak bisa menghapus kategori')
                                ->body('Kategori masih digunakan oleh artikel.')
                                ->danger()
                                ->send();

                            return false; // Stop delete
                        }
                    }),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () =>
                            Filament::auth()->user()?->can('category.delete')
                        )
                        ->before(function ($records){
                            foreach ($records as $record) {
                                if ($record->post()->count() > 0) {

                                Notification::make()
                                        ->title('Gagal menghapus')
                                        ->body('Salah satu kategori masih dipakai artikel.')
                                        ->danger()
                                        ->send();

                                    return false;
                                }
                            }
                        }),
                ]),
            ]);
    }
}
