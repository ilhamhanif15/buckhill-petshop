<?php 

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductService
{
    public function __construct(
        protected Product $product
    )
    {
        //
    }
}