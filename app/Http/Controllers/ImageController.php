<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Resources\ImageResource;
use App\Service\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    /**
     * @var ImageService
     */
    private $imageService;

    /**
     * Create a new controller instance.
     *
     * @param ImageService $imageService
     */
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Get all images.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $images = $this->imageService->getAll($request->all());
            if ($images === null) {
                throw new \Exception();
            }

            $transformed = ImageResource::collection($images)
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
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get image by id.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $image = $this->imageService->getById($id);
            if ($image instanceof \App\Exceptions\CustomException) {
                throw $image;
            }

            if ($image === null) {
                throw new \Exception();
            }

            $transformed = (new ImageResource($image))->single();

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $transformed
            ], 200);
        } catch(CustomException $ce) {
            return response()->json([
                'success' => false,
                'message' => $ce->getMessage(),
                'error' => $ce->getMessage()
            ], $ce->getCode());
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] show : ", __CLASS__).$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
