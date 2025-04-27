<?php

namespace App\Services;

use App\Models\Seller;
use App\Repositories\SellerRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SellerService
{
    public function __construct(protected SellerRepository $repository) {}

    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getAll($filters, $perPage);
    }

    public function create(array $data): Seller
    {
        return $this->repository->create($data);
    }

    public function update(Seller $seller, array $data): Seller
    {
        return $this->repository->update($seller, $data);
    }

    public function delete(Seller $seller): void
    {
        $this->repository->delete($seller);
    }

    public function find(int $id): Seller
    {
        return $this->repository->findOrFail($id);
    }
}
