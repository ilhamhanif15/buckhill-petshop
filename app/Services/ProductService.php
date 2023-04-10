<?php 

namespace App\Services;

use App\Models\Product;
use App\Utils\Paginationable;
use Illuminate\Http\Request;

class ProductService
{
    public function __construct(
        protected Product $product
    )
    {
        //
    }

    public function getPaginate(Request $request)
    {
        $paginationable = new Paginationable($this->product, $request);

        return $paginationable->paginate();
    }
}