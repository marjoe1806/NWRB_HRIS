<?php 
	$readonly = "";
	if($key == "viewEmployeeBalanceDetails")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<center>
		    <h2 style="border-bottom: solid 1px;">Balance Posting</h2>
		</center>
		<div class="row">
			<!-- <div class="col-md-4">
	            <label class="form-label">Location</label>
	            <div class="form-group">
	                <div class="form-line location_select">
	                    <select class="location_id form-control" name="location_id" id="location_id" data-live-search="true">
	                        <option value=""></option>
	                    </select>
	                </div>
	            </div>
	        </div>
	        <div class="col-md-4">
	            <label class="form-label">Department</label>
	            <div class="form-group">
	                <div class="form-line division_select">
	                    <select class="division_id form-control" name="division_id" id="division_id" data-live-search="true">
	                        <option value=""></option>
	                    </select>
	                </div>
	            </div>
	        </div> -->
	        <div class="col-md-8">
                <label class="form-label">Leave Group <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line leave_grouping_select">
                        <select class="leave_grouping_id form-control " name="leave_grouping_id" id="leave_grouping_id" data-live-search="true" >
                            <option value=""></option>
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
			                <th rowspan="2">Year</th>
			                <th rowspan="2" style="width:40%;">Employee</th>
			                <th colspan="2">Balance</th>
			                <th rowspan="2">Remarks</th>
			                <?php if($key == "addEmployeeBalance"): ?>
			                <th rowspan="2"></th>
			            	<?php endif; ?>
			            </tr>
			            <tr>
			            	<th>VL Amt</th>
			                <th>SL Amt</th>
			            </tr>
			        </thead>
			        <tbody style="">
			        	<tr class="row_0">
			                <td>
			                	<?php if($key == "updateEmployeeBalance"): ?>
			                	<input type="hidden" value="" name="balance_id" class="balance_id" id="balance_id"> 
			                	<?php endif; ?>
			                	<div class="form-group">
            	                    <div class="form-line year_select">
            	                        <select data-container="body" class="leave_year form-control" name="leave_year[]" id="leave_year" data-live-search="true" required>
            	                        	<?php 
            	                        	$years = array_combine(range(date("Y"), 1910), range(date("Y"), 1910));
            	                        	foreach ($years as $k => $v) {
            	                        	 	echo '<option value="'.$k.'">'.$v.'</option>';
            	                        	} 
            	                        	?>
            	                        </select>
            	                    </div>
            	                </div>
							</td>
							<td>
								<div class="form-group">
				                    <div class="form-line employee_select">
				                        <select class="employee_id form-control" name="employee_id[]" id="employee_id" data-live-search="true" required>
				                            <option value=""></option>
				                        </select>
				                    </div>
				                </div>
							</td>
							<td>
								<div class="form-group">
									<div class="form-line">
										<input type="number" name="vl_balance_amt[]" id="vl_balance_amt"
											class="vl_balance_amt form-control">
									</div>
								</div>
							</td>
							<td>
								<div class="form-group">
									<div class="form-line">
										<input type="number" name="sl_balance_amt[]" id="sl_balance_amt"
											class="sl_balance_amt form-control">
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
							<?php if($key == "addEmployeeBalance"): ?>
			                <td class="text-right">
			                	<button type="button" id="removeBalanceRow" style="visibility:hidden;" class="removeBalanceRow btn btn-danger btn-circle waves-effect waves-circle waves-float">
			                		<i class="material-icons">remove</i>
			                	</button>
			                </td>
			                <?php endif; ?>
			            </tr>
			        </tbody>
			        <?php if($key == "addEmployeeBalance"): ?>
			        <tfoot>
			        	<tr>
			        		<td colspan="6" class="text-right">
			        			<button type="button" id="addBalanceRow" class="btn btn-info btn-circle waves-effect waves-circle waves-float">
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
    	<?php if($key == "addEmployeeBalance"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add Record</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateEmployeeBalance"): ?>
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

