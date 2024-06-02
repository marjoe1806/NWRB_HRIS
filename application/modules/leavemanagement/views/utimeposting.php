<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2 style="font-size: 2.2rem">
                    <i class="fa fa-id-card-o" aria-hidden="true"></i>Under Time Posting
                    <small>Absences, Undertimes & Lates Posting</small>
                </h2>
                <ul class="header-dropdown m-r-0">
                    <li>
                        <?php if(Helper::role(ModuleRels::UTIME_ADD_RECORDS)): ?>
                        <a id="addEmployeeUTimeForm" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addEmployeeUTimeForm' ?>">
                            <button type="button" class="btn btn-info btn-lg waves-effect waves-float">
                                <i class="material-icons">add</i> Add Undertime
                            </button>
                        </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="row search_row">
                    <!-- <div class="col-md-2">
                        <label class="form-label">Location</label>
                        <div class="form-group">
                            <div class="form-line location_select">
                                <select class="location_id form-control" name="location_id" id="location_id" data-live-search="true">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Department</label>
                        <div class="form-group">
                            <div class="form-line division_select">
                                <select class="division_id form-control" name="division_id" id="division_id" data-live-search="true">
                                    <option value="" selected></option>
                                </select>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-md-4">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <div class="form-line division_select">
                                <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Employee</label>
                        <div class="form-group">
                            <div class="form-line employee_select">
                                <select class="search_employee form-control" name="search_employee" id="search_employee" data-live-search="true">
                                    <option value="" selected></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Year</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select class="search_year form-control" name="search_year" id="search_year" data-live-search="true">
                                    <?php 
                                    $years = array_combine(range(date("Y"), 1910), range(date("Y"), 1910));
                                    foreach ($years as $k => $v) {
                                        echo '<option value="'.$k.'">'.$v.'</option>';
                                    } 
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <a id="searchEmployeeUTime" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>">
                            <button type="button" class="btn btn-info btn-circle-lg waves-effect waves-circle waves-float" data-toggle="modal" data-target="#printOptionsModal">
                                <i class="material-icons">search</i>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/leavemanagement/utimeposting.js"></script>