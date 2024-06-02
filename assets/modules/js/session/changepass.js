$(function() {
	$("#frmChangePass").validate({
        rules:
        {
            ".required":
            {
                required: true
            },
            ".email":
            {
                required: true,
                email: true
            }
        },
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        }
    });
	$("#bCancel").click(function() {
		logout();
	});
	$(document).on('submit','#frmChangePass',function(e){
		e.preventDefault();
		form = $(this)
		url = $(this).attr('action');
		
		var lowerCaseLetters = new RegExp('[a-z]');
		var upperCaseLetters = new RegExp('[A-Z]');
		var numbers = new RegExp('[0-9]');
        password1 = $("#password1").val();
        password2 = $("#password2").val();
		
		if (password1.length < 8) {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Password must be 8 characters or longer.",
            });
            return false;
        }
		if (!password1.match(lowerCaseLetters)) {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Password must contain at least 1 lowercase alphabetical character.",
            });
            return false;
        }
		if (!password1.match(upperCaseLetters)) {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Password must contain at least 1 uppercase alphabetical character.",
            });
            return false;
        }
		if (!password1.match(numbers)) {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "Password must contain at least 1 numerical alphabetical character.",
            });
            return false;
        }
		if (password1 != password2) {
            $.alert({
                title: '<label class="text-danger">Failed</label>',
                content: "New password don't match.",
            });
            return false;
        }

		$.confirm({
		    title: '<label class="text-warning">Confirm</label>',
		    content: 'Are you sure about your new password?',
		    type: 'orange',
		    buttons: {
		        yes: {
		            btnClass: 'btn-blue',
		            action: function () {
		                //Code here
		                if(form.length == 1) {
							var resultCode;
		                	$.confirm({
		                	    content: function () {
		                	        var self = this;
    	                        	data = new FormData(form[0]);
    	                        	return $.ajax({
										url: url,
    	                        		data: data,
    	                        		type:'POST',
										dataType: 'JSON',
    	                        		contentType: false,
    	                        		processData: false
    	                        	}).done(function(result){
										resultCode = result.Code;
    	                        		if(result.Code == 0) {
											self.setTitle('<label class="text-success">Success</label>');
    	                        		}
    	                        		else{
											self.setTitle('<label class="text-danger">Failed</label>');
    	                        		}
										self.setContent(result.Message);
    	                        	}).fail(function(){
                                        self.setContent('Url Loading Failed');
                                    });
		                	    }, buttons: {
									ok: {
										action: function() {
											if(resultCode == 0) {
												form[0].reset();
												logout();
											}
										}
									}
								}
		                	});
		                	
		                }
		            }

		        },
		        cancel: function () {}
		    }
		});
	});
	

	$(".chk").iCheck("destroy");
	$(".chk").iCheck({
	  checkboxClass: "icheckbox_square-grey",
	});

	$(document).on("ifChecked", "#showPassword", function () {
		showPassword();
	});

	$(document).on("ifUnchecked", "#showPassword", function () {
		showPassword();
	});
});
	function logout() {
		window.location.href = commons.baseurl + 'session/logout';
	}
	var myInput = document.getElementById("password1");
	var letter = document.getElementById("letter");
	var capital = document.getElementById("capital");
	var number = document.getElementById("number");
	var length = document.getElementById("length");

	// When the user starts to type something inside the password field
	myInput.onkeyup = function() {
	// Validate lowercase letters
	var lowerCaseLetters = /[a-z]/g;
	if(myInput.value.match(lowerCaseLetters)) {
		letter.classList.remove("invalid");
		letter.classList.add("valid");
	} else {
		letter.classList.remove("valid");
		letter.classList.add("invalid");
	}

  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myInput.value.match(upperCaseLetters)) {
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) {
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }

  // Validate length
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}

function showPassword() {
	var x = document.getElementById("password");
	var y = document.getElementById("password1");
	var z = document.getElementById("password2");
	if (x.type === "password") {
	  x.type = "text";
	} else {
	  x.type = "password";
	}
	if (y.type === "password") {
		y.type = "text";
	} else {
		y.type = "password";
	}
	if (z.type === "password") {
		z.type = "text";
	} else {
		z.type = "password";
	}
  }