<?php

namespace Database\Seeders;

use App\Models\KriteriaAndalalin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KriteriaAndalalinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // 1. PUSAT KEGIATAN
            // a. Kegiatan perdagangan dan perbelanjaan
            ['Kegiatan Perdagangan dan Perbelanjaan', 'Luas Lantai Bangunan', 'tinggi', 3000, null, 'm2'],
            ['Kegiatan Perdagangan dan Perbelanjaan', 'Luas Lantai Bangunan', 'sedang', 1001, 3000, 'm2'],
            ['Kegiatan Perdagangan dan Perbelanjaan', 'Luas Lantai Bangunan', 'rendah', 500, 1000, 'm2'],

            // b. Kegiatan perkantoran
            ['Kegiatan Perkantoran', 'Luas Lantai Bangunan', 'tinggi', 10000, null, 'm2'],
            ['Kegiatan Perkantoran', 'Luas Lantai Bangunan', 'sedang', 4001, 10000, 'm2'],
            ['Kegiatan Perkantoran', 'Luas Lantai Bangunan', 'rendah', 1000, 4000, 'm2'],

            // c. Kegiatan Industri dan Pergudangan
            ['Industri', 'Luas Lantai Bangunan', 'tinggi', 10000, null, 'm2'],
            ['Industri', 'Luas Lantai Bangunan', 'sedang', 5001, 10000, 'm2'],
            ['Industri', 'Luas Lantai Bangunan', 'rendah', 2500, 5000, 'm2'],

            ['Pergudangan', 'Luas Lantai Bangunan', 'tinggi', 500000, null, 'm2'],
            ['Pergudangan', 'Luas Lantai Bangunan', 'sedang', 170001, 500000, 'm2'],
            ['Pergudangan', 'Luas Lantai Bangunan', 'rendah', 40000, 170000, 'm2'],

            // d. Kegiatan Pariwisata
            ['Kawasan Pariwisata', 'Wajib', 'tinggi', null, null, null],
            ['Tempat Wisata', 'Luas Lahan', 'tinggi', 10.0, null, 'hektar'],
            ['Tempat Wisata', 'Luas Lahan', 'sedang', 5.0, 10.0, 'hektar'],
            ['Tempat Wisata', 'Luas Lahan', 'rendah', 1.0, 5.0, 'hektar'],

            // e. Fasilitas Pendidikan
            ['Sekolah/Universitas', 'Jumlah Siswa', 'tinggi', 1500, null, 'siswa'],
            ['Sekolah/Universitas', 'Jumlah Siswa', 'sedang', 500, 1500, 'siswa'],

            // f. Fasilitas Pelayanan Umum
            ['Rumah Sakit', 'Jumlah Tempat Tidur', 'tinggi', 700, null, 'tempat tidur'],
            ['Rumah Sakit', 'Jumlah Tempat Tidur', 'sedang', 201, 700, 'tempat tidur'],
            ['Rumah Sakit', 'Jumlah Tempat Tidur', 'rendah', 75, 200, 'tempat tidur'],

            ['Bank', 'Luas Lantai Bangunan', 'tinggi', 3000, null, 'm2'],
            ['Bank', 'Luas Lantai Bangunan', 'sedang', 1001, 3000, 'm2'],
            ['Bank', 'Luas Lantai Bangunan', 'rendah', 500, 1000, 'm2'],

            // 2. PERUMAHAN DAN PERMUKIMAN
            ['Perumahan Sederhana', 'Jumlah Unit', 'tinggi', 1000, null, 'unit'],
            ['Perumahan Sederhana', 'Jumlah Unit', 'sedang', 401, 1000, 'unit'],
            ['Perumahan Sederhana', 'Jumlah Unit', 'rendah', 150, 400, 'unit'],

            ['Perumahan Menengah-atas/Townhouse/Cluster', 'Jumlah Unit', 'tinggi', 500, null, 'unit'],
            ['Perumahan Menengah-atas/Townhouse/Cluster', 'Jumlah Unit', 'sedang', 301, 500, 'unit'],
            ['Perumahan Menengah-atas/Townhouse/Cluster', 'Jumlah Unit', 'rendah', 100, 300, 'unit'],

            ['Rumah Susun Sederhana', 'Jumlah Unit', 'sedang', 800, null, 'unit'],
            ['Rumah Susun Sederhana', 'Jumlah Unit', 'rendah', 150, 800, 'unit'],

            ['Apartemen', 'Jumlah Unit', 'tinggi', 500, null, 'unit'],
            ['Apartemen', 'Jumlah Unit', 'sedang', 301, 500, 'unit'],
            ['Apartemen', 'Jumlah Unit', 'rendah', 50, 300, 'unit'],

            // 3. INFRASTRUKTUR
            ['Akses ke dan dari Jalan Tol', 'Wajib', 'tinggi', null, null, null],

            ['Pelabuhan Utama', 'Wajib', 'tinggi', null, null, null],
            ['Pelabuhan Pengumpul', 'Wajib', 'tinggi', null, null, null],
            ['Pelabuhan Pengumpan Regional', 'Wajib', 'sedang', null, null, null],
            ['Pelabuhan Pengumpan Lokal', 'Wajib', 'sedang', null, null, null],

            ['Pelabuhan Khusus', 'Luas Lahan', 'tinggi', 100000, null, 'm2'],
            ['Pelabuhan Khusus', 'Luas Lahan', 'sedang', 50001, 100000, 'm2'],
            ['Pelabuhan Khusus', 'Luas Lahan', 'rendah', null, 50000, 'm2'],

            ['Pelabuhan Sungai, Danau dan Penyeberangan', 'Lintas Provinsi dan/atau Antarnegara', 'tinggi', null, null, null],
            ['Pelabuhan Sungai, Danau dan Penyeberangan', 'Lintas Kabupaten/Kota', 'sedang', null, null, null],
            ['Pelabuhan Sungai, Danau dan Penyeberangan', 'Lintas dalam Kabupaten/Kota', 'rendah', null, null, null],

            ['Bandar Udara Pengumpul Skala Pelayanan Primer', 'Jumlah Penumpang per Tahun', 'tinggi', 5000000, null, 'orang/tahun'],
            ['Bandar Udara Pengumpul Skala Pelayanan Sekunder', 'Jumlah Penumpang per Tahun', 'tinggi', 1000000, 5000000, 'orang/tahun'],
            ['Bandar Udara Pengumpul Skala Pelayanan Tersier', 'Jumlah Penumpang per Tahun', 'sedang', 500000, 1000000, 'orang/tahun'],
            ['Bandar Udara Pengumpan (Spoke)', 'Wajib', 'rendah', null, null, null],

            ['Terminal Penumpang Tipe A', 'Wajib', 'tinggi', null, null, null],
            ['Terminal Penumpang Tipe B', 'Wajib', 'sedang', null, null, null],
            ['Terminal Penumpang Tipe C', 'Wajib', 'rendah', null, null, null],
            ['Terminal Angkutan Barang', 'Wajib', 'tinggi', null, null, null],
            ['Terminal Peti Kemas', 'Wajib', 'tinggi', null, null, null],

            ['Stasiun Kereta Api Kelas Besar', 'Wajib', 'tinggi', null, null, null],
            ['Stasiun Kereta Api Kelas Sedang', 'Wajib', 'sedang', null, null, null],
            ['Stasiun Kereta Api Kelas Kecil', 'Wajib', 'rendah', null, null, null],

            ['Pool Kendaraan', 'Wajib', 'sedang', null, null, null],

            ['Fasilitas Parkir untuk Umum', 'Jumlah SRP', 'tinggi', 300, null, 'SRP'],
            ['Fasilitas Parkir untuk Umum', 'Jumlah SRP', 'sedang', 100, 300, 'SRP'],

            // 4. PUSAT KEGIATAN/PERMUKIMAN/INFRASTRUKTUR LAINNYA
            ['Stasiun Pengisian Bahan Bakar', 'Jumlah Dispenser', 'tinggi', 6, null, 'dispenser'],
            ['Stasiun Pengisian Bahan Bakar', 'Jumlah Dispenser', 'sedang', 3, 6, 'dispenser'],
            ['Stasiun Pengisian Bahan Bakar', 'Jumlah Dispenser', 'rendah', 1, 2, 'dispenser'],

            ['Hotel', 'Jumlah Kamar', 'tinggi', 300, null, 'kamar'],
            ['Hotel', 'Jumlah Kamar', 'sedang', 121, 300, 'kamar'],
            ['Hotel', 'Jumlah Kamar', 'rendah', 75, 120, 'kamar'],

            ['Gedung Pertemuan', 'Luas Lantai Bangunan', 'tinggi', 3000, null, 'm2'],
            ['Gedung Pertemuan', 'Luas Lantai Bangunan', 'sedang', 1000, 3000, 'm2'],

            ['Restaurant', 'Jumlah Tempat Duduk', 'sedang', 300, null, 'tempat duduk'],
            ['Restaurant', 'Jumlah Tempat Duduk', 'rendah', 100, 300, 'tempat duduk'],

            ['Fasilitas Olahraga (Indoor/Outdoor)', 'Luas Lantai Bangunan', 'tinggi', 20000, null, 'm2'],
            ['Fasilitas Olahraga (Indoor/Outdoor)', 'Luas Lantai Bangunan', 'sedang', 5001, 20000, 'm2'],
            ['Fasilitas Olahraga (Indoor/Outdoor)', 'Luas Lantai Bangunan', 'rendah', 1000, 5000, 'm2'],

            ['Kawasan TOD (Transit Oriented Development)', 'Wajib', 'tinggi', null, null, null],

            ['Asrama', 'Jumlah Kamar', 'sedang', 700, null, 'kamar'],
            ['Asrama', 'Jumlah Kamar', 'rendah', 150, 700, 'kamar'],

            ['Ruko', 'Luas Lantai Bangunan', 'sedang', 5000, null, 'm2'],
            ['Ruko', 'Luas Lantai Bangunan', 'rendah', 2000, 5000, 'm2'],

            ['Jalan Layang (Flyover)', 'Wajib', 'tinggi', null, null, null],
            ['Lintas Bawah (Underpass)', 'Wajib', 'tinggi', null, null, null],
            ['Terowongan (Tunnel)', 'Wajib', 'tinggi', null, null, null],
            ['Jembatan', 'Wajib', 'tinggi', null, null, null],

            ['Rest Area Tipe A', 'Wajib', 'tinggi', null, null, null],
            ['Rest Area Tipe B', 'Wajib', 'sedang', null, null, null],
            ['Rest Area Tipe C', 'Wajib', 'rendah', null, null, null],

            // Kriteria berbasis jumlah perjalanan (kendaraan) yang ditimbulkan
            ['Kegiatan Penimbul Bangkitan Perjalanan', 'Perjalanan Baru pada Jam Padat', 'tinggi', 1500, null, 'kendaraan'],
            ['Kegiatan Penimbul Bangkitan Perjalanan', 'Rata-rata Perjalanan Baru per Hari', 'tinggi', 10000, null, 'kendaraan/hari'],

            ['Kegiatan Penimbul Bangkitan Perjalanan', 'Perjalanan Baru pada Jam Padat', 'sedang', 500, null, 'kendaraan'],
            ['Kegiatan Penimbul Bangkitan Perjalanan', 'Rata-rata Perjalanan Baru per Hari', 'sedang', 3000, 4000, 'kendaraan/hari'],

            ['Kegiatan Penimbul Bangkitan Perjalanan', 'Perjalanan Baru pada Jam Padat', 'rendah', 100, null, 'kendaraan'],
            ['Kegiatan Penimbul Bangkitan Perjalanan', 'Rata-rata Perjalanan Baru per Hari', 'rendah', 700, null, 'kendaraan/hari'],
        ];

        foreach ($data as [$jenisKegiatan, $parameter, $kategori, $batasMin, $batasMax, $satuan]) {
            KriteriaAndalalin::create([
                'jenis_kegiatan' => $jenisKegiatan,
                'parameter'      => $parameter,
                'kategori'       => $kategori,
                'batas_min'      => $batasMin,
                'batas_max'      => $batasMax,
                'satuan'         => $satuan,
            ]);
        }
    }
}
