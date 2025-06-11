<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    // Tampilkan daftar user
    // app/Http/Controllers/UserController.php

    public function index(Request $request)
{
    $query = User::query();

    // Cek apakah ada parameter pencarian
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%$search%")
              ->orWhere('username', 'LIKE', "%$search%")
              ->orWhere('phone', 'LIKE', "%$search%");
        });
    }

    // Cek apakah ada filter role
    if ($request->has('role') && $request->role !== 'all') {
        $query->where('role', $request->role);
    }

    // Cek apakah ada filter status
    if ($request->has('status') && $request->status !== 'all') {
        $query->where('status', $request->status);
    }

    $users = $query->paginate(10); // Batasi 10 item per halaman

    if ($request->ajax()) {
        return response()->json([
            'users' => $users,
            'pagination' => (string) $users->links('pagination::bootstrap-5') // Menggunakan Bootstrap 5 untuk pagination
        ]);
    }

    // Ambil semua roles dan statuses untuk dropdown
    $statuses = ['active', 'inactive'];

    return view('admin.users.index', compact('users', 'statuses'));
}



    // Tampilkan form untuk menambahkan user baru
    public function create()
    {
        return view('admin.users.create');
    }

    // Simpan user baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15',
            'role' => 'required|in:admin,peminjam,sdm,dala,warek',
            'status' => 'required|in:active,inactive',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Buat user baru
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $request->status,
            'keterangan' => $request->keterangan,  
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    // Tampilkan detail user untuk diedit
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Update data user
    public function update(Request $request, User $user)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15',
            'role' => 'required|in:admin,peminjam,sdm,dala,warek',
            'status' => 'required|in:active,inactive',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Update user
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $request->status,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    // Hapus user
    public function destroy(User $user)
    {

        if ($user->username=='admin'||$user->username=='Warek II') {
            return redirect()->route('admin.users.index')->withErrors('User tersebut tidak diizinkan untuk dihapus');
        }

        try {$user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Kode kesalahan untuk integritas referensial
                return redirect()->route('admin.users.index')->withErrors('user tidak dapat dihapus karena masih ada peminjaman.');
            }
            // Tangani pengecualian lainnya jika perlu
            
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.users.index')->withErrors('user tidak ditemukan.');
        }
    }
    
    public function toggleStatus(User $user)
    {
        // Mengubah status user antara 'active' dan 'inactive'
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User status updated successfully.');
    }

}
