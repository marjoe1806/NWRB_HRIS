<?php //var_dump($_SESSION);die(); ?>
<style>
	/*PROFILE WIDGET*/
	.profile_widget{
		color: #fff;
		padding: 30px 40px 10px 40px;
		border-radius: 4px;
		overflow: hidden;
		text-align: center;
		background-size: cover;
		background-position: center;
		background-color: #0e1b36;
		background-repeat: no-repeat;
		background-image: url("<?php echo base_url(); ?>assets/custom/images/widget_bg.jpg");
		box-shadow: 0 3px 10px rgba(0, 0, 0, 0.6);
	}
	.pw_avatar{
		width: 150px;
		height: 150px;
		overflow: hidden;
		border-radius: 150px;
		border: 3px solid #fff;
		margin: 0 auto 35px auto;
	}
	.pw_avatar img{
		max-width: 100%;
		height: auto;
	}
	.pw_info{
		margin: 0 0 100px 0;
	}
	.pw_info h1{
		margin: 0 0 8px 0;
		font-size: 24px;
	}
	.pw_info h2{
		margin: 0 0 10px 0;
		font-size: 19px;
		color: #678ec4;
	}
	.pw_tag{
		margin: 0 0 10px 0;
	}
	.pw_info p{
		margin: 0;
		font-size: 14px;
	}
</style>

<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
	    <div class="card">
	        <div class="header bg-blue">
	            <h2>
	               	User Profile <small>Update your profile here...</small>
	            </h2>
	        </div>
	        <div class="body">
	            <form id="updateUserProfile" method="POST" novalidate="novalidate" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateUserProfile'; ?>">
	            	<input type="hidden" name="userid" id ="userid" value="<?php echo Helper::get('userid'); ?>">
	            	<div class="row">
		            	<div class="col-md-6">
		                    <div class="form-group form-float">
		                    	<label class="form-label">Username <span class="text-danger">*</span></label>
		                        <div class="form-line">
		                            <input type="text" id="username" class="required form-control" name="username" required="" aria-required="true" value="<?php echo Helper::get('username'); ?>" readonly>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-md-6">
		                    <div class="form-group form-float">
	                    		<label class="form-label">Email Address <span class="text-danger">*</span></label>
	                    		<div class="form-line focused">
	                    			<input type="text" class="email required form-control" id="email" name="email" required="" aria-required="true" value="<?php echo Helper::get('email'); ?>" readonly>
	                    		</div>
	                    	</div>
	                    </div>
	                </div>
	                <div class="row">
	                	<div class="col-md-12">
	                		<div class="form-group form-float">
	                    		<label class="form-label">Employee <span class="text-danger">*</span></label>
	                    		<div class="form-line focused">
	                    			<input type="text" class="employee_name required form-control" id="employee_name" name="employee_name" required="" aria-required="true" readonly value="<?php echo Helper::get('first_name')." ".Helper::get('last_name') ; ?>">
	                    		</div>
	                    	</div>
	                	</div>
	                </div>
	                <div class="row">
	                    <div class="col-md-6">
	                    	<div class="form-group form-float">
	                    		<label class="form-label">Position <span class="text-danger">*</span></label>
	                    		<div class="form-line focused">
	                    			<input type="text" class="employee_position required form-control" id="employee_position" name="employee_position" value="<?php echo Helper::get('employee_position'); ?>" required="" aria-required="true" readonly>
	                    		</div>
	                    	</div>
	                    </div>
	                    <div class="col-md-6">
	                    	<div class="form-group form-float">
	                    		<label class="form-label">Division <span class="text-danger">*</span></label>
	                    		<div class="form-line focused">
	                    			<input type="text" class="employee_division required form-control" id="employee_division" name="employee_division"  value="<?php echo Helper::get('employee_division'); ?>" required="" aria-required="true" readonly>
	                    		</div>
	                    	</div>
	                    </div>
	                </div>
	                <div class="row">
                        <div class="col-sm-12 text-right">
                            <!-- <button type="submit" class="updateBtn btn btn-primary waves-effect" 
                            data-division="<?php echo Helper::get('division'); ?>"
                            data-section="<?php echo Helper::get('section'); ?>"
                            data-position="<?php echo Helper::get('position'); ?>"
                            data-userlevel="<?php echo Helper::get('userlevel'); ?>">
                                <i class="material-icons">mode_edit</i>
                                <span>UPDATE PROFILE</span>
                            </button> -->
                            <a class="changePasswordForm" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/changePasswordForm'; ?>" data-id="<?php echo Helper::get('userid'); ?>">
	                            <button type="button" class="btn btn-danger waves-effect">
	                                <i class="material-icons">lock</i>
	                                <span>CHANGE PASSWORD</span>
	                            </button>
                            </a>
                        </div>
                    </div>
                </form>
	        </div>
	    </div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
		<div class="profile_widget" >
			<a class="hover-image" href="javascript:void(0);">
		      <div class="pw_avatar">
		      	<?php (Helper::get('photopath') != "" && Helper::get('photopath') != "n/a")?$photo = Helper::get('photopath'): $photo = base_url()."assets/custom/images/account_circle_grey_192x192.png"; ?>
		        <img class="image_photo"  src="<?php echo $photo; ?>" alt="" />
		      </div><!-- /.pw_avatar -->
		      <div class="pw_avatar uploadicon" style="background-color:rgba(0, 0, 0, .5);margin-top:-185px;display:none;">
		        <span style="position:relative;top:56px;">
		      		<i class="material-icons">camera_alt</i>
		      	</span>
		      	<form id="upload-profile" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateUserPhoto'; ?>">
			      	<input type="hidden" name="userid" value="<?php echo Helper::get('userid'); ?>">
			      	<input id="profile-photo" type="file" style="display:none;" name="files" accept="image/*" class="changephoto">
		      	</form>
		      </div><!-- /.pw_avatar -->
		    </a>
	      <div class="pw_info" >
	      	
	        <h1><?php echo Helper::get('employee_name'); ?></h1>
	        <h2><?php echo Helper::get('employee_position'); ?></h2>
	        <div class="pw_tag" >
	        </div><!-- /.pw_tag -->
	        <p class="text-center">
	        	<?php echo Helper::get('employee_division'); ?>
	        </p>

	      </div><!-- /.pw_info -->
    	</div><!-- /.profile_widget -->
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/userprofile/userprofile.js"></script>