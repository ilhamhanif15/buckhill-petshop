<?php 

namespace App\Services;

use App\Models\Category;
use App\Utils\Paginationable;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
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

    /**
     * @param Request $request
     * @return Paginator
     */
    public function getPaginate(Request $request)
    {
        $paginationable = new Paginationable($this->category, $request);

        return $paginationable->paginate();
    }

    /**
     * @param array $data
     * @return Category
     */
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

    /**
     * @param string $uuid
     * @return Category|null
     */
    public function show(string $uuid)
    {
        return $this->category->where('uuid', $uuid)->first();
    }

    /**
     * @param array $data
     * @param string $uuid
     * @return Category
     */
    public function update(array $data, string $uuid)
    {
        $categoryFound = $this->show($uuid);

        if (!$categoryFound) {
            abort(404, "Category Not Found.");
        }

        return DB::transaction(fn() => tap($categoryFound)->update($data)->refresh());
    }

    /**
     * @param string $uuid
     * @return Category
     */
    public function destroy(string $uuid)
    {
        $categoryFound = $this->show($uuid);

        if (!$categoryFound) {
            abort(404, "Category Not Found.");
        }

        $deletedData = $categoryFound;

        DB::transaction(fn() => $categoryFound->delete());

        return $deletedData;        
    }
}