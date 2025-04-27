<?php

namespace Tests\Unit;

use App\Models\Seller;
use App\Repositories\SellerRepository;
use App\Services\SellerService;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\TestCase;
use Mockery;

class SellerServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_returns_paginated_sellers()
    {
        $mockRepository = Mockery::mock(SellerRepository::class);
        $mockPaginator = Mockery::mock(LengthAwarePaginator::class);

        $mockRepository
            ->shouldReceive('getAll')
            ->once()
            ->andReturn($mockPaginator);

        $service = new SellerService($mockRepository);

        $result = $service->getAll();

        $this->assertSame($mockPaginator, $result);
    }

    public function test_create_returns_seller_instance()
    {
        $mockRepository = Mockery::mock(SellerRepository::class);
        $mockSeller = new Seller([
            'name' => 'Test Seller',
            'email' => 'test@example.com',
        ]);

        $mockRepository
            ->shouldReceive('create')
            ->once()
            ->with([
                'name' => 'Test Seller',
                'email' => 'test@example.com',
            ])
            ->andReturn($mockSeller);

        $service = new SellerService($mockRepository);

        $result = $service->create([
            'name' => 'Test Seller',
            'email' => 'test@example.com',
        ]);

        $this->assertInstanceOf(Seller::class, $result);
        $this->assertEquals('Test Seller', $result->name);
    }
}
