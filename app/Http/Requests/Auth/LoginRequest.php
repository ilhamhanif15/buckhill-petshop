<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      schema="LoginRequest",
 *      title="Login user",
 *      description="Login request body data",
 *      type="object",
 *      required={"email", "password"},
 *      @OA\Property(
 *          property="email",
 *          description="Email of the user",
 *          format="email",
 *          type="string",
 *          example="user@mail.com"
 *      ),
 *      @OA\Property(
 *          property="password",
 *          type="string",
 *          description="Password for user's account",
 *          example="mypassword"
 *      )
 * )
 */
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            "email" => ["required", "email"],
            "password" => ["required"]
        ];
    }
}
