$(function () {
	var table;


	var page = "";
	base_url = commons.base_url;
	var table;
	loadTable("","","");
	$.when(
		getFields.location()
	).done(function () {
		$('#division_id option:first').text("All");
		/*$('#payroll_period_id').change();*/
		$.AdminBSB.select.activate();
	})
	$('.date_from').bootstrapMaterialDatePicker({
		format: 'YYYY-MM-DD',
		clearButton: true,
		weekStart: 1,
		time: false
	});
	$(document).on('change','#date_from',function(e){
        from = $(this).val();
        $('#date_to').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            clearButton: true,
            startDate: from,
            minDate: from,
            weekStart: 1,
            destroy: true,
            time: false
        });
        $('#date_to').bootstrapMaterialDatePicker('setMinDate',from);
        $('#date_to').bootstrapMaterialDatePicker('setStartDate',from);
    })
	$(document).on('click', function (e) {
		$('[data-toggle="popover"],[data-original-title]').each(function () {
			//the 'is' for buttons that trigger popups
			//the 'has' for icons within a button that triggers a popup
			if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
				(($(this).popover('hide').data('bs.popover') || {}).inState || {}).click = false // fix for BS 3.3.6
			}

		});
	});
	$(document).on('click', '#printClearance', function (e) {
		e.preventDefault();
		PrintElem("clearance-div");
	})


	//Ajax non-forms
	$(document).on('click', '#summarizeAllEmployeeDailyTimeRecord', function (e) {
		e.preventDefault();
		my = $(this)
		id = my.attr('data-id');
		url = my.attr('href');
		location_id = $('.search_entry #location_id').val();
		date_from = $('.search_entry #date_from').val()
		date_to = $('.search_entry #date_to').val()
		// alert(location_id)
		reloadTable();

	})

	function loadTable(location_id,date_from,date_to) {
		location_id = $('.search_entry #location_id').val();
		date_from = $('.search_entry #date_from').val()
		date_to = $('.search_entry #date_to').val()
		plus_url = ""
		// alert(location_id);
		table = $('#datatables').DataTable({
			"processing": true,
			"serverSide": true,
			"stateSave": true, // presumably saves state for reloads -- entries
			"bStateSave": true, // presumably saves state for reloads -- page number
			"order": [],
			"ajax": {
				url: commons.baseurl + "payrollreports/PrintedDTR/fetchRows",
				type: "POST",
				data:function(d){
					d.location_id = $('.search_entry #location_id').val();
					d.date_from   =	$('.search_entry #date_from').val()
					d.date_to	  =	$('.search_entry #date_to').val()
				}
			},
			"columnDefs": [{
				"targets": [0],
				"orderable": false,
			}, ],
			oLanguage: {sProcessing: '<div class="preloader pl-size-sm">'
                                    +'<div class="spinner-layer pl-red-grey">'
                                    +    '<div class="circle-clipper left">'
                                    +        '<div class="circle"></div>'
                                    +    '</div>'
                                    +    '<div class="circle-clipper right">'
                                    +        '<div class="circle"></div>'
                                    +    '</div>'
                                    +'</div>'
                                    +'</div>'}
		});
	}

	function reloadTable() {
		table.ajax.reload();
	}

	function PrintElem(elem) {

		var mywindow = window.open('', 'PRINT', 'height=400,width=600');
		mywindow.document.write('<html moznomarginboxes mozdisallowselectionprint><head>');
		// html, body { height: 100%; }
		mywindow.document.write('<style> * { font-family: arial; text-align: center; font-size: 12px; color: #000; background: #fff } body { -webkit-print-color-adjust: exact !important; size: auto; } @page { margin: 3mm 5mm 3mm 5mm; } .fixed-height { height: 20pt !important } .text-danger { color: #c00808 !important } .text-danger td { color: #c00808 !important } small { font-size: 10px } .text-left { text-align: left } .text-right { text-align: right } table { width: 50% } .border table { border-collapse: collapse } .border td, .border th { border: 1px solid #000; } @media print { .no-print, .no-print * { display: none; } } </style>')
		mywindow.document.write('</head><body >');
		mywindow.document.write(document.getElementById(elem).innerHTML);
		mywindow.document.write('</body></html>');

		mywindow.document.close();
		mywindow.focus();

		mywindow.print();
		mywindow.close();

		return true;
	}
	function firstOfLastMonth() {
		date = new Date();
		// console.log(date.getMonth()+1)
		var year = date.getFullYear();
		var month = date.getMonth();
		return year + '-' + month + '-' + '01';       
	}
	// alert(firstOfLastMonth())

})
