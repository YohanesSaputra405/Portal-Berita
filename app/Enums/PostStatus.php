<?php

namespace App\Enums;

enum PostStatus: string
{
    case Draft = 'draft';
    case Pending = 'pending';
    case Approved = 'approved';
    case InReview = 'in_review';
    case Finished = 'finished';
    case Revision = 'revision';
    case Rejected = 'rejected';
    case Published = 'published';
    case Submitted = 'submitted';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Pending => 'Pending Review',
            self::Approved => 'Approved',
            self::Finished => 'Finished',
            self::InReview => 'Sedang Direview',
            self::Revision => 'Perlu Revisi',
            self::Rejected => 'Ditolak',
            self::Published => 'Published',
            self::Submitted => 'Submitted',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Pending => 'warning',
            self::Approved => 'primary',
            self::Finished => 'info',
            self::InReview => 'warning',
            self::Revision => 'info',
            self::Submitted => 'info',
            self::Rejected => 'danger',
            self::Published => 'success',
        };
    }
}