<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Download Mobile Employees <small>Manage Employees</small>
                </h2>
                
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-info">Location <span class="text-danger">*</span></h5>
                        <div class="form-group">
                            <div class="form-line location_select">
                                <select class="location_id form-control " name="location_id" id="location_id" data-live-search="true" >
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <a id="downloadEmployeesMobile" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/downloadEmployeesMobile'; ?>">
                            <button type="button" class="btn btn-info btn-lg btn-block waves-effect">
                                <i class="material-icons">cloud_download</i>
                                <span> Download Mobile Employees</span>
                            </button>
                        </a>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/employees/employeemobile.js"></script>