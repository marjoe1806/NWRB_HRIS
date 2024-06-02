$(function() {
	$("body").addClass('fp-page');
	
	var form = $("#form_authorize");
    form.validate({
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.input-group').append(error);
        },
		rules: {
			Passcode: {
				required: true
			}
		},
		messages: {
			Passcode: {
				required: 'Passcode is required'
			}
		},
		success: function(label) {
			
		}
    });
    $('#attachmentsTable').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        responsive: true,
        aaSorting: [],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records",
        }

    });
});
