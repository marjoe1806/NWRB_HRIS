<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
				<h2>
				Travel Order Tracking
					<small>Viewing of all travel order status</small>
                </h2>
                
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" id="sess_division_id" value="<?php echo $_SESSION['emp_division_id']; ?>">
                        <input type="hidden" id="sess_employee_id"  value="<?php echo $_SESSION['employee_id']; ?>">
                        <input type="hidden" id="sess_position" value="<?php echo $_SESSION['position_id']; ?>">
                        <input type="hidden" id="sess_salary" value="<?php echo number_format($_SESSION['salary'],2); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <h5 class="text-info">Status</h5>
                            <select name="status" id="status" class="form-control">
                                <option value="">ALL</option>
                                <option value="0">RECOMMENDATION <br><small>(Sec. Head)</small></option>
                                <option value="1">RECOMMENDATION <br><small>(Div. Head)</small></option>
                                <option value="2">CERTIFICATION <br><small>(Deputy)</small></option>
                                <option value="3">FOR APPROVAL <br><small>(Director)</small></option>
                                <option value="4">FOR DRIVER AND VEHICLE ASSIGNING <br><small>(Gss)</small></option>
                                <option value="5">COMPLETED</option>
                                <option value="6">REJECTED</option>
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
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $form; ?>









<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/travelorder/travelordertracking.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/local/module/js/worksheet/travelorderworksheet.js"></script>