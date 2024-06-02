$(function() {
	var url = window.location;
	$('ul.list a[href="'+ url +'"]').parents().addClass('active');	
	$('ul.list a').filter(function() {
		return this.href == url;
	}).parents().addClass('active');

	// $(document).ready(function () {
	// 	$('#datatables').DataTable({
	// 		pagingType: 'full_numbers',
	// 	});
	// });

	$('body').tooltip({
	    selector: '[data-toggle="tooltip"]'
	});
    $('#myModal').on('hidden.bs.modal', function () {
	  $("#myModal .modal-body").html('<center><h3 class="text-warning">Content Not Found!</h3></center>')
	  $("#myModal .modal-title").html('<i class="material-icons text-danger" style="font-size:30px;">error</i>')
	});

	$(document).on('click', '#check_all', function () {
	    !$(this).prop('checked') ? $(".checkbox_table").prop("checked", false) : $(".checkbox_table").prop("checked", true);
	});
	let filterState;

	$(document).on('click', '.filter-button', function (e) {
	    e.preventDefault();
	    $("#filterInput").val('');
	    filterState = $(this).attr('id');
	    $('#check_all').prop('checked', false)
	    $('#filterInput').prop("disabled", false);
	    $('#module').DataTable().search('').columns().search('').draw();
	    $(".filter-buttons").find("a.filter-button").removeClass('bg-green');
	    $(".employee-table-columns").find("th.employee-table-column").removeClass('bg-green');
	    $(this).addClass('bg-green');
	    switch (filterState) {
	        case 'filterByID':
	            $("#card-number-column").addClass('bg-green');
	            break;
	        case 'filterByName':
	            $("#name-column").addClass('bg-green');
	            break;
	        case 'filterByDepartment':
	            $("#department-column").addClass('bg-green');
	            break;
	        case 'filterByOffice':
	            $("#office-column").addClass('bg-green');
	            break;
	        case 'filterByAgency':
	            $("#agency-column").addClass('bg-green');
	            break;
	        case 'filterByLocation':
	            $("#location-column").addClass('bg-green');
	            break;
	        default:
	            e.preventDefault();
	            break;
	    }
	});

	$(document).on('keyup', '#filterInput', function (e) {
	    e.preventDefault();
	    switch (filterState) {
	        case 'filterByID':
	            $('#module').DataTable().columns(1).search($("#filterInput").val()).draw();
	            break;
	        case 'filterByName':
	            $('#module').DataTable().columns(2).search($("#filterInput").val()).draw();
	            break;
	        case 'filterByDepartment':
	            $('#module').DataTable().columns(3).search($("#filterInput").val()).draw();
	            break;
	        case 'filterByOffice':
	            $('#module').DataTable().columns(4).search($("#filterInput").val()).draw();
	            break;
	        case 'filterByAgency':
	            $('#module').DataTable().columns(5).search($("#filterInput").val()).draw();
	            break;
	        case 'filterByLocation':
	            $('#module').DataTable().columns(6).search($("#filterInput").val()).draw();
	            break;
	        default:
	            $('#module').DataTable().search($("#filterInput").val()).draw();
	            break;
	    }
	});

	$(document).on('keypress keyup keydown','.full-name',function(e){
		var cp_value= $(this).val().toUpperCase();
		$(this).val(cp_value );
		//END CAPITALIZE
		inputLength = $(this).val().length;
		//console.log(inputLength);
		namevalue = $(this).val();
		var inputValue = e.which;
		//console.log(inputValue)
		// allow letters and whitespaces only.
		if(!(inputValue >= 65 && inputValue <= 122) && (inputValue != 32 && inputValue != 190 && inputValue != 46 && inputValue !=0 && inputValue !=8 && inputValue !=9) || (inputValue >= 91 && inputValue <=96)) { 
			e.preventDefault(); 
		}
	});
	$(document).on('keypress keyup keydown','.non-special',function(e){
		//console.log(inputLength);
		var inputValue = e.which;
		console.log(inputValue)
		// allow letters and whitespaces only.
		if(!(inputValue >= 65 && inputValue <= 122) && (inputValue != 32 && inputValue != 190 && inputValue != 188 && inputValue != 44 && inputValue != 46 && inputValue !=0 && inputValue !=8 && inputValue !=9) || (inputValue >= 91 && inputValue <=96)) { 
			e.preventDefault(); 
		}
	});
	$(document).on('keypress keyup keydown','.code-box',function(e){
		//END CAPITALIZE
		inputLength = $(this).val().length;
		//console.log(inputLength);
		namevalue = $(this).val();
		var inputValue = e.which;
		//console.log(inputValue);
		// allow letters and whitespaces only.
		//alert(inputValue == 94);
		if(!(inputValue >= 65 && inputValue <= 122 || inputValue >= 48 && inputValue <= 57) && (inputValue != 32 && inputValue !=0 && inputValue !=8 && inputValue !=9 && inputValue != 189 && inputValue != 45) || (inputValue >= 91 && inputValue <=96)) { 
			e.preventDefault(); 
		}
		else
		{
				$(this).attr('data-content','');
				$(this).popover('disable');
				$(this).popover('hide');
		}
	});
	//address Validation
	$(document).on('keypress keyup keydown','.address',function(e){
		//CAPITALIZE
		var cp_value= ucfirst($(this).val());
		$(this).val(cp_value );
		//END CAPITALIZE
		namevalue = $(this).val();
		var inputValue = e.which;
		// allow letters and whitespaces only.
		//alert(inputValue == 94);
		if(!(inputValue >= 48 && inputValue <= 122) && (inputValue!= 188 && inputValue!= 44 && inputValue != 190 && inputValue != 46 && inputValue != 9 && inputValue != 32 && inputValue != 35 && inputValue !=0 && inputValue !=8) || (inputValue >= 91 && inputValue <=96) || (inputValue >= 58 && inputValue <=64)) { 
			e.preventDefault(); 
		}
		else
		{
			$(this).attr('data-content','');
			$(this).popover('disable');
			$(this).popover('hide');
		}
	});
	//phone number validation
	$(document).on('keypress keyup keydown','.number',function(e){
		var number = e.which;
		//console.log(number);
		if(!(number >= 48 && number <= 57) && number !=8 && number != 9 && number != 0) 
		{ //0-9 only
			e.preventDefault();
		}
		else
		{
			$(this).attr('data-content','');
			$(this).popover('disable');
			$(this).popover('hide');
		}
	});
	//decimal number validation
	$(document).on('keypress keyup keydown','.decimal',function(e){
		var number = e.which;
		if(!(number >= 48 && number <= 57) && (number != 190 && number != 46 && number != 46 && number != 8 && number != 9)) 
		{ //0-9 only
			e.preventDefault();
		}
	});
	$(document).on('keypress keyup keydown','.currency',function(e){
		var number = e.which;
		// if(!(number >= 48 && number <= 57) || !(number >= 96 && number <= 105))//(number != 190 && number != 46 && number != 46 && number != 8 && number != 9)) 
		// { //0-9 only
		// 	e.preventDefault();
		// }
		if((number >= 48 && number <= 57) || (number >= 96 && number <= 105)){
			
			val = $(this).val().replace(/,/g, '');
			$(this).val(addCommas(val));
		}else return false;
	})
	$(document).on('keypress keyup keydown','.currency2',function(e){
		var number = e.which;
		// if(!(number >= 48 && number <= 57) || !(number >= 96 && number <= 105))//(number != 190 && number != 46 && number != 46 && number != 8 && number != 9)) 
		// { //0-9 only
		// 	e.preventDefault();
		// }
		if((number >= 48 && number <= 57) || (number >= 96 && number <= 105) || number == 46 || number == 8 || number == 9){
			
			// val = $(this).val().replace(/,/g, '');
			// $(this).val(addCommas(val));
			return true;
		}else return false;
	})
	$(document).on('keypress','.currency3',function(event){
		if($(this).val().indexOf('.') != -1){
			var inp = $(this).val().split(".");
			if(inp[1].length >= 2){
				event.preventDefault();
			}
		}
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
	})
	
});

function ucfirst(str,force)
{
	return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});		  
}
function addCommas(number) {
    var parts = number.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}
