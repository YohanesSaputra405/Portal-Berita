<?php

namespace App\Actions\Articles;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubmitContributorArticleAction
{
    public function __construct(
        private readonly UploadArticleImageAction $uploadImageAction,
    ) {}

    /**
     * Menangani seluruh logika bisnis saat kontributor mengirim artikel.
     * Proses dibungkus dalam DB transaction agar integritas data terjaga.
     *
     * @param array        $data Data tervalidasi dari StoreArticleRequest.
     * @param UploadedFile $image File gambar yang sudah tervalidasi.
     * @param User         $author User yang sedang login.
     * @return Post Artikel yang baru dibuat.
     */
    public function execute(array $data, UploadedFile $image, User $author): Post
    {
        return DB::transaction(function () use ($data, $image, $author) {

            // 1. Upload gambar terlebih dahulu (atomic dalam transaction)
            $imagePath = $this->uploadImageAction->execute($image);

            // 2. Buat artikel dengan status Pending (menunggu review editor/admin)
            $post = Post::create([
                'user_id'        => $author->id,
                'title'          => $data['title'],
                'slug'           => Str::slug($data['title']),
                'excerpt'        => $data['excerpt'] ?? null,
                'content'        => $data['content'],
                'featured_image' => $imagePath,
                'status'         => PostStatus::Pending,
            ]);

            // 3. Sync relasi kategori dan tag
            if (! empty($data['category_ids'])) {
                $post->categories()->sync($data['category_ids']);
            }

            if (! empty($data['tags'])) {
                $post->tags()->sync($data['tags']);
            }

            return $post;
        });
    }
}
