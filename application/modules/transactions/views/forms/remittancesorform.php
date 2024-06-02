<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" class="id" name="id" value="">
		<div class="row clearfix">            
            <div class="col-md-6">
                <label class="form-label">Loan Category <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line loans_select">
                        <select class="loans_id form-control required" name="loans_id" id="loans_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 sub_loan">
                <label class="form-label">Loan Type <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line sub_loans_select">
                        <select class="sub_loans_id form-control required" name="sub_loans_id" id="sub_loans_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-6">
                <label class="form-label">Serivce / Unit / Division <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line division_select">
                        <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div> -->
        </div>
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
                <label class="form-label">Period <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line payroll_period_select">
                        <select class="payroll_period_id form-control " name="payroll_period_id" id="payroll_period_id" data-live-search="true">
                            <option value="">Loading...</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Official Receipt Number <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="official_receipt_no" id="official_receipt_no" class="official_receipt_no form-control" required >
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Date <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="date_posted" id="date_posted" class="date_posted form-control datepicker" required >
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right" style="width:100%;">
            <?php if($key == "addRemittancesOR"): ?>
                <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
                    <i class="material-icons">add</i><span> Add</span>
                </button>
            <?php endif; ?>
            <?php if($key == "updateRemittancesOR"): ?>
                <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
                    <i class="material-icons">save</i><span> Update</span>
                </button>
            <?php endif; ?>
            <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
                <i class="material-icons">close</i><span> Close</span>
            </button>
        </div>
    </div>
</form>

