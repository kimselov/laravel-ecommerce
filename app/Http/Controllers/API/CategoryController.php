<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json(["data" => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => ["required","max:50","string"],
            "slug" => ["required","max:50","string"],
        ]);
        // $category = Category::create($validated);
        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->save();
        return response()->json(['message' => 'Category created', 'data' => $category]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json(["data" =>  $category ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
             "name" => ["required","max:50","string"],
             "slug" => ["required","max:50","string"],
        ]);

        $category = Category::findOrfail($id);
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->save();
        return response()->json(["message" => "Category Updated", "data" => $category]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrfail($id);
        $category->delete();
        return response()->json(["message" => "Category Deleted Successfully", "data" => $category]);
    }
}
