<div class="row clearfix" id="userLevelForm">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-blue">
				<h2>
					Printed DTR Report
					<small>Printed DTR Summary</small>
				</h2>
			</div>
			<div class="body">
				<div style="width:100%; padding-bottom: 20px" class="search_entry">
					<div class="row">
						<div class="col-md-6">
							<h5 class="text-info">Location</h5>
							<div class="form-group">
								<div class="form-line location_select">
									<select class="location_id form-control" name="location_id" id="location_id" data-live-search="true">
										<option value="" selected></option>
									</select>
								</div>
							</div>
						</div>
						<center><h5 class="text-info">Date Range</h5></center>
						<div class="col-md-3">
							<div class="input-group">
								<span class="input-group-addon">
									From
								</span>
								<div class="form-line">
									<input type="text" id="date_from" name="date_from" class="date_from	 form-control">
								</div>
							</div>
						</div>
						<div class="col-md-3">

							<div class="input-group">
								<span class="input-group-addon">
									To
								</span>
								<div class="form-line">
									<input type="text" id="date_to" name="date_to" class="date_to form-control">
									
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<a id="summarizeAllEmployeeDailyTimeRecord" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/summarizeAllEmployeeDailyTimeRecord' ?>">
								<button type="button" class="btn btn-info btn-block btn-lg waves-effect waves-float">
									<i class="material-icons">search</i>
									<span>Load Employees</span>
								</button>
							</a>
							<div id="print-all-preloader" style="display: none">
								<div class="text-center" style="margin-top: 20px">
									<div class="preloader">
										<div class="spinner-layer pl-blue">
											<div class="circle-clipper left">
												<div class="circle"></div>
											</div>
											<div class="circle-clipper right">
												<div class="circle"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
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

<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/payrollreports/printeddtr.js"></script>
