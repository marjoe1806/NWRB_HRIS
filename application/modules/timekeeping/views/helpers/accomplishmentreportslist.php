<div class="table-responsive listTable">
	<table id="datatables" class="table table-hover table-striped" style="width:100%;">
		<thead>
			<tr>
				<th>Employee</th>
				<th>Document Type</th>
				<th>File Name</th>
				<th>File Size</th>
				<th>Date</th>
				<th>Status</th>
				<th width="10%">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
            if(isset($list->Data->details) && sizeof($list->Data->details) > 0): 
                foreach ($list->Data->details as $index => $value) { ?>
			<tr>
				<td>
					<?php echo $value->employee_lastname . ', '. $value->employee_firstname . ' ' . $value->employee_middlename ; ?>
				</td>
				<td>
					<?php echo $value->document_desc; ?>
				</td>
				<td>
					<?php echo $value->file_name; ?>
				</td>
				<td>
					<?php echo formatBytes($value->file_size); ?>
				</td>
				<td>
					<?php echo $value->accomplishment_date; ?>
				</td>
				<td>
					<?php echo ($value->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>'; ?>
				</td>
				<td>
					<a id="downloadAccomplishmentReportsForm" class="downloadAccomplishmentReportsForm btn btn-info btn-circle waves-effect waves-circle waves-float"
					href="<?php echo str_replace(' ', '%20', base_url() . 'assets/uploads/accomplishmentreports/' . $value->file_name); ?>" data-toggle="tooltip"
					data-placement="top" title="Download" data-id="<?php echo $value->id; ?>" <?php foreach ($value as $k=> $v) { echo ' data-'.$k.'="'.$v.'" '; } ?>" target="_blank" download>
						<i class="material-icons">vertical_align_bottom</i>
					</a>
					<!-- <a id="updateAccomplishmentReportsForm" class="updateAccomplishmentReportsForm btn btn-info btn-circle waves-effect waves-circle waves-float"
					href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateAccomplishmentReportsForm'; ?>"
					data-toggle="tooltip" data-placement="top" title="Update" data-id="<?php echo $value->id; ?>" <?php foreach ($value
					as $k=> $v) { echo ' data-'.$k.'="'.$v.'" '; } ?>">
						<i class="material-icons">mode_edit</i>
					</a> -->
					<?php if($value->is_active == "1"){ ?>
					<a class="deactivateAccomplishmentReports btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip"
					data-placement="top" title="Deactivate" data-id="<?php echo $value->id; ?>" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateAccomplishmentReports'; ?>">
						<i class="material-icons">do_not_disturb</i>
					</a>
					<?php }else{ ?>
					<a class="activateAccomplishmentReports btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip"
					data-placement="top" title="Activate" data-id="<?php echo $value->id; ?>" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateAccomplishmentReports'; ?>">
						<i class="material-icons">done</i>
					</a>
					<?php } ?>
				</td>
			</tr>
			<?php } endif; ?>
		</tbody>
	</table>
</div>

<?php
    function formatBytes($size, $precision = 2) {
        $base = log($size, 1024);
        $suffixes = array('Bytes', 'KB', 'MB', 'GB', 'TB');   
        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }
?>