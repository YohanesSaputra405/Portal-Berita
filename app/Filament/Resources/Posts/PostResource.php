<?php

namespace App\Filament\Resources\Posts;

use App\Enums\PostStatus;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Resources\Posts\RelationManagers\PostsRelationManager;
use App\Filament\Resources\Posts\Schemas\PostForm;
use App\Filament\Resources\Posts\Tables\PostsTable;
use App\Models\Post;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    protected static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Filament::auth()->id();

        return $data;
    }

    public static function canViewAny(): bool
    {
        return Filament::auth()->user()?->can('viewAny', Post::class) ?? false;
    }

    public static function canCreate(): bool
    {
        return Filament::auth()->user()?->can('create', Post::class) ?? false;
    }

    public static function canEdit($record): bool
    {
        return Filament::auth()->user()?->can('update', $record) ?? false;
    }

    public static function canDelete($record): bool
    {
        return Filament::auth()->user()?->can('delete', $record) ?? false;
    }

    // Filter data berdasarkan role
    public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery()
        ->with(['author', 'categories']);

    $user = Filament::auth()->user();

    if (! $user) {
        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | REPORTER
    |--------------------------------------------------------------------------
    | Melihat hanya artikel miliknya (semua status)
    */
    if ($user->hasRole('reporter')) {
        return $query->where('user_id', $user->id);
    }

    /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN
    |--------------------------------------------------------------------------
    | Melihat hanya artikel Pending
    */
    if ($user->hasRole('super_admin')) {
        return $query->whereIn('status', [
            PostStatus::Pending,
            PostStatus::Approved,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | EDITOR
    |--------------------------------------------------------------------------
    | Melihat hanya artikel Approved
    */
    if ($user->hasRole('editor')) {
        return $query->whereIn('status', [
            PostStatus::Approved,
            PostStatus::InReview,
            PostStatus::Finished,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    | Melihat artikel yang siap publish atau sudah publish
    */
    if ($user->hasRole('admin')) {
        return $query->whereIn('status', [
            PostStatus::Finished,
            PostStatus::Published,
        ]);
    }

    return $query;
}

    public static function form(Schema $schema): Schema
    {
        return PostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PostsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function shouldRegisterNavigation(): bool
{
    return Filament::auth()->user()?->can('post.view') ?? false;
}
}
