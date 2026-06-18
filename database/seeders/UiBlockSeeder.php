<?php

namespace Database\Seeders;

use App\Models\UiBlock;
use Illuminate\Database\Seeder;

class UiBlockSeeder extends Seeder
{
    public function run(): void
    {
        UiBlock::truncate();

        $blocks = [
            // ── BANNERS ────────────────────────────────────────────────────
            [
                'title'         => 'Summer Sale — Up to 50% Off',
                'type'          => 'banner',
                'is_active'     => true,
                'display_order' => 0,
                'config'        => [
                    'image_url' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1280&h=400&fit=crop',
                    'link'      => 'https://example.com/summer-sale',
                ],
            ],
            [
                'title'         => 'New Arrivals — Explore the Collection',
                'type'          => 'banner',
                'is_active'     => true,
                'display_order' => 1,
                'config'        => [
                    'image_url' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1280&h=400&fit=crop',
                    'link'      => 'https://example.com/new-arrivals',
                ],
            ],

            // ── CARDS ──────────────────────────────────────────────────────
            [
                'title'         => 'Seamless Integrations',
                'type'          => 'card',
                'is_active'     => true,
                'display_order' => 2,
                'config'        => [
                    'title'       => 'Connect Everything',
                    'description' => 'Integrate with your favourite tools in minutes. Our platform supports 200+ third-party services out of the box, from CRMs to analytics.',
                    'image_url'   => 'https://images.unsplash.com/photo-1558655146-d09347e92766?w=800&h=400&fit=crop',
                ],
            ],
            [
                'title'         => 'Enterprise Security',
                'type'          => 'card',
                'is_active'     => true,
                'display_order' => 3,
                'config'        => [
                    'title'       => 'Bank-grade Security',
                    'description' => 'SOC 2 Type II certified, end-to-end encryption, and role-based access control keep your data safe at every layer.',
                    'image_url'   => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=800&h=400&fit=crop',
                ],
            ],

            // ── LISTS ──────────────────────────────────────────────────────
            [
                'title'         => 'Key Platform Features',
                'type'          => 'list',
                'is_active'     => true,
                'display_order' => 4,
                'config'        => [
                    'items' => [
                        'Dynamic UI blocks — no code deploys needed',
                        'Real-time drag-and-drop reordering',
                        'Instant toggle to show/hide any block',
                        'Type-specific configuration forms',
                        'Zero-downtime content updates',
                    ],
                ],
            ],
            [
                'title'         => 'Getting Started Checklist',
                'type'          => 'list',
                'is_active'     => true,
                'display_order' => 5,
                'config'        => [
                    'items' => [
                        'Run migrations: php artisan migrate --seed',
                        'Start the dev server: composer run dev',
                        'Visit /admin/ui-blocks to manage blocks',
                        'Visit / to see the dynamic client page',
                        'Add a new block type: create a partial + update the form JS',
                    ],
                ],
            ],

            // ── STATS ──────────────────────────────────────────────────────
            [
                'title'         => 'Platform at a Glance',
                'type'          => 'stats',
                'is_active'     => true,
                'display_order' => 6,
                'config'        => [
                    'stats' => [
                        ['label' => 'Active Users',     'value' => '124K'],
                        ['label' => 'Blocks Served',    'value' => '3.2M'],
                        ['label' => 'Uptime',           'value' => '99.9%'],
                        ['label' => 'Integrations',     'value' => '200+'],
                    ],
                ],
            ],
            [
                'title'         => 'This Month\'s Growth',
                'type'          => 'stats',
                'is_active'     => false, // intentionally inactive to demo toggle
                'display_order' => 7,
                'config'        => [
                    'stats' => [
                        ['label' => 'New Sign-ups',   'value' => '+18%'],
                        ['label' => 'Revenue',        'value' => '+24%'],
                        ['label' => 'Support Tickets', 'value' => '-12%'],
                        ['label' => 'NPS Score',      'value' => '72'],
                    ],
                ],
            ],
        ];

        foreach ($blocks as $block) {
            UiBlock::create($block);
        }

        $this->command->info('Seeded ' . count($blocks) . ' UI blocks (7 active, 1 inactive as demo).');
    }
}
