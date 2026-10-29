<?php

use Illuminate\Database\Seeder;
use App\Models\TrJenisAbsen;

class TrJenisAbsenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TrJenisAbsen::insert([
            [ 'kode' => 'CB', 'nama' => 'Cuti Bersama', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'CH', 'nama' => 'Cuti Ibadah Keagamaan', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'CL', 'nama' => 'Cuti Diluar Tanggungan Perusahaan', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'CP', 'nama' => 'Cuti Panjang', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'CT', 'nama' => 'Cuti Tahunan', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'DL', 'nama' => 'Dinas Luar', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'H1', 'nama' => 'Cuti Haid', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'H2', 'nama' => 'Cuti Melahirkan', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'H3', 'nama' => 'Cuti Keguguran', 'created_by' => 'Seeder', 'created_at' => now()],
            [ 'kode' => 'K', 'nama' => 'Kerja', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'KK', 'nama' => 'Kerja (Spesial)', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'L', 'nama' => 'Minggu/Hari Libur Nasional', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'M', 'nama' => 'Mangkir', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'M1', 'nama' => 'Mangkir (Ditahan Alat Negara/Mogok Kerja)', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'P1', 'nama' => 'Izin Tidak Bekerja dan Tidak Mendapat Gaji', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'P2', 'nama' => 'Izin Tidak Bekerja Karena Mogok Yang Sah & Mendapat Gaji', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'P3', 'nama' => 'Izin Meninggalkan Pekerjaan Yang Menjadi Tanggungan Perusahaan & Mendapat Gaji', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'P4', 'nama' => 'Izin Tidak Bekerja & Mendapat Gaji Sesuai PKB mengenai Izin Meninggalkan Pekerjaan', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'S', 'nama' => 'Sakit Rawat Inap', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'S1', 'nama' => 'Sakit Rawat Jalan (Maks.2 Hari)', 'created_by' => 'Seeder', 'created_at' => now()],
            [ 'kode' => 'S2', 'nama' => 'Sakit Mendadak (Emergency)', 'created_by' => 'Seeder', 'created_at' => now()],
            [ 'kode' => 'N/A', 'nama' => 'No Available', 'created_by' => 'Seeder', 'created_at' => now()],
        	[ 'kode' => 'TD', 'nama' => 'Tidak kerja, Dibayar', 'created_by' => 'Seeder', 'created_at' => now()],
        ]);
    }
}
