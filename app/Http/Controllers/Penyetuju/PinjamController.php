<?php

namespace App\Http\Controllers\Penyetuju;

use App\Models\Item;
use App\Models\User;
use App\Models\Pinjam;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PinjamController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data peminjaman
        $query = Pinjam::with('user');
        if (Auth::user()->role === 'dala') {
            $query = Pinjam::whereNotNull('status_dala')->with('user');
        }elseif(Auth::user()->role === 'sdm'){
            $query = Pinjam::whereNotNull('status_sdm')->with('user');
        }elseif(Auth::user()->role === 'warek'){
            $query = Pinjam::whereNotNull('status_warek')->with('user');
        }

       // Pencarian berdasarkan ID, User, dan Keterangan
       if ($request->has('search_all') && !empty($request->search_all)) {
        $search = $request->search_all;
        $query->where(function($q) use ($search) {
            $q->where('id', 'LIKE', "%{$search}%")
              ->orWhereHas('user', function($query) use ($search) {
                  $query->where('name', 'LIKE', "%{$search}%");
              })
              ->orWhere('keterangan_peminjam', 'LIKE', "%{$search}%")
              ->orWhere('keterangan_penyetuju', 'LIKE', "%{$search}%")
              ->orWhere('instansi', 'LIKE', "%{$search}%");
            });
        }

        // Filter berdasarkan tanggal peminjaman
        if ($request->has('loan_date_start') && $request->loan_date_start) {
            $query->where('loan_date', '>=', $request->loan_date_start);
        }
        if ($request->has('loan_date_end') && $request->loan_date_end) {
            $query->where('loan_date', '<=', $request->loan_date_end);
        }

        // Filter berdasarkan tanggal pengembalian
        if ($request->has('return_date_start') && $request->return_date_start) {
            $query->where('return_date', '>=', $request->return_date_start);
        }
        if ($request->has('return_date_end') && $request->return_date_end) {
            $query->where('return_date', '<=', $request->return_date_end);
        }

        // Filter berdasarkan status
        if ($request->has('status_filter') && $request->status_filter) {
            $query->where('status', $request->status_filter);
        }
        
        if ($request->has('status_sdm_filter') && $request->status_sdm_filter) {
            $query->where('status_sdm', $request->status_sdm_filter);
        }
        
        if ($request->has('status_dala_filter') && $request->status_dala_filter) {
            $query->where('status_dala', $request->status_dala_filter);
        }

        if ($request->has('status_warek_filter') && $request->status_warek_filter) {
            $query->where('status_warek', $request->status_warek_filter);
        }

        // Pagination
        $pinjams = $query->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'data' => $pinjams->items(),
                'pagination' => (string) $pinjams->links('pagination::bootstrap-5')
            ]);
        }

        if (Auth::user()->role === 'dala') {
            return view('dala.pinjams.index', compact('pinjams'));
        }elseif(Auth::user()->role === 'sdm'){
            return view('sdm.pinjams.index', compact('pinjams'));
        }elseif(Auth::user()->role === 'warek'){
            return view('warek.pinjams.index', compact('pinjams'));
        } 
    }

    public function updateStatus(Request $request, $id)
    {
        $pinjam = Pinjam::findOrFail($id);
        $original = $pinjam->getOriginal(); 
        if ($request->filled('status_warek')) {
            $pinjam->status_warek = $request->status_warek;
        }
        if ($request->filled('status_dala')) {
            $pinjam->status_dala = $request->status_dala;
        }
        if ($request->filled('status_sdm')) {
            $pinjam->status_sdm = $request->status_sdm;
        }
        // Menyimpan log aktivitas
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'update status',
            'pinjam_id' => $pinjam->id,
            'changes' => json_encode([
                'before' => $original,
                'after' => $pinjam->getAttributes()
            ]),
        ]);
        $pinjam->save();

        return redirect()->back()->with('success', 'Status updated successfully!');
    }

    public function edit($id)
    {
        $pinjam = Pinjam::with('details.item')->findOrFail($id);
        $items = Item::all(); // Semua item untuk modal
        $users = User::all(); // Semua user untuk dropdown
        $noPeminjam = User::where('id',  $pinjam->user_id )->first();
        $noAdmin = User::where('username', 'admin')->first();
        $peminjamWhatsappNumber = $noPeminjam ? $noPeminjam->phone : null;
        $adminWhatsappNumber = $noAdmin ? $noAdmin->phone : null;

        if (Auth::user()->role === 'dala') {
            return view('dala.pinjams.edit', compact('pinjam', 'items', 'users','adminWhatsappNumber','peminjamWhatsappNumber','noPeminjam'));
        }elseif(Auth::user()->role === 'sdm'){
            return view('sdm.pinjams.edit', compact('pinjam', 'items', 'users','adminWhatsappNumber','peminjamWhatsappNumber','noPeminjam'));
        }elseif(Auth::user()->role === 'warek'){
            return view('warek.pinjams.edit', compact('pinjam', 'items', 'users','adminWhatsappNumber','peminjamWhatsappNumber','noPeminjam'));
        }
    }

    public function update(Request $request, $id)
    {

        $pinjam = Pinjam::findOrFail($id);
        $original = $pinjam->getOriginal(); // Data awal sebelum perubahan

        if ($request->filled('status_warek')) {
            $pinjam->status_warek = $request->status_warek;
        }
        if ($request->filled('status_dala')) {
            $pinjam->status_dala = $request->status_dala;
        }
        if ($request->filled('status_sdm')) {
            $pinjam->status_sdm = $request->status_sdm;
        }
        $pinjam->keterangan_penyetuju = $request->keterangan_penyetuju;
        $pinjam->save();
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'update',
            'pinjam_id' => $id,
            'changes' => json_encode([
                'before' => $original,
                'after' => $pinjam->getAttributes()
            ]),
        ]);
        
        if (Auth::user()->role === 'dala') {
            return redirect()->route('dala.pinjams.index')->with('success', 'Data peminjaman berhasil diperbarui');
        }elseif(Auth::user()->role === 'sdm'){
            return redirect()->route('sdm.pinjams.index')->with('success', 'Data peminjaman berhasil diperbarui');
        }elseif(Auth::user()->role === 'warek'){
            return redirect()->route('warek.pinjams.index')->with('success', 'Data peminjaman berhasil diperbarui');
        }
    }

}
