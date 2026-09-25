<?php

return [

    'providers' => [
        'openai' => [
            'label' => 'OpenAI',
            'api_key_option' => 'openai_api_key',
            'models' => [
                'gpt-4o-mini',
                'gpt-5-nano',
                'gpt-4.1-nano',
                'gpt-3.5-turbo',
                'gpt-5-mini',
                'gpt-4o',
                'gpt-4.1-mini',
                'gpt-4.1',
                'gpt-4-turbo',
                'gpt-5.2',
                'gpt-5.1',
                'gpt-5',
            ],
            'default_model' => 'gpt-4o-mini',
            'base_url' => 'https://api.openai.com/v1',
        ],
        'grok' => [
            'label' => 'Grok (xAI)',
            'api_key_option' => 'grok_api_key',
            'models' => [
                'grok-3-mini',
                'grok-3',
                'grok-4-0709',
                'grok-2-1212',
                'grok-2-vision-1212',
                'grok-1.5-0314',
            ],
            'default_model' => 'grok-3-mini',
            'base_url' => 'https://api.x.ai/v1',
        ],
        'gemini' => [
            'label' => 'Google Gemini',
            'api_key_option' => 'gemini_api_key',
            'models' => [
                'gemini-2.5-flash',
                'gemini-2.5-pro',
                'gemini-1.5-flash',
                'gemini-1.5-pro',
                'gemini-1.0-pro',
                'gemini-1.0-pro-vision',
            ],
            'default_model' => 'gemini-2.5-flash',
            'base_url' => 'https://generativelanguage.googleapis.com/v1beta',
        ],
        'anthropic' => [
            'label' => 'Anthropic Claude',
            'api_key_option' => 'anthropic_api_key',
            'models' => [
                'claude-3-5-haiku-latest',
                'claude-3-7-sonnet-latest',
                'claude-sonnet-4-20250514',
                'claude-3-opus-latest',
                'claude-3-haiku-20240307',
                'claude-3-sonnet-20240229',
            ],
            'default_model' => 'claude-3-5-haiku-latest',
            'base_url' => 'https://api.anthropic.com/v1',
        ],
    ],
    'default_provider' => 'openai',

    /*
    |--------------------------------------------------------------------------
    | OpenAI – One API key is used for all: text, image, and video.
    | Text: Chat Completions (openai_models). Image: Images API (openai_image_models). Video: Sora (openai_video_models).
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Text generation (Chat Completions API)
    |--------------------------------------------------------------------------
    */
    'openai_models' => [
        // Low-cost (text + vision)
        'gpt-4o-mini',
        'gpt-5-nano',
        'gpt-4.1-nano',
        'gpt-3.5-turbo',
        // Mid-tier (text + vision)
        'gpt-5-mini',
        'gpt-4o',
        'gpt-4.1-mini',
        'gpt-4.1',
        'gpt-4-turbo',
        // High capability
        'gpt-5.2',
        'gpt-5.1',
        'gpt-5',
    ],

    'openai_default_model' => 'gpt-4o-mini',

    'openai_model_labels' => [
        'gpt-4o-mini'   => 'gpt-4o-mini (Text + Image input)',
        'gpt-5-nano'    => 'gpt-5-nano (Text + Image, cheapest)',
        'gpt-4.1-nano'  => 'gpt-4.1-nano (Text + Image)',
        'gpt-3.5-turbo' => 'gpt-3.5-turbo (Text only)',
        'gpt-5-mini'    => 'gpt-5-mini (Text + Image)',
        'gpt-4o'        => 'gpt-4o (Text + Image)',
        'gpt-4.1-mini'  => 'gpt-4.1-mini (Text + Image)',
        'gpt-4.1'       => 'gpt-4.1 (Text + Image)',
        'gpt-4-turbo'   => 'gpt-4-turbo (Text + Image)',
        'gpt-5.2'       => 'gpt-5.2 (Text + Image)',
        'gpt-5.1'       => 'gpt-5.1 (Text + Image)',
        'gpt-5'         => 'gpt-5 (Text + Image)',
    ],

    'openai_default_temperature' => 0.7,
    'openai_default_max_tokens'   => 1000,

    /*
    |--------------------------------------------------------------------------
    | OpenAI Image Models (Images API – text-to-image)
    |--------------------------------------------------------------------------
    */
    'openai_image_models' => [
        'gpt-image-1.5',
        'gpt-image-1-mini',
        'gpt-image-1',
    ],
    'openai_default_image_model' => 'gpt-image-1-mini',

    /*
    |--------------------------------------------------------------------------
    | OpenAI Video Models (Sora / Videos API – text-to-video)
    |--------------------------------------------------------------------------
    */
    'openai_video_models' => [
        'sora-2',
        'sora-2-pro',
    ],
    'openai_default_video_model' => 'sora-2',

    'tones' => [
        'professional' => 'Professional',
        'friendly'     => 'Friendly',
        'casual'       => 'Casual',
        'formal'      => 'Formal',
        'creative'    => 'Creative',
    ],

    // Language list for AI is from languageIsoCode() in CoreArray.php

];
