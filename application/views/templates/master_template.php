<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>
		<?php echo isset($title) ? $title : "Home | NWRB - CHRIS"; ?>
	</title>
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
	<!-- Multi Select Css -->
	<link href="<?php echo base_url(); ?>assets/plugins/multi-select/css/multi-select.css" rel="stylesheet">
	<!-- Bootstrap Select Css -->
	<link href="<?php echo base_url(); ?>assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
	<!-- Jquery Confirm Css -->
	<link href="<?php echo base_url(); ?>assets/plugins/jquery-confirm/jquery-confirm.min.css" rel="stylesheet" />
	<!-- Custom Css -->
	<link href="<?php echo base_url(); ?>assets/custom/css/style.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>assets/custom/css/workflow.css" rel="stylesheet" />
	<!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
	<link href="<?php echo base_url(); ?>assets/custom/css/themes/all-themes.css" rel="stylesheet" />
	<!-- JQuery DataTable Css -->
	<link href="<?php echo base_url(); ?>assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>assets/plugins/jquery-datatable/extensions/responsive/responsive.dataTables.min.css" rel="stylesheet">
	<!-- Date Time Picker -->
	<link href="<?php echo base_url(); ?>assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
	 rel="stylesheet" />
	<!-- Font-awesome -->
	<link href="<?php echo base_url(); ?>assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" />
	<!-- Date Range Picker -->
	<link href="<?php echo base_url(); ?>assets/plugins/daterange/daterangepicker.css" rel="stylesheet" />
	<!-- icheck -->
	<link href="<?php echo base_url(); ?>assets/plugins/iCheck/skins/square/blue.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>assets/plugins/iCheck/skins/square/grey.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>assets/plugins/iCheck/skins/minimal/grey.css" rel="stylesheet" />
	<!-- Sweetalert -->
	<link href="<?php echo base_url(); ?>assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
	<!-- LightGallery -->
	<link href="<?php echo base_url(); ?>assets/plugins/light-gallery/css/lightgallery.css" rel="stylesheet">
	<!-- Jquery Core Js -->
	<script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
	<!-- table exporter Js -->
	<script src="<?php echo base_url(); ?>assets/plugins/tablexport/tableExport.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/tablexport/libs/FileSaver/FileSaver.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/tablexport/libs/html2canvas/html2canvas.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/tablexport/libs/jsPDF/jspdf.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/tablexport/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
	<!-- Commons -->
	<script src="<?php echo base_url(); ?>assets/commons/js/commons.js"></script>
	<script src="<?php echo base_url(); ?>assets/commons/js/autologout.js"></script>
	<!-- Jquery Validation Plugin js -->
	<script src="<?php echo base_url(); ?>assets/plugins/jquery-validation/jquery.validate.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/jquery-validation/jquery.validate.unobtrusive.dynamic.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/jquery-validation/additional-methods.js"></script>
	<!-- Jquery Steps Plugin js -->
	<script src="<?php echo base_url(); ?>assets/plugins/jquery-steps/jquery.steps.js"></script>
	<!-- Field Master -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/fieldmanagement/fieldmanagementmaster.js"></script>
	<!-- Input Mask Plugin Js -->
	<script src="<?php echo base_url(); ?>assets/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>
	<style type="text/css">
		.green-background {
			background-color: white !important;
		}

		@media only screen and (min-width: 771px) {
			#navbar-collapse ul.dropdown-menu {
				top: 100px;
			}

			nav ul ul:before {
				position: absolute;
				top: -9px;
				left: 90%;
				display: inline-block;
				border-right: 9px solid transparent;
				border-bottom: 9px solid #EEE;
				border-left: 9px solid transparent;
				content: '';
			}
		}

		@media only screen and (max-width: 770px) {
			#navbar-collapse ul.dropdown-menu {
				top: 100px;
				left: 50px;
			}

			nav ul ul:before {
				position: absolute;
				top: -9px;
				left: 6%;
				display: inline-block;
				border-right: 9px solid transparent;
				border-bottom: 9px solid #EEE;
				border-left: 9px solid transparent;
				content: '';
			}
		}

		hr {
			border: 0;
			height: 1px;
			background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
		}

		input[type=number] {
			text-align: right
		}
		.daterangepicker {
		    position: absolute;
		    top: 18px; 
		    left: 20px; 
		    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
		    box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
		    -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
		    -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
		    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
		    border-bottom: 1px solid #ddd;
		}
		.full-screen {
		  width: 95%;
		}
		div.dropdown-menu.open { width: 100%; } ul.dropdown-menu.inner>li>a { white-space: initial; }

	</style>
