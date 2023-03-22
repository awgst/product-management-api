<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\Image\CreateImageRequest;
use App\Http\Requests\Image\UpdateImageRequest;
use App\Http\Resources\ImageResource;
use App\Service\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Termwind\Components\Dd;

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
                'error' => null
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
                'error' => null
            ], $ce->getCode());
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] show : ", __CLASS__).$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => null
            ], 500);
        }
    }

    /**
     * Store new image.
     * @param CreateImageRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateImageRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $image = $this->imageService->create($request->data());
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
                'error' => null
            ], $ce->getCode());
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] store : ", __CLASS__).$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => null
            ], 500);
        }
    }

    /**
     * Update image by id.
     * @param UpdateImageRequest $request
     * @param int $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateImageRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $image = $this->imageService->update($id, $request->data());
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
                'error' => null
            ], $ce->getCode());
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] update : ", __CLASS__).$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => null
            ], 500);
        }
    }

    /**
     * Delete image by id.
     * @param int $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $image = $this->imageService->delete($id);
            if ($image instanceof \App\Exceptions\CustomException) {
                throw $image;
            }

            if ($image === null) {
                throw new \Exception();
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => null
            ], 200);
        } catch(CustomException $ce) {
            return response()->json([
                'success' => false,
                'message' => $ce->getMessage(),
                'error' => null
            ], $ce->getCode());
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] destroy : ", __CLASS__).$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => null
            ], 500);
        }
    }
}
