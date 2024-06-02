<form id="<?php echo $key; ?>"
	action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
	<div class="form-elements-container">
		<div class="row clearfix">
			<input type="hidden" id="employee_id" class="employee_id" name="employee_id">
			<div class="col-md-9">
				<label class="form-label">Selected Employee
					<span class="text-danger">*</span>
				</label>
				<div class="form-group" style="pointer-events: none">
					<div class="form-line">
						<input id="employee_name" class="form-control employee_name" type="text" disabled>
						<!-- <select class="employee_id form-control" name="employee_id" id="employee_id" data-live-search="true" required>
							<option disabled selected></option>
						</select> -->
					</div>
				</div>
			</div>
			<div class="col-md-3" style="pointer-events: none">
				<label class="form-label">Date
					<span class="text-danger">*</span>
				</label>
				<div class="form-group">
					<div class="form-line">
						<input type="text" name="transaction_date" id="transaction_date"
							class="transaction_date datepicker form-control" required>
					</div>
				</div>
			</div>
		</div>
		<?php if($key == "updateDailyTimeRecordMaintenance"): ?>
		<h5 class="text-center text-primary">OFFICIAL RECORD</h5>
		<div class="row clearfix" style="pointer-events: none">
			<div class="col-md-2">
				<label class="form-label">Time In</label>
				<div class="form-group">
					<div class="form-line">
						<input type="time" name="transaction_time[]" id="dtr_am_arrival"
							class="dtr_am_arrival timepicker form-control" disabled>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<label class="form-label">Lunch Out</label>
				<div class="form-group">
					<div class="form-line">
						<input type="time" name="transaction_time[]" id="dtr_am_departure"
							class="dtr_am_departure timepicker form-control" disabled>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<label class="form-label">Lunch In</label>
				<div class="form-group">
					<div class="form-line">
						<input type="time" name="transaction_time[]" id="dtr_pm_arrival"
							class="dtr_pm_arrival timepicker form-control" disabled>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<label class="form-label">Time Out</label>
				<div class="form-group">
					<div class="form-line">
						<input type="time" name="transaction_time[]" id="dtr_pm_departure"
							class="dtr_pm_departure timepicker form-control" disabled>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<label class="form-label">OT In</label>
				<div class="form-group">
					<div class="form-line">
						<input type="time" name="transaction_time[]" id="dtr_overtime_in"
							class="dtr_overtime_in timepicker form-control" disabled>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<label class="form-label">OT Out</label>
				<div class="form-group">
					<div class="form-line">
						<input type="time" name="transaction_time[]" id="dtr_overtime_out"
							class="dtr_overtime_out timepicker form-control" disabled>
					</div>
				</div>
			</div>
		</div>
		<h5 class="text-center text-success">ADJUSTMENTS</h5>
		<?php endif; ?>
		<div class="row clearfix">
			<div class="col-md-2" id="am_arrival_div">
				<label class="form-label">Time In</label>
				<div class="form-group">
					<div class="form-line">
						<input type="time" name="transaction_time[]" id="dtr_adjustment_am_arrival"
							class="dtr_adjustment_am_arrival timepicker form-control">
					</div>
				</div>
			</div>
			<div class="col-md-2" id="am_departure_div">
				<label class="form-label">Lunch Out</label>
				<div class="form-group">
					<div class="form-line">
						<input type="time" name="transaction_time[]" id="dtr_adjustment_am_departure"
							class="dtr_adjustment_am_departure timepicker form-control">
					</div>
				</div>
			</div>
			<div class="col-md-2" id="pm_arrival_div">
				<label class="form-label" >Lunch In</label>
				<div class="form-group">
					<div class="form-line">
						<input type="time" name="transaction_time[]" id="dtr_adjustment_pm_arrival"
							class="dtr_adjustment_pm_arrival timepicker form-control">
					</div>
				</div>
			</div>
			<div class="col-md-2" id="pm_departure_div">
				<label class="form-label" >Time Out</label>
				<div class="form-group">
					<div class="form-line">
						<input type="time" name="transaction_time[]" id="dtr_adjustment_pm_departure"
							class="dtr_adjustment_pm_departure timepicker form-control">
					</div>
				</div>
			</div>
			<div class="col-md-2" id="overtime_in_div">
				<label class="form-label" >OT In</label>
				<div class="form-group">
					<div class="form-line">
						<input type="time" name="transaction_time[]" id="dtr_adjustment_overtime_in"
							class="dtr_adjustment_overtime_in timepicker form-control">
					</div>
				</div>
			</div>
			<div class="col-md-2" id="overtime_out_div">
				<label class="form-label" >OT Out</label>
				<div class="form-group">
					<div class="form-line">
						<input type="time" name="transaction_time[]" id="dtr_adjustment_overtime_out"
							class="dtr_adjustment_overtime_out timepicker form-control">
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix">
			<!-- <div class="col-md-12">
				<label class="form-label">Remarks</label>
				<div class="form-group">
					<div class="form-line">
						<textarea name="remarks" id="remarks" class="remarks form-control"></textarea>
					</div>
				</div>
			</div> -->
			<div class="col-md-6">
				<label class="form-label">Remarks
					<span class="text-danger">*</span>
				</label>
				<div class="form-group">
					<div class="form-line">
						<select class="remarks form-control" name="remarks" id="remarks" data-live-search="true" required>
							<!-- <option disabled selected></option> -->
							<!--<option value="defective">Defective</option>
							<option value="offsetting">Offsetting</option> -->
							<option value="Special Order" selected>Special Order</option>
							<option value="Time-Off" selected>Time Off</option>
							<option value="Timeplacement">Time Placement</option>
							<option value="OT w/ Certification">OT with Certification</option>
							<option value="WFH">Work From Home</option>
							<option value="LWOP">LWOP</option>
							<option value="AWOL">AWOL</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-6" id="remarks_specific_div" style="display:none;">
				<label class="form-label">Special Order Number <span class="text-danger">*</span></label>
				<div class="form-group">
					<div class="form-line">
						<input type="text" name="remarks_specific" id="remarks_specific" class="remarks_specific form-control" required>
					</div>
				</div>
			</div>
		</div>
		<div class="text-right" style="width:100%;">
			<?php if($key == "addDailyTimeRecordMaintenance"): ?>
			<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
				<i class="material-icons">add</i>
				<span> Add</span>
			</button>
			<?php endif; ?>
			<?php if($key == "updateDailyTimeRecordMaintenance"): ?>
			<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
				<i class="material-icons">save</i>
				<span> Update</span>
			</button>
			<?php endif; ?>
			<button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" type="button">
				<i class="material-icons">close</i>
				<span> Close</span>
			</button>
		</div>
</form>
