<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateFormRequest;
use Illuminate\Http\Request;
use App\Http\Services\Category\CategoryService;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function create() {
        return view('admin.category.add', [
            'title' => 'Thêm Danh Mục Mới',
            'categories' => $this->categoryService->getParent()
        ]);
    }

    public function store(CreateFormRequest $request)
    {
        $this->categoryService->create($request);

        return redirect()->back();
    }

    public function index()
    {
        return view('admin.category.list', [
            'title' => 'Danh Sách Danh Mục Mới Nhất',
            'categories' => $this->categoryService->getAll()
        ]);
    }

    public function show(Category $category)
    {
        return view('admin.category.edit', [
            'title' => 'Chỉnh Sửa Danh Mục: ' . $category->name,
            'category' => $category,
            'categories' => $this->categoryService->getParent()
        ]);
    }

    public function update(Category $category, CreateFormRequest $request)
    {
        $this->categoryService->update($request, $category);

        return redirect('/admin/categories/list');
    }

    public function destroy(Request $request): JsonResponse
    {
        $result = $this->categoryService->destroy($request);
        if ($result) {
            return response()->json([
                'error' => false,
                'message' => 'Xóa thành công danh mục'
            ]);
        }

        return response()->json([
            'error' => true
        ]);
    }


}
