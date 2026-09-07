<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(BankSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(GatewaySeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(UserSeeder::class);
        // $this->call(TwitterConfigSeeder::class);
        // $this->call(InstagramConfigSeeder::class);
        // $this->call(LinkedinConfigSeeder::class);
        // $this->call(TikTokConfigSeeder::class);
        // $this->call(FacebookConfigSeeder::class);
        // $this->call(YouTubeConfigSeeder::class);
        // $this->call(ThreadsConfigSeeder::class);




        // if (filter_var(env('ENABLE_DUMMY_DATA', false), FILTER_VALIDATE_BOOLEAN)) {
        //     $this->call(DummyDataSeeder::class);
        // }
    }
}
