<?php

namespace Tests\Feature;

use App\Models\Color;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ColorCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $normalUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->adminUser = User::factory()->create([
            'is_admin' => true,
        ]);

        // Create a non-admin user
        $this->normalUser = User::factory()->create([
            'is_admin' => false,
        ]);
    }

    /** @test */
    public function guest_cannot_access_colors_index()
    {
        $response = $this->get('/admin/colors');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function non_admin_cannot_access_colors_index()
    {
        $response = $this->actingAs($this->normalUser)->get('/admin/colors');
        $response->assertRedirect('/');
    }

    /** @test */
    public function admin_can_view_colors_index()
    {
        $color = Color::create([
            'name' => 'Sunset Gold',
            'code' => '#FFD700',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/colors');
        
        $response->assertStatus(200);
        $response->assertSee('Sunset Gold');
        $response->assertSee('#FFD700');
    }

    /** @test */
    public function admin_can_view_create_page()
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/colors/create');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_create_color()
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/colors', [
            'name' => 'Royal Purple',
            'code' => '#800080',
            'status' => 'active',
        ]);

        $response->assertRedirect('/admin/colors');
        $response->assertSessionHas('success', 'Color created successfully.');

        $this->assertDatabaseHas('ec_colors', [
            'name' => 'Royal Purple',
            'code' => '#800080',
            'status' => 'active',
        ]);
    }

    /** @test */
    public function color_creation_requires_valid_hex_code()
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/colors', [
            'name' => 'Invalid Color',
            'code' => 'not-a-hex',
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors(['code']);
        $this->assertDatabaseMissing('ec_colors', [
            'name' => 'Invalid Color',
        ]);
    }

    /** @test */
    public function admin_can_view_edit_page()
    {
        $color = Color::create([
            'name' => 'Lime Green',
            'code' => '#00FF00',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->get("/admin/colors/{$color->id}/edit");
        $response->assertStatus(200);
        $response->assertSee('Lime Green');
    }

    /** @test */
    public function admin_can_update_color()
    {
        $color = Color::create([
            'name' => 'Old Gold',
            'code' => '#CFB53B',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->put("/admin/colors/{$color->id}", [
            'name' => 'New Gold',
            'code' => '#FFD700',
            'status' => 'inactive',
        ]);

        $response->assertRedirect('/admin/colors');
        $response->assertSessionHas('success', 'Color updated successfully.');

        $this->assertDatabaseHas('ec_colors', [
            'id' => $color->id,
            'name' => 'New Gold',
            'code' => '#FFD700',
            'status' => 'inactive',
        ]);
    }

    /** @test */
    public function admin_can_delete_color()
    {
        $color = Color::create([
            'name' => 'To Be Deleted',
            'code' => '#ABCDEF',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->delete("/admin/colors/{$color->id}");

        $response->assertRedirect('/admin/colors');
        $response->assertSessionHas('success', 'Color deleted successfully.');

        $this->assertDatabaseMissing('ec_colors', [
            'id' => $color->id,
        ]);
    }
}
