<?php

namespace App\Http\Services\SocialMedia;

use App\Models\SocialMediaAccount;
use App\Models\ScheduledPost;
use Illuminate\Http\UploadedFile;

interface SocialMediaServiceInterface
{
    public function post(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array;

    public function postToPage(SocialMediaAccount $account, string $pageId, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array;

    public function postToGroup(SocialMediaAccount $account, string $groupId, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array;

    public function getAccountInfo(SocialMediaAccount $account): array;

    public function getPages(SocialMediaAccount $account): array;

    public function getGroups(SocialMediaAccount $account): array;

    public function validateAccount(SocialMediaAccount $account): bool;

    public function getPlatformName(): string;

    public function getSupportedMediaTypes(): array;

    public function getContentLimits(): array;
}