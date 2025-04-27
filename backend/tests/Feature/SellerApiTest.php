<?php

namespace Tests\Feature;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
    }

    public function test_can_list_sellers_with_filters()
    {
        $this->authenticate();

        Seller::factory()->create(['name' => 'Carlos Silva', 'email' => 'carlos@example.com']);
        Seller::factory()->create(['name' => 'Maria Souza', 'email' => 'maria@example.com']);

        $response = $this->getJson('/api/sellers?name=Carlos');

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Carlos Silva'])
            ->assertJsonMissing(['name' => 'Maria Souza']);
    }

    public function test_can_create_seller()
    {
        $this->authenticate();

        $payload = [
            'name'  => 'Novo Vendedor',
            'email' => 'novo@example.com',
        ];

        $response = $this->postJson('/api/sellers', $payload);

        $response->assertCreated()
            ->assertJsonFragment(['name' => 'Novo Vendedor']);

        $this->assertDatabaseHas('sellers', ['email' => 'novo@example.com']);
    }

    public function test_validation_error_on_create()
    {
        $this->authenticate();

        $response = $this->postJson('/api/sellers', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email']);
    }

    public function test_can_show_single_seller()
    {
        $this->authenticate();

        $seller = Seller::factory()->create();

        $response = $this->getJson("/api/sellers/{$seller->id}");

        $response->assertOk()
            ->assertJsonFragment(['email' => $seller->email]);
    }

    public function test_not_found_on_invalid_seller_show()
    {
        $this->authenticate();

        $response = $this->getJson('/api/sellers/999');

        $response->assertNotFound();
    }

    public function test_can_update_seller()
    {
        $this->authenticate();

        $seller = Seller::factory()->create();

        $response = $this->putJson("/api/sellers/{$seller->id}", [
            'name' => 'Nome Atualizado',
        ]);

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Nome Atualizado']);

        $this->assertDatabaseHas('sellers', ['id' => $seller->id, 'name' => 'Nome Atualizado']);
    }

    public function test_validation_error_on_update()
    {
        $this->authenticate();

        $seller = Seller::factory()->create();

        $response = $this->putJson("/api/sellers/{$seller->id}", [
            'email' => 'not-an-email',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_can_delete_seller()
    {
        $this->authenticate();

        $seller = Seller::factory()->create();

        $response = $this->deleteJson("/api/sellers/{$seller->id}");

        $response->assertOk(); 
        $response->assertJson([
            'message' => 'Seller deleted successfully',
        ]);

        $this->assertDatabaseMissing('sellers', ['id' => $seller->id]);
    }

    public function test_not_found_on_invalid_seller_delete()
    {
        $this->authenticate();

        $response = $this->deleteJson('/api/sellers/999');

        $response->assertNotFound();
    }
}
