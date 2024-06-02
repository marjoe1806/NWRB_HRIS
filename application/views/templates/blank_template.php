<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<title><?php echo isset($title) ? $title : "NWRB - CHRIS"; ?></title>
		<!-- Favicon-->
		<link rel="icon" href="<?php echo base_url(); ?>assets/custom/images/singlelgalogo.png" type="image/png" />
	
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
		<!-- Jquery Core Js -->
		<script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
		<!-- Custom body background -->
		<style type="text/css">
			body {
			    background: -webkit-linear-gradient(#629057, #cefb1b) !important;
			    background: -o-linear-gradient(#629057, #cefb1b) !important;
			    background: -moz-linear-gradient(#629057, #cefb1b) !important;
			    background: linear-gradient(#3F51B5, #FFEB3B) !important;
			    background-size: 100% 299% !important;
			    background-position: left -299px !important;
			}
		</style>
	</head>

	<body class="bg-blue-grey">
		<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-blue">
                    	<button type="button" class="close" data-dismiss="modal"><i class="material-icons">close</i></button>
                        <h4 class="modal-title" id="defaultModalLabel"><i class="material-icons text-danger" style="font-size:30px;">error</i> </h4>
                    </div>
                    <div class="modal-body">
                    	<center><h3 class="text-warning">Content Not Found!</h3></center>
                    </div>
                </div>
            </div>
        </div>
		<!-- ./Modal -->
		<?php echo $content; ?>
		<!-- Commons -->
		<script src="<?php echo base_url(); ?>assets/commons/js/commons.js"></script>
		<!-- Jquery DataTable Plugin Js -->
	    <script src="<?php echo base_url(); ?>assets/plugins/jquery-datatable/jquery.dataTables.js"></script>
	    <script src="<?php echo base_url(); ?>assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>	
		<!-- Bootstrap Core Js -->
		<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.js"></script>
		<!-- Waves Effect Plugin Js -->
		<script src="<?php echo base_url(); ?>assets/plugins/node-waves/waves.js"></script>
		<!-- Validation Plugin Js -->
		<script src="<?php echo base_url(); ?>assets/plugins/jquery-validation/jquery.validate.js"></script>
		<!-- Custom Js -->
		<script src="<?php echo base_url(); ?>assets/custom/js/admin.js"></script>
		<script src="<?php echo base_url(); ?>assets/custom/js/pages/examples/sign-in.js"></script>
	</body>

</html>