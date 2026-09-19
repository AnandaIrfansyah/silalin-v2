<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterDokumenPersyaratan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterDokumenController extends Controller
{
    public function index(Request $request)
    {
        $jenis  = $request->get('jenis_permohonan', '');
        $search = $request->get('search', '');

        $dokumen = MasterDokumenPersyaratan::when($jenis, fn($q) => $q->where('jenis_permohonan', $jenis))
            ->when($search, fn($q) => $q->where('nama_dokumen', 'like', "%{$search}%"))
            ->orderBy('urutan')
            ->paginate(10);

        return view('admin.master-dokumen.index', compact('dokumen', 'jenis', 'search'));
    }

    public function show($id)
    {
        return response()->json([
            'status' => true,
            'data' => MasterDokumenPersyaratan::findOrFail($id),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        MasterDokumenPersyaratan::create($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Dokumen persyaratan berhasil ditambahkan.',
        ]);
    }

    public function update(Request $request, $id)
    {
        $d = MasterDokumenPersyaratan::findOrFail($id);

        $validated = $this->validateData($request, $id);

        $d->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Dokumen persyaratan berhasil diperbarui.',
        ]);
    }

    public function toggleActive($id)
    {
        $d = MasterDokumenPersyaratan::findOrFail($id);
        $d->is_active = ! $d->is_active;
        $d->save();

        return response()->json([
            'status'  => true,
            'message' => 'Status dokumen berhasil diubah.',
        ]);
    }

    public function destroy($id)
    {
        $d = MasterDokumenPersyaratan::findOrFail($id);
        $d->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Dokumen persyaratan berhasil dihapus.',
        ]);
    }

    private function validateData(Request $request, $ignoreId = null)
    {
        return $request->validate([
            'nama_dokumen'     => ['required', 'string', 'max:255'],
            'kode'             => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('master_dokumen_persyaratan', 'kode')->ignore($ignoreId)],
            'jenis_permohonan' => ['required', 'in:andalalin,parkir,semua'],
            'keterangan'       => ['nullable', 'string'],
            'is_wajib'         => ['required', 'boolean'],
            'urutan'           => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
