<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function handleRecordCreation(array $data): Post
    {
        $data['user_id'] = Filament::auth()->id();

        return Post::create($data);
    }
}
