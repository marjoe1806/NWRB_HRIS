<?php 
	$userid = (isset($data->Data->details[0]->userid))?$data->Data->details[0]->userid:"";
	$entityid = (isset($data->Data->details[0]->entityid))?$data->Data->details[0]->entityid:"";
	$username = (isset($data->Data->details[0]->username))?$data->Data->details[0]->username:"";
	
	$firstname = (isset($data->Data->details[0]->first_name))?$data->Data->details[0]->first_name:"";
	$middlename = (isset($data->Data->details[0]->middle_name) && $data->Data->details[0]->middle_name != "n/a")?$data->Data->details[0]->middle_name:"";
	$lastname = (isset($data->Data->details[0]->last_name))?$data->Data->details[0]->last_name:"";
	$email = (isset($data->Data->details[0]->email))?$data->Data->details[0]->email:"";
	$password = (isset($data->Data->details[0]->password))?$data->Data->details[0]->password:"";

	$division = (isset($data->Data->details[0]->department_name))?$data->Data->details[0]->department_name:"";
	$userlevelname = (isset($data->Data->details[0]->userlevelname))?$data->Data->details[0]->userlevelname:"";
	$status = (isset($data->Data->details[0]->status))?$data->Data->details[0]->status:"";
	$fullname = $firstname.' '.$middlename.' '.$lastname;


	$position_id = (isset($data->Data->details[0]->position_id))?$data->Data->details[0]->position_id:"";
	$position = (isset($data->Data->details[0]->position_name))?$data->Data->details[0]->position_name:"";

	if($position_id != "" && !is_numeric($position_id)){
		$position = $position_id;
	}

    $display = "show";
    if($key == "updateUserConfig"){
        $display = "none";
    }
?>
<?php if($key == "updateUserConfig"): ?>
<style>
	a:hover{
		text-decoration: none;	
	}
</style>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <!-- <tr>
            <th class="text-primary">Employee ID</th>
            <td class="employee-id"><?php echo $entityid; ?></td>
        </tr> -->
        <tr>
            <th class="text-primary">Username</th>
            <td class="username"><?php echo $username; ?></td>
        </tr>
        <tr>
            <th class="text-primary">Full Name</th>
            <td class="fullname"><?php echo $fullname; ?></td>
        </tr>
        <tr>
            <th class="text-primary">User Level</th>
            <td class="userlevelname"><?php echo $userlevelname; ?></td>
        </tr>
        <tr>
            <th class="text-primary">Email Address</th>
            <td class="email"><?php echo $email; ?></td>
        </tr>
       <!--  <tr>
            <th class="text-primary">Gender</th>
            <td class="fullname"><?php echo $gender; ?></td>
        </tr> -->
        <tr>
            <th class="text-primary">Position</th>
            <td class="position"><?php echo $position; ?></td>
        </tr>
        <tr>
            <th class="text-primary">Service / Division / Unit</th>
            <td class="division"><?php echo $division; ?></td>
        </tr>
        <!-- <tr>
            <th class="text-primary">Section</th>
            <td class="fullname"><?php echo $section; ?></td>
        </tr> -->
        <tr>
            <th class="text-primary">Status</th>
            <td class="status"><label class="<?php echo ($status == "ACTIVE")?"text-success":"text-danger"; ?>"><?php echo $status; ?></label></td>
        </tr>
    </table>
</div>
<?php endif; ?>

<!-- <form id="frmChangePass" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2) ?>/changePass" method="POST" role="form" data-parsley-validate>
	<div class="form-changepass-container" style="display:<?php echo 'none'; ?>">
		<input type="hidden" name="userid">
		<input type="hidden" name="username">
		<fieldset>
			<div class="msg">
	            Enter your new password
	        </div>	
	        <div class="input-group">
	            <span class="input-group-addon">
	                <i class="material-icons">lock</i>
	            </span>
	            <div class="form-line">
	                <input id="password" type="password" class="newpass required form-control" name="newpass" placeholder="New Password" required autofocus />
	            </div>
	        </div>
			<div class="input-group">
	            <span class="input-group-addon">
	                <i class="material-icons">lock</i>
	            </span>
	            <div class="form-line">
	                <input id="password2" type="password" class="newpass2 required form-control" name="newpass2" placeholder="Verify New Password" required autofocus />
	            </div>
	        </div>
		</fieldset>
	</div>
