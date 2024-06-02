<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                <?php echo (isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] != null)? "Former":"Active"; ?> Employees <small>Manage Employees</small>
                </h2>
                
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-md-6">
                        <?php if(Helper::role(ModuleRels::EMPLOYEE_ADD_RECORDS)):  ?>
                            <?php if(!(isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] != null)){ ?>
                            <a id="addEmployeesForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addEmployeesForm'; ?>">
                                <button type="button" class="btn btn-info btn-lg waves-effect">
                                    <i class="material-icons">save</i>
                                    <span> Add Record</span>
                                </button>
                            </a>
                            <?php } ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 text-right">
                            <!-- <a style="text-decoration:none;" 
                            data-toggle="tooltip" data-placement="top" title="Export to CSV"
                            href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/exportEmployeesCSVFile'; ?>"> -->
                                <button type="button" id="btnXls" class="btn btn-success btn-circle-lg waves-effect waves-circle waves-float" >
                                    <i class="material-icons">archive</i>
                                </button>
                            <!-- </a> -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="table-holder">
                            <?php echo $table; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/employees/requestapprovers.js"></script>