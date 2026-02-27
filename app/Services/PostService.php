<?php

namespace App\Services;

use App\Enums\PostStatus;
use App\Events\PostPublished;
use App\Models\Post;
use App\Models\PostHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * PostService
 *
 * Workflow engine untuk Post.
 */
class PostService
{
    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(array $data, User $author): Post
    {
        return DB::transaction(function () use ($data, $author) {

            $initialStatus = $author->hasRole('reporter')
                ? PostStatus::Draft
                : PostStatus::Submitted;

            $post = Post::create([
                ...$data,
                'user_id' => $author->id,
                'status'  => $initialStatus,
            ]);

            $this->recordHistory($post, null, $initialStatus, $author);

            return $post;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Post $post, array $data, User $actor): Post
    {
        if (! $actor->can('update', $post)) {
            throw ValidationException::withMessages([
                'authorization' => 'Anda tidak memiliki izin.',
            ]);
        }

        $post->update($data);

        return $post;
    }

    /*
    |--------------------------------------------------------------------------
    | CHANGE STATUS
    |--------------------------------------------------------------------------
    */

    public function changeStatus(
        Post $post,
        PostStatus $newStatus,
        User $actor,
        ?string $note = null
    ): Post {

        if (! $this->isAuthorized($post, $newStatus, $actor)) {
            throw ValidationException::withMessages([
                'authorization' => 'Tidak diizinkan mengubah status.',
            ]);
        }

        $this->validateTransition($post->status, $newStatus, $note);

        return DB::transaction(function () use ($post, $newStatus, $actor, $note) {

            $oldStatus = $post->status;

            $post->update([
                'status' => $newStatus,
                'published_at' => $newStatus === PostStatus::Published
                    ? now()
                    : $post->published_at,
            ]);

            $this->recordHistory($post, $oldStatus, $newStatus, $actor, $note);

            if ($newStatus === PostStatus::Published) {
                event(new PostPublished($post));
            }

            return $post;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSITION VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validateTransition(
        PostStatus $current,
        PostStatus $next,
        ?string $note
    ): void {

        $allowed = [

            PostStatus::Draft->value => [
                PostStatus::Pending,
            ],

            PostStatus::Submitted->value => [
                PostStatus::Pending,
            ],

            PostStatus::Pending->value => [
                PostStatus::Approved,
                PostStatus::Rejected,
                PostStatus::Revision,
            ],

            PostStatus::Revision->value => [
                PostStatus::Pending,
            ],

            PostStatus::Approved->value => [
                PostStatus::InReview,
            ],
    
            PostStatus::InReview->value => [
                PostStatus::Finished,
            ],

            PostStatus::Finished->value => [
                PostStatus::Published,
            ],
        ];

        if (
            ! isset($allowed[$current->value]) ||
            ! in_array($next, $allowed[$current->value], true)
        ) {
            throw ValidationException::withMessages([
                'status' => 'Transisi status tidak diperbolehkan.',
            ]);
        }

        if (
            in_array($next, [PostStatus::Rejected, PostStatus::Revision], true)
            && empty($note)
        ) {
            throw ValidationException::withMessages([
                'note' => 'Reject dan revision wajib disertai catatan.',
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION MAP
    |--------------------------------------------------------------------------
    */

    private function isAuthorized(
        Post $post,
        PostStatus $newStatus,
        User $actor
    ): bool {

        return match ($newStatus) {

            PostStatus::Pending =>
                $actor->can('submit', $post),

            PostStatus::Approved =>
                $actor->can('approve', $post),

            PostStatus::InReview =>
                $actor->can('startReview', $post),

            PostStatus::Finished =>
                $actor->can('finish', $post),

            PostStatus::Published =>
                $actor->can('publish', $post),

            default => false,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    private function recordHistory(
        Post $post,
        ?PostStatus $old,
        PostStatus $new,
        User $actor,
        ?string $note = null
    ): void {
        PostHistory::create([
            'post_id'   => $post->id,
            'actor_id'  => $actor->id,
            'old_status'=> $old?->value,
            'new_status'=> $new->value,
            'note'      => $note,
        ]);
    }
}