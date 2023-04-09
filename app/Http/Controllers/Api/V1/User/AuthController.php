<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthUserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthUserService $authUserService
    )
    {
        //
    }

    /**
     * @OA\Post(
     *      path="/api/v1/user/login",
     *      tags={"User"},
     *      summary="Login an User account",
     *      description="Returns User's Token",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(ref="#/components/schemas/LoginRequest")
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
    public function login(LoginRequest $request)
    {
        return $this->authUserService->login($request->email, $request->password);
    }
}
