<style type="text/css">
        .alignRight{
            text-align: right;
        }
        .alignCenter{
            text-align: center;
        }
    	.form-group{
    		margin-bottom: 0px;
    	}
        @media (min-width: 992px){
            .modal-lg {
                width: 1200px;
            }
        }
        @media (min-width: 768px){
            .modal-dialog {
                width: 1200px;
                margin: 30px auto;
            }
        }
    </style>
    
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card"> 
                            <form id="<?php echo $key; ?>" enctype="multipart/form-data" accept-charset="utf-8">
                            <!-- <form id="wizard_with_validation" method="POST"> -->
                                <input type="hidden" class="id" name="id" id="id">
                                <div class="header">
                                    <h1><b>PAYROLL INFORMATION</b><small style="color:#555555; font-weight:bold;"> of <span class="emp_name"></span></small></h1>
                                </div>
                                <div class="body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Employment Status</label>
                                            <div class="form-group">
                                                <div class="form-line"><!-- employment_status_select -->
                                                    <select class="employment_status form-control " name="employment_status" id="employment_status" required>
                                                        <option value="Active">Active</option>
                                                        <option value="Transferred">Transferred</option>
                                                        <option value="Retired">Retired</option>
                                                        <option value="Resigned">Resigned</option>
                                                        <option value="Dropped">Dropped</option>
                                                        <option value="Terminated">Terminated</option>
                                                        <option value="Dismissed">Dismissed</option>
                                                        <option value="AWOL">AWOL</option>
                                                        <option value="End of Contract">End of Contract</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Location <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <select class="location_id form-control" name="location_id"  id="location_id" data-live-search="true">
                                                        <option value="" selected></option>
                                                        <?php foreach ($locations as $k => $v) : ?>
                                                            <option value="<?php echo $v['id']?>"><?php echo $v['name']?></option>
                                                        <?php endforeach; ?>
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Date of Original Appointment <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" name="start_date" id="start_date" class="start_date datepicker form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Date of Last Service</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" name="end_date" id="end_date" class="end_date datepicker form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Account Number</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="account_number form-control " name="account_number" id="account_number">
                                                    <!-- <input type="hidden" class="account_number form-control " name="account_number" id="account_number" disabled> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Pay Basis <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <div class="form-line pay_basis_select">
                                                    <select class="pay_basis form-control " name="pay_basis" id="pay_basis" required>
                                                        <option value=""></option>
                                                        <!-- <option value="Permanent">Weekly</option>
                                                        <option value="Permanent (Probationary)">Bi-Monthly</option> -->
                                                        <option value="Permanent">Permanent</option>
                                                        <option value="Contractual">Contractual</option>
                                                        <option value="Temporary">Temporary</option>
                                                        <option value="Contract of Service">Contract of Service</option>
                                                        <option value="Appointed Official">Appointed Official</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Plantilla Item No. <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <div class="form-line item_no_select">
                                                    <select class="item_no form-control " name="item_no" id="item_no" data-live-search="true" required>
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3" style="pointer-events:none;">
                                            <label class="form-label">Position Title</label>
                                            <div class="form-group" style="display: none;">
                                                <div class="form-line position_select">
                                                    <select class="position_id form-control " name="position_id" id="position_id"  data-live-search="true" readonly>
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="position_title" id="position_title" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Position Designation</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="position_designation form-control " name="position_designation" id="position_designation">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Service / Department / Unit Designation</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="division_designation form-control " name="division_designation" id="division_designation">
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Salary Grade</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="salary_grade form-control " name="salary_grade" id="salary_grade" readonly>
                                                    <input type="hidden" class="salary_grade_id form-control " name="salary_grade_id" id="salary_grade_id">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Step Increment</label>
                                            <div class="form-group">
                                                <div class="form-line salary_grade_step_select">
                                                    <select class="salary_grade_step_id form-control " name="salary_grade_step_id" id="salary_grade_step_id" data-live-search="true">
                                                        <option value=""></option>
                                                    </select> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Date of Last Promotion</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" name="date_of_permanent" id="date_of_permanent" class="date_of_permanent datepicker form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 div_daily_rate" style="pointer-events:none; display: none;">
                                            <label class="form-label">Daily Rate</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <textarea name="daily_rate" id="daily_rate" class="daily_rate form-control no-resize auto-growth alignRight" type="number" rows="1" style="overflow: hidden; overflow-wrap: break-word; "></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3" style="pointer-events:none;">
                                            <label class="form-label">Salary</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <textarea name="salary" id="salary" class="salary form-control no-resize auto-growth alignRight" type="number" rows="1" style="overflow: hidden; overflow-wrap: break-word; "></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Tax Percentage(%)</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="number" min="0" value=".00" id="tax_percentage" name="tax_percentage" class="tax_percentage form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Tax Additionals (PHP)</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="number" min="0" value=".00" id="tax_additional" name="tax_additional" class="tax_additional form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row hidden">
                                        <div class="col-md-3">
                                            <label class="form-label">1st Payroll</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                <textarea value=".00" name="cut_off_1" id="cut_off_1" class="cut_off_1 cutoff form-control no-resize auto-growth alignRight" type="number" rows="1" style="overflow: hidden; overflow-wrap: break-word; "></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">2nd Payroll</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                <textarea value=".00" name="cut_off_2" id="cut_off_2" class="cut_off_2 cutoff form-control no-resize auto-growth alignRight" type="number" rows="1" style="overflow: hidden; overflow-wrap: break-word; "></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">3rd Payroll</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                <textarea value=".00" name="cut_off_3" id="cut_off_3" class="cut_off_3 cutoff form-control no-resize auto-growth alignRight" type="number" rows="1" style="overflow: hidden; overflow-wrap: break-word; "></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">4th Payroll</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                <textarea value=".00" name="cut_off_4" id="cut_off_4" class="cut_off_4 cutoff form-control no-resize auto-growth alignRight" type="number" rows="1" style="overflow: hidden; overflow-wrap: break-word; "></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Type of Shift</label>
                                            <div class="form-group">
                                                <input type="hidden" name="tmp_regular_shift" id="tmp_regular_shift" readonly>
                                                <input type="hidden" name="tmp_shift_id" id="tmp_shift_id" readonly>
                                                <input type="radio" class="chkradio shift_changes" name="regular_shift" id="radio_input_shift_fix" value="1">&nbsp;Fixed Shift&nbsp;&nbsp;&nbsp;
                                                <input type="radio" class="chkradio shift_changes" name="regular_shift" id="radio_input_shift_flex" value="0">&nbsp;Flexible Shift
                                            </div>
                                        </div>
                                        <div class="col-md-3 regular_shift_container">
                                            <label class="form-label">Shift Schedule</label>
                                            <div class="form-group">
                                                <div class="form-line shift_select">
                                                    <select class="shift_id form-control shift_changes" name="shift_id" id="shift_id" data-live-search="true">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 flexible_shift_container" style="display:none;">
                                            <label class="form-label">Shift Schedule</label>
                                            <div class="form-group">
                                                <div class="form-line flex_shift_select">
                                                    <select class="flex_shift_id form-control shift_changes" name="flex_shift_id" id="flex_shift_id" data-live-search="true">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 shift_date_effectivity">
                                            <label class="form-label">Shift Date Effectivity</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="hidden" name="tmp_shift_date_effectivity" id="tmp_shift_date_effectivity" readonly>
                                                    <input type="text" name="shift_date_effectivity" id="shift_date_effectivity" class="shift_date_effectivity datepicker form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <fieldset>
                                                <legend>Income</legend>
                                                
                                                <fieldset id="taxableDiv">
                                                    <legend>Taxable</legend>
                                                    <div class="row">
                                                        <div class="col-md-6">Monthly Gross Salary</div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                    <input type="number" min="0" value=".00" id="monthly_gross" name="monthly_gross" class="monthly_gross form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>

                                                
                                                <fieldset>
                                                    <legend>Non-Taxable</legend>
                                                    <div id="non_taxable">
                                                        <div class="row">
                                                            <div class="col-md-6"><input type="checkbox" name="with_pera" id="md_checkbox_2x" class="md_checkbox_2x chk with_pera">&nbsp;&nbsp;PERA</div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="form-line">
                                                                        <input type="number" min="0" value="2000.00" id="pera" name="pera" class="pera form-control" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">Rep Allowance</div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="form-line">
                                                                    <input type="number" min="0" value="0.00" name="rep_allowance" id="rep_allowance" class="rep_allowance form-control" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">Travel Allowance</div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="form-line">
                                                                    <input type="number" min="0" value="0.00" name="trans_allowance" id="trans_allowance" class="trans_allowance form-control" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset>
                                                <legend>Mandatory Deductions</legend>
                                                    <div class="row">
                                                        <div class="col-md-6"><input type="checkbox" name="with_gsis" id="md_checkbox_2a" class="md_checkbox_2a chk with_gsis chk_non_taxable">&nbsp;&nbsp;GSIS Contribution</div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                    <input type="number" min="0" value=".00" id="gsis_contribution" name="gsis_contribution" class="gsis_contribution form-control ">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6"><input type="checkbox" name="with_philhealth_contribution" id="md_checkbox_2d" class="md_checkbox_2d chk with_philhealth_contribution chk_non_taxable">&nbsp;&nbsp;Philhealth Contribution</div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                    <input type="number" min="0" value=".00" id="philhealth_contribution" name="philhealth_contribution" class="philhealth_contribution form-control chk_non_taxable">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6"><input type="checkbox" name="with_pagibig_contribution" id="md_checkbox_2c" class="md_checkbox_2c chk with_pagibig_contribution chk_non_taxable">&nbsp;&nbsp;Pagibig Contribution</div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                <input type="number" min="0" id="pagibig_contribution" name="pagibig_contribution" class="pagibig_contribution form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6"><input type="checkbox" name="with_mp2_contributions" id="md_checkbox_2b" class="md_checkbox_2b chk with_mp2_contributions chk_non_taxable">&nbsp;&nbsp;MP2 Contribution</div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                <input type="number" min="0" id="mp2_contribution" name="mp2_contribution" class="mp2_contribution form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6"><input type="checkbox" name="with_union_dues" id="md_checkbox_2v" class="md_checkbox_2v chk with_union_dues chk_non_taxable">&nbsp;&nbsp;NWRBEA Membership</div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                    <input type="number" min="0" id="union_dues_contribution" name="union_dues_contribution" class="union_dues_contribution form-control" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </fieldset>
                                            <fieldset>
                                                <legend></legend>
                                                    <div class="row">
                                                        <div class="col-md-6">Gross Annual Income</div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                    <input type="number" min="0" value=".00" id="annual_income" name="annual_income" class="annual_income form-control" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">Total Mandatory Deduction</div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                    <input type="number" min="0" value=".00" id="mandatory_deduction" name="mandatory_deduction" class="mandatory_deduction form-control" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">Total Addtl. Compensation</div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                    <input type="number" min="0" value=".00" id="addtl_compensation" name="addtl_compensation" class="addtl_compensation form-control" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">Allowable Compensation</div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                    <input type="number" min="0" value=".00" id="allowable_compensation" name="allowable_compensation" class="allowable_compensation comp_input form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">Addtl. Taxable Compensation</div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                    <input type="number" min="0" value=".00" id="addtl_taxable_compensation" name="addtl_taxable_compensation" class="addtl_taxable_compensation form-control" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">Est. Annual Net Taxable Amount</div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                    <input type="number" min="0" value=".00" id="est_annual_net_tax_amnt" name="est_annual_net_tax_amnt" class="est_annual_net_tax_amnt form-control" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">Tax Rate (Annual)</div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                    <input type="number" min="0" value=".00" id="annual_tax_rate" name="annual_tax_rate" class="annual_tax_rate form-control" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">Est. Annual Tax</div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                    <input type="number" min="0" value=".00" id="est_annual_tax" name="est_annual_tax" class="est_annual_tax form-control" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">Est. Monthly Tax Deduction</div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                    <input type="number" min="0" value=".00" id="est_monthly_tax_deductions" name="est_monthly_tax_deductions" class="est_monthly_tax_deductions form-control" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="row clearfix" id="jo_tax_container" style="display:none;">
                                        <div class="col-lg-1 col-md-1 col-sm-4 col-xs-5 form-control-label">
                                            <label for="email_address_2">Tax (%):</label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="number" min="0" value=".00" id="tax" name="tax" class="tax form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-4 col-xs-5 form-control-label">
                                            <label for="email_address_2">GMP (%):</label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="number" min="0" value=".00" id="gmp" name="gmp" class="gmp form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-4 col-xs-5 form-control-label">
                                            <label for="email_address_2">0th (%):</label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="number" min="0" value=".00" id="0th" name="0th" class="0th form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" name="btnSubmit" class="btn btn-primary btn-sm" style="float: right;">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>