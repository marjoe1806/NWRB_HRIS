$(function () {
	base_url = commons.base_url;
	// table_monthly = $('#datatables_monthly').DataTable({
	// 	"pagingType": "full_numbers",
	// 	aaSorting: [],
	// 	language: {
	// 		search: "_INPUT_",
	// 		searchPlaceholder: "Search records",
	// 	}
	// });

	$('.datepicker').bootstrapMaterialDatePicker({
		format: 'YYYY-MM-DD',
		clearButton: true,
		weekStart: 1,
		time: false
	});

	$(document).on('show.bs.modal', '#myModal', function () {
		$('.datepicker').bootstrapMaterialDatePicker({
			format: 'YYYY-MM-DD',
			clearButton: true,
			weekStart: 1,
			time: false
		});
		$.AdminBSB.input.activate();
		$.AdminBSB.select.activate();
	});

	$(document).on('click', '#printPeriodSettings', function (e) {
		e.preventDefault();
		PrintElem("servicerecords-container");
	});

	//Confirms
	$(document).on('click', '.lockPeriodSettings,.deactivatePeriodSettings,.activateWeeklyPeriodSettings,.deactivateWeeklyPeriodSettings', function (e) {
		e.preventDefault();
		me = $(this);
		url = me.attr('href');
		var id = me.attr('data-id');
		if(me.attr('data-is-posted') == 1){
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Period already locked"
			});
			return false;
		}
			content = 'Are you sure you want to lock selected period settings?';
		data = {
			id: id
		};
		$.confirm({
			title: '<label class="text-warning">Confirm!</label>',
			content: content,
			type: 'orange',
			buttons: {
				confirm: {
					btnClass: 'btn-blue',
					action: function () {
						//Code here
						$.confirm({
							content: function () {
								var self = this;
								return $.ajax({
									type: "POST",
									url: url,
									data: {
										id: id
									},
									dataType: "json",
									success: function (result) {
										if (result.Code == "0") {
														self.setContent(result.Message);
														self.setTitle('<label class="text-success">Success</label>');
														$('#myModal').modal('hide');
														loadTable();
										} else {
											self.setContent(result.Message);
											self.setTitle('<label class="text-danger">Failed</label>');
										}
									},
									error: function (result) {
										self.setContent("There was an error in the connection. Please contact the administrator for updates.");
										self.setTitle('<label class="text-danger">Failed</label>');
									}
								});
							}
						});
					}

				},
				cancel: function () {}
			}
		});
	})

    $(document).on("click", "#btnsearch", function(){
        loadTable();
    });

	function daysInMonth (month, year) { // Use 1 for January, 2 for February, etc.
		return new Date(year, month, 0).getDate();
	}

	function setweekPeriod(key){
		var officiald = new Date($("#payroll_period").val());

	    var d = new Date($("#payroll_period").val()),
	        month = d.getMonth(),
	        mondays = [];
	        monday = "";
	    var fistdayvalid = d.getDay();
	    var fistDatevalid = d.getDate();
	    var pd = new Date(officiald.setMonth(officiald.getMonth()-1)),
	        pmonth = pd.getMonth(),
	        pmondays = [];
	        pmonday = "";
	    d.setDate(1);

	    // setter ng hahanapin na day every month
	    while (d.getDay() !== 1) {
	        d.setDate(d.getDate() + 1);
	        monday = d.setDate(d.getDate());
	    }
	     while (pd.getDay() !== 1) {
	        pd.setDate(pd.getDate() + 1);
	        pmonday = pd.setDate(pd.getDate());
	    }
	    // makuha lng lahat ng moday sa buwan ng prev, cur
	    while (d.getMonth() === month) {
	        mondays.push(new Date(d.getTime()));
	        d.setDate(d.getDate() + 7);
	    }
	    while (pd.getMonth() === pmonth) {
	        pmondays.push(new Date(pd.getTime()));
	        pd.setDate(pd.getDate() + 7);
	    }

	    var dateindexfrom = (new Date(pmondays[pmondays.length - 1])).toISOString();
	    var dateindexto = (new Date(pmondays[pmondays.length - 1].setDate(pmondays[pmondays.length - 1].getDate() + 4))).toISOString();
	    pd.setDate(pd.getDate() + 4);
	    var dataindexrev = (new Date(pmondays[pmondays.length - 1])).toISOString();
	    dateindexfrom = dateindexfrom.substring(0, 10);
	    dateindexto = dateindexto.substring(0, 10);
	    weeksarr = [];
	    if(	fistdayvalid != 1){
	        weeksarr = [
	                        {
	                            "from": dateindexfrom,
	                            "to": dateindexto
	                        }
	                    ]
	    }
		mondays.forEach(function(entry) {
			dateto  = new Date(entry).toISOString()
			dateform = new Date(entry.setDate(entry.getDate()+4)).toISOString()
			weeksarr.push({
			     "from": dateto.substring(0, 10),
			     "to": dateform.substring(0, 10)
			})
	    });

	    if(parseInt(key+1) <= weeksarr.length){
	    	$("#start_date").val(weeksarr[key].from)
	    	$("#end_date").val(weeksarr[key].to)
	    }else{
			$.alert({
				title: '<label class="text-danger">Failed</label>',
				content: "Period not allowed"
			});
	    }


	}

})


function loadTable(){
	$("#datatables_monthly").DataTable().clear().destroy();
	table = $('#datatables_monthly').DataTable({  
		"processing":true,  
		"serverSide":true,  
		"stateSave": true, // presumably saves state for reloads -- entries
		"bStateSave": true, // presumably saves state for reloads -- page number
		"order":[],
		scroller: {
			displayBuffer: 20
		},
		"columnDefs": [ {
		  "targets"  : [0],
		  "orderable": false
		}],
		initComplete : function() {
			
			var input = $('.dataTables_filter input').unbind(),
			self = this.api(),
			$searchButton = $('<button id="search-employee" class="btn bg-purple btn-circle waves-effect waves-circle waves-float">')
			.html('<i class="material-icons">search</i>')
			.click(function() {
				
				if(!$('#search-employee').is(':disabled')){
					$('#search-employee').attr('disabled',true);
					self.search(input.val()).draw();
					$('#datatables_monthly button').attr('disabled',true);
					$('.dataTables_filter').append('<div id="search-loader"><br>' 
						+'<div class="preloader pl-size-xs">'
						+    '<div class="spinner-layer pl-red-grey">'
						+        '<div class="circle-clipper left">'
						+            '<div class="circle"></div>'
						+        '</div>'
						+        '<div class="circle-clipper right">'
						+            '<div class="circle"></div>'
						+        '</div>'
						+    '</div>'
						+'</div>'
						+'&emsp;Please Wait..</div>');
				}

			})
			if	($("#search-employee").length === 0) {
			  $('.dataTables_filter').append($searchButton);
			}
			
		},
		"drawCallback": function( settings ) {
			$('#search-loader').remove();
			$('#search-employee').removeAttr('disabled');
			$('#datatables_monthly button').removeAttr('disabled');
		},
		"ajax":{  
			url:commons.baseurl+ "transactions/PayrollPosting/fetchRows?isPosted="+$("#isPosted").val()+"&month="+$("#month").val(),
			type:"GET",
		},  
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