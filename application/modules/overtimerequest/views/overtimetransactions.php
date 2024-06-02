<div class="row clearfix" id="userLevelForm">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-blue">
				<h2>
				Overtime Transaction/s
					<small>Manage Overtime Transaction/s</small>
				</h2>
			</div>
			<div class="body">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
                            <h5 class="text-info">Status</h5>
							<select class="form-control" id="status">
								<option value="">All</option>
								<option value="PENDING">Pending</option>
								<option value="APPROVED">Approved</option>
								<option value="COMPLETED">Completed</option>
								<option value="REJECTED">Disapproved</option>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<a href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'; ?>" id="btnSearch" class="btn btn-primary btn-circle-lg waves-effect waves-circle waves-float"><i class="material-icons">search</i></a>
					</div>
                    <div class="col-md-6 text-right">
                    	<button type="button" id="btnXls" class="btn btn-success btn-circle-lg waves-effect waves-circle waves-float" >
                        	<i class="material-icons">archive</i>
                        </button>
                    </div>
				</div>
				<div id="table-holder">
					<?php echo $table; ?>
				</div>
			</div>
			<div>
                <input type="hidden" id="sess_division_id" value="<?php echo $_SESSION['division_id']; ?>">
                <input type="hidden" id="sess_employee_id"  value="<?php echo $_SESSION['employee_id']; ?>">
                <input type="hidden" id="sess_position" value="<?php echo $_SESSION['position_id']; ?>">
            </div>
		</div>
	</div>
</div>
<?php echo $form; ?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/overtimerequest/overtimetransactions.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/local/module/js/worksheet/officialbusinessworksheet.js"></script>