<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Traits\RespondsWithHttpStatus;

class CategoryController extends Controller
{
    use RespondsWithHttpStatus;

    public function index()
    {
        $categories = Category::with('posts')->paginate(20);
        if (count($categories) > 0) {
            return $this->success('All Categories', $categories, 200);
        }
        return $this->failure('No Categories found', 200);
    }

    public function store(CreateCategoryRequest $request)
    {
        $categoryCreated = Category::create($request->only('name'));
        if ($categoryCreated) {
            return $this->success('Category created successfully', ['data' => $categoryCreated], 201);
        }
        return $this->failure('Create new category failed', 417);
    }

    public function show(Category $category)
    {
        if ($category) {
            return $this->success('Category', $category->load('posts'), 200);
        }
        return $this->failure('No category found', 417);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $categoryUpdated = $category->update($request->only('name'));
        if ($categoryUpdated) {
            return $this->success('Category updated successfully', $category->load('posts'), 200);
        }
        return $this->failure('Update category failed', 417);
    }

    public function destroy(Category $category)
    {
        $categoryDeleted = $category->delete();
        if ($categoryDeleted) {
            return $this->success('Category updated successfully', $category, 200);
        }
        return $this->failure('delete category failed', 417);
    }
}

