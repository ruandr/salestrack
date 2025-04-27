<?php

namespace App\Services;

use App\Mail\DailySalesSummary;
use App\Models\Sale;
use App\Models\User;
use App\Repositories\SaleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;

class SaleService
{
    public function __construct(protected SaleRepository $repository) {}

    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getAll($filters, $perPage);
    }

    public function createSale(array $data): Sale
    {
        return $this->repository->create($data);
    }

    public function getSalesBySeller(int $sellerId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getSalesBySeller($sellerId, $perPage);
    }    

    public function findSale(int $id): Sale
    {
        return $this->repository->findOrFail($id);
    }

    public function getSalesForToday()
    {
        return $this->repository->getSalesByDate(today());
    }

    public function sendDailySalesSummaryToAll()
    {
        $sales = $this->getSalesForToday();

        $this->sendSalesSummaryToSellers($sales);

        $this->sendSalesSummaryToAdmin($sales);
    }

    public function sendDailySalesSummaryToSeller(int $sellerId)
    {
        $sales = $this->getSalesForToday()->where('seller_id', $sellerId);
        if ($sales->isEmpty()) {
            return;
        }

        $this->sendSalesSummaryToSellers($sales);
    }


    protected function sendSalesSummaryToSellers($sales)
    {
        $sellers = $sales->groupBy('seller_id');
        foreach ($sellers as $sellerSales) {
            $salesCount = $sellerSales->count();
            $totalSalesValue = $sellerSales->sum('amount');
            $totalCommission = $sellerSales->sum(fn($sale) => $sale->amount * Sale::COMMISSION_RATE);
            
            $seller = $sellerSales->first()->seller;
            Mail::to($seller->email)->send(new DailySalesSummary($salesCount, $totalSalesValue, $totalCommission));
        }
    }

    protected function sendSalesSummaryToAdmin($sales)
    {
        $totalSalesValueForAdmin = $sales->sum('amount');
        $totalCommissionForAdmin = $sales->sum(fn($sale) => $sale->amount * Sale::COMMISSION_RATE);

        $adminEmail = User::ADMIN_MAIL;
        Mail::to($adminEmail)->send(new DailySalesSummary(
            $sales->count(),
            $totalSalesValueForAdmin,
            $totalCommissionForAdmin
        ));
    }
}
