<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create and save the new category
        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json($category, 201);
    }
    public function update(Request $request, $id)
    {
        // Find the category by ID
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update the category name
        $category->update([
            'name' => $request->name,
        ]);

        return response()->json($category);
    }
    public function destroy($id)
    {
        // Find the category by ID
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Delete the category
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
