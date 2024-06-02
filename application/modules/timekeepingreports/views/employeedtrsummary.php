<div class="row clearfix" id="userLevelForm">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-blue">
				<h2>
				My Daily Time Record
				</h2>
			</div>
			<div class="body">
				<div style="width:100%; padding-bottom: 20px" class="search_entry">
					<div class="row">
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
						<div class="col-md-3">
							<h5 class="text-info">From</h5>
							<div class="form-group">
								<div class="form-line">
									<input type="text" id="period_covered_from" name="period_covered_from" class="period_covered_from	 form-control"
									 readonly>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<h5 class="text-info">To</h5>
							<div class="form-group">
								<div class="form-line">
									<input type="text" id="period_covered_to" name="period_covered_to" class="period_covered_to form-control"
									 readonly>
									<input type="hidden" value="" id="payroll_period_hidden">
									<input type="hidden" id="pay_basis" value="<?php echo $_SESSION['pay_basis']; ?>">
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<button type="button" id="viewDailyTimeRecordSummaryForm" class="btn btn-info btn-circle-lg waves-effect waves-float"> <i class="material-icons">search</i> </button>
						</div>
					</div>

				</div>


				<!-- <div id="table-holder">
					<?php //echo $table; ?>
				</div> -->



			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/timekeepingreports/employeedtrsummary.js"></script>
