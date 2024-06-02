<?php 
	
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="row clearfix">
		<div class="col-md-12">
			<h4 class="text-info">Select Satellite Location</h4>
			<div class="form-group">
				<div class="form-line satellite_location_select">
					<select class="satellite_location form-control" name="satellite_location" id="satellite_location" data-live-search="true">
						<option value="" selected></option>
					</select>
				</div>
			</div>
		</div>
	</div>
    <div class="text-right" style="width:100%;">
    	<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">search</i><span> Search</span>
	        </button>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>

