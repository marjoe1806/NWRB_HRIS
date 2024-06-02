<div id="table-holder-weekly">
	<form id="SalaryStepsForm" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/saveSalaryGradeSteps'; ?>">
		<div class="table-responsive listTable" style="width:100%;">
			<table id="datatables_weekly" class="table table-hover table-bordered">
				<thead>
					<tr class="text-primary">
						<th>Step 1</th>
						<th>Step 2</th>
						<th>Step 3</th>
						<th>Step 4</th>
					</tr>
				</thead>
				<tbody>
					<?php $steps = isset($list->Data->details) && sizeof($list->Data->details) > 0 ? $list->Data->details[0] : null; ?>
					<tr>
						<td>
							<input type="text" name="step_1" id="step_1" class="step_1 form-control" value="<?php echo isset($steps->step_1) ? $steps->step_1 : 0 ; ?>">
						</td>
						<td>
							<input type="text" name="step_2" id="step_2" class="step_2 form-control" value="<?php echo isset($steps->step_2) ? $steps->step_2 : 0 ; ?>">
						</td>
						<td>
							<input type="text" name="step_3" id="step_3" class="step_3 form-control" value="<?php echo isset($steps->step_3) ? $steps->step_3 : 0 ; ?>">
						</td>
						<td>
							<input type="text" name="step_4" id="step_4" class="step_4 form-control" value="<?php echo isset($steps->step_4) ? $steps->step_4 : 0 ; ?>">
						</td>
					</tr>
					<thead>
						<tr class="text-primary">
							<th>Step 5</th>
							<th>Step 6</th>
							<th>Step 7</th>
							<th>Step 8</th>
						</tr>
					</thead>
					<tr>
						<td>
							<input type="text" name="step_5" id="step_5" class="step_5 form-control" value="<?php echo isset($steps->step_5) ? $steps->step_5 : 0 ; ?>">
						</td>
						<td>
							<input type="text" name="step_6" id="step_6" class="step_6 form-control" value="<?php echo isset($steps->step_6) ? $steps->step_6 : 0 ; ?>">
						</td>
						<td>
							<input type="text" name="step_7" id="step_7" class="step_7 form-control" value="<?php echo isset($steps->step_7) ? $steps->step_7 : 0 ; ?>">
						</td>
						<td>
							<input type="text" name="step_8" id="step_8" class="step_8 form-control" value="<?php echo isset($steps->step_8) ? $steps->step_8 : 0 ; ?>">
						</td>
					</tr>
					<?php // } endif; ?>
				</tbody>
			</table>
			<div class="row">
				<div class="col-md-12" style="margin-bottom: 20px">
					<button id="saveSalaryGradeSteps" class="btn btn-primary btn-lg waves-effect" type="submit">
						<i class="material-icons">save</i><span> Save</span>
					</button>
					<button id="cancelUpdateForm" class="btn btn-default btn-lg waves-effect" data-dismiss="modal" type="button">
						<i class="material-icons">close</i><span> Close</span>
					</button>
				</div>
			</div>
		</div>
	</form>
</div>
