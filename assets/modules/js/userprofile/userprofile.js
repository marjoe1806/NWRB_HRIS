$(function(){
	var base_url = commons.baseurl;

    // getEmployees.activate()
    // getFields.employee();
	var validate = $("#updateUserProfile").validate
	({
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
    $(document).on('mouseenter','.hover-image',function(e){
        //alert('oo')
        $('.uploadicon').fadeIn(100);
    })
    $(document).on('mouseleave','.hover-image',function(e){
        //alert('oo')
        $('.uploadicon').fadeOut(100);
    })
    $(document).on('click','.hover-image',function(e){
        $('#profile-photo')[0].click();
    })
    $(document).on('change','#profile-photo',function(e){
        form = $('#upload-profile');
        url = form.attr('action');
        $.confirm({
            title: '<label class="text-warning">Confirm!</label>',
            content: "Are you sure you want to change profile photo?",
            type: 'orange',
            buttons: {
                confirm: {
                    btnClass: 'btn-blue',
                    action: function () {
                        //Code here
                        $.confirm({
                            content: function () {
                                var self = this;
                                data = new FormData(form[0]);
                                return $.ajax({
                                    url: url,
                                    data: data,
                                    type:'POST',
                                    contentType: false,
                                    processData: false,
                                    dataType: "json",
                                    success: function(result){
                                        json = result;
                                        console.log(json);
                                        if(json.Code == "0")
                                        {
                                            self.setContent(json.Message);
                                            self.setTitle('<label class="text-success">Success</label>');
                                            $('.image_photo').attr('src',json.Data+'?number='+(Math.floor(Math.random()*6)+1))
                                        }
                                        else
                                        {
                                            //Failed
                                            self.setContent(json.Message);
                                            self.setTitle('<label class="text-danger">Failed</label>');
                                        }
                                    },
                                    error: function(result){
                                        self.setContent("There was an error in the connection. Please contact the administrator for updates.");
                                        self.setTitle('<label class="text-danger">Failed</label>');
                                    }
                                });
                            }
                        });
                    }

                },
                cancel: function () {
                }
            }
        })
    })
	//Ajax non-forms
    $(document).on('click','.changePasswordForm',function(e){
        e.preventDefault();
        me = $(this)
        id = me.attr('data-id');
        url = me.attr('href');  
        $.ajax({
            type: "POST",
            url: url,
            data: {id:id},
            dataType: "json",
            success: function(result){
                page = me.attr('id');
                if(result.hasOwnProperty("key")){
                    switch(result.key){
                        case 'changePass':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $("#myModal .modal-dialog").css("width", "55%");
                            $('#myModal .modal-title').html('Change Password');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            
                            break;
                    }
                    $("#"+result.key).validate({
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
                }
            },
            error: function(result){
                self.setContent("There was an error in the connection. Please contact the administrator for updates.");
                self.setTitle('<label class="text-danger">Failed</label>');
            }
        });
    })
	$(document).on('submit','#updateUserProfile,#frmChangePass',function(e){
        e.preventDefault();
        form = $(this)
        content = "Are you sure you want to proceed?";
        // if(form.attr('id') == "updateUserProfile"){
        //     content = "Are you sure you want to update your profile?";
        // }
        // if(form.attr('id') == "frmChangePass"){
        //     content = "Are you sure you want to change your password?";
        //     if($('input[name="newpass"]').val() != $('input[name="newpass2"]').val()){
        //     	$.alert({
        //     		title:'<label class="text-danger">Failed</label>',
        //     		content:'Confirm and New password does not match.'
        //     	})
        //     	return false;
        //     }
        // }
        url = form.attr('action');
        
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
                                    data: form.serialize(),
                                    dataType: "json",
                                    success: function(result){
                                        if(result.hasOwnProperty("key")){
                                            if(result.Code == "0"){
                                                if(result.hasOwnProperty("key")){
                                                    switch(result.key){
                                                    	case 'changePass':
                                                        case 'updateUserProfile':
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            $('#myModal').modal('hide');
                                                            break;
                                                    }
                                                }  
                                            }
                                            else{
                                                self.setContent(result.Message);
                                                self.setTitle('<label class="text-danger">Failed</label>');
                                            }
                                        }
                                    },
                                    error: function(result){
                                        self.setContent("There was an error in the connection. Please contact the administrator for updates.");
                                        self.setTitle('<label class="text-danger">Failed</label>');
                                    }
                                });
                            }
                        });
                    }

                },
                cancel: function () {
                }
            }
        });
    })
	me = $('#updateUserProfile').find('.updateBtn');
})

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