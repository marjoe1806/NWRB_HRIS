<div class="row clearfix" id="flagCeremonySchedulesForm">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-blue">
				<h2>
					Flag Ceremony Schedules
					<small>Manage Flag Ceremony Schedules</small>
				</h2>
			</div>
			<div class="body">
			<div style="width:100%;padding-bottom:20px;">
					<!-- <a id="addFlagCeremonySchedulesForm" class="addFlagCeremonySchedulesForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/flagCeremonySchedulesForm'; ?>" data-toggle="tooltip" data-placement="top" title="Update" data-key="addFlagCeremonySchedules" data-year="<?php echo date('Y', strtotime(date('Y-m-d'))); ?>" data-month="<?php echo date('m', strtotime(date('Y-m-d'))); ?>" data-flagdateceremony = "<?php echo date('Y-m-d'); ?>">
						<button type="button" class="btn btn-info btn-lg waves-effect">
							<i class="material-icons">save</i>
							<span> Add Record</span>
						</button>
					</a> -->
								</div>
				<div id="table-holder">
					<?php echo $table; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/timekeeping/flagceremonyschedules.js"></script>