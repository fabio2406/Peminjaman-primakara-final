<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\Pinjam;
use App\Models\Category;
use App\Models\PinjamDetail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
         // Ambil semua kategori untuk dropdown
        $categories = Category::all();
        $items = Item::all();

        return view('welcome', compact(
            'items',
            'categories'
        ));
    }

    public function dashboardAdmin()
    {
         // Ambil semua kategori untuk dropdown
         $categories = Category::all();
         
        $totalUsers = User::count();
        $totalItems = Item::count();
        $totalLoans = Pinjam::count();
        
        $pendingLoans = Pinjam::where('status', 'pending')->count();
        $approvedLoans = Pinjam::where('status', 'approved')->count();
        $rejectedLoans = Pinjam::where('status', 'rejected')->count();
        $returnedLoans = Pinjam::where('status', 'returned')->count();

        $items = Item::all();

        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalItems', 
            'totalLoans', 
            'pendingLoans', 
            'approvedLoans', 
            'rejectedLoans', 
            'returnedLoans',
            'items',
            'categories'
        ));
    }

    public function dashboardPeminjam()
    {
         // Ambil semua kategori untuk dropdown
         $categories = Category::all();

        $totalLoans = Pinjam::where('user_id',Auth::id() )->count();

        $pendingLoans = Pinjam::where('user_id',Auth::id() )->where('status', 'pending')->count();
        $approvedLoans = Pinjam::where('user_id',Auth::id() )->where('status', 'approved')->count();
        $rejectedLoans = Pinjam::where('user_id',Auth::id() )->where('status', 'rejected')->count();
        $returnedLoans = Pinjam::where('user_id',Auth::id() )->where('status', 'returned')->count();

        $items = Item::all();

        return view('peminjam.dashboard', compact(
            'totalLoans', 
            'pendingLoans', 
            'approvedLoans', 
            'rejectedLoans', 
            'returnedLoans',
            'items',
            'categories'
        ));
    }
    public function dashboardDala()
    {
         // Ambil semua kategori untuk dropdown
         $categories = Category::all();

        $totalLoans = Pinjam::where('user_id',Auth::id() )->count();

        $pendingLoans = Pinjam::where('status_dala', 'pending')->count();
        $approvedLoans = Pinjam::where('status_dala', 'approved')->count();
        $rejectedLoans = Pinjam::where('status_dala', 'rejected')->count();


        $items = Item::all();

        return view('dala.dashboard', compact(
            'totalLoans', 
            'pendingLoans', 
            'approvedLoans', 
            'rejectedLoans', 
            'items',
            'categories'
        ));
    }

    public function dashboardSdm()
    {
         // Ambil semua kategori untuk dropdown
         $categories = Category::all();

        $totalLoans = Pinjam::where('user_id',Auth::id() )->count();

        $pendingLoans = Pinjam::where('status_sdm', 'pending')->count();
        $approvedLoans = Pinjam::where('status_sdm', 'approved')->count();
        $rejectedLoans = Pinjam::where('status_sdm', 'rejected')->count();


        $items = Item::all();

        return view('sdm.dashboard', compact(
            'totalLoans', 
            'pendingLoans', 
            'approvedLoans', 
            'rejectedLoans', 
            'items',
            'categories'
        ));
    }

    public function dashboardWarek()
    {
         // Ambil semua kategori untuk dropdown
         $categories = Category::all();

        $totalLoans = Pinjam::where('user_id',Auth::id() )->count();

        $pendingLoans = Pinjam::where('status_warek', 'pending')->count();
        $approvedLoans = Pinjam::where('status_warek', 'approved')->count();
        $rejectedLoans = Pinjam::where('status_warek', 'rejected')->count();


        $items = Item::all();

        return view('warek.dashboard', compact(
            'totalLoans', 
            'pendingLoans', 
            'approvedLoans', 
            'rejectedLoans', 
            'items',
            'categories'
        ));
    }

    public function filterItems(Request $request)
    {
        // Ambil nilai input dari request
        $search = $request->input('search');
        $category = $request->input('category');
        $pengelola = $request->input('pengelola');
        $loanDate = $request->input('loan_date');
        $returnDate = $request->input('return_date');

        // Query dasar untuk items
        $query = Item::query();



        // Filter berdasarkan kategori
        if ($category && $category != 'all') {
            $query->where('category_id', $category);
        }

        if ($pengelola && $pengelola != 'all') {
            $query->where('pengelola', $pengelola);
        }

        // Filter berdasarkan pencarian (nama item atau kode item)
        if ($search) {
            $query->where('nama_item', 'like', '%' . $search . '%')
                ->orWhere('kode_item', 'like', '%' . $search . '%');
        }

        // // Filter stok berdasarkan tanggal peminjaman dan pengembalian (logika stok ditambahkan jika diperlukan)
        // if ($loanDate && $returnDate) {
        //     // Logika pengecekan stok berdasarkan tanggal peminjaman dan pengembalian
        // }

        // Ambil data item yang sudah difilter dengan pagination
        $items = $query->with('category')->paginate(10); // Batasi 10 item per halaman

        // Kembalikan data item dan informasi pagination sebagai response JSON
        return response()->json([
            'items' => $items->items(), // Data item
            'pagination' => (string) $items->links('pagination::bootstrap-5'), // Pagination dengan style Bootstrap
        ]);
    }


    public function getAvailableItems(Request $request) : JsonResponse
    {
        $loanDate = $request->query('loan_date');
        $returnDate = $request->query('return_date');
    
        // Ambil semua item yang akan diperiksa
        $items = Item::all();
        $availableStocks = [];
    
        foreach ($items as $item) {
            // Hitung stok yang sedang dipinjam dengan status approved
            $borrowedQtyApproved = PinjamDetail::where('item_id', $item->id)
            ->whereHas('pinjam', function ($query) use ($loanDate, $returnDate) {
                $query->where(function($query) use ($loanDate, $returnDate) {
                    $query->where(function ($query) use ($loanDate, $returnDate) {
                        // Memeriksa apakah rentang tanggal peminjaman tumpang tindih
                        $query->where('loan_date', '<=', $returnDate)
                              ->where('return_date', '>=', $loanDate);
                    })->whereIn('status', ['approved']);
                });
            })
            ->sum('qty');
        
            // Hitung stok yang tersedia
            $availableStocks[$item->id] = max(0, $item->stok - $borrowedQtyApproved);
        }
    
        return response()->json($availableStocks); // Mengembalikan stok yang tersedia dalam format JSON
    }

}
