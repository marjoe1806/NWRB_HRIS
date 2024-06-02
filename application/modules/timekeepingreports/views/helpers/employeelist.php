<style>
	.dataTables_info {
		width: auto;
		float: right;
		margin-bottom: 10px;
	}
</style>


<div class="row">
	<div class="col-md-12">
		<div class="btn-group btn-group-lg btn-group-justified" role="group" aria-label="Justified button group">
			<a id="filterOptionsButton" class="btn bg-blue waves-effect" data-toggle="collapse" data-parent="#accordion" href="#filterContainer"><i class="material-icons">find_in_page</i>
				<span>Filter Options</span></a>
			<a id="printPreviewButton" class="btn bg-green waves-effect"><i class="material-icons">print</i> <span>Print Preview</span></a>
		</div>
		<!-- <h4 class="text-info text-center">Employee Daily Time Record</h4> -->
		<div class="panel-group" id="accordion">
			<div class="panel">
				<div id="filterContainer" class="panel-collapse collapse">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-6">
								<div class="input-group">
									<span class="input-group-addon">
										Search for
									</span>
									<div class="form-line">
										<input id="filterInput" type="text" class="form-control" disabled>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="btn-group btn-group-lg btn-group-justified filter-buttons" role="group" aria-label="Justified button group">
									<a class="btn filter-button bg-blue waves-effect" id="filterByID">Card No.</a>
									<a class="btn filter-button bg-blue waves-effect" id="filterByName">Name</a>
									<!-- <a class="btn filter-button bg-blue waves-effect" id="filterByDepartment">Dept.</a> -->
									<a class="btn filter-button bg-blue waves-effect" id="filterByOffice">Office</a>
									<a class="btn filter-button bg-blue waves-effect" id="filterByAgency">Agency</a>
									<a class="btn filter-button bg-blue waves-effect" id="filterByLocation">Location</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table id="module" class="table table-hover table-striped table-responsive">
				<thead>
					<tr class="employee-table-columns" style="background: #eee">
						<!-- <th>
							<input type="checkbox" id="check_all">
							<label for="check_all"></label>
						</th> -->
						<th class="employee-table-column" id="card-number-column">Card<br>Number</th>
						<th class="employee-table-column" id="name-column">Employee<br>Name</th>
						<th class="employee-table-column" id="department-column">Employee<br>Department</th>
						<th class="employee-table-column" id="office-column">Employee<br>Office</th>
						<th class="employee-table-column" id="agency-column">Employee<br>Agency</th>
						<th class="employee-table-column" id="location-column">Employee<br>Location</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if(isset($list) && $list != null) : { foreach($list as $k => $v) {?>
					<tr>
						<!-- <td>
							<input type="checkbox" class="checkbox_table required" id="checkbox<?php echo $k; ?>" value="<?php echo @$v['id']; ?>"
							 name="checkbox" data-id="<?php echo @$v['id']?>" data-employee-number="<?php echo @$v['employee_number']?>" data-shift-id="<?php echo @$v['shift_id']?>">
							<label for="checkbox<?php echo $k; ?>"></label>
						</td> -->
						<td>
							<?php echo @$v['employee_number']; ?>
						</td>
						<td>
							<?php echo @$v['last_name'] . ', ' . @$v['first_name'] . ' ' . ($v['middle_name'] != null && $v['middle_name'] == 'N/A' ? '' : $v['middle_name']); ?>
						</td>
						<td>
							<?php echo @$v['department_name']; ?>
						</td>
						<td>
							<?php echo @$v['office_name']; ?>
						</td>
						<td>
							<?php echo @$v['agency_code']; ?>
						</td>
						<td>
							<?php echo @$v['location_name']; ?>
						</td>
					</tr>
					<?php } } endif; ?>
				</tbody>
			</table>
		</div>


	</div>
</div>
</div>
