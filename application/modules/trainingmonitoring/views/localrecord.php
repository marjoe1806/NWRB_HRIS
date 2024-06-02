<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Local Records
                </h2>
                
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-md-6">
                        <?php //if(Helper::role(ModuleRels::ADD_DIVISION)): ?>
                            <a id="addLocalRecordForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addLocalRecordForm'; ?>">
                                <button type="button" class="btn btn-info btn-lg waves-effect">
                                    <i class="material-icons">save</i>
                                    <span> Add Local Record</span>
                                </button>
                            </a>
                        <?php //endif; ?>
                    </div>
                    <div class="col-md-6" style="text-align: right">
                        <label style="margin-right: 9px">Export:</label>
                        <button type="button" id="btnXls" class="btn btn-success btn-circle-lg waves-effect waves-circle waves-float pull-right" >
                        <i class="material-icons">archive</i>
                        </button>
                    </div>
                </div>
                <div id="table-holder">
                    <?php echo $table; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Employee Js -->
<script src="<?php echo base_url(); ?>assets/modules/js/employees/employeesmaster.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/trainingmonitoring/localrecord.js"></script>