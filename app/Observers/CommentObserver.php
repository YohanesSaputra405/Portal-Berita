<?php

namespace App\Observers;

use App\Models\Comment;
use App\Enums\CommentStatus;
use App\Services\CommentFilterService;

class CommentObserver
{
    public function __construct(
        protected CommentFilterService $filterService
    ) {}

    /**
     * Handle the Comment "creating" event.
     */
    public function creating(Comment $comment): void
    {
        if ($this->filterService->containsForbiddenWords($comment->content)) {
            $comment->status = CommentStatus::Rejected;
        }
    }
}
