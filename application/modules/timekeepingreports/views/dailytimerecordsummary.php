<div class="row clearfix" id="userLevelForm">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-blue">
				<h2>
				Daily Time Record
					<small>Print Daily Time Record Summary</small>
				</h2>
			</div>
			<div class="body">
				<div style="width:100%; padding-bottom: 20px" class="search_entry">
					<div class="row">
						<div class="col-md-4">
							<h5 class="text-info">Service / Division / Unit</h5>
							<div class="form-group">
								<div class="form-line division_select">
									<select class="division_id form-control" name="division_id" id="division_id" data-live-search="true">
										<option value="" selected></option>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<h5 class="text-info">Pay Basis</h5>
							<div class="form-group">
								<div class="form-line pay_basis_select">
									<select class="pay_basis form-control" name="pay_basis" id="pay_basis" data-live-search="true">
										<option value="" selected></option>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<h5 class="text-info">Payroll Period Covered</h5>
							<div class="form-group">
								<div class="form-line payroll_period_select">
									<select class="payroll_period_id form-control" name="payroll_period_id" id="payroll_period_id"
									 data-live-search="true">
										<option value="" selected></option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
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
						<div class="col-md-3">
							<div class="input-group">
								<span class="input-group-addon">
									From
								</span>
								<div class="form-line">
									<input type="text" id="period_covered_from" name="period_covered_from" class="period_covered_from	 form-control"
									 readonly>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="input-group">
								<span class="input-group-addon">
									To
								</span>
								<div class="form-line">
									<input type="text" id="period_covered_to" name="period_covered_to" class="period_covered_to form-control"
									 readonly>
									<input type="hidden" value="" id="payroll_period_hidden">
			
			<?php //if(in_array(17002,$_SESSION["sessionModules"])){
				// echo '<input type="hidden" id="self" value="1" readonly>';
			// } ?>
								</div>
							</div>
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

<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/timekeepingreports/dailytimerecordsummary.js"></script>
