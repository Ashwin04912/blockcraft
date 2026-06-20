<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\UiBlock;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::firstOrCreate(
            ['email' => 'admin@blockcraft.test'],
            ['name' => 'BlockCraft Admin', 'password' => Hash::make('password')]
        );

        // 1. Company Corporate Site
        $company = Site::create([
            'name' => 'Acme Corporation',
            'slug' => 'company',
            'description' => 'Official corporate website for Acme.',
            'owner_id' => $owner->id,
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
                'cards' => [
                    [
                        'title' => 'Enterprise Grade Solutions',
                        'description' => 'Scalable, secure software built for Fortune 500 companies and their compliance requirements.',
                        'image_url' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&q=80&w=800',
                    ],
                    [
                        'title' => 'Dedicated Support',
                        'description' => 'A named account team and 24/7 incident response, not a ticket queue.',
                        'image_url' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&q=80&w=800',
                    ],
                    [
                        'title' => 'Global Infrastructure',
                        'description' => 'Multi-region deployment with 99.99% uptime SLA across 120 countries.',
                        'image_url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&q=80&w=800',
                    ],
                ],
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
                    ['label' => 'Active Users', 'value' => '1.2M+', 'icon' => 'users'],
                    ['label' => 'Uptime', 'value' => '99.99%', 'icon' => 'chart'],
                    ['label' => 'Countries', 'value' => '120', 'icon' => 'star'],
                    ['label' => 'Avg. Response', 'value' => '<2h', 'icon' => 'clock'],
                ],
                'size' => 'lg',
            ]
        ]);

        UiBlock::create([
            'site_id' => $company->id,
            'title' => 'Get Started CTA',
            'type' => 'cta',
            'is_active' => true,
            'display_order' => 4,
            'config' => [
                'heading' => 'Ready to scale with Acme?',
                'subheading' => 'Talk to our sales team and get a tailored rollout plan within 48 hours.',
                'button_text' => 'Contact Sales',
                'button_link' => '#',
                'style' => 'primary',
            ]
        ]);

        UiBlock::create([
            'site_id' => $company->id,
            'title' => 'Frequently Asked Questions',
            'type' => 'accordion',
            'is_active' => true,
            'display_order' => 5,
            'config' => [
                'items' => [
                    ['question' => 'Do you offer a free trial?', 'answer' => 'Yes — every plan includes a 14-day trial, no credit card required.'],
                    ['question' => 'Can I migrate from another provider?', 'answer' => 'Our onboarding team handles data migration at no extra cost for Enterprise plans.'],
                    ['question' => 'What is your uptime guarantee?', 'answer' => 'We guarantee 99.99% uptime, backed by service credits in our SLA.'],
                ],
            ]
        ]);

        UiBlock::create([
            'site_id' => $company->id,
            'title' => 'Main Footer',
            'type' => 'footer',
            'is_active' => true,
            'display_order' => 6,
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
            'owner_id' => $owner->id,
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
            'title' => 'Newsletter CTA',
            'type' => 'cta',
            'is_active' => true,
            'display_order' => 3,
            'config' => [
                'heading' => 'Get 10% off your first drop',
                'subheading' => 'Join the list for early access to limited releases.',
                'button_text' => 'Sign Up',
                'button_link' => '#',
                'style' => 'secondary',
            ]
        ]);

        UiBlock::create([
            'site_id' => $store->id,
            'title' => 'Store Footer',
            'type' => 'footer',
            'is_active' => true,
            'display_order' => 4,
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
