$(function () {
	$("body").addClass('fp-page');
	var form = $("#forgot_password");
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
		success: function(label) {
			console.log(label);
		}
    });
});