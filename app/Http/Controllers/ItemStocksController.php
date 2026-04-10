<?php

namespace App\Http\Controllers;

use App\Models\ItemStock;
use App\Models\ItemCategories;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ItemStocksExport;

class ItemStocksController extends Controller
{
    public function index()
    {
        $items = ItemStock::with('category')->get();
        $categories = ItemCategories::all();

        // Ambil session flash new broken item untuk Add
        $newBrokenItems = session('new_broken_items', []);

        return view('item', compact('items', 'categories', 'newBrokenItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'item_name' => 'required',
            'total_stock' => 'required|integer'
        ]);

        // Ambil field form
        $data = $request->only(['item_name', 'category_id', 'total_stock']);

        // Set default NOT NULL fields
        $data['total_repaired'] = 0;
        $data['total_borrowed'] = 0;

        $item = ItemStock::create($data);

        // Simpan nilai New Broken Item sementara di session
        $newBroken = $request->input('new_broken_item', 0);
        session()->flash('new_broken_items', [$item->id => $newBroken]);

        return redirect('/item');
    }

    public function update(Request $request, $id)
    {
        $item = ItemStock::findOrFail($id);

        $data = $request->only(['item_name', 'category_id', 'total_stock']);

        // Default field
        $data['total_repaired'] = $item->total_repaired ?? 0;
        $data['total_borrowed'] = $item->total_borrowed ?? 0;

        $item->update($data);

        return redirect('/item');
    }

    public function destroy($id)
    {
        ItemStock::findOrFail($id)->delete();

        return redirect('/item');
    }
    public function export()
    {
        return Excel::download(new ItemStocksExport, 'item_stocks.xlsx');
    }
}