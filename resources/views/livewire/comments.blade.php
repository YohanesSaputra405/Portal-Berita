<?php

use App\Models\Comment;
use App\Models\Post;
use App\Enums\CommentStatus;
use App\Services\CommentFilterService;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public Post $post;
    public string $content = '';
    public ?string $errorMessage = null;
    public ?string $successMessage = null;

    public function with(): array
    {
        return [
            'comments' => $this->post->comments()
                ->approved()
                ->root()
                ->with(['author', 'replies.author'])
                ->latest()
                ->get(),
        ];
    }

    public function login()
    {
        return redirect()->guest(route('login'));
    }

    public function store()
    {
        if (!Auth::check()) {
            return redirect()->guest(route('login'));
        }

        $this->validate([
            'content' => 'required|min:3|max:1000',
        ]);

        $filterService = app(CommentFilterService::class);
        
        if ($filterService->containsForbiddenWords($this->content)) {
            $this->errorMessage = 'Maaf, komentar Anda mengandung kata-kata yang tidak diperbolehkan dan otomatis ditolak.';
            $this->content = '';
            return;
        }

        Comment::create([
            'post_id' => $this->post->id,
            'user_id' => Auth::id(),
            'content' => $this->content,
            'status' => CommentStatus::Approved,
        ]);

        $this->content = '';
        $this->successMessage = 'Komentar Anda telah dikirim!';
        $this->errorMessage = null;

        $this->dispatch('comment-added');
    }
};
?>

<div class="mt-16 bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-800">
    <h3 class="text-2xl font-bold mb-8 text-slate-900 dark:text-white">Komentar ({{ $post->comments()->approved()->count() }})</h3>

    @auth
        <form wire:submit.prevent="store" class="mb-12">
            @if($errorMessage)
                <div class="mb-4 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl text-sm">
                    {{ $errorMessage }}
                </div>
            @endif

            @if($successMessage)
                <div class="mb-4 p-4 bg-green-50 border border-green-100 text-green-600 rounded-2xl text-sm">
                    {{ $successMessage }}
                </div>
            @endif

            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-slate-200 overflow-hidden mt-1">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ Auth::user()->name }}">
                </div>
                <div class="flex-grow">
                    <textarea 
                        wire:model="content"
                        placeholder="Tulis pendapat Anda..." 
                        class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-800 border-transparent focus:border-blue-500 focus:ring-0 text-slate-900 dark:text-white placeholder-slate-400 min-h-[100px] transition-all"
                    ></textarea>
                    @error('content') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition shadow-lg shadow-blue-100 dark:shadow-none">
                            Kirim Komentar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    @else
        <div class="mb-12 p-8 bg-slate-50 dark:bg-slate-800 rounded-3xl text-center">
            <p class="text-slate-600 dark:text-slate-400 mb-4">Silakan login untuk memberikan komentar.</p>
            <button wire:click="login" class="inline-block px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition">Login Sekarang</button>
        </div>
    @endauth

    <!-- List Comments -->
    <div class="space-y-8">
        @forelse($comments as $comment)
            <div class="flex space-x-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-slate-200 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->author->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $comment->author->name }}">
                </div>
                <div class="flex-grow">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-bold text-slate-900 dark:text-white">{{ $comment->author->name }}</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                        {{ $comment->content }}
                    </p>
                    
                    {{-- Balasan (jika ada) --}}
                    @if($comment->replies->count() > 0)
                        <div class="mt-6 space-y-6 pl-6 border-l-2 border-slate-100 dark:border-slate-800">
                            @foreach($comment->replies as $reply)
                                <div class="flex space-x-3">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-200 overflow-hidden">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->author->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $reply->author->name }}">
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span class="font-bold text-sm text-slate-900 dark:text-white">{{ $reply->author->name }}</span>
                                            <span class="text-[10px] text-slate-500 dark:text-slate-400">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-slate-600 dark:text-slate-300">
                                            {{ $reply->content }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                Belum ada komentar. Jadilah yang pertama memberikan pendapat!
            </div>
        @endforelse
    </div>
</div>