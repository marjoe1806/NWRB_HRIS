<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Competency Test <small>Manage Competency Test</small>
                </h2>
                
            </div>
            <div class="body">
                <div style="width:100%;padding-bottom:20px;">
                    <?php //if(Helper::role(ModuleRels::ADD_DIVISION)): ?>
                        <!-- <a id="addCompetencyTestForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addCompetencyTestForm'; ?>">
                            <button type="button" class="btn btn-info btn-lg waves-effect">
                                <i class="material-icons">save</i>
                                <span> Add Record</span>
                            </button>
                        </a> -->
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
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/competencymanagement/competencytest.js"></script>