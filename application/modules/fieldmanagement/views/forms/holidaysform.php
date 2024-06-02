<?php
	$readonly = "";
	if($key == "viewHolidaysDetails")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" class="id" name="id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<div class="row clearfix">
            <div class="col-md-12">
                <label class="form-label">Holiday Type</label>
                <div class="form-group">
                    <div class="form-line">
                        <select class="holiday_type form-control" name="holiday_type" id="holiday_type" data-live-search="true">
                            <!-- <option value="Non-special">Non-special</option> -->
                            <option value="Regular">Regular Holiday</option>
                            <!-- <option value="Legal">Legal</option> -->
                            <option value="Special">Special Non-Working Holiday</option>
                            <option value="Suspension">Work Suspension</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
		<div class="row clearfix">
			<div class="col-md-6">
                <label class="form-label">Date <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="date" id="date" class="date form-control" required <?php echo $readonly ?>>
                	</div>
            	</div>
            </div>
			<div class="col-md-6">
                <label class="form-label">Time <span class="text-danger">*</span> (if Work Suspension)</label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="time_suspension" id="time_suspension" class="time_suspension form-control" <?php echo $readonly ?>>
                	</div>
            	</div>
            </div>
		</div>
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Description <span class="text-danger">*</span></label>
                <div class="form-group">
	                <div class="form-line">
	            		<textarea name="holiday_name" rows="3" id="holiday_name" class="required holiday_name form-control" <?php echo $readonly ?>></textarea>
						<!-- <div class="help-block with-errors"></div> -->
	            	</div>
            	</div>
            </div>
		</div>
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addHolidays"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateHolidays"): ?>
	        <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update</span>
	        </button>
	        <!-- <?php //if($status == "INACTIVE"): ?>
		        <a id="activateUserLevelConfig" class="activateUserLevelConfig" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'activateUserLevelConfig'; ?>">
		            <button class="btn btn-success btn-sm waves-effect" type="button">
		                <i class="material-icons">visibility</i><span> Activate</span>
		            </button>
	            </a>
	        <?php //endif; ?>
	        <?php //if($status == "ACTIVE"): ?>
            <a id="deactivateUserLevelConfig" class="deactivateUserLevelConfig" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'deactivateUserLevelConfig'; ?>">
	            <button  class="btn btn-danger btn-sm waves-effect" type="button">
	                <i class="material-icons">visibility_off</i><span> Deactivate</span>
	            </button>
            </a>
            <?php //endif; ?> -->

        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>

