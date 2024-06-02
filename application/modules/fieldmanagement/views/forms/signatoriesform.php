<?php 
	$readonly = "";
	if($key == "viewSignatoriesDetails")
		$readonly = "disabled";
?>
<?php if($module == "Signatories") : ?>

<form enctype="multipart/form-data" id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" name="id" class="id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<!-- <div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Signatory No.<span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="signatory_no" id="signatory_no" class="signatory_no form-control" required <?php echo $readonly ?>>
                	</div>
            	</div>
            </div>
		</div> -->
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Signatory Description<span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="signatory" id="signatory" class="signatory form-control" required <?php echo $readonly ?>>
                	</div>
            	</div>
            </div>
		</div>
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Service / Unit / Division <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line division_select">
                        <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
		</div>
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Employee <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line employee_select">
                        <select class="employee_id form-control " name="employee_id" id="employee_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
		</div>
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addSignatories"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateSignatories"): ?>
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

<?php endif; ?>

<?php if($module == "Head Signatories") : ?>
<form enctype="multipart/form-data" id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
        <input type="hidden" name="id" class="id" value="">
        <!-- <div id="form-user" role="form" data-toggle="validator"> -->
        <div class="row clearfix">
            <div class="col-md-12">
                <label class="form-label">Signatory <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="signatory" id="signatory" class="signatory form-control" required <?php echo $readonly ?>>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <label class="form-label">Position <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="employee_id" id="employee_id" class="employee_id form-control" required <?php echo $readonly ?>>
                        <!-- <div class="help-block with-errors"></div> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <label class="form-label">Payroll Group <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line payroll_grouping_select">
                        <select class="payroll_grouping_id form-control " name="payroll_grouping_id" id="payroll_grouping_id" data-live-search="true" >
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <label class="form-label">E-Signature </label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="file" id="file" name="file" class="file form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="display:none">
                <img name="signature_img" id="signature_img" class="signature_img" src="#" alt="your image" style="width:100%" />
                <br><br><br>
            </div>
        </div>  
    </div>
    <div class="text-right" style="width:100%;">
        <?php if($key == "addHeadSignatories"): ?>
            <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
                <i class="material-icons">add</i><span> Add</span>
            </button>
        <?php endif; ?>
        <?php if($key == "updateHeadSignatories"): ?>
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
<?php endif; ?>

