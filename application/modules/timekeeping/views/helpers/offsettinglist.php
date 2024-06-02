<div class="table-responsive listTable">
	<table id="datatables" class="table table-hover table-striped" style="width:100%;">
		<thead>
			<tr>
				<th>Date Requested</th>
				<th>No. of Hrs</th>
				<th>Name</th>
				<th>Purpose</th>
				<th>Checked by</th>
				<th>Status</th>
				<th width="10%">Action</th>
			</tr>
		</thead>
	</table>
</div>

<?php
    function formatBytes($size, $precision = 2) {
        $base = log($size, 1024);
        $suffixes = array('Bytes', 'KB', 'MB', 'GB', 'TB');
        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }
?>
