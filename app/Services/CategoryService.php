<?php 

namespace App\Services;

use App\Models\Category;
use App\Utils\Paginationable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CategoryService
{
    public function __construct(
        private Category $category
    )
    {
        //
    }

    public function getPaginate(Request $request)
    {
        $paginationable = new Paginationable($this->category, $request);

        return $paginationable->paginate();
    }

    public function store(array $data)
    {
        $existedCategorySlug = Category::where('slug', $data['slug'])->limit(1)->first();

        if ($existedCategorySlug) {
            throw ValidationException::withMessages([
                'title' => 'This category is already exist.'
            ]);
        }

        return DB::transaction(fn() => $this->category->create($data));
    }

    public function show(string $uuid)
    {
        return $this->category->where('uuid', $uuid)->first();
    }
}