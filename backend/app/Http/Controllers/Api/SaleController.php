<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Models\Sale;
use App\Services\SaleService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function __construct(protected SaleService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['seller_id']);
        $perPage = (int) $request->input('per_page', 15);

        $sales = $this->service->getAll($filters, $perPage);

        return response()->json([
            'success' => true,
            'data' => $sales,
        ]);
    }

    public function store(StoreSaleRequest $request): JsonResponse
    {
        $sale = $this->service->createSale($request->validated());

        return response()->json([
            'success' => true,
            'data' => $sale,
        ], 201);
    }

    public function show(Sale $sale): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $sale,
        ]);
    }

    public function listBySeller(int $sellerId, Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $sales = $this->service->getSalesBySeller($sellerId, $perPage);

        return response()->json([
            'success' => true,
            'data' => $sales,
        ]);
    }


    public function sendSummaryToSeller(int $sellerId): JsonResponse
    {
        if (Auth::user()->isAdmin()) {
            try {
                $this->service->sendDailySalesSummaryToSeller($sellerId);
                return response()->json(['message' => 'Resumo diário de vendas enviado para o vendedor com sucesso']);
            } catch (Exception $ex) {
                return response()->json([
                    'message' => 'Erro ao enviar resumo diário de vendas',
                    'detail' => $ex->getMessage()
                ], 500);
            }
        } else {
            return response()->json(['message' => 'Você não tem permissão para acessar esta funcionalidade'], 401);
        }
    }
}
