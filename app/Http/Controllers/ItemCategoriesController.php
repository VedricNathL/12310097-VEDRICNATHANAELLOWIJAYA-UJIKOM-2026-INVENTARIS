<?php

namespace App\Http\Controllers;

use App\Models\ItemCategories;
use Illuminate\Http\Request;

class ItemCategoriesController extends Controller
{
    public function index()
    {
        $categories = ItemCategories::withCount('stocks')->get();
        return view('/category', compact('categories'));
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'division' => 'required'
        ]);

        ItemCategories::create($request->all());

        return redirect('/category');
    }

    public function edit($id)
    {
        $category = ItemCategories::findOrFail($id);
        return view('category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = ItemCategories::findOrFail($id);
        $category->update($request->all());

        return redirect('/category');
    }

    public function destroy($id)
    {
        ItemCategories::findOrFail($id)->delete();
        return redirect('/category');
    }
}