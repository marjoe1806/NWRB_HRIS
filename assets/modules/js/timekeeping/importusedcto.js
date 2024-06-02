// alert('Under Development')
$(function () {
	var exceljson = null;
	var baseurl = null;
	var flag = null;

	$('#myModal').on('hidden.bs.modal', function (e) {
		$('#myModal .modal-footer').remove();
	})

    $("input[type=file]").change(function(){
    	$('#exceltable').html('');
    	$('.excel_card').hide();
        $('#viewfile').removeAttr('disabled',true);
    });

	$(document).on('click', '#viewfile', function () {
		exportToTable();
	})

	$(document).on('click', '.view_btn', function () {
		$('.view_logs').show();
		$(this).hide();
		$('.hide_btn').show();
	})

	$(document).on('click', '.hide_btn', function () {
		$('.view_logs').hide();
		$(this).hide();
		$('.view_btn').show();
	})

	$(document).on('click', '.close_btn', function () {
		 $("input[type=file]").val('');
		$('#exceltable').html('');
    	$('.excel_card').hide();
        $('#viewfile').removeAttr('disabled',true);
	})

	$(document).on('click', '#importfile', function () {
		baseurl = $(this).attr('data-baseurl');
		flag = Math.random();

		$.ajax({
			type: "POST",
			url: baseurl+'viewImportFromExcelForm',
			dataType: "json",
			success: function (result) {
				$('#myModal .modal-dialog').attr('class', 'modal-dialog modal-md');
				$('#myModal .modal-title').html('Import From Excel');
				$('#myModal .modal-body').html(result.form);
				$('#myModal .modal-content').append(
					'<div class="modal-footer">'+
						'<button type="button" class="hide_btn btn btn-default" style="display:none">Hide Logs</button>'+
						'<button type="button" class="view_btn btn btn-default">View Logs</button>'+
						'<button type="button" class="close_btn btn btn-default" data-dismiss="modal" disabled>Close</button>'+
					'</div>'
					);
				$('#myModal').modal({
				   backdrop: 'static',
				   keyboard: false
				});
				$('#myModal').modal('show');

				setTimeout(function() {
					fetchRows();
				}, 1000);
			},
			error: function (result) {
				$.alert({
					title: '<label class="text-danger">Failed</label>',
					content: 'There was an error in the connection. Please contact the administrator for updates.'
				});
			}
		});
	});

	function fetchRows() {
		var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;  
		/*Checks whether the file is a valid excel file*/  
		if (regex.test($("#file").val().toLowerCase())) {  
			var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/  
			if ($("#file").val().toLowerCase().indexOf(".xlsx") > 0) {  
				xlsxflag = true;  
			}  
			/*Checks whether the browser supports HTML5*/  
			if (typeof (FileReader) != "undefined") {  
				var reader = new FileReader();  
				reader.onload = function (e) {  
					var data = e.target.result;  
					/*Converts the excel data in to object*/  
					if (xlsxflag) {  
						var workbook = XLSX.read(data, { type: 'binary' });  
					} else {  
						var workbook = XLS.read(data, { type: 'binary' });  
					}  
					/*Gets all the sheetnames of excel in to a variable*/  
					var sheet_name_list = workbook.SheetNames;  

					var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/  
					var entries = 0;
					var maxlength = 0;
					sheet_name_list.forEach(function (y) { /*Iterate through all sheets*/  
						/*Convert the cell value to Json*/  
						if (xlsxflag) {  
							exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);  
						} else {  
							exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);  
						}  
				
						// var maxlength = (exceljson.length * sheet_name_list.length);
						maxlength += exceljson.length;

						OTtimeArr = []
						$.each(exceljson, function(i,v){
							var data = {};
							for(var key in v){
							  data[key.trim()] = String(v[key]).trim();
							}
							data['SOURCE_DEVICE'] = flag;
							// let OTstart = typeof v.OT_ARRIVAL == 'undefined' ? '00:00:00' : v.OT_ARRIVAL.split(" ")[0];
							// let OTend = typeof v.OT_DEPARTURE == 'undefined' ? '00:00:00' : v.OT_DEPARTURE.split(" ")[0];

							// let OTduration = diff(OTstart, OTend);
							// OTtimeArr.push(OTduration)
							// let SUMduration = sumOT(OTtimeArr);
							//  data['OTtotal'] = SUMduration
							// //  console.log("|||||||||||||||||||||||||||||||||||")
							// // console.log(data)
	                    	$.ajax({
								type: "POST",
								url: baseurl+'importexcel',
								data: data,
								dataType: "json",
								success: function (result) {
									entries += 1;
									progressbar(entries,maxlength);

							        $('.logs').append(result.Logs+'\n');
							        var psconsole = $('.logs');
								    if(psconsole.length)
								       psconsole.scrollTop(psconsole[0].scrollHeight - psconsole.height());
								},
								error: function (result) {	
									entries += 1;
									progressbar(entries,maxlength);							
									// $.alert({
									// 	title: '<label class="text-danger">Failed</label>',
									// 	content: 'There was an error in the connection. Please contact the administrator for updates.'
									// });
								}
							});
							
							
	                    })
						
					});
				} 

				if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/  
					reader.readAsArrayBuffer($("#file")[0].files[0]);  
				} else {  
					reader.readAsBinaryString($("#file")[0].files[0]);  
				}  

				
			} else {  
				$.alert({
					title: '<label class="text-danger">Failed</label>',
					content: 'Sorry! Your browser does not support HTML5!'
				});
				$('#myModal').modal('hide');
			}  
		} else {  
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: 'Please upload a valid Excel file!'
			}); 
			$('#myModal').modal('hide');
		} 
	}



	function exportToTable() {  
		var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;  
		/*Checks whether the file is a valid excel file*/  
		if (regex.test($("#file").val().toLowerCase())) {  
			var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/  
			if ($("#file").val().toLowerCase().indexOf(".xlsx") > 0) {  
				xlsxflag = true;  
			} 
			/*Checks whether the browser supports HTML5*/  
			if (typeof (FileReader) != "undefined") {  
				var reader = new FileReader();  
				reader.onload = function (e) {  
					var data = e.target.result;  
					/*Converts the excel data in to object*/  
					if (xlsxflag) {  
						var workbook = XLSX.read(data, { type: 'binary' });  
					} else {  
						var workbook = XLS.read(data, { type: 'binary' });  
					}  
					/*Gets all the sheetnames of excel in to a variable*/  
					var sheet_name_list = workbook.SheetNames;  

					var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/  
					sheet_name_list.forEach(function (y) { /*Iterate through all sheets*/  
						/*Convert the cell value to Json*/  
						if (xlsxflag) {  
						 var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);  
						} else {  
						 var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);  
						}  
						
						if (exceljson.length > 0 && cnt == 0) {  
						 BindTable(exceljson, '#exceltable');  
						 cnt++;  
						}  
					});  
					$('#exceltable').show();  
				} 

				if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/  
					reader.readAsArrayBuffer($("#file")[0].files[0]);  
				} else {  
					reader.readAsBinaryString($("#file")[0].files[0]);  
				}  
			} else {  
				$.alert({
					title: '<label class="text-danger">Failed</label>',
					content: 'Sorry! Your browser does not support HTML5!'
				});
			}  
		} else {  
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: 'Please upload a valid Excel file!'
			}); 
		}  
	}  

	function BindTable(jsondata, tableid) {/*Function used to convert the JSON array to Html Table*/  
		var columns = BindTableHeader(jsondata, tableid); /*Gets all the column headings of Excel*/  
		for (var i = 0; i < jsondata.length; i++) {  
			var row$ = $('<tr/>');  
			for (var colIndex = 0; colIndex < columns.length; colIndex++) {  
			var cellValue = jsondata[i][columns[colIndex]];  
				if (cellValue == null)  
					cellValue = "";  
				row$.append($('<td style="white-space:nowrap;"/>').html(cellValue));  
			}  
			$(tableid).append(row$);  
		}  
	}  

	function BindTableHeader(jsondata, tableid) {/*Function used to get all column names from JSON and bind the html table header*/  
		var columnSet = [];  
		var theader$ = $('<thead/>');
		var headerTr$ = $('<tr/>');  
		for (var i = 0; i < jsondata.length; i++) {  
			var rowHash = jsondata[i];  
			for (var key in rowHash) {  
				if (rowHash.hasOwnProperty(key)) {  
					if ($.inArray(key, columnSet) == -1) {/*Adding each unique column names to a variable array*/  
						columnSet.push(key);  
						theader$.append(headerTr$.append($('<th style="white-space:nowrap;"/>').html(key)));  
					}  
				}  
			}  
		}  
		$(tableid).append(theader$);  

		if(jsondata.length > 0) {
			$('#viewfile').attr('disabled',true)
			$('.excel_card').show();
		}

		return columnSet;  
	} 

	function progressbar(entries, maxlength) {
		percentage = ((entries/maxlength) * 100).toFixed(2);
		$('.progress-bar').attr('aria-valuenow', percentage);
		$('.progress-bar').css('width',percentage+'%');
        // $('.progress-bar').html((entries)+" out of "+maxlength);
        $('.progress-bar').html(percentage+'% ('+(entries)+" out of "+maxlength+")");

        if(percentage == 100) {
        	$('.close_btn').removeAttr('disabled',true);
        	// $('.view_btn').removeAttr('disabled',true);
        }
	}
})
