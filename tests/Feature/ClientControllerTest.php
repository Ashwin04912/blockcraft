<?php

namespace Tests\Feature;

use App\Models\Site;
use App\Models\UiBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_page_shows_only_active_blocks_in_order()
    {
        $site = Site::create(['name' => 'Public Site', 'slug' => 'public-site']);

        $hidden = UiBlock::create([
            'site_id'       => $site->id,
            'title'         => 'Hidden Banner',
            'type'          => 'banner',
            'is_active'     => false,
            'display_order' => 0,
            'config'        => ['image_url' => 'https://example.com/a.png', 'link' => 'https://example.com'],
        ]);

        $second = UiBlock::create([
            'site_id'       => $site->id,
            'title'         => 'Second Block',
            'type'          => 'header',
            'is_active'     => true,
            'display_order' => 2,
            'config'        => ['logo_text' => 'Second'],
        ]);

        $first = UiBlock::create([
            'site_id'       => $site->id,
            'title'         => 'First Block',
            'type'          => 'header',
            'is_active'     => true,
            'display_order' => 1,
            'config'        => ['logo_text' => 'First'],
        ]);

        $response = $this->get(route('client.page', $site->slug));

        $response->assertStatus(200);
        $response->assertViewHas('blocks', function ($blocks) use ($first, $second, $hidden) {
            return $blocks->pluck('id')->all() === [$first->id, $second->id]
                && ! $blocks->contains('id', $hidden->id);
        });
    }

    public function test_client_page_shows_empty_state_when_no_active_blocks()
    {
        $site = Site::create(['name' => 'Empty Site', 'slug' => 'empty-site']);

        $response = $this->get(route('client.page', $site->slug));

        $response->assertStatus(200);
        $response->assertSee('No active blocks');
    }

    public function test_client_page_returns_404_for_unknown_slug()
    {
        $response = $this->get('/page/does-not-exist');

        $response->assertStatus(404);
    }

    public function test_client_page_is_publicly_accessible_without_auth()
    {
        $site = Site::create(['name' => 'No Auth Needed', 'slug' => 'no-auth-needed']);

        $response = $this->get(route('client.page', $site->slug));

        $response->assertStatus(200);
    }
}
