<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Web User Configuration <small>Manage Web Users</small>
                </h2>
                
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-md-3">
                        <a id="addUserConfigForm" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addUserConfigForm'; ?>">
                            <button type="button" class="btn bg-blue btn-md waves-effect">
                                <i class="material-icons">create</i>
                                <span>Create New User Account</span>
                            </button>
                        </a>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-3">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <select id="user-status" class="form-control" name="Status">
                                    <option value="ACTIVE" selected>ACTIVE</option>
                                    <option value="INACTIVE" >INACTIVE</option>
                                </select>
                                <label class="form-label"></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-4">
                        <form id="search-specific" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'; ?>" method="POST">
                            <div class="col-md-8 col-sm-8 col-xs-8 col-lg-8">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id = "specific" class="form-control" name="specific" placeholder="Search">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4 col-lg-4">
                                <div class="form-group">   
                                    <button id = "specificbtn" type="submit" class="btn bg-indigo btn-circle waves-effect waves-circle waves-float">
                                            <i class="material-icons">search</i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                <?php echo $table; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/usermanagement/userconfig.js"></script>