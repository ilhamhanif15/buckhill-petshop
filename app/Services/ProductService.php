<?php 

namespace App\Services;

use App\Models\Product;
use App\Utils\Paginationable;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(
        protected Product $product
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
        $paginationable = new Paginationable($this->product->with([
            'category:uuid,title,slug'
        ]), $request);

        $paginationable->setSearchableColumns([
            'category.title',
            'title',
            'price',
            'description',
        ]);

        return $paginationable->paginate();
    }

    /**
     * @param array $data
     * @return Product
     */
    public function store(array $data)
    {
        // Default MetaData
        $data['metadata'] = [
            "brand" => null,
            "image" => null
        ];

        return DB::transaction(fn() => $this->product->create($data));
    }

    /**
     * @param string $uuid
     * @return Product|null
     */
    public function show(string $uuid)
    {
        return $this->product->with(['category:uuid,title,slug'])->where('uuid', $uuid)->first();
    }

    /**
     * @param array $data
     * @param string $uuid
     * @return Product
     */
    public function update(array $data, string $uuid)
    {
        $productFound = $this->show($uuid);

        if (!$productFound) {
            abort(404, "Product Not Found.");
        }

        return DB::transaction(fn() => tap($productFound)->update($data)->refresh());
    }

    /**
     * @param string $uuid
     * @return Product
     */
    public function destroy(string $uuid)
    {
        $productFound = $this->show($uuid);

        if (!$productFound) {
            abort(404, "Product Not Found.");
        }

        $deletedData = $productFound;

        DB::transaction(fn() => $productFound->delete());

        return $deletedData;        
    }
}