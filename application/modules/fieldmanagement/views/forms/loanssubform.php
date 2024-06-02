<?php 
	$readonly = "";
	if($key == "viewLoansDetails")
		$readonly = "disabled";
?>

    <div class="form-elements-container">
    	<input type="hidden" name="id" class="id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<h3 class="loan_title text-primary">Loan</h3>
		<hr>
		<div class="table-responsive sub_loan_table">
			<form id="addSubLoans" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addSubLoans'; ?>" method="POST">
				<table class="table">
					<tfoot>
						<tr>
							<td colspan="4">
								<h4>Add New Sub Loan</h4>
							</td>
						</tr>
						
							<tr>
								<td>
									<input type="hidden" name="loan_id" id="load_id" class="loan_id" value="">
								    <div class="form-group">
								    	<div class="form-line">
								    		<input type="text" name="code" id="code" class="code form-control" placeholder="Enter Code" required <?php echo $readonly ?>>
								    	</div>
									</div>
								</td>
								<td>
					                <div class="form-group">
						                <div class="form-line">
						            		<input type="text" name="description" placeholder="Enter Description" id="description" class="description form-control" required <?php echo $readonly ?>>
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
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<td>Code</td>
						<td>Description</td>
						<td>Status</td>
						<td></td>
					</tr>
				</thead>
				<tbody>
					<?php
					if(isset($list->Data->details) && sizeof($list->Data->details) > 0){ 
						foreach ($list->Data->details as $k => $v) { ?>
						<tr>
							<td><?php echo $v->code; ?></td>
							<td><?php echo $v->description; ?></td>
							<td>
								<?php echo ($v->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>'; ?>
							</td>
							<td>
								<!--<a id="updateSubLoansForm" class="updateSubLoansForm btn btn-info btn-circle waves-effect waves-circle waves-float" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateSubLoansForm'; ?>"
								data-toggle="tooltip" data-placement="top" title="Update" data-id="<?php echo $v->id; ?>" 
									<?php foreach ($v as $k1=> $v1) { echo ' data-'.$k1.'="'.$v1.'" '; } ?>" >
									<i class="material-icons">mode_edit</i>
								</a>-->
								<?php if($v->is_active == "1"){ ?>
								<?php //if(Helper::role(ModuleRels::DEACTIVATE_DOCUMENT_TYPE)): ?>
								<a class="deactivateSubLoans btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top"
								title="Deactivate" data-id="<?php echo $v->id; ?>" 
								data-loan_id="<?php echo $v->loan_id; ?>" 
								href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateSubLoans'; ?>">
									<i class="material-icons">do_not_disturb</i>
								</a>
								<?php //endif; ?>
								<?php }else{ ?>
								<?php //if(Helper::role(ModuleRels::ACTIVATE_DOCUMENT_TYPE)): ?>
								<a class="activateSubLoans btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top"
								title="Activate" data-id="<?php echo $v->id; ?>" 
								data-loan_id="<?php echo $v->loan_id; ?>" 
								href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateSubLoans'; ?>">
									<i class="material-icons">done</i>
								</a>
								<?php //endif; ?>
								<?php } ?>
							</td>
						</tr>	
					<?php }
					}else{ ?>
						<tr>
							<td colspan="4">No data available.</td>
						</tr>
					<?php } ?>
					
				</tbody>
			</table>
		</div>
    </div>
    <div class="text-right" style="width:100%;">
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>

