<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\UiBlock;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Company Corporate Site
        $company = Site::create([
            'name' => 'Acme Corporation',
            'slug' => 'company',
            'description' => 'Official corporate website for Acme.',
        ]);

        UiBlock::create([
            'site_id' => $company->id,
            'title' => 'Main Navigation',
            'type' => 'header',
            'is_active' => true,
            'display_order' => 0,
            'config' => [
                'bg_style' => 'light',
                'logo_text' => 'Acme Corp',
                'nav_links' => [
                    ['label' => 'Products', 'url' => '#'],
                    ['label' => 'Solutions', 'url' => '#'],
                    ['label' => 'About Us', 'url' => '#'],
                ],
                'cta_label' => 'Contact Sales',
                'cta_url' => '#'
            ]
        ]);

        UiBlock::create([
            'site_id' => $company->id,
            'title' => 'Main Hero',
            'type' => 'banner',
            'is_active' => true,
            'display_order' => 1,
            'config' => [
                'image_url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=2000',
                'link' => '#',
                'size' => 'lg',
            ]
        ]);

        UiBlock::create([
            'site_id' => $company->id,
            'title' => 'Core Values',
            'type' => 'card',
            'is_active' => true,
            'display_order' => 2,
            'config' => [
                'title' => 'Enterprise Grade Solutions',
                'description' => 'We build scalable and secure software for Fortune 500 companies.',
                'image_url' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&q=80&w=800',
                'size' => 'md',
            ]
        ]);

        UiBlock::create([
            'site_id' => $company->id,
            'title' => 'Key Metrics',
            'type' => 'stats',
            'is_active' => true,
            'display_order' => 3,
            'config' => [
                'stats' => [
                    ['label' => 'Active Users', 'value' => '1.2M+'],
                    ['label' => 'Uptime', 'value' => '99.99%'],
                    ['label' => 'Countries', 'value' => '120'],
                ],
                'size' => 'lg',
            ]
        ]);

        UiBlock::create([
            'site_id' => $company->id,
            'title' => 'Main Footer',
            'type' => 'footer',
            'is_active' => true,
            'display_order' => 4,
            'config' => [
                'brand' => 'Acme Corp',
                'tagline' => 'Building the future of enterprise software since 2010.',
                'copyright' => '© 2026 Acme Corporation. All rights reserved.',
                'links' => [
                    ['label' => 'Privacy Policy', 'url' => '#'],
                    ['label' => 'Terms of Service', 'url' => '#'],
                    ['label' => 'Security', 'url' => '#'],
                ],
                'social_links' => [
                    ['platform' => 'Twitter', 'url' => '#'],
                    ['platform' => 'LinkedIn', 'url' => '#'],
                    ['platform' => 'GitHub', 'url' => '#'],
                ]
            ]
        ]);


        // 2. E-commerce Store
        $store = Site::create([
            'name' => 'Sneaker Drop',
            'slug' => 'store',
            'description' => 'Premium sneaker marketplace landing page.',
        ]);

        UiBlock::create([
            'site_id' => $store->id,
            'title' => 'Store Header',
            'type' => 'header',
            'is_active' => true,
            'display_order' => 0,
            'config' => [
                'bg_style' => 'dark',
                'logo_text' => 'Sneaker Drop',
                'nav_links' => [
                    ['label' => 'New Arrivals', 'url' => '#'],
                    ['label' => 'Men', 'url' => '#'],
                    ['label' => 'Women', 'url' => '#'],
                    ['label' => 'Sale', 'url' => '#'],
                ],
                'cta_label' => 'Cart (0)',
                'cta_url' => '#'
            ]
        ]);

        UiBlock::create([
            'site_id' => $store->id,
            'title' => 'Promo Banner',
            'type' => 'banner',
            'is_active' => true,
            'display_order' => 1,
            'config' => [
                'image_url' => 'https://images.unsplash.com/photo-1556906781-9a412961c28c?auto=format&fit=crop&q=80&w=2000',
                'link' => '/sale',
                'size' => 'md',
            ]
        ]);

        UiBlock::create([
            'site_id' => $store->id,
            'title' => 'Features List',
            'type' => 'list',
            'is_active' => true,
            'display_order' => 2,
            'config' => [
                'items' => [
                    'Free express shipping on orders over $150',
                    'Authenticity guaranteed on all products',
                    '30-day hassle-free returns',
                ],
                'size' => 'md',
            ]
        ]);
        
        UiBlock::create([
            'site_id' => $store->id,
            'title' => 'Store Footer',
            'type' => 'footer',
            'is_active' => true,
            'display_order' => 3,
            'config' => [
                'brand' => 'Sneaker Drop',
                'tagline' => 'Your premium destination for exclusive footwear.',
                'links' => [
                    ['label' => 'FAQ', 'url' => '#'],
                    ['label' => 'Shipping', 'url' => '#'],
                    ['label' => 'Returns', 'url' => '#'],
                ]
            ]
        ]);

        $this->command->info('Seeded 2 sites with demo blocks including headers and footers.');
    }
}
