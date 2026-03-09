<x-member-layout>
    <div class="max-w-4xl mx-auto py-4">
        <div class="mb-8">
            <a href="{{ route('member.articles.index') }}" class="text-sm font-medium text-gray-500 hover:text-amber-600 flex items-center gap-1 mb-4 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Tulis Artikel Baru</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Bagikan informasi atau gagasan Anda melalui portal kami.</p>
        </div>

        <form action="{{ route('member.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Judul Artikel <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Masukkan judul yang menarik..." class="w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition-all">
                    @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Excerpt -->
                <div>
                    <label for="excerpt" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Kutipan Singkat (Excerpt)</label>
                    <textarea name="excerpt" id="excerpt" rows="2" placeholder="Ringkasan singkat artikel (akan muncul di kartu berita)..." class="w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition-all">{{ old('excerpt') }}</textarea>
                    @error('excerpt') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Isi Konten Berita <span class="text-red-500">*</span></label>
                    <textarea name="content" id="content" rows="12" placeholder="Tuliskan isi berita secara lengkap di sini..." class="w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition-all font-serif">{{ old('content') }}</textarea>
                    @error('content') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Featured Image -->
                <div x-data="{ imageUrl: null }">
                    <label for="featured_image" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Cover / Gambar Utama <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <!-- Preview Area -->
                        <div x-show="imageUrl" class="mb-4 relative group">
                            <img :src="imageUrl" class="w-full h-64 object-cover rounded-2xl border-2 border-amber-500 shadow-md">
                            <button type="button" @click="imageUrl = null; $refs.fileInput.value = ''" class="absolute top-4 right-4 p-2 bg-red-600 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Upload Box -->
                        <div x-show="!imageUrl" class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 dark:border-gray-600 border-dashed rounded-2xl hover:border-amber-400 transition-colors bg-gray-50/50 dark:bg-gray-700/20">
                            <div class="space-y-1 text-center font-medium">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="featured_image" class="relative cursor-pointer rounded-md font-bold text-amber-600 hover:text-amber-500 focus-within:outline-none">
                                        <span>Upload file</span>
                                        <input id="featured_image" x-ref="fileInput" name="featured_image" type="file" class="sr-only" @change="if ($event.target.files.length > 0) imageUrl = URL.createObjectURL($event.target.files[0])">
                                    </label>
                                    <p class="pl-1 uppercase">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 italic">PNG, JPG, WEBP hingga 2MB</p>
                            </div>
                        </div>
                    </div>
                    @error('featured_image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                    <!-- Categories -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wider">Kategori <span class="text-red-500 text-xs italic">* Pilih minimal satu</span></label>
                        <div class="grid grid-cols-1 gap-2 max-h-48 overflow-y-auto p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl border border-gray-100 dark:border-gray-600">
                            @foreach($categories as $category)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-amber-500 transition-colors">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('category_ids') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tags (Optional) -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wider">Tagar (Opsional)</label>
                        <div class="grid grid-cols-1 gap-2 max-h-48 overflow-y-auto p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl border border-gray-100 dark:border-gray-600">
                            @foreach($tags as $tag)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-amber-500 transition-colors">#{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('member.articles.index') }}" class="px-8 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 font-bold rounded-2xl hover:bg-gray-50 transition-all">Batal</a>
                <button type="submit" class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-2xl shadow-lg shadow-amber-500/30 transition-all transform hover:-translate-y-1">Kirim Artikel ke Editor</button>
            </div>
        </form>
    </div>
</x-member-layout>
