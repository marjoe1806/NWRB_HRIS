<div class="row clearfix" id="dvfilter">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                Position List <small>Manage Positions List</small>
                </h2>
            </div>
            <div class="body">
                <div style="width:100%;" class="search_entry">
                    <div class="row">
                        <div class="col-md-3">
                            <h5 class="text-info">Select Filter <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line dropdown_select">
                                    <select class="filter1 form-control" name="filter1" id="filter1" data-live-search="true">
                                        <option value="all" selected>All</option>
                                        <option value="vacant">List of Vacant Positions</option>
                                        <option value="filled">List of Filled Positions</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 division_div">
                            <h5 class="text-info">Division <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line division_select">
                                    <select class="division_id form-control" name="division_id" id="division_id" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
							<br>
							<button type="button" class="search_btn btn btn-info btn-block btn-lg waves-effect waves-float">
								<span>Search</span>
							</button>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/recruitment/vacantpositions.js"></script>
