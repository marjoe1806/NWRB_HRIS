<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta http-equiv='cache-control' content='no-cache'> <!--clear cache-->
		<meta http-equiv='expires' content='0'><!--clear cache with some expire time -->
		<meta http-equiv='pragma' content='no-cache'> <!-- no Cache -->
		<title><?php echo isset($title) ? $title : "Sign In | NWRB - CHRIS"; ?></title>
		<!-- Favicon-->
		<link rel="icon" href="<?php echo base_url(); ?>assets/custom/images/nwrb.png" type="image/png" />
	
		<!-- Google Fonts -->		
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/fonts/googlefonts.css" />
		
		<!-- Bootstrap Core Css -->
		<link href="<?php echo base_url(); ?>assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
		<!-- Waves Effect Css -->
		<link href="<?php echo base_url(); ?>assets/plugins/node-waves/waves.css" rel="stylesheet" />
		<!-- Animation Css -->
		<link href="<?php echo base_url(); ?>assets/plugins/animate-css/animate.css" rel="stylesheet" />
		<!-- Custom Css -->
		<link href="<?php echo base_url(); ?>assets/custom/css/style.css" rel="stylesheet" />
		<!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
		<link href="<?php echo base_url(); ?>assets/custom/css/themes/all-themes.css" rel="stylesheet" />
		<!-- Jquery Confirm Css -->
		<link href="<?php echo base_url(); ?>assets/plugins/jquery-confirm/jquery-confirm.min.css" rel="stylesheet" />
		
		<!-- icheck -->
		<link href="<?php echo base_url(); ?>assets/plugins/iCheck/skins/square/blue.css" rel="stylesheet" />
		<link href="<?php echo base_url(); ?>assets/plugins/iCheck/skins/square/grey.css" rel="stylesheet" />
		<link href="<?php echo base_url(); ?>assets/plugins/iCheck/skins/minimal/grey.css" rel="stylesheet" />
		<!-- Jquery Core Js -->
		<script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
		<!-- Custom body background -->
		<style type="text/css">
			body {
			    /* background: -webkit-linear-gradient(#629057, #cefb1b) !important;
			    background: -o-linear-gradient(#629057, #cefb1b) !important;
			    background: -moz-linear-gradient(#629057, #cefb1b) !important;
			    background: linear-gradient(#3F51B5, #FFEB3B) !important;
			    background-size: 100% 299% !important;
			    background-position: left -299px !important; */
				
				/* background-color: gray;
				background-image: url(<?php echo base_url(); ?>assets/custom/images/tc_login_bg.png);
				background-size: 100% 100%;
				background-repeat: no-repeat;
				background-attachment: fixed; */

				/* background: rgba(255,255,255,1);
				background: rgba(255,255,255,1);
				background: -moz-linear-gradient(left, rgba(255,255,255,1) 0%, rgba(234,195,77,0.92) 47%, rgba(235,62,10,0.83) 100%);
				background: -webkit-gradient(left top, right top, color-stop(0%, rgba(255,255,255,1)), color-stop(47%, rgba(234,195,77,0.92)), color-stop(100%, rgba(235,62,10,0.83)));
				background: -webkit-linear-gradient(left, rgba(255,255,255,1) 0%, rgba(234,195,77,0.92) 47%, rgba(235,62,10,0.83) 100%);
				background: -o-linear-gradient(left, rgba(255,255,255,1) 0%, rgba(234,195,77,0.92) 47%, rgba(235,62,10,0.83) 100%);
				background: -ms-linear-gradient(left, rgba(255,255,255,1) 0%, rgba(234,195,77,0.92) 47%, rgba(235,62,10,0.83) 100%);
				background: linear-gradient(to right, rgba(255,255,255,1) 0%, rgba(234,195,77,0.92) 47%, rgba(235,62,10,0.83) 100%);
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#eb3e0a', GradientType=1 ); */

			/* height: 100px; */
            background: -webkit-linear-gradient(left top, #47ecdc , #1d1dc6);
            background: -o-linear-gradient(bottom right, #459ce7, #1d1dc6);
            background: -moz-linear-gradient(bottom right, #459ce7, #1d1dc6);
            background: linear-gradient(to bottom right, #47ecdc , #1d1dc6);
			background-size: 100% 299% !important;
			background-position: left -299px !important; */


			}
		</style>
	</head>

	<body class="login-page">
		<?php echo $content; ?>
	
		<!-- Bootstrap Core Js -->
		<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.js"></script>
		<!-- Waves Effect Plugin Js -->
		<script src="<?php echo base_url(); ?>assets/plugins/node-waves/waves.js"></script>
		<!-- Validation Plugin Js -->
		<script src="<?php echo base_url(); ?>assets/plugins/jquery-validation/jquery.validate.js"></script>
		<!-- icheck -->
		<script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.js"></script>
		<!-- Custom Js -->
		<script src="<?php echo base_url(); ?>assets/custom/js/admin.js"></script>
		<!-- Commons -->
		<script src="<?php echo base_url(); ?>assets/commons/js/commons.js"></script>
		<!-- Jquery Confirm Plugin -->
		<script src="<?php echo base_url(); ?>assets/plugins/jquery-confirm/jquery-confirm.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/custom/js/pages/examples/sign-in.js"></script>
	</body>

</html>