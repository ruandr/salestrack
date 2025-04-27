<?php

namespace App\Repositories;

use App\Models\Sale;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SaleRepository
{
    public function create(array $data): Sale
    {
        return Sale::create($data);
    }

    public function findOrFail(int $id): Sale
    {
        return Sale::findOrFail($id);
    }

    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Sale::query();
    
        if (isset($filters['seller_id'])) {
            $query->where('seller_id', $filters['seller_id']);
        }
    
        return $query->paginate($perPage);
    }

    public function getSalesBySeller(int $sellerId, int $perPage = 15): LengthAwarePaginator
    {
        return Sale::where('seller_id', $sellerId)
                    ->orderByDesc('sale_date')
                    ->paginate($perPage);
    }    

    public function getSalesByDate($date): Collection
    {
        return Sale::whereDate('sale_date', $date)->get();
    }

}
