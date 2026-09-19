<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterFasilitas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterFasilitasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $fasilitas = MasterFasilitas::when($search, fn($q) => $q->where('nama_fasilitas', 'like', "%{$search}%"))
            ->orderBy('urutan')
            ->paginate(10);

        return view('admin.master-fasilitas.index', compact('fasilitas', 'search'));
    }

    public function show($id)
    {
        $f = MasterFasilitas::findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $f,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        MasterFasilitas::create($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Fasilitas berhasil ditambahkan.',
        ]);
    }

    public function update(Request $request, $id)
    {
        $f = MasterFasilitas::findOrFail($id);

        $validated = $this->validateData($request, $id);

        $f->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Fasilitas berhasil diperbarui.',
        ]);
    }

    public function toggleActive($id)
    {
        $f = MasterFasilitas::findOrFail($id);
        $f->is_active = ! $f->is_active;
        $f->save();

        return response()->json([
            'status'  => true,
            'message' => 'Status fasilitas berhasil diubah.',
        ]);
    }

    public function destroy($id)
    {
        $f = MasterFasilitas::findOrFail($id);

        if ($f->fasilitas()->exists() ?? false) {
            // guard opsional kalau nanti relasi ke pivot sudah ada
        }

        $f->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Fasilitas berhasil dihapus.',
        ]);
    }

    private function validateData(Request $request, $ignoreId = null)
    {
        return $request->validate([
            'nama_fasilitas' => ['required', 'string', 'max:255'],
            'kode'           => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('master_fasilitas', 'kode')->ignore($ignoreId)],
            'deskripsi'      => ['nullable', 'string'],
            'urutan'         => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
