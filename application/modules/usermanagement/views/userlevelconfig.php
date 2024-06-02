<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    New User Account Level <small>User Level Configuration</small>
                </h2>
                
            </div>
            <div class="body">
                <?php echo $form; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card" id="userLevelTable">
            <div class="header bg-blue">
                <h2>
                    Manage User Account Levels <small>User Level Configuration</small>
                </h2>
                
            </div>
            <div class="body">
                <!-- <div style="width:100%;padding-bottom:20px;">
                    <a id="addUserConfigForm" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addUserConfigForm'; ?>">
                        <button type="button" class="btn bg-blue btn-lg waves-effect">
                            <i class="material-icons">save</i>
                            <span>Add User</span>
                        </button>
                    </a>
                </div> -->
                <?php echo $table; ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/usermanagement/userlevelconfig.js"></script>