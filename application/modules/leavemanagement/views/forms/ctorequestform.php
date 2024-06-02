<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
	<div class="form-elements-container">
		<!-- <hr> -->
			<center><h4 class="text-info">APPLICATION FOR AVAILMENT OF <br> COMPENSATORY TIME OFF (CTO)</h4></center>
		<hr>
		<input type="hidden" name="id" value="" class="id">
		<input type="hidden" id="employee_number" name="employee_number" value="<?php echo $_SESSION['employee_number']; ?>" class="employee_number">

		<div class="row clearfix"> 
		   	<div class="col-md-6" style="pointer-events: none">
	            <label class="form-label">Employee Name</label>
                <div class="form-group">
                	<div class="form-line employee_select">
                		<select  class="employee_id  form-control" id="employee_id" name="employee_id" data-live-search="true" readonly>
							<option value=""></option>
						</select>
                	</div>
            	</div>
	        </div>
		   	<div class="col-md-6" style="pointer-events: none">
		      	<label class="form-label">Division/Unit</label>
      			<div class="form-group">
			        <div class="form-line division_select">
			            <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" readonly>
			                <option value=""></option>
			            </select>
			        </div>
			    </div>
		   	</div>
		</div>

		<div class="row clearfix" style="pointer-events: none">
			<?php if($key !== "addCTORequestForm"): ?>
			<!-- <div class="col-md-4" style="display:none;">
                <label class="form-label">Control No. <span class="text-danger">*</span></label> 
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="control_no form-control" name="control_no" id="control_no" required>
					</div>
				</div>
            </div> -->
			<?php endif; ?>
            <div class="col-md-6" style="pointer-events: none">
                <label class="form-label">Position</label>
				<div class="form-group">
                	<div class="form-line position_select" style="display: none">
                		<select class="position_id  form-control" id="position_id" name="position_id" data-live-search="true" readonly>
							<option value=""></option>
						</select>
                	</div>
                	<div class="form-line position_input" style="display: none">
						<input type="text" class="position_id form-control" name="position_id" id="position_id" readonly="readonly">
                	</div>
            	</div>
            </div>
            <div class="col-md-6" style="pointer-events: none">
                <label class="form-label">Date of Filing</label>
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="filing_date form-control" name="filing_date" id="filing_date" value="<?php echo date("F d, Y"); ?>" readonly="readonly">
					</div>
				</div>
            </div>
		</div>

		<hr>
		<center><h5 class="text-info">DETAILS OF APPLICATION</h5></center>

		<div class="row clearfix">
			<div class="col-md-12">
				<div class="row">
					<?php if(isset($data['employee'])): ?>
					<div class="col-md-2">
						<div class="form-group ">
							<label class="form-label"><small>Total Offset: </small></label>
							<input type="text" class="form-control totalOffset" name="total_offset" id="total_offset" data-offset-hrs ="<?php  echo $data['employee']['totaloffsetHrs']; ?>" data-offset-mins ="<?php  echo $data['employee']['totaloffsetMins']; ?>" value="<?php echo sprintf('%02d:%02d', $data['employee']['totaloffsetHrs'], $data['employee']['totaloffsetMins']); ?>" readonly>
						</div>
					</div>
					<?php endif; ?>
					<div class="col-md-2 inclusiveDates">
		                <label class="form-label"><small>No. of Working Days: </small></label>
		                <div class="form-group">
		                	<div class="form-line">
		                		<input type="number" value="1" class="number_of_days form-control" name="number_of_days" id="number_of_days" required readonly>
		                	</div>
		            	</div>
		            </div>
					<div class="col-md-2"> 
						<div id="no_of_hrs_content">
							<label class="form-label"><small>No. of Hours:</small> <span class="text-danger">*</span></label>
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="no_of_hrs form-control" name="no_of_hrs" min="4" pattern="[0-9]+" id="no_of_hrs" required>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3" style="display:none;"> 
						<div id="no_of_mins_content">
							<label class="form-label"><small>No. of Minutes:</small> <span class="text-danger">*</span></label>
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="no_of_mins form-control" name="no_of_mins" min="0" max="59" id="no_of_mins" value="0" required>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<label class="form-label"><small>Inclusive Date Range</small> </label>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="days_of_leave inclusive_dates form-control" name="inclusive_dates" placeholder="yyyy-mm-dd" required>
							</div>
						</div>
					</div>
				</div>
			</div>			
		</div>
	</div>
	<div class="text-right" style="width:100%;">
    	<?php if($key == "addCTORequest"): ?>
    		<button id="saveCTORequest" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add Record</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateCTORequest"): ?>
	        <button id="updateCTORequest" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update Record</span>
	        </button>
        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>