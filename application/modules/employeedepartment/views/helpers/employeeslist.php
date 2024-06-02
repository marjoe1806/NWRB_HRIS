<?php //var_dump($this->uri->segment(1));die(); ?>
<input type="hidden" id="hide_emp_status" value="<?php echo isset($_GET['EmploymentStatus'])?$_GET['EmploymentStatus']:"Active"; ?>">
<div class="table-responsive listTable" style="width:100%;">
    <table id="datatables" class="table table-hover table-striped">
        <thead> 
            <tr  >
                <th>Employee No.</th>
                <th>Scanning No.</th>
                <th>Full Name</th>
                <th>Position</th>
                <th>Department</th>
                <th>Location</th>
                <th>Employment Status</th>
                <th>Date Created</th>
                <th>Action</th>                                        
          </tr>
        </thead>
    </table>
</div>