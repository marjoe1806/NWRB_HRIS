<?php
    $readonly = "";
    if($key == "viewPeriodSettingsDetails")
        $readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>"
method="POST">
    <div class="form-elements-container">
        <input type="hidden" name="id" class="id" value="">
        <!-- <div id="form-user" role="form" data-toggle="validator"> -->
        <div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Payroll Period
                    <span class="text-danger">*</span>
                </label>
                <div class="form-group">
                    <div class="form-line">
                        <!-- <input type="text" name="name" id="name" class="name form-control" required <?php echo $readonly ?>> -->
                        <input type="text" name="payroll_period" id="payroll_period" class="payroll_period datepicker form-control" value="<?php echo date('Y-m-d'); ?>" required <?php
                        echo $readonly ?>>
                        <!-- <div class="help-block with-errors"></div> -->
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Period Identification
                    <span class="text-danger">*</span>
                </label>
                <div class="form-group">
                    <div class="form-line">
                        <!-- <div class="help-block with-errors"></div> -->
                        <select name="period_id" id="period_id" class="period_id form-control" required <?php echo $readonly ?>>
                            <option value="" selected disabled=""></option>
                            <option value="Whole Period">Whole Period</option>
                            <option value="1st Period">1st Period</option>
                            <option value="2nd Period">2nd Period</option>
                            <option value="1st Week">1st Week</option>
                            <option value="2nd Week">2nd Week</option>
                            <option value="3rd Week">3rd Week</option>
                            <option value="4th Week">4th Week</option>
                            <option value="5th Week">5th Week</option>
                            <option value="6th Week">6th Week</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">From
                    <span class="text-danger">*</span>
                </label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="start_date" id="start_date" class="start_date form-control" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">To
                    <span class="text-danger">*</span>
                </label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="end_date" id="end_date" class="end_date form-control" required >
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-right" style="width:100%;">
        <?php if($key == "addPeriodSettings"): ?>
        <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
            <i class="material-icons">add</i>
            <span> Add</span>
        </button>
        <?php endif; ?>
        <?php if($key == "updatePeriodSettings"): ?>
        <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
            <i class="material-icons">save</i>
            <span> Update</span>
        </button>
        <!-- <?php if($status == "INACTIVE"): ?>
                <a id="activateUserLevelConfig" class="activateUserLevelConfig" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'activateUserLevelConfig'; ?>">
                    <button class="btn btn-success btn-sm waves-effect" type="button">
                        <i class="material-icons">visibility</i><span> Activate</span>
                    </button>
                </a>
            <?php endif; ?>
            <?php if($status == "ACTIVE"): ?>
            <a id="deactivateUserLevelConfig" class="deactivateUserLevelConfig" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'deactivateUserLevelConfig'; ?>">
                <button  class="btn btn-danger btn-sm waves-effect" type="button">
                    <i class="material-icons">visibility_off</i><span> Deactivate</span>
                </button>
            </a>
            <?php endif; ?> -->

        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i>
            <span> Close</span>
        </button>
    </div>
</form>
