<?php

namespace App\Http\Services\Category;

use App\Models\Category;
use Illuminate\Support\Facades\Session;

class CategoryService {

    public function getParent()
    {
        return Category::where('parent_id', 0)->get();
    }

    public function show()
    {
        return Category::select('name', 'id')
            ->where('parent_id', 0)
            ->orderby('id')
            ->get();
    }

    public function getAll()
    {
        return Category::orderbyDesc('id')->paginate(20);
    }


    public function create($request)
    {
        try {
            Category::create([
                'name' => (string)$request->input('name'),
                'parent_id' => (int)$request->input('parent_id'),
                'description' => (string)$request->input('description'),
                'content' => (string)$request->input('content'),
                'active' => (string)$request->input('active')
            ]);

            Session::flash('success', 'Tạo Danh Mục Thành Công');
        } catch (\Exception $err) {
            Session::flash('error', $err->getMessage());
            return false;
        }

        return true;
    }

    public function update($request, $category): bool
    {
        if ($request->input('parent_id') != $category->id) {
            $category->parent_id = (int)$request->input('parent_id');
        }

        $category->name = (string)$request->input('name');
        $category->description = (string)$request->input('description');
        $category->content = (string)$request->input('content');
        $category->active = (string)$request->input('active');
        $category->save();

        Session::flash('success', 'Cập nhật thành công Danh mục');
        return true;
    }

    public function destroy($request)
    {
        $id = (int)$request->input('id');
        $category = Category::where('id', $id)->first();
        if ($category) {
            return Category::where('id', $id)->orWhere('parent_id', $id)->delete();
        }

        return false;
    }

    public function getId($id)
    {
        return Category::where('id', $id)->where('active', 1)->firstOrFail();
    }

    public function getProduct($category, $request)
    {
        $query = $category->products()
            ->select('id', 'name', 'price', 'price_sale', 'thumb')
            ->where('active', 1);

        if ($request->input('price')) {
            $query->orderBy('price', $request->input('price'));
        }

        return $query
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();
    }

}

