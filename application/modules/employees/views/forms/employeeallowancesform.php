<?php 
	$readonly = "";
	if($key == "viewEmployeeAllowancesDetails")
		$readonly = "disabled";
?>

    <div class="form-elements-container">
    	<input type="hidden" name="id" class="id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<h3 class="employee_name text-primary">Employee</h3>
		<hr>
		<div class="table-responsive sub_loan_table">
			<form id="addEmployeeAllowances" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addEmployeeAllowances'; ?>" method="POST">
				<table class="table">
					<tfoot>
						<tr>
							<td colspan="4">
								<h4>Add New Allowance</h4>
							</td>
						</tr>
						
							<tr>
								<td>
									<input type="hidden" name="employee_id" id="employee_id" class="employee_id" value="">
								    <div class="form-group">
								    	<div class="form-line allowance_id_select">
					                        <select class="allowance_id form-control required" name="allowance_id" id="allowance_id" data-live-search="true" >
					                            <option value=""></option>
					                        </select>
					                    </div>
									</div>
								</td>
								<td>
					                <div class="form-group">
						                <div class="form-line">
						            		<input type="number" min="0" name="amount" placeholder="Enter Amount" id="amount" class="amount form-control" required <?php echo $readonly ?>>
											<!-- <div class="help-block with-errors"></div> -->
						            	</div>
					            	</div>
								</td>
								<td></td>
								<td class="text-right">
						    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
							            <i class="material-icons">add</i><span> Add</span>
							        </button>
								</td>
							</tr>
					</tfoot>
				</table>
			</form>
			<form id="updateEmployeeAllowances" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateEmployeeAllowances'; ?>" method="POST">
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<td>Allowance Description</td>
							<td>Amount</td>
							<td>Status</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						<?php
						if(isset($list->Data->details) && sizeof($list->Data->details) > 0){ 
							foreach ($list->Data->details as $k => $v) { ?>
							<tr>
								<td>
									<?php echo $v->allowance_name; ?>
									<input type="hidden" id="id" class="id" name="id" value="<?php echo $v->id; ?>" disabled>
									<input type="hidden" id="employee_id" class="employee_id" name="employee_id" value="<?php echo $v->employee_id; ?>" disabled>
								</td>
								<td><span class="td-text"><?php echo $v->amount; ?></span>
									<input style="display:none;" type="number" min="0" id="amount" class="amount form-control required" name="amount" value="<?php echo $v->amount; ?>" disabled>
								</td>
								<td>
									<?php echo ($v->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>'; ?>
								</td>
								<td>
									<a id="updateEmployeeAllowancesForm" class="updateEmployeeAllowancesForm btn btn-info btn-circle waves-effect waves-circle waves-float" href="#"
										data-toggle="tooltip" data-placement="top" title="Update" >
										<i class="material-icons">mode_edit</i>
									</a>
									<button style="display:none;" type="submit" class="btn btn-info btn-circle waves-effect waves-circle waves-float"
										data-toggle="tooltip" data-placement="top" title="Save"><i class="material-icons">save</i></button>
									<button style="display:none;" type="button" class="cancelUpdateForm btn btn-danger btn-circle waves-effect waves-circle waves-float"
										data-toggle="tooltip" data-placement="top" title="Cancel"><i class="material-icons">close</i></button>
									<?php if($v->is_active == "1"){ ?>
									<?php //if(Helper::role(ModuleRels::DEACTIVATE_DOCUMENT_TYPE)): ?>
									<a class="deactivateEmployeeAllowances btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top"
									title="Deactivate" data-id="<?php echo $v->id; ?>" 
									data-employee_id="<?php echo $v->employee_id; ?>" 
									href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateEmployeeAllowances'; ?>">
										<i class="material-icons">do_not_disturb</i>
									</a>
									<?php //endif; ?>
									<?php }else{ ?>
									<?php //if(Helper::role(ModuleRels::ACTIVATE_DOCUMENT_TYPE)): ?>
									<a class="activateEmployeeAllowances btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top"
									title="Activate" data-id="<?php echo $v->id; ?>" 
									data-employee_id="<?php echo $v->employee_id; ?>" 
									href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateEmployeeAllowances'; ?>">
										<i class="material-icons">done</i>
									</a>
									<?php //endif; ?>
									<?php } ?>
								</td>
							</tr>	
						<?php }
						}else{ ?>
							<tr>
								<td colspan="7">No data available.</td>
							</tr>
						<?php } ?>
						
					</tbody>
				</table>
			</form>
		</div>
    </div>
    <div class="text-right" style="width:100%;">
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>

