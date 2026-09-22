<?php

namespace App\Http\Controllers\Pemohon;

use App\Http\Controllers\Controller;
use App\Models\KriteriaAndalalin;
use App\Models\MasterDokumenPersyaratan;
use App\Models\PermohonanAndalalin;
use App\Models\PermohonanAndalalinLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermohonanAndalalinController extends Controller
{
    public function index()
    {
        $permohonan = PermohonanAndalalin::where('user_id', Auth::id())->latest()->paginate(10);

        return view('pemohon.permohonan-andalalin.index', compact('permohonan'));
    }

    public function create()
    {
        $company = Auth::user()->company;

        if (! $company || $company->status !== 'verified') {
            return redirect()->route('pemohon.company.index')
                ->with('toast_error', 'Profil perusahaan harus terverifikasi sebelum mengajukan Andalalin.');
        }

        $jenisKegiatanList = KriteriaAndalalin::distinct()->orderBy('jenis_kegiatan')->pluck('jenis_kegiatan');
        $dokumenPersyaratan = MasterDokumenPersyaratan::untukJenis('andalalin')->get();

        return view('pemohon.permohonan-andalalin.create', compact('jenisKegiatanList', 'dokumenPersyaratan'));
    }

    /**
     * AJAX: ambil daftar parameter unik untuk satu jenis kegiatan
     */
    public function getParameters(Request $request)
    {
        $parameters = KriteriaAndalalin::where('jenis_kegiatan', $request->jenis_kegiatan)
            ->distinct()
            ->pluck('parameter');

        return response()->json(['status' => true, 'data' => $parameters]);
    }

    /**
     * AJAX: ambil daftar rentang nilai (+kategori) untuk kombinasi jenis kegiatan & parameter
     */
    public function getRentangNilai(Request $request)
    {
        $rentang = KriteriaAndalalin::where('jenis_kegiatan', $request->jenis_kegiatan)
            ->where('parameter', $request->parameter)
            ->orderByRaw('COALESCE(batas_min, 0)')
            ->get()
            ->map(function ($k) {
                $label = match (true) {
                    $k->batas_min && $k->batas_max => "{$this->formatAngka($k->batas_min)} s.d. {$this->formatAngka($k->batas_max)} {$k->satuan}",
                    $k->batas_min && ! $k->batas_max => "Di atas {$this->formatAngka($k->batas_min)} {$k->satuan}",
                    ! $k->batas_min && $k->batas_max => "Di bawah {$this->formatAngka($k->batas_max)} {$k->satuan}",
                    default => 'Wajib',
                };

                return [
                    'id'       => $k->id,
                    'label'    => $label,
                    'kategori' => $k->kategori,
                    'satuan'   => $k->satuan,
                ];
            });

        return response()->json(['status' => true, 'data' => $rentang]);
    }

    private function formatAngka($angka)
    {
        return number_format($angka, 0, ',', '.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kriteria_andalalin_id' => ['required', 'exists:kriteria_andalalins,id'],
            'alamat_proyek'         => ['required', 'string'],
        ]);

        $kriteria = KriteriaAndalalin::findOrFail($validated['kriteria_andalalin_id']);
        $dokumenWajib = MasterDokumenPersyaratan::untukJenis('andalalin')->where('is_wajib', true)->get();

        foreach ($dokumenWajib as $dok) {
            $request->validate([
                "dokumen.{$dok->kode}" => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            ], [], ["dokumen.{$dok->kode}" => $dok->nama_dokumen]);
        }

        $permohonan = DB::transaction(function () use ($request, $validated, $kriteria) {
            $permohonan = PermohonanAndalalin::create([
                'user_id'                 => Auth::id(),
                'company_id'              => Auth::user()->company->id,
                'kriteria_andalalin_id'   => $kriteria->id,
                'jenis_kegiatan_snapshot' => $kriteria->jenis_kegiatan,
                'parameter_snapshot'      => $kriteria->parameter,
                'kategori_bangkitan'      => $kriteria->kategori,
                'alamat_proyek'           => $validated['alamat_proyek'],
                'status'                  => 'diajukan',
            ]);

            $semuaDokumen = MasterDokumenPersyaratan::untukJenis('andalalin')->get();
            foreach ($semuaDokumen as $dok) {
                if ($request->hasFile("dokumen.{$dok->kode}")) {
                    $path = $request->file("dokumen.{$dok->kode}")->store('permohonan-andalalin/dokumen', 'public');

                    $permohonan->dokumen()->create([
                        'master_dokumen_id' => $dok->id,
                        'file_path'         => $path,
                        'uploaded_at'       => now(),
                    ]);
                }
            }

            PermohonanAndalalinLog::create([
                'permohonan_andalalin_id' => $permohonan->id,
                'user_id'                 => Auth::id(),
                'aksi'                    => 'submit',
                'status_sebelum'          => 'draft',
                'status_sesudah'          => 'diajukan',
            ]);

            return $permohonan;
        });

        return response()->json([
            'status'   => true,
            'message'  => 'Permohonan Andalalin berhasil diajukan.',
            'redirect' => route('pemohon.permohonan-andalalin.index'),
        ]);
    }

    public function show($id)
    {
        $permohonan = PermohonanAndalalin::with(['dokumen.masterDokumen', 'logs.user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json(['status' => true, 'data' => $permohonan]);
    }
}
