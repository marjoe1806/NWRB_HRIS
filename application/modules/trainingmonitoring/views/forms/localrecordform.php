<?php 
	$readonly = "";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
	
    <div class="form-elements-container">
    	<input type="hidden" class="tm_id" name="table1[tm_id]" value="">
    	<?php if($key == "addLocalRecord" || $key == "updateLocalRecord"): ?>
			<div class="row clearfix">
				<div class="col-md-12">
					<label class="form-label" style="font-size: 1.25rem">Seminar / Training / Conference</label>
					<div class="form-group form-float">
						<div class="form-line">
							<input type="text" class="tm_seminar_training form-control" id="event_name" name="table1[tm_seminar_training]" required>
						</div>
					</div>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-12">
					<label class="form-label" style="font-size: 1.25rem">Conducted/Sponsored By</label>
					<div class="form-group form-float">
						<div class="form-line">
							<input type="text" class="sponsored_by form-control" id="sponsored_by" name="table1[sponsored_by]" required>
						</div>
					</div>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-6">
					<label class="form-label" style="font-size: 1.25rem">Place</label>
					<div class="form-group form-float">
						<div class="form-line">
							<input type="text" class="tm_place form-control" id="place" name="table1[tm_place]" required>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<label class="form-label" style="font-size: 1.25rem">Country</label>
					<div class="form-group form-float">
						<div class="form-line">
							<select class="form-control tm_country nopointerevent" id="country" name="table1[tm_country]" required>
								<option value="Philippines" selected>Philippines</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-6">
					<label class="form-label" style="font-size: 1.25rem">Start Date</label>
					<div class="form-group form-float">
						<div class="form-line">
							<input type="text" class="datepicker tm_start_date form-control" id="start_date" name="table1[tm_start_date]" required>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<label class="form-label" style="font-size: 1.25rem">End Date</label>
					<div class="form-group form-float">
						<div class="form-line">
							<input type="text" class="datepicker tm_end_date form-control" id="end_date" name="table1[tm_end_date]" required>
						</div>
					</div>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-6">
					<p>
						<input type="checkbox" name="table1[is_travel_time_inclusive]" id="travel_time_flag" class="filled-in chk-col-green" value="1">
						<label for="travel_time_flag">
							<strong>Travel Time Inclusive</strong>
						</label>
					</p>
				</div>
				<div class="col-md-6">
					<p>
						<input type="checkbox" name="table1[is_with_travel_report]" id="travel_report_flag" class="filled-in chk-col-green" value="1">
						<label for="travel_report_flag">
							<strong>
								With Travel Report</strong>
						</label>
					</p>
				</div>
			</div>
			<hr>
			<div class="row clearfix">
				<div class="col-md-12">
					<label class="form-label" style="font-size: 1.25rem">Participant Details</label>
					<table id="participant_table" class="table table-hover">
						<tbody>
							<tr class="first_row">
								<td class="first_column">
									<div class="form-group form-float">
										<div class="form-line">
											<select class="employee_select form-control" id="employee_select" name="table2[tmp_participant][]" data-live-search="true" required>
											</select>
										</div>
									</div>
									<input type="hidden" class="employee_select_name" name="table2[tmp_participant_name][]">
								</td>
								<td class="second_column" style="text-align:right;">
									<!-- <button id="removeParticipant" class="btn btn-danger btn-circle waves-effect waves-circle waves-float" type="button">
							            <i class="material-icons">remove</i>
							        </button> -->
								</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td></td>
								<td style="text-align:right;">
						    		<button id="addParticipant" class="addParticipant btn btn-info btn-circle waves-effect waves-circle waves-float" type="button">
							            <i class="material-icons">add</i>
							        </button>
								</td>
							</tr>
						</tfoot>
						
					</table>
				</div>
			</div>
		<?php endif; ?>
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addLocalRecord"): ?>
    		<button id="addLocalRecord" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateLocalRecord" || $key == "changeUpload"): ?>
	        <button id="updateLocalRecord" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update</span>
	        </button>
        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>

