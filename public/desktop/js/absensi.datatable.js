var Datatable = function() {
	moment.locale('id');
	return {
		init: function(options) {
			function toDate(indoDate) {
				// var number = indoDate.split('-');
				// return number[2] + '-' + number[1] + '-' + number[0];
				return moment(indoDate).format('yyyy');
			}

			function toBulan(month) {
				var bln = [
					''
					, 'Januari'
					, 'Februari'
					, 'Maret'
					, 'April'
					, 'Mei'
					, 'Juni'
					, 'Juli'
					, 'Agustus'
					, 'September'
					, 'Oktober'
					, 'November'
					, 'Desember'
				];

				return bln[month];
			};
			
			if(options.dom === undefined) {
				options.dom = '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 text-right"B>><"row"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>';
			}

			if(options.language === undefined) {
				options.language = {
					processing: '<div class="text-primary"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Mohon menunggu...</span></div>',
      				emptyTable: 'Tidak ada data untuk ditampilkan',
      				zeroRecords: 'Tidak ada data untuk ditampilkan',
				};
			}

			var table = jQuery('#datatable_ajax').DataTable(options);

			$('#datatable_ajax tfoot th').each( function () {
				var title = $(this).not('.filter-custom').text();
				var dp = $(this).data('datepicker');
				if(title !== '') {
					$(this).html('<input type="text" placeholder="Cari" class="form-control" />');
				}

				if(dp == true){
					$(this).children('input').attr('readonly', true);
					$(this).children('input').datepicker({
						autoclose: true,
						format: 'yyyy-mm-dd',
						clearBtn: true,
						language: 'ID'
					});
				}

			});

			table.columns().every(function () {
				var that = this;
				var cari = '';
				$('input, select', this.footer()).on('change', function (e) {
					cari = this.value;
					if(that.search() !== cari) {
						that
						.search(cari)
						.draw();
					}
				});
			});

			table.on('draw', function( settings, json ) {
				var elemen = $('.formatToDate');
				elemen.each(function(i, el) {
					if(el.nodeName == 'TD') {
						var dt =  new Date($(el).text().toString());
						// tgl = (dt.getDate() < 10 ? '0' + dt.getDate() : dt.getDate()) + '-' 
								// + ((dt.getMonth() + 1) < 10 ? '0' + (dt.getMonth() + 1) : (dt.getMonth() + 1)) + '-' + dt.getFullYear();
							tgl = moment(dt).format('DD-MMM-YYYY');
						console.log(tgl);
						$(el).html(tgl);
					}
				});
			});

			table.on('draw', function( settings, json ) {
				var elemen = $('.formatToTime');
				elemen.each(function(i, el) {
					if(el.nodeName == 'TD') {
						$(el).html($(el).text().toString().substring(0, 5));
					}
				});
			});

			table.on('draw', function( settings, json ) {
				var elemen = $('.convertToMinute');
				elemen.each(function(i, el) {
					if(el.nodeName == 'TD') {
						var wk = $(el).text().toString().split(':');
						$(el).html((parseInt(wk[0])*60)+parseInt(wk[1]));
					}
				});
			});

			table.on('draw', function( settings, json ) {
				var elemen = $('.text-uppercase');
				elemen.each(function(i, el) {
					if(el.nodeName == 'TD') {
						$(el).html($(el).text().toUpperCase());
					}
				});
			});

			table.on('draw', function( settings, json ) {
				var elemen = $('.text-bulan');
				elemen.each(function(i, el) {
					if(el.nodeName == 'TD') {
						$(el).html(toBulan($(el).text()));
					}
				});
			});

			return table;
		}
	}
}();

