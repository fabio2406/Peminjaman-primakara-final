<?php

namespace App\Http\Controllers\Admin;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');
        if (Auth::user()->role === 'peminjam') {
            $query = ActivityLog::whereIn('pinjam_id', function ($subquery) {
                $subquery->select('id')
                         ->from('pinjams')
                         ->where('user_id', Auth::id());
            })->with('user');
        }elseif (Auth::user()->role === 'dala') {
            $query = ActivityLog::whereIn('pinjam_id', function ($subquery) {
                $subquery->select('id')
                         ->from('pinjams')
                         ->whereNotNull('status_dala');
            })->with('user');
        }elseif (Auth::user()->role === 'sdm') {
            $query = ActivityLog::whereIn('pinjam_id', function ($subquery) {
                $subquery->select('id')
                         ->from('pinjams')
                         ->whereNotNull('status_sdm');
            })->with('user');
        }elseif (Auth::user()->role === 'warek') {
            $query = ActivityLog::whereIn('pinjam_id', function ($subquery) {
                $subquery->select('id')
                         ->from('pinjams')
                         ->whereNotNull('status_warek');
            })->with('user');
        }

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search !== null) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function ($query) use ($search) {
                    $query->where('username', 'LIKE', "%$search%");
                })
                ->orWhere('pinjam_id', 'LIKE', "%$search%");
            });
        }

        // Filter berdasarkan tipe aktivitas
        if ($request->has('activity_type') && $request->activity_type !== 'all') {
            $query->where('activity_type', $request->activity_type);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->has('from_date') && $request->from_date) {
            $fromDate = $request->from_date . ' 00:00:00'; // Menambahkan jam 00:00 untuk from_date
            if ($request->has('until_date') && $request->until_date) {
                $untilDate = $request->until_date . ' 23:59:59'; // Menambahkan jam 23:59 untuk until_date
                // Jika from_date dan until_date keduanya ada, gunakan rentang antara keduanya
                $query->whereBetween('created_at', [$fromDate, $untilDate]);
            } else {
                // Jika hanya ada from_date, filter mulai dari tanggal tersebut hingga sekarang
                $query->where('created_at', '>=', $fromDate);
            }
        } elseif ($request->has('until_date') && $request->until_date) {
            $untilDate = $request->until_date . ' 23:59:59'; // Menambahkan jam 23:59 untuk until_date
            // Jika hanya ada until_date, filter hingga tanggal tersebut dari awal waktu
            $query->where('created_at', '<=', $untilDate);
        }


        // Menggunakan pagination dengan limit 10 per halaman
        $logs = $query->orderBy('created_at', 'desc')->paginate(10);

        // Jika request menggunakan AJAX, cek apakah filter bekerja
        if ($request->ajax()) {
            return response()->json([
                'logs' => $logs,
                'filters' => [
                    'search' => $request->search,
                    'activity_type' => $request->activity_type,
                    'from_date' => $request->from_date,
                    'until_date' => $request->until_date,
                ],
                'pagination' => (string) $logs->links('pagination::bootstrap-5'),
            ]);
        }
        if (Auth::user()->role === 'peminjam') {
            return view('peminjam.logs.index', compact('logs'));
        }elseif (Auth::user()->role === 'dala') {
            return view('dala.logs.index', compact('logs'));
        }elseif(Auth::user()->role === 'sdm'){
            return view('sdm.logs.index', compact('logs'));
        }elseif(Auth::user()->role === 'warek'){
            return view('warek.logs.index', compact('logs'));
        }else{
            return view('admin.logs.index', compact('logs'));   
        }
    }

    
    public function show($id)
    {
        // Ambil data activity log berdasarkan ID
        $activityLog = ActivityLog::find($id);

        // Cek apakah data ditemukan
        if (!$activityLog) {
            return abort(404, 'Activity log tidak ditemukan.');
        }
        // Decode kolom 'changes' dari JSON ke array
        $changes = json_decode($activityLog->changes, true);

        // Pastikan key 'before' dan 'after' selalu ada
        $changes['before'] = $changes['before'] ?? []; // Berikan default array kosong jika tidak ada 'before'
        $changes['after'] = $changes['after'] ?? []; 
        
        // Kirim data ke view
        return view('admin.logs.show', compact('changes'));
    }
}
