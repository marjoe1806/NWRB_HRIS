<?php 
	$readonly = "";
	if($key == "viewPayrollSettingsDeductionOptionsDetails")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" class="id" name="id" value="">
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Deductions <span class="text-danger">*</span></label>
                <div class="form-group">
	                <div class="form-line">
	            		<input type="text" name="deductions" id="deductions" class="deductions form-control" required <?php echo $readonly ?>>
	            	</div>
            	</div>
            </div>
		</div>
        <div class="row clearfix">
            <div class="col-md-12">
                <label class="form-label">Order No. <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0"  name="order_no" id="order_no" class="order_no form-control" required <?php echo $readonly ?>>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <label class="form-label">Monthly / Semi</label>
                <div class="form-group">
                    <div class="form-line">
                        <select class="monthly_semi form-control" name="monthly_semi" id="monthly_semi" data-live-search="true">
                            <option value="1st Period">1st Period</option>
                            <option value="2nd Period">2nd Period</option>
                            <option value="Per Period">Per Period</option>
                            <option value="N/A">N/A</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addPayrollSettingsDeductionOptions"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updatePayrollSettingsDeductionOptions"): ?>
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

