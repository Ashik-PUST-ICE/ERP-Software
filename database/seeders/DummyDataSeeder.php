<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\FileManager;
use App\Models\Gallery;
use App\Models\LandingBlog;
use App\Models\Menu;
use App\Models\Package;
use App\Models\Page;
use App\Models\Payment;
use App\Models\PostHistory;
use App\Models\ScheduledPost;
use App\Models\SocialMediaAccount;
use App\Models\SubscriptionRefund;
use App\Models\Template;
use App\Models\Ticket;
use App\Models\TicketConversation;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPackage;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    /**
     * Seed dummy data for screenshots / documentation.
     * Set ENABLE_DUMMY_DATA=true in .env and run:
     * php artisan db:seed --class=DummyDataSeeder
     */
    public function run(): void
    {
        if (!filter_var(env('ENABLE_DUMMY_DATA', false), FILTER_VALIDATE_BOOLEAN)) {
            $this->command->warn('Set ENABLE_DUMMY_DATA=true in .env to run this seeder.');
            return;
        }

        $packagesCount = (int) env('DUMMY_PACKAGES_COUNT', 5);
        $packagesImages = filter_var(env('DUMMY_PACKAGES_IMAGES', true), FILTER_VALIDATE_BOOLEAN);
        $couponsCount = (int) env('DUMMY_COUPONS_COUNT', 10);
        $usersCount = (int) env('DUMMY_USERS_COUNT', 15);
        $adminUsersCount = (int) env('DUMMY_ADMIN_USERS_COUNT', 5);
        $campaignsCount = (int) env('DUMMY_CAMPAIGNS_COUNT', 12);
        $templatesCount = (int) env('DUMMY_TEMPLATES_COUNT', 15);
        $categoriesCount = (int) env('DUMMY_CATEGORIES_COUNT', 8);
        $postsCount = (int) env('DUMMY_POSTS_COUNT', 20);
        $ticketsCount = (int) env('DUMMY_TICKETS_COUNT', 8);
        $galleryImages = filter_var(env('DUMMY_GALLERY_IMAGES', true), FILTER_VALIDATE_BOOLEAN);
        $galleryCount = (int) env('DUMMY_GALLERY_COUNT', 10);
        $videoGallery = filter_var(env('DUMMY_VIDEO_GALLERY', true), FILTER_VALIDATE_BOOLEAN);
        $videoGalleryCount = (int) env('DUMMY_VIDEO_GALLERY_COUNT', 8);
        $blogsCount = (int) env('DUMMY_BLOGS_COUNT', 8);
        $pagesCount = (int) env('DUMMY_PAGES_COUNT', 8);
        $menusCount = (int) env('DUMMY_MENUS_COUNT', 10);

        $admin = User::where('role', USER_ROLE_ADMIN)->first();
        $superAdmin = User::where('role', USER_ROLE_SUPER_ADMIN)->first();
        $createdBy = $admin?->id ?? $superAdmin?->id ?? 1;
        $gateway = \App\Models\Gateway::where('status', ACTIVE)->first();

        if (!$gateway) {
            $this->command->error('Run GatewaySeeder first. No active gateway found.');
            return;
        }

        $this->seedPackages($packagesCount, $packagesImages);
        $this->seedCoupons($couponsCount);
        $adminUsers = $this->seedAdminUsers($adminUsersCount);
        $users = $this->seedUsers($usersCount);
        $categories = $this->seedCategories($categoriesCount, $createdBy);
        $this->seedTemplates($templatesCount, $categories, $createdBy);
        $this->seedCampaigns($campaignsCount, $createdBy);
        $userPackages = $this->seedOrdersAndPayments($users, $gateway);
        $this->seedTickets($ticketsCount, $users, $userPackages);
        
        // Seed gallery images if enabled
        if ($galleryImages) {
            $this->seedGalleryAndPosts($users, $adminUsers, $postsCount, $galleryCount);
        }
        
        // Seed video gallery if enabled
        if ($videoGallery) {
            $this->seedVideoGallery($users, $adminUsers, $videoGalleryCount);
        }
        
        $this->seedSubscriptionRefunds($users, $userPackages);
        $this->seedBlogs($blogsCount, $createdBy);
        $this->seedPages($pagesCount);
        $this->seedMenus($menusCount);
        // Skip roles seeding as it requires custom database setup
        // $this->seedRolesAndPermissions();
    }

    private function seedPackages(int $count, bool $withImages = true): void
    {
        $names = ['Starter', 'Professional', 'Business', 'Enterprise', 'Premium', 'Growth', 'Scale'];
        $colors = ['4A90E2', '7ED321', 'F5A623', 'D0021B', '9013FE', '50E3C2', 'BD10E0'];
        
        // Create package icons directory if it doesn't exist and images are enabled
        $iconDir = 'uploads/packages';
        if ($withImages && !Storage::disk('public')->exists($iconDir)) {
            Storage::disk('public')->makeDirectory($iconDir);
        }
        
        for ($i = 0; $i < min($count, count($names)); $i++) {
            $name = $names[$i] ?? "Plan " . ($i + 1);
            $slug = Str::slug($name) . '-' . Str::random(4);
            if (Package::where('slug', $slug)->exists()) {
                continue;
            }
            
            $packageData = [
                'name' => $name,
                'slug' => $slug,
                'description' => "Dummy package for {$name} - perfect for screenshots.",
                'monthly_price' => rand(9, 99),
                'yearly_price' => rand(90, 999),
                'post_limit' => rand(10, 500),
                'ai_enabled' => $i % 2 === 0 ? 1 : 0,
                'status' => STATUS_ACTIVE,
                'provider_limit' => $this->getProviderLimit($i),
                'features' => $this->getFeatures($i),
            ];
            
            // Create a placeholder icon image if enabled
            if ($withImages) {
                $iconFileName = 'package-' . Str::slug($name) . '-' . time() . '.png';
                $iconPath = $iconDir . '/' . $iconFileName;
                
                // Generate a simple colored placeholder image
                $this->generatePlaceholderImage($iconPath, $colors[$i] ?? '4A90E2', $name);
                
                // Create FileManager record
                $fileManager = FileManager::create([
                    'file_type' => 'image/png',
                    'storage_type' => 'public',
                    'original_name' => $iconFileName,
                    'file_name' => $iconFileName,
                    'user_id' => 1,
                    'path' => $iconPath,
                    'extension' => 'png',
                    'size' => filesize(storage_path('app/public/' . $iconPath)) ?: 1000,
                ]);
                
                $packageData['icon'] = $fileManager->id;
            }
            
            Package::create($packageData);
        }
    }
    
    /**
     * Generate a simple placeholder image with text
     */
    private function generatePlaceholderImage(string $path, string $color, string $text): void
    {
        $width = 100;
        $height = 100;
        
        // Create image resource
        $image = imagecreatetruecolor($width, $height);
        
        // Parse hex color
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
        
        // Fill background
        $bgColor = imagecolorallocate($image, $r, $g, $b);
        imagefill($image, 0, 0, $bgColor);
        
        // Add white text
        $textColor = imagecolorallocate($image, 255, 255, 255);
        $fontSize = 4;
        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $textHeight = imagefontheight($fontSize);
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;
        
        // For simple text, we'll just create a blank colored image
        // as TrueType font requires additional setup
        
        // Save as PNG
        imagepng($image, storage_path('app/public/' . $path));
        imagedestroy($image);
    }

    /**
     * Generate a gallery image with a colored background
     */
    private function generateGalleryImage(string $path, array $color, string $title): void
    {
        $width = 800;
        $height = 600;
        
        // Create image resource
        $image = imagecreatetruecolor($width, $height);
        
        // Fill background with the specified color
        $bgColor = imagecolorallocate($image, $color['r'], $color['g'], $color['b']);
        imagefill($image, 0, 0, $bgColor);
        
        // Add some variation - draw a rectangle in center
        $lighterColor = imagecolorallocate($image, 
            min(255, $color['r'] + 40), 
            min(255, $color['g'] + 40), 
            min(255, $color['b'] + 40)
        );
        imagefilledrectangle($image, 100, 100, 700, 500, $lighterColor);
        
        // Save as JPEG
        imagejpeg($image, storage_path('app/public/' . $path), 85);
        imagedestroy($image);
    }

    /**
     * Get provider limit (social media platforms) for a package based on its index
     * Platform IDs: 1=Facebook, 2=Twitter, 3=YouTube, 4=LinkedIn, 5=Instagram, 6=TikTok, 7=Threads
     */
    private function getProviderLimit(int $packageIndex): array
    {
        $providerLimits = [
            [1, 2],                    // Starter: Facebook, Twitter
            [1, 2, 5],                 // Professional: Facebook, Twitter, Instagram
            [1, 2, 3, 5],              // Business: Facebook, Twitter, YouTube, Instagram
            [1, 2, 3, 4, 5],           // Enterprise: Facebook, Twitter, YouTube, LinkedIn, Instagram
            [1, 2, 3, 4, 5, 6],        // Premium: All except Threads
            [1, 2, 3, 4, 5, 6, 7],     // Growth: All platforms
            [1, 2, 3, 4, 5, 6, 7],     // Scale: All platforms
        ];
        
        return $providerLimits[$packageIndex % count($providerLimits)] ?? [1, 2];
    }

    /**
     * Get features for a package based on its index
     */
    private function getFeatures(int $packageIndex): array
    {
        $allFeatures = [
            // Starter
            [
                'Basic Post Scheduling',
                '5 Posts per day',
                'Email Support',
                'Basic Analytics',
            ],
            // Professional
            [
                'Advanced Post Scheduling',
                '20 Posts per day',
                'Priority Email Support',
                'Advanced Analytics',
                'Hashtag Suggestions',
            ],
            // Business
            [
                'Unlimited Post Scheduling',
                '50 Posts per day',
                'Priority Email & Chat Support',
                'Advanced Analytics',
                'Hashtag Suggestions',
                'Custom Templates',
            ],
            // Enterprise
            [
                'Unlimited Post Scheduling',
                '100 Posts per day',
                '24/7 Priority Support',
                'Advanced Analytics & Reports',
                'AI-Powered Hashtag Suggestions',
                'Custom Templates',
                'Team Collaboration',
            ],
            // Premium
            [
                'Unlimited Post Scheduling',
                'Unlimited Posts per day',
                '24/7 Priority Support',
                'Advanced Analytics & Reports',
                'AI-Powered Content Creation',
                'Custom Templates',
                'Team Collaboration',
                'API Access',
            ],
            // Growth
            [
                'Unlimited Post Scheduling',
                'Unlimited Posts per day',
                '24/7 Priority Support',
                'Advanced Analytics & Reports',
                'AI-Powered Content Creation',
                'Custom Templates',
                'Team Collaboration',
                'API Access',
                'White Label Option',
            ],
            // Scale
            [
                'Unlimited Post Scheduling',
                'Unlimited Posts per day',
                'Dedicated Account Manager',
                'Advanced Analytics & Reports',
                'Full AI-Powered Suite',
                'Unlimited Custom Templates',
                'Unlimited Team Members',
                'Full API Access',
                'White Label Option',
                'Custom Integrations',
            ],
        ];
        
        return $allFeatures[$packageIndex % count($allFeatures)] ?? $allFeatures[0];
    }

    private function seedCoupons(int $count): void
    {
        $codes = ['WELCOME10', 'SAVE20', 'FLASH30', 'NEWYEAR25', 'SUMMER15', 'VIP50', 'BETA5', 'LAUNCH20'];
        for ($i = 0; $i < min($count, 20); $i++) {
            $code = $codes[$i % count($codes)] . ($i > 7 ? $i : '');
            if (Coupon::where('code', $code)->exists()) {
                continue;
            }
            Coupon::create([
                'name' => "Dummy Coupon {$code}",
                'code' => $code,
                'discount_type' => $i % 2 ? 'percentage' : 'fixed',
                'amount' => $i % 2 ? rand(5, 30) : rand(5, 50),
                'start_date' => now()->subDays(rand(1, 30)),
                'end_date' => now()->addDays(rand(30, 90)),
                'minimum_spend' => rand(0, 50),
                'usage_limit_per_coupon' => rand(10, 100),
                'usage_limit_per_customer' => 1,
                'used_count' => rand(0, 5),
                'status' => STATUS_ACTIVE,
            ]);
        }
    }

    private function seedUsers(int $count): \Illuminate\Support\Collection
    {
        $users = collect();
        for ($i = 0; $i < $count; $i++) {
            $email = "user.dummy{$i}@example.com";
            if (User::where('email', $email)->exists()) {
                $users->push(User::where('email', $email)->first());
                continue;
            }
            $user = User::create([
                'name' => "Demo User " . ($i + 1),
                'email' => $email,
                'mobile' => '02321' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'password' => Hash::make('123456'),
                'role' => USER_ROLE_USER,
                'status' => USER_STATUS_ACTIVE,
                'email_verification_status' => 1,
                'phone_verification_status' => 1,
            ]);
            $users->push($user);
        }
        return $users;
    }
    
    private function seedAdminUsers(int $count): \Illuminate\Support\Collection
    {
        $users = collect();
        for ($i = 0; $i < $count; $i++) {
            $email = "admin.dummy{$i}@example.com";
            if (User::where('email', $email)->exists()) {
                $users->push(User::where('email', $email)->first());
                continue;
            }
            $user = User::create([
                'name' => "Admin User " . ($i + 1),
                'email' => $email,
                'mobile' => '02321' . str_pad($i + 100, 4, '0', STR_PAD_LEFT),
                'password' => Hash::make('123456'),
                'role' => USER_ROLE_ADMIN,
                'status' => USER_STATUS_ACTIVE,
                'email_verification_status' => 1,
                'phone_verification_status' => 1,
            ]);
            $users->push($user);
        }
        return $users;
    }

    private function seedCategories(int $count, int $createdBy): \Illuminate\Support\Collection
    {
        $titles = ['Marketing', 'Social Media', 'Promotions', 'Events', 'Announcements', 'Tips', 'News', 'Tutorials'];
        $categories = collect();
        for ($i = 0; $i < min($count, count($titles)); $i++) {
            $title = $titles[$i] ?? "Category " . ($i + 1);
            $slug = Str::slug($title) . '-dummy-' . Str::random(4);
            if (Category::where('slug', $slug)->exists()) {
                $categories->push(Category::where('slug', $slug)->first());
                continue;
            }
            $cat = Category::create([
                'title' => $title,
                'slug' => $slug,
                'type' => 'template',
                'short_description' => "Dummy category for {$title}.",
                'status' => STATUS_ACTIVE,
                'created_by' => $createdBy,
            ]);
            $categories->push($cat);
        }
        return $categories;
    }

    private function seedTemplates(int $count, $categories, int $createdBy): void
    {
        $platforms = ['instagram', 'facebook', 'twitter', 'linkedin'];
        $postTypes = ['Feed', 'Reels', 'Story'];
        
        // Create template images directory
        $templateImageDir = 'uploads/templates';
        if (!Storage::disk('public')->exists($templateImageDir)) {
            Storage::disk('public')->makeDirectory($templateImageDir);
        }
        
        // Get existing galleries and videos to link
        $galleries = Gallery::all();
        $videos = Video::all();
        
        $templateColors = [
            ['r' => 66, 'g' => 135, 'b' => 245],     // Blue
            ['r' => 126, 'g' => 211, 'b' => 33],    // Green
            ['r' => 245, 'g' => 166, 'b' => 35],     // Orange
            ['r' => 208, 'g' => 2, 'b' => 27],       // Red
            ['r' => 144, 'g' => 19, 'b' => 254],     // Purple
        ];
        
        // Template titles for each post type
        $feedTitles = [
            'Product Launch Announcement',
            'Special Offer Alert',
            'New Collection Launch',
            'Flash Sale Notice',
            'Brand Story Post',
        ];
        
        $reelsTitles = [
            'Behind the Scenes Reel',
            'Product Demo Reel',
            'Customer Testimonial Reel',
            'How-To Tutorial Reel',
            'Event Highlights Reel',
        ];
        
        $storyTitles = [
            'Daily Story Update',
            'Quick Tip Story',
            'New Arrival Story',
            'Poll Story',
            'Q&A Session Story',
        ];
        
        // Create templates for each post type
        $templateIndex = 0;
        
        // Feed templates
        for ($i = 0; $i < $count / 3; $i++) {
            $title = $feedTitles[$i % count($feedTitles)] . ' ' . ($i + 1);
            $slug = Str::slug($title) . '-' . Str::random(6);
            if (Template::where('slug', $slug)->exists()) {
                continue;
            }
            
            // Generate template image
            $color = $templateColors[$templateIndex % count($templateColors)];
            $imageFileName = 'template-feed-' . $i . '-' . time() . '.jpg';
            $imagePath = $templateImageDir . '/' . $imageFileName;
            $this->generateGalleryImage($imagePath, $color, $title);
            
            // Get random gallery images IDs
            $galleryImageIds = '';
            if ($galleries->count() > 0) {
                $galleryImageIds = $galleries->random(min(3, $galleries->count()))->pluck('id')->implode(',');
            }
            
            Template::create([
                'title' => $title,
                'slug' => $slug,
                'category_id' => $categories->random()->id,
                'image' => $imagePath,
                'video' => null,
                'short_description' => "Dummy Feed template #" . ($i + 1),
                'content' => "Check out our latest {$title}! \n\n#feed #template #dummy",
                'post_type' => 'Feed',
                'platform' => $platforms[$i % count($platforms)],
                'gallery_image_ids' => $galleryImageIds,
                'gallery_video_ids' => '',
                'status' => STATUS_ACTIVE,
                'created_by' => $createdBy,
            ]);
            $templateIndex++;
        }
        
        // Reels templates (with video)
        for ($i = 0; $i < $count / 3; $i++) {
            $title = $reelsTitles[$i % count($reelsTitles)] . ' ' . ($i + 1);
            $slug = Str::slug($title) . '-' . Str::random(6);
            if (Template::where('slug', $slug)->exists()) {
                continue;
            }
            
            // Generate template thumbnail image
            $color = $templateColors[($templateIndex + 1) % count($templateColors)];
            $imageFileName = 'template-reels-' . $i . '-' . time() . '.jpg';
            $imagePath = $templateImageDir . '/' . $imageFileName;
            $this->generateGalleryImage($imagePath, $color, $title);
            
            // Get random video IDs for Reels
            $galleryVideoIds = '';
            if ($videos->count() > 0) {
                $galleryVideoIds = $videos->random(min(1, $videos->count()))->pluck('id')->implode(',');
            }
            
            Template::create([
                'title' => $title,
                'slug' => $slug,
                'category_id' => $categories->random()->id,
                'image' => $imagePath,
                'video' => null,
                'short_description' => "Dummy Reels template #" . ($i + 1),
                'content' => "Watch our {$title}! \n\n#reels #template #dummy #trending",
                'post_type' => 'Reels',
                'platform' => $platforms[$i % count($platforms)],
                'gallery_image_ids' => '',
                'gallery_video_ids' => $galleryVideoIds,
                'status' => STATUS_ACTIVE,
                'created_by' => $createdBy,
            ]);
            $templateIndex++;
        }
        
        // Story templates (with video or image)
        for ($i = 0; $i < $count / 3; $i++) {
            $title = $storyTitles[$i % count($storyTitles)] . ' ' . ($i + 1);
            $slug = Str::slug($title) . '-' . Str::random(6);
            if (Template::where('slug', $slug)->exists()) {
                continue;
            }
            
            // Generate template thumbnail image
            $color = $templateColors[($templateIndex + 2) % count($templateColors)];
            $imageFileName = 'template-story-' . $i . '-' . time() . '.jpg';
            $imagePath = $templateImageDir . '/' . $imageFileName;
            $this->generateGalleryImage($imagePath, $color, $title);
            
            // Get random gallery image IDs for Story
            $galleryImageIds = '';
            if ($galleries->count() > 0) {
                $galleryImageIds = $galleries->random(min(1, $galleries->count()))->pluck('id')->implode(',');
            }
            
            Template::create([
                'title' => $title,
                'slug' => $slug,
                'category_id' => $categories->random()->id,
                'image' => $imagePath,
                'video' => null,
                'short_description' => "Dummy Story template #" . ($i + 1),
                'content' => "{$title} - Swipe up! \n\n#story #template #dummy #swipeup",
                'post_type' => 'Story',
                'platform' => $platforms[$i % count($platforms)],
                'gallery_image_ids' => $galleryImageIds,
                'gallery_video_ids' => '',
                'status' => STATUS_ACTIVE,
                'created_by' => $createdBy,
            ]);
            $templateIndex++;
        }
    }

    private function seedCampaigns(int $count, int $createdBy): void
    {
        $platforms = ['instagram', 'facebook', 'twitter', 'linkedin'];
        $statuses = ['pending', 'posted', 'failed'];
        for ($i = 0; $i < $count; $i++) {
            Campaign::create([
                'name' => "Campaign " . ($i + 1) . " - Dummy",
                'start_date' => now()->subDays(rand(0, 30)),
                'end_date' => now()->addDays(rand(5, 60)),
                'scheduled_time' => now()->addHours(rand(1, 48)),
                'description' => "Dummy campaign for screenshots.",
                'platform' => $platforms[$i % 4],
                'content' => "Sample campaign content for documentation.",
                'post_type' => 'Feed',
                'status' => $statuses[$i % 3],
                'created_by' => $createdBy,
            ]);
        }
    }

    private function seedOrdersAndPayments($users, $gateway): \Illuminate\Support\Collection
    {
        $packages = Package::where('status', STATUS_ACTIVE)->get();
        if ($packages->isEmpty()) {
            return collect();
        }
        $userPackages = collect();
        foreach ($users->take(10) as $user) {
            $package = $packages->random();
            $price = rand(1, 2) === 1 ? $package->monthly_price : $package->yearly_price;
            $subType = $price === $package->monthly_price ? SUBSCRIPTION_TYPE_MONTHLY : SUBSCRIPTION_TYPE_YEARLY;
            $payment = Payment::create([
                'paymentable_type' => Package::class,
                'paymentable_id' => (string) $package->id,
                'gateway_id' => $gateway->id,
                'user_id' => $user->id,
                'tnxId' => 'TXN-' . strtoupper(Str::random(12)),
                'price' => $price,
                'sub_total' => $price,
                'grand_total' => $price,
                'subscription_type' => $subType,
                'payment_status' => PAYMENT_STATUS_PAID,
                'payment_time' => now()->subDays(rand(1, 30)),
            ]);
            
            // Create Transaction record for the payment
            Transaction::create([
                'user_id' => $user->id,
                'payment_id' => $payment->id,
                'type' => TRANSACTION_TYPE_SUBSCRIPTION,
                'tnxId' => $payment->tnxId,
                'amount' => $price,
                'purpose' => 'Package subscription: ' . $package->name,
                'payment_method' => $gateway->name ?? 'Gateway',
                'payment_time' => $payment->payment_time,
            ]);
            $start = now()->subDays(rand(1, 60));
            $end = $subType === SUBSCRIPTION_TYPE_YEARLY ? $start->copy()->addYear() : $start->copy()->addMonth();
            $userPackage = UserPackage::create([
                'user_id' => $user->id,
                'packageable_type' => Package::class,
                'packageable_id' => $package->id,
                'payment_id' => $payment->id,
                'subscription_id' => 'sub_dummy_' . Str::random(10),
                'start_date' => $start,
                'end_date' => $end,
                'status' => STATUS_ACTIVE,
                'subscription_type' => $subType,
            ]);
            $userPackages->push($userPackage);
        }
        return $userPackages;
    }

    private function seedTickets(int $count, $users, $userPackages): void
    {
        $titles = [
            'Cannot access dashboard after login',
            'Payment not processed correctly',
            'Need help with template customization',
            'API integration not working',
            'Scheduled posts not publishing',
            'Social media account disconnected',
            'Feature request for bulk posting',
            'Billing inquiry for subscription',
            'Cannot upload images to gallery',
            'Need help with hashtag suggestions',
        ];
        
        $descriptions = [
            'After logging in, I get a blank page. Please help resolve this issue as soon as possible.',
            'I made a payment yesterday but it still shows as pending. Transaction ID: DUMMY123',
            'I want to customize the template but cannot find the option. Please guide me.',
            'The API returns 500 error when trying to post. Here is the error message: ...',
            'My scheduled posts are not being published at the scheduled time. This is urgent.',
            'All my social media accounts got disconnected automatically. I need to re-connect them daily.',
            'It would be great to have a bulk posting feature to save time. Is this planned?',
            'I was charged twice for my subscription. Please refund the duplicate charge.',
            'When I try to upload images, I get an error saying "File too large". But the file is only 2MB.',
            'The hashtag suggestions are not relevant to my content. Can I customize them?',
        ];
        
        $statuses = [TICKET_STATUS_OPEN, TICKET_STATUS_IN_PROGRESS, TICKET_STATUS_RESOLVED, TICKET_STATUS_CLOSED];
        $priorities = [0, 1, 2]; // 0=Low, 1=Medium, 2=High
        
        $adminUsers = User::where('role', USER_ROLE_ADMIN)->get();
        
        // Get user_id 2 if exists
        $user2 = User::find(2);
        
        // Ensure at least 5 tickets for user_id 2
        $ticketsForUser2 = min(5, $count);
        
        for ($i = 0; $i < $count; $i++) {
            // First 5 tickets for user_id 2
            if ($i < $ticketsForUser2 && $user2) {
                $user = $user2;
            } else {
                $user = $users->random();
            }
            
            $orderId = $userPackages->isNotEmpty() ? (string) $userPackages->random()->id : null;
            $status = $statuses[array_rand($statuses)];
            $priority = $priorities[array_rand($priorities)];
            $createdAt = now()->subDays(rand(0, 30));
            
            $ticket = Ticket::create([
                'client_id' => $user->id,
                'order_id' => $orderId,
                'ticket_id' => 'TKT-' . strtoupper(Str::random(8)),
                'ticket_title' => $titles[$i % count($titles)],
                'ticket_description' => $descriptions[$i % count($descriptions)],
                'status' => $status,
                'priority' => $priority,
                'created_by' => $user->id,
                'last_reply_by' => $status !== TICKET_STATUS_OPEN ? ($adminUsers->isNotEmpty() ? $adminUsers->random()->id : $user->id) : null,
                'last_reply_time' => $status !== TICKET_STATUS_OPEN ? $createdAt->addHours(rand(1, 48)) : null,
            ]);
            
            // Add ticket conversations
            $conversationCount = rand(1, 4);
            for ($j = 0; $j < $conversationCount; $j++) {
                $isAdminReply = $j % 2 !== 0; // Every other reply is from admin
                $replyUser = $isAdminReply && $adminUsers->isNotEmpty() ? $adminUsers->random() : $user;
                
                $conversationTexts = [
                    'Thank you for reaching out. We are looking into this issue.',
                    'Could you please provide more details about the problem?',
                    'This has been resolved. Please check and let us know.',
                    'We have escalated this to our technical team.',
                    'Please try clearing your cache and try again.',
                    'Is this issue still persisting? Let us know.',
                    'We apologize for the inconvenience. Working on a fix.',
                ];
                
                TicketConversation::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $replyUser->id,
                    'conversation_text' => $conversationTexts[array_rand($conversationTexts)],
                    'attachment' => null,
                ]);
            }
            
            // Update last reply for ticket
            $lastConversation = TicketConversation::where('ticket_id', $ticket->id)->latest()->first();
            if ($lastConversation) {
                $ticket->update([
                    'last_reply_id' => $lastConversation->id,
                    'last_reply_by' => $lastConversation->user_id,
                    'last_reply_time' => $lastConversation->created_at,
                ]);
            }
        }
    }

    private function seedGalleryAndPosts($users, $adminUsers, int $postsCount, int $galleryCount = 10): void
    {
        $platforms = ['instagram', 'facebook', 'twitter', 'linkedin'];
        
        // Create gallery directory if it doesn't exist
        $galleryDir = 'uploads/galleries';
        if (!Storage::disk('public')->exists($galleryDir)) {
            Storage::disk('public')->makeDirectory($galleryDir);
        }
        
        // Generate sample colors for images
        $colors = [
            ['r' => 66, 'g' => 135, 'b' => 245],     // Blue
            ['r' => 126, 'g' => 211, 'b' => 33],    // Green
            ['r' => 245, 'g' => 166, 'b' => 35],     // Orange
            ['r' => 208, 'g' => 2, 'b' => 27],       // Red
            ['r' => 144, 'g' => 19, 'b' => 254],     // Purple
            ['r' => 80, 'g' => 227, 'b' => 194],     // Teal
            ['r' => 189, 'g' => 16, 'b' => 224],      // Pink
        ];
        
        $imageTitles = [
            'Summer Vacation',
            'Product Launch',
            'Team Meeting',
            'Marketing Campaign',
            'Brand Story',
            'Customer Testimonial',
            'Behind the Scenes',
            'Event Highlight',
            'New Collection',
            'Holiday Greeting',
        ];
        
        // Seed images for regular users (user_id = 2)
        $imageCount = 0;
        $imagesPerUser = max(1, (int)($galleryCount / max(1, $users->count())));
        foreach ($users->take(min($galleryCount, $users->count())) as $user) {
            for ($j = 0; $j < $imagesPerUser; $j++) {
                $color = $colors[$imageCount % count($colors)];
                $title = $imageTitles[$imageCount % count($imageTitles)];
                $fileName = 'gallery-user-' . $user->id . '-' . $imageCount . '-' . time() . '.jpg';
                $filePath = $galleryDir . '/' . $fileName;
                
                // Generate a simple colored placeholder image
                $this->generateGalleryImage($filePath, $color, $title);
                
                Gallery::create([
                    'user_id' => 2, // User ID 2
                    'title' => $title . ' (User)',
                    'platform' => $platforms[$j % 4],
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_type' => 'image',
                    'file_extension' => 'jpg',
                    'file_size' => filesize(storage_path('app/public/' . $filePath)) ?: 50000,
                    'dimensions' => '800x600',
                ]);
                
                $imageCount++;
            }
        }
        
        // Seed images for admin users
        $imageCount = 0;
        foreach ($adminUsers->take(3) as $adminUser) {
            for ($j = 0; $j < 2; $j++) {
                $color = $colors[($imageCount + 3) % count($colors)];
                $title = $imageTitles[($imageCount + 5) % count($imageTitles)];
                $fileName = 'gallery-admin-' . $adminUser->id . '-' . $imageCount . '-' . time() . '.jpg';
                $filePath = $galleryDir . '/' . $fileName;
                
                // Generate a simple colored placeholder image
                $this->generateGalleryImage($filePath, $color, $title);
                
                Gallery::create([
                    'user_id' => $adminUser->id,
                    'title' => $title . ' (Admin)',
                    'platform' => $platforms[$j % 4],
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_type' => 'image',
                    'file_extension' => 'jpg',
                    'file_size' => filesize(storage_path('app/public/' . $filePath)) ?: 50000,
                    'dimensions' => '800x600',
                ]);
                
                $imageCount++;
            }
        }

        $socialAccounts = SocialMediaAccount::all();
        if ($socialAccounts->isEmpty()) {
            foreach ($users->take(3) as $user) {
                SocialMediaAccount::create([
                    'user_id' => $user->id,
                    'platform' => $platforms[0],
                    'account_id' => 'dummy_' . Str::random(10),
                    'access_token' => 'dummy_token_' . Str::random(20),
                    'username' => 'demo_user_' . $user->id,
                    'is_active' => true,
                ]);
            }
            $socialAccounts = SocialMediaAccount::all();
        }

        $statuses = ['pending', 'posted', 'failed'];
        $postTypes = ['feed', 'video', 'reel'];
        
        // Get galleries and videos for media
        $galleries = Gallery::all();
        $videos = Video::all();
        
        for ($i = 0; $i < min($postsCount, $socialAccounts->count() * 5); $i++) {
            $account = $socialAccounts->random();
            $postType = $postTypes[$i % 3];
            $status = $statuses[$i % 3];
            
            // Determine media based on post type
            $mediaUrl = null;
            $mediaType = null;
            
            if ($postType === 'reel' && $videos->count() > 0) {
                $video = $videos->random();
                $mediaUrl = $video->file_path;
                $mediaType = 'video';
            } elseif ($postType === 'video' && $galleries->count() > 0) {
                $gallery = $galleries->random();
                $mediaUrl = $gallery->file_path;
                $mediaType = 'image';
            } elseif ($galleries->count() > 0) {
                $gallery = $galleries->random();
                $mediaUrl = $gallery->file_path;
                $mediaType = 'image';
            }
            
            $content = '';
            if ($postType === 'feed') {
                $content = "Dummy Feed post #" . ($i + 1) . ". Check out our latest update! \n\n#feed #dummy #autopost";
            } elseif ($postType === 'video') {
                $content = "Dummy Video post #" . ($i + 1) . " - Watch this amazing content! \n\n#video #dummy";
            } else {
                $content = "Dummy Reel #" . ($i + 1) . " - Watch this amazing reel! \n\n#reel #trending #dummy";
            }
            
            // Vary the scheduled time across different dates
            $scheduledTime = null;
            $postedAt = null;
            
            if ($status === 'posted') {
                // Past dates for posted
                $scheduledTime = now()->subDays(rand(1, 30))->subHours(rand(0, 23));
                $postedAt = $scheduledTime->copy()->addMinutes(rand(5, 60));
            } elseif ($status === 'failed') {
                // Past dates for failed
                $scheduledTime = now()->subDays(rand(1, 15));
            } else {
                // Future dates for pending
                $scheduledTime = now()->addDays(rand(1, 30))->addHours(rand(1, 12));
            }
            
            $scheduledPost = ScheduledPost::create([
                'user_id' => $account->user_id,
                'social_media_account_id' => $account->id,
                'content' => $content,
                'post_type' => $postType,
                'media_url' => $mediaUrl,
                'media_type' => $mediaType,
                'scheduled_time' => $scheduledTime,
                'status' => $status,
                'posted_at' => $postedAt,
            ]);
            
            // Create PostHistory for posted and failed posts
            if ($status === 'posted' || $status === 'failed') {
                PostHistory::create([
                    'user_id' => $account->user_id,
                    'social_media_account_id' => $account->id,
                    'scheduled_post_id' => $scheduledPost->id,
                    'content' => $content,
                    'media_url' => $mediaUrl,
                    'platform' => $account->platform,
                    'platform_post_id' => 'dummy_post_' . $scheduledPost->id . '_' . time(),
                    'post_type' => 'scheduled',
                    'posted_at' => $postedAt,
                    'response_data' => $status === 'posted' ? ['message' => 'Post published successfully'] : null,
                    'success' => $status === 'posted',
                    'error_message' => $status === 'failed' ? 'Simulated failure for demo' : null,
                    'post_metadata' => [
                        'post_type' => $postType,
                        'media_type' => $mediaType,
                    ],
                ]);
            }
        }
        
        // Add extra PostHistory records for dashboard to look better
        $this->seedExtraPostHistory($socialAccounts);
    }
    
    private function seedExtraPostHistory($socialAccounts)
    {
        if ($socialAccounts->isEmpty()) {
            return;
        }
        
        $platforms = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'tiktok', 'threads'];
        $postTypes = ['feed', 'video', 'reel'];
        
        // Create 50 more posts for dashboard analytics
        for ($i = 0; $i < 50; $i++) {
            $account = $socialAccounts->random();
            $platform = $platforms[array_rand($platforms)];
            $postType = $postTypes[array_rand($postTypes)];
            $isSuccess = rand(1, 100) <= 85; // 85% success rate
            
            // Random date within last 30 days
            $postedAt = now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            $content = '';
            if ($postType === 'feed') {
                $content = "Amazing feed post #" . ($i + 1) . "! Check this out! \n\n#socialmedia #marketing #autopost";
            } elseif ($postType === 'video') {
                $content = "Check out this video #" . ($i + 1) . "! Very informative content. \n\n#video #content";
            } else {
                $content = "Trending reel #" . ($i + 1) . "! You need to see this! \n\n#reel #trending";
            }
            
            PostHistory::create([
                'user_id' => $account->user_id,
                'social_media_account_id' => $account->id,
                'scheduled_post_id' => null,
                'content' => $content,
                'media_url' => null,
                'platform' => $platform,
                'platform_post_id' => 'ext_' . $platform . '_' . $i . '_' . time(),
                'post_type' => 'manual',
                'posted_at' => $postedAt,
                'response_data' => $isSuccess ? [
                    'message' => 'Post published successfully',
                    'post_id' => 'post_' . rand(100000, 999999),
                    'platform' => $platform,
                ] : null,
                'success' => $isSuccess,
                'error_message' => $isSuccess ? null : 'Connection timeout - please try again',
                'post_metadata' => [
                    'post_type' => $postType,
                    'scheduling_type' => 'manual',
                    'engagement' => rand(10, 1000),
                ],
            ]);
        }
    }
    
    private function seedSubscriptionRefunds($users, $userPackages): void
    {
        $reasons = [
            'Product not as described',
            'Accidental purchase',
            'Service not provided',
            'Technical issues',
            'Request by customer',
        ];
        
        $statuses = [STATUS_PENDING, STATUS_SUCCESS, STATUS_REJECT];
        
        foreach ($users->take(5) as $user) {
            if ($userPackages->isEmpty()) {
                continue;
            }
            
            $userPackage = $userPackages->random();
            $payment = Payment::find($userPackage->payment_id);
            
            if (!$payment) {
                continue;
            }
            
            SubscriptionRefund::create([
                'user_id' => $user->id,
                'user_package_id' => $userPackage->id,
                'payment_id' => $payment->id,
                'transaction_id' => $payment->id,
                'transaction_hash' => $payment->tnxId,
                'refund_amount' => rand(10, 100),
                'buy_amount' => $payment->grand_total,
                'reasons' => $reasons[array_rand($reasons)],
                'status' => $statuses[array_rand($statuses)],
            ]);
        }
    }

    /**
     * Seed landing blogs with dummy data
     */
    private function seedBlogs(int $count, int $createdBy): void
    {
        $titles = [
            'Getting Started with Social Media Marketing',
            '10 Tips for Growing Your Online Presence',
            'How to Schedule Posts for Maximum Engagement',
            'The Future of AI in Content Creation',
            'Best Practices for Multi-Platform Posting',
            'Understanding Social Media Analytics',
            'How to Build a Strong Brand Identity',
            'Maximizing Your ROI with Paid Advertising',
            'Creating Viral Content That Converts',
            'The Power of User-Generated Content',
        ];

        $descriptions = [
            'Learn the fundamentals of social media marketing and how to get started with your brand.',
            'Discover proven strategies to grow your followers and increase your online visibility.',
            'Master the art of scheduling posts to reach your audience at the optimal times.',
            'Explore how artificial intelligence is revolutionizing content creation and marketing.',
            'Best practices for managing your presence across multiple social media platforms.',
            'Understanding your metrics is crucial for growth. Learn how to read and use analytics.',
            'Build a recognizable and trustworthy brand that resonates with your target audience.',
            'Learn how to allocate your advertising budget for maximum return on investment.',
            'Create shareable content that drives engagement and conversions for your business.',
            'Leverage your customers\' content to build trust and authenticity for your brand.',
        ];

        // Create blogs directory if it doesn't exist
        $blogDir = 'uploads/blogs';
        if (!Storage::disk('public')->exists($blogDir)) {
            Storage::disk('public')->makeDirectory($blogDir);
        }

        $blogColors = [
            ['r' => 66, 'g' => 135, 'b' => 245],     // Blue
            ['r' => 126, 'g' => 211, 'b' => 33],    // Green
            ['r' => 245, 'g' => 166, 'b' => 35],    // Orange
            ['r' => 208, 'g' => 2, 'b' => 27],      // Red
            ['r' => 144, 'g' => 19, 'b' => 254],    // Purple
            ['r' => 80, 'g' => 227, 'b' => 194],    // Teal
            ['r' => 255, 'g' => 87, 'b' => 51],     // Coral
            ['r' => 63, 'g' => 81, 'b' => 181],     // Indigo
        ];

        for ($i = 0; $i < min($count, count($titles)); $i++) {
            $title = $titles[$i];
            
            if (LandingBlog::where('title', $title)->exists()) {
                continue;
            }

            // Generate a blog image
            $imageFileName = 'blog-' . Str::slug($title) . '-' . time() . '.jpg';
            $imagePath = $blogDir . '/' . $imageFileName;
            $color = $blogColors[$i % count($blogColors)];
            $this->generateBlogImage($imagePath, $color, $title);

            LandingBlog::create([
                'title' => $title,
                'description' => $descriptions[$i],
                'date' => now()->subDays(rand(1, 60))->format('Y-m-d'),
                'url' => 'https://example.com/blog/' . Str::slug($title),
                'image' => $imagePath,
                'status' => STATUS_ACTIVE,
            ]);
        }
    }

    /**
     * Generate a blog image with colored background
     */
    private function generateBlogImage(string $path, array $color, string $title): void
    {
        $width = 800;
        $height = 400;
        
        // Create image resource
        $image = imagecreatetruecolor($width, $height);
        
        // Fill background with the specified color
        $bgColor = imagecolorallocate($image, $color['r'], $color['g'], $color['b']);
        imagefill($image, 0, 0, $bgColor);
        
        // Add some variation - draw a lighter rectangle in center
        $lighterColor = imagecolorallocate($image, 
            min(255, $color['r'] + 30), 
            min(255, $color['g'] + 30), 
            min(255, $color['b'] + 30)
        );
        imagefilledrectangle($image, 50, 50, 750, 350, $lighterColor);
        
        // Save as JPEG
        imagejpeg($image, storage_path('app/public/' . $path), 85);
        imagedestroy($image);
    }

    /**
     * Seed pages with dummy data
     */
    private function seedPages(int $count): void
    {
        $titles = [
            'About Us',
            'Contact Us',
            'Privacy Policy',
            'Terms and Conditions',
            'Refund Policy',
            'Pricing',
            'Services',
            'FAQ',
        ];

        $descriptions = [
            'Learn more about our company and our mission to help businesses grow their social media presence.',
            'Get in touch with our team for any inquiries or support you may need.',
            'Understand how we collect, use, and protect your personal information.',
            'Read our terms and conditions for using our platform and services.',
            'Learn about our refund policy and how we handle refund requests.',
            'Explore our pricing plans and find the right package for your needs.',
            'Discover the range of services we offer to help boost your social media marketing.',
            'Find answers to commonly asked questions about our platform.',
        ];

        $metaTitles = [
            'About Us - AutoPost',
            'Contact Us - Get in Touch',
            'Privacy Policy - AutoPost',
            'Terms and Conditions - AutoPost',
            'Refund Policy - AutoPost',
            'Pricing Plans - AutoPost',
            'Our Services - AutoPost',
            'Frequently Asked Questions - AutoPost',
        ];

        // Create pages directory if it doesn't exist
        $pageDir = 'uploads/pages';
        if (!Storage::disk('public')->exists($pageDir)) {
            Storage::disk('public')->makeDirectory($pageDir);
        }

        $pageColors = [
            ['r' => 66, 'g' => 135, 'b' => 245],     // Blue
            ['r' => 126, 'g' => 211, 'b' => 33],    // Green
            ['r' => 245, 'g' => 166, 'b' => 35],    // Orange
            ['r' => 208, 'g' => 2, 'b' => 27],      // Red
            ['r' => 144, 'g' => 19, 'b' => 254],    // Purple
            ['r' => 80, 'g' => 227, 'b' => 194],    // Teal
            ['r' => 255, 'g' => 87, 'b' => 51],     // Coral
            ['r' => 63, 'g' => 81, 'b' => 181],     // Indigo
        ];

        for ($i = 0; $i < min($count, count($titles)); $i++) {
            $title = $titles[$i];
            $slug = Str::slug($title);
            
            if (Page::where('slug', $slug)->exists()) {
                continue;
            }

            // Generate OG image
            $imageFileName = 'page-' . Str::slug($title) . '-' . time() . '.jpg';
            $imagePath = $pageDir . '/' . $imageFileName;
            $color = $pageColors[$i % count($pageColors)];
            $this->generatePageImage($imagePath, $color, $title);

            Page::create([
                'en_title' => $title,
                'en_description' => $descriptions[$i],
                'slug' => $slug,
                'meta_title' => $metaTitles[$i],
                'meta_description' => $descriptions[$i],
                'meta_keywords' => implode(', ', explode(' ', $title)) . ', autopost, social media',
                'og_image' => $imagePath,
            ]);
        }
    }

    /**
     * Generate a page OG image with colored background
     */
    private function generatePageImage(string $path, array $color, string $title): void
    {
        $width = 1200;
        $height = 630;
        
        // Create image resource
        $image = imagecreatetruecolor($width, $height);
        
        // Fill background with the specified color
        $bgColor = imagecolorallocate($image, $color['r'], $color['g'], $color['b']);
        imagefill($image, 0, 0, $bgColor);
        
        // Add some variation - draw a lighter rectangle in center
        $lighterColor = imagecolorallocate($image, 
            min(255, $color['r'] + 30), 
            min(255, $color['g'] + 30), 
            min(255, $color['b'] + 30)
        );
        imagefilledrectangle($image, 100, 80, 1100, 550, $lighterColor);
        
        // Save as JPEG
        imagejpeg($image, storage_path('app/public/' . $path), 85);
        imagedestroy($image);
    }

    /**
     * Seed menus with dummy data
     */
    private function seedMenus(int $count): void
    {
        // Dynamic menus (type = 2)
        $dynamicMenus = [
            ['name' => 'Home', 'slug' => 'home', 'url' => '/', 'type' => 2],
            ['name' => 'About Us', 'slug' => 'about-us', 'url' => '/page/about-us', 'type' => 2],
            ['name' => 'Services', 'slug' => 'services', 'url' => '/page/services', 'type' => 2],
            ['name' => 'Pricing', 'slug' => 'pricing', 'url' => '/pricing', 'type' => 2],
            ['name' => 'Blog', 'slug' => 'blog', 'url' => '/blog', 'type' => 2],
            ['name' => 'FAQ', 'slug' => 'faq', 'url' => '/page/faq', 'type' => 2],
            ['name' => 'Contact', 'slug' => 'contact', 'url' => '/page/contact-us', 'type' => 2],
        ];

        // Footer-left / Company menus (type = 3)
        $footerLeftMenus = [
            ['name' => 'About Us', 'slug' => 'footer-about-us', 'url' => '/page/about-us', 'type' => 3],
            ['name' => 'Careers', 'slug' => 'footer-careers', 'url' => '/page/careers', 'type' => 3],
            ['name' => 'Blog', 'slug' => 'footer-blog', 'url' => '/blog', 'type' => 3],
            ['name' => 'Press', 'slug' => 'footer-press', 'url' => '/page/press', 'type' => 3],
        ];

        // Footer-right / Support menus (type = 4)
        $footerRightMenus = [
            ['name' => 'Help Center', 'slug' => 'footer-help-center', 'url' => '/page/help-center', 'type' => 4],
            ['name' => 'Contact Us', 'slug' => 'footer-contact-us', 'url' => '/page/contact-us', 'type' => 4],
            ['name' => 'Privacy Policy', 'slug' => 'footer-privacy-policy', 'url' => '/page/privacy-policy', 'type' => 4],
            ['name' => 'Terms & Conditions', 'slug' => 'footer-terms-conditions', 'url' => '/page/terms-and-conditions', 'type' => 4],
            ['name' => 'Refund Policy', 'slug' => 'footer-refund-policy', 'url' => '/page/refund-policy', 'type' => 4],
        ];

        // Seed dynamic menus
        foreach ($dynamicMenus as $menuData) {
            if (Menu::where('slug', $menuData['slug'])->where('type', 2)->exists()) {
                continue;
            }

            Menu::create([
                'name' => $menuData['name'],
                'slug' => $menuData['slug'],
                'url' => $menuData['url'],
                'type' => $menuData['type'],
                'status' => STATUS_ACTIVE,
            ]);
        }

        // Seed footer-left menus
        foreach ($footerLeftMenus as $menuData) {
            if (Menu::where('slug', $menuData['slug'])->where('type', 3)->exists()) {
                continue;
            }

            Menu::create([
                'name' => $menuData['name'],
                'slug' => $menuData['slug'],
                'url' => $menuData['url'],
                'type' => $menuData['type'],
                'status' => STATUS_ACTIVE,
            ]);
        }

        // Seed footer-right menus
        foreach ($footerRightMenus as $menuData) {
            if (Menu::where('slug', $menuData['slug'])->where('type', 4)->exists()) {
                continue;
            }

            Menu::create([
                'name' => $menuData['name'],
                'slug' => $menuData['slug'],
                'url' => $menuData['url'],
                'type' => $menuData['type'],
                'status' => STATUS_ACTIVE,
            ]);
        }
    }

    /**
     * Seed video gallery with dummy videos and thumbnails
     */
    private function seedVideoGallery($users, $adminUsers, int $videoGalleryCount = 8): void
    {
        $platforms = ['youtube', 'tiktok', 'instagram', 'facebook'];
        
        // Create video and thumbnail directories if they don't exist
        $videoDir = 'uploads/videos';
        $thumbnailDir = 'uploads/videos/thumbnails';
        
        if (!Storage::disk('public')->exists($videoDir)) {
            Storage::disk('public')->makeDirectory($videoDir);
        }
        if (!Storage::disk('public')->exists($thumbnailDir)) {
            Storage::disk('public')->makeDirectory($thumbnailDir);
        }
        
        $videoTitles = [
            'Product Demo 2024',
            'Brand Introduction',
            'Customer Testimonial',
            'How-To Tutorial',
            'Event Highlights',
            'Behind the Scenes',
            'Company Culture',
            'New Feature Walkthrough',
            'Success Story',
            'Holiday Special',
        ];
        
        $durations = ['0:30', '1:15', '2:45', '5:00', '10:30', '15:00'];
        
        $colors = [
            ['r' => 255, 'g' => 0, 'b' => 0],        // YouTube Red
            ['r' => 0, 'g' => 0, 'b' => 0],          // TikTok Black
            ['r' => 131, 'g' => 56, 'b' => 236],     // Instagram Purple
            ['r' => 24, 'g' => 119, 'b' => 242],     // Facebook Blue
        ];
        
        // Seed videos for regular users (user_id = 2)
        $videoCount = 0;
        $videosPerUser = max(1, (int)($videoGalleryCount / 2 / max(1, $users->count())));
        foreach ($users->take(min($videoGalleryCount / 2, $users->count())) as $user) {
            for ($j = 0; $j < $videosPerUser; $j++) {
                $platform = $platforms[$j % count($platforms)];
                $title = $videoTitles[$videoCount % count($videoTitles)];
                $color = $colors[$j % count($colors)];
                
                // Generate video filename and path
                $videoFileName = 'video-user-' . $user->id . '-' . $videoCount . '-' . time() . '.mp4';
                $videoPath = $videoDir . '/' . $videoFileName;
                
                // Generate thumbnail filename and path
                $thumbnailFileName = 'thumb-user-' . $user->id . '-' . $videoCount . '-' . time() . '.jpg';
                $thumbnailPath = $thumbnailDir . '/' . $thumbnailFileName;
                
                // Generate a thumbnail image (placeholder)
                $this->generateVideoThumbnail($thumbnailPath, $color, $title);
                
                // Create a small placeholder video file (just a text file for demo purposes)
                Storage::disk('public')->put($videoPath, 'Dummy video file for ' . $title);
                
                Video::create([
                    'user_id' => 2, // User ID 2
                    'title' => $title . ' (User)',
                    'platform' => $platform,
                    'file_name' => $videoFileName,
                    'file_path' => $videoPath,
                    'file_type' => 'video',
                    'file_extension' => 'mp4',
                    'file_size' => rand(1000000, 50000000),
                    'duration' => $durations[array_rand($durations)],
                    'thumbnail_path' => $thumbnailPath,
                    'status' => STATUS_ACTIVE,
                ]);
                
                $videoCount++;
            }
        }
        
        // Seed videos for admin users
        $videoCount = 0;
        foreach ($adminUsers->take(3) as $adminUser) {
            for ($j = 0; $j < 2; $j++) {
                $platform = $platforms[($j + 2) % count($platforms)];
                $title = $videoTitles[($videoCount + 5) % count($videoTitles)];
                $color = $colors[($j + 2) % count($colors)];
                
                // Generate video filename and path
                $videoFileName = 'video-admin-' . $adminUser->id . '-' . $videoCount . '-' . time() . '.mp4';
                $videoPath = $videoDir . '/' . $videoFileName;
                
                // Generate thumbnail filename and path
                $thumbnailFileName = 'thumb-admin-' . $adminUser->id . '-' . $videoCount . '-' . time() . '.jpg';
                $thumbnailPath = $thumbnailDir . '/' . $thumbnailFileName;
                
                // Generate a thumbnail image (placeholder)
                $this->generateVideoThumbnail($thumbnailPath, $color, $title);
                
                // Create a small placeholder video file
                Storage::disk('public')->put($videoPath, 'Dummy video file for ' . $title);
                
                Video::create([
                    'user_id' => $adminUser->id,
                    'title' => $title . ' (Admin)',
                    'platform' => $platform,
                    'file_name' => $videoFileName,
                    'file_path' => $videoPath,
                    'file_type' => 'video',
                    'file_extension' => 'mp4',
                    'file_size' => rand(1000000, 50000000),
                    'duration' => $durations[array_rand($durations)],
                    'thumbnail_path' => $thumbnailPath,
                    'status' => STATUS_ACTIVE,
                ]);
                
                $videoCount++;
            }
        }
    }

    /**
     * Generate a video thumbnail image
     */
    private function generateVideoThumbnail(string $path, array $color, string $title): void
    {
        $width = 640;
        $height = 360;
        
        // Create image resource
        $image = imagecreatetruecolor($width, $height);
        
        // Fill background with dark color
        $bgColor = imagecolorallocate($image, 30, 30, 30);
        imagefill($image, 0, 0, $bgColor);
        
        // Add platform color bar at bottom
        $platformColor = imagecolorallocate($image, $color['r'], $color['g'], $color['b']);
        imagefilledrectangle($image, 0, 320, 640, 360, $platformColor);
        
        // Add play button circle (simplified)
        $circleColor = imagecolorallocate($image, 255, 255, 255);
        imagefilledellipse($image, 320, 180, 80, 80, $circleColor);
        
        $playColor = imagecolorallocate($image, $color['r'], $color['g'], $color['b']);
        // Draw play triangle (simplified as small rectangle)
        imagefilledrectangle($image, 308, 168, 332, 192, $playColor);
        
        // Save as JPEG
        imagejpeg($image, storage_path('app/public/' . $path), 85);
        imagedestroy($image);
    }
    
    private function seedRolesAndPermissions(): void
    {
        // Create dummy roles for both Admin and Super Admin users
        $rolesData = [
            // Super Admin roles (user_type = USER_ROLE_SUPER_ADMIN = 1)
            ['name' => 'Super Admin', 'display_name' => 'Super Admin', 'user_type' => USER_ROLE_SUPER_ADMIN, 'status' => STATUS_ACTIVE],
            ['name' => 'Super Manager', 'display_name' => 'Super Manager', 'user_type' => USER_ROLE_SUPER_ADMIN, 'status' => STATUS_ACTIVE],
            // Admin roles (user_type = USER_ROLE_ADMIN = 2)
            ['name' => 'Admin', 'display_name' => 'Admin', 'user_type' => USER_ROLE_ADMIN, 'status' => STATUS_ACTIVE],
            ['name' => 'Manager', 'display_name' => 'Manager', 'user_type' => USER_ROLE_ADMIN, 'status' => STATUS_ACTIVE],
            ['name' => 'Editor', 'display_name' => 'Editor', 'user_type' => USER_ROLE_ADMIN, 'status' => STATUS_ACTIVE],
            ['name' => 'Moderator', 'display_name' => 'Moderator', 'user_type' => USER_ROLE_ADMIN, 'status' => STATUS_ACTIVE],
        ];
        
        foreach ($rolesData as $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleData['name'], 'user_type' => $roleData['user_type']],
                ['display_name' => $roleData['display_name'], 'status' => $roleData['status']]
            );
            
            // Create some dummy permissions if none exist
            $permissions = ['Manage Dashboard', 'Manage Users', 'Manage Posts', 'Manage Settings', 'View Reports', 'Manage Payments', 'Manage Packages'];
            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate(['name' => $permissionName]);
                if (!$role->hasPermissionTo($permission)) {
                    $role->givePermissionTo($permission);
                }
            }
        }
    }
}