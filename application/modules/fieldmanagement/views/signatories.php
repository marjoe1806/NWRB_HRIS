<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Signatories <small>Manage Signatories</small>
                </h2>
                
            </div>
            <div class="body">
                <!-- <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#home_with_icon_title" data-toggle="tab" aria-expanded="true">
                            <i class="material-icons">folder_shared</i> Signatories
                        </a>
                    </li>
                    <li role="presentation" class="">
                        <a href="#profile_with_icon_title" data-toggle="tab" aria-expanded="false">
                            <i class="material-icons">person_pin</i> Head Signatories
                        </a>
                    </li>
                </ul> -->
                <!-- <div class="tab-content"> -->
                    <!-- <div role="tabpanel" class="tab-pane fade active in" id="home_with_icon_title"> -->
                        <div id="table-holder-signatories">
                            <div style="width:100%;padding-bottom:20px;">
                                <?php //if(Helper::role(ModuleRels::ADD_DIVISION)): ?>
                                    <a id="addSignatoriesForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addSignatoriesForm'; ?>">
                                        <button type="button" class="btn btn-info btn-lg waves-effect">
                                            <i class="material-icons">save</i>
                                            <span> Add Record</span>
                                        </button>
                                    </a>
                                <?php //endif; ?>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <h5 class="text-info">Status</h5>
                                        <select name="status" id="status" class="form-control">
                                            <option value="ALL">ALL</option>
                                            <option value="1">Active</option>
                                            <option value="0">In Active</option>
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
                    <!-- </div> -->
                    <!-- <div role="tabpanel" class="tab-pane fade" id="profile_with_icon_title">
                        <div id="table-holder-headsignatory">
                            <div style="width:100%;padding-bottom:20px;">
                                <?php //if(Helper::role(ModuleRels::ADD_DIVISION)): ?>
                                    <a id="addHeadSignatoriesForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addHeadSignatoriesForm'; ?>">
                                        <button type="button" class="btn btn-info btn-lg waves-effect">
                                            <i class="material-icons">save</i>
                                            <span> Add Record</span>
                                        </button>
                                    </a>
                                <?php //endif; ?>
                            </div>
                            <div id="table-holder-head">
                                <?php// echo $tablehead; ?>
                            </div>
                        </div>

                    </div> -->
                <!-- </div> -->

            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/fieldmanagement/signatories.js"></script>