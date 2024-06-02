<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2 style="font-size: 2.2rem">
                    <i class="fa fa-id-card-o" aria-hidden="true"></i>Balance Posting
                    <small>Employee Balance Brought Posting</small>
                </h2>
                <ul class="header-dropdown m-r-0">
                    <li>
                        <?php if(Helper::role(ModuleRels::BALANCE_ADD_RECORDS)): ?>
                        <a id="addEmployeeBalanceForm" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addEmployeeBalanceForm' ?>">
                            <button type="button" class="btn btn-info btn-lg waves-effect waves-float">
                                <i class="material-icons">add</i> Add Balance
                            </button>
                        </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="row search_row">
                    <!-- <div class="col-md-3">
                        <label class="form-label">Location</label>
                        <div class="form-group">
                            <div class="form-line location_select">
                                <select class="location_id form-control" name="location_id" id="location_id" data-live-search="true">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Department</label>
                        <div class="form-group">
                            <div class="form-line division_select">
                                <select class="search_division form-control" name="search_division" id="search_division" data-live-search="true">
                                    <option value="" selected></option>
                                </select>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-md-6">
                        <label class="form-label">Leave Group <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <div class="form-line leave_grouping_select">
                                <select class="leave_grouping_id form-control " name="leave_grouping_id" id="leave_grouping_id" data-live-search="true" >
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <a id="searchEmployeeBalance" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>">
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/leavemanagement/balanceposting.js"></script>