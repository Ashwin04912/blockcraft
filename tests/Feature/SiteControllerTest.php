<?php

namespace Tests\Feature;

use App\Models\Site;
use App\Models\UiBlock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
    }

    public function test_dashboard_lists_sites_with_block_counts()
    {
        $site = Site::create([
            'name' => 'Test Site',
            'slug' => 'test-site',
            'owner_id' => $this->admin->id,
        ]);

        UiBlock::create([
            'site_id' => $site->id,
            'title'   => 'Block 1',
            'type'    => 'banner',
            'config'  => [],
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('sites', function ($sites) use ($site) {
            return $sites->first()->id === $site->id
                && $sites->first()->ui_blocks_count === 1;
        });
    }

    public function test_can_create_site_with_valid_data()
    {
        $payload = [
            'name'        => 'My New Site',
            'slug'        => 'my-new-site',
            'description' => 'A description',
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.sites.store'), $payload);

        $this->assertDatabaseHas('sites', [
            'name' => 'My New Site',
            'slug' => 'my-new-site',
        ]);

        $site = Site::where('slug', 'my-new-site')->first();
        $response->assertRedirect(route('admin.sites.visual-editor', $site));
    }

    public function test_site_creation_requires_name_and_slug()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.sites.store'), []);

        $response->assertSessionHasErrors(['name', 'slug']);
        $this->assertDatabaseCount('sites', 0);
    }

    public function test_site_slug_must_be_unique()
    {
        Site::create(['name' => 'Existing', 'slug' => 'taken-slug']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.sites.store'), [
                'name' => 'New Site',
                'slug' => 'taken-slug',
            ]);

        $response->assertSessionHasErrors('slug');
        $this->assertDatabaseCount('sites', 1);
    }

    public function test_site_slug_must_match_format()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.sites.store'), [
                'name' => 'Bad Slug Site',
                'slug' => 'Not A Valid Slug!',
            ]);

        $response->assertSessionHasErrors('slug');
        $this->assertDatabaseCount('sites', 0);
    }

    public function test_can_delete_site_and_cascade_deletes_blocks()
    {
        $site = Site::create(['name' => 'To Delete', 'slug' => 'to-delete', 'owner_id' => $this->admin->id]);
        $block = UiBlock::create([
            'site_id' => $site->id,
            'title'   => 'Child Block',
            'type'    => 'banner',
            'config'  => [],
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.sites.destroy', $site));

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseMissing('sites', ['id' => $site->id]);
        $this->assertDatabaseMissing('ui_blocks', ['id' => $block->id]);
    }

    public function test_dashboard_only_shows_own_sites()
    {
        $other = User::factory()->create();
        Site::create(['name' => 'Mine', 'slug' => 'mine', 'owner_id' => $this->admin->id]);
        Site::create(['name' => 'Not Mine', 'slug' => 'not-mine', 'owner_id' => $other->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertViewHas('sites', function ($sites) {
            return $sites->count() === 1 && $sites->first()->slug === 'mine';
        });
    }

    public function test_cannot_delete_another_users_site()
    {
        $other = User::factory()->create();
        $site = Site::create(['name' => 'Not Mine', 'slug' => 'not-mine', 'owner_id' => $other->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.sites.destroy', $site));

        $response->assertStatus(403);
        $this->assertDatabaseHas('sites', ['id' => $site->id]);
    }

    public function test_site_routes_are_protected_by_auth()
    {
        $site = Site::create(['name' => 'Protected', 'slug' => 'protected']);

        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->post(route('admin.sites.store'), [])->assertRedirect(route('login'));
        $this->delete(route('admin.sites.destroy', $site))->assertRedirect(route('login'));
    }
}
