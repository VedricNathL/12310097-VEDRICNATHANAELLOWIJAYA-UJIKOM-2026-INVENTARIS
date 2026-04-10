<?php

namespace App\Http\Controllers;

use App\Models\ItemStock;
use App\Models\ItemCategories;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class ItemStocksController extends Controller
{
    public function index()
    {
        $items = ItemStock::with('category')->get();
        $categories = ItemCategories::all();

        return view('item', compact('items', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'item_name' => 'required',
            'total_stock' => 'required|numeric'
        ]);

        // Buat item baru tanpa menyentuh total_repaired
        $item = new ItemStock();
        $item->item_name = $request->item_name;
        $item->category_id = $request->category_id;
        $item->total_stock = $request->total_stock;
        $item->total_repaired = 0; // default 0
        $item->total_borrowed = 0; // default 0
        $item->save();

        return redirect('/item');
    }

    public function edit($id)
    {
        $item = ItemStock::findOrFail($id);
        $categories = ItemCategories::all();

        return view('item.edit', compact('item', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $item = ItemStock::findOrFail($id);

        $item->item_name = $request->item_name;
        $item->category_id = $request->category_id;
        $item->total_stock = $request->total_stock;
        $item->total_repaired = $request->total_repaired ?? $item->total_repaired;
        $item->total_borrowed = $request->total_borrowed ?? $item->total_borrowed;

        $item->save();

        return redirect('/item');
    }

    public function destroy($id)
    {
        ItemStock::findOrFail($id)->delete();

        return redirect('/item');
    }

    public function export()
    {
        $items = ItemStock::with('category')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Item Name');
        $sheet->setCellValue('B1', 'Category');
        $sheet->setCellValue('C1', 'Total Stock');
        $sheet->setCellValue('D1', 'Total Repaired');
        $sheet->setCellValue('E1', 'Created At');

        $row = 2;
        foreach ($items as $item) {
            $sheet->setCellValue('A' . $row, $item->item_name);
            $sheet->setCellValue('B' . $row, $item->category);
            $sheet->setCellValue('C' . $row, $item->total_stock);
            $sheet->setCellValue('D' . $row, $item->total_repaired == 0 ? '-' : $item->total_repaired);

            // Format tanggal: "Nama Bulan Tanggal, Tahun"
            $createdAt = Carbon::parse($item->created_at);
            $sheet->setCellValue('E' . $row, $createdAt->format('F j, Y'));

            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'item_stocks.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return Response::download($temp_file, $fileName)->deleteFileAfterSend(true);
    }
}