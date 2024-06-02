<div class="row clearfix" id="userLevelForm">
    <div class="col-md-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Mobile Timekeeping App User <small>Manage Mobile Users</small>
                </h2>
                
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-md-6">
                        <?php //if(Helper::role(ModuleRels::EMPLOYEE_ADD_RECORDS)):  ?>
                            <a id="addMobileUserConfigForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addMobileTimekeepingAppUserForm'; ?>">
                                <button type="button" class="btn btn-info btn-lg waves-effect">
                                    <i class="material-icons">save</i>
                                    <span> Add Record</span>
                                </button>
                            </a>
                        <?php //endif; ?>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/usermanagement/mobiletimekeepingappuser.js"></script>