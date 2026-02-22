<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Imports\DosenImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Database\UniqueConstraintViolationException;

class DosenManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $dosen = User::where('role', 'dosen')
            ->when($search, function ($query, $search) {
                return $query->searchUser($search);
            })
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.management.dosen.index', compact('dosen', 'search'));
    }

    public function create()
    {
        return view('admin.management.dosen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'no_hp' => 'nullable|string',
            'NIDN' => 'required|numeric|digits_between:8,10|unique:users,NIDN',
            'bidang_keahlian' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ], [
            'NIDN.required' => 'NIDN wajib diisi.',
            'NIDN.numeric' => 'NIDN harus berupa angka.',
            'NIDN.digits_between' => 'NIDN harus terdiri dari 8 sampai 10 digit.',
            'NIDN.unique' => 'NIDN sudah digunakan oleh dosen lain.',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['role'] = 'dosen';

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('profile_pictures', 'public');
        }

        User::create($data);

        return redirect()->route('admin.management.dosen.index')->with('success', 'Dosen berhasil ditambahkan.');
    }

    public function edit(User $dosen)
    {
        return view('admin.management.dosen.edit', compact('dosen'));
    }

    public function update(Request $request, User $dosen)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $dosen->id,
            'password' => 'nullable|min:6',
            'no_hp' => 'nullable|string',
            'NIDN' => 'required|numeric|digits_between:8,10|unique:users,NIDN,' . $dosen->id,
            'bidang_keahlian' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ], [
            'NIDN.required' => 'NIDN wajib diisi.',
            'NIDN.numeric' => 'NIDN harus berupa angka.',
            'NIDN.digits_between' => 'NIDN harus terdiri dari 8 sampai 10 digit.',
            'NIDN.unique' => 'NIDN sudah digunakan oleh dosen lain.',
        ]);

        $data = $request->except(['_token', '_method', 'password']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($dosen->foto) {
                Storage::disk('public')->delete($dosen->foto);
            }
            $data['foto'] = $request->file('foto')->store('profile_pictures', 'public');
        }

        $dosen->update($data);

        return redirect()->route('admin.management.dosen.index')->with('success', "Data Dosen \"{$dosen->name}\" berhasil diperbarui.");
    }

    public function destroy(User $dosen)
    {
        if ($dosen->foto) {
            Storage::disk('public')->delete($dosen->foto);
        }
        $dosen->delete();
        return redirect()->route('admin.management.dosen.index')->with('success', "Data Dosen \"{$dosen->name}\" berhasil dihapus.");
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new DosenImport, $request->file('file'));

            return redirect()->route('admin.management.dosen.index')->with('success', 'Data dosen berhasil diimport!');
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $messages = [];
            foreach ($failures as $failure) {
                $row = $failure->row();
                $errors = $failure->errors();
                $messages[] = "Baris {$row}: " . implode(', ', $errors);
            }

            return redirect()->back()->with('error', 'Gagal mengimpor data:<br>' . implode('<br>', $messages));
        } catch (UniqueConstraintViolationException $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data: Terdapat duplikasi data (NIDN atau email) yang tidak valid.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }
}
