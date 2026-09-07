<?php

namespace App\Http\Requests\AutoPost\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ScheduledPostRequest extends FormRequest
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
        $rules = [
            'user_id' => 'nullable|exists:users,id',
            'account_ids' => $this->isMethod('PUT') || $this->isMethod('PATCH') ? 'nullable|array' : 'required|array|min:1',
            'account_ids.*' => 'required|exists:social_media_accounts,id',
            'social_media_account_id' => 'nullable|exists:social_media_accounts,id', // For backward compatibility
            'content' => 'nullable|string|max:10000',
            'post_type' => 'required|in:feed,reels,story,reel,video,Feed,Reels,Story,Reel,Video',
            'publish_now' => 'nullable|in:0,1,true,false',
            'scheduled_time' => array_filter([
                'required_if:publish_now,0',
                'required_if:publish_now,false',
                'nullable',
                'date_format:Y-m-d H:i',
                ($this->isMethod('PUT') || $this->isMethod('PATCH')) ? null : 'after:now'
            ]),
            'options' => 'nullable|array',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,webp|max:102400', // 100MB max
            'gallery_image_ids' => 'nullable|string',
            'gallery_video_ids' => 'nullable|string',
            'direct_media' => 'nullable|array',
            'direct_media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,webp|max:102400',
            'save_template' => 'nullable|boolean',
        ];

        return $rules;
    }

    /**
     * Get custom messages for validation errors
     */
    public function messages(): array
    {
        return [
            'user_id.exists' => 'The selected user is invalid',
            'account_ids.required' => 'Please select at least one social media account',
            'account_ids.array' => 'Account IDs must be an array',
            'account_ids.min' => 'Please select at least one social media account',
            'account_ids.*.exists' => 'One or more selected social media accounts are invalid',
            'social_media_account_id.exists' => 'The selected social media account is invalid',
            'content.max' => 'Content cannot exceed 10,000 characters',
            'post_type.required' => 'Please select a post type',
            'post_type.in' => 'Invalid post type selected',
            'scheduled_time.required_if' => 'Scheduled time is required when not publishing immediately',
            'scheduled_time.date_format' => 'Scheduled time must be in YYYY-MM-DD HH:MM format',
            'scheduled_time.after' => 'Scheduled time must be in the future',
            'media.mimes' => 'Media must be an image (jpg, jpeg, png, gif, webp) or video (mp4, mov)',
            'media.max' => 'Media file size cannot exceed 100MB',
        ];
    }

    /**
     * Prepare the data for validation
     */
    protected function prepareForValidation(): void
    {
        // Convert options from string to array if needed
        if ($this->has('options') && is_string($this->input('options'))) {
            $this->merge([
                'options' => json_decode($this->input('options'), true) ?: []
            ]);
        }

        // Convert scheduled_time from datetime-local format (YYYY-MM-DDTHH:MM) to YYYY-MM-DD HH:MM
        if ($this->filled('scheduled_time')) {
            $time = str_replace('T', ' ', $this->input('scheduled_time'));
            $this->merge(['scheduled_time' => $time]);
        }
    }
}