<div class="row clearfix" id="birForm">
<input type="hidden" id="hide_emp_status" value="<?php echo isset($_GET['EmploymentStatus'])?$_GET['EmploymentStatus']:"Active"; ?>">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                 2316 Form <small>2316 Form Summary</small>
                </h2>
            </div>
            <div class="body">
                <div style="width:100%;padding-bottom:20px;" class="search_entry">
            
                    <div class="row">
                     
                        <div class="col-md-4">
                            <h5 class="text-info">Date Year <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line year_select">
                                    <select class="year_select form-control" name="year_select" id="year_select" data-live-search="true" required>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a id="viewBirFormSummary" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>">
                                <button type="button" class="btn btn-block btn-lg btn-info waves-effect">
                                <i class="material-icons">description</i> Employees Summary
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/payrollreports/birform.js"></script>