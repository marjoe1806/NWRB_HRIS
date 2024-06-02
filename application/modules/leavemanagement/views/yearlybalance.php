<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Yearly Balance <small>Yearly Balance Summary</small>
                </h2>
                
            </div>
            <div class="body">
                <div style="width:100%;padding-bottom:20px;" class="search_entry">
                   
                    <div class="row">
                        <!-- <div class="col-md-3">
                            <label class="form-label">Location </label>
                            <div class="form-group">
                                <div class="form-line location_select">
                                    <select class="location_id form-control " name="location_id" id="location_id" data-live-search="true" >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Department</label>
                            <div class="form-group">
                                <div class="form-line division_select">
                                    <select class="division_id form-control" name="division_id" id="division_id" data-live-search="true">
                                        <option value="" selected></option>
                                    </select>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-md-3">
                            <label class="form-label">Department <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <div class="form-line division_select">
                                    <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Month</label>
                            <div class="form-group">
                                <div class="form-line month_select">
                                    <select class="month_code form-control" name="month_code" id="month_code" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
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
                            <a id="computePayroll" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/balance' ?>" data-toggle="tooltip" data-placement="top" title="Search Leave Ledger">
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/leavemanagement/yearlybalance.js"></script>