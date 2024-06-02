<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Mobile Devices <small>Manage Mobile Devices</small>
                </h2>
                
            </div>
            <div class="body">
                <div style="width:100%;padding-bottom:20px;">
                    <?php //if(Helper::role(ModuleRels::ADD_DIVISION)): ?>
                        <a id="addMobileDevicesForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addMobileDevicesForm'; ?>">
                            <button type="button" class="btn btn-info btn-lg waves-effect">
                                <i class="material-icons">save</i>
                                <span> Add Record</span>
                            </button>
                        </a>
                    <?php //endif; ?>
                </div>
                <div id="table-holder">
                    <?php echo $table; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/fieldmanagement/mobiledevices.js"></script>