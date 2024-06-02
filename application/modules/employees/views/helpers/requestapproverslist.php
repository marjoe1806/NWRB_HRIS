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
                    <div class="form-line position_select">
                        <select class="position_id form-control" name="position_id" id="position_id" data-live-search="true">
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
            <tr  >
                <th style="min-width: 70px;">Action</th>      
                <th style="min-width: 80px;">Employee No.</th>
                <th style="min-width: 80px;">Scanning No.</th>
                <th style="min-width: 190px;">Full Name</th>
                <th style="min-width: 210px;">Position</th>
                <th style="min-width: 220px;">Service / Division / Unit</th>
                <th style="min-width: 50px;">Salary Grade</th>
                <?php
                if(isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] == 1){
                    echo '<th style="min-width: 110px;">Date of Last Service</th>';
                }else{
                    echo '<th style="min-width: 110px;">Date of Assumption</th>';
                }
                ?>
                <th style="min-width: 90px;">Employment Status</th>
                <th style="min-width: 50px;">Sex</th>
          
                <th style="min-width: 110px;">Date Created</th>
                <th style="min-width: 110px;">Date Modified</th>                               
          </tr>
        </thead>
    </table>
</div>