<?php

namespace Tests\Feature;

use App\Models\Sale;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
    }

    public function test_can_list_sales_with_filters()
    {
        $this->authenticate();

        $seller = Seller::factory()->create();

        Sale::factory()->create(['seller_id' => $seller->id, 'amount' => 100.0]);
        Sale::factory()->create(['seller_id' => $seller->id, 'amount' => 200.0]);

        $response = $this->getJson('/api/sales?amount=100');

        $response->assertOk();
    }

    public function test_can_create_sale()
    {
        $this->authenticate();

        $seller = Seller::factory()->create();

        $payload = [
            'seller_id' => $seller->id,
            'amount' => 150.0,
            'sale_date' => '2025-04-26',
        ];

        $response = $this->postJson('/api/sales', $payload);

        $response->assertCreated()
            ->assertJsonFragment(['amount' => 150.0]);

        $this->assertDatabaseHas('sales', ['amount' => 150.0]);
    }

    public function test_validation_error_on_create()
    {
        $this->authenticate();

        $response = $this->postJson('/api/sales', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['seller_id', 'amount', 'sale_date']);
    }

    public function test_can_show_single_sale()
    {
        $this->authenticate();

        $sale = Sale::factory()->create();

        $response = $this->getJson("/api/sales/{$sale->id}");

        $response->assertOk()
            ->assertJsonFragment(['amount' => $sale->amount]);
    }

    public function test_not_found_on_invalid_sale_show()
    {
        $this->authenticate();

        $response = $this->getJson('/api/sales/999');

        $response->assertNotFound();
    }


    public function test_can_list_sales_by_seller()
    {
        $this->authenticate();

        $seller = Seller::factory()->create();

        Sale::factory()->count(3)->create([
            'seller_id' => $seller->id,
        ]);

        Sale::factory()->count(2)->create();

        $response = $this->getJson("/api/sales/seller/{$seller->id}");

        $response->assertOk()
            ->assertJsonFragment(['success' => true])
            ->assertJsonCount(5, 'data.data');
    }
}
