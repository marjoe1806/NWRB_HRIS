<?php //var_dump($this->uri->segment(1));die(); ?>
<div class="table-responsive listTable" style="width:100%;">
	<table id="datatables_weekly" class="table table-hover table-striped">
		<thead>
			<tr>
				<th>Payroll Period</th>
				<th>Week No.</th>
				<th>Period From</th>
				<th>Period To</th>
				<th>Seq. No</th>
				<th>Pay Month</th>
				<th>Pay Year</th>
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
					<?php echo ($value->is_posted == "1")?'<label class="text-success">YES</label>':'<label class="text-danger">NO</label>'; ?>
				</td>
				<td>
					<?php echo $value->payroll_period; ?>
				</td>
				<td>
					<?php echo $value->start_date; ?>
				</td>
				<td>
					<?php echo $value->end_date; ?>
				</td>
				<td>
					<?php echo $value->period_id; ?>
				</td>
				<td>
					<?php echo $value->sequence_number; ?>
				</td>
				<td>
					<?php echo date("F", strtotime($value->payroll_period)); ?>
				</td>
				<td>
					<?php echo date("Y", strtotime($value->payroll_period)); ?>
				</td>
				<td>
					<?php echo ($value->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>'; ?>
				</td>
				<td>
					<a id="updateWeeklySalaryGradesForm" class="updateWeeklySalaryGradesForm btn btn-info btn-circle waves-effect waves-circle waves-float" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateWeeklySalaryGradesForm'; ?>"
					data-toggle="tooltip" data-placement="top" title="Update" data-id="<?php echo $value->id; ?>" <?php foreach ($value
					as $k=> $v) { echo ' data-'.$k.'="'.$v.'" '; } ?>" >
						<i class="material-icons">mode_edit</i>
					</a>
					<?php if($value->is_active == "1"){ ?>
					<?php //if(Helper::role(ModuleRels::DEACTIVATE_DOCUMENT_TYPE)): ?>
					<a class="deactivateWeeklySalaryGrades btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top"
					title="Deactivate" data-id="<?php echo $value->id; ?>" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateWeeklySalaryGrades'; ?>">
						<i class="material-icons">do_not_disturb</i>
					</a>
					<?php //endif; ?>
					<?php }else{ ?>
					<?php //if(Helper::role(ModuleRels::ACTIVATE_DOCUMENT_TYPE)): ?>
					<a class="activateWeeklySalaryGrades btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top"
					title="Activate" data-id="<?php echo $value->id; ?>" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateWeeklySalaryGrades'; ?>">
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
