<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Government Certificates
                </h2>
                
            </div>
            <div class="body">
                <div style="width:100%;padding-bottom:20px;" class="search_entry">
                    <div class="row clearfix">            
                        <div class="col-md-3">
                            <h5 class="text-info">Loan Category <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line loans_select">
                                    <select class="loans_id form-control required" name="loans_id" id="loans_id" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 sub_loan">
                            <h5 class="text-info">Loan Type <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line sub_loans_select">
                                    <select class="sub_loans_id form-control required" name="sub_loans_id" id="sub_loans_id" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h5 class="text-info">Pay Basis <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line pay_basis_select">
                                    <select class="pay_basis form-control payroll_filters" name="pay_basis" id="pay_basis" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- <div class="col-md-3">
                            <h5 class="text-info">Type of Certificate <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line certificate_type_select">
                                    <select class="certificate_type form-control" name="certificate_type" id="certificate_type" data-live-search="true">
                                        <option value=""></option>
                                        <option value="GSIS">GSIS</option>
                                        <option value="Pagibig">Pag-ibig</option>
                                        <option value="PhilHealth">PhilHealth</option>
                                    </select>
                                </div>
                            </div>
                        </div> -->
                        <!-- <div class="col-md-3">
                            <h5 class="text-info">Period <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line payroll_period_select">
                                    <select class="payroll_period_id form-control" name="payroll_period_id" id="payroll_period_id" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-md-4">
                            <h5 class="text-info">Service / Division / Unit</h5>
                            <div class="form-group">
                                <div class="form-line division_select">
                                    <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <a id="searchCertificate" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>" data-toggle="tooltip" data-placement="top" title="Search Certificate">
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/payrollreports/certificate.js"></script>