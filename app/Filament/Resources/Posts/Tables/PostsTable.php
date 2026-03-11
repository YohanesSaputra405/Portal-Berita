<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Enums\PostStatus;
use App\Models\PostHistory;
use App\Services\PostService;
use Filament\Facades\Filament;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Carbon;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('10s') // auto refresh realtime
            ->columns([

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('user.name')
                    ->label('Penulis')
                    ->sortable(),

                TextColumn::make('categories.name')
                    ->label('Kategori')
                    ->badge()
                    ->separator(', '),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (PostStatus $state) => $state->label())
                    ->color(fn (PostStatus $state) => $state->color())
                    ->sortable(),

                TextColumn::make('scheduled_at')
                    ->label('Jadwal Terbit')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return $state
                            ? Carbon::parse($state)->format('d M Y H:i')
                            : '-';
                    })
                    ->badge()
                    ->color(function ($record) {
                        if (!$record->scheduled_at) {
                            return 'gray';
                        }

                        if (now()->lt($record->scheduled_at)) {
                            return 'warning';
                        }

                        if ($record->status !== PostStatus::Published) {
                            return 'danger';
                        }

                        return 'success';
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->since()
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Terbit')
                    ->since()
                    ->sortable(),
            ])

            ->filters([
                TrashedFilter::make()
                    ->label('Kotak Sampah'),

                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options(collect(PostStatus::cases())
                        ->mapWithKeys(fn ($case) => [
                            $case->value => $case->label(),
                        ])
                        ->toArray()
                    ),
            ])

            ->actions([

                ViewAction::make()
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->color('info'),

                EditAction::make()
                    ->label('Ubah')
                    ->icon('heroicon-o-pencil')
                    ->color('primary')
                    ->visible(fn ($record) =>
                        Filament::auth()->user()?->can('update', $record)
                    ),

                Action::make('submit')
                    ->label('Ajukan')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn ($record) =>
                        Filament::auth()->user()?->can('submit', $record)
                    )
                    ->action(fn ($record) =>
                        app(PostService::class)->changeStatus(
                            $record,
                            PostStatus::Pending,
                            Filament::auth()->user()
                        )
                    ),

                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->visible(fn ($record) =>
                        Filament::auth()->user()?->can('approve', $record)
                    )
                    ->action(fn ($record) =>
                        app(PostService::class)->changeStatus(
                            $record,
                            PostStatus::Approved,
                            Filament::auth()->user()
                        )
                    ),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->schema([
                        Textarea::make('note')
                            ->label('Alasan Penolakan')
                            ->placeholder('Jelaskan mengapa berita ini ditolak...')
                            ->required(),
                    ])
                    ->visible(fn ($record) =>
                        Filament::auth()->user()?->can('reject', $record)
                    )
                    ->action(function ($record, array $data) {

                        $oldStatus = $record->status;

                        $record->update([
                            'status' => PostStatus::Rejected,
                        ]);

                        PostHistory::create([
                            'post_id'    => $record->id,
                            'actor_id'   => Filament::auth()->id(),
                            'old_status' => $oldStatus->value,
                            'new_status' => PostStatus::Rejected->value,
                            'note'       => $data['note'],
                        ]);
                    }),

                Action::make('startReview')
                    ->label('Mulai Tinjau')
                    ->icon('heroicon-o-eye')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn ($record) =>
                        Filament::auth()->user()?->can('startReview', $record)
                    )
                    ->action(fn ($record) =>
                        app(PostService::class)->changeStatus(
                            $record,
                            PostStatus::InReview,
                            Filament::auth()->user()
                        )
                    ),

                Action::make('finish')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) =>
                        Filament::auth()->user()?->can('finish', $record)
                    )
                    ->action(fn ($record) =>
                        app(PostService::class)->changeStatus(
                            $record,
                            PostStatus::Finished,
                            Filament::auth()->user()
                        )
                    ),

                Action::make('publish')
                    ->label('Terbitkan')
                    ->icon('heroicon-o-globe-alt')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) =>
                        Filament::auth()->user()?->can('publish', $record)
                    )
                    ->action(fn ($record) =>
                        app(PostService::class)->changeStatus(
                            $record,
                            PostStatus::Published,
                            Filament::auth()->user()
                        )
                    ),

                Action::make('schedule')
                    ->label('Jadwalkan')
                    ->icon('heroicon-o-clock')
                    ->color('info')
                    ->schema([
                        DateTimePicker::make('scheduled_at')
                            ->label('Waktu Jadwal')
                            ->required()
                            ->minDate(Carbon::now()),
                    ])
                    ->visible(fn ($record) =>
                        $record
                        && Filament::auth()->user()?->can('schedule', $record)
                    )
                    ->action(function ($record, array $data) {
                        $record->update([
                            'scheduled_at' => $data['scheduled_at'],
                        ]);
                    }),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])

            ->defaultSort('created_at', 'desc');
    }
}