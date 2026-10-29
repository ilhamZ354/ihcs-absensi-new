<?php

use Illuminate\Database\Seeder;

class FnGetJmlHKBulananUnit extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("IF (OBJECT_ID('fn_get_jml_hk_bulanan_unit') IS NOT NULL) DROP FUNCTION fn_get_jml_hk_bulanan_unit;");      
        DB::statement("CREATE FUNCTION [dbo].[fn_get_jml_hk_bulanan_unit] ( 
			@tahun smallint
			, @bulan smallint
			, @unit varchar(5)
		)
		RETURNS smallint
		AS
		BEGIN
			declare @s date
			, @e date
			, @result smallint
			
			set @s = convert(varchar(4), @tahun) + convert(varchar(2), @bulan) + '01'
			set @e = dateadd(month, 1, @s)

			
			if(@unit = '00')
				select @result = (select count(tgl) from dbo.fn_get_list_date_range(@s, @e) where datename(dw, tgl) not in ('Saturday')) - (select count(*) from tr_libur_cuti_bersamas where tgl between @s and @e)
			else 
				select @result = (select count(tgl) from dbo.fn_get_list_date_range(@s, @e)) - (select count(*) from tr_libur_cuti_bersamas where tgl between @s and @e)

			return (select @result)
		END;");
    }
}
