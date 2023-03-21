<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Resources\CategoryResource;
use App\Service\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * Create a new controller instance.
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Get all categories.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $categories = $this->categoryService->getAll($request->all());
            if ($categories === null) {
                throw new \Exception();
            }

            $transformed = CategoryResource::collection($categories)
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
     * Get category by id.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $category = $this->categoryService->getById($id);
            if ($category instanceof CustomException) {
                throw $category;
            }

            if ($category === null) {
                throw new \Exception();
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => (new CategoryResource($category))->single(),
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
}
