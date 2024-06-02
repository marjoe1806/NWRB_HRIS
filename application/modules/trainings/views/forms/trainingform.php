<?php
$readonly = "";
$required = "";
$disabled = "";
$inputRequired = "inputRequired";
if($key == "addManageTrainings" || $key == "updateManageTrainings") {
	$required = "required";
} elseif ($key == "viewManageTrainings" || $key == "viewTrainingList") {
	$readonly = "readonly";
	$disabled = "disabled";
}
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <input type="hidden" class="id" name="id" id="id" value="<?php echo isset($key) ? Helper::get('manage_trainings_id') : ''; ?>">
    <div class="row">
        <div class="col-md-12">
            <label class="form-label">Title of Learning And Development Interventions / Training Programs <span class="text-danger">*</span></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" name="title" id="title" class="title form-control" pattern="^[^\s]+(\s+[^\s]+)*$" <?php echo $required; ?> <?php echo $readonly ?>>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label class="form-label">Conducted / Sponsored By <span class="text-danger">*</span></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" name="sponsor" id="sponsor" class="sponsor form-control" pattern="^[^\s]+(\s+[^\s]+)*$" <?php echo $required; ?> <?php echo $readonly ?>>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label">Venue <span class="text-danger">*</span></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" name="venue" id="venue" class="venue form-control" pattern="^[^\s]+(\s+[^\s]+)*$" <?php echo $required; ?> <?php echo $readonly ?>>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <label class="form-label">Start Date <span class="text-danger">*</span></label>
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control date_mask start_date <?php echo $inputRequired; ?>" name="start_date" id="start_date" <?php echo $required; ?> <?php echo $readonly ?>>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <label class="form-label">End Date <span class="text-danger">*</span></label>
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control date_mask end_date <?php echo $inputRequired; ?>" name="end_date" id="end_date" <?php echo $required; ?> <?php echo $readonly ?>>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <label class="form-label">Number of Hours <span class="text-danger">*</span></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="number" name="no_of_hours" id="no_of_hours" class="no_of_hours form-control" pattern="^[^\s]+(\s+[^\s]+)*$" <?php echo $required; ?> <?php echo $readonly ?>>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <label class="form-label">Type of L&D (Managerial / Supervisory / Technical / etc.) <span class="text-danger">*</span></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" name="type" id="type" class="type form-control" pattern="^[^\s]+(\s+[^\s]+)*$" <?php echo $required; ?> <?php echo $readonly ?>>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label class="form-label">Fees <span class="text-danger">*</span></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="number" name="fees" id="fees" class="fees form-control" <?php echo $required; ?> <?php echo $readonly ?>>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label">Travel Allowance <span class="text-danger">*</span></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="number" name="travel_allowance" id="travel_allowance" class="travel_allowance form-control" <?php echo $required; ?> <?php echo $readonly ?>>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label class="form-label">Travel Order <span class="text-danger">*</span></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" name="travel_order" id="travel_order" class="travel_order form-control" pattern="^[^\s]+(\s+[^\s]+)*$" <?php echo $required; ?> <?php echo $readonly ?>>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label">Office Order <span class="text-danger">*</span></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" name="office_order" id="office_order" class="office_order form-control" pattern="^[^\s]+(\s+[^\s]+)*$" <?php echo $required; ?> <?php echo $readonly ?>>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <a href='#' id='select-all'>SELECT ALL &nbsp;/&nbsp;</a>
                    <a href='#' id='deselect-all'>DESELECT ALL</a>
                    <select class="ms" multiple="multiple" id="employee_ms" name="employee_ms[]" style="position: absolute; left: -9999px;" <?php echo $disabled ?>>
                        <?php if(isset($employees) && $employees): ?>
                            <?php foreach ($employees as $k => $v) : ?>
                                <option value="<?php echo $v['id']?>"><?php echo $v['name']?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <br>
                    <span style="color: red">
                        <small>
                            *All selected employees (on the right) will be joining the training set in this form.
                        </small>
                    </span>
                </div>
            </div>
        </div>
    </div>
	
	<div class="text-right" style="width:100%;">
		<?php if($key == "addManageTrainings"): ?>
			<button id="addManageTrainings" class="btn btn-primary btn-sm waves-effect" type="submit">
				<i class="material-icons">add</i><span> Add</span>
			</button>
		<?php endif; ?>
		<?php if($key == "updateManageTrainings"): ?>
			<button id="updateManageTrainings" class="btn btn-primary btn-sm waves-effect" type="submit">
				<i class="material-icons">save</i><span> Update</span>
			</button>
		<?php endif; ?>
		<button id="cancelManageTrainings" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
			<i class="material-icons">close</i><span> Close</span>
		</button>
	</div>
</form>