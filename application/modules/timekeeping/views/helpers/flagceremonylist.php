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
				<th>Actions</th>
				<th>Month</th>
				<th>Date</th>
				<th>Day</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			
		<?php for($i=0; $i<sizeof($months); $i++) { 
			$flagdateceremony = date("Y-m-d", strtotime("first monday ".date("Y-".$months[$i])));
			$flagdayceremony = date("l", strtotime(date($flagdateceremony)));
			$status = 1;

			if(isset($holidays)) {
				for($k=0; $k<sizeof($holidays); $k++) {
					if($holidays[$k]['date'] == $flagdateceremony) {
						$date = new DateTime($flagdateceremony);
						$date->modify('+1 day');
						$flagdateceremony = $date->format('Y-m-d');
						$flagdayceremony = date("l", strtotime(date($flagdateceremony)));
					}
				}
			}
			
			if(isset($list->Data)) {
				for($j=0; $j<sizeof($list->Data->details); $j++) {
					if($list->Data->details[$j]->month == $months[$i]) {
						$flagdateceremony = $list->Data->details[$j]->flagdateceremony;
						$flagdayceremony = date("l", strtotime(date($flagdateceremony)));
						$status = $list->Data->details[$j]->is_active;
					}
				}
			}
		?>
			<tr>
				<td>
					<a id="updateFlagCeremonySchedulesForm" class="updateFlagCeremonySchedulesForm btn btn-info btn-circle waves-effect waves-circle waves-float" 
					href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/flagCeremonySchedulesForm'; ?>" data-toggle="tooltip" data-placement="top" title="Update" data-key="updateFlagCeremonySchedules" data-year="<?php echo date('Y', strtotime($flagdateceremony)); ?>" data-month="<?php echo date('m', strtotime($flagdateceremony)); ?>" data-flagdateceremony = "<?php echo $flagdateceremony; ?>">
						<i class="material-icons">mode_edit</i>
					</a>
					<?php if($status == 1){ ?>
					<a id="deactivateFlagCeremonySchedules" class="deactivateFlagCeremonySchedules btn btn-danger btn-circle waves-effect waves-circle waves-float" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateFlagCeremonySchedules'; ?>" data-toggle="tooltip" data-placement="top" title="Update" data-key="deactivateFlagCeremonySchedules" data-year="<?php echo date('Y', strtotime($flagdateceremony)); ?>" data-month="<?php echo date('m', strtotime($flagdateceremony)); ?>" data-flagdateceremony = "<?php echo $flagdateceremony; ?>">
						<i class="material-icons">do_not_disturb</i>
					</a>
				<?php }else{ ?>
					<a id="activateFlagCeremonySchedules" class="activateFlagCeremonySchedules btn btn-success btn-circle waves-effect waves-circle waves-float" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateFlagCeremonySchedules'; ?>" data-toggle="tooltip" data-placement="top" title="Update" data-key="activateFlagCeremonySchedules" data-year="<?php echo date('Y', strtotime($flagdateceremony)); ?>" data-month="<?php echo date('m', strtotime($flagdateceremony)); ?>" data-flagdateceremony = "<?php echo $flagdateceremony; ?>">
						<i class="material-icons">done</i>
					</a>
				<?php } ?>
				</td>
				<td><?php echo $months_name[$i]; ?></td>
				<td><?php echo $flagdateceremony; ?></td>
				<td><?php echo $flagdayceremony; ?></td>
				<td><?php echo $status == 1 ? "ACTIVE" : "INACTIVE";  ?></td>
			</tr>
		<?php } ?>
			
		</tbody>
	</table>
</div>



