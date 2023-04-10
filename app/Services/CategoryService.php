<?php 

namespace App\Services;

use App\Models\Category;
use App\Utils\Paginationable;
use Illuminate\Http\Request;

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
}