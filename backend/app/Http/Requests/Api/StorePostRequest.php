<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->can('create posts') || auth()->user()->is_admin);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'unique:posts,title'],
            'content' => ['required', 'string', 'min:50'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date', 'after_or_equal:now'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
            'seo_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Post title is required.',
            'title.unique' => 'A post with this title already exists.',
            'content.required' => 'Post content is required.',
            'content.min' => 'Post content must be at least 50 characters long.',
            'featured_image.image' => 'The featured image must be a valid image file.',
            'featured_image.max' => 'The featured image must not exceed 5MB.',
            'status.required' => 'Post status is required.',
            'status.in' => 'Post status must be either draft or published.',
            'published_at.after_or_equal' => 'Published date must be in the future.',
            'categories.*.exists' => 'One or more selected categories do not exist.',
            'tags.*.exists' => 'One or more selected tags do not exist.',
            'seo_title.max' => 'SEO title must not exceed 60 characters.',
            'meta_description.max' => 'Meta description must not exceed 160 characters.',
        ];
    }
}
