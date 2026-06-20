<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\UiBlock;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::firstOrCreate(
            ['email' => 'admin@blockcraft.test'],
            ['name' => 'BlockCraft Admin', 'password' => Hash::make('password')]
        );

        $site = Site::firstOrCreate(
            ['slug' => 'portfolio'],
            ['name' => 'Designer Portfolio', 'description' => 'A creative portfolio for designers and developers.', 'owner_id' => $owner->id]
        );

        UiBlock::where('site_id', $site->id)->delete();

        $blocks = [
            // ── HEADER ──────────────────────────────────────────────────────
            [
                'title'         => 'Portfolio Header',
                'type'          => 'header',
                'is_active'     => true,
                'display_order' => 0,
                'config'        => [
                    'bg_style'   => 'light',
                    'logo_text'  => 'Alex.Design',
                    'cta_label'  => 'Hire Me',
                    'cta_url'    => '#contact',
                    'nav_links'  => [
                        ['label' => 'Work', 'url' => '#work'],
                        ['label' => 'About', 'url' => '#about'],
                        ['label' => 'Services', 'url' => '#services'],
                    ],
                ],
            ],

            // ── HERO BANNER ─────────────────────────────────────────────────
            [
                'title'         => 'Hero Banner',
                'type'          => 'banner',
                'is_active'     => true,
                'display_order' => 1,
                'config'        => [
                    'image_url' => 'https://images.unsplash.com/photo-1558655146-d09347e92766?w=1280&h=800&fit=crop',
                    'link'      => '#work',
                ],
            ],

            // ── STATS ───────────────────────────────────────────────────────
            [
                'title'         => 'My Achievements',
                'type'          => 'stats',
                'is_active'     => true,
                'display_order' => 2,
                'config'        => [
                    'size'  => 'lg',
                    'stats' => [
                        ['icon' => 'clock', 'label' => 'Years Experience', 'value' => '8+'],
                        ['icon' => 'star', 'label' => 'Projects Delivered', 'value' => '120+'],
                        ['icon' => 'users', 'label' => 'Happy Clients', 'value' => '50+'],
                        ['icon' => 'chart', 'label' => 'Awards Won', 'value' => '12'],
                    ],
                ],
            ],

            // ── PORTFOLIO CARDS ─────────────────────────────────────────────
            [
                'title'         => 'Selected Work',
                'type'          => 'card',
                'is_active'     => true,
                'display_order' => 3,
                'config'        => [
                    'size'  => 'md',
                    'cards' => [
                        [
                            'title'       => 'Fintech Dashboard',
                            'description' => 'A modern analytics dashboard for a leading fintech startup.',
                            'image_url'   => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop',
                        ],
                        [
                            'title'       => 'E-Commerce App',
                            'description' => 'A mobile-first shopping experience with seamless checkout.',
                            'image_url'   => 'https://images.unsplash.com/photo-1555529771-835f59fc5efe?w=800&h=600&fit=crop',
                        ],
                        [
                            'title'       => 'Brand Identity',
                            'description' => 'Complete brand redesign and style guide for a global agency.',
                            'image_url'   => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800&h=600&fit=crop',
                        ],
                    ],
                ],
            ],

            // ── SKILLS LIST ─────────────────────────────────────────────────
            [
                'title'         => 'My Arsenal',
                'type'          => 'list',
                'is_active'     => true,
                'display_order' => 4,
                'config'        => [
                    'layout' => 'horizontal',
                    'size'   => 'md',
                    'items'  => [
                        'UI/UX Design',
                        'Frontend Development',
                        'Figma Prototyping',
                        'Laravel & Vue',
                        'Tailwind CSS',
                        'Motion Graphics',
                    ],
                ],
            ],

            // ── FOOTER ──────────────────────────────────────────────────────
            [
                'title'         => 'Portfolio Footer',
                'type'          => 'footer',
                'is_active'     => true,
                'display_order' => 5,
                'config'        => [
                    'brand'     => 'Alex.Design',
                    'tagline'   => 'Crafting digital experiences that matter.',
                    'copyright' => '© 2026 Alex Design. All rights reserved.',
                    'social_links' => [
                        ['platform' => 'Twitter', 'url' => 'https://twitter.com'],
                        ['platform' => 'Dribbble', 'url' => 'https://dribbble.com'],
                        ['platform' => 'LinkedIn', 'url' => 'https://linkedin.com'],
                    ],
                ],
            ],
        ];

        foreach ($blocks as $block) {
            $block['site_id'] = $site->id;
            UiBlock::create($block);
        }

        $this->command->info('Seeded ' . count($blocks) . ' UI blocks for portfolio site "' . $site->slug . '".');
    }
}
