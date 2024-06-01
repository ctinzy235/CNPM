<?php

namespace App\Http\Controllers;

use App\Http\Services\Category\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request, $id, $slug = '')
    {
        $category = $this->categoryService->getId($id);
        $products = $this->categoryService->getProduct($category, $request);

        return view('category', [
            'title' => $category->name,
            'products' => $products,
            'category'  => $category
        ]);
    }
}
