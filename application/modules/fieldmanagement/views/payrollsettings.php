<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Payroll Settings <small>Manage Payroll Settings</small>
                </h2>
                
            </div>
            <div class="body">
                
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#PayrollSetup" data-toggle="tab" aria-expanded="true">
                            <i class="material-icons">credit_card</i> Payroll Setup
                        </a>
                    </li>
                    <li role="presentation" class="">
                        <a href="#DeductionOptions" data-toggle="tab" aria-expanded="false">
                            <i class="material-icons">remove_circle_outline</i> Deduction Options
                        </a>
                    </li>
                    
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="PayrollSetup">
                        <div style="width:100%;padding-bottom:20px;">
                            <?php //if(Helper::role(ModuleRels::ADD_DIVISION)): ?>
                                <!-- <a id="addPayrollSetupForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addPayrollSetupForm'; ?>">
                                    <button type="button" class="btn btn-info btn-lg waves-effect">
                                        <i class="material-icons">save</i>
                                        <span> Add Record</span>
                                    </button>
                                </a> -->
                            <?php //endif; ?>
                        </div>
                        <div id="table-holder-payrollsetup">
                            <?php echo $form_payroll_setup; ?>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="DeductionOptions">
                        <div style="width:100%;padding-bottom:20px;">
                            <?php //if(Helper::role(ModuleRels::ADD_DIVISION)): ?>
                                <a id="addPayrollSettingsDeductionOptionsForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addPayrollSettingsDeductionOptionsForm'; ?>">
                                    <button type="button" class="btn btn-info btn-lg waves-effect">
                                        <i class="material-icons">save</i>
                                        <span> Add Record</span>
                                    </button>
                                </a>
                            <?php //endif; ?>
                        </div>
                        <div id="table-holder-deductionoptions">
                            <?php echo $table_deduction_options; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/fieldmanagement/payrollsettings.js"></script>