</form> -->
<form id="<?php echo $key ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST"  autocomplete="off">
    <div class="form-elements-container" style="display:<?php echo $display; ?>">
    	<input type="hidden" name="userid" value="<?php echo $userid; ?>">
    	<input type="hidden" name="status" value="<?php echo $status; ?>">
		<div class="row">
			<div class="col-md-12">
				<label class="form-label">Department <span class="text-danger">*</span></label>
			    <div class="form-group">
			        <div class="form-line division_select">
			            <select class="division_id form-control required " name="division_id" id="division_id" data-live-search="true" >
			                <option value=""></option>
			            </select>
			        </div>
			    </div>
			</div>
			<div class="col-md-12">
				<div class="form-group form-float">
					<label class="form-label">Employee <span class="text-danger">*</span></label>
					<div class="form-line employee_select <?php echo ($key=='updateUserConfig')?'focused':''; ?>">
						<select class="employee_id form-control required" id="employee_id" name="employee_id" data-live-search="true" readonly>
							<option value=""></option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group form-float">
					<label class="form-label">Position <!-- <span class="text-danger">*</span> --></label>
					<div class="form-line <?php echo ($key=='updateUserConfig')?'focused':''; ?>">
						<input type="text" class="position_name form-control" id="position_name" aria-required="true" readonly>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group form-float">
					<label class="form-label">Username <small>(Pls. use your email as username.)</small> <span class="text-danger">*</span></label>
					<div class="form-line <?php echo ($key=='updateUserConfig')?'focused':''; ?>">
						<input type="text" class="username email required form-control font-italic" style="text-transform:uppercase;" id="username" name="username" required="" aria-required="true" value="<?php echo $username; ?>">
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group form-float">
					<label class="form-label">Password <span class="text-danger">*</span></label>
					<div class="form-line <?php echo ($key=='updateUserConfig')?'focused':''; ?>">
						<input type="password" class="password required form-control font-italic" style="text-transform:uppercase;" id="password" name="password" required="" aria-required="true" value="">
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group form-float">
					<label class="form-label">Email <span class="text-danger">*</span></label>
					<div class="form-line <?php echo ($key=='updateUserConfig')?'focused':''; ?>">
						<input type="text" class="email required form-control font-italic" id="email" name="email" required="" aria-required="true" value="">
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group form-float">
					<label class="form-label">User Level <span class="text-danger">*</span></label>
					<div class="form-line <?php echo ($key=='updateUserConfig')?'focused':''; ?>">
						<select class="userlevelid required form-control" name="userlevelid" data-live-search="true">
							<option value=""></option>
							<?php 
							if(isset($userlevels) && sizeof($userlevels) > 0){
								foreach($userlevels as $k=>$v){
									echo '<option value="'.$v['userlevelid'].'">'.$v['userlevelname'].'</option>';
								} 
							}
							?>
						</select>
						
					</div>
				</div>
			</div>
		</div>       
    </div>
    <!-- 
    <div class="form-group">
        <input type="checkbox" id="checkbox" name="checkbox">
        <label for="checkbox">Assistant Override</label>
    </div> -->
    <div class="text-right" style="width:100%;">
    	<button id="changeUserPassword" class="btn btn-primary btn-sm waves-effect" type="button" style="display:<?php echo 'none'; ?>"
        	data-toggle="tooltip" data-placement="top" title="Save Password">
            <i class="material-icons">save</i> save
        </button>
        <button id="saveUserConfig" class="btn btn-primary btn-sm waves-effect" type="submit" style="display:<?php echo $display; ?>"
        	data-toggle="tooltip" data-placement="top" title="Save">
            <i class="material-icons">save</i> save
        </button>
        <?php if($key == "updateUserConfig"): ?>
            <button id="showUpdateForm" class="btn bg-blue btn-sm waves-effect" type="button"
            data-toggle="tooltip" data-placement="top" title="Update" >
                <i class="material-icons">edit</i> Edit
            </button>
				<a id="grantAccessUserConfig" class="grantAccessUserConfig" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/grantAccessUserConfig'; ?>"
					data-toggle="tooltip" data-placement="top" title="Grant PDF Access" 
            		data-id="<?php echo $userid; ?>" data-userlevel="<?php echo $userlevelname; ?>">
		            <button class="btn bg-green btn-sm waves-effect" type="button">
		                <i class="material-icons">lock_open</i> Grant PDS Access
		            </button>
	            </a>
            <?php if($status == "INACTIVE"): ?>
            	<a id="activateUserConfig" class="activateUserConfig" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateUserConfig'; ?>"
            		data-toggle="tooltip" data-placement="top" title="Activate" 
            		data-id="<?php echo $userid; ?>" data-userlevel="<?php echo $userlevelname; ?>">
		            <button  class="btn bg-green btn-sm waves-effect" type="button">
		                <i class="material-icons">visibility</i> Activate
		            </button>
	            </a>
            <?php endif ?>
			<?php if($status == "ACTIVE"): ?>
				<a id="deactivateUserConfig" class="deactivateUserConfig" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateUserConfig'; ?>"
					data-toggle="tooltip" data-placement="top" title="Deactivate" 
            		data-id="<?php echo $userid; ?>"
            		data-userlevel="<?php echo $userlevelname; ?>">
		            <button class="btn bg-red btn-sm waves-effect" type="button">
		                <i class="material-icons">visibility_off</i> Deactivate
		            </button>
	            </a>
        	<?php endif; ?>
        	<?php if($status == "LOCKED"): ?>
            	<a id="unlockUserConfig" class="unlockUserConfig" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/unlockUserConfig'; ?>"
            		data-toggle="tooltip" data-placement="top" title="Unlock" 
            		data-id="<?php echo $userid; ?>" data-userlevel="<?php echo $userlevelname; ?>">
		            <button  class="btn bg-green btn-sm waves-effect" type="button">
		                <i class="material-icons">lock_open</i> Unlock
		            </button>
	            </a>
            <?php endif ?>
            <button id="cancelUpdateForm" class="btn btn-warning btn-sm waves-effect" type="button" style="display:none" data-toggle="tooltip" data-placement="top" title="Cancel" >
                <i class="material-icons">close</i> Close
            </button>
            <!-- <a id="forgotUserPassword" class="forgotUserPassword" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/forgotUserPassword'; ?>"
        		data-toggle="tooltip" data-placement="top" title="Reset Password" 
        		data-id="<?php echo $userid; ?>">
	            <button id="resetPassword" class="btn bg-deep-orange btn-sm waves-effect" type="button">
	                <i class="material-icons">refresh</i>
	            </button>
            </a> -->
            <!-- <a id="logoutUser" class="logoutUser" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/logoutUser'; ?>"
            	data-toggle="tooltip" data-placement="top" title="Logout"
        		data-id="<?php echo $userid; ?>">
	            <button class="btn bg-teal btn-sm waves-effect" type="button">
	                <i class="material-icons">power_settings_new</i>
	            </button>
            </a> -->
           <!--  <button id="showChangePasswordForm" class="btn bg-green btn-sm waves-effect" type="button"
            data-toggle="tooltip" data-placement="top" title="Change Password"
            data-id="<?php echo $userid; ?>"
            data-username="<?php echo $username; ?>">
                <i class="material-icons">lock_open</i>
            </button> -->
        <?php endif; ?>
    </div>
</form>

