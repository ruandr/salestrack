<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSellerRequest;
use App\Http\Requests\UpdateSellerRequest;
use App\Models\Seller;
use App\Services\SellerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function __construct(protected SellerService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['name', 'email']);
        $perPage = (int) $request->input('per_page', 15);

        $sellers = $this->service->getAll($filters, $perPage);

        return response()->json([
            'success' => true,
            'data' => $sellers,
        ]);
    }

    public function store(StoreSellerRequest $request): JsonResponse
    {
        $seller = $this->service->create($request->validated());

        return response()->json([
            'success' => true,
            'data' => $seller,
        ], 201);
    }

    public function show(Seller $seller): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $seller,
        ]);
    }

    public function update(UpdateSellerRequest $request, Seller $seller): JsonResponse
    {
        $updated = $this->service->update($seller, $request->validated());

        return response()->json([
            'success' => true,
            'data' => $updated,
        ]);
    }

    public function destroy(Seller $seller): JsonResponse
    {
        $this->service->delete($seller);

        return response()->json([
            'success' => true,
            'message' => 'Seller deleted successfully',
        ]);
    }
}
