<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Contributions <small>Manage Contributions</small>
                </h2>
                
            </div>
            <div class="body">
                
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#GSIS" data-toggle="tab" aria-expanded="true">
                            <i class="material-icons">card_membership</i> GSIS
                        </a>
                    </li>
                    <li role="presentation" class="">
                        <a href="#PhilHealth" data-toggle="tab" aria-expanded="false">
                            <i class="material-icons">healing</i> PhilHealth
                        </a>
                    </li>
                    
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="GSIS">
                        <div style="width:100%;padding-bottom:20px;">
                            <?php //if(Helper::role(ModuleRels::ADD_DIVISION)): ?>
                                <a id="addContributionsGSISForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addContributionsGSISForm'; ?>">
                                    <button type="button" class="btn btn-info btn-lg waves-effect">
                                        <i class="material-icons">save</i>
                                        <span> Add Record</span>
                                    </button>
                                </a>
                            <?php //endif; ?>
                        </div>
                        <div id="table-holder-gsis">
                            <?php echo $table_gsis; ?>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="PhilHealth">
                        <div style="width:100%;padding-bottom:20px;">
                            <?php //if(Helper::role(ModuleRels::ADD_DIVISION)): ?>
                                <a id="addContributionsPhilHealthForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addContributionsPhilHealthForm'; ?>">
                                    <button type="button" class="btn btn-info btn-lg waves-effect">
                                        <i class="material-icons">save</i>
                                        <span> Add Record</span>
                                    </button>
                                </a>
                            <?php //endif; ?>
                        </div>
                        <div id="table-holder-philhealth">
                            <?php echo $table_philhealth; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/fieldmanagement/contributions.js"></script>