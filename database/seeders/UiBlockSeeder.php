<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\UiBlock;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UiBlockSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::firstOrCreate(
            ['email' => 'admin@blockcraft.test'],
            ['name' => 'BlockCraft Admin', 'password' => Hash::make('password')]
        );

        $site = Site::firstOrCreate(
            ['slug' => 'demo'],
            ['name' => 'Demo Site', 'description' => 'Standalone demo seeded by UiBlockSeeder.', 'owner_id' => $owner->id]
        );

        UiBlock::where('site_id', $site->id)->delete();

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
                'title'         => 'Why Teams Choose BlockCraft',
                'type'          => 'card',
                'is_active'     => true,
                'display_order' => 2,
                'config'        => [
                    'cards' => [
                        [
                            'title'       => 'Connect Everything',
                            'description' => 'Integrate with your favourite tools in minutes — 200+ third-party services out of the box, from CRMs to analytics.',
                            'image_url'   => 'https://images.unsplash.com/photo-1558655146-d09347e92766?w=800&h=400&fit=crop',
                        ],
                        [
                            'title'       => 'Bank-grade Security',
                            'description' => 'SOC 2 Type II certified, end-to-end encryption, and role-based access control keep your data safe at every layer.',
                            'image_url'   => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=800&h=400&fit=crop',
                        ],
                        [
                            'title'       => 'Built for Scale',
                            'description' => 'From a single landing page to hundreds of sites — the same block engine handles both without code changes.',
                            'image_url'   => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&h=400&fit=crop',
                        ],
                    ],
                ],
            ],

            // ── CTA ────────────────────────────────────────────────────────
            [
                'title'         => 'Trial CTA',
                'type'          => 'cta',
                'is_active'     => true,
                'display_order' => 3,
                'config'        => [
                    'heading'     => 'Start building in minutes',
                    'subheading'  => 'No credit card required. Cancel anytime.',
                    'button_text' => 'Start Free Trial',
                    'button_link' => 'https://example.com/signup',
                    'style'       => 'primary',
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
                        ['label' => 'Active Users',     'value' => '124K', 'icon' => 'users'],
                        ['label' => 'Blocks Served',    'value' => '3.2M', 'icon' => 'chart'],
                        ['label' => 'Uptime',           'value' => '99.9%', 'icon' => 'clock'],
                        ['label' => 'Integrations',     'value' => '200+', 'icon' => 'star'],
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

            // ── ACCORDION ──────────────────────────────────────────────────
            [
                'title'         => 'Common Questions',
                'type'          => 'accordion',
                'is_active'     => true,
                'display_order' => 8,
                'config'        => [
                    'items' => [
                        ['question' => 'How do I add a new block type?', 'answer' => 'Create a Blade partial under resources/views/client/blocks/, then add it to the supportedTypes() list.'],
                        ['question' => 'Does reordering require a page reload?', 'answer' => 'No — drag-and-drop reorder saves instantly via AJAX in the visual editor.'],
                    ],
                ],
            ],
        ];

        foreach ($blocks as $block) {
            $block['site_id'] = $site->id;
            UiBlock::create($block);
        }

        $activeCount = count(array_filter($blocks, fn ($b) => $b['is_active'] ?? true));

        $this->command->info(
            'Seeded ' . count($blocks) . ' UI blocks (' . $activeCount . ' active, '
            . (count($blocks) - $activeCount) . ' inactive as demo) for site "' . $site->slug . '".'
        );
    }
}
