<div class="row clearfix" id="userLevelForm">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-blue">
				<h2>
					Compensatory Time Off Request
					<small>Manage Compensatory Time Off Request</small>
				</h2>
			</div>
			<div class="body">
				<div class="row">
					<div class="col-md-6">
							<?php //if(Helper::role(ModuleRels::LOCATOR_SLIPS_ADD_RECORDS)): ?> 
							<a id="addCTORequestForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addCTORequestForm'; ?>">
								<button type="button" class="btn btn-info btn-lg waves-effect">
									<i class="material-icons">add</i>
									<span>Add CTO Request</span>
								</button>
							</a>
							<?php //endif; ?>
					</div>
                    <div class="col-md-6 text-right">
                    	<button type="button" id="btnXls" class="btn btn-success btn-circle-lg waves-effect waves-circle waves-float"  data-toggle="tooltip" data-placement="top" title="Export xls">
                        	<i class="material-icons">archive</i>
                        </button>
                    </div>
				</div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <h5 class="text-info">Status</h5>
							<select name="status" id="status" class="form-control" data-live-search="true">
                                <option value="">ALL</option>
								<option value="1">For Certification (Head HR)</option>
								<option value="2">For Recommendation (Section Head)</option>
								<option value="3">For Recommendation (Division Head)</option>
								<option value="4">For Approval (Deputy)</option>
								<option value="5">APPROVED</option>
								<option value="6">DISAPPROVED</option>
								<!-- <option value="7">REJECTED</option> -->
								<option value="8">CANCELLED</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="button" id="btnsearch" class="btn btn-primary btn-circle-lg waves-effect waves-circle waves-float"><i class="material-icons">search</i></button>
                        </div>
                    </div>
                </div>
				<div id="table-holder">
					<?php echo $table; ?>
				</div>
				<div>
                    <input type="hidden" id="sess_division_id" value="<?php echo $_SESSION['division_id']; ?>">
                    <input type="hidden" id="sess_employee_id"  value="<?php echo $_SESSION['employee_id']; ?>">
                    <input type="hidden" id="sess_position" value="<?php echo $_SESSION['position_id']; ?>">
                </div>
			</div>
		</div>
	</div>
</div>

<?php echo $form; ?>







<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/leavemanagement/ctorequest.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/local/module/js/worksheet/ctoworksheet.js"></script> 