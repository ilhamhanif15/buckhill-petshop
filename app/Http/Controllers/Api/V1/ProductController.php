<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
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
     * @OA\Post(
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        //
    }
}
