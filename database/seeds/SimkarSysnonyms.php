<?php

use Illuminate\Database\Seeder;

class SimkarSysnonyms extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("IF (OBJECT_ID('karyawan_ptpn4') IS NOT NULL) DROP SYNONYM karyawan_ptpn4;
            CREATE SYNONYM [dbo].[karyawan_ptpn4] FOR [10.10.5.187].[SIMKAR].[dbo].karyawan_ptpn4;");

        DB::statement("IF (OBJECT_ID('tm_karyawan_nonptpn4') IS NOT NULL) DROP SYNONYM tm_karyawan_nonptpn4;
            CREATE SYNONYM [dbo].[tm_karyawan_nonptpn4] FOR [10.10.5.187].[SIMKAR].[dbo].tm_karyawan_nonptpn4;");

        DB::statement("IF (OBJECT_ID('tm_bagian') IS NOT NULL) DROP SYNONYM tm_bagian;
            CREATE SYNONYM [dbo].[tm_bagian] FOR [10.10.5.187].[SIMKAR].[dbo].tm_bagian;");

        DB::statement("IF (OBJECT_ID('tm_jabatan') IS NOT NULL) DROP SYNONYM tm_jabatan;
            CREATE SYNONYM [dbo].[tm_jabatan] FOR [10.10.5.187].[SIMKAR].[dbo].tm_jabatan;");

        DB::statement("IF (OBJECT_ID('unitbagianmaster') IS NOT NULL) DROP SYNONYM unitbagianmaster;
            CREATE SYNONYM [dbo].[unitbagianmaster] FOR [10.10.5.187].[SIMKAR].[dbo].unitbagianmaster;");
        DB::statement("IF (OBJECT_ID('t_history_karyawan_bulanan') IS NOT NULL) DROP SYNONYM t_history_karyawan_bulanan;
            CREATE SYNONYM [dbo].[t_history_karyawan_bulanan] FOR [10.10.5.187].[SIMKAR].[dbo].T_HISTORY_KARYAWAN_BULANAN;");
    }
}
