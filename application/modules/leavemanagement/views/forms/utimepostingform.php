<?php 
	$readonly = "";
	if($key == "viewEmployeeUTimeDetails")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<center>
		    <h2 style="border-bottom: solid 1px;">UTime Posting</h2>
		</center>
		<div class="row">
<!-- 			<div class="col-md-3">
	            <label class="form-label">Location</label>
	            <div class="form-group">
	                <div class="form-line location_select">
	                    <select class="location_id form-control" name="location_id" id="location_id" data-live-search="true">
	                        <option value=""></option>
	                    </select>
	                </div>
	            </div>
	        </div>
	        <div class="col-md-3">
                <label class="form-label">Department</label>
                <div class="form-group">
                    <div class="form-line division_select">
                        <select class="division_id form-control" name="division_id" id="division_id" data-live-search="true">
                            <option value="" selected></option>
                        </select>
                    </div>
                </div>
            </div> -->
            <div class="col-md-6">
                <label class="form-label">Department <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line division_select">
                        <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Employee</label>
                <div class="form-group">
                    <div class="form-line employee_select">
                        <select class="employee_id form-control" name="employee_id[]" id="employee_id" data-live-search="true" required>
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Year</label>
                <div class="form-group">
                    <div class="form-line">
                        <select class="leave_year form-control" name="leave_year" id="leave_year" data-live-search="true" required>
                        	<?php 
                        	$years = array_combine(range(date("Y"), 1910), range(date("Y"), 1910));
                        	foreach ($years as $k => $v) {
                        	 	echo '<option value="'.$k.'">'.$v.'</option>';
                        	} 
                        	?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
		<div class="body">
			<div class="table-responsive">
			    <table class="table table-bordered table-hover table-striped" style="text-transform: uppercase;text-align:center" id="leaveapplicationtable">
			        <thead>
			            <tr>
			                <th>Period</th>
			                <th>UTime Amount</th>
			                <th>Remarks</th>
			                <?php if($key == "addEmployeeUTime"): ?>
			                <th></th>
			            	<?php endif; ?>
			            </tr>
			        </thead>
			        <tbody style="">
			        	<tr class="row_0">
			                <td>
			                	<?php if($key == "updateEmployeeUTime"): ?>
			                	<input type="hidden" value="" name="utime_id" class="utime_id" id="utime_id"> 
			                	<?php endif; ?>
			                	<div class="form-group">
				                	<div class="form-line month_select">
						                <select class="month_code  form-control" id="month_code" name="month_code[]" data-live-search="true" required>
											<option value=""></option>
										</select>
									</div>
								</div>
							</td>
							<td>
								<div class="form-group">
									<div class="form-line">
										<input type="text" name="utime_amt[]" id="utime_amt"
											class="utime_amt daystimepicker form-control">
									</div>
								</div>
							</td>
							<td>
								<div class="form-group">
									<div class="form-line">
										<textarea name="remarks[]" id="remarks" row="4" class="remarks form-control"></textarea>
									</div>
								</div>
							</td>
							<?php if($key == "addEmployeeUTime"): ?>
			                <td class="text-right">
			                	<button type="button" id="removeUTimeRow" style="visibility:hidden;" class="removeUTimeRow btn btn-danger btn-circle waves-effect waves-circle waves-float">
			                		<i class="material-icons">remove</i>
			                	</button>
			                </td>
			                <?php endif; ?>
			            </tr>
			        </tbody>
			        <?php if($key == "addEmployeeUTime"): ?>
			        <tfoot>
			        	<tr>
			        		<td colspan="4" class="text-right">
			        			<button type="button" id="addUTimeRow" class="btn btn-info btn-circle waves-effect waves-circle waves-float">
			        				<i class="material-icons">add</i>
			        			</button>
			        		</td>
			        	</tr>
			        </tfoot>
		    		<?php endif; ?>
			    </table>
			</div>
		</div>
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addEmployeeUTime"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add Record</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateEmployeeUTime"): ?>
	        <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update Record</span>
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

