<div class="table-responsive listTable">
	<table id="datatables" class="table table-hover table-striped" style="width:100%;">
		<thead>
			<tr>
				<th>Action</th>
				<th>ID</th>
				<th>Shift Code</th>
				<th>Shift Description</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			<?php if(isset($list->Data->details) && sizeof($list->Data->details) > 0): foreach ($list->Data->details as $index => $value) { ?>
			<td>
				<?php if(Helper::role(ModuleRels::EMPLOYEE_SCHEDULES_VIEW_DETAILS)): ?>
				<a id="weeklyEmployeeSchedulesForm" class="weeklyEmployeeSchedulesForm btn btn-info btn-circle waves-effect waves-circle waves-float"
				href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/weeklyEmployeeSchedulesForm'; ?>" data-toggle="tooltip"
				data-placement="top" title="Daily Schedule" data-id="<?php echo $value->id; ?>" <?php foreach ($value as $k=> $v) { echo ' data-'.$k.'="'.$v.'" '; } ?>" >
					<i class="material-icons">format_list_numbered</i>
				</a>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::EMPLOYEE_SCHEDULES_UPDATE_DETAILS)): ?>
				<a id="updateEmployeeSchedulesForm" class="updateEmployeeSchedulesForm btn btn-info btn-circle waves-effect waves-circle waves-float"
				href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateEmployeeSchedulesForm'; ?>" data-toggle="tooltip"
				data-placement="top" title="Update" data-id="<?php echo $value->id; ?>" <?php foreach ($value as $k=> $v) { echo ' data-'.$k.'="'.$v.'" '; } ?>" >
					<i class="material-icons">mode_edit</i>
				</a>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::EMPLOYEE_SCHEDULES_ACTIVATION)): ?>
					<?php if($value->is_active == "1"){ ?>
					<a class="deactivateEmployeeSchedules btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip"
					data-placement="top" title="Deactivate" data-id="<?php echo $value->id; ?>" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateEmployeeSchedules'; ?>">
						<i class="material-icons">do_not_disturb</i>
					</a>
					<?php }else{ ?>
					<a class="activateEmployeeSchedules btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip"
					data-placement="top" title="Activate" data-id="<?php echo $value->id; ?>" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateEmployeeSchedules'; ?>">
						<i class="material-icons">done</i>
					</a>
					<?php } ?>
				<?php endif; ?>
			</td>
			<td>
				<?php echo $value->id; ?>
			</td>
			<td>
				<?php echo $value->shift_code; ?>
			</td>
			<td>
				<?php echo $value->description; ?>
			</td>
			<td>
				<?php echo ($value->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>'; ?>
			</td>
			</tr>
			<?php }
            endif; ?>
		</tbody>
	</table>
</div>