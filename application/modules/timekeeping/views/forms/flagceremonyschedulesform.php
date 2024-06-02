<?php
	// print_r($params);
?>

<div class="row clearfix">
    <div class="col-md-12">
        <label class="form-label">Date <span class="text-danger">*</span></label>
        <div class="form-group">
            <div class="form-line">
                <input type="text" name="flagdateceremony" id="flagdateceremony" class="flagdateceremony datepicker form-control" value="" required>
            </div>
        </div>
    </div>
    <div>
    	<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
    	<span class="pull-right">&nbsp;</span>

    	<button type="button" class="updateFlagCeremonySchedules btn btn-info pull-right" data-month="<?php echo $params['month']; ?>" data-year="<?php echo $params['year']; ?>" 
        href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$params['key']; ?>">Update</button>
    </div>    
</div>
