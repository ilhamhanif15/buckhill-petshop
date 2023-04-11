<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Illuminate\Support\Str;

class ProductServiceTest extends TestCase
{
    public function _create_product()
    {
        $category = Category::create([
            'title' => 'Category Test',
            'slug' => Str::slug('Category Test')
        ]);

        $data = [
            'category_uuid' => $category->uuid,
            'title' => 'Product Test',
            'price' => 100,
            'description' => 'Product Test Description',
            'metadata' => [],
        ];

        /** @var ProductService $productService */
        $productService = app(ProductService::class);

        $product = $productService->store($data);

        return [
            $product,
            $category,
            $data,
            $productService
        ];
    }

    public function test_create_new_product(): void
    {
        /** @var ProductService $productService */
        [$product, $category, $data] = $this->_create_product();

        $this->assertTrue($product->category_uuid === $data['category_uuid']);
        $this->assertTrue($product->title === $data['title']);
        $this->assertTrue($product->price === $data['price']);
        $this->assertTrue($product->description === $data['description']);

        $product->forceDelete();
        $category->forceDelete();
    }

    public function test_update_a_product(): void
    {   
        /** @var ProductService $productService */
        [$product, $category, $data, $productService] = $this->_create_product();

        $updatedProduct = $productService->update([
            'title' => 'Product Update Test'
        ], $product->uuid);

        $this->assertTrue($updatedProduct->title === 'Product Update Test');

        $product->forceDelete();
        $category->forceDelete();
    }

    public function test_delete_a_product(): void
    {   
        /** @var ProductService $productService */
        [$product, $category, $data, $productService] = $this->_create_product();

        $deletedProduct = $productService->destroy($product->uuid);

        $this->assertEmpty(Product::where('uuid', $deletedProduct->uuid)->first());

        $category->forceDelete();
    }

    public function test_show_a_product(): void
    {
        /** @var ProductService $productService */
        [$product, $category, $data, $productService] = $this->_create_product();

        $showProduct = $productService->show($product->uuid);

        $this->assertNotEmpty($showProduct);

        $product->forceDelete();
        $category->forceDelete();
    }

    public function test_it_should_be_a_paginator(): void
    {
        /** @var ProductService $productService */
        [$product, $category, $data, $productService] = $this->_create_product();

        $paginator = $productService->getPaginate(new Request());

        $this->assertInstanceOf(LengthAwarePaginator::class, $paginator);

        $product->forceDelete();
        $category->forceDelete();
    }
}
