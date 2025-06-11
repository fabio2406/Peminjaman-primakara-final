<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ItemsImport;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('category'); // Mengambil item beserta kategori

        // Cek apakah ada parameter pencarian
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('kode_item', 'LIKE', "%$search%")
                ->orWhere('nama_item', 'LIKE', "%$search%");
            });
        }

        // Cek apakah ada filter kategori
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        // Cek apakah ada filter kategori
        if ($request->has('pengelola') && $request->pengelola !== 'all') {
            $query->where('pengelola', $request->pengelola);
        }

        // Menggunakan pagination dengan limit 10 per halaman
        $items = $query->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'items' => $items,
                'pagination' => (string) $items->links('pagination::bootstrap-5'), // Menggunakan bootstrap-5
            ]);
        }

        // Ambil semua kategori untuk dropdown
        $categories = Category::all();

        return view('admin.items.index', compact('items', 'categories'));
    }



    public function create()
    {
        $categories = Category::all();
        return view('admin.items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_item' => 'required|unique:items',
            'nama_item' => 'required',
            'stok' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'pengelola' => 'required|in:dpt,sdm,dala,warek',
        ]);

        Item::create($request->all());
        return redirect()->route('admin.items.index')->with('success', 'Item created successfully.');
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('admin.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'kode_item' => 'required|unique:items,kode_item,' . $item->id,
            'nama_item' => 'required',
            'stok' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'pengelola' => 'required|in:dpt,sdm,dala,warek',
        ]);

        $item->update($request->all());
        return redirect()->route('admin.items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {

        try {$item->delete();
            
           
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Kode kesalahan untuk integritas referensial
                return redirect()->route('admin.items.index')->withErrors('Item tidak dapat dihapus karena masih ada peminjaman.');
            }
            // Tangani pengecualian lainnya jika perlu
            return redirect()->route('admin.items.index')->withErrors('Terjadi kesalahan saat menghapus item.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.items.index')->withErrors('Item tidak ditemukan.');
        }
        return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully.');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv|max:10240', // Validasi file
        ]);

        // Import file menggunakan ItemsImport
        Excel::import(new ItemsImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data berhasil diimport.');
    }
}
