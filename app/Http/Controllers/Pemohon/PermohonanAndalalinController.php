<?php

namespace App\Http\Controllers\Pemohon;

use App\Http\Controllers\Controller;
use App\Models\KriteriaAndalalin;
use App\Models\MasterDokumenPersyaratan;
use App\Models\PermohonanAndalalin;
use App\Models\PermohonanAndalalinLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

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

    private function formatRentang($kriteria): string
    {
        return match (true) {
            $kriteria->batas_min && $kriteria->batas_max => number_format($kriteria->batas_min, 0, ',', '.') . ' s.d. ' . number_format($kriteria->batas_max, 0, ',', '.') . ' ' . $kriteria->satuan,
            $kriteria->batas_min && ! $kriteria->batas_max => 'Di atas ' . number_format($kriteria->batas_min, 0, ',', '.') . ' ' . $kriteria->satuan,
            ! $kriteria->batas_min && $kriteria->batas_max => 'Di bawah ' . number_format($kriteria->batas_max, 0, ',', '.') . ' ' . $kriteria->satuan,
            default => 'Wajib',
        };
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
                'user_id'                   => Auth::id(),
                'company_id'                => Auth::user()->company->id,
                'kriteria_andalalin_id'     => $kriteria->id,
                'jenis_kegiatan_snapshot'   => $kriteria->jenis_kegiatan,
                'parameter_snapshot'        => $kriteria->parameter,
                'nilai_parameter_snapshot'  => $this->formatRentang($kriteria), // baru
                'kategori_bangkitan'        => $kriteria->kategori,
                'alamat_proyek'             => $validated['alamat_proyek'],
                'status'                    => 'diajukan',
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

        $dokumenWajib = MasterDokumenPersyaratan::untukJenis('andalalin')->get();

        return view('pemohon.permohonan-andalalin.show', compact('permohonan', 'dokumenWajib'));
    }

    public function revisiBerkas(Request $request, $id)
    {
        $permohonan = PermohonanAndalalin::where('user_id', Auth::id())->findOrFail($id);

        if ($permohonan->status !== 'revisi_berkas') {
            return back()->with('toast_error', 'Permohonan ini tidak dalam tahap revisi berkas.');
        }

        $dokumenList = MasterDokumenPersyaratan::untukJenis('andalalin')->get();

        foreach ($dokumenList as $dok) {
            if ($request->hasFile("dokumen.{$dok->kode}")) {
                $path = $request->file("dokumen.{$dok->kode}")->store('permohonan-andalalin/dokumen', 'public');

                // hapus file lama biar gak numpuk sampah
                $existing = $permohonan->dokumen()->where('master_dokumen_id', $dok->id)->first();
                if ($existing) {
                    Storage::disk('public')->delete($existing->file_path);
                    $existing->update(['file_path' => $path, 'uploaded_at' => now()]);
                } else {
                    $permohonan->dokumen()->create([
                        'master_dokumen_id' => $dok->id,
                        'file_path'         => $path,
                        'uploaded_at'       => now(),
                    ]);
                }
            }
        }

        $permohonan->update(['status' => 'diajukan']);

        PermohonanAndalalinLog::create([
            'permohonan_andalalin_id' => $permohonan->id,
            'user_id'                 => Auth::id(),
            'aksi'                    => 'revisi_berkas_diupload',
            'status_sebelum'          => 'revisi_berkas',
            'status_sesudah'          => 'diajukan',
            'keterangan'              => 'Pemohon mengupload ulang dokumen yang direvisi',
        ]);

        return redirect()->route('pemohon.permohonan-andalalin.show', $id)
            ->with('toast_success', 'Dokumen berhasil diupload ulang, menunggu verifikasi Admin.');
    }

    public function uploadDokumenAndalalin(Request $request, $id)
    {
        $permohonan = PermohonanAndalalin::where('user_id', Auth::id())->findOrFail($id);

        if (! in_array($permohonan->status, ['menunggu_dokumen_andalalin', 'revisi_dokumen_andalalin'])) {
            return back()->with('toast_error', 'Permohonan ini tidak dalam tahap upload dokumen andalalin.');
        }

        $request->validate([
            'file_dokumen_andalalin' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        if ($permohonan->file_dokumen_andalalin) {
            Storage::disk('public')->delete($permohonan->file_dokumen_andalalin);
        }

        $path = $request->file('file_dokumen_andalalin')->store('permohonan-andalalin/dokumen-andalalin', 'public');

        $permohonan->update([
            'file_dokumen_andalalin' => $path,
            'catatan_verifikasi_dokumen_andalalin' => null,
        ]);

        PermohonanAndalalinLog::create([
            'permohonan_andalalin_id' => $permohonan->id,
            'user_id'                 => Auth::id(),
            'aksi'                    => 'upload_dokumen_andalalin',
            'status_sebelum'          => $permohonan->status,
            'status_sesudah'          => $permohonan->status,
            'keterangan'              => 'Pemohon mengupload dokumen andalalin, menunggu verifikasi Admin',
        ]);

        return redirect()->route('pemohon.permohonan-andalalin.show', $id)
            ->with('toast_success', 'Dokumen andalalin berhasil diupload, menunggu verifikasi Admin.');
    }

    public function downloadSuratPernyataan($id)
    {
        $permohonan = PermohonanAndalalin::with('company')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        abort_unless(in_array($permohonan->status, ['menunggu_ttd', 'revisi_ttd']), 404);

        $templatePath = resource_path('templates/surat_pernyataan_kesanggupan.docx');
        abort_unless(file_exists($templatePath), 404, 'Template surat pernyataan belum tersedia.');

        $template = new TemplateProcessor($templatePath);
        $tanggalDokumen = Carbon::parse($permohonan->tanggal_dokumen);

        $template->setValue('nama_penanggung_jawab', $permohonan->company->nama_penanggung_jawab);
        $template->setValue('jabatan_penanggung_jawab', $permohonan->company->jabatan_penanggung_jawab);
        $template->setValue('nama_perusahaan', $permohonan->company->nama_perusahaan);
        $template->setValue('alamat_perusahaan', $permohonan->company->alamat_perusahaan);
        $template->setValue('jenis_kegiatan', $permohonan->jenis_kegiatan_snapshot);
        $template->setValue('alamat_proyek', $permohonan->alamat_proyek);
        $template->setValue('jenis_sifat_naskah', $permohonan->jenis_sifat_naskah);
        $template->setValue('nomor_klasifikasi', $permohonan->nomor_klasifikasi);
        $template->setValue('nomor_urut_dokumen', $permohonan->nomor_urut_dokumen);
        $template->setValue('kode_bidang', $permohonan->kode_bidang);
        $template->setValue('tahun', $tanggalDokumen->format('Y'));
        $template->setValue('tanggal_dokumen_formatted', $tanggalDokumen->translatedFormat('d F Y'));

        $tempDir = storage_path('app/temp');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $fileName = 'Surat-Pernyataan-Kesanggupan-' . $permohonan->id . '.docx';
        $tempPath = $tempDir . '/' . $fileName;

        $template->saveAs($tempPath);

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    public function uploadTtd(Request $request, $id)
    {
        $permohonan = PermohonanAndalalin::where('user_id', Auth::id())->findOrFail($id);

        if (! in_array($permohonan->status, ['menunggu_ttd', 'revisi_ttd'])) {
            return back()->with('toast_error', 'Permohonan ini tidak dalam tahap upload tanda tangan.');
        }

        $request->validate([
            'file_surat_pernyataan_ttd' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        if ($permohonan->file_surat_pernyataan_ttd) {
            Storage::disk('public')->delete($permohonan->file_surat_pernyataan_ttd);
        }

        $path = $request->file('file_surat_pernyataan_ttd')->store('permohonan-andalalin/ttd', 'public');

        $permohonan->update([
            'file_surat_pernyataan_ttd' => $path,
            'catatan_verifikasi_ttd'    => null,
        ]);

        PermohonanAndalalinLog::create([
            'permohonan_andalalin_id' => $permohonan->id,
            'user_id'                 => Auth::id(),
            'aksi'                    => 'upload_ttd',
            'status_sebelum'          => $permohonan->status,
            'status_sesudah'          => $permohonan->status,
            'keterangan'              => 'Pemohon mengupload surat pernyataan yang sudah ditandatangani',
        ]);

        return redirect()->route('pemohon.permohonan-andalalin.show', $id)
            ->with('toast_success', 'Surat berhasil diupload, menunggu verifikasi Admin.');
    }
}
