<?php 
	$readonly = "";
	if($key == "viewMobileDevicesDetails")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" class="id" name="id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">IMEI <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="imei" id="imei" class="imei form-control" required <?php echo $readonly ?>>
                	</div>
            	</div>
            </div>
		</div>
		<div class="row clearfix">
			<div class="col-md-12">
	            <label class="form-label">Satellite Location <span class="text-danger">*</span></label>
	            <div class="form-group">
	                <div class="form-line location_select">
	                    <select class="location_id form-control " name="location_id" id="location_id" data-live-search="true">
	                        <option value=""></option>
	                    </select>
	                </div>
	            </div>
	        </div>
        </div>
        <div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Device Code <span class="text-danger"></span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="device_code" id="device_code" class="device_code form-control" <?php echo $readonly ?>>
                	</div>
            	</div>
            </div>
		</div>
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Description</label>
                <div class="form-group">
	                <div class="form-line">
	            		<textarea name="description" rows="5" id="description" class="required description form-control" <?php echo $readonly ?>></textarea>
						<!-- <div class="help-block with-errors"></div> -->
	            	</div>
            	</div>
            </div>
		</div>
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addMobileDevices"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateMobileDevices"): ?>
	        <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update</span>
	        </button>
	        <!-- <?php if($status == "INACTIVE"): ?>
		        <a id="activateUserLevelConfig" class="activateUserLevelConfig" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'activateUserLevelConfig'; ?>">
		            <button class="btn btn-success btn-sm waves-effect" type="button">
		                <i class="material-icons">visibility</i><span> Activate</span>
		            </button>
	            </a>
	        <?php endif; ?>
	        <?php if($status == "ACTIVE"): ?>
            <a id="deactivateUserLevelConfig" class="deactivateUserLevelConfig" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'deactivateUserLevelConfig'; ?>">
	            <button  class="btn btn-danger btn-sm waves-effect" type="button">
	                <i class="material-icons">visibility_off</i><span> Deactivate</span>
	            </button>
            </a>
            <?php endif; ?> -->
            
        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>

