<?php 
    $required = ""; 
    $inputRequired = "inputRequired"; 
?>

<style>

.EdBackCSS input {
    /* height: 80px !important;  */
    padding-top: 15px;
    padding-bottom: 65px;
}
.EdBackCSS1 textarea {
    height: 80px !important; 
}

/* Set height for input elements within the element with class "EdBackCSS" */

</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h1>PERSONAL DATA SHEET</h1>
            </div>
            <div class="body">
                <form id="<?php echo $key; ?>" enctype="multipart/form-data" accept-charset="utf-8">
                    <input type="hidden" class="id" name="id" id="id" value="<?php echo isset($key) && $key === 'pds' ? Helper::get('employee_id'):''; ?>">
                    <!-- <input type="hidden" name="acc" id="acc" value="<?php //echo ($access) ? 1 : 0; ?>"> -->
                    
                    <h3><small>PERSONAL INFORMATION</small></h3>
                    <fieldset>
                        <h3>I. PERSONAL INFORMATION</h3>
                        <hr>
                        <div class="row clearfix">
                            <div id="aniimated-thumbnials">
                                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-2"></div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-8">
                                    <a class="image-tag" href="<?php echo base_url(); ?>assets/custom/images/default-avatar.jpg" data-sub-html="Employee Avatar">
                                        <img id="employeeImage" class="img-responsive thumbnail" src="<?php echo base_url(); ?>assets/custom/images/default-avatar.jpg" style="display:none; width: 100%;height:auto;">
                                    </a>
                                    <div id="my_camera" style="height: 297px;weight: 297px;"></div>

                                    <?php if($key != "viewEmployees"): ?>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <button id="upload" class="upload form-control btn btn-info btn-sm waves-effect" type="button">
                                                <i class="material-icons">file_upload</i><span>UPLOAD PHOTO (4.5cm X 3.6cm)</span>
                                            </button>
                                            <div class="hiddenfile" style="width: 0px; height: 0px;overflow: hidden;">
                                                <input id="fileupload" class="fileupload" name="" accept="image/*" type="file">
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-6 col-sm-6">
                                            <button id="take_snapshot" class="form-control btn btn-success btn-sm waves-effect" type="button">
                                                <i class="material-icons">camera</i><span> Take Snapshot</span>
                                            </button>
                                            <button id="reset_snapshot" style="display:none;" class="form-control btn btn-success btn-sm waves-effect" type="button">
                                                <i class="material-icons">camera</i><span> Reset Snapshot</span>
                                            </button>
                                        </div> -->
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <?php if($this->uri->segment(2) != "PDS"): ?>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">EMPLOYEE NO.</label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control employee_id_number" name="employee_id_number" id="employee_id_number" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">BIOMETRICS NO. <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control employee_number <?php echo $inputRequired; ?>" name="employee_number" id="employee_number" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">SERVICE / DIVISION / UNIT <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-line division_select"  style="z-index:2">
                                        <select class="division_id form-control" name="division_id" id="division_id" data-live-search="true">
                                            <option value="" selected></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">EMPLOYEE NAME</label>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-float">
                                <label class="form-label">FIRST NAME <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                        <input type="text" class="form-control first_name <?php echo $inputRequired; ?>" name="first_name" id="first_name" style="text-transform:uppercase;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-float">
                                <label class="form-label">MIDDLE NAME</label>
                                    <div class="form-line">
                                        <input type="text" class="form-control middle_name" name="middle_name" id="middle_name" style="text-transform:uppercase;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-float">
                                <label class="form-label">SURNAME<span class="text-danger">*</span></label>
                                    <div class="form-line">
                                        <input type="text" class="form-control last_name <?php echo $inputRequired; ?>" name="last_name" id="last_name" style="text-transform:uppercase;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-float">
                                    <label class="form-label">NAME EXTENSION (JR.,SR)</label>
                                    <div class="form-line">
                                        <input type="text" class="form-control extension" name="extension" id="extension" style="text-transform:uppercase;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">DATE OF BIRTH (mm/dd/yyyy) <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control birthday date_mask <?php echo $inputRequired; ?>" name="birthday" id="birthday">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">PLACE OF BIRTH <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control birth_place <?php echo $inputRequired; ?>" name="birth_place" id="birth_place" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">SEX <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-line gender_select">
                                        <select class="gender <?php echo $inputRequired; ?> form-control " name="gender" id="gender" data-live-search="true" required>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">CIVIL STATUS <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-line civil_status_select" style="z-index:2">
                                        <select class="civil_status form-control " name="civil_status" id="civil_status" data-live-search="true" required>
                                            <option value="Single" selected>Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Widowed">Widowed</option>
                                            <option value="Annulled">Annulled</option>
                                            <option value="Divorced">Divorced</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="display:none;">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">OTHERS <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control civil_status_others" name="civil_status_others" id="civil_status_others" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">HEIGHT (cm) <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="number" class="form-control height <?php echo $inputRequired; ?>" name="height" id="height" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">WEIGHT (kg) <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="number" class="form-control weight <?php echo $inputRequired; ?>" name="weight" id="weight"<?php echo $required; ?> >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">BLOOD TYPE <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control bloodtype <?php echo $inputRequired; ?>" name="bloodtype" id="bloodtype" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">GSIS ID NO <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control gsis <?php echo $inputRequired; ?>" placeholder="00-000-000-000" name="gsis" id="gsis" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">PAG-IBIG ID NO <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control pagibig <?php echo $inputRequired; ?>" placeholder="00-000-000-000" name="pagibig" id="pagibig" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">PHILHEALTH NO <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control philhealth <?php echo $inputRequired; ?>" placeholder="00-000000000-0" name="philhealth" id="philhealth" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">SSS NO <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control sss <?php echo $inputRequired; ?>" placeholder="00-0000000-0" name="sss" id="sss" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">TIN NO <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control tin <?php echo $inputRequired; ?>" placeholder="000-000-000-000" name="tin" id="tin" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <!-- <label class="form-label">AGENCY EMPLOYEE NO <span class="text-danger">*</span></label> -->
                            </div>
                            <div class="col-md-4">
                                <!-- <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control ageny_employee_no <?php //echo $inputRequired; ?>" name="ageny_employee_no" id="ageny_employee_no" <?php //echo $required; ?>>
                                    </div>
                                </div> -->
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">CITIZENSHIP <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="form-line">
                                        <select name="nationality" id="nationality" class="nationality form-control" value="Filipino">
                                            <option value="Filipino">Filipino</option>
                                            <option value="Dual_Citizenship">Dual Citizenship</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="form-line">
                                        <select name="nationality_details" id="nationality_details" class="nationality_details form-control" value="By_Birth">
                                            <option value="By_Birth">By Birth</option>
                                            <option value="By_Naturalization">By Naturalization</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="display:none;">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">PLS. INDICATE COUNTRY <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control nationality_country" name="nationality_country" id="nationality_country" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">RESIDENTIAL ADDRESS</label>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-float"><label class="form-label">HOUSE/BLOCK/LOT NO. <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                    <textarea name="house_number" id="house_number"<?php echo $required; ?> class="house_number <?php echo $inputRequired; ?> form-control no-resize auto-growth" rows="1" style="overflow: hidden; overflow-wrap: break-word;"></textarea>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-float"><label class="form-label">STREET <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                    <textarea name="street" id="street"<?php echo $required; ?> class="street <?php echo $inputRequired; ?> form-control no-resize auto-growth" rows="1" style="overflow: hidden; overflow-wrap: break-word;"></textarea>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-float"><label class="form-label">SUBDIVISION/VILLAGE <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                    <textarea name="village" id="village"<?php echo $required; ?> class="village <?php echo $inputRequired; ?> form-control no-resize auto-growth" rows="1" style="overflow: hidden; overflow-wrap: break-word;"></textarea>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-float"><label class="form-label">BARANGAY <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                    <textarea name="barangay" id="barangay"<?php echo $required; ?> class="barangay <?php echo $inputRequired; ?> form-control no-resize auto-growth" rows="1" style="overflow: hidden; overflow-wrap: break-word;"></textarea>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-float"><label class="form-label">CITY/MUNICIPALITY <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                    <textarea name="municipality" id="municipality"<?php echo $required; ?> class="municipality <?php echo $inputRequired; ?> form-control no-resize auto-growth" rows="1" style="overflow: hidden; overflow-wrap: break-word;"></textarea>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-float"><label class="form-label">PROVINCE <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                    <textarea name="province" id="province"<?php echo $required; ?> class="province <?php echo $inputRequired; ?> form-control no-resize auto-growth" rows="1" style="overflow: hidden; overflow-wrap: break-word;"></textarea>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-float"><label class="form-label">ZIPCODE <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                    <textarea name="zip_code" id="zip_code"<?php echo $required; ?> class="zip_code <?php echo $inputRequired; ?> form-control no-resize auto-growth" rows="1" style="overflow: hidden; overflow-wrap: break-word;"></textarea>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">PERMANENT ADDRESS</label>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-float"><label class="form-label">HOUSE/BLOCK/LOT NO. <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                    <textarea name="permanent_house_number" id="permanent_house_number"<?php echo $required; ?> class="permanent_house_number <?php echo $inputRequired; ?> form-control no-resize auto-growth" rows="1" style="overflow: hidden; overflow-wrap: break-word;"></textarea>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-float"><label class="form-label">STREET <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                    <textarea name="permanent_street" id="permanent_street"<?php echo $required; ?> class="permanent_street <?php echo $inputRequired; ?> form-control no-resize auto-growth" rows="1" style="overflow: hidden; overflow-wrap: break-word;"></textarea>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-float"><label class="form-label">SUBDIVISION/VILLAGE <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                    <textarea name="permanent_village" id="permanent_village"<?php echo $required; ?> class="permanent_village <?php echo $inputRequired; ?> form-control no-resize auto-growth" rows="1" style="overflow: hidden; overflow-wrap: break-word;"></textarea>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-float"><label class="form-label">BARANGAY <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                    <textarea name="permanent_barangay" id="permanent_barangay"<?php echo $required; ?> class="permanent_barangay <?php echo $inputRequired; ?> form-control no-resize auto-growth" rows="1" style="overflow: hidden; overflow-wrap: break-word;"></textarea>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-float"><label class="form-label">CITY/MUNICIPALITY <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                    <textarea name="permanent_municipality" id="permanent_municipality"<?php echo $required; ?> class="permanent_municipality <?php echo $inputRequired; ?> form-control no-resize auto-growth" rows="1" style="overflow: hidden; overflow-wrap: break-word;"></textarea>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-float"><label class="form-label">PROVINCE <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                    <textarea name="permanent_province" id="permanent_province"<?php echo $required; ?> class="permanent_province <?php echo $inputRequired; ?> form-control no-resize auto-growth" rows="1" style="overflow: hidden; overflow-wrap: break-word;"></textarea>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-float"><label class="form-label">ZIPCODE <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                    <textarea name="permanent_zip_code" id="permanent_zip_code"<?php echo $required; ?> class="permanent_zip_code <?php echo $inputRequired; ?> form-control no-resize auto-growth" rows="1" style="overflow: hidden; overflow-wrap: break-word;"></textarea>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">TELEPHONE NO </label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control tel_no" name="tel_no" id="tel_no" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">MOBILE NO <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control contact_number <?php echo $inputRequired; ?>" name="contact_number" id="contact_number" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">E-MAIL ADDRESS <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control email <?php echo $inputRequired; ?>" name="email" id="email" style="text-transform: unset;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h3>II. FAMILY BACKGROUND</h3>
                        <hr>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">SPOUSE'S NAME</label>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-float">
                                <label class="form-label">FIRST NAME <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                        <input type="text" class="form-control spouse_first_name <?php echo $inputRequired; ?>" name="spouse_first_name" id="spouse_first_name" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-float">
                                <label class="form-label">MIDDLE NAME</label>
                                    <div class="form-line">
                                        <input type="text" class="form-control spouse_middle_name" name="spouse_middle_name" id="spouse_middle_name" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-float">
                                <label class="form-label">SURNAME<span class="text-danger">*</span></label>
                                    <div class="form-line">
                                        <input type="text" class="form-control spouse_last_name <?php echo $inputRequired; ?>" name="spouse_last_name" id="spouse_last_name" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-float">
                                    <label class="form-label">NAME EXTENSION (JR.,SR)</label>
                                    <div class="form-line">
                                        <input type="text" class="form-control spouse_extension" name="spouse_extension" id="spouse_extension" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">OCCUPATION <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control spouse_occupation <?php echo $inputRequired; ?>" name="spouse_occupation" id="spouse_occupation"  <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">EMPLOYER / BUSINESS NAME <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control spouse_employer_name <?php echo $inputRequired; ?>" name="spouse_employer_name" id="spouse_employer_name"  <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">BUSINESS ADDRESS <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control spouse_business_address <?php echo $inputRequired; ?>" name="spouse_business_address" id="spouse_business_address"  <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">TELEPHONE NO <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control spouse_tel_no" name="spouse_tel_no" id="spouse_tel_no" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">FATHER'S NAME</label>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-float">
                                <label class="form-label">FIRST NAME <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                        <input type="text" class="form-control father_first_name <?php echo $inputRequired; ?>" name="father_first_name" id="father_first_name" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-float">
                                <label class="form-label">MIDDLE NAME</label>
                                    <div class="form-line">
                                        <input type="text" class="form-control father_middle_name" name="father_middle_name" id="father_middle_name" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-float">
                                <label class="form-label">SURNAME <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                        <input type="text" class="form-control father_last_name <?php echo $inputRequired; ?>" name="father_last_name" id="father_last_name" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-float">
                                    <label class="form-label">NAME EXTENSION (JR.,SR)</label>
                                    <div class="form-line">
                                        <input type="text" class="form-control father_extension" name="father_extension" id="father_extension" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">MOTHER'S (MAIDEN) NAME</label>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-float">
                                <label class="form-label">FIRST NAME <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                        <input type="text" class="form-control mother_first_name <?php echo $inputRequired; ?>" name="mother_first_name" id="mother_first_name" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-float">
                                <label class="form-label">MIDDLE NAME</label>
                                    <div class="form-line">
                                        <input type="text" class="form-control mother_middle_name" name="mother_middle_name" id="mother_middle_name" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-float">
                                <label class="form-label">SURNAME <span class="text-danger">*</span></label>
                                    <div class="form-line">
                                        <input type="text" class="form-control mother_last_name <?php echo $inputRequired; ?>" name="mother_last_name" id="mother_last_name" <?php echo $required; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-float">
                                    <label class="form-label">NAME EXTENSION (JR.,SR)</label>
                                    <div class="form-line">
                                        <input type="text" class="form-control mother_extension" name="mother_extension" id="mother_extension" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <label class="text-danger">All fields inside the table is required. Input "N/A" in first column if not available.</label>
                                    
                                    <table cellspacing="5" class="table table-bordered" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>NAME OF CHILDREN<br>(Write full name and list all)</th>
                                                <th>DATE OF BIRTH<br>(mm/dd/yyyy)</th>
                                                <th>ACTION <button type="button" id="btnAddCH" class="btn btn-primary btn-sm" style="float: right;"><i class="material-icons">add</i></button></th>
                                            </tr>
                                            
                                        </thead>
                                        <tbody id="tbchildres">
                                
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                        <textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control <?php echo $inputRequired; ?> no-resize auto-growth" name="children_name[0]" id="children_name[0]"  <?php echo $required; ?>></textarea>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" class="form-control is_first_col_required_birthday date_mask <?php //echo $inputRequired; ?>" name="children_birthday[0]" id="children_birthday[0]"  <?php echo $required; ?>>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>
                                            </tr>
                                            
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                        <h3>III. EDUCATIONAL BACKGROUND</h3>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <label class="text-danger">All fields inside the table is required. Input "N/A" in first column if not available.</label>
                                    <table cellspacing="5" class="table table-bordered EdBackCSS EdBackCSS1" style="width:100%;"> 
                                        <thead>
                                            <tr>
                                                <th rowspan="2" style="min-width: 130px">LEVEL</th>
                                                <th rowspan="2">NAME OF SCHOOL<br>(Write in full)</th>
                                                <th rowspan="2">BASIC EDUCATION / DEGREE / COURSE<br>(Write in full)</th>
                                                <th colspan="2">PERIOD OF ATTENDANCE</th>
                                                <th rowspan="2">HIGHEST LEVEL / UNITS EARNED<br>(if not graduated)</th>
                                                <th rowspan="2">YEAR GRADUATED</th>
                                                <th rowspan="2">SCHOLARSHIP / ACADEMIC HONORS RECEIVED</th>
                                                <th rowspan="2">ACTION</th>
                                            </tr>
                                            <tr>
                                                <th>From</th>
                                                <th>To</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="headcol">ELEMENTARY</td>
                                                <td style="min-width: 250px"><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth elementary_school inputRequired" name="elementary_school[0]" id="elementary_school[0]" ></textarea></div></div></td>
                                                <td style="min-width: 250px"><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_sec_col_required elementary_degree" name="elementary_degree[0]" id="elementary_degree[0]" ></textarea></div></div></td>
                                                <td style="min-width: 125px"><div class="form-group"><div class="form-line"><input type="text" class="form-control attendancePeriod is_sec_col_required elementary_period_from" name="elementary_period_from[0]" id="elementary_period_from[0]" ></div></div></td>
                                                <td style="min-width: 125px"><div class="form-group"><div class="form-line"><input type="text" class="form-control attendancePeriod is_sec_col_required elementary_period_to" name="elementary_period_to[0]" id="elementary_period_to[0]" ></div></div></td>
                                                <td style="min-width: 125px"><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_sec_col_required elementary_highest_level" name="elementary_highest_level[0]" id="elementary_highest_level[0]" ></textarea></div></div></td>
                                                <td style="min-width: 125px"><div class="form-group"><div class="form-line"><input type="text" class="form-control attendancePeriod is_sec_col_required elementary_year_graduated" name="elementary_year_graduated[0]" id="elementary_year_graduated[0]" maxlength="4" ></div></div></td>
                                                <td style="min-width: 125px"><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_sec_col_required elementary_received" name="elementary_received[0]" id="elementary_received[0]" ></textarea></div></div></td>
                                                <td style="min-width: 95px"><button type="button" class="btn btn-primary btn-sm btnAddELEM" style="float: right;"><i class="material-icons">add</i></button></td>
                                            </tr>
                                            <tr>
                                                <td class="headcol">SECONDARY</td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth secondary_school inputRequired" name="secondary_school[0]" id="secondary_school[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_sec_col_required secondary_degree" name="secondary_degree[0]" id="secondary_degree[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control attendancePeriod is_sec_col_required secondary_period_from" name="secondary_period_from[0]" id="secondary_period_from[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control attendancePeriod is_sec_col_required secondary_period_to" name="secondary_period_to[0]" id="secondary_period_to[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_sec_col_required secondary_highest_level" name="secondary_highest_level[0]" id="secondary_highest_level[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control attendancePeriod is_sec_col_required secondary_year_graduated" name="secondary_year_graduated[0]" id="secondary_year_graduated[0]" maxlength="4" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_sec_col_required secondary_received" name="secondary_received[0]" id="secondary_received[0]" ></textarea></div></div></td>
                                                <td><button type="button" class="btn btn-primary btn-sm btnAddSEC" style="float: right;"><i class="material-icons">add</i></button></td>
                                            </tr>
                                            <tr>
                                                <td class="headcol">VOCATIONAL / <br>TRADE COURSE</td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth vocational_school inputRequired" name="vocational_school[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_sec_col_required vocational_degree" name="vocational_degree[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control attendancePeriod is_sec_col_required vocational_period_from" name="vocational_period_from[0]"></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control attendancePeriod is_sec_col_required vocational_period_to" name="vocational_period_to[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_sec_col_required vocational_highest_level" name="vocational_highest_level[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control attendancePeriod is_sec_col_required vocational_year_graduated" name="vocational_year_graduated[0]" maxlength="4" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_sec_col_required vocational_received" name="vocational_received[0]" ></textarea></div></div></td>
                                                <td><button type="button" class="btn btn-primary btn-sm btnAddVTC" style="float: right;"><i class="material-icons">add</i></button></td>
                                            </tr>
                                            <tr>
                                                <td class="headcol">COLLEGE</td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth college_school inputRequired" name="college_school[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_sec_col_required college_degree" name="college_degree[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control attendancePeriod is_sec_col_required college_period_from" name="college_period_from[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control attendancePeriod is_sec_col_required college_period_to" name="college_period_to[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_sec_col_required college_highest_level" name="college_highest_level[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control attendancePeriod is_sec_col_required college_year_graduated" name="college_year_graduated[0]" maxlength="4" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_sec_col_required college_received" name="college_received[0]" ></textarea></div></div></td>
                                                <td><button type="button" class="btn btn-primary btn-sm btnAddC" style="float: right;"><i class="material-icons">add</i></button></td>
                                            </tr>
                                            <tr>
                                                <td class="headcol">GRADUATE<br>STUDIES</td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth grad_stud_school inputRequired" name="grad_stud_school[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_sec_col_required grad_stud_degree" name="grad_stud_degree[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control attendancePeriod is_sec_col_required grad_stud_period_from" name="grad_stud_period_from[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control attendancePeriod is_sec_col_required grad_stud_period_to" name="grad_stud_period_to[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_sec_col_required grad_stud_highest_level" name="grad_stud_highest_level[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control attendancePeriod is_sec_col_required grad_stud_year_graduated" name="grad_stud_year_graduated[0]" maxlength="4" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_sec_col_required grad_stud_received" name="grad_stud_received[0]" ></textarea></div></div></td>
                                                <td><button type="button" class="btn btn-primary btn-sm btnAddGS" style="float: right;"><i class="material-icons">add</i></button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <h3><small>WORK EXPERIENCE AND ELIGIBILITY</small></h3>
                    <fieldset>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="text-danger">All fields inside the table is required. Input "N/A" in first column if not available.</label>
                                <h3>IV. CIVIL SERVICE ELIGIBILITY</h3>
                                <hr>
                                <div class="table-responsive">
                                    <table cellspacing="5" class="table table-bordered" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" style="min-width: 250px">CAREER SERVICE / RA 1080 (BOARD / BAR) UNDER SPECIAL LAWS / CES / CSEE / BARANGAY ELIGIBILITY / DRIVER'S LICENSE</th>
                                                <th rowspan="2" style="min-width: 125px">RATING<br>(if applicable)</th>
                                                <th rowspan="2" style="min-width: 200px">DATE OF EXAMINATION / CONFERMENT</th>
                                                <th rowspan="2" style="min-width: 250px">PLACE OF EXAMINATION / CONFERMENT</th>
                                                <th colspan="2" align="center">LICENSE<br>(if applicable)</th>
                                                <th rowspan="2" style="min-width: 125px">ACTION <button type="button" id="btnAddCSE" class="btn btn-primary btn-sm" style="float: right;"><i class="material-icons">add</i></button></th>
                                            </tr>
                                            <tr>
                                                <th style="min-width: 125px">NUMBER</th>
                                                <th style="min-width: 125px">DATE OF VALIDITY</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbcse">
                                            <tr>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth  inputRequired" name="civil_service_eligibility[0]" id="civil_service_eligibility[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control is_first_col_required" name="rating[0]" id="rating[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control is_first_col_required_examination date_picker" name="date_conferment[0]" id="date_conferment[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_first_col_required" name="place_examination[0]" id="place_examination[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth" name="license_number[0]" id="license_number[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control date_mask" name="license_validity[0]" id="license_validity[0]" ></div></div></td>
                                                <td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h3>V. WORK EXPERIENCE</h3>
                                <small>(Include private employment.  Start from your recent work) Description of duties should be indicated in the attached Work Experience sheet.</small>
                                <hr>
                                <div class="table-responsive">
                                    <label class="text-danger">All fields inside the table is required. Input "N/A" in third column if not available.</label>
                                    <table cellspacing="5" class="table table-bordered" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th colspan="2" align="center">INCLUSIVE DATES<br>(mm/dd/yyyy)</th>
                                                <th rowspan="2" style="min-width: 350px">POSITION TITLE<br>(Write in full / Do not abbreviate)</th>
                                                <th rowspan="2" style="min-width: 350px">DEPARTMENT / AGENCY / OFFICE / COMPANY<br>(Write in full / Do not abbreviate)</th>
                                                <th rowspan="2" style="min-width: 125px">MONTHLY SALARY</th>
                                                <th rowspan="2" style="min-width: 125px">SALARY / JOB / PAY GRADE (if applicable) & STEP (Format "00-0") / INCREMENT</th>
                                                <th rowspan="2" style="min-width: 200px">STATUS OF APPOINTMENT</th>
                                                <th rowspan="2" style="min-width: 90px">GOV'T SERVICE (Y/N)</th>
                                                <th rowspan="2" style="min-width: 125px">ACTION <!--<button type="button" id="btnAddWE" class="btn btn-primary btn-sm" style="float: right;"><i class="material-icons">add</i></button>--></th>
                                            </tr>
                                            <tr>
                                                <th style="min-width: 125px" align="center">FROM</th>
                                                <th style="min-width: 125px" align="center">TO</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbworkexp">
                                            <tr>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control is_third_col_required_from date_mask" name="work_from[0]" id="work_from[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control is_third_col_required_to date_mask" name="work_to[0]" id="work_to[0]" ></div><div class="form-group"><input type="checkbox" class="chk is_work_present" > PRESENT</div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth  inputRequired" name="position[0]" id="position[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_third_col_required" name="company[0]" id="company[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control is_third_col_required currency3" name="monthly_salary[0]" id="monthly_salary[0]" >
                                                    <input type="checkbox" name="day_check[0]" style="position:absolute;left:72px;top:6px;opacity:1;"> 
                                                    <span style="position:absolute;left:88px;top:6px;opacity:1;">/day</span></div></div>
                                                </td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control salaryGrade is_third_col_required" name="grade[0]" id="grade[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_third_col_required" name="status_appointment[0]" id="status_appointment[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control is_third_col_required govtservice" name="gov_service[0]" id="gov_service[0]" maxlength="1"  style="text-transform:uppercase;"></div></div></td>
                                                <th>
                                                    <button type="button" class="btn btn-primary btn-sm addRow" style="float: right"><i class="material-icons">add</i></button>
                                                    <button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button>
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <h3><small>TRAININGS/SEMINARS AND CIVIC WORK</small></h3>
                    <fieldset>
                        <div class="row">
                            <div class="col-md-12">
                                <h3>VI. VOLUNTARY WORK OR INVOLVEMENT IN CIVIC / NON-GOVERNMENT / PEOPLE / VOLUNTARY ORGANIZATION/S</h3>
                                <hr>
                                <div class="table-responsive">
                                    <label class="text-danger">All fields inside the table is required. Input "N/A" in first column if not available.</label>
                                    <table cellspacing="5" class="table table-bordered" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" style="min-width: 350px">NAME & ADDRESS OF ORGANIZATION/S<br>(Write in full)</th>
                                                <th colspan="2" align="center">INCLUSIVE DATES OF ATTENDANCE<br>(mm/dd/yyyy)</th>
                                                <th rowspan="2" style="min-width: 70px">NUMBER OF<br>HOURS</th>
                                                <th rowspan="2" style="min-width: 125px">POSITION / NATURE OF WORK</th>
                                                <th rowspan="2" style="min-width: 125px">ACTION <button type="button" id="btnAddVW" class="btn btn-primary btn-sm" style="float: right;"><i class="material-icons">add</i></button></th>
                                            </tr>
                                            <tr>
                                                <th style="min-width: 125px" align="center">FROM</th>
                                                <th style="min-width: 125px" align="center">TO</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbvoluntarywork">
                                            <tr>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth  inputRequired" name="organization[0]" id="organization[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control is_first_col_required date_mask attendance_from" name="organization_work_from[0]" id="organization_work_from[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control is_first_col_required date_mask attendance_to" name="organization_work_to[0]" id="organization_work_to[0]" ></div><div class="form-group"><input type="checkbox" class="chk is_work_present" > PRESENT</div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="number" class="form-control is_first_col_required" name="organization_number_hours[0]" id="organization_number_hours[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth  is_first_col_required" name="organization_work_nature[0]" id="organization_work_nature[0]" ></textarea></div></div></td>
                                                <td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h3>VII. LEARNING AND DEVELOPMENT (L&D) INTERVENTIONS/TRAINING PROGRAMS ATTENDED</h3>
                                <small>Start from the most recent L&D/training program and include only the relevant L&D/training taken for the last five (5) years for Division Chief/Executive/Managerial positions)</small>
                                <hr>
                                <div class="table-responsive">
                                    <label class="text-danger">All fields inside the table is required. Input "N/A" in first column if not available.</label>
                                    <table cellspacing="5" class="table table-bordered" style="width:100%;">
                                        <thead>
                                            <tr>
                                                
                                                <th rowspan="2" style="min-width: 250px">TITLE OF LEARNING AND DEVELOPMENT INTERVENTIONS / TRAINING PROGRAMS<br>(Write in full)</th>
                                                <th colspan="2" align="center">INCLUSIVE DATES OF ATTENDANCE<br>(mm/dd/yyyy)</th>
                                                <th rowspan="2" style="min-width: 125px">NUMBER OF HOURS</th>
                                                <th rowspan="2" style="min-width: 125px">TYPE OF L&D (Managerial / Supervisory / Technical / etc.)</th>
                                                <th rowspan="2" style="min-width: 220px">CONDUCTED / SPONSORED BY<br>(Write in full)</th>
                                                <th rowspan="2" style="min-width: 125px">ACTION<!-- <button type="button" id="btnAddLDI" class="btn btn-primary btn-sm" style="float: right;"><i class="material-icons">add</i></button>--></th>
                                            </tr>
                                            <tr>
                                                <th style="min-width: 125px" align="center">FROM</th>
                                                <th style="min-width: 125px" align="center">TO</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tblearnings">
                                            <tr>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth  inputRequired" name="training[0]" id="training[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control is_first_col_required traningfrom date_mask" name="traning_from[0]" id="traning_from[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control is_first_col_required traningto date_mask" name="training_to[0]" id="training_to[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="number" class="form-control is_first_col_required" name="training_number_hours[0]" id="training_number_hours[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_first_col_required" name="training_type[0]" id="training_type[0]" ></textarea></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth is_first_col_required" name="training_sponsored_by[0]" id="training_sponsored_by[0]" ></textarea></div></div></td>
                                                <th>
                                                    <button type="button" class="btn btn-primary btn-sm addRow2" style="float: right"><i class="material-icons">add</i></button>
                                                    <button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button>
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h3>VIII. OTHER INFORMATION</h3>
                                <hr>
                                <div class="table-responsive">
                                    <label class="text-danger">All fields inside the table is required. Input "N/A" if not available.</label>
                                    <table cellspacing="5" class="table table-bordered" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="width: 80%">SPECIAL SKILLS AND HOBBIES</th>
                                                <th>ACTION <button type="button" id="btnAddSSH" class="btn btn-primary btn-sm" style="float: right;"><i class="material-icons">add</i></button></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbspecialskils">
                                            <tr>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth  inputRequired" name="special_skills[0]" id="special_skills[0]" ></textarea></div></div></td>
                                                <td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <label class="text-danger">All fields inside the table is required. Input "N/A" if not available.</label>
                                    <table cellspacing="5" class="table table-bordered" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="width: 80%">NON-ACADEMIC DISTINCTIONS / RECOGNITION (Write in full)</th>
                                                <th>ACTION <button type="button" id="btnAddNAD" class="btn btn-primary btn-sm" style="float: right;"><i class="material-icons">add</i></button></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbrecognitions">
                                            <tr>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth  inputRequired" name="recognitions[0]" id="recognitions[0]" ></textarea></div></div></td>
                                                <td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <label class="text-danger">All fields inside the table is required. Input "N/A" if not available.</label>
                                    <table cellspacing="5" class="table table-bordered" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="width: 80%">MEMBERSHIP IN ASSOCIATION / ORGANIZATION (Write in full)</th>
                                                <th>ACTION <button type="button" id="btnAddMA" class="btn btn-primary btn-sm" style="float: right;"><i class="material-icons">add</i></button></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tborganizations">
                                            <tr>
                                                <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth  inputRequired" name="membership[0]" id="membership[0]" ></textarea></div></div></td>
                                                <td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <h3><small>OTHER INFORMATION</small></h3>
                    <fieldset>
                        <table id="tblqs" width="100%" class="table">
                            <tr>
                                <td colspan="3"><label class="form-label">34. Are you related by consanguinity or affinity to the appointing or recommending authority, or to the chief of bureau or office or to the person who has immediate supervision over you in the Office, Bureau or Department where you will appointed,</label></td>
                            </tr>
                            <tr>
                                <td width="55%"><label class="form-label">a. within the third degree <span class="text-danger">*</span></label></td>
                                <td width="15%">
                                    <div class="form-group">
                                        <input type="radio" class="chkradio" name="radio_input_01" id="radio_input_01_Yes" value="Yes">&nbsp;Yes&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="chkradio" name="radio_input_01" id="radio_input_01_No" value="No">&nbsp;No
                                    </div>
                                </td>
                                <td width="30%">
                                    <div class="form-group"><label class="form-label">If yes, give details</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control inputifyes if_yes_01" name="if_yes_01" >
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label class="form-label">b. within the fourth degree (for Local Government Unit - Career Employees)? <span class="text-danger">*</span></label></td>
                                <td>
                                    <div class="form-group">
                                        <input type="radio" class="chkradio" name="radio_input_02" id="radio_input_02_Yes" value="Yes">&nbsp;Yes&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="chkradio" name="radio_input_02" id="radio_input_02_No" value="No">&nbsp;No
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group"><label class="form-label">If yes, give details</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control inputifyes if_yes_02" name="if_yes_02" >
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label class="form-label">35. a. Have you ever been found guilty of any administrative offense? <span class="text-danger">*</span></label></td>
                                <td>
                                    <div class="form-group">
                                        <input type="radio" class="chkradio" name="radio_input_03" id="radio_input_03_Yes" value="Yes">&nbsp;Yes&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="chkradio" name="radio_input_03" id="radio_input_03_No" value="No">&nbsp;No
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group"><label class="form-label">If yes, give details</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control inputifyes if_yes_03" name="if_yes_03" >
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label class="form-label">b. Have you been criminally charged before any court? <span class="text-danger">*</span></label></td>
                                <td>
                                    <div class="form-group">
                                        <input type="radio" class="chkradio" name="radio_input_04" id="radio_input_04_Yes" value="Yes">&nbsp;Yes&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="chkradio" name="radio_input_04" id="radio_input_04_No" value="No">&nbsp;No
                                    </div>
                                </td>
                                <td>
                                <label class="form-label">If yes, give details</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group"><label class="form-label">Date Filed</label>
                                                <div class="form-line">
                                                    <input type="text" class="form-control inputifyes if_yes_04" name="if_yes_04" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group"><label class="form-label">Status of Case/s</label>
                                                <div class="form-line">
                                                    <input type="text" class="form-control inputifyes if_yes_case_status_04" name="if_yes_case_status_04" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label class="form-label">36. Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal? <span class="text-danger">*</span></label></td>
                                <td>
                                    <div class="form-group">
                                        <input type="radio" class="chkradio" name="radio_input_05" id="radio_input_05_Yes" value="Yes">&nbsp;Yes&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="chkradio" name="radio_input_05" id="radio_input_05_No" value="No">&nbsp;No
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group"><label class="form-label">If yes, give details</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control inputifyes if_yes_05" name="if_yes_05" >
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label>37. Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector? <span class="text-danger">*</span></label></td>
                                <td>
                                    <div class="form-group">
                                        <input type="radio" class="chkradio" name="radio_input_06" id="radio_input_06_Yes" value="Yes">&nbsp;Yes&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="chkradio" name="radio_input_06" id="radio_input_06_No" value="No">&nbsp;No
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group"><label class="form-label">If yes, give details</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control inputifyes if_yes_06" name="if_yes_06" >
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label class="form-label">38. a. Have you ever been a candidate in a national or local election held within the last year (except Barangay election)? <span class="text-danger">*</span></label></td>
                                <td>
                                    <div class="form-group">
                                        <input type="radio" class="chkradio" name="radio_input_07" id="radio_input_07_Yes" value="Yes">&nbsp;Yes&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="chkradio" name="radio_input_07" id="radio_input_07_No" value="No">&nbsp;No
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group"><label class="form-label">If yes, give details</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control inputifyes if_yes_07" name="if_yes_07" >
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label>b. Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate? <span class="text-danger">*</span></label></td>
                                <td>
                                    <div class="form-group">
                                        <input type="radio" class="chkradio" name="radio_input_08" id="radio_input_08_Yes" value="Yes">&nbsp;Yes&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="chkradio" name="radio_input_08" id="radio_input_08_No" value="No">&nbsp;No
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group"><label class="form-label">If yes, give details</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control inputifyes if_yes_08" name="if_yes_08" >
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label class="form-label">39. Have you acquired the status of an immigrant or permanent resident of another country? <span class="text-danger">*</span></label></td>
                                <td>
                                    <div class="form-group">
                                        <input type="radio" class="chkradio" name="radio_input_09" id="radio_input_09_Yes" value="Yes">&nbsp;Yes&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="chkradio" name="radio_input_09" id="radio_input_09_No" value="No">&nbsp;No
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group"><label class="form-label">If yes, give details (country)</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control inputifyes if_yes_09" name="if_yes_09" >
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3"><label>40. Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for Disabled Persons (RA 7277); and (c) Solo Parents Welfare Act of 2000 (RA 8972), please answer the following items:</label></td>
                            </tr>
                            <tr>
                                <td><label class="form-label">a. Are you a member of any indigenous group? <span class="text-danger">*</span></label></td>
                                <td>
                                    <div class="form-group">
                                        <input type="radio" class="chkradio" name="radio_input_10" id="radio_input_10_Yes" value="Yes">&nbsp;Yes&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="chkradio" name="radio_input_10" id="radio_input_10_No" value="No">&nbsp;No
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group"><label class="form-label">If yes, please specify</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control inputifyes if_yes_10" name="if_yes_10" >
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label class="form-label">b. Are you a person with disability? <span class="text-danger">*</span></label></td>
                                <td>
                                    <div class="form-group">
                                        <input type="radio" class="chkradio" name="radio_input_11" id="radio_input_11_Yes" value="Yes">&nbsp;Yes&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="chkradio" name="radio_input_11" id="radio_input_11_No" value="No">&nbsp;No
                                    </div>
                                </td>
                                <td>
                                <div class="form-group"><label class="form-label">If yes, please specify ID No</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control inputifyes if_yes_11" name="if_yes_11" >
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label class="form-label">c. Are you a solo parent? <span class="text-danger">*</span></label></td>
                                <td>
                                    <div class="form-group">
                                        <input type="radio" class="chkradio" name="radio_input_12" id="radio_input_12_Yes" value="Yes">&nbsp;Yes&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="chkradio" name="radio_input_12" id="radio_input_12_No" value="No">&nbsp;No
                                    </div>
                                </td>
                                <td>
                                <div class="form-group"><label class="form-label">If yes, please specify ID No</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control inputifyes if_yes_12" name="if_yes_12" >
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <label>REFERENCES (Person not related by consanguinity or affinity to applicant/appointee)</label>
                                <label class="text-danger">All fields inside the table is required. Input "N/A" in first column if not available.</label>
                                <div class="table-responsive">
                                    <table cellspacing="5" class="table table-bordered" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="min-width: 200px;">NAME</th>
                                                <th style="min-width: 200px;">ADDRESS</th>
                                                <th style="min-width: 200px;">TEL NO.</th>
                                                <th>ACTION <button type="button" id="btnAddRef" class="btn btn-primary btn-sm" style="float: right;"><i class="material-icons">add</i></button></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbreferences">
                                            <tr>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control inputRequired" name="reference_name[0]" id="reference_name[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control is_first_col_required" name="reference_address[0]" id="reference_address[0]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control is_first_col_required" name="reference_tel_no[0]" id="reference_tel_no[0]" ></div></div></td>
                                                <td><button type="button" class="btn btn-danger btn-sm deleteRowFourth" style="float: right"><i class="material-icons">remove</i></button></td>
                                            </tr>
                                            <tr>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control inputRequired" name="reference_name[1]" id="reference_name[1]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control is_first_col_required" name="reference_address[1]" id="reference_address[1]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control is_first_col_required" name="reference_tel_no[1]" id="reference_tel_no[1]" ></div></div></td>
                                                <td><button type="button" class="btn btn-danger btn-sm deleteRowFourth" style="float: right"><i class="material-icons">remove</i></button></td>
                                            </tr>
                                            <tr>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control inputRequired" name="reference_name[2]" id="reference_name[2]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control is_first_col_required" name="reference_address[2]" id="reference_address[2]" ></div></div></td>
                                                <td><div class="form-group"><div class="form-line"><input type="text" class="form-control is_first_col_required" name="reference_tel_no[2]" id="reference_tel_no[2]" ></div></div></td>
                                                <td><button type="button" class="btn btn-danger btn-sm deleteRowFourth" style="float: right"><i class="material-icons">remove</i></button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                        <label class="form-label">GOVERNMENT ISSUED ID <span class="text-danger">*</span></label></label>
                                    <div class="form-line">
                                    <textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;"<?php echo $required; ?> class="form-control no-resize auto-growth gov_issued_id inputRequired" name="gov_issued_id" id="gov_issued_id" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                        <label class="form-label">ID / License / Passport No <span class="text-danger">*</span></label></label>
                                    <div class="form-line">
                                    <textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;"<?php echo $required; ?> class="form-control no-resize auto-growth valid_id inputRequired" name="valid_id" id="valid_id" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                        <label class="form-label">Date / Place of Issuance <span class="text-danger">*</span></label></label>
                                    <div class="form-line">
                                    <textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;"<?php echo $required; ?> class="form-control no-resize auto-growth place_issuance inputRequired" name="place_issuance" id="place_issuance" ></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    
                    <?php if((isset($key)) && ($key === "pds" || $key === "addEmployees")): ?>
                    <h3><small>Attachments</small></h3>
                    <fieldset>
                    <?php if(isset($key) && $key === "pds"): ?>
                        <div class="row">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <br>
                                <h4>Attachments</h4>
                                <table class="table table-bordered" width="100%">
                                    <thead>
                                        <tr>
                                            <th>File Title</th>
                                            <th>Current File</th>
                                            <th>New File</th>
                                            <th>Action  <button type="button" id="btnViewAddFile" class="btn btn-info btn-sm pull-right"><i class="material-icons">add</i> Add File</button></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbFiles"></tbody>
                                </table>
                            </div>
                        </div>
                    <?php elseif(isset($key) && $key == "addEmployees"): ?>
                        <div class="row">
                            <div class="col-md-12 col-xs-12 col-sm-12"> 
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 col-sm-12">
                                        <br>
                                        <h4>Attachments</h4>
                                        <table class="table table-bordered" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>File Title</th>
                                                    <th>File Upload</th>
                                                    <th>Action <button type="button" id="btnAddFile" class="btn btn-info btn-sm pull-right"> + Add</button></th>
                                                </tr>
                                                <tbody id="tbFiles">
                                                    <tr>
                                                        <td><div class="form-group"><div class="form-line"><textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth" name="file_title[0]" id="file_title[0]" ></textarea></div></div></td>
                                                        <td><div class="form-group"><div class="form-line">
                                                            <input type="file" class="form-control is_first_col_required select_file" name="uploaded_file[0]" id="uploaded_file[0]" >
                                                            <input type="hidden" class="form-control" name="decoded_file[0]" id="decoded_file[0]" >
                                                        </div></div></td>
                                                        <td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right"><i class="material-icons">remove</i></button></td>
                                                    </tr>
                                                </tbody>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                    <?php endif; ?>
                    </fieldset>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

    

