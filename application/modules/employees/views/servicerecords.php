<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                Service Records <small></small>
                </h2>
                
            </div>
            <div class="body">
                <div style="width:100%;" class="search_entry">                   
                    <div class="row">
                        <div class="col-md-3">
                            <h5 class="text-info">Service / Division / Unit <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line division_select">
                                    <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>                       
                        <div class="col-md-3">
                            <h5 class="text-info">Employment Status <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line employment_status">
                                    <select class="employment_status form-control" name="employment_status" id="employment_status" >
                                    <option value="ALL">ALL</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Transferred">Transferred</option>
                                    <option value="Retired">Retired</option>
                                    <option value="Resigned">Resigned</option>
                                    <option value="Dropped">Dropped</option>
                                    <option value="Terminated">Terminated</option>
                                    <option value="Dismissed">Dismissed</option>
                                    <option value="AWOL">AWOL</option>
                                    <option value="End of Contract">End of Contract</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <a id="computePayroll" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>" data-toggle="tooltip" data-placement="top" title="Search">
                                <button type="button" class="btn btn-primary btn-circle-lg waves-effect waves-circle waves-float">
                                    <i class="material-icons">search</i>
                                </button>
                            </a>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/employees/servicerecords.js"></script>

