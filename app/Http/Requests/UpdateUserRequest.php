<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:10|max:255',
            'content' => 'required|min:100',
            'category' =>'required|exists:categories,name',
            'tags' => 'required|exists:tags,name',
            'featured_image' => 'image|max:2048|mimes:png,jpg,jpeg,gif,wepb',
            'excerpt' => 'nullable|max:500',
        ];
    }
}
