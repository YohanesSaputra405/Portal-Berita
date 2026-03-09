<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Hanya yang login dan punya role yang bisa mengirim artikel (akan dihandle di middleware/policy juga)
        return auth()->check() && auth()->user()->hasAnyRole(['contributor', 'user', 'reporter', 'editor', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'max:255', 'unique:posts,title'],
            'excerpt'        => ['nullable', 'string', 'max:500'],
            'content'        => ['required', 'string'],
            'featured_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'category_ids'   => ['required', 'array', 'min:1'],
            'category_ids.*' => ['exists:categories,id'],
            'tags'           => ['nullable', 'array'],
            'tags.*'         => ['exists:tags,id'],
        ];
    }
}
