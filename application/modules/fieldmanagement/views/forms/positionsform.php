<?php 
	$readonly = "";
	if($key == "viewPositionsDetails")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" name="id" class="id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<div class="row clearfix">
			<div class="col-md-12">
	            <label class="form-label">Pay Basis</label>
	            <div class="form-group">
	                <div class="form-line pay_basis_select">
	                    <select class="pay_basis form-control" name="pay_basis" id="pay_basis" data-live-search="true">
	                        <option value=""></option>
	                    </select>
	                </div>
	            </div>
	        </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Salary Grade <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line salary_grade_select">
                        <select class="salary_grade_id form-control required" name="salary_grade_id" id="salary_grade_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Salary Grade Steps<span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line salary_grade_step_select">
                        <select class="salary_grade_step_id form-control required" name="salary_grade_step_id" id="salary_grade_step_id" data-live-search="true">
                            <option value=""></option>
                        </select>
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
                <label class="form-label">Plantilla Item No <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="code" id="code" class="code form-control" required <?php echo $readonly ?>>
                	</div>
            	</div>
            </div>
		</div>
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Position Title <span class="text-danger">*</span></label>
                <div class="form-group">
	                <div class="form-line">
						<input type="text" name="name" id="name" class="name form-control" required <?php echo $readonly ?>>
						<!-- <div class="help-block with-errors"></div> -->
	            	</div>
            	</div>
            </div>
		</div>
        <div class="row">
            <div class="col-md-12">
                <label class="text-danger">
                Note:<br>
                Biometrix Break - if employee need to break in and break out<br>
                DTR - if employee need to submit dtr<br>
                Leave Recommendation - if the employee dont have recommendation officer<br>
                Drivers - if the position is driver
                </label>
            <div>
        </div>
        <hr>
		<div class="row clearfix">
			<!-- <div class="col-md-6">
                <label class="form-label">Biometrix Break In & Out</label>
            </div> -->
			<div class="col-md-6">
                <div class="form-group">
                    <input type="checkbox" name="is_break" id="is_break" class="is_break form-control chk">
                    <label class="form-label">Biometrix Break</label>
                </div>
            </div>
			<div class="col-md-6">
                <div class="form-group">
                    <input type="checkbox" name="is_leave_recom" id="is_leave_recom" class="is_leave_recom form-control chk">
                    <label class="form-label">Leave Recommendation</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="checkbox" name="is_dtr" id="is_dtr" class="is_dtr form-control chk">
                    <label class="form-label">DTR</label>
                </div>
            </div>
             <div class="col-md-6">
                <div class="form-group">
                    <input type="checkbox" name="is_driver" id="is_driver" class="is_driver form-control chk">
                    <label class="form-label">Drivers</label>
                </div>
            </div>
		</div>
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addPositions"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updatePositions"): ?>
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

