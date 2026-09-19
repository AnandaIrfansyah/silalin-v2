<?php

namespace Database\Seeders;

use App\Models\MasterFasilitas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterFasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['Marka Parkir', 'marka_parkir', 1],
            ['Rambu Lalu Lintas', 'rambu_lalu_lintas', 2],
            ['Rambu Jalan Masuk/Keluar', 'rambu_jalan_masuk_keluar', 3],
            ['Jalur Pejalan Kaki', 'jalur_pejalan_kaki', 4],
            ['Fasilitas Pemadam Kebakaran', 'fasilitas_pemadam_kebakaran', 5],
            ['Fasilitas Pengamanan', 'fasilitas_pengamanan', 6],
            ['Fasilitas Keselamatan', 'fasilitas_keselamatan', 7],
        ];

        foreach ($data as [$nama, $kode, $urutan]) {
            MasterFasilitas::firstOrCreate(
                ['kode' => $kode],
                ['nama_fasilitas' => $nama, 'urutan' => $urutan]
            );
        }
    }
}
