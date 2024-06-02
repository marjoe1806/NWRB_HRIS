<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
	<div class="form-elements-container">
		<hr>
			<?php if($key == "updateOvertimeTransactions"):?>
				<center><h4 class="text-info">Update Overtime Request Permission Form</h4></center>
				<?php else:?>
					<center><h4 class="text-info">Overtime Request Permission Form</h4></center>
					<?php endif;?>
		<hr>
		<input type="hidden" name="id" value="" class="id">
		<input type="hidden" name="employee_id" value="" class="employee_id">
		<input type="hidden" name="division_id" value="" class="division_id">
		<input type="hidden" name="location_id" value="" class="location_id">
		<input type="hidden" name="is_active" value="" class="is_active">
		
		<div class="row clearfix" id="emp_info">
			<div class="col-md-4">
                <label class="form-label">Control No.</label>
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="control_no form-control" name="control_no" id="control_no" required>
					</div>
				</div>
            </div>

			<div class="col-md-8">
                <label class="form-label">Employee No.</label>
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="position_id form-control" name="position_id" id="position_id" >
					</div>
				</div>
            </div>
		</div>

		<div class="row clearfix">
		   <div class="col-md-4">
		      <label class="form-label">Division/Unit</label>
		      <div class="form-group form-float">
		         <div class="form-line">
		            <input type="text" class="control_no form-control" name="control_no" id="control_no" >
		         </div>
		      </div>
		   </div>
		   <div class="col-md-8">
		      <label class="form-label">Name</label>
		      <div class="row">
		         <div class="col-md-4">
		            <div class="form-group form-float">
		               <div class="form-line">		
		                  <input type="text" class="last_name form-control" name="last_name" id="last_name" placeholder="Last">	
		               </div>
		            </div>
		         </div>
		         <div class="col-md-4">
		            <div class="form-group form-float">
		               <div class="form-line">		
		                  <input type="text" class="first_name form-control" name="first_name" id="first_name" placeholder="First">	
		               </div>
		            </div>
		         </div>
		         <div class="col-md-4">
		            <div class="form-group form-float">
		               <div class="form-line">		
		                  <input type="text" class="middle_name form-control" name="middle_name" id="middle_name" placeholder="Middle">	
		               </div>
		            </div>
		         </div>
		      </div>
		   </div>
		</div>

		<hr>

		<div class="row clearfix">
			<div class="col-md-12">
                <div class="row">
                	<div class="col-md-12">
                		<div>
								<div class="col-md-12">
								 	<div class="container-fluid">
										<div class="row">
										 	<div class="col-md-4">
											 <input name="purpose" type="radio" id="radio_paid" class="purpose with-gap radio-col-green" value="paid" required/>
											 <label for="radio_paid">WITH PAY</label>
											</div>
											<div class="col-md-4">
											<input name="purpose" type="radio" id="radio_cto" class="purpose with-gap radio-col-green" value="cto" required/>
											<label for="radio_cto">FOR CTO</label>
											</div>
										</div>

									</div>
								</div>
							</div>
								<div class="row">
									<div class="col-md-12"> 
										<div id="activity_name_content">
											<label class="form-label" style="font-size: 1.25rem">
												<small>Purpose:<small></label>
											<div class="form-group form-float">
												<div class="form-line">
													<input type="text" class="activity_name form-control" name="activity_name" id="activity_name">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4"> 
										<div id="activity_date_content">
											<label class="form-label" style="font-size: 1.25rem">
												<small>Date of Activity:<small></label>
											<div class="form-group form-float">
												<div class="form-line">
													<input type="text" class="transaction_date form-control" name="transaction_date" id="transaction_date">
												</div>
											</div> 
										</div>
									</div>
									<div class="col-md-8"> 
										<div id="activity_venue_content">
											<label class="form-label" style="font-size: 1.25rem">
												<small>Location:<small></label>
											<div class="form-group form-float">
												<div class="form-line">
													<input type="text" class="location form-control" name="location" id="location">
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-6"> 
										<div id="">
											<label class="form-label" style="font-size: 1.25rem">
												<small>Time In:<small></label>
											<div class="form-group form-float">
												<div class="form-line">
													<input type="text" class="time_in timepicker form-control" name="time_in" id="time_in" required>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" name="overtime_transaction_time" id="overtime_transaction_time" value="<?php echo date('H:i:s')?>" class="overtime_transaction_time timepicker form-control">
									<div class="col-md-6"> 
										<div id="">
											<label class="form-label" style="font-size: 1.25rem">
												<small>Time Out:<small></label>
											<div class="form-group form-float">
												<div class="form-line">
													<input type="text" name="time_out" id="time_out" class="time_out over timepicker form-control" required>
												</div>
											</div>
										</div>
									</div>
								</div>
						</div>
                	</div>
                	<hr>
                </div>
            </div>
		</div>
		

	</div>
	<div class="text-right" style="width:100%;">
	<?php if($key == "updateOvertimeTransactions"):?>
	    <button id="updateOvertimeTransactionsForm" class="btn btn-primary btn-sm waves-effect" type="submit">
	        <i class="material-icons">save</i><span> Update Record</span>
	    </button>
    <?php endif; ?>
    	<button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
	
</form>