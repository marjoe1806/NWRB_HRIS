<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Applicant Report <small>Applicant Report Summary </small>
                </h2>
            </div>
            <div class="body">
                <div style="width:100%;padding-bottom:20px;" class="search_entry">
                    <div class="row">
                        <div class="col-md-4">
                            <h5 class="text-info">Status <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line application_status_select">
                                    <select class="application_status form-control" name="application_status" id="application_status" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
						<div class="col-md-4">
                            <h5 class="text-info">Position <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line vacancy_select">
                                    <select class="vacancy_id form-control" name="vacancy_id" id="vacancy_id" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <details class="col-md-12" id="table-column-toggle-checkbox">
                            <summary class="ml-auto mr-auto">Select which data to show</summary>
                        </details>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a id="viewApplicantReportsSummary" href="'+commons.baseurl+'recruitment/ApplicantReports/viewApplicantReportsSummary">
                                <button type="button" class="btn btn-block btn-lg btn-info waves-effect">
                                <i class="material-icons">description</i> Applicant Report Summary
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
				<?php echo $table; ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/recruitment/applicantreports.js"></script>
