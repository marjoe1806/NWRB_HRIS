$(function () {
    $('#sign_in').validate({
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
			username: 'required',
			password: 'required'
		},
		messages: {
			username: {
				required: 'You must enter your username'
			},
			password: 'Your must enter your password'
		},
		success: function(label) {
			
		}
    });
});