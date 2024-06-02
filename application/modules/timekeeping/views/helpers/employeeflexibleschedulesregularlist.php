<?php //var_dump($this->uri->segment(1));die(); ?>
<div class="table-responsive listTable">
	<table id="datatables_regular" class="table table-hover table-striped" style="width:100%;">
		<thead>
			<tr style="font-size: 12px">
				<th>Shift Code</th>
				<th>Day</th>
				<th>Start Time</th>
				<th>Break Time Start</th>
				<th>Break Time End</th>
				<th>End Time</th>
				<th>Working Hours</th>
				<th>Grace Period</th>
				<th>Status</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
            if(isset($list->Data->details) && sizeof($list->Data->details) > 0): 
                foreach ($list->Data->details as $index => $value) { ?>
			<tr>
				<td>
					<?php echo $value->shift_code; ?>
				</td>
				<td>
					<?php echo $value->description; ?>
				</td>
				<td>
					<?php echo date('g:i A', strtotime($value->start_time)); ?>
				</td>
				<td>
					<?php echo date('g:i A', strtotime($value->break_time_start)); ?>
				</td>
				<td>
					<?php echo date('g:i A', strtotime($value->break_time_end)); ?>
				</td>
				<td>
					<?php echo date('g:i A', strtotime($value->end_time)); ?>
				</td>
				<td>
					<?php echo $value->working_hours . ' Hours'; ?>
				</td>
				<td>
					<?php echo $value->grace_period . ' Minutes'; ?>
				</td>
				<td>
					<?php echo ($value->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>'; ?>
				</td>
				<td>
					<a id="updateEmployeeSchedulesForm" class="updateEmployeeSchedulesForm btn btn-info btn-circle waves-effect waves-circle waves-float" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateEmployeeSchedulesForm'; ?>"
					data-toggle="tooltip" data-placement="top" title="Update" data-id="<?php echo $value->id; ?>" <?php foreach ($value
					as $k=> $v) { echo ' data-'.$k.'="'.$v.'" '; } ?>" >
						<i class="material-icons">mode_edit</i>
					</a>
					<?php if($value->is_active == "1"){ ?>
					<?php //if(Helper::role(ModuleRels::DEACTIVATE_DOCUMENT_TYPE)): ?>
					<a class="deactivateEmployeeSchedules btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top"
					title="Deactivate" data-id="<?php echo $value->id; ?>" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateEmployeeSchedules'; ?>">
						<i class="material-icons">do_not_disturb</i>
					</a>
					<?php //endif; ?>
					<?php }else{ ?>
					<?php //if(Helper::role(ModuleRels::ACTIVATE_DOCUMENT_TYPE)): ?>
					<a class="activateEmployeeSchedules btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top"
					title="Activate" data-id="<?php echo $value->id; ?>" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateEmployeeSchedules'; ?>">
						<i class="material-icons">done</i>
					</a>
					<?php //endif; ?>
					<?php } ?>
				</td>
			</tr>
			<?php }
            endif; ?>
		</tbody>
	</table>
</div>
