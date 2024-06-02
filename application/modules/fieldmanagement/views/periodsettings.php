<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                Payroll Period Settings <small>Manage Payroll Period Settings</small>
                </h2>
            </div>
            <div class="body">
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade active in" id="home_with_icon_title">
                        <div style="width:100%;padding-bottom:20px;">
                                <a id="addPeriodSettingsForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addPeriodSettingsForm'; ?>">
                                    <button type="button" class="btn btn-info btn-lg waves-effect">
                                        <i class="material-icons">save</i>
                                        <span> Add Record</span>
                                    </button>
                                </a>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <h5 class="text-info">Month</h5>
                                    <select name="month" id="month" class="form-control">
                                        <option value="ALL">ALL</option>
                                        <option value="1" <?php echo (int)date("m") == 1 ? "selected" : ""; ?>>January</option>
                                        <option value="2" <?php echo (int)date("m") == 2 ? "selected" : ""; ?>>February</option>
                                        <option value="3" <?php echo (int)date("m") == 3 ? "selected" : ""; ?>>March</option>
                                        <option value="4" <?php echo (int)date("m") == 4 ? "selected" : ""; ?>>April</option>
                                        <option value="5" <?php echo (int)date("m") == 5 ? "selected" : ""; ?>>May</option>
                                        <option value="6" <?php echo (int)date("m") == 6 ? "selected" : ""; ?>>June</option>
                                        <option value="7" <?php echo (int)date("m") == 7 ? "selected" : ""; ?>>July</option>
                                        <option value="8" <?php echo (int)date("m") == 8 ? "selected" : ""; ?>>August</option>
                                        <option value="9" <?php echo (int)date("m") == 9 ? "selected" : ""; ?>>September</option>
                                        <option value="10" <?php echo (int)date("m") == 10 ? "selected" : ""; ?>>October</option>
                                        <option value="11" <?php echo (int)date("m") == 11 ? "selected" : ""; ?>>November</option>
                                        <option value="12" <?php echo (int)date("m") == 12 ? "selected" : ""; ?>>December</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <h5 class="text-info">Status</h5>
                                    <select name="status" id="status" class="form-control">
                                        <option value="ALL">ALL</option>
                                        <option selected value="1">Active</option>
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
                        <div id="table-holder-monthly">
                            <?php echo $table_monthly; ?>
                        </div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="profile_with_icon_title">
                        <div style="width:100%;padding-bottom:20px;">
                                <a id="addPeriodSettingsForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addPeriodSettingsForm'; ?>">
                                    <button type="button" class="btn btn-info btn-lg waves-effect">
                                        <i class="material-icons">save</i>
                                        <span> Add Record</span>
                                    </button>
                                </a>
                        </div>
                        <div id="table-holder-semimonthly">
                            <?php //echo $table_semimonthly; ?>
                        </div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/fieldmanagement/periodsettings.js"></script>