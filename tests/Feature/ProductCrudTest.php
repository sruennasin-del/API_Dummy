<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Size;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $normalUser;
    private Category $category;
    private Color $color;
    private Size $size;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->normalUser = User::factory()->create([
            'is_admin' => false,
        ]);

        $this->category = Category::create([
            'name' => 'Watches',
            'slug' => 'watches',
            'description' => 'Timepieces',
            'status' => 'active',
        ]);

        $this->color = Color::create([
            'name' => 'Space Gray',
            'code' => '#555555',
            'status' => 'active',
        ]);

        $this->size = Size::create([
            'name' => '44mm',
            'status' => 'active',
        ]);
    }

    /** @test */
    public function guest_cannot_access_products_index()
    {
        $response = $this->get('/admin/products');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function non_admin_cannot_access_products_index()
    {
        $response = $this->actingAs($this->normalUser)->get('/admin/products');
        $response->assertRedirect('/');
    }

    /** @test */
    public function admin_can_view_products_index_with_search_and_pagination()
    {
        // Seed 15 products to test pagination (default paginated by 10)
        for ($i = 1; $i <= 15; $i++) {
            Product::create([
                'title' => "Smart Watch Edition {$i}",
                'slug' => "smart-watch-edition-{$i}",
                'category_id' => $this->category->id,
                'price' => 299.00 + $i,
                'stock' => 10,
                'description' => "Test description {$i}",
                'status' => 'active',
            ]);
        }

        $response = $this->actingAs($this->adminUser)->get('/admin/products');
        $response->assertStatus(200);
        $response->assertSee('Smart Watch Edition 15');
        // It paginates: check if it doesn't display first page elements if we go to page 2
        $response2 = $this->actingAs($this->adminUser)->get('/admin/products?page=2');
        $response2->assertStatus(200);

        // Test search
        $responseSearch = $this->actingAs($this->adminUser)->get('/admin/products?search=Edition 15');
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Smart Watch Edition 15');
        $responseSearch->assertDontSee('Smart Watch Edition 1');
    }

    /** @test */
    public function admin_can_create_product_with_gallery_images_and_pivot_relations()
    {
        Storage::fake('public');

        $mainImageFile = UploadedFile::fake()->image('main.jpg');
        $detailImageFile0 = UploadedFile::fake()->image('detail0.jpg');

        $response = $this->actingAs($this->adminUser)->post('/admin/products', [
            'title' => 'Test Watch Product',
            'slug' => 'test-watch-product',
            'category_id' => $this->category->id,
            'price' => 199.99,
            'stock' => 50,
            'description' => 'Detailed watch description',
            'status' => 'active',
            'colors' => [$this->color->id],
            'sizes' => [$this->size->id],
            'image_file' => $mainImageFile,
            'detail_image_file_0' => $detailImageFile0,
            'detail_image_1' => '/images/fake-url-detail.jpg',
        ]);

        $response->assertRedirect('/admin/products');
        $response->assertSessionHas('success', 'Product created successfully.');

        $this->assertDatabaseHas('ec_products', [
            'title' => 'Test Watch Product',
            'price' => 199.99,
            'stock' => 50,
        ]);

        $product = Product::where('slug', 'test-watch-product')->first();
        $this->assertNotNull($product);
        $this->assertTrue($product->colors->contains($this->color->id));
        $this->assertTrue($product->sizes->contains($this->size->id));

        // Check detail images in database
        $this->assertDatabaseHas('ec_product_images', [
            'product_id' => $product->id,
            'image_path' => '/images/fake-url-detail.jpg',
        ]);

        $detailFiles = ProductImage::where('product_id', $product->id)
            ->where('image_path', 'like', '/storage/products/details/%')
            ->get();
        $this->assertCount(1, $detailFiles);
    }

    /** @test */
    public function size_and_color_variations_remain_nullable()
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/products', [
            'title' => 'Nullable Variations Watch',
            'price' => 150.00,
            'stock' => 10,
            'description' => 'Test nullable variations',
            'status' => 'active',
            // Leave colors and sizes absent
        ]);

        $response->assertRedirect('/admin/products');
        
        $product = Product::where('title', 'Nullable Variations Watch')->first();
        $this->assertNotNull($product);
        $this->assertCount(0, $product->colors);
        $this->assertCount(0, $product->sizes);
    }

    /** @test */
    public function admin_can_edit_and_update_product_gallery_and_relations()
    {
        Storage::fake('public');

        $product = Product::create([
            'title' => 'Watch to Update',
            'slug' => 'watch-to-update',
            'category_id' => $this->category->id,
            'price' => 250.00,
            'stock' => 15,
            'status' => 'active',
        ]);

        $detail1 = ProductImage::create([
            'product_id' => $product->id,
            'image_path' => '/images/old-detail.jpg',
        ]);

        $newDetailFile = UploadedFile::fake()->image('new-detail.jpg');

        $response = $this->actingAs($this->adminUser)->put("/admin/products/{$product->id}", [
            'title' => 'Watch Updated Name',
            'slug' => 'watch-updated-name',
            'category_id' => $this->category->id,
            'price' => 275.00,
            'stock' => 20,
            'status' => 'inactive',
            'colors' => [$this->color->id],
            // Update detail image 0 (which was $detail1) by checking delete on it and uploading a new one,
            // or just replacing/adding new detail images.
            'detail_image_file_0' => $newDetailFile,
            'delete_detail_0' => '1',
        ]);

        $response->assertRedirect('/admin/products');

        $this->assertDatabaseHas('ec_products', [
            'id' => $product->id,
            'title' => 'Watch Updated Name',
            'price' => 275.00,
            'status' => 'inactive',
        ]);

        $product->refresh();
        $this->assertTrue($product->colors->contains($this->color->id));
        $this->assertCount(0, $product->sizes);

        // Check old detail is gone
        $this->assertDatabaseMissing('ec_product_images', [
            'id' => $detail1->id,
        ]);

        // Check new detail is present
        $this->assertDatabaseHas('ec_product_images', [
            'product_id' => $product->id,
        ]);
    }

    /** @test */
    public function admin_can_delete_product_along_with_gallery_images()
    {
        $product = Product::create([
            'title' => 'Watch to Delete',
            'slug' => 'watch-to-delete',
            'price' => 250.00,
            'stock' => 15,
            'status' => 'active',
        ]);

        $detail = ProductImage::create([
            'product_id' => $product->id,
            'image_path' => '/images/detail-delete.jpg',
        ]);

        $response = $this->actingAs($this->adminUser)->delete("/admin/products/{$product->id}");
        $response->assertRedirect('/admin/products');

        $this->assertDatabaseMissing('ec_products', [
            'id' => $product->id,
        ]);

        $this->assertDatabaseMissing('ec_product_images', [
            'id' => $detail->id,
        ]);
    }
}
