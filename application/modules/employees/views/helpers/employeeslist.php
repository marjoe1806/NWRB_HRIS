<?php //var_dump($this->uri->segment(1));die(); ?>
<input type="hidden" id="hide_emp_status" value="<?php echo isset($_GET['EmploymentStatus'])?$_GET['EmploymentStatus']:"Active"; ?>">
    <div class="dvfilter">
        <div class="row">
            <div class="col-md-6">
                <h5 class="text-info">Service / Division / Unit</h5>
                <div class="form-group">
                    <div class="form-line division_select">
                        <select class="division_id form-control" name="division_id" id="division_id" data-live-search="true">
                            <option value="" selected></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <h5 class="text-info">Pay Basis</h5>
                <div class="form-group">
                    <div class="form-line pay_basis_select">
                        <select class="pay_basis form-control" name="pay_basis" id="pay_basis" data-live-search="true">
                            <option value="" selected></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <h5 class="text-info">Salary Grade</h5>
                <div class="form-group">
                    <div class="form-line salary_grade_select">
                        <select class="salary_grade_id form-control" name="salary_grade_id" id="salary_grade_id" data-live-search="true">
                            <option value="" selected></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <h5 class="text-info">Position</h5>
                <div class="form-group">
                    <div class="form-line position_name_select">
                        <select class="position_name form-control" name="position_name" id="position_name" data-live-search="true">
                            <option value="" selected></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <a id="viewFilteredEmployee" href="#" class="btn btn-block btn-lg btn-info waves-effect"><i class="material-icons">people</i> Load Filtered Employees</a>
            </div>
        </div>
    </div>
<div class="table-responsive" style="width:100%;">
    <table id="datatables" class="table table-hover table-striped">
        <thead> 
            <tr>
                <th>Payroll Info</th>
                <th>PDS Info</th>      
                <th>Employee No.</th>
                <th>Scanning No.</th>
                <th>Full Name</th>
                <th>Position</th>
                <th>Service / Division / Unit</th>
                <th style="min-width: 50px;">Salary Grade</th>
                <?php
                if(isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] == 1){
                    echo '<th style="min-width: 110px;">Date of Last Service</th>';
                }else{
                    echo '<th style="min-width: 110px;">Date Hired</th>';
                }
                ?>
                <th>Employment Status</th>
                <th>Sex</th>
                <th>Mobile</th>
                <th>Email</th>
                <!-- <th>School</th> -->
                <th>Date Created</th>
                <th>Date Modified</th>                               
          </tr>
        </thead>
    </table>
</div>