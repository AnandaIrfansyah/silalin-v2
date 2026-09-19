<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KriteriaAndalalin;
use Illuminate\Http\Request;

class KriteriaAndalalinController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', '');
        $search   = $request->get('search', '');

        $kriteria = KriteriaAndalalin::when($kategori, fn($q) => $q->where('kategori', $kategori))
            ->when($search, fn($q) => $q->where('jenis_kegiatan', 'like', "%{$search}%"))
            ->orderBy('jenis_kegiatan')
            ->paginate(10);

        return view('admin.kriteria-andalalin.index', compact('kriteria', 'kategori', 'search'));
    }

    public function show($id)
    {
        $k = KriteriaAndalalin::findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $k,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        KriteriaAndalalin::create($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Kriteria Andalalin berhasil ditambahkan.',
        ]);
    }

    public function update(Request $request, $id)
    {
        $k = KriteriaAndalalin::findOrFail($id);

        $validated = $this->validateData($request);

        $k->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Kriteria Andalalin berhasil diperbarui.',
        ]);
    }

    public function destroy($id)
    {
        $k = KriteriaAndalalin::findOrFail($id);
        $k->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Kriteria Andalalin berhasil dihapus.',
        ]);
    }

    private function validateData(Request $request)
    {
        return $request->validate([
            'jenis_kegiatan' => ['required', 'string', 'max:255'],
            'parameter'      => ['required', 'string', 'max:255'],
            'kategori'       => ['required', 'in:rendah,sedang,tinggi'],
            'batas_min'      => ['nullable', 'numeric', 'min:0'],
            'batas_max'      => ['nullable', 'numeric', 'min:0', 'gte:batas_min'],
            'satuan'         => ['nullable', 'string', 'max:255'],
        ]);
    }
}
