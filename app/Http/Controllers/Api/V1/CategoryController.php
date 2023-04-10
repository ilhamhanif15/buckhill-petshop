<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Services\CategoryService;
use App\Traits\ResponseBuilder;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ResponseBuilder;

    public function __construct(
        private CategoryService $categoryService
    )
    {
        //
    }

    /**
     * @OA\GET(
     *      path="/api/v1/categories",
     *      tags={"Category"},
     *      summary="List categories",
     *      description="Returns categories",
     *      @OA\Parameter(
     *          description="Page",
     *          in="query",
     *          name="page",
     *      ),
     *      @OA\Parameter(
     *          description="Per page",
     *          in="query",
     *          name="per_page",
     *      ),
     *      @OA\Parameter(
     *          description="Sort By",
     *          in="query",
     *          name="sortBy",
     *      ),
     *      @OA\Parameter(
     *          description="Desc",
     *          in="query",
     *          name="desc",
     *          @OA\Schema(
     *              type="boolean"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *      )
     * )
     */
    public function index(Request $request)
    {
        $data = $this->categoryService->getPaginate($request);

        return $this->responseSuccess($data);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/categories",
     *      tags={"Category"},
     *      summary="Create new Category Item",
     *      description="Create new Category Item",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(ref="#/components/schemas/CategoryStoreRequest")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *      )
     * )
     */
    public function store(StoreRequest $request)
    {
        $result = $this->categoryService->store($request->validated());

        return $this->responseSuccess($result);
    }

    /**
     * @OA\GET(
     *      path="/api/v1/categories/{uuid}",
     *      tags={"Category"},
     *      summary="Show Category by uuid",
     *      description="Returns categories",
     *      @OA\Parameter(
     *          description="uuid",
     *          in="path",
     *          required=true,
     *          name="uuid",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *      )
     * )
     */
    public function show(string $uuid)
    {
        $result = $this->categoryService->show($uuid);

        if (!$result) {
            return $this->errorResponse("Category Not Found.", 404);
        }

        return $this->responseSuccess($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
