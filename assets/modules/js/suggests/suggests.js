function load_data(query, id) {
	my = $(`#${id}`);
	if($(my).data('model') === undefined) return;
	$.ajax({
		url: commons.baseurl + 'suggests/Suggests',
		method: 'POST',
		data: {
			model 		: $(my).data('model'),
			param_type 	: $(my).data('type'),
			select 		: $(my).data('select'),
			param_val	: query,
		},
		success: function(response) {
			if(response) {
				let select_picker = my
				let el = '';

				$(select_picker).empty();
				$.each(JSON.parse(response), function(k, v) {
					el +=`
						<option value="${v.id}">${v.employee_number} - ${v.first_name} ${v.last_name}</option>
					`
				});

				$(select_picker).append(el);
				$(select_picker).selectpicker('refresh');

			}
		}
	});
}

let input_field = $('.bs-searchbox').find('input');


$(document).on('keyup', input_field, function(e) {
	var input = e.target;
	var val = $(input).val();
	var {offsetParent: {offsetParent : { offsetParent: {childNodes} }}} = e.target;
	var id = $(childNodes[0]).attr("id");
	if (val.length >= 3) {
		if(val != '' && id != '') {
			load_data(val, id);
		} else {
			load_data();
		}
	}
});
