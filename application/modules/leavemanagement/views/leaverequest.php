<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Leave Requests <small>Manage Leave/s</small>
                </h2>
                
            </div>
            <div class="body">
                <div class="row">
                        <?php if(Helper::role(ModuleRels::LEAVE_ADD_RECORDS)):  ?>
                        <div class="col-md-3">
                            <a id="addLeaveRequestForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addLeaveRequestForm'; ?>">
                                <button type="button" class="btn btn-info btn-lg waves-effect">
                                    <i class="material-icons">add</i>
                                    <span> Leave Request</span>
                                </button>
                            </a>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-offset-6 col-md-3 text-right">
                                <!-- <a style="text-decoration:none;" 
                                data-toggle="tooltip" data-placement="top" title="Export to CSV"
                                href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/exportEmployeesCSVFile'; ?>"> -->
                                    <button type="button" id="btnXls" class="btn btn-success btn-circle-lg waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Export xls">
                                        <i class="material-icons">archive</i>
                                    </button>
                                <!-- </a> -->
                        </div>
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
                            <select name="status" id="status" class="form-control" data-live-search="true">
                                <option value="">ALL</option>
                                <option value="1">FOR CERTIFICATION</option>
                                <option value="2">FOR RECOMMENDATION (Supervisor)</option>
                                <option value="3">FOR RECOMMENDATION (Division Head)</option>
                                <option value="4">FOR APPROVAL</option>
                                <option value="5">APPROVED</option>
                                <option value="6">DISAPPROVED</option>
                                <!-- <option value="7">CANCELLED</option> -->
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








<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/leavemanagement/leaverequest.js"></script>
 <script type="text/javascript" src="<?php echo base_url();?>assets/local/module/js/worksheet/leaverequestworksheet.js"></script>

