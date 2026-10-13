<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermohonanParkir;
use App\Models\PermohonanParkirLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermohonanParkirController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', '');
        $search = $request->get('search', '');

        $permohonan = PermohonanParkir::with(['user', 'company'])
            ->where('status', '!=', 'draft')
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('nama_lokasi', 'like', "%{$search}%")
                        ->orWhereHas('company', fn($c) => $c->where('nama_perusahaan', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10);

        return view('admin.permohonan-parkir.index', compact('permohonan', 'status', 'search'));
    }

    public function show($id)
    {
        $permohonan = PermohonanParkir::with(['user', 'company', 'dokumen', 'fasilitas', 'logs.user'])
            ->findOrFail($id);

        return view('admin.permohonan-parkir.show', compact('permohonan'));
    }

    public function verifikasiBerkas(Request $request, $id)
    {
        $permohonan = PermohonanParkir::findOrFail($id);

        if ($permohonan->status !== 'diajukan') {
            return back()->with('toast_error', 'Permohonan ini tidak dalam tahap verifikasi berkas.');
        }

        $request->validate([
            'aksi'    => ['required', 'in:setuju,revisi'],
            'catatan' => ['required_if:aksi,revisi', 'nullable', 'string', 'max:1000'],
        ]);

        if ($request->aksi === 'setuju') {
            $this->ubahStatus(
                $permohonan,
                'verifikasi_berkas_disetujui',
                'dijadwalkan_survey',
                ['catatan_verifikasi_berkas' => null],
                'Berkas disetujui, lanjut ke jadwal survey'
            );
        } else {
            $this->ubahStatus(
                $permohonan,
                'verifikasi_berkas_revisi',
                'revisi_berkas',
                ['catatan_verifikasi_berkas' => $request->catatan],
                $request->catatan
            );
        }

        return back()->with('toast_success', 'Verifikasi berkas berhasil diproses.');
    }

    public function jadwalSurvey(Request $request, $id)
    {
        $permohonan = PermohonanParkir::findOrFail($id);

        if ($permohonan->status !== 'dijadwalkan_survey') {
            return back()->with('toast_error', 'Permohonan ini tidak dalam tahap penjadwalan survey.');
        }

        $validated = $request->validate([
            'tanggal_survey' => ['required', 'date'],
            'jam_survey'     => ['required', 'date_format:H:i'],
        ]);

        $permohonan->update($validated);

        $this->catatLog(
            $permohonan,
            'jadwal_survey',
            $permohonan->status,
            $permohonan->status,
            "Survey dijadwalkan: {$validated['tanggal_survey']} pukul {$validated['jam_survey']}"
        );

        return back()->with('toast_success', 'Jadwal survey berhasil disimpan.');
    }

    public function uploadBeritaAcara(Request $request, $id)
    {
        $permohonan = PermohonanParkir::with('fasilitas')->findOrFail($id);

        if ($permohonan->status !== 'dijadwalkan_survey' || ! $permohonan->tanggal_survey) {
            return back()->with('toast_error', 'Jadwalkan survey terlebih dahulu sebelum mengupload berita acara.');
        }

        $request->validate([
            'file_berita_acara_survey' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'fasilitas'                => [$permohonan->fasilitas->isNotEmpty() ? 'required' : 'nullable', 'array'],
            'fasilitas.*'              => ['in:ada,tidak ada'],
        ]);

        DB::transaction(function () use ($request, $permohonan) {
            $koreksi = [];

            foreach ($permohonan->fasilitas as $item) {
                $hasil = $request->input("fasilitas.{$item->id}", $item->status);
                $item->update(['status_hasil_survey' => $hasil]);

                if ($hasil !== $item->status) {
                    $koreksi[] = "{$item->nama_fasilitas_snapshot} ({$item->status} menjadi {$hasil})";
                }
            }

            $keterangan = $koreksi
                ? 'Berita acara survey diupload. Koreksi fasilitas: ' . implode(', ', $koreksi)
                : 'Berita acara survey diupload. Hasil survey fasilitas sesuai input pemohon';

            $this->ubahStatus($permohonan, 'upload_berita_acara', 'dijadwalkan_paparan', [
                'file_berita_acara_survey' => $request->file('file_berita_acara_survey')
                    ->store('permohonan-parkir/berita-acara', 'public'),
            ], $keterangan);
        });

        return back()->with('toast_success', 'Berita acara dan hasil survey fasilitas berhasil disimpan.');
    }

    public function jadwalPaparan(Request $request, $id)
    {
        $permohonan = PermohonanParkir::findOrFail($id);

        if ($permohonan->status !== 'dijadwalkan_paparan') {
            return back()->with('toast_error', 'Permohonan ini tidak dalam tahap penjadwalan paparan.');
        }

        $validated = $request->validate([
            'tanggal_paparan' => ['required', 'date'],
            'jam_paparan'     => ['required', 'date_format:H:i'],
        ]);

        $permohonan->update($validated);

        $this->catatLog(
            $permohonan,
            'jadwal_paparan',
            $permohonan->status,
            $permohonan->status,
            "Paparan dijadwalkan: {$validated['tanggal_paparan']} pukul {$validated['jam_paparan']}"
        );

        return back()->with('toast_success', 'Jadwal paparan berhasil disimpan.');
    }

    public function uploadLaporanAnalisis(Request $request, $id)
    {
        $permohonan = PermohonanParkir::findOrFail($id);

        if ($permohonan->status !== 'dijadwalkan_paparan' || ! $permohonan->tanggal_paparan) {
            return back()->with('toast_error', 'Jadwalkan paparan terlebih dahulu sebelum mengupload laporan analisis.');
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
            ->store('permohonan-parkir/laporan-analisis', 'public');

        $this->ubahStatus(
            $permohonan,
            'upload_laporan_analisis',
            'menunggu_ttd',
            $validated,
            'Laporan analisis diupload, surat pernyataan siap diunduh pemohon'
        );

        return back()->with('toast_success', 'Laporan analisis berhasil diupload. Surat pernyataan siap diunduh pemohon.');
    }

    public function verifikasiTtd(Request $request, $id)
    {
        $permohonan = PermohonanParkir::findOrFail($id);

        if ($permohonan->status !== 'menunggu_ttd') {
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
            $this->ubahStatus($permohonan, 'rekomendasi_diterbitkan', 'selesai', [
                'catatan_verifikasi_ttd' => null,
                'file_surat_rekomendasi' => $request->file('file_surat_rekomendasi')
                    ->store('permohonan-parkir/rekomendasi', 'public'),
                'diterbitkan_at'         => now(),
            ], 'Surat rekomendasi parkir diterbitkan');
        } else {
            $this->ubahStatus(
                $permohonan,
                'verifikasi_ttd_revisi',
                'revisi_ttd',
                ['catatan_verifikasi_ttd' => $request->catatan],
                $request->catatan
            );
        }

        return back()->with('toast_success', 'Verifikasi tanda tangan berhasil diproses.');
    }

    private function ubahStatus(PermohonanParkir $permohonan, string $aksi, string $statusBaru, array $data = [], ?string $keterangan = null): void
    {
        $statusLama = $permohonan->status;

        $permohonan->update(array_merge($data, ['status' => $statusBaru]));

        $this->catatLog($permohonan, $aksi, $statusLama, $statusBaru, $keterangan);
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
