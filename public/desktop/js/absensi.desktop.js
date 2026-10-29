const BASE_URL = 'https://absensi.ptpn4.co.id/';

$(document).ready(function() {
	loadAvatar();	
});

function getListBagian(kode_unit = null, callback) {
	$.ajax({
		url: BASE_URL + 'api/simkar/list-bagian',
		method: 'POST',
		data: {
			unit: kode_unit
		},
		success: callback,
	});
}

function getListKaryawan(nikNama = null, kode_unit = null, kode_bagian = null, kode_jabatan = null, callback) {
	$.ajax({
		url: BASE_URL + 'api/simkar/list-karyawan-simple',
		method: 'POST',
		data: {
			unit: kode_unit,
			bagian: kode_bagian,
			jabatan: kode_jabatan,
			nikNama: nikNama,
		},
		success: callback,
	});
}

function jsGetCuti(jenis_absen, nik, tgl_mulai, tgl_selesai, callback)
{
	if (jenis_absen !=='' && nik !== '' && tgl_mulai !=='' && tgl_selesai !=='')
	{
		$.ajax({
			url: BASE_URL + 'api/absensi/get-cuti',
			method: 'POST',
			data: {
				jenis 		: jenis_absen,
				nik 		: nik,
				tgl_mulai 	: tgl_mulai,
				tgl_selesai : tgl_selesai,
			},
			success: callback,
		});
	}
}

function loadAvatar() {
	setTimeout(function(){
		$.each($('[data-load="avatar"]'), function(index, element) {	
			var _class = $(element).attr('class');
			var alt = 'User avatar';

			var img = $("<img class='"+_class+"' alt='"+alt+"' src='https:\/\/ihcs.ptpn4.co.id\/avatar' />");

			var parent = $(element).parent();
			$(element).remove();
			if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
				parent.append($("<img class='user-avatar' alt='Gagal memuat avatar' />"));
			} else {
				parent.append(img);
			}
		})
	}, 500);
}

function toDate(indoDate) {
	var number = indoDate.split('-');
	return number[2] + '-' + number[1] + '-' + number[0];
}
