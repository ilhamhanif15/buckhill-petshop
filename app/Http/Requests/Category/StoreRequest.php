<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *      schema="CategoryStoreRequest",
 *      title="Category Store Request",
 *      description="Category Store Request body data",
 *      type="object",
 *      required={"title"},
 *      @OA\Property(
 *          property="title",
 *          description="Name/title of Category for a product",
 *          type="string",
 *          example="dry dog food"
 *      )
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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->title),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string']
        ];
    }
}
