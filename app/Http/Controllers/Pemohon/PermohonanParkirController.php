<?php

namespace App\Http\Controllers\Pemohon;

use App\Http\Controllers\Controller;
use App\Models\MasterDokumenPersyaratan;
use App\Models\MasterFasilitas;
use App\Models\PermohonanParkir;
use App\Models\PermohonanParkirLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class PermohonanParkirController extends Controller
{
    public function index()
    {
        $permohonan = PermohonanParkir::where('user_id', Auth::id())->latest()->paginate(10);

        return view('pemohon.permohonan-parkir.index', compact('permohonan'));
    }

    public function create()
    {
        if (! $this->companyTerverifikasi()) {
            return redirect()->route('pemohon.company.index')
                ->with('toast_error', 'Profil perusahaan harus terverifikasi sebelum mengajukan Rekomendasi Parkir.');
        }

        $dokumenPersyaratan = MasterDokumenPersyaratan::untukJenis('parkir')->get();
        $daftarFasilitas    = MasterFasilitas::where('is_active', true)->orderBy('urutan')->get();

        return view('pemohon.permohonan-parkir.create', compact('dokumenPersyaratan', 'daftarFasilitas'));
    }

    public function store(Request $request)
    {
        abort_unless($this->companyTerverifikasi(), 403);

        $dokumenAktif = MasterDokumenPersyaratan::untukJenis('parkir')->get();

        $rules = [
            'nama_lokasi'   => ['required', 'string', 'max:255'],
            'jenis_parkir'  => ['required', 'in:umum,khusus'],
            'luas_area'     => ['required', 'numeric', 'min:0.01'],
            'alamat_lokasi' => ['required', 'string'],
            'fasilitas.*'   => ['in:ada,tidak ada'],
        ];
        $attributes = [];

        foreach ($dokumenAktif as $dok) {
            $rules["dokumen.{$dok->kode}"] = [
                $dok->is_wajib ? 'required' : 'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ];
            $attributes["dokumen.{$dok->kode}"] = $dok->nama_dokumen;
        }

        $validated = $request->validate($rules, [], $attributes);

        DB::transaction(function () use ($request, $validated, $dokumenAktif) {
            $permohonan = PermohonanParkir::create([
                'user_id'       => Auth::id(),
                'company_id'    => Auth::user()->company->id,
                'nama_lokasi'   => $validated['nama_lokasi'],
                'jenis_parkir'  => $validated['jenis_parkir'],
                'luas_area'     => $validated['luas_area'],
                'alamat_lokasi' => $validated['alamat_lokasi'],
                'status'        => 'diajukan',
            ]);

            foreach ($dokumenAktif as $dok) {
                if ($request->hasFile("dokumen.{$dok->kode}")) {
                    $permohonan->dokumen()->create([
                        'master_dokumen_id'     => $dok->id,
                        'nama_dokumen_snapshot' => $dok->nama_dokumen,
                        'file_path'             => $request->file("dokumen.{$dok->kode}")
                            ->store('permohonan-parkir/dokumen', 'public'),
                        'uploaded_at'           => now(),
                    ]);
                }
            }

            foreach (MasterFasilitas::where('is_active', true)->get() as $fasilitas) {
                $permohonan->fasilitas()->create([
                    'master_fasilitas_id'     => $fasilitas->id,
                    'nama_fasilitas_snapshot' => $fasilitas->nama_fasilitas,
                    'status'                  => $request->input("fasilitas.{$fasilitas->id}", 'tidak ada'),
                ]);
            }

            $this->catatLog($permohonan, 'submit', 'draft', 'diajukan');
        });

        return response()->json([
            'status'   => true,
            'message'  => 'Permohonan Rekomendasi Parkir berhasil diajukan.',
            'redirect' => route('pemohon.permohonan-parkir.index'),
        ]);
    }

    public function show($id)
    {
        $permohonan = $this->cariMilikSaya($id, ['dokumen', 'fasilitas', 'logs']);
        $dokumenAktif = MasterDokumenPersyaratan::untukJenis('parkir')->get();

        return view('pemohon.permohonan-parkir.show', compact('permohonan', 'dokumenAktif'));
    }

    public function revisiBerkas(Request $request, $id)
    {
        $permohonan = $this->cariMilikSaya($id);

        if ($permohonan->status !== 'revisi_berkas') {
            return back()->with('toast_error', 'Permohonan ini tidak dalam tahap revisi berkas.');
        }

        $dokumenAktif = MasterDokumenPersyaratan::untukJenis('parkir')->get();

        $rules = [];
        $attributes = [];
        foreach ($dokumenAktif as $dok) {
            $rules["dokumen.{$dok->kode}"] = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
            $attributes["dokumen.{$dok->kode}"] = $dok->nama_dokumen;
        }
        $request->validate($rules, [], $attributes);

        if (empty(array_filter($request->file('dokumen', [])))) {
            return back()->with('toast_error', 'Pilih minimal satu dokumen untuk diupload ulang.');
        }

        DB::transaction(function () use ($request, $permohonan, $dokumenAktif) {
            foreach ($dokumenAktif as $dok) {
                if (! $request->hasFile("dokumen.{$dok->kode}")) {
                    continue;
                }

                $path = $request->file("dokumen.{$dok->kode}")->store('permohonan-parkir/dokumen', 'public');
                $existing = $permohonan->dokumen()->where('master_dokumen_id', $dok->id)->first();

                if ($existing) {
                    Storage::disk('public')->delete($existing->file_path);
                    $existing->update(['file_path' => $path, 'uploaded_at' => now()]);
                    continue;
                }

                $permohonan->dokumen()->create([
                    'master_dokumen_id'     => $dok->id,
                    'nama_dokumen_snapshot' => $dok->nama_dokumen,
                    'file_path'             => $path,
                    'uploaded_at'           => now(),
                ]);
            }

            $permohonan->update(['status' => 'diajukan']);

            $this->catatLog(
                $permohonan,
                'revisi_berkas_diupload',
                'revisi_berkas',
                'diajukan',
                'Pemohon mengupload ulang dokumen yang direvisi'
            );
        });

        return redirect()->route('pemohon.permohonan-parkir.show', $id)
            ->with('toast_success', 'Dokumen berhasil diupload ulang, menunggu verifikasi Admin.');
    }

    public function downloadSuratPernyataan($id)
    {
        $permohonan = $this->cariMilikSaya($id, ['company']);

        abort_unless(in_array($permohonan->status, ['menunggu_ttd', 'revisi_ttd']), 404);

        $templatePath = resource_path('templates/surat_pernyataan_kesanggupan.docx');
        abort_unless(file_exists($templatePath), 404, 'Template surat pernyataan belum tersedia.');

        $tanggalDokumen = $permohonan->tanggal_dokumen;
        $company = $permohonan->company;

        $template = new TemplateProcessor($templatePath);
        $template->setValue('nama_penanggung_jawab', $company->nama_penanggung_jawab);
        $template->setValue('jabatan_penanggung_jawab', $company->jabatan_penanggung_jawab);
        $template->setValue('nama_perusahaan', $company->nama_perusahaan);
        $template->setValue('alamat_perusahaan', $company->alamat_perusahaan);
        $template->setValue('jenis_kegiatan', sprintf(
            'Fasilitas Parkir %s (%s)',
            ucfirst($permohonan->jenis_parkir),
            $permohonan->nama_lokasi
        ));
        $template->setValue('alamat_proyek', $permohonan->alamat_lokasi);
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

        $fileName = 'Surat-Pernyataan-Kesanggupan-Parkir-' . $permohonan->id . '.docx';
        $tempPath = $tempDir . '/' . $fileName;
        $template->saveAs($tempPath);

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    public function uploadTtd(Request $request, $id)
    {
        $permohonan = $this->cariMilikSaya($id);

        if (! in_array($permohonan->status, ['menunggu_ttd', 'revisi_ttd'])) {
            return back()->with('toast_error', 'Permohonan ini tidak dalam tahap upload tanda tangan.');
        }

        $request->validate([
            'file_surat_pernyataan_ttd' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        if ($permohonan->file_surat_pernyataan_ttd) {
            Storage::disk('public')->delete($permohonan->file_surat_pernyataan_ttd);
        }

        $statusSebelum = $permohonan->status;

        $permohonan->update([
            'file_surat_pernyataan_ttd' => $request->file('file_surat_pernyataan_ttd')
                ->store('permohonan-parkir/ttd', 'public'),
            'catatan_verifikasi_ttd'    => null,
            'status'                    => 'menunggu_ttd',
        ]);

        $this->catatLog(
            $permohonan,
            'upload_ttd',
            $statusSebelum,
            'menunggu_ttd',
            'Pemohon mengupload surat pernyataan yang sudah ditandatangani'
        );

        return redirect()->route('pemohon.permohonan-parkir.show', $id)
            ->with('toast_success', 'Surat berhasil diupload, menunggu verifikasi Admin.');
    }

    private function cariMilikSaya($id, array $with = []): PermohonanParkir
    {
        return PermohonanParkir::with($with)->where('user_id', Auth::id())->findOrFail($id);
    }

    private function companyTerverifikasi(): bool
    {
        return Auth::user()->company?->status === 'verified';
    }

    private function catatLog(PermohonanParkir $permohonan, string $aksi, ?string $sebelum, string $sesudah, ?string $keterangan = null): void
    {
        PermohonanParkirLog::create([
            'permohonan_parkir_id' => $permohonan->id,
            'user_id'              => Auth::id(),
            'aksi'                 => $aksi,
            'status_sebelum'       => $sebelum,
            'status_sesudah'       => $sesudah,
            'keterangan'           => $keterangan,
        ]);
    }
}
