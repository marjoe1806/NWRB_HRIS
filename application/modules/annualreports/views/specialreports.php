<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                Special Payroll Reports
                </h2>
                
            </div>
            <div class="body">
                <div style="width:100%;padding-bottom:20px;" class="search_entry">
                   
                    <div class="row">
                        <div class="col-md-3">
                            <h5 class="text-info">Service / Division / Unit</h5>
                            <div class="form-group">
                                <div class="form-line division_select">
                                    <select class="division_id form-control" name="division_id" id="division_id" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <h5 class="text-info">Type</h5>
                            <div class="form-group">
                                <div class="form-line type_select">
                                    <select class="type form-control " name="type" id="type" data-live-search="true">
                                        <option value="">Nothing selected</option>
                                        <option value="Overtime">Overtime</option>
                                        <option value="Bonus">Bonus</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2" id="pay_basis_select">
                            <h5 class="text-info">Pay Basis</h5>
                            <div class="form-group">
                                <div class="form-line pay_basis_select">
                                    <select class="pay_basis form-control" name="pay_basis" id="pay_basis" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3" style="display:none;" id="bonus">
                            <h5 class="text-info">Type of Bonus</h5>
                            <div class="form-group">
                                <div class="form-line bonuses_select">
                                    <select class="bonus_type form-control " name="bonus_type" id="bonus_type" data-live-search="true">
                                        <option value="">Loading...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2" id="month">
                            <h5 class="text-info">Month</h5>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="month_select form-control" name="month_select" id="month_select" data-live-search="true">
                                        <option value="">Nothing selected</option>
                                        <option value="01">January</option>
                                        <option value="02">February</option>
                                        <option value="03">March</option>
                                        <option value="04">April</option>
                                        <option value="05">May</option>
                                        <option value="06">June</option>
                                        <option value="07">July</option>
                                        <option value="08">August</option>
                                        <option value="09">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h5 class="text-info">Year</h5>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="year form-control" name="year" id="year" data-live-search="true">
                                        <?php 
                                        $years = array_combine(range(date("Y"), 2022), range(date("Y"), 2022));
                                        foreach ($years as $k => $v) {
                                            echo '<option value="'.$k.'">'.$v.'</option>';
                                        } 
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <label class="f0orm-label">Location <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <div class="form-line location_select">
                                    <select class="location_id form-control " name="location_id" id="location_id" data-live-search="true" >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="f0orm-label">Department <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <div class="form-line division_select">
                                    <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div> -->
                        <!-- <div class="col-md-3">
                            <a id="computePayroll" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>" data-toggle="tooltip" data-placement="top" title="Search Special Reportss">
                                <button type="button" class="btn btn-primary btn-circle-lg waves-effect waves-circle waves-float">
                                    <i class="material-icons">search</i>
                                </button>
                            </a>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a id="viewSpecialReportsSummary" href="'+commons.baseurl+'annualreports/SpecialReports/viewSpecialReportsSummary">
                                <button type="button" class="btn btn-block btn-lg btn-info waves-effect">
                                <i class="material-icons">description</i> Special Reports Summary
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- <div id="table-holder">
                    <?php echo $table; ?>
                </div> -->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/annualreports/specialreports.js"></script>