<?php

use Illuminate\Database\Seeder;

class FnGetListDateRange extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('fn_get_list_date_range') IS NOT NULL) DROP FUNCTION fn_get_list_date_range;");
        DB::statement("CREATE FUNCTION fn_get_list_date_range (@start DATE, @end DATE)
		RETURNS @listDays TABLE (tgl	date)
		AS
		BEGIN
			
			;WITH n AS 
			(
				SELECT TOP (DATEDIFF(DAY, @start, @end)) 
					n = ROW_NUMBER() OVER (ORDER BY [object_id])
				FROM sys.all_objects
			)
			INSERT INTO @listDays
			SELECT DATEADD(DAY, n-1, @start)
			FROM n;
			RETURN

		END;");
    }
}
