<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Service\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            Log::channel('exception')->error(sprintf("[%s] index : ", __CLASS__).$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'data' => null,
            ], 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        try {
            $product = $this->productService->getById($request->id);
            if ($product === null) {
                throw new \Exception();
            }

            if ($product instanceof CustomException) {
                throw $product;
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => (new ProductResource($product))->single()
            ], 200);
        } catch (CustomException $ce) {
            return response()->json([
                'success' => false,
                'message' => $ce->getMessage(),
                'data' => null,
            ], $ce->getCode());
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] show : ", __CLASS__).$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'data' => null,
            ], 500);
        }
    }

    /**
     * @param CreateProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateProductRequest $request)
    {
        try {
            $product = $this->productService->create($request->validated());
            if ($product === null) {
                throw new \Exception();
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => (new ProductResource($product))->single()
            ], 200);
        } catch (CustomException $ce) {
            return response()->json([
                'success' => false,
                'message' => $ce->getMessage(),
                'data' => null,
            ], $ce->getCode());
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] store : ", __CLASS__).$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'data' => null,
            ], 500);
        }
    }

    /**
     * @param UpdateProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProductRequest $request)
    {
        try {
            $product = $this->productService->update($request->id, $request->all());
            if ($product === null) {
                throw new \Exception();
            }

            if ($product instanceof CustomException) {
                throw $product;
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => (new ProductResource($product))->single()
            ], 200);
        } catch (CustomException $ce) {
            return response()->json([
                'success' => false,
                'message' => $ce->getMessage(),
                'data' => null,
            ], $ce->getCode());
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] update : ", __CLASS__).$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'data' => null,
            ], 500);
        }
    }
}
