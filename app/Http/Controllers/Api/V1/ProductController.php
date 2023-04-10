<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Services\ProductService;
use App\Traits\ResponseBuilder;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ResponseBuilder;

    public function __construct(
        private ProductService $productService
    )
    {
        //
    }

    /**
     * @OA\GET(
     *      path="/api/v1/products",
     *      tags={"Product"},
     *      summary="List products",
     *      description="Returns Products",
     *      @OA\Parameter(
     *          description="Page",
     *          in="query",
     *          name="page",
     *      ),
     *      @OA\Parameter(
     *          description="Search for title, price, description, category title",
     *          in="query",
     *          name="q",
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
     *          description="Successful operation",
     *          @OA\JsonContent()
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
        $data = $this->productService->getPaginate($request);

        return $this->responseSuccess($data);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/products",
     *      tags={"Product"},
     *      summary="Create new Product Item",
     *      description="Create new Product Item",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(ref="#/components/schemas/ProductStoreRequest")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
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
        $result = $this->productService->store($request->validated());

        return $this->responseSuccess($result);
    }

    /**
     * @OA\GET(
     *      path="/api/v1/products/{uuid}",
     *      tags={"Product"},
     *      summary="Show Product by uuid",
     *      description="Returns product",
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
        $result = $this->productService->show($uuid);

        if (!$result) {
            return $this->errorResponse("Product Not Found.", 404);
        }

        return $this->responseSuccess($result);
    }

    /**
     * @OA\PUT(
     *      path="/api/v1/products/{uuid}",
     *      tags={"Product"},
     *      summary="Update product Item",
     *      description="Update product Item",
     *      @OA\Parameter(
     *          description="uuid",
     *          in="path",
     *          required=true,
     *          name="uuid",
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(ref="#/components/schemas/ProductStoreRequest")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent()
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
    public function update(UpdateRequest $request, string $uuid)
    {
        $result = $this->productService->update($request->validated(), $uuid);

        return $this->responseSuccess($result);
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/products/{uuid}",
     *      tags={"Product"},
     *      summary="Delete Product by uuid",
     *      description="Delete a Product",
     *      @OA\Parameter(
     *          description="uuid",
     *          in="path",
     *          required=true,
     *          name="uuid",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
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
    public function destroy(string $uuid)
    {
        $result = $this->productService->destroy($uuid);

        return $this->responseSuccess($result);
    }
}
