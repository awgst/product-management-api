<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Service\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * ProductController constructor.
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $products = $this->productService->getAll($request->all());
            if ($products === null) {
                throw new \Exception();
            }

            $transformed = ProductResource::collection($products)
                ->response()
                ->getData(true);

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $transformed['data'],
                'pagination' => $transformed['links']
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'data' => null,
            ], 500);
        }
    }
}
