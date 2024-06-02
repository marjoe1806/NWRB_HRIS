<?php 
	$readonly = "";
	$min_date = date('Y-m-d');

	if($key == "viewCompetencyAccessDetails")
		$readonly = "disabled";
?>

<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" class="id" name="id" value="">
    	<input type="hidden" class="reference" name="reference" value="">
    	<input type="hidden" class="date" name="old_date" value="">
    	<input type="hidden" class="time_start" name="old_start_date" value="">
    	<input type="hidden" class="time_end" name="old_end_date" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<div class="row clearfix">
			<div class="col-md-4">
                <label class="form-label">Test Type <span class="text-danger">*</span></label>
                <div class="form-group">
	                <div class="form-line">
						<select name="type_id" id="type_id" class="form-control type_id" <?php echo $readonly ?>>
						<?php for ($i=0; $i < sizeof($type_info); $i++): ?>
							<option value="<?php echo $type_info[$i]->id; ?>">
							<?php 
								if($type_info[$i]->type == "general"){
									echo "General Ability";
								}else if($type_info[$i]->type == "promotion"){
									echo "2nd Level Promotion";
								}else if($type_info[$i]->type == "ethics"){
									echo "Ethics";
								}
							?>
							</option>
						<?php endfor;?>
						</select>
					</div>
				</div>
            </div>
			<div class="col-md-4">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
						<select name="status" id="status" class="form-control status" <?php echo $readonly ?>>
							<option value="1">Go</option>
							<option value="2">Suspended</option>
							<option value="3">Not Go</option>
						</select>
                	</div>
            	</div>
            </div>
			<div class="col-md-4">
                <label class="form-label">Examination Date <span class="text-danger">*</span></label>
                <div class="form-group">
	                <div class="form-line">
					<input min="<?php echo $min_date ?>" type="date" name="date" placeholder="Enter Date" id="date" class="form-control date" required <?php echo $readonly ?>>
					</div>
				</div>
            </div>
		</div>
		<div class="row clearfix">
			<div class="col-md-4">
                <label class="form-label">Time Start <span class="text-danger">*</span></label>
				<div class="form-group">
					<div class="form-line">
						<input type="time" name="time_start" placeholder="Enter Time" id="time_start" class="form-control time_start" required <?php echo $readonly ?>>
					</div>
				</div>
            </div>
			<div class="col-md-4">
                <label class="form-label">Time End <span class="text-danger">*</span></label>
                <div class="form-group">
	                <div class="form-line">
						<input type="time" name="time_end" placeholder="Enter End" id="time_end" class="form-control time_end" required <?php echo $readonly ?>>
					</div>
				</div>
            </div>
			<div class="col-md-4">
                <label class="form-label">Exam Duration <small>(Minutes)</small> <span class="text-danger">*</span></label>
                <div class="form-group">
	                <div class="form-line">
						<input min="1" max="1440" type="number" name="exam_duration" placeholder="Enter Duration" id="exam_duration" class="form-control exam_duration" required <?php echo $readonly ?>>
					</div>
				</div>
            </div>
		</div>
		<div id="emailDisplayList">
			<label class="form-label">Email Address <span class="text-danger">*</span></label>
			<?php for ($i=0; $i < sizeof($email_list); $i++): ?>
				<?php if($email_list[$i]->status == 1){$dis = 'disabled';$red = 'readonly';}else{$dis = '';$red = '';} ?>
				<div class="row clearfix email_row_<?php echo $i; ?>">
					<div class="col-md-6">
						<div class="form-group">
							<div class="form-line dropdown_list">
								<select name="emailaddress[]" id="emailaddress" class="form-control emailaddress custom-select" <?php echo $readonly ?> <?php echo $red; ?>>
									<option value=""></option>
									<?php for ($b=0; $b < sizeof($employee_list); $b++): ?>
										<?php if($email_list[$i]->emailaddress == $employee_list[$b]->email):?>
										<option selected value="<?php echo $employee_list[$b]->email; ?>"><?php echo $employee_list[$b]->email;?></option>
										<?php else: ?>
										<option value="<?php echo $employee_list[$b]->email; ?>"><?php echo $employee_list[$b]->email;?></option>
										<?php endif; ?>
									<?php endfor;?>
								</select>
							</div>
						</div>
					</div>
					<?php if($readonly == ''): ?>
					<div class="col-md-2" style="text-align: middle">
						<div class="button_div_<?php echo $i; ?>">
							<?php if(sizeof($email_list) == ($i+1)): ?>
							<button data-id="<?php echo $i; ?>"  class="btn btn-primary btn-sm waves-effect addEmailaddress" type="button" style=" min-width: 100%">
								<i class="material-icons">add</i><span> Add</span>
							</button>
							<?php else: ?>
								<button data-id="<?php echo $i; ?>" class="btn btn-danger btn-sm waves-effect removeEmailaddress" type="button" style="min-width: 100%" <?php echo $dis; ?>>
									<i class="material-icons">remove</i><span> Remove</span>
								</button>
							<?php endif; ?>
						</div>
					</div>
					<?php endif; ?>
					<div class="col-md-4">
						<div style="margin-top: 10px;">
							<?php echo ($email_list[$i]->status == 1) ? 'Examination Done' : ''; ?>
						</div>
					</div>
				</div>
			<?php endfor;?>
		</div>
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addCompetencyAccess"): ?>
    		<button class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateCompetencyAccess"): ?>
	        <button class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update</span>
	        </button>
        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>
<script type="text/javascript">
    var global_employee_list = <?php echo json_encode($employee_list); ?>;
</script>