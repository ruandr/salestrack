<?php

namespace App\Repositories;

use App\Models\Seller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SellerRepository
{
    public function findOrFail(int $id) {
        return Seller::findOrFail($id);
    }

    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Seller::query();

        foreach ($filters as $field => $value) {
            if (in_array($field, ['name', 'email'])) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        return $query->paginate($perPage);
    }

    public function create(array $data): Seller
    {
        return Seller::create($data);
    }

    public function update(Seller $seller, array $data): Seller
    {
        $seller->update($data);
        return $seller;
    }

    public function delete(Seller $seller): void
    {
        $seller->delete();
    }
}
