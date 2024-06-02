<style>
    .info-box .content .text{
        font-size: 15px !important;
    }
</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="content" style="padding-left:20px;padding-right:20px;">
                <div class="row">  
                    <div class="col-md-4"> 
                        <h3 style="color:#31708f"><b>DASHBOARD</b></h3>
                    </div>
                    <?php if(Helper::role(ModuleRels::PAYROLL_DASHBOARD)): ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>As of date</label>
                            <div class="form-line">
                                <input type="text" class="form-control datepicker" name="asofdate" id="asofdate" readonly value="<?php echo date("Y-m-d"); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Payroll Period</label>
                            <div class="form-line payroll_period_select">
                                <select class="payroll_period_id form-control" name="payroll_period_id" id="payroll_period_id" data-live-search="true">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <?php //if(Helper::role(ModuleRels::HRIS_DASHBOARD)): ?>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="https://telcomlivecontent.com/NWRBLMS/my/" target="_blank" class="info-box bg-blue hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">assignment</i>
                    </div>
                    <div class="content">
                        <div class="text">LEARNING MANAGEMENT SYSTEM</div>
                    </div>
                </a>
            </div>
            <?php //endif; ?>
            
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" id="downloadMobileApp">
                <div class="info-box bg-deep-purple hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">cloud_download</i>
                    </div>
                    <div class="content">
                        <div class="text">DOWNLOAD MOBILE APPLICATION</div>
                    </div>
                </div>
            </div>
            <?php if(Helper::role(ModuleRels::HR_DASHBOARD)): ?>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div id="yr_service" class="info-box bg-deep-orange hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">date_range</i>
                    </div>
                    <div class="content">
                        <div class="text">SUMMARY LENGTH OF SERVICE</div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <!-- <div class="row">
            <?php if(Helper::role(ModuleRels::HRIS_DASHBOARD)): ?>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="<?php echo base_url(); ?>leavemanagement/CTORequest/" target="_blank" class="info-box bg-cyan hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">book</i>
                        </div>
                        <div class="content">
                            <div class="text">COMPENSATORY TIME OFF REQUEST</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="<?php echo base_url(); ?>leavemanagement/LeaveRequest/" target="_blank" class="info-box bg-orange hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">book</i>
                        </div>
                        <div class="content">
                            <div class="text">LEAVE REQUEST</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="<?php echo base_url(); ?>timekeeping/OfficialBusinessRequest/" target="_blank" class="info-box bg-deep-orange hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">book</i>
                        </div>
                        <div class="content">
                            <div class="text">PERSONAL LOCATOR SLIP REQUEST</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="<?php echo base_url(); ?>travelOrder/TravelOrderRequest/" target="_blank" class="info-box bg-red hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">book</i>
                        </div>
                        <div class="content">
                            <div class="text">TRAVEL ORDER REQUEST</div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        </div> -->
        <div class="row">
            <?php if(Helper::role(ModuleRels::LEAVE_CTO_APPROVER)): ?>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="<?php echo base_url(); ?>leavemanagement/CTOApproval/" target="_blank" class="info-box hover-zoom-effect">
                    <!-- <div class="info-box hover-zoom-effect"> -->
                        <div class="icon bg-cyan">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text"><b>FOR APPROVAL</b> <small>CTO Request</small></div>
                            <div class="total_cto number" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                        </div>
                    <!-- </div> -->
                    </a>
                </div>
            <?php endif; ?>
            <?php if(Helper::role(ModuleRels::LEAVE_TRANSACTIONS_SUB_MENU)): ?>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="<?php echo base_url(); ?>leavemanagement/PendingLeave/" target="_blank" class="info-box hover-zoom-effect">
                        <div class="icon bg-cyan">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text"><b>FOR APPROVAL</b> <small>Leave Request</small></div>
                            <div class="total_leave number" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
            <?php if(Helper::role(ModuleRels::OB_VIEW_ALL_TRANSACTIONS)): ?>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="<?php echo base_url(); ?>timekeeping/LocatorSlips/" target="_blank" class="info-box hover-zoom-effect">
                        <div class="icon bg-cyan">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text"><b>FOR APPROVAL</b> <small>Locator Slips</small></div>
                            <div class="total_locator number" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
            <?php if(Helper::role(ModuleRels::TRAVEL_ORDER_APPROVER_SUB_MENU)): ?>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="<?php echo base_url(); ?>travelOrder/TravelOrderApproval/" target="_blank" class="info-box hover-zoom-effect">
                    <div class="icon bg-cyan">
                        <i class="material-icons">playlist_add_check</i>
                    </div>
                    <div class="content">
                        <div class="text"><b>FOR APPROVAL</b> <small>Travel Order</small></div>
                        <div class="total_to number" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                    </div>
                </a>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if(Helper::role(ModuleRels::HR_DASHBOARD)): ?>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" id="viewCTO">
                <!-- <a href="<?php echo base_url(); ?>leavemanagement/CTOApproval/" target="_blank" class="info-box hover-zoom-effect"> -->
                <div class="info-box hover-zoom-effect">
                    <div class="icon bg-orange">
                        <i class="material-icons">compare_arrows</i>
                    </div>
                    <div class="content">
                        <div class="text"><b>PENDING</b> <small>CTO Request</small></div>
                        <div class="total_pending_cto number" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                    </div>
                </div>
                <!-- </a> -->
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" id="viewLeave">
                <!-- <a href="<?php echo base_url(); ?>leavemanagement/PendingLeave/" target="_blank" class="info-box hover-zoom-effect"> -->
                <div class="info-box hover-zoom-effect">
                    <div class="icon bg-orange">
                        <i class="material-icons">compare_arrows</i>
                    </div>
                    <div class="content">
                        <div class="text"><b>PENDING</b> <small>Leave Request</small></div>
                        <div class="total_pending_leave number" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                    </div>
                </div>
                <!-- </a> -->
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" id="viewOB">
                <!-- <a href="<?php echo base_url(); ?>timekeeping/LocatorSlips/" target="_blank" class="info-box hover-zoom-effect"> -->
                <div class="info-box hover-zoom-effect">
                    <div class="icon bg-orange">
                        <i class="material-icons">compare_arrows</i>
                    </div>
                    <div class="content">
                        <div class="text"><b>PENDING</b> <small>Locator Slips</small></div>
                        <div class="total_pending_ob number" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                    </div>
                </div>
                <!-- </a> -->
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" id="viewTO">
                <!-- <a href="<?php echo base_url(); ?>travelOrder/TravelOrderApproval/" target="_blank" class="info-box hover-zoom-effect"> -->
                <div class="info-box hover-zoom-effect">    
                    <div class="icon bg-orange">
                        <i class="material-icons">compare_arrows</i>
                    </div>
                    <div class="content">
                        <div class="text"><b>PENDING</b> <small>Travel Order</small></div>
                        <div class="total_pending_to number" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                    </div>
                </div>
                <!-- </a> -->
            </div>
            
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" id="viewApprovedCTO">
                <!-- <a href="<?php echo base_url(); ?>leavemanagement/CTOApproval/" target="_blank" class="info-box hover-zoom-effect"> -->
                <div class="info-box hover-zoom-effect">
                    <div class="icon bg-green">
                        <i class="material-icons">done</i>
                    </div>
                    <div class="content">
                        <div class="text"><b>APPROVED</b> <small>CTO Request</small></div>
                        <div class="total_approved_cto number" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                    </div>
                </div>
                <!-- </a> -->
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" id="viewApprovedLeave">
                <!-- <a href="<?php echo base_url(); ?>leavemanagement/PendingLeave/" target="_blank" class="info-box hover-zoom-effect"> -->
                <div class="info-box hover-zoom-effect">
                    <div class="icon bg-green">
                        <i class="material-icons">done</i>
                    </div>
                    <div class="content">
                        <div class="text"><b>APPROVED</b> <small>Leave Request</small></div>
                        <div class="total_approved_leave number" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                    </div>
                </div>
                <!-- </a> -->
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" id="viewApprovedOB">
                <!-- <a href="<?php echo base_url(); ?>timekeeping/LocatorSlips/" target="_blank" class="info-box hover-zoom-effect"> -->
                <div class="info-box hover-zoom-effect">
                    <div class="icon bg-green">
                        <i class="material-icons">done</i>
                    </div>
                    <div class="content">
                        <div class="text"><b>APPROVED</b> <small>Locator Slips</small></div>
                        <div class="total_approved_ob number" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                    </div>
                </div>
                <!-- </a> -->
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" id="viewApprovedTO">
                <!-- <a href="<?php echo base_url(); ?>travelOrder/TravelOrderApproval/" target="_blank" class="info-box hover-zoom-effect"> -->
                <div class="info-box hover-zoom-effect">    
                    <div class="icon bg-green">
                        <i class="material-icons">done</i>
                    </div>
                    <div class="content">
                        <div class="text"><b>APPROVED</b> <small>Travel Order</small></div>
                        <div class="total_approved_to number" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                    </div>
                </div>
                <!-- </a> -->
            </div>
        </div>
        <?php endif; ?>
        <div class="row">
            <?php if(Helper::role(ModuleRels::PAYROLL_DASHBOARD)): ?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">accessibility</i>
                    </div>
                    <div class="content">
                        <div class="text">Total Employees</div>
                        <div class="total_employees number count-to" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-blue-grey hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">assessment</i>
                    </div>
                    <div class="content">
                        <div class="text">Total Computed Gross Pay</div>
                        <div class="number total_gross_pay"> 0.00</div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="row">
            <?php if(Helper::role(ModuleRels::PAYROLL_DASHBOARD)): ?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">accessibility</i>
                    </div>
                    <div class="content">
                        <div class="text">Total Permanents Employees</div>
                        <div class="total_permanents number count-to" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">assignment</i>
                    </div>
                    <div class="content">
                        <div class="text">Total Taxes Withheld</div>
                        <div class="number total_withholding_tax"> 0.00</div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="row">
            <?php if(Helper::role(ModuleRels::PAYROLL_DASHBOARD)): ?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">accessibility</i>
                    </div>
                    <div class="content">
                        <div class="text">Total Coterminous Employees</div>
                        <div class="total_coterminous number count-to" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-green hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">receipt</i>
                    </div>
                    <div class="content">
                        <div class="text">Total GSIS Contributions</div>
                        <div class="number total_gsis"> 0.00</div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="row">
            <?php if(Helper::role(ModuleRels::PAYROLL_DASHBOARD)): ?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">accessibility</i>
                    </div>
                    <div class="content">
                        <div class="text">Total Male Employees</div>
                        <div class="total_males number count-to" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-brown hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">healing</i>
                    </div>
                    <div class="content">
                        <div class="text">Total PHILHEALTH Contributions</div>
                        <div class="number total_philhealth"> 0.00</div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <?php if(Helper::role(ModuleRels::PAYROLL_DASHBOARD)): ?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">accessibility</i>
                    </div>
                    <div class="content">
                        <div class="text">Total Female Employees</div>
                        <div class="total_females number count-to" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-blue hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">home</i>
                    </div>
                    <div class="content">
                        <div class="text">Total PAG-IBIG Contributions</div>
                        <div class="number total_pagibig"> 0.00</div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
         <!-- <div class="row">
            <?php //if(Helper::role(ModuleRels::HR_DASHBOARD)): ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               <div class="thumbnail">
                  <div id="table-holder" class="promotion_table" style="text-align: center;font-weight: bold">Years in Service</div>
                  <?php echo $tablepromotion; ?>
               </div>
            </div>
            <?php //endif; ?>
         </div> -->
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/home/dashboard.js"></script>