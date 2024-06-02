<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Leave Transaction/s <small>Manage Leave/s</small>
                </h2>
                
            </div>
            <div class="body">
                <div class="row">
                       <div class="col-md-3">
                            <h5 class="text-info">Type</h5>
                           <div class="form-group">
                               <div class="form-line">
                                   <select class="form-control leave_type" name="leave_type" id="leave_type" data-live-search="true" >
                                       <option value="">ALL</option>
                                       <option value="violence">Violence Against Women and Children Leave</option>
                                       <option value="vacation">Vacation Leave</option>
                                       <option value="terminal">Terminal Leave</option>
                                       <option value="study">Study Leave</option>
                                       <option value="special">Special Leave</option>
                                       <option value="solo">Solo Parent Leave</option>
                                       <option value="sick">Sick Leave</option>
                                       <option value="rehab">Rehabilitation Privilege</option>
                                       <option value="paternity">Paternity Leave</option>
                                       <option value="monetization">Monetization of Leave Credits</option>
                                       <option value="maternity">Maternity Leave</option>
                                       <option value="force">Forced Leave</option>
                                       <option value="offset">Calamity Leave</option>
                                       <option value="benefits">Special Leave Benefits for Women</option>
                                       <!-- <option value="offset">Compensatory Time Off</option> -->
                                   </select>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-3">
                            <h5 class="text-info">Status</h5>
                           <div class="form-group">
                               <div class="form-line">
                                   <select class="status form-control " name="status" id="status" data-live-search="true" >
                                       <option value="">ALL</option>
                                       <option value="1">FOR CERTIFICATION</option>
                                       <option value="2">FOR RECOMMENDATION (Supervisor)</option>
                                       <option value="3">FOR RECOMMENDATION (Division Head)</option>
                                       <option value="4">FOR APPROVAL</option>
                                       <option value="5">APPROVED</option>
                                       <option value="6">DISAPPROVED</option>
                                       <!-- <option value="7">REJECTED</option> -->
                                   </select>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-3">
                            <input type="hidden" id="sess_division_id" value="<?php echo $_SESSION['emp_division_id']; ?>">
                            <input type="hidden" id="sess_employee_id"  value="<?php echo $_SESSION['employee_id']; ?>">
                            <input type="hidden" id="sess_position" value="<?php echo $_SESSION['position_id']; ?>">
                            <input type="hidden" id="sess_salary" value="<?php echo number_format($_SESSION['salary'],2); ?>">
                            <a id="btnsearch" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>" data-toggle="tooltip" data-placement="top" title="Search">
                                <button type="button" class="btn btn-primary btn-circle-lg waves-effect waves-circle waves-float">
                                    <i class="material-icons">search</i>
                                </button>
                            </a>
                       </div>
                        <div class="col-md-3 text-right">
                                <!-- <a style="text-decoration:none;" 
                                data-toggle="tooltip" data-placement="top" title="Export to CSV"
                                href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/exportEmployeesCSVFile'; ?>"> -->
                                    <button type="button" id="btnXls" class="btn btn-success btn-circle-lg waves-effect waves-circle waves-float"  data-toggle="tooltip" data-placement="top" title="Export xls">
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
<?php echo $form; ?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/leavemanagement/pendingleave.js"></script>
 <script type="text/javascript" src="<?php echo base_url();?>assets/local/module/js/worksheet/leaverequestworksheet.js"></script>