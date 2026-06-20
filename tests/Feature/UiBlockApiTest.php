<?php

namespace Tests\Feature;

use App\Models\Site;
use App\Models\UiBlock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UiBlockApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Site $site;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        $this->site = Site::create([
            'name' => 'Test Site',
            'slug' => 'test-site',
            'description' => 'A site for testing',
            'owner_id' => $this->admin->id,
        ]);
    }

    public function test_can_create_ui_block_via_ajax()
    {
        $payload = [
            'title' => 'Test Header',
            'type' => 'header',
            'is_active' => true,
            'config' => [
                'bg_style' => 'dark',
                'logo_text' => 'My Logo',
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.store', $this->site), $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('ui_blocks', [
            'site_id' => $this->site->id,
            'title' => 'Test Header',
            'type' => 'header',
        ]);
    }

    public function test_can_update_ui_block_via_ajax()
    {
        $block = UiBlock::create([
            'site_id' => $this->site->id,
            'title' => 'Old Title',
            'type' => 'header',
            'is_active' => false,
            'display_order' => 0,
            'config' => []
        ]);

        $payload = [
            'title' => 'Updated Title',
            'type' => 'header',
            'is_active' => true,
            'config' => [
                'bg_style' => 'light',
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->putJson(route('admin.sites.ui-blocks.update', [$this->site, $block]), $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('ui_blocks', [
            'id' => $block->id,
            'title' => 'Updated Title',
            'is_active' => true,
        ]);
    }

    public function test_can_delete_ui_block()
    {
        $block = UiBlock::create([
            'site_id' => $this->site->id,
            'title' => 'To Delete',
            'type' => 'banner',
            'config' => []
        ]);

        $response = $this->actingAs($this->admin)
            ->deleteJson(route('admin.sites.ui-blocks.destroy', [$this->site, $block]));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('ui_blocks', [
            'id' => $block->id,
        ]);
    }

    public function test_can_toggle_ui_block_active_status()
    {
        $block = UiBlock::create([
            'site_id' => $this->site->id,
            'title' => 'Toggle Me',
            'type' => 'banner',
            'is_active' => true,
            'config' => []
        ]);

        $response = $this->actingAs($this->admin)
            ->patchJson(route('admin.sites.ui-blocks.toggle', [$this->site, $block]));

        $response->assertStatus(200)
                 ->assertJson(['is_active' => false]);

        $this->assertDatabaseHas('ui_blocks', [
            'id' => $block->id,
            'is_active' => false,
        ]);
    }

    public function test_can_reorder_ui_blocks()
    {
        $block1 = UiBlock::create([
            'site_id' => $this->site->id,
            'title' => 'Block 1',
            'type' => 'banner',
            'display_order' => 0,
            'config' => []
        ]);

        $block2 = UiBlock::create([
            'site_id' => $this->site->id,
            'title' => 'Block 2',
            'type' => 'banner',
            'display_order' => 1,
            'config' => []
        ]);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.reorder', $this->site), [
                'order' => [$block2->id, $block1->id]
            ]);

        $response->assertStatus(200);

        $this->assertEquals(0, $block2->fresh()->display_order);
        $this->assertEquals(1, $block1->fresh()->display_order);
    }

    public function test_reorder_rejects_block_ids_from_another_site()
    {
        $ownBlock = UiBlock::create([
            'site_id' => $this->site->id,
            'title' => 'Own Block',
            'type' => 'banner',
            'display_order' => 0,
            'config' => [],
        ]);

        $otherSite = Site::create([
            'name' => 'Other Site',
            'slug' => 'other-site',
            'description' => 'A different tenant',
        ]);

        $foreignBlock = UiBlock::create([
            'site_id' => $otherSite->id,
            'title' => 'Foreign Block',
            'type' => 'banner',
            'display_order' => 0,
            'config' => [],
        ]);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.reorder', $this->site), [
                'order' => [$foreignBlock->id, $ownBlock->id],
            ]);

        $response->assertStatus(422);
        $this->assertEquals(0, $foreignBlock->fresh()->display_order);
    }

    public function test_reorder_rejects_incomplete_order_payload()
    {
        UiBlock::create([
            'site_id' => $this->site->id,
            'title' => 'Block A',
            'type' => 'banner',
            'display_order' => 0,
            'config' => [],
        ]);

        $block2 = UiBlock::create([
            'site_id' => $this->site->id,
            'title' => 'Block B',
            'type' => 'banner',
            'display_order' => 1,
            'config' => [],
        ]);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.reorder', $this->site), [
                'order' => [$block2->id],
            ]);

        $response->assertStatus(422);
    }

    public function test_cannot_manage_blocks_on_another_users_site()
    {
        $otherUser = User::factory()->create();
        $otherSite = Site::create([
            'name' => 'Other Owner Site',
            'slug' => 'other-owner-site',
            'owner_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.store', $otherSite), [
                'title' => 'Intruder Block',
                'type'  => 'banner',
                'config' => ['image_url' => 'https://example.com/a.png', 'link' => 'https://example.com'],
            ]);

        $response->assertStatus(403);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sites.ui-blocks.index', $otherSite));

        $response->assertStatus(403);
    }

    public function test_can_render_block_preview_html()
    {
        $block = UiBlock::create([
            'site_id' => $this->site->id,
            'title' => 'Render Me',
            'type' => 'header',
            'config' => [
                'bg_style' => 'light',
                'logo_text' => 'Render Logo',
            ]
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sites.ui-blocks.render', [$this->site, $block]));

        $response->assertStatus(200);
        $response->assertSee('Render Logo');
    }

    public function test_api_routes_are_protected_by_auth()
    {
        // Unauthenticated user should be blocked
        $response = $this->postJson(route('admin.sites.ui-blocks.store', $this->site), []);
        $response->assertStatus(401);

        $response = $this->postJson(route('admin.sites.ui-blocks.reorder', $this->site), []);
        $response->assertStatus(401);
    }

    public function test_create_requires_title_and_valid_type()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.store', $this->site), [
                'type' => 'not-a-real-type',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'type']);
    }

    public function test_banner_block_requires_image_url_and_link()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.store', $this->site), [
                'title' => 'My Banner',
                'type'  => 'banner',
                'config' => [],
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['config.image_url', 'config.link']);
    }

    public function test_banner_block_rejects_non_url_values()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.store', $this->site), [
                'title' => 'My Banner',
                'type'  => 'banner',
                'config' => [
                    'image_url' => 'not-a-url',
                    'link'      => 'not-a-url',
                ],
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['config.image_url', 'config.link']);
    }

    public function test_card_block_requires_at_least_one_card_with_title_and_description()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.store', $this->site), [
                'title' => 'My Card',
                'type'  => 'card',
                'config' => ['cards' => [[]]],
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['config.cards.0.title', 'config.cards.0.description']);
    }

    public function test_card_block_image_url_is_optional()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.store', $this->site), [
                'title' => 'My Card',
                'type'  => 'card',
                'config' => [
                    'cards' => [
                        ['title' => 'Card title', 'description' => 'Card description'],
                    ],
                ],
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('ui_blocks', ['title' => 'My Card', 'type' => 'card']);
    }

    public function test_list_block_requires_at_least_one_item()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.store', $this->site), [
                'title' => 'My List',
                'type'  => 'list',
                'config' => ['items' => []],
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['config.items']);
    }

    public function test_stats_block_requires_label_and_value_per_stat()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.store', $this->site), [
                'title' => 'My Stats',
                'type'  => 'stats',
                'config' => [
                    'stats' => [
                        ['label' => 'Users'],
                    ],
                ],
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['config.stats.0.value']);
    }

    public function test_config_size_must_be_valid_enum_when_present()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.store', $this->site), [
                'title' => 'My Header',
                'type'  => 'header',
                'config' => ['size' => 'xl'],
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['config.size']);
    }

    public function test_rejects_unsupported_block_type()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.store', $this->site), [
                'title' => 'Sketchy',
                'type'  => 'eval_html',
                'config' => [],
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['type']);
    }

    public function test_can_create_cta_block()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.store', $this->site), [
                'title' => 'Sign Up CTA',
                'type'  => 'cta',
                'config' => [
                    'heading'     => 'Ready to start?',
                    'button_text' => 'Sign Up',
                    'button_link' => 'https://example.com/signup',
                ],
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('ui_blocks', ['title' => 'Sign Up CTA', 'type' => 'cta']);
    }

    public function test_cta_block_requires_heading_and_button_fields()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.store', $this->site), [
                'title' => 'Bad CTA',
                'type'  => 'cta',
                'config' => [],
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['config.heading', 'config.button_text', 'config.button_link']);
    }

    public function test_accordion_block_requires_items_with_question_and_answer()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.store', $this->site), [
                'title' => 'FAQ',
                'type'  => 'accordion',
                'config' => ['items' => [['question' => 'Why?']]],
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['config.items.0.answer']);
    }

    public function test_rich_text_block_strips_script_tags_on_render()
    {
        $block = UiBlock::create([
            'site_id' => $this->site->id,
            'title' => 'About',
            'type' => 'rich_text',
            'is_active' => true,
            'display_order' => 0,
            'config' => [
                'html' => '<p>Hello</p><script>alert(1)</script><a href="javascript:alert(2)">click</a>',
            ],
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sites.ui-blocks.render', [$this->site, $block]));

        $response->assertStatus(200);
        $response->assertSee('Hello');
        $response->assertDontSee('<script>', false);
        $response->assertDontSee('javascript:', false);
    }

    public function test_unknown_but_valid_type_falls_back_to_placeholder()
    {
        $block = UiBlock::create([
            'site_id' => $this->site->id,
            'title' => 'Pricing Table',
            'type' => 'table',
            'is_active' => true,
            'display_order' => 0,
            'config' => [
                'headers' => ['Plan', 'Price'],
                'rows'    => [['Basic', '$9']],
            ],
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sites.ui-blocks.render', [$this->site, $block]));

        $response->assertStatus(200);
        $response->assertSee('No template available for this block type yet.');
    }

    public function test_update_returns_404_when_block_belongs_to_different_site()
    {
        $otherSite = Site::create(['name' => 'Other Site', 'slug' => 'other-site']);
        $block = UiBlock::create([
            'site_id' => $otherSite->id,
            'title'   => 'Foreign Block',
            'type'    => 'header',
            'config'  => [],
        ]);

        $response = $this->actingAs($this->admin)
            ->putJson(route('admin.sites.ui-blocks.update', [$this->site, $block]), [
                'title' => 'Hijacked',
                'type'  => 'header',
            ]);

        $response->assertStatus(404);
    }

    public function test_destroy_returns_404_when_block_belongs_to_different_site()
    {
        $otherSite = Site::create(['name' => 'Other Site', 'slug' => 'other-site']);
        $block = UiBlock::create([
            'site_id' => $otherSite->id,
            'title'   => 'Foreign Block',
            'type'    => 'header',
            'config'  => [],
        ]);

        $response = $this->actingAs($this->admin)
            ->deleteJson(route('admin.sites.ui-blocks.destroy', [$this->site, $block]));

        $response->assertStatus(404);
        $this->assertDatabaseHas('ui_blocks', ['id' => $block->id]);
    }

    public function test_toggle_returns_404_when_block_belongs_to_different_site()
    {
        $otherSite = Site::create(['name' => 'Other Site', 'slug' => 'other-site']);
        $block = UiBlock::create([
            'site_id' => $otherSite->id,
            'title'   => 'Foreign Block',
            'type'    => 'header',
            'config'  => [],
        ]);

        $response = $this->actingAs($this->admin)
            ->patchJson(route('admin.sites.ui-blocks.toggle', [$this->site, $block]));

        $response->assertStatus(404);
    }

    public function test_render_returns_404_when_block_belongs_to_different_site()
    {
        $otherSite = Site::create(['name' => 'Other Site', 'slug' => 'other-site']);
        $block = UiBlock::create([
            'site_id' => $otherSite->id,
            'title'   => 'Foreign Block',
            'type'    => 'header',
            'config'  => [],
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sites.ui-blocks.render', [$this->site, $block]));

        $response->assertStatus(404);
    }

    public function test_reorder_validates_order_is_array_of_integers()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.reorder', $this->site), [
                'order' => ['not-an-id'],
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['order.0']);
    }

    public function test_reorder_requires_order_field()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.sites.ui-blocks.reorder', $this->site), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['order']);
    }

    public function test_index_view_lists_blocks_ordered_by_display_order()
    {
        $second = UiBlock::create([
            'site_id' => $this->site->id, 'title' => 'B', 'type' => 'banner',
            'display_order' => 1, 'config' => [],
        ]);
        $first = UiBlock::create([
            'site_id' => $this->site->id, 'title' => 'A', 'type' => 'banner',
            'display_order' => 0, 'config' => [],
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sites.ui-blocks.index', $this->site));

        $response->assertStatus(200);
        $response->assertViewHas('blocks', function ($blocks) use ($first, $second) {
            return $blocks->pluck('id')->all() === [$first->id, $second->id];
        });
    }

    public function test_non_json_store_request_redirects_with_success_message()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.sites.ui-blocks.store', $this->site), [
                'title' => 'Form Created',
                'type'  => 'header',
                'config' => [],
            ]);

        $response->assertRedirect(route('admin.sites.ui-blocks.index', $this->site));
        $response->assertSessionHas('success');
    }

    public function test_non_json_destroy_request_redirects_with_success_message()
    {
        $block = UiBlock::create([
            'site_id' => $this->site->id, 'title' => 'To Delete', 'type' => 'banner', 'config' => [],
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.sites.ui-blocks.destroy', [$this->site, $block]));

        $response->assertRedirect(route('admin.sites.ui-blocks.index', $this->site));
        $response->assertSessionHas('success');
    }

    public function test_visual_editor_view_loads_with_blocks()
    {
        UiBlock::create([
            'site_id' => $this->site->id, 'title' => 'Editor Block', 'type' => 'banner', 'config' => [],
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sites.visual-editor', $this->site));

        $response->assertStatus(200);
        $response->assertViewIs('admin.preview');
    }
}
