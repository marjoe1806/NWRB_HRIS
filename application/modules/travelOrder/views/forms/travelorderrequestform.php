<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
	<div class="form-elements-container">
		<hr>
			<center><h4 class="text-info">Travel Order Form</h4></center>
		<hr>
		<br>
		<br>
		<input type="hidden" name="id" value="" class="id">
		<input type="hidden" id="employee_number" name="employee_number" value="<?php echo $_SESSION['employee_number']; ?>" class="employee_number">

		<div class="row clearfix">
			<div class="col-md-4">
	            <label class="form-label">Division</label>
                <div class="form-group">
                	<div class="form-line division_select">
                		<select class="division_id  form-control" id="division_id" name="division_id" data-live-search="true" readonly>
							<option value=""></option>
						</select>
                	</div>
            	</div>
	        </div>
		   	<div class="col-md-8">
	            <label class="form-label">Passenger / Driver </label>
                <div class="form-group">
                	<div class="form-line employee_select">
                		<select class="employee_id  form-control" id="employee_id" name="employee_id" data-live-search="true" readonly >
							<option value=""></option>
						</select>
                	</div>
            	</div>
	        </div>
	      <!--   <div class="col-md-4">
		      	<label class="form-label">Driver</label>
      			<div class="form-group">
			        <div class="form-line">
			        	<input type="text" class="driver form-control" name="driver" id="driver" required readonly>
			        </div>
			    </div>
		   	</div> -->
		</div>
		<div class="row clearfix">
			<div class="col-md-4 travel_order_no_div">
                <label class="form-label">Travel Order No.</label> 
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="travel_order_no form-control" name="travel_order_no" id="travel_order_no" required readonly>
					</div>
				</div>
            </div>
            <div class="col-md-4 vehicle_no_div">
                <label class="form-label">Vehicle No.</label>
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="vehicle_no form-control" name="vehicle_no" id="vehicle_no" required readonly>
					</div>
				</div>
            </div>
              <div class="col-md-4 driver_div">
                <label class="form-label">Driver</label>
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="driver form-control" name="driver" id="driver" required readonly>
					</div>
				</div>
            </div>
		</div>
		<div class="row clearfix">
        <div class="col-md-12">
                <label class="form-label">Destination/Location</label>
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="location form-control" name="location" id="location" required readonly>
					</div>
				</div>
      </div>
		</div>
		
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Purpose</label> 
				<div class="form-group form-float">
					<div class="form-line">
						<textarea class="officialpurpose form-control" name="officialpurpose" id="officialpurpose" rows="3" cols="50" required readonly> </textarea>
					</div>
				</div>
            </div>
		</div>
		<div class="col-md-12">
			<div class="col-md-6">
			<div class="row clearfix">
		 		<div class="col-md-12">
					<input name="purpose" type="radio" id="radio_return_office" class="purpose with-gap radio-col-green" value="return" readonly/>
					<label for="radio_return_office">Return to the Office</label>
					<br>
					<input name="purpose" type="radio" id="radio_not_return_office" class="purpose with-gap radio-col-green" value="not_return" readonly/>
					<label for="radio_not_return_office">Not return to the Office<br><br><br></label>
				</div>
			</div>
			</div>
			<div class="col-md-6">
			<div id="vehicle_content">
						<label class="form-label" style="font-size: 1.25rem;text-align:left;">
						<small>No / With Vehicle</small> <span class="text-danger">*</span> 
						</label>
						<div class="row">
							<div class="col-md-6">
								<input name="is_vehicle" type="radio" id="radio_2" class="is_vehicle with-gap radio-col-green" value="2" />
								<label for="radio_2">With Vehicle</label><br>
							</div>
							<div class="col-md-6">
							<input name="is_vehicle" type="radio" id="radio_3" class="is_vehicle with-gap radio-col-green" value="3" />
								<label for="radio_3">No Vehicle</label>
								<br>
							</div>
						</div>
					</div>
			</div>
		</div>
		<div class="row clearfix reason_hide" style="display: none;">
			<div class="col-md-12">
                <label class="form-label">Reason</label> 
				<div class="form-group form-float">
					<div class="form-line">
						<textarea type="text" class="reason form-control" name="reason" id="reason" rows="3" cols="50" readonly> </textarea>
					</div>
				</div>
            </div>
		</div>
		<div class="row clearfix">
			<div class="col-md-4">
                <label class="form-label">Travel Date/Time</label> 
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="duration form-control" name="duration" id="duration" required readonly>
					</div>
				</div>
            </div>
            <div class="col-md-4">
                <label class="form-label">No of days</label> 
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="no_days form-control" name="no_days" id="no_days" readonly>
					</div>
				</div>
            </div>
		</div>
		
		
	</div>
	<div class="text-right" style="width:100%;">
    	<?php if($key == "addTravelOrder"): ?>
    		<button id="saveTravelOrder" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add Record</span>
	        </button>
    	<?php endif; ?>
    	<!-- <?php if($key == "updateTravelOrder"): ?>
	        <button id="updateTravelOrderRequest" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update Record</span>
	        </button>
        <?php endif; ?> -->
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>