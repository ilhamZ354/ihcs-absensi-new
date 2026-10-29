<?php

use Illuminate\Database\Seeder;

class SpBackupDb extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement("IF (OBJECT_ID('sp_backup_db') IS NOT NULL) DROP PROCEDURE sp_backup_db");    	
        DB::statement("CREATE PROCEDURE sp_backup_db
            @createdBy varchar(50)
        AS BEGIN
            SET NOCOUNT ON

            declare @tgl datetime = getdate()
            , @fileName varchar(200)
            , @dbName varchar(200)

            select @dbName = DB_NAME() 
            set @fileName = 'D:\DB_BACKUP\ABSENSI\AUTO_BACKUP_BY_'+ upper(@createdBy) +'_'+ upper(@dbName) +'_' + replace(convert(varchar(19), @tgl, 120), ':', '.') + '.BAK'
            BACKUP DATABASE @dbName TO DISK = @fileName
            
            select @fileName as filepath;
        END;");
    }
}
