<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
	<div class="form-elements-container">
		<hr>
			<center><h4 class="text-info">Official Business Permission Form</h4></center>
		<hr>
		<input type="hidden" name="table1[id]" value="" class="id">
		
		<div class="row clearfix">
			<div class="col-md-4">
                <label class="form-label">Control No.</label>
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="control_no form-control" name="table1[control_no]" id="control_no" required>
					</div>
				</div>
            </div>

			<div class="col-md-8">
                <label class="form-label">Employee No.</label>
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="employee_no form-control" name="table1[employee_no]" id="employee_no" >
					</div>
				</div>
            </div>
		</div>

		<div class="row clearfix">
		   <div class="col-md-4">
		      <label class="form-label">Division/Unit</label>
		      <div class="form-group form-float">
		         <div class="form-line">
		            <input type="text" class="control_no form-control" name="table1[control_no]" id="control_no" >
		         </div>
		      </div>
		   </div>
		   <div class="col-md-8">
		      <label class="form-label">Name</label>
		      <div class="row">
		         <div class="col-md-4">
		            <div class="form-group form-float">
		               <div class="form-line">		
		                  <input type="text" class="last_name form-control" name="table1[last_name]" id="last_name" placeholder="Last">	
		               </div>
		            </div>
		         </div>
		         <div class="col-md-4">
		            <div class="form-group form-float">
		               <div class="form-line">		
		                  <input type="text" class="first_name form-control" name="table1[first_name]" id="first_name" placeholder="First">	
		               </div>
		            </div>
		         </div>
		         <div class="col-md-4">
		            <div class="form-group form-float">
		               <div class="form-line">		
		                  <input type="text" class="middle_name form-control" name="table1[middle_name]" id="middle_name" placeholder="Middle">	
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
                		<div id="vacation_type_content">
								<div class="col-md-12" style="background:;">
								 	<div class="container-fluid">
									 	<div class="row">
										 	<div class="col-md-4">
												<input name="table1[type]" type="radio" id="radio_meeting" class="meeting with-gap radio-col-green" value="meeting" />
												<label for="radio_meeting">Meeting</label>
											</div>
											<div class="col-md-4">
												<input name="table1[type]" type="radio" id="radio_training_program" class="training_program with-gap radio-col-green" value="training_program"
												/>
												<label for="radio_training_program">Training Program</label>
											</div>
											<div class="col-md-4">
												<input name="table1[type]" type="radio" id="radio_others" class="others with-gap radio-col-green" value="others"
												/>
												<label for="radio_others">Others</label>
											</div>
										</div>
										<div class="row">
										 	<div class="col-md-4">
												<input name="table1[type]" type="radio" id="radio_seminar_conference" class="seminar_conference with-gap radio-col-green" value="seminar_conference" />
												<label for="radio_seminar_conference">Seminar/Conference</label>
											</div>
											<div class="col-md-4">
												<input name="table1[type]" type="radio" id="radio_gov_transaction" class="gov_transaction with-gap radio-col-green" value="gov_transaction"
												/>
												<label for="radio_gov_transaction">Government Transaction</label>
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
													<input type="text" class="activity_name form-control" name="table1[activity_name]" id="activity_name">
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
													<input type="text" class="activity_date form-control" name="table1[activity_date]" id="activity_date">
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4"> 
										<div id="activity_venue_content">
											<label class="form-label" style="font-size: 1.25rem">
												<small>Location:<small></label>
											<div class="form-group form-float">
												<div class="form-line">
													<input type="text" class="activity_venue form-control" name="table1[activity_venue]" id="activity_venue">
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4"> 
										<div id="activity_time_content">
											<label class="form-label" style="font-size: 1.25rem">
												<small>Time of Activity:<small></label>
											<div class="form-group form-float">
												<div class="form-line">
													<input type="text" class="activity_time form-control" name="table1[activity_time]" id="activity_time">
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
		


		<hr>

	</div>
	<div class="text-right" style="width:100%;">

	        <button id="updateLeaveRequest" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update Record</span>
	        </button>
      
    </div>
</form>