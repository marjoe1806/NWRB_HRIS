$(function() {
	var findIP = new Promise(r=>{
		var w=window,
		a=new (w.RTCPeerConnection||w.mozRTCPeerConnection||w.webkitRTCPeerConnection)({iceServers:[]}),
		b=()=>{};		
		a.createDataChannel("");
		a.createOffer(c=>a.setLocalDescription(c,b,b),b);
		a.onicecandidate=c=>{
			try{
				c.candidate.candidate.match(/([0-9]{1,3}(\.[0-9]{1,3}){3}|[a-f0-9]{1,4}(:[a-f0-9]{1,4}){7})/g).forEach(r)
			} catch(e){
				
			}
		}
	});
	
	findIP.then(function(ip) {
		$("#ip").val(ip);
	}, function(err) {
		console.log(err);
	});
	$(document).on('click','#back_login',function(e){
		e.preventDefault();
		$('.password_content').slideUp();
		$('.username_content').slideDown();
	})
	$(document).on('click','#verify_username',function(e){
		e.preventDefault();
		url = commons.baseurl +"session/Session/validateUsername";
		$.ajax({
			type: "POST",
			data:{username:$('#username').val()},
			url: url,
			dataType: "json",
			success: function (result) {
				if(result.Code != "0"){
					$('#username-error').remove();
     				$('.username_content .form-line').addClass("error");
     				$('.username_content .input-group').append(
     						'<label id="username-error" class="error" for="username">'+result.Message+'</label>');
				}
				else{
					$('.password_content').slideDown();
					$('.username_content').slideUp();
				}
			},
			error: function (result) {
				$.alert({
					title: '<label class="text-danger">Failed</label>',
					content: 'There was an error in the connection. Please contact the administrator for updates.'
				});
			}
		});
	})
	$(document).on('submit','#sign_in',function(e){
		e.preventDefault();
		my = $(this)
		url = my.attr("action");
		$.ajax({
			type: "POST",
			data:{username:$('#username').val(), password:$('#password').val(), ip:$('#ip').val()},
			url: url,
			dataType: "json",
			success: function (result) {
				if(result.Code != "0"){
					$('#password-error').remove();
     				$('.password_content .form-line').addClass("error");
     				$('.password_content .input-group').append(
     						'<label id="password-error" class="error" for="password">'+result.Message+'</label>');
				}
				else{
					location.reload();
				}
			},
			error: function (result) {
				$.alert({
					title: '<label class="text-danger">Failed</label>',
					content: 'There was an error in the connection. Please contact the administrator for updates.'
				});
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

function showPassword() {
	var x = document.getElementById("password");
	if (x.type === "password") {
	  x.type = "text";
	} else {
	  x.type = "password";
	}
  }