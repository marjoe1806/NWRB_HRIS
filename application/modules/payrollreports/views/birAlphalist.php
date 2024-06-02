<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                 BIR alphalist <small>BIR alphalist Summary</small>
                </h2>
            </div>
            <div class="body">
			<a id="addBIRform" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addBIRform'; ?>">
			    <button type="button" class="btn btn-info btn-lg waves-effect" >
                    <i class="material-icons">save</i> Create</button>
					</a>
                <div style="width:100%;padding-bottom:20px;" class="search_entry">
					
                    <div class="row"> 
                        <div class="col-md-4">
                            <h5 class="text-info">Year <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line year_select">
                                    <select class="year_select form-control" name="year_select" id="year_select" data-live-search="true" required>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-4">
                            <a id="viewBirAlphalist" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewBirAlphalist'; ?>">
                                <button type="button" class="btn btn-info btn-lg waves-effect" >
                                    <i class="material-icons">attach_file</i> Excel</button>
                            </a>
                            <a id="viewBirAlphalistdat" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewBirAlphalistdat'; ?>">
                                <button type="button" class="btn btn-info btn-lg waves-effect" >
                                    <i class="material-icons">attach_file</i> DAT File</button>
                            </a>
                        </div>
                   
                        <!-- <div class="col-md-4">
                            <h5 class="text-info">Table Number<span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control" name="table_number" id="table_number" data-live-search="true" required>
                                        <option value=""></option>
                                        <option value="1">1st Table</option>
                                        <option value="2">2nd Table</option>
                                        <option value="3">3rd Table</option>
                                        <option value="4">4th Table</option>
										<option value="5">5th Table</option>
										<option value="6">6th Table</option>
										<option value="7">7th Table</option>
                                    </select>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a id="viewPayrollRegisterSummary" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>">
                                <button type="button" class="btn btn-block btn-lg btn-info waves-effect">
                                <i class="material-icons">description</i> BIR Alphalist Summary
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/payrollreports/biralphalist.js"></script>