</head>

<body class="ls-closed">
	<input type="hidden" class="employee_id_hide" value="<?php echo Helper::get('employee_id'); ?>">
	<input type = "hidden" class="leave_grouping_id_hide" name="leave_grouping_id_hide" id="leave_grouping_id_hide" value="<?php echo Helper::get('leave_grouping_id'); ?>">
	<input type = "hidden" class="payroll_grouping_id_hide" name="payroll_grouping_id_hide" id="payroll_grouping_id_hide" value="<?php echo Helper::get('payroll_grouping_id'); ?>">
	<input type = "hidden" class="leave_tracking_all_access" name="leave_tracking_all_access" id="leave_tracking_all_access" value="<?php echo Helper::role(ModuleRels::LEAVE_TRACKING_ALL_ACCESS)?1:0; ?>">
	<!-- Page Loader -->
	<div class="page-loader-wrapper">
		<div class="loader">
			<div class="preloader">
				<div class="spinner-layer pl-blue-grey">
					<div class="circle-clipper left">
						<div class="circle"></div>
					</div>
					<div class="circle-clipper right">
						<div class="circle"></div>
					</div>
				</div>
			</div>
			<p>Please wait...</p>
		</div>
	</div>
	<!-- #END# Page Loader -->
	<!-- Overlay For Sidebars -->
	<div class="overlay"></div>
	<!-- #END# Overlay For Sidebars -->
	<!-- Top Bar -->
	<nav class="navbar green-background">
		<div class="container-fluid">
			<div class="navbar-header">
				<a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse"
				 aria-expanded="false"></a>
				<a class="bars text-white header-bar" id="header-bar"></a>
				<button style="display:none" id="trigger-burger" type="button">Trigger Button</button>
				<a class="navbar-brand" href="<?php echo base_url(); ?>">
					<div style="display: inline;">
						<img style="margin-top: -15px !important;" src="<?php echo base_url(); ?>assets/custom/images/nwrb.png" width=50
						 alt="" />
						&nbsp;
						<big style="color: black;position: absolute;" class="font-24">
							<b style="color:#31708f">Human Resource Information System</b>
						</big>
					</div>
				</a>
			</div>
		</div>
	</nav>
	<!-- #Top Bar -->
	<section>
		<!-- Left Sidebar -->
		<aside id="leftsidebar" class="sidebar">
			<!-- User Info -->
			<div class="user-info">
				<div class="image">
					<?php if(Helper::get('photopath') == "test" || Helper::get('photopath') == "n/a" || Helper::get('photopath') == "N/A" || Helper::get('photopath') == ""): ?>
					<img src="<?php echo base_url(); ?>assets/custom/images/account_circle_grey_192x192.png" width="48" height="48"
					 alt="User" />
					<?php else: ?>
					<img src="<?php echo Helper::get('photopath'); ?>" class="image_photo" width="48" height="48" alt="User" />
					<?php endif; ?>
				</div>
				<div class="info-container">
					<div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?php echo Helper::get('first_name').' '. Helper::get('last_name') ; ?>
					</div>
					<!-- <div class="email">
						<?php //echo Helper::get('userlevelname'); ?>
					</div> -->
					<div class="btn-group user-helper-dropdown">
						<i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
						<ul class="dropdown-menu pull-right">
							<li><a href="<?php echo base_url(); ?>userprofile/Userprofile/"><i class="material-icons">person</i>Profile</a></li>
							<li role="seperator" class="divider"></li>
							<li><a href="<?php echo base_url(); ?>session/logout"><i class="material-icons">input</i>Sign Off</a></li>
						</ul>
					</div>
				</div>
			</div>
			<!-- #User Info -->
			<!-- Menu -->
			<?php echo $menu; ?>
			<!-- #Menu -->
			<!-- Footer -->
			<div class="legal">
				<div class="copyright">
					HRIS &copy; 2021 <a href="http://www.mobilemoney.ph" target="_blank">Telcom Live Content, Inc.</a>
				</div>
				<div class="version">
					<b>Version: </b> 1.0
				</div>
			</div>
			<!-- #Footer -->
		</aside>
		<!-- #END# Left Sidebar -->
	</section>

	<section class="content">
		<?php echo $content; ?>
	<!-- Modal -->
	<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header bg-blue">
					<button type="button" class="close" data-dismiss="modal"><i class="material-icons">close</i></button>
					<h4 class="modal-title" id="defaultModalLabel"><i class="material-icons text-danger" style="font-size:30px;">error</i>
					</h4>
				</div>
				<div class="modal-body">
					<center>
						<h3 class="text-warning">Content Not Found!</h3>
					</center>
				</div>
			</div>
		</div>
	</div>
	<!-- ./Modal -->
	</section>

	<!-- Module script(s) -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/home/home.js"></script>
	<!-- Bootstrap Core Js -->
	<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.js"></script>
	<!-- Slimscroll Plugin Js -->
	<script src="<?php echo base_url(); ?>assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
	<!-- Waves Effect Plugin Js -->
	<script src="<?php echo base_url(); ?>assets/plugins/node-waves/waves.js"></script>
	<!-- Jquery Confirm Plugin -->
	<script src="<?php echo base_url(); ?>assets/plugins/jquery-confirm/jquery-confirm.min.js"></script>
	<!-- Sparkline Chart Plugin Js -->
	<!-- Jquery DataTable Plugin Js -->
	<script src="<?php echo base_url(); ?>assets/plugins/jquery-datatable/jquery.dataTables.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/jquery-datatable/extensions/responsive/dataTables.responsive.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/jquery-datatable/dataTables.rowsGroup.js"></script>
	<!-- Moment Plugin Js -->
	<script src="<?php echo base_url(); ?>assets/plugins/momentjs/moment.js"></script>
	<!-- Date time picker -->
	<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
	<!-- Date Range picker -->
	<script src="<?php echo base_url(); ?>assets/plugins/daterange/daterangepicker.js"></script>
	<!-- icheck -->
	<script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.js"></script>
	<!-- Autosize Plugin Js -->
    <script src="<?php echo base_url(); ?>assets/plugins/autosize/autosize.js"></script>
	<!-- Sweetalert -->
	<script src="<?php echo base_url(); ?>assets/plugins/sweetalert/sweetalert-dev.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/sweetalert/sweetalert.min.js"></script>
	<!-- Multi Select Plugin Js -->
	<script src="<?php echo base_url(); ?>assets/plugins/multi-select/js/jquery.multi-select.js"></script>
	<!-- Select Plugin Js -->
	<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>
	<!-- Alphanum Js -->
	<script src="<?php echo base_url(); ?>assets/plugins/alphanum/js/alphanum.js"></script>
	<!-- Lightgallery JS -->
	<script src="<?php echo base_url(); ?>assets/plugins/light-gallery/js/lightgallery.js"></script>
	<!-- Webcam JS -->
	<script src="<?php echo base_url(); ?>assets/plugins/webcam-js/webcam.min.js"></script>
	<!-- Custom Js -->
	<script src="<?php echo base_url(); ?>assets/custom/js/admin.js"></script>
	<!-- Demo Js -->
	<script src="<?php echo base_url(); ?>assets/custom/js/demo.js"></script>
	<script type="text/javascript">
		$(document).on("click", "#trigger-burger", function() {
			$('#header-bar').click();
		});
	</script>

	</body>

</html>
