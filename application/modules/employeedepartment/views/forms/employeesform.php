<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" class="id" name="id" id="id">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
        <hr>
        <center><h4 class="text-info">Personal Information</h4></center>
        <hr>
        <div class="row clearfix">
            <div id="aniimated-thumbnials">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-2"></div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-8">
                    <a class="image-tag" href="<?php echo base_url(); ?>assets/custom/images/default-avatar.jpg" data-sub-html="Employee Avatar">
                        <img id="employeeImage" class="img-responsive thumbnail" src="<?php echo base_url(); ?>assets/custom/images/default-avatar.jpg" style="display:none; width: 297px;height:220px;">
                    </a>
                    <div id="my_camera" style=""></div>

                    <?php if($key != "viewEmployees"): ?>
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <button id="upload" class="upload form-control btn btn-warning btn-sm waves-effect" type="button">
                                <i class="material-icons">file_upload</i><span> Upload</span>
                            </button>
                            <div class="hiddenfile" style="width: 0px; height: 0px;overflow: hidden;">
                                <input id="fileupload" class="fileupload" name="" accept="image/*" type="file">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <button id="take_snapshot" class="form-control btn btn-success btn-sm waves-effect" type="button">
                                <i class="material-icons">camera</i><span> Take Snapshot</span>
                            </button>
                            <button id="reset_snapshot" style="display:none;" class="form-control btn btn-success btn-sm waves-effect" type="button">
                                <i class="material-icons">camera</i><span> Reset Snapshot</span>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <hr>
		<div class="row clearfix">
			<div class="col-md-4">
                <label class="form-label">Employee No. <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="employee_id_number" id="employee_id_number" class="employee_id_number form-control" >
                	</div>
            	</div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Scanning No. <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="employee_number" id="employee_number" class="employee_number form-control" >
                    </div>
                </div>
            </div>
		</div>
		<div class="row clearfix">
			<div class="col-md-4">
                <label class="form-label">First Name <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="first_name" id="first_name" class="first_name full-name form-control"  >
                	</div>
            	</div>
            </div>
			<div class="col-md-4">
                <label class="form-label">Middle Name <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="middle_name" id="middle_name" class="middle_name full-name form-control"  >
                	</div>
            	</div>
            </div>
			<div class="col-md-4">
                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="last_name" id="last_name" class="last_name full-name form-control"  >
                	</div>
            	</div>
            </div>
		</div>
        <div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Agency <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line agency_select">
                        <select class="agency_id form-control " name="agency_id" id="agency_id" data-live-search="true" >
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Office <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line office_select">
                        <select class="office_id form-control " name="office_id" id="office_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Department <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line division_select">
                        <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Satellite Location <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line location_select">
                        <select class="location_id form-control " name="location_id" id="location_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Mobile No. <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" name="contact_number" id="contact_number" class="contact_number form-control"  >
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Contract <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line contract_select">
                        <select class="contract form-control " name="contract" id="contract" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Other No.</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" name="other_number" id="other_number" class="other_number form-control" >
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
			<div class="col-md-8">
                <label class="form-label">Residential Address <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="address" id="address" class="address form-control"  >
                	</div>
            	</div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Zip Code <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" name="zip_code" id="zip_code" class="zip_code form-control"  >
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-8">
                <label class="form-label">Provincial Address</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="provincial_address" id="provincial_address" class="provincial_address form-control" >
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-8">
                <label class="form-label">Permanent Address</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="permanent_address" id="permanent_address" class="permanent_address form-control" >
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
        	<div class="col-md-4">
                <label class="form-label">Birthday<span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="birthday" placeholder="Ex: 07/30/2016" id="birthday" class="date birthday form-control"  >
                	</div>
            	</div>
            </div>
            <div class="col-md-8">
                <label class="form-label">Birth Place<span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="birth_place" id="birth_place" class="birth_place form-control"  >
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-2">
                <label class="form-label">GSIS</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="gsis" id="gsis" class="gsis form-control" >
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">SSS</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="sss" id="sss" class="sss form-control" >
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">TIN</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="tin" id="tin" class="tin form-control" >
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">Philhealth</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="philhealth" id="philhealth" class="philhealth form-control" >
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">Pagibig</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="pagibig" id="pagibig" class="pagibig form-control" >
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-3">
                <label class="form-label">Nationality<span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="nationality" id="nationality" class="nationality form-control"  >
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Civil Status <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line civil_status_select">
                        <select class="civil_status form-control " name="civil_status" id="civil_status" data-live-search="true">
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Annuled">Annuled</option>
                            <option value="Divorced">Divorced</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Height (m)<span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" name="height" id="height" class="height form-control"  >
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Weight (kg)<span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" name="weight" id="weight" class="weight form-control"  >
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Email<span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="email" id="email" class="email form-control"  >
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Gender <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line gender_select">
                        <select class="gender form-control " name="gender" id="gender" data-live-search="true">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <center><h4 class="text-info">Payroll Information</h4></center>
        <hr>
        <div class="row clearfix">
            <div class="col-md-4">
                <label class="form-label">Employment Status <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line employment_status_select">
                        <select class="employment_status form-control " name="employment_status" id="employment_status" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Start Date <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="start_date" id="start_date" class="start_date datepicker form-control" >
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">End Date</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="end_date" id="end_date" class="end_date form-control" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-4">
                <label class="form-label">Pay Basis <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line pay_basis_select">
                        <select class="pay_basis form-control " name="pay_basis" id="pay_basis" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <label class="form-label">Position <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line position_select">
                        <select class="position_id form-control " name="position_id" id="position_id" data-live-search="true" >
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="row clearfix">
            <div class="col-md-3">
                <label class="form-label">Salary Grade <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line salary_grade_select">
                        <!-- <select class="salary_grade_id form-control " name="salary_grade_id" id="salary_grade_id" data-live-search="true">
                            <option value=""></option>
                        </select> -->
                        <input type="text" class="salary_grade form-control " name="salary_grade" id="salary_grade" disabled>
                        <input type="hidden" class="salary_grade_id form-control " name="salary_grade_id" id="salary_grade_id">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Salary Grade Step<span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line salary_grade_step_select">
                        <!-- <select class="salary_grade_step_id form-control " name="salary_grade_step_id" id="salary_grade_step_id" data-live-search="true">
                            <option value=""></option>
                        </select> -->
                        <input type="text" class="salary_grade_step form-control " name="salary_grade_step" id="salary_grade_step" disabled>
                        <input type="hidden" class="salary_grade_step_id form-control " name="salary_grade_step_id" id="salary_grade_step_id">
                        
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Salary <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" name="salary" id="salary" class="salary form-control"  readonly>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="row clearfix">
            <div class="col-md-2">
                <input type="checkbox" id="md_checkbox_26" name="uses_biometrics" value="1" class="uses_biometrics1 filled-in chk-col-blue">
                <label for="md_checkbox_26">Uses Biometrics</label>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-2">
                <input type="checkbox" id="md_checkbox_27" name="with_late" value="1" class="with_late1 filled-in chk-col-blue">
                <label for="md_checkbox_27">With Late</label>
            </div>
            <div class="col-md-2">
                <input type="checkbox" id="md_checkbox_30" name="with_overtime" value="1" class="with_overtime1 filled-in chk-col-blue">
                <label for="md_checkbox_30">With Overtime</label>
            </div>
            <div class="col-md-2">
                <input type="checkbox" id="md_checkbox_28" name="regular_shift" value="1" class="regular_shift1 filled-in chk-col-blue">
                <label for="md_checkbox_28">Regular Shift</label>
            </div>
            <div class="col-md-6 shift_container" style="display:none;">
                <div class="form-group">
                    <div class="form-line shift_select">
                        <select class="shift_id form-control " name="shift_id" id="shift_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix" id="jo_tax_container">
            <div class="col-md-2">
                <input type="checkbox" id="md_checkbox_2z" name="with_tax" value="1" class="with_tax1 filled-in chk-col-blue">
                <label for="md_checkbox_2z">With Tax</label>
            </div>
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
        <div class="row clearfix" id="monthly_tax_container" style="display:none;">
            <div class="col-md-2">
                <input type="checkbox" id="md_checkbox_2t" name="with_tax" value="1" class="with_tax1 filled-in chk-col-blue" >
                <label for="md_checkbox_2t">With Tax</label>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                <label for="email_address_2">Tax Percentage(%):</label>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" value=".00" id="tax_percentage" name="tax_percentage" class="tax_percentage form-control" readonly>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                <label for="email_address_2">Tax Additionals (Php.):</label>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" value=".00" id="tax_additional" name="tax_additional" class="tax_additional form-control" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-2">
                <input type="checkbox" id="md_checkbox_2a" name="with_gsis" value="1" class="with_gsis1 filled-in chk-col-blue">
                <label for="md_checkbox_2a">GSIS</label>
            </div>
            <div class="col-md-2">
                <input type="checkbox" id="md_checkbox_2g" name="with_sss" value="1" class="with_sss1 filled-in chk-col-blue">
                <label for="md_checkbox_2g">SSS</label>
            </div>
            <div class="col-md-3">
                <input type="checkbox" id="md_checkbox_2d" name="with_philhealth_contribution" value="1" class="with_philhealth_contribution1 filled-in chk-col-blue">
                <label for="md_checkbox_2d">Philhealth Contribution</label>
            </div>
            <div class="col-md-2">
                <input type="checkbox" id="md_checkbox_2c" name="with_pagibig_contribution" value="1" class="with_pagibig_contribution1 filled-in chk-col-blue">
                <label for="md_checkbox_2c">Pagibig Contribution</label>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" value="100.00" id="pagibig_contribution" name="pagibig_contribution" class="pagibig_contribution form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-2">
                <input type="checkbox" id="md_checkbox_2v" name="with_acpcea" value="1" class="with_acpcea1 filled-in chk-col-blue">
                <label for="md_checkbox_2v">E.C.C.</label>
            </div>
            <div class="col-md-2">
                <input type="checkbox" id="md_checkbox_2b" name="with_e_cola" value="1" class="with_e_cola1 filled-in chk-col-blue">
                <label for="md_checkbox_2b">E-Cola</label>
            </div>
            <div class="col-md-2">
                <input type="checkbox" id="md_checkbox_2x" name="with_pera" value="1" class="with_pera1 filled-in chk-col-blue">
                <label for="md_checkbox_2x">PERA</label>
            </div>
            
        </div>
        <div class="row clearfix">
            <div class="col-lg-1 col-md-1 col-sm-4 col-xs-5 form-control-label">
                <label for="email_address_2">PSMBFI:</label>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" value="0.00" id="psmbfi_amt" name="psmbfi_amt" class="psmbfi_amt form-control">
                    </div>
                </div>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-4 col-xs-5 form-control-label">
                <label for="email_address_2">Provident:</label>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" value="0.00" id="provident_amt" name="provident_amt" class="provident_amt form-control">
                    </div>
                </div>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-4 col-xs-5 form-control-label">
                <label for="email_address_2">Grocery:</label>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" value="0.00" id="grocery_amt" name="grocery_amt" class="grocery_amt form-control">
                    </div>
                </div>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-4 col-xs-5 form-control-label">
                <label for="email_address_2">Damayan:</label>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" value="0.00" id="damayan_amt" name="damayan_amt" class="damayan_amt form-control">
                    </div>
                </div>
            </div>
        </div> -->
        <br>
        <!-- <div class="row clearfix">
            <div class="col-md-4">
                <label class="form-label">Fund Source <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line fund_source_select">
                        <select class="fund_source_id form-control " name="fund_source_id" id="fund_source_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tax Code</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="tax_code" id="tax_code" class="tax_code form-control">
                    </div>
                </div>
            </div>
        </div> -->
        <!-- <div class="row clearfix">
            <div class="col-md-4">
                <label class="form-label">Budget Classification</label>
                <div class="form-group">
                    <div class="form-line budget_classification_select">
                        <select class="budget_classification_id form-control " name="budget_classification_id" id="budget_classification_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <label class="form-label">Services</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="services" id="services" class="services form-control"  readonly>
                    </div>
                </div>
            </div>
        </div>  -->
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addEmployees"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add Employee</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateEmployees"): ?>
	        <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update</span>
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

