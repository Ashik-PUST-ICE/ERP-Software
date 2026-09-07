<?php

namespace App\Http\Requests\AutoPost\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'platform' => 'nullable|string',
            'account_ids' => 'nullable|array',
            'content' => 'nullable|string',
            'post_type' => 'nullable|string',
            'status' => 'nullable|in:pending,posted,failed',
            'gallery_image_ids' => 'nullable|string',
            'gallery_video_ids' => 'nullable|string',
            'direct_media' => 'nullable|array',
            'direct_media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,webp|max:102400',
        ];
    }
}