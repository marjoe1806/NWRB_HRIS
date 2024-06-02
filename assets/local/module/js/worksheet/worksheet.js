$(document).ready(function(){
	
	var baseurl = commons.baseurl;
	var i = 0;
	var a = 0;
	getEmployees.activate()
	$(document).on('show.bs.modal','#myModal', function () {
	    //$('.to_name').val($('.employee_id_hide').val()).change();
	    $.AdminBSB.input.activate();
	    $.AdminBSB.select.activate();
	})
	$(document).on('click', '#add', function(e){  
		e.preventDefault();
		i++;
		a = a+5;
		$('#dynamic_row').append(
			'<tr id="row'+i+'">'
			+	'<td><input type="text" class="form-control" name="indv-goals[]" placeholder="Input text here.."></td>'
			+	'<td><input type="text" class="form-control" name="third-res[]" placeholder="Input text here.."></td>'
			+	'<td><input type="text" class="form-control" name="fourth-res[]" placeholder="Input text here.."></td>'
			+	'<td><input type="text" class="form-control" name="res-remarks[]" placeholder="Input text here.."></td>'
			+	'<td><input type="number" step = "0.01" class="form-control" name="percentage[]" placeholder="Input text here.."></td>'
			+	'<td><button class="btn btn-danger btn-circle waves-effect waves-circle waves-float btn-round btn_remove" id="'+i+'"><i class="material-icons">not_interested</i></td>'
			+'</tr>'
		);

	});

	$(document).on('click', '.btn_remove', function(){  
		var button_id = $(this).attr("id");   
        $('#row'+button_id+'').remove();
	}); 


	$(document).on('click', '#print_preview', function(){
		if( $("#rater_display").val() == "" ||  $("#director_display").val() == ""){
			swal("Warning", "Please fill out other fields", "warning");
		}
		else{
			var rater = $("#rater_display").val();
			var direc = $("#director_display").val();
			$("#rater_display").replaceWith('<input type="text" value = "'+rater+'" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" required>');
			$("#director_display").replaceWith('<input type="text" value = "'+direc+'" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" required>');
			PrintElem("content");
		}
	});  

  

  
	function loadEmpName(){
		$.ajax({
	        url: baseurl + "individual_goal_worksheet/Report_List/get_emp_name", 
	        dataType: "json", 
	        success: function(jsondata){
	            for(var i = 0;i < jsondata.data.length;i++){
	                $("#emp_name").append("<option value='"+jsondata.data[i].id+"'>"+jsondata.data[i].name+"</option>");
	            }
	        }
	    });
	}

	loadEmpName();
	$(document).on('change','#emp_name',function(){
		//alert('hello')
		id = $(this).val()
		url2 = commons.baseurl + "employees/Employees/getEmployeesById";
        dept_id = "N/A"
        position = "N/A"
        $.ajax({
            async: false,
            url: url2,
            data:{Id: id},
            type:'GET',
            dataType:'JSON',
            success: function(res) {
                temp = res;
                //console.log(temp.Data.details)
                if(temp.Code == "0"){
                    position = temp.Data.details[0].position_name;
                    dept_id = temp.Data.details[0].department_name;
                    status = temp.Data.details[0].employment_type;
                    civil_status = temp.Data.details[0].civil_status;
                    gsis = temp.Data.details[0].sss_no;
                    tin = temp.Data.details[0].tin_no;
                    //$('#myModal .employee_select').selectpicker('destroy')
                }
            }
        });
        $('.employee_position').html(position)
        $('.employee_division').html(dept_id)
	})

	$(document).on('change','#first_approver',function(){
		//alert('hello')
		id = $(this).val()
		url2 = commons.baseurl + "employees/Employees/getEmployeesById";
        dept_id = "N/A"
        position = "N/A"
        $.ajax({
            async: false,
            url: url2,
            data:{Id: id},
            type:'GET',
            dataType:'JSON',
            success: function(res) {
                temp = res;
                if(temp.Code == "0"){
                    position = temp.Data.details[0].position_name;
                    dept_id = temp.Data.details[0].department_name;
                    status = temp.Data.details[0].employment_type;
                    civil_status = temp.Data.details[0].civil_status;
                    gsis = temp.Data.details[0].sss_no;
                    tin = temp.Data.details[0].tin_no;
                    //$('#myModal .employee_select').selectpicker('destroy')
                }
            }
        });
        $('#first_approver_position').val(position);
	})

	$(document).on('change','#second_approver',function(){
		//alert('hello')
		id = $(this).val()
		url2 = commons.baseurl + "employees/Employees/getEmployeesById";
        dept_id = "N/A"
        position = "N/A"
        $.ajax({
            async: false,
            url: url2,
            data:{Id: id},
            type:'GET',
            dataType:'JSON',
            success: function(res) {
                temp = res;
                if(temp.Code == "0"){
                    position = temp.Data.details[0].position_name;
                    dept_id = temp.Data.details[0].department_name;
                    status = temp.Data.details[0].employment_type;
                    civil_status = temp.Data.details[0].civil_status;
                    gsis = temp.Data.details[0].sss_no;
                    tin = temp.Data.details[0].tin_no;
                    //$('#myModal .employee_select').selectpicker('destroy')
                }
            }
        });
        $('#second_approver_position').val(position);
	})

	$(document).on('change','#first_approver_update',function(){
		//alert('hello')
		id = $(this).val()
		url2 = commons.baseurl + "employees/Employees/getEmployeesById";
        dept_id = "N/A"
        position = "N/A"
        $.ajax({
            async: false,
            url: url2,
            data:{Id: id},
            type:'GET',
            dataType:'JSON',
            success: function(res) {
                temp = res;
                if(temp.Code == "0"){
                    position = temp.Data.details[0].position_name;
                    dept_id = temp.Data.details[0].department_name;
                    status = temp.Data.details[0].employment_type;
                    civil_status = temp.Data.details[0].civil_status;
                    gsis = temp.Data.details[0].sss_no;
                    tin = temp.Data.details[0].tin_no;
                    //$('#myModal .employee_select').selectpicker('destroy')
                }
            }
        });
        $('#first_approver_position_update').val(position);
	})

	$(document).on('change','#second_approver_update',function(){
		//alert('hello')
		id = $(this).val()
		url2 = commons.baseurl + "employees/Employees/getEmployeesById";
        dept_id = "N/A"
        position = "N/A"
        $.ajax({
            async: false,
            url: url2,
            data:{Id: id},
            type:'GET',
            dataType:'JSON',
            success: function(res) {
                temp = res;
                if(temp.Code == "0"){
                    position = temp.Data.details[0].position_name;
                    dept_id = temp.Data.details[0].department_name;
                    status = temp.Data.details[0].employment_type;
                    civil_status = temp.Data.details[0].civil_status;
                    gsis = temp.Data.details[0].sss_no;
                    tin = temp.Data.details[0].tin_no;
                    //$('#myModal .employee_select').selectpicker('destroy')
                }
            }
        });
        $('#second_approver_position_update').val(position);
	})

	$(document).on('click', '#add_modal', function(){
		// console.log('test');
		// $('#emp_name').val('');
	});

	$(document).on('click', '#submit', function(){

		var indv_goals_val = $("input[name='indv-goals[]']").map(function(){return $(this).val();}).get();
		var third_res_val = $("input[name='third-res[]']").map(function(){return $(this).val();}).get();
		var fourth_res_val = $("input[name='fourth-res[]']").map(function(){return $(this).val();}).get();
		var res_remarks_val = $("input[name='res-remarks[]']").map(function(){return $(this).val();}).get();
		var percentage_val = $("input[name='percentage[]']").map(function(){return $(this).val();}).get();
		var quality_of_job_val = $("#quality_of_job").val();
		var public_and_emp_rel_val = $("#public_and_emp_rel").val();
		var punc_and_attend_val = $("#punc_and_attend").val();
		var industry_val = $("#industry").val();
		var total_score_val = $("#total_score").val();
		var average_score_val = $("#average_score").val();
		var rating_val = $("#rating").val();
		var final_rating_part_val = $("#final_rating_part").val();
		var final_rating_part2_val = $("#final_rating_part2").val();
		var numerical_rate_val = $("#numerical_rate").val();
		var adjectival_rate_val = $("#adjectival_rate").val();
		var rater_id = $("#rater_name").val();
		var rater_name = $("#rater_name").find("option:selected").text();
		var first_approver_id = $("#first_approver").val();
		var first_approver_name = $("#first_approver").find("option:selected").text();
		var first_approver_position = $("#first_approver_position").val();
		var second_approver_id = $("#second_approver").val();
		var second_approver_name = $("#second_approver").find("option:selected").text();
		var second_approver_position = $("#second_approver_position").val();
		var emp_name = $("#emp_name").find("option:selected").text();
		var emp_id = $("#emp_name").val();
		var supervisor_id = $('#supervisor_name').val();
		var supervisor_name = $('#supervisor_name').find("option:selected").text();
		url2 = commons.baseurl + "employees/Employees/getEmployeesById";
        dept_id = "N/A"
        position = "N/A"
        
        $.ajax({
            async: false,
            url: url2,
            data:{Id: emp_id},
            type:'GET',
            dataType:'JSON',
            success: function(res) {
                temp = res;
                if(temp.Code == "0"){
                    position = temp.Data.details[0].position_name;
                    dept_id = temp.Data.details[0].department_name;
                    status = temp.Data.details[0].employment_type;
                    civil_status = temp.Data.details[0].civil_status;
                    gsis = temp.Data.details[0].sss_no;
                    tin = temp.Data.details[0].tin_no;
                    //$('#myModal .employee_select').selectpicker('destroy')
                }
            }
        });

		$.ajax({
			"url": baseurl + "individual_goal_worksheet/Report_List/insert",
			"type": "POST",
			"dataType": "json",
			"data":{
				rater_id: rater_id,
				rater_name: rater_name,
				first_approver_id: first_approver_id,
				first_approver_name: first_approver_name,
				first_approver_position: first_approver_position,
				second_approver_id: second_approver_id,
				second_approver_name: second_approver_name,
				second_approver_position: second_approver_position,
				emp_id: emp_id,
				supervisor_name: supervisor_name,
				supervisor_id: supervisor_id,
				employee_name: emp_name,
				employee_position: position,
				employee_division: dept_id,
	    		indv_goals: indv_goals_val,
	    		third_res: third_res_val,
	    		fourth_res: fourth_res_val,
	    		res_remarks: res_remarks_val,
	    		percentage: percentage_val,
	    		quality_of_job: quality_of_job_val,
	    		public_and_emp_rel: public_and_emp_rel_val,
	    		punc_and_attend: punc_and_attend_val,
	    		industry: industry_val,
	    		total_score: total_score_val,
	    		average_score: average_score_val,
	    		rating: rating_val,
	    		final_rating_part: final_rating_part_val,
	    		final_rating_part2: final_rating_part2_val,
	    		numerical_rate: numerical_rate_val,
	    		adjectival_rate: adjectival_rate_val
			},
		    success: function(result){
		    	if(result.data == "success"){
			        swal("Success", "Data Inserted Successfully!", "success");
		            $("#add_pes").modal("hide");
		        	loadTblReports();
		    	}
		    	else{
		    		alert("error");
		    	}
		    }
		});
	});

	function loadTblReports(){
		$('#tbl_reports').DataTable({
			"destroy": "true",
	        "pagingType": "full_numbers",
	        "lengthMenu": [
	            [10, 25, 50, -1],
	            [10, 25, 50, "All"]
	        ],
	        responsive: true,
	        language: {
	            search: "_INPUT_",
	            searchPlaceholder: "Search records",
	        },
	        ajax: baseurl + "individual_goal_worksheet/Report_List/load_tbl_reports",
	        columns: [
		        { 
		        	data: 'id_report' 
		        },
		        { 
		        	data: 'employee_name' 
		        },
		        { 
		        	data: 'date_created' 
		        },
		        {
					"data": function (data) {
						//console.log(data);
						return `
                            <button data-third_position='` + data.third_position + `' data-third_name='` + data.third_name + `' data-id_third='` + data.id_third + `' data-director_position='` + data.director_position + `' data-director_name='` + data.director_name + `' data-id_director='` + data.id_director + `'  data-rater_name='` + data.rater_name + `' data-id_rater='` + data.id_rater + `' data-id_report='` + data.id_report + `'  data-emp_name='` + data.employee_name + `'  data-employee_id='` + data.emp_id + `'  data-employee_position='` + data.employee_position + `'  data-employee_division='` + data.employee_division + `'  data-supervisor_name='` + data.supervisor_name + `'  data-supervisor_id='` + data.supervisor_id + `'  class="btn btn-success btn-circle waves-effect waves-circle waves-float" id = "update_report_btn" data-toggle = "modal" data-target = "#update_pes">
                            	<i class="material-icons">mode_edit</i>
                            </button>
                            <button data-third_position='` + data.third_position + `' data-third_name='` + data.third_name + `' data-id_third='` + data.id_third + `' data-director_position='` + data.director_position + `' data-director_name='` + data.director_name + `' data-id_director='` + data.id_director + `'  data-rater_name='` + data.rater_name + `' data-id_rater='` + data.id_rater + `' data-id_report='` + data.id_report + `'  data-emp_name='` + data.employee_name + `'  data-employee_id='` + data.emp_id + `'  data-employee_position='` + data.employee_position + `'  data-employee_division='` + data.employee_division + `'  data-supervisor_name='` + data.supervisor_name+ `'  data-supervisor_id='` + data.supervisor_id + `' class="btn btn-info btn-circle waves-effect waves-circle waves-float" id = "view_report" data-toggle = "modal" data-target = "#print_preview_modal">
                            	<i class="material-icons">remove_red_eye</i>
                            </button>
                            <button data-id_report='` + data.id_report + `' data-emp_name='` + data.employee_name + `' class="btn btn-danger btn-circle waves-effect waves-circle waves-float" id = "delete_report">
                            	<i class="material-icons">delete</i>
                            </button>
	                    `;
					},
				}
			],

	    });
	}

	loadTblReports();

	$(document).on('click', '#delete_report', function(){
		var this_delete = $(this).data("id_report");
		swal({
		 	title: "Are you sure?",
		 	text: "Your will not be able to recover this imaginary file!",
		 	type: "warning",
		 	showCancelButton: true,
		 	confirmButtonClass: "btn-danger",
		 	confirmButtonText: "Yes, delete it!",
		 	closeOnConfirm: false
		},
		function(){
		 	$.ajax({
				"url": baseurl + "individual_goal_worksheet/Report_List/delete_this_report",
				"type": "POST",
				"dataType": "json",
				"data":{
					report_id: this_delete
				},
			    success: function(result){
			    	if(result.data == "deleted"){
			    		swal("Success", "Successfully Deleted!", "success");
			    		loadTblReports();
			    	}
			    	else{
			    		aler("error");
			    	}
			    }
			});
		});
	});

	$(document).on('click', '#update_report_btn', function(){
		$("#empty").empty();
		var id = $(this).data("id_report");
		var name = $(this).data("employee_name");
		var employee_id = $(this).data("employee_id");
		var employee_division = $(this).data("employee_division");
		var employee_position = $(this).data("employee_position");
		var employee_position = $(this).data("employee_position");
		var supervisor_name = $(this).data("supervisor_name");
		var supervisor_id = $(this).data("supervisor_id");
		var id_rater = $(this).data("id_rater");
		var rater_name = $(this).data("rater_name");
		var id_director = $(this).data("id_director");
		var director_name = $(this).data("director_name");
		var director_position = $(this).data("director_position");
		var id_third = $(this).data("id_third");
		var third_name = $(this).data("third_name");
		var third_position = $(this).data("third_position");
		var immediate = $(this).data("supervisor_id")

		$("#rater_name_update").val(id_rater).change();
		$("#first_approver_update").val(id_director).change();
		$("#third_name").val(third_name).change();
		$('.emp_position_display').html(employee_division)
		$('.emp_division_display').html(employee_position)
		$("#emp_name_display").val(employee_id).change();
		$('#second_approver_update').val(id_third).change();
		$('#supervisor_name_display').val(supervisor_id).change();
		//console.log($('#second_approver_update').val(supervisor_id).change())
		$.ajax({
			"url": baseurl + "individual_goal_worksheet/Report_List/getAllData",
			"type": "POST",
			"dataType": "json",
			"data":{
				report_id: id
			},
		    success: function(result){
		    	var j = 0;

		    	console.log(result.data);
		    	$("#hidden_id").val(result.data[0].id);
		    	$("#hidden_id_form").val(result.data[0].id_form_b);
		    	$("#update_quality_of_job").val(result.data[0].quality_of_job);
		    	$("#update_public_and_emp_rel").val(result.data[0].public_and_emp_rel);
		    	$("#update_punc_and_attend").val(result.data[0].punc_and_attend);
		    	$("#update_industry").val(result.data[0].industry);
		    	$("#update_total_score").val(result.data[0].total_score);
		    	$("#update_average_score").val(result.data[0].average_score);
		    	$("#update_rating").val(result.data[0].rating);
		    	$("#update_final_rating_part").val(result.data[0].final_rating_part);
		    	$("#update_final_rating_part2").val(result.data[0].final_rating_part2);
		    	$("#update_numerical_rate").val(result.data[0].numerical_rate);
		    	$("#update_adjectival_rate").val(result.data[0].adjectival_rate);
		  		
		    	for(var a = 0; a < result.data.length; a++){
		    		j++;
		    		$('#dynamic_row_update').append(
						'<tr id="update_row'+j+'">'
						+	'<td style = "display: none;"><input name = "update-id[]" type="hidden" value = "'+result.data[a].id+'"></td>'
						+	'<td style = "display: none;"><input name = "update-report-id[]" type="hidden" value = "'+result.data[a].report_id+'"></td>'
						+	'<td><input type="text" value = "'+result.data[a].individual_goals+'" class="form-control" name="update-indv-goals[]" placeholder="Input text here.."></td>'
						+	'<td><input type="text" value = "'+result.data[a].third_quarter_results+'" class="form-control" name="update-third-res[]" placeholder="Input text here.."></td>'
						+	'<td><input type="text" value = "'+result.data[a].fourth_quarter_results+'" class="form-control" name="update-fourth-res[]" placeholder="Input text here.."></td>'
						+	'<td><input type="text" value = "'+result.data[a].result_and_remarks+'" class="form-control" name="update-res-remarks[]" placeholder="Input text here.."></td>'
						+	'<td><input type="number" step = "0.01" value = "'+result.data[a].percentage+'" class="form-control" name="update-percentage[]" placeholder="Input text here.."></td>'
						+	'<td><button data-id_delete = '+result.data[a].id+' value = "'+result.data[a].id+'" class="btn btn-danger btn-circle waves-effect waves-circle waves-float btn_update_remove"><i class="material-icons">not_interested</i></td>'
						+'</tr>'
					);
		    	}



				$(document).on('click', '#update_add', function(e){ 
					e.preventDefault();
					j++;
					$('#dynamic_row_update').append(
						'<tr id="update_row'+j+'">'
						+	'<td style = "display: none;"><input name = "update-id[]" type="hidden" value = "0"></td>'
						+	'<td style = "display: none;"><input name = "update-report-id[]" type="hidden" value = "'+result.data[0].report_id+'"></td>'
						+	'<td><input type="text" class="form-control" name="update-indv-goals[]" placeholder="Input text here.."></td>'
						+	'<td><input type="text" class="form-control" name="update-third-res[]" placeholder="Input text here.."></td>'
						+	'<td><input type="text" class="form-control" name="update-fourth-res[]" placeholder="Input text here.."></td>'
						+	'<td><input type="text" class="form-control" name="update-res-remarks[]" placeholder="Input text here.."></td>'
						+	'<td><input type="number" step = "0.01" class="form-control" name="update-percentage[]" placeholder="Input text here.."></td>'
						+	'<td><button class="btn btn-danger btn-circle waves-effect waves-circle waves-float btn_add_row_remove" id="'+j+'"><i class="material-icons">not_interested</i></td>'
						+'</tr>'
					);
				});


				$(document).on('click', '.btn_add_row_remove', function(e){
					var button_id = $(this).attr("id");   
        			$('#update_row'+button_id+'').remove();
				});

				$(document).on('click', '.btn_update_remove', function(){  
					var id_delete = $(this).data("id_delete");
					swal({
					 	title: "Are you sure?",
					 	text: "Your will not be able to recover this imaginary file!",
					 	type: "warning",
					 	showCancelButton: true,
					 	confirmButtonClass: "btn-danger",
					 	confirmButtonText: "Yes, delete it!",
					 	closeOnConfirm: false
					},
					function(){
					 	$.ajax({
							"url": baseurl + "individual_goal_worksheet/Report_List/delete",
							"type": "POST",
							"dataType": "json",
							"data":{
					    		delete_id : id_delete,
						    },
						    success: function(result){
						    	if(result.data == "deleted"){
						    		swal("Success", "Successfully Deleted!", "success");
						    		$("#update_pes").modal("hide");
		        					loadTblReports();
						    	}	
						    }
						});
					});
				});
		    }
		});

	});

	$(document).on('click', '#view_report', function(){  
		$('#table-content').empty();
		var id = $(this).data("id_report");
		var rater_name = $(this).data("rater_name");
		var director_view = $(this).data("director_position");
		var report_name = $(this).data("emp_name");
		var report_division = $(this).data("employee_division");
		var report_position = $(this).data("employee_position");
		var supervisor = $(this).data("supervisor_name");
		var exe_director = $(this).data("third_name");
		var second_name = $(this).data("director_name");
		var second_position = $(this).data("director_position");
		var third_position = $(this).data("third_position");
		$("#report_name").text(report_name);
		$(".disp_divison").html(report_division);
		$(".disp_position").html(report_position);
		$(".disp_super").html(supervisor);
		$("#rater_display").val(rater_name);
		$(".director_view").html(director_view);
		$("#exe_director").val(exe_director);
		$("#director_display").val(second_name);
		$("#third_position").html(third_position);
		$.ajax({
			"url": baseurl + "individual_goal_worksheet/Report_List/getAllData",
			"type": "POST",
			"dataType": "json",
			"data":{
				report_id: id
			},
			success: function(result){
				console.log(result.data);
				for(var a = 0; a < result.data.length; a++){
					$('#table-content').append(
						'<tr style = "border: 1px solid black; text-align: center; font-size: 11pt;">'
							+ '<td style = "border: 1px solid black;">' 
							+		result.data[a].individual_goals
							+ '</td>'
							+ '<td style = "border: 1px solid black;">' 
							+		result.data[a].third_quarter_results
							+ '</td>'
							+ '<td style = "border: 1px solid black;">' 
							+		result.data[a].fourth_quarter_results
							+ '</td>'
							+ '<td style = "border: 1px solid black;">' 
							+		result.data[a].result_and_remarks
							+ '</td>'
							+ '<td style = "border: 1px solid black;">' 
							+		result.data[a].percentage
							+ '</td>'
						+'</tr>'
					);
				}

				$("#qob").replaceWith('<input type="text" value = '+result.data[0].quality_of_job+' name="qob" id = "qob" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly>');
				$("#public").replaceWith('<input type="text" value = '+result.data[0].public_and_emp_rel+' name="qob" id = "qob" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly>');
				$("#punc").replaceWith('<input type="text" value = '+result.data[0].punc_and_attend+' name="qob" id = "qob" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly>');
				$("#ind").replaceWith('<input type="text" value = '+result.data[0].industry+' name="qob" id = "qob" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly>');
				$("#total").replaceWith('<input type="text" value = '+result.data[0].total_score+' name="qob" id = "qob" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly>');
				$("#ave").replaceWith('<input type="text" value = '+result.data[0].average_score+' name="qob" id = "qob" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly>');
				$("#rate").replaceWith('<input type="text" value = '+result.data[0].rating+' name="qob" id = "qob" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly>');
				$("#parti").replaceWith('<input type="text" value = '+result.data[0].final_rating_part+' name="qob" id = "qob" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly>');
				$("#partii").replaceWith('<input type="text" value = '+result.data[0].final_rating_part2+' name="qob" id = "qob" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly>');
				$("#nume").replaceWith('<input type="text" value = '+result.data[0].numerical_rate+' name="qob" id = "qob" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly>');
				$("#adj").replaceWith('<input type="text" value = '+result.data[0].adjectival_rate+' name="qob" id = "qob" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly>');
			}
		});
	});	

	

	$(document).on('click', '#update_submit', function(){
		var update_id_val = $("input[name='update-id[]']").map(function(){return $(this).val();}).get();
		var update_indv_goals_val = $("input[name='update-indv-goals[]']").map(function(){return $(this).val();}).get();
		var update_third_res_val = $("input[name='update-third-res[]']").map(function(){return $(this).val();}).get();
		var update_fourth_res_val = $("input[name='update-fourth-res[]']").map(function(){return $(this).val();}).get();
		var update_res_remarks_val = $("input[name='update-res-remarks[]']").map(function(){return $(this).val();}).get();
		var update_percentage_val = $("input[name='update-percentage[]']").map(function(){return $(this).val();}).get();
		var update_report_id_val = $("input[name='update-report-id[]']").map(function(){return $(this).val();}).get();
		
		var rater_name_update_id = $("#rater_name_update").val();
		var rater_name_update = $("#rater_name_update").find("option:selected").text();
		var first_approver_update = $("#first_approver_update").find("option:selected").text();
		var first_approver_update_id = $("#first_approver_update").val();
		var first_approver_position = $("#first_approver_position_update").val();
		var second_approver_id_update = $("#second_approver_update").val();
		var second_approver_update = $("#second_approver_update").find("option:selected").text();
		var second_approver_position_update = $("#second_approver_position_update").val();


		var emp_name = $("#emp_name_display").find("option:selected").text();
		var emp_id = $("#emp_name_display").val();
		var supervisor_id = $('#supervisor_name_display').val();
		var supervisor_name = $('#supervisor_name_display').find("option:selected").text();
		url2 = commons.baseurl + "employees/Employees/getEmployeesById";
        dept_id = "N/A"
        position = "N/A"
        $.ajax({
            async: false,
            url: url2,
            data:{id: emp_id},
            type:'POST',
            dataType:'JSON',
            success: function(res) {
                temp = res;
                if(temp.Code == "0"){
                    position = temp.Data.details[0].position_name;
                    dept_id = temp.Data.details[0].department_name;
                    status = temp.Data.details[0].employment_type;
                    civil_status = temp.Data.details[0].civil_status;
                    gsis = temp.Data.details[0].sss_no;
                    tin = temp.Data.details[0].tin_no;
                    //$('#myModal .employee_select').selectpicker('destroy')
                }
            }
        });
		$.ajax({
			"url": baseurl + "individual_goal_worksheet/Report_List/update",
			"type": "POST",
			"dataType": "json",
			"data":{
				rater_name_update: rater_name_update,
				first_approver_update: first_approver_update,
				first_approver_position: first_approver_position,
				second_approver_update: second_approver_update,
				second_approver_position_update: second_approver_position_update,
				rater_name_update_id: rater_name_update_id,
				first_approver_update_id: first_approver_update_id,
				second_approver_id_update: second_approver_id_update,
				employee_id: emp_id,
				supervisor_id: supervisor_id,
				update_report_id: update_report_id_val,
				supervisor_name: supervisor_name,
				employee_name: emp_name,
				employee_position: position,
				employee_division: dept_id,
	    		update_indv_goals: update_indv_goals_val,
	    		update_third_res: update_third_res_val,
	    		update_fourth_res: update_fourth_res_val,
	    		update_res_remarks: update_res_remarks_val,
	    		update_percentage: update_percentage_val,
	    		update_id : update_id_val,
	    		update_quality_of_job: $("#update_quality_of_job").val(),
	    		update_punc_and_attend: $("#update_punc_and_attend").val(),
	    		update_industry: $("#update_industry").val(),
	    		update_total_score: $("#update_total_score").val(),
	    		update_average_score: $("#update_average_score").val(),
	    		update_rating: $("#update_rating").val(),
	    		update_final_rating_part: $("#update_final_rating_part").val(),
	    		update_final_rating_part2: $("#update_final_rating_part2").val(),
	    		update_numerical_rate: $("#update_numerical_rate").val(),
	    		update_adjectival_rate: $("#update_adjectival_rate").val(),
	    		hidden_id: $("#hidden_id").val(),
	    		hidden_id_form: $("#hidden_id_form").val()		    
	    	},
		    success: function(result){
		    	if(result.data == true){
		    		// swal("Success", "Successfully Updated!", "success");
		    		swal({
			            title: "Success!",
			            text: "Successfully Updated!",
			            type: "success"
			        }, function() {
			            location.reload(true);
			        });
		        	$("#update_pes").modal("hide");
		        	// loadTblReports();
		        	
		    	}
		    }
		});

	});

	
	$("#quality_of_job, #public_and_emp_rel, #punc_and_attend, #industry").bind("keyup change", function(e) {
		e.preventDefault();
    	var total_score =+ Number($("#quality_of_job").val()) + Number($("#public_and_emp_rel").val()) + Number($("#punc_and_attend").val()) + Number($("#industry").val());
    	$("#total_score").val(total_score);
    	$("#average_score").val(total_score/4);
    	$("#rating").val((total_score/4)*.25);
	});

	$("#update_quality_of_job, #update_public_and_emp_rel, #update_punc_and_attend, #update_industry").bind("keyup change", function(e) {
		e.preventDefault();
    	var total_score =+ Number($("#update_quality_of_job").val()) + Number($("#update_public_and_emp_rel").val()) + Number($("#update_punc_and_attend").val()) + Number($("#update_industry").val());
    	$("#update_total_score").val(total_score);
    	$("#update_average_score").val(total_score/4);
    	$("#update_rating").val((total_score/4)*.25);
	});

	$("#final_rating_part, #final_rating_part2").bind("keyup change", function(e) {
		e.preventDefault();
		var num_rating =+ Number($("#final_rating_part").val()) + Number($("#final_rating_part2").val());
		$("#numerical_rate").val(num_rating);
	});

	$("#update_final_rating_part, #update_final_rating_part2").bind("keyup change", function(e) {
		e.preventDefault();
		var num_rating =+ Number($("#update_final_rating_part").val()) + Number($("#update_final_rating_part2").val());
		$("#update_numerical_rate").val(num_rating);
	});

	//$("#rater").val
	
	function PrintElem(elem){
	    
	    var mywindow = window.open('', 'PRINT', 'height=400,width=600');

	    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
	    mywindow.document.write('</head><body >');
	    mywindow.document.write('<h1>' + document.title  + '</h1>');
	    mywindow.document.write(document.getElementById(elem).innerHTML);
	    mywindow.document.write('</body></html>');

	    mywindow.document.close(); // necessary for IE >= 10
	    mywindow.focus(); // necessary for IE >= 10*/

	    mywindow.print();
	    mywindow.close();

	    return true;
	}

});

