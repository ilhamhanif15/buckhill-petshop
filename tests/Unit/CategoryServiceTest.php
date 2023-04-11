<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;
use Illuminate\Support\Str;

class CategoryServiceTest extends TestCase
{
    public function _create_category()
    {
        $data = [
            'title' => 'Category service test',
            'slug' => Str::slug('Category service test')
        ];

        /** @var CategoryService $categoryService */
        $categoryService = app(CategoryService::class);

        $category = $categoryService->store($data);

        return [
            $category,
            $categoryService,
            $data 
        ];
    }

    public function test_create_new_product(): void
    {
        /** @var CategoryService $categoryService */
        [$category, $categoryService, $data] = $this->_create_category();

        $this->assertTrue($category->title === $data['title']);
        $this->assertTrue($category->slug === $data['slug']);

        $category->forceDelete();
    }

    public function test_update_a_product(): void
    {   
        /** @var CategoryService $categoryService */
        [$category, $categoryService, $data] = $this->_create_category();

        $updatedProduct = $categoryService->update([
            'title' => 'Category Update Test'
        ], $category->uuid);

        $this->assertTrue($updatedProduct->title === 'Category Update Test');

        $category->forceDelete();
    }

    public function test_delete_a_product(): void
    {   
        /** @var CategoryService $categoryService */
        [$category, $categoryService, $data] = $this->_create_category();

        $deletedCategory = $categoryService->destroy($category->uuid);

        $this->assertEmpty(Category::where('uuid', $deletedCategory->uuid)->first());

        $category->forceDelete();
    }

    public function test_show_a_product(): void
    {
        /** @var CategoryService $categoryService */
        [$category, $categoryService, $data] = $this->_create_category();

        $showProduct = $categoryService->show($category->uuid);

        $this->assertNotEmpty($showProduct);

        $category->forceDelete();
    }

    public function test_it_should_be_a_paginator(): void
    {
        /** @var CategoryService $categoryService */
        [$category, $categoryService, $data] = $this->_create_category();

        $paginator = $categoryService->getPaginate(new Request());

        $this->assertInstanceOf(LengthAwarePaginator::class, $paginator);

        $category->forceDelete();
    }
}
