<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermohonanAndalalin;
use App\Models\PermohonanAndalalinLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermohonanAndalalinController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', '');
        $search = $request->get('search', '');

        $permohonan = PermohonanAndalalin::with(['user', 'company'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, function ($q) use ($search) {
                $q->whereHas('company', fn($qq) => $qq->where('nama_perusahaan', 'like', "%{$search}%"))
                    ->orWhere('jenis_kegiatan_snapshot', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.permohonan-andalalin.index', compact('permohonan', 'status', 'search'));
    }

    public function show($id)
    {
        $permohonan = PermohonanAndalalin::with(['user', 'company', 'dokumen.masterDokumen', 'logs.user'])
            ->findOrFail($id);

        return view('admin.permohonan-andalalin.show', compact('permohonan'));
    }

    public function verifikasiBerkas(Request $request, $id)
    {
        $permohonan = PermohonanAndalalin::findOrFail($id);

        if ($permohonan->status !== 'diajukan') {
            return back()->with('toast_error', 'Permohonan ini tidak dalam tahap verifikasi berkas.');
        }

        $request->validate([
            'aksi'     => ['required', 'in:setuju,revisi'],
            'catatan'  => ['required_if:aksi,revisi', 'nullable', 'string', 'max:1000'],
        ]);

        $statusBaru = $request->aksi === 'setuju' ? 'dijadwalkan_survey' : 'revisi_berkas';

        $this->ubahStatus($permohonan, $statusBaru, [
            'catatan_verifikasi_berkas' => $request->catatan,
        ], $request->aksi === 'setuju' ? 'Berkas disetujui, lanjut ke jadwal survey' : $request->catatan);

        return back()->with('toast_success', 'Verifikasi berkas berhasil diproses.');
    }

    public function jadwalSurvey(Request $request, $id)
    {
        $permohonan = PermohonanAndalalin::findOrFail($id);

        if ($permohonan->status !== 'dijadwalkan_survey') {
            return back()->with('toast_error', 'Permohonan ini tidak dalam tahap penjadwalan survey.');
        }

        $validated = $request->validate([
            'tanggal_survey' => ['required', 'date'],
            'jam_survey'     => ['required'],
        ]);

        $permohonan->update($validated);

        PermohonanAndalalinLog::create([
            'permohonan_andalalin_id' => $permohonan->id,
            'user_id'                 => Auth::id(),
            'aksi'                    => 'jadwal_survey',
            'status_sebelum'          => $permohonan->status,
            'status_sesudah'          => $permohonan->status,
            'keterangan'              => "Survey dijadwalkan: {$validated['tanggal_survey']} {$validated['jam_survey']}",
        ]);

        return back()->with('toast_success', 'Jadwal survey berhasil disimpan.');
    }

    public function uploadBeritaAcara(Request $request, $id)
    {
        $permohonan = PermohonanAndalalin::findOrFail($id);

        if ($permohonan->status !== 'dijadwalkan_survey') {
            return back()->with('toast_error', 'Permohonan ini tidak dalam tahap survey.');
        }

        $request->validate([
            'file_berita_acara_survey' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $path = $request->file('file_berita_acara_survey')->store('permohonan-andalalin/berita-acara', 'public');

        // Kategori rendah lompat langsung ke jadwal paparan; sedang/tinggi wajib dokumen andalalin dulu
        $statusBaru = $permohonan->wajibDokumenAndalalin() ? 'menunggu_dokumen_andalalin' : 'dijadwalkan_paparan';

        $this->ubahStatus($permohonan, $statusBaru, [
            'file_berita_acara_survey' => $path,
        ], 'Berita acara survey diupload');

        return back()->with('toast_success', 'Berita acara survey berhasil diupload.');
    }

    public function verifikasiDokumenAndalalin(Request $request, $id)
    {
        $permohonan = PermohonanAndalalin::findOrFail($id);

        if (! in_array($permohonan->status, ['menunggu_dokumen_andalalin', 'revisi_dokumen_andalalin'])) {
            return back()->with('toast_error', 'Permohonan ini tidak dalam tahap verifikasi dokumen andalalin.');
        }

        if (! $permohonan->file_dokumen_andalalin) {
            return back()->with('toast_error', 'Pemohon belum mengupload dokumen andalalin.');
        }

        $request->validate([
            'aksi'    => ['required', 'in:setuju,revisi'],
            'catatan' => ['required_if:aksi,revisi', 'nullable', 'string', 'max:1000'],
        ]);

        $statusBaru = $request->aksi === 'setuju' ? 'dijadwalkan_paparan' : 'revisi_dokumen_andalalin';

        $this->ubahStatus($permohonan, $statusBaru, [
            'catatan_verifikasi_dokumen_andalalin' => $request->catatan,
        ], $request->aksi === 'setuju' ? 'Dokumen andalalin disetujui' : $request->catatan);

        return back()->with('toast_success', 'Verifikasi dokumen andalalin berhasil diproses.');
    }

    public function jadwalPaparan(Request $request, $id)
    {
        $permohonan = PermohonanAndalalin::findOrFail($id);

        if ($permohonan->status !== 'dijadwalkan_paparan') {
            return back()->with('toast_error', 'Permohonan ini tidak dalam tahap penjadwalan paparan.');
        }

        $validated = $request->validate([
            'tanggal_paparan' => ['required', 'date'],
            'jam_paparan'     => ['required'],
        ]);

        $permohonan->update($validated);

        PermohonanAndalalinLog::create([
            'permohonan_andalalin_id' => $permohonan->id,
            'user_id'                 => Auth::id(),
            'aksi'                    => 'jadwal_paparan',
            'status_sebelum'          => $permohonan->status,
            'status_sesudah'          => $permohonan->status,
            'keterangan'              => "Paparan dijadwalkan: {$validated['tanggal_paparan']} {$validated['jam_paparan']}",
        ]);

        return back()->with('toast_success', 'Jadwal paparan berhasil disimpan.');
    }

    public function uploadLaporanAnalisis(Request $request, $id)
    {
        $permohonan = PermohonanAndalalin::findOrFail($id);

        if ($permohonan->status !== 'dijadwalkan_paparan') {
            return back()->with('toast_error', 'Permohonan ini tidak dalam tahap paparan.');
        }

        $validated = $request->validate([
            'file_laporan_analisis' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'jenis_sifat_naskah'    => ['required', 'string', 'max:255'],
            'nomor_klasifikasi'     => ['required', 'string', 'max:255'],
            'kode_bidang'           => ['required', 'string', 'max:255'],
            'nomor_urut_dokumen'    => ['required', 'string', 'max:255'],
            'tanggal_dokumen'       => ['required', 'date'],
        ]);

        $validated['file_laporan_analisis'] = $request->file('file_laporan_analisis')
            ->store('permohonan-andalalin/laporan-analisis', 'public');

        $this->ubahStatus($permohonan, 'menunggu_ttd', $validated, 'Laporan analisis diupload, surat pernyataan kesanggupan digenerate');

        return back()->with('toast_success', 'Laporan analisis berhasil diupload. Surat pernyataan siap diunduh pemohon.');
    }

    public function verifikasiTtd(Request $request, $id)
    {
        $permohonan = PermohonanAndalalin::findOrFail($id);

        if (! in_array($permohonan->status, ['menunggu_ttd', 'revisi_ttd'])) {
            return back()->with('toast_error', 'Permohonan ini tidak dalam tahap verifikasi tanda tangan.');
        }

        if (! $permohonan->file_surat_pernyataan_ttd) {
            return back()->with('toast_error', 'Pemohon belum mengupload surat yang sudah ditandatangani.');
        }

        $request->validate([
            'aksi'                   => ['required', 'in:setuju,revisi'],
            'catatan'                => ['required_if:aksi,revisi', 'nullable', 'string', 'max:1000'],
            'file_surat_rekomendasi' => ['required_if:aksi,setuju', 'nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        if ($request->aksi === 'setuju') {
            $path = $request->file('file_surat_rekomendasi')->store('permohonan-andalalin/rekomendasi', 'public');

            $this->ubahStatus($permohonan, 'selesai', [
                'catatan_verifikasi_ttd' => null,
                'file_surat_rekomendasi' => $path,
                'diterbitkan_at'         => now(),
            ], 'Surat rekomendasi andalalin diterbitkan');
        } else {
            $this->ubahStatus($permohonan, 'revisi_ttd', [
                'catatan_verifikasi_ttd' => $request->catatan,
            ], $request->catatan);
        }

        return back()->with('toast_success', 'Verifikasi tanda tangan berhasil diproses.');
    }

    private function ubahStatus(PermohonanAndalalin $permohonan, string $statusBaru, array $dataTambahan = [], ?string $keterangan = null): void
    {
        $statusLama = $permohonan->status;

        $permohonan->update(array_merge($dataTambahan, ['status' => $statusBaru]));

        PermohonanAndalalinLog::create([
            'permohonan_andalalin_id' => $permohonan->id,
            'user_id'                 => Auth::id(),
            'aksi'                    => $statusBaru,
            'status_sebelum'          => $statusLama,
            'status_sesudah'          => $statusBaru,
            'keterangan'              => $keterangan,
        ]);
    }
}
