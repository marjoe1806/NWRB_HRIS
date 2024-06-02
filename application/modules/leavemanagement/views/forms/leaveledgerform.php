<?php 
	$readonly = "";
	if($key == "viewEmployeeLedgerDetails")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<center>
		    <h2 style="border-bottom: solid 1px;">EMPLOYEE LEAVE CARD</h2>
		</center>
		<div class="row">
			<div class="col-md-3">
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
                        <select class="leave_year form-control" name="leave_year[]" id="leave_year" data-live-search="true" required>
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
			                <th></th>
			                <th colspan="4" style="font-size: 9px">
			                    <center>Vacation Leave</center>
			                </th>
			                <th colspan="4" style="font-size: 9px">
			                    <center>Sick Leave</center>
			                </th>
			                <th style="text-align:center" rowspan="2" valign="middle">REMARKS</th>
			                <th></th>
			            </tr>
			            <tr>
			                <th>Period</th>
			                <th style="width:100px;">Earned (<span class="text-success" style="font-size:12px;">+</span>)</th>
			                <th width="8%" style="font-size: 9px">
			                    <center>Undertime w/ Pay (<span style="font-size:12px;" class="text-danger">-</span>)</center>
			                </th>
			                <th style="width:100px;">Balance</th>
			                <th width="8%" style="font-size: 9px">
			                    <center>Undertime w/o Pay</center>
			                </th>
			                <th>Earned (<span class="text-success" style="font-size:12px;">+</span>)</th>
			                <th width="8%" style="font-size: 9px">
			                    <center>Undertime w/ Pay (<span style="font-size:12px;" class="text-danger">-</span>)</center>
			                </th>
			                <th style="width:100px;">Balance</th>
			                <th width="8%" style="font-size: 9px">
			                    <center>Undertime w/o Pay</center>
			                </th>
			                <th></th>
			            </tr>
			        </thead>
			        <tbody style="">
			        	<tr class="row_0">
			                <td>
			                	<?php if($key == "updateEmployeeLedger"): ?>
			                	<input type="hidden" value="" name="ledger_id" class="ledger_id" id="ledger_id"> 
			                	<?php endif; ?>
			                	<div class="form-group">
				                	<div class="form-line month_select">
						                <select class="month_code  form-control" id="month_code" name="month_code[]" data-live-search="true" required>
											<option value=""></option>
										</select>
									</div>
								</div>
							</td>
			                <td class="text-success">
			                	<!-- + 1.25  -->
			                	<input type="number" name="leave_vacation_earned[]" class="leave_vacation_earned form-control" value="1.25">

			                </td>
			                <td class="text-danger">
			                	<div class="form-group">
									<div class="form-line">
										<input type="text" min="0" style="color:#a94442;text-align:center;" name="leave_vacation_undertime_w_pay[]" id="leave_vacation_undertime_w_pay" class="leave_vacation_undertime_w_pay form-control" value="0"/>
									</div>
								</div>
			                	
			                </td>
			                <td class="text-primary">
		                		<input type="number" min="0" class="leave_vacation_balance form-control" name="leave_vacation_balance[]" value="0">
			                </td>
			                <td class="text-danger"></td>
			                <td class="text-success">
			                	+ 1.25 
			                	<input type="hidden" name="leave_sick_earned[]" class="leave_sick_earned" value="1.25">
			                </td>
			                <td class="text-danger" style="width:100px;">
			                	<div class="form-group">
									<div class="form-line">
										<input type="number" min="0" style="color:#a94442;text-align:center;" name="leave_sick_undertime_w_pay[]" id="leave_sick_undertime_w_pay" class="leave_sick_undertime_w_pay form-control" value="0"/>
									</div>
								</div>
			                	
			                </td>
			                <td class="text-primary">
		                		<input type="number" min="0" class="leave_sick_balance form-control" name="leave_sick_balance[]" value="0">
			                </td>
			                <td></td>
			                <td style="width:300px;">
			                	<div class="form-group">
									<div class="form-line">
										<textarea name="remarks[]" id="remarks" class="remarks form-control" rows="3">
										</textarea>
									</div>
								</div>
			                </td>
			                <td class="text-right">
			                	<button type="button" id="removeLedgerRow" style="visibility:hidden;" class="removeLedgerRow btn btn-danger btn-circle waves-effect waves-circle waves-float">
			                		<i class="material-icons">remove</i>
			                	</button>
			                </td>
			            </tr>
			        </tbody>
			        <?php if($key == "addEmployeeLedger"): ?>
			        <tfoot>
			        	<tr>
			        		<td colspan="11" class="text-right">
			        			<button type="button" id="addLedgerRow" class="btn btn-info btn-circle waves-effect waves-circle waves-float">
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
    	<?php if($key == "addEmployeeLedger"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add Record</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateEmployeeLedger"): ?>
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

