<div class="row clearfix" id="dvfilter">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                Trainings<small>Manage Trainings</small>
                </h2>
            </div>
            <div class="body">
                <div style="width:100%;padding-bottom:20px;">
					<a id="addManageTrainingsForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addManageTrainingsForm'; ?>">
						<button type="button" class="btn btn-info btn-lg waves-effect">
							<i class="material-icons">save</i>
							<span> Add Record</span>
						</button>
					</a>
				</div>
                <div style="width:100%;" class="search_entry">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">SELECT FILTER <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <div class="form-line dropdown_select">
                                    <select class="filter1 form-control" name="filter1" id="filter1" data-live-search="true">
                                        <option value="all" selected>All</option>
                                        <option value="title">TITLE</option>
                                        <option value="sponsor">SPONSOR</option>
                                        <option value="venue">VENUE</option>
                                        <option value="office_order">OFFICE ORDER</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 title_div filter2" style="display:none">
                            <label class="form-label">TITLE <span class="text-danger">*</label></span>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="title form-control" name="title" id="title">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 sponsor_div filter2" style="display:none">
                            <label class="form-label">SPONSOR <span class="text-danger">*</label></span>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="sponsor form-control" name="sponsor" id="sponsor">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 venue_div filter2" style="display:none">
                            <label class="form-label">VENUE <span class="text-danger">*</label></span>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="venue form-control" name="venue" id="venue">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 office_order_div filter2" style="display:none">
                            <label class="form-label">OFFICE ORDER <span class="text-danger">*</label></span>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="office_order form-control" name="office_order" id="office_order">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <!-- <a href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>" data-toggle="tooltip" data-placement="top" title="Search"> -->
                                <button type="button" class="search_btn btn btn-primary btn-circle-lg waves-effect waves-circle waves-float">
                                    <i class="material-icons">search</i>
                                </button>
                            <!-- </a> -->
                        </div>
                    </div>
                </div>
                <div class="table-holder">
                    <?php echo $table ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/trainings/managetrainings.js"></script>
