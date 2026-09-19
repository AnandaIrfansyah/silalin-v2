<?php

namespace Database\Seeders;

use App\Models\MasterDokumenPersyaratan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDokumenSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Overlap (dipakai di Andalalin & Parkir)
            ['Surat Permohonan', 'surat_permohonan', 'semua', 1],
            ['PKKPR', 'pkkpr', 'semua', 2],
            ['Akta Tanah', 'akta_tanah', 'semua', 3],
            ['Site Plan', 'site_plan', 'semua', 4],
            ['Foto Lokasi', 'foto_lokasi', 'semua', 5],
            ['OSS KBLI', 'oss_kbli', 'semua', 6],

            // Khusus Andalalin
            ['Gambar Bangunan', 'gambar_bangunan', 'andalalin', 7],

            // Khusus Parkir
            ['Rekomendasi Andalalin', 'rekom_andalalin', 'parkir', 7],
        ];

        foreach ($data as [$nama, $kode, $jenis, $urutan]) {
            MasterDokumenPersyaratan::firstOrCreate(
                ['kode' => $kode],
                [
                    'nama_dokumen'     => $nama,
                    'jenis_permohonan' => $jenis,
                    'is_wajib'         => true,
                    'urutan'           => $urutan,
                ]
            );
        }
    }
}
