<?php

namespace Tests\Unit;

use App\Models\Sale;
use App\Repositories\SaleRepository;
use App\Services\SaleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use PHPUnit\Framework\TestCase;

class SaleServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_returns_paginated_sellers()
    {
        $mockRepository = Mockery::mock(SaleRepository::class);
        $mockPaginator = Mockery::mock(LengthAwarePaginator::class);

        $mockRepository
            ->shouldReceive('getAll')
            ->once()
            ->andReturn($mockPaginator);

        $service = new SaleService($mockRepository);

        $result = $service->getAll();

        $this->assertSame($mockPaginator, $result);
    }

    public function test_create_sale_returns_sale_instance()
    {
        $mockRepository = Mockery::mock(SaleRepository::class);
        $mockSale = new Sale([
            'seller_id' => 1,
            'amount' => 100.0,
            'sale_date' => '2025-04-26',
        ]);

        $mockRepository
            ->shouldReceive('create')
            ->once()
            ->with([
                'seller_id' => 1,
                'amount' => 100.0,
                'sale_date' => '2025-04-26',
            ])
            ->andReturn($mockSale);

        $service = new SaleService($mockRepository);

        $result = $service->createSale([
            'seller_id' => 1,
            'amount' => 100.0,
            'sale_date' => '2025-04-26',
        ]);

        $this->assertInstanceOf(Sale::class, $result);
        $this->assertEquals(100.0, $result->amount);
    }

    public function test_get_sales_by_seller_returns_paginated_sales()
    {
        $mockRepository = Mockery::mock(SaleRepository::class);
        $mockPaginator = Mockery::mock(LengthAwarePaginator::class);

        $mockRepository
            ->shouldReceive('getSalesBySeller')
            ->once()
            ->with(1, 15)
            ->andReturn($mockPaginator);

        $service = new SaleService($mockRepository);

        $result = $service->getSalesBySeller(1);

        $this->assertSame($mockPaginator, $result);
    }
}
