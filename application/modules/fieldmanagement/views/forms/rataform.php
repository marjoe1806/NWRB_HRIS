<?php 
	$readonly = "";
	if($key == "viewRATADetails")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" name="id" class="id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Position <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line position_select">
                        <select class="position_id form-control required" name="position_id" id="position_id" data-live-search="true" >
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Rep. Allowance <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="rep_allowance" id="rep_allowance" class="rep_allowance form-control currency" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Transpo. Allowance<span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="transpo_allowance" id="transpo_allowance" class="transpo_allowance form-control currency" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addRATA"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateRATA"): ?>
	        <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update</span>
	        </button>
        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>

