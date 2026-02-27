<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use App\Enums\PostStatus;

class PostPolicy
{
    /*
    |--------------------------------------------------------------------------
    | BASIC PERMISSIONS
    |--------------------------------------------------------------------------
    */

    public function viewAny(User $user): bool
    {
        return $user->can('post.view');
    }

    public function view(User $user, Post $post): bool
    {
        return $user->can('post.view');
    }

    public function create(User $user): bool
    {
        return $user->can('post.create');
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->can('post.delete');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE RULES
    |--------------------------------------------------------------------------
    */

    public function update(User $user, Post $post): bool
    {
        if ($post->status === PostStatus::Published) {
            return false;
        }

        if ($user->hasRole(['super_admin', 'admin'])) {
            return false;
        }

        if ($user->hasRole('user')) {
            return $post->status === PostStatus::Revision
                && $post->user_id === $user->id;
        }

        if ($user->hasRole('reporter')) {
            return $post->status === PostStatus::Draft
                && $post->user_id === $user->id;
        }

        if ($user->hasRole('editor')) {
            return $post->status === PostStatus::InReview;
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | WORKFLOW PERMISSIONS
    |--------------------------------------------------------------------------
    */

    public function submit(User $user, Post $post): bool
    {
        return $user->hasRole('reporter')
            && $post->status === PostStatus::Draft
            && $post->user_id === $user->id;
    }

    public function approve(User $user, Post $post): bool
    {
        return $user->hasRole('super_admin')
            && $post->status === PostStatus::Pending;
    }

    public function reject(User $user, Post $post): bool
    {
        return $user->hasRole('super_admin')
            && $post->status === PostStatus::Pending;
    }

    public function startReview(User $user, Post $post): bool
    {
        return $user->hasRole('editor')
            && $post->status === PostStatus::Approved;
    }

    public function finish(User $user, Post $post): bool
    {
        return $user->hasRole('editor')
            && $post->status === PostStatus::InReview;
    }

    public function publish(User $user, Post $post): bool
    {
        return $user->hasRole('admin')
            && $post->status === PostStatus::Finished;
    }

    public function schedule(User $user, Post $post): bool
    {
        return $user->hasRole('admin')
            && $post->status === PostStatus::Finished
            && is_null($post->published_at);
    }
}