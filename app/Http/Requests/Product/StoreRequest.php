<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      schema="ProductStoreRequest",
 *      title="Product Store Request",
 *      description="Product Store Request body data",
 *      type="object",
 *      required={"title", "category_uuid", "price", "description"},
 *      @OA\Property(
 *          property="title",
 *          description="Name/title of Product",
 *          type="string",
 *          example="dry dog food"
 *      ),
 *      @OA\Property(
 *          property="category_uuid",
 *          description="Category for this product",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="price",
 *          description="Price of Product",
 *          type="float",
 *          example="100"
 *      ),
 *      @OA\Property(
 *          property="description",
 *          description="Description of Product",
 *          type="string",
 *          example="This food is absolutely for your dogs"
 *      ),
 * )
 */
class StoreRequest extends FormRequest
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
            'category_uuid' => ['required', 'exists:categories,uuid'],
            'title' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'description' => ['required', 'string'],
        ];
    }
}
