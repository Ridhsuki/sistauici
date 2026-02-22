<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MahasiswaImport;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Database\UniqueConstraintViolationException;

class MahasiswaManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $mahasiswas = User::where('role', 'mahasiswa')
            ->when($search, function ($query, $search) {
                return $query->searchUser($search);
            })
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();
        return view('admin.management.mahasiswa.index', compact('mahasiswas', 'search'));
    }

    public function create()
    {
        return view('admin.management.mahasiswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'no_hp' => 'nullable|string',
            'NIM' => 'required|numeric|digits_between:12,15|unique:users,NIM',
            'prodi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ], [
            'NIM.required' => 'NIM wajib diisi.',
            'NIM.numeric' => 'NIM harus berupa angka.',
            'NIM.digits_between' => 'NIM harus terdiri dari 12 sampai 15 digit.',
            'NIM.unique' => 'NIM sudah digunakan oleh pengguna lain.',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['role'] = 'mahasiswa';

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('profile_pictures', 'public');
        }

        User::create($data);

        return redirect()->route('admin.management.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan');
    }

    public function edit($id)
    {
        $mahasiswa = User::where('role', 'mahasiswa')->findOrFail($id);
        return view('admin.management.mahasiswa.edit', compact('mahasiswa'));
    }

    public function update(Request $request, User $mahasiswa)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $mahasiswa->id,
            'no_hp' => 'nullable|string',
            'NIM' => 'required|numeric|digits_between:12,15|unique:users,NIM,' . $mahasiswa->id,
            'prodi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ], [
            'NIM.required' => 'NIM wajib diisi.',
            'NIM.numeric' => 'NIM harus berupa angka.',
            'NIM.digits_between' => 'NIM harus terdiri dari 12 sampai 15 digit.',
            'NIM.unique' => 'NIM sudah digunakan oleh pengguna lain.',
        ]);

        $data = $request->all();

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('profile_pictures', 'public');
        }

        $mahasiswa->update($data);

        return redirect()->route('admin.management.mahasiswa.index')->with('success', "Data Mahasiswa \"{$mahasiswa->name}\" berhasil diperbarui.");
    }

    public function destroy(User $mahasiswa)
    {
        $mahasiswaName = $mahasiswa->name;
        $mahasiswa->delete();

        return redirect()->route('admin.management.mahasiswa.index')->with('success', "Mahasiswa \"$mahasiswaName\" berhasil dihapus.");
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new MahasiswaImport, $request->file('file'));

            return redirect()->route('admin.management.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diimport!');
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $messages = [];
            foreach ($failures as $failure) {
                $row = $failure->row();
                $errors = $failure->errors();
                $messages[] = "Baris {$row}: " . implode(', ', $errors);
            }

            return redirect()->back()->with('error', 'Gagal mengimpor data: ' . implode('<br>', $messages));
        } catch (UniqueConstraintViolationException $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data: Terdapat duplikasi data (NIM atau email) yang tidak valid.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }
}
