<div id="table-holder-weekly">
	<div style="width:100%;padding-bottom:20px;">
		<a id="addWeeklyPeriodSettingsForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addWeeklyPeriodSettingsForm'; ?>"
		data-id="<?php echo isset($_POST['id'])? $_POST['id'] :''; ?>">
			<button type="button" class="btn btn-info btn-lg waves-effect">
				<i class="material-icons">save</i>
				<span> Add Record</span>
			</button>
		</a>
	</div>
	<div class="table-responsive listTable" style="width:100%;">
		<table id="datatables_weekly" class="table table-hover table-striped">
			<thead>
				<tr>
					<th>Day</th>
					<th>Time In</th>
					<th>Break Out</th>
					<th>Break In</th>
					<th>Time Out</th>
					<th>Working Hours</th>
					<th>Restday Flag</th>
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
						<?php echo $value->week_number; ?>
					</td>
					<td>
						<?php echo $value->start_date; ?>
					</td>
					<td>
						<?php echo $value->end_date; ?>
					</td>
					<td>
						<?php echo date("F", strtotime($value->start_date)); ?>
					</td>
					<td>
						<?php echo date("Y", strtotime($value->start_date)); ?>
					</td>
					<td>
						<?php echo $value->is_restday; ?>
					</td>
					<td>
						<?php echo ($value->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>'; ?>
					</td>
					<td>
						<a id="updateWeeklyPeriodSettingsForm" class="updateWeeklyPeriodSettingsForm btn btn-info btn-circle waves-effect waves-circle waves-float"
						href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateWeeklyPeriodSettingsForm'; ?>"
						data-toggle="tooltip" data-placement="top" title="Update" data-id="<?php echo $value->id; ?>" <?php foreach ($value
						as $k=> $v) { echo ' data-'.$k.'="'.$v.'" '; } ?>" >
							<i class="material-icons">mode_edit</i>
						</a>
						<?php if($value->is_active == "1"){ ?>
						<?php //if(Helper::role(ModuleRels::DEACTIVATE_DOCUMENT_TYPE)): ?>
						<a class="deactivateWeeklyPeriodSettings btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip"
						data-placement="top" title="Deactivate" data-id="<?php echo $value->id; ?>" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateWeeklyPeriodSettings'; ?>">
							<i class="material-icons">do_not_disturb</i>
						</a>
						<?php //endif; ?>
						<?php }else{ ?>
						<?php //if(Helper::role(ModuleRels::ACTIVATE_DOCUMENT_TYPE)): ?>
						<a class="activateWeeklyPeriodSettings btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip"
						data-placement="top" title="Activate" data-id="<?php echo $value->id; ?>" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateWeeklyPeriodSettings'; ?>">
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
</div>