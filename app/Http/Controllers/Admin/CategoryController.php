<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::where('store_id', auth()->user()->store_id)->latest();
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $categories = $query->paginate(10)->withQueryString();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(function ($query) {
                    return $query->where('store_id', auth()->user()->store_id);
                })
            ],
            'description' => 'nullable|string',
            'status' => 'nullable'
        ]);


        try {
            Category::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->has('status'),
                'store_id' => auth()->user()->store_id
            ]);

            return redirect()
                ->route('admin.categories')
                ->with('success', 'Category created successfully');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create category. Please try again.');
        }
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        // Validate the request
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id)->where(function ($query) use ($category) {
                    return $query->where('store_id', auth()->user()->store_id);
                })
            ],
            'description' => 'nullable|string',
            'status' => 'nullable'
        ]);

        try {
            // Update category with proper data
            $category->update([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->has('status')
            ]);

            return redirect()
                ->route('admin.categories')
                ->with('success', 'Category updated successfully');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update category. Please try again.');
        }
    }

    public function destroy(Category $category)
    {
        try {
            // Check if category has products
            if ($category->products()->count() > 0) {
                return redirect()
                    ->route('admin.categories')
                    ->with('error', 'Cannot delete category with associated products');
            }

            $category->delete();

            return redirect()
                ->route('admin.categories')
                ->with('success', 'Category deleted successfully');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete category. Please try again.');
        }
    }
} 