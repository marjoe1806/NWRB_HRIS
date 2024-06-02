<?php 
	$readonly = "";
	if($key == "viewMobileUserConfig")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Location <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<select class="location_id form-control" id="location_id" data-live-search="true" <?php echo $readonly ?>>
                            <option value="" selected></option>
                            <?php foreach ($locations as $k => $v) : ?>
                                <option value="<?php echo $v['id']?>"><?php echo $v['name']?></option>
                            <?php endforeach; ?>
                            
                        </select>
                	</div>
            	</div>
            </div>
			<?php if($key == "viewMobileUserConfig"):?>
			<div class="col-md-12" style="pointer-events:none;">
				<div class="form-group form-float">
					<label class="form-label">Employee <span class="text-danger">*</span></label>
					<div class="form-line employee_select">
						<select class="employee_id form-control" id="employee_id" name="employee_id" data-live-search="true" readonly>
							<option value=""></option>
						</select>
					</div>
				</div>
			</div>
			<?php else:?>
				<div class="col-md-12">
				<div class="form-group form-float">
					<label class="form-label">Employee <span class="text-danger">*</span></label>
					<div class="form-line employee_select">
						<select class="employee_id form-control" id="employee_id" name="employee_id" data-live-search="true" readonly>
							<option value=""></option>
						</select>
					</div>
				</div>
			</div>
			<?php endif;?>
			<div class="col-md-12">
				<div class="form-group form-float">
					<label class="form-label">Position </label>
					<div class="form-line">
						<input type="text" class="position_name form-control" id="position_name" aria-required="true" readonly>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group form-float">
					<label class="form-label">Department <span class="text-danger">*</span></label>
					<div class="form-line">
						<input type="text" class="department_name required form-control" id="department_name" required="" aria-required="true" readonly>
					</div>
				</div>
			</div>
		</div>
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addMobileUserConfig"): ?>
    		<button class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Create</span>
	        </button>
    	<?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>

