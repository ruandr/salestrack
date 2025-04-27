<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\Seller;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = Seller::take(5)->get();

        foreach ($sellers as $seller) {
            Sale::factory()->count(10)->create([
                'seller_id' => $seller->id,
            ]);
        }
    }
}
