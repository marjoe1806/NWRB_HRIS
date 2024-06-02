<?php
	// print_r($holidays);
	$months = ["01","02","03","04","05","06","07","08","09","10","11","12"];
	$months_name = ["JANUARY","FEBRUARY","MARCH","APRIL","MAY","JUNE","JULY","AUGUST","SEPTEMBER","OCTOBER","NOVEMBER","DECEMBER"];

	$firstmonday = date("Y-m-d", strtotime("first monday ".date("Y-m")));
	// var_dump($list->Data->details[0]->month);
?>

<div class="table-responsive">
	<table id="datatables" class="table table-hover table-striped table-responsive" style="width:100%;">
		<thead>
			<tr>
				<th>Month</th>
				<th>Date</th>
				<th>Day</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			
		<?php 
		foreach ($list->Data->details as $key => $value) {
			$flagdateceremony = $value->flagdateceremony;
		?>
			<tr>
				<td><?php echo date("F", strtotime($value->flagdateceremony)); ?></td>
				<td><?php echo $value->flagdateceremony; ?></td>
				<td><?php echo date("l", strtotime($value->flagdateceremony)); ?></td>
				<td>
					<a id="updateFlagCeremonySchedulesForm" class="updateFlagCeremonySchedulesForm btn btn-info btn-circle waves-effect waves-circle waves-float" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/flagCeremonySchedulesForm'; ?>" data-toggle="tooltip" data-placement="top" title="Update" data-key="updateFlagCeremonySchedules" data-year="<?php echo date('Y', strtotime($flagdateceremony)); ?>" data-month="<?php echo date('m', strtotime($flagdateceremony)); ?>" data-flagdateceremony = "<?php echo $flagdateceremony; ?>">
						<i class="material-icons">mode_edit</i>
					</a>
				</td>
			</tr>
		<?php } ?>
			
		</tbody>
	</table>
</div>



