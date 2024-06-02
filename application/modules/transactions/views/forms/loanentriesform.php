<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" class="id" name="id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Pay Basis <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line pay_basis_select">
                        <select class="pay_basis form-control payroll_filters" name="pay_basis" id="pay_basis" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div> 
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Service / Unit / Division <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line division_select">
                        <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
			<div class="col-md-12">
                <label class="form-label">Employee <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line employee_select">
                        <select class="employee_id form-control " name="employee_id" id="employee_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
		</div>
        <div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Loan Category <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line loans_select">
                        <select class="loans_id form-control " name="loans_id" id="loans_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Loan Type <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line sub_loans_select">
                        <select class="sub_loans_id form-control " name="sub_loans_id" id="sub_loans_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Loan Amount <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" name="loan_amount" id="loan_amount" class="loan_amount form-control" required >
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Loan Balance <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" name="loan_balance" id="loan_balance" class="loan_balance form-control" required >
                    </div>
                </div>
            </div>
        </div> -->
        <div class="row clearfix">
             <div class="col-md-6">
                <label class="form-label">Loan Amount<span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" name="loan_amount" id="loan_amount" class="loan_amount form-control" required >
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Amortization Per Month<span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" name="amortization_per_month" id="amortization_per_month" class="amortization_per_month form-control" required >
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-6">
                <label class="form-label">Remaining Months to Pay <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" name="rmtp" id="rmtp" value="6" class="rmtp form-control" required readonly>
                    </div>
                </div>
            </div> -->
            
            <div class="col-md-6">
                <label class="form-label">Period Filed <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line payroll_period_select">
                        <select class="payroll_period_id form-control " name="payroll_period_id" id="payroll_period_id" data-live-search="true">
                            <option value="">Loading...</option>
                        </select>
                    </div>
                </div>
            </div>
       
         
            <div class="col-md-5">
                <label class="form-label">Status</label>
                <div class="form-group">
                    <div class="form-line">
                        <div class="form-line location_select">
                            <select class="is_active form-control" name="is_active" id="is_active" data-live-search="true">
                                <option value="1">ACTIVE</option>
                                <option value="0">INACTIVE</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <label class="form-label"><br></label><br>
                <label class="status_icon text-success"><i class="material-icons">check_circle</i></label>
            </div>
        </div>
        <div class="row clearfix" style="display:none;">
            <div class="col-md-6">
                <input type="checkbox" id="basic_checkbox_2" class="hold_tag1 filled-in" name="hold_tag" value="1">
                <label for="basic_checkbox_2">Hold Tag</label>
            </div>
            <div class="col-md-6">
            <label>Payment Status:</label>&emsp;
            <input type="hidden" name="payment_status" class="payment_status" value="INCOMPLETE">
            <span class="text-primary payment_status_text">INCOMPLETE</span>
            </div>
        </div>

    <div class="text-right" style="width:100%;">
    	<?php if($key == "addLoanEntries"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateLoanEntries"): ?>
	        <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update</span>
	        </button>
            
        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>

