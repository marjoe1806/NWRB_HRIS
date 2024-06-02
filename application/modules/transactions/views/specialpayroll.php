<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Special Payroll <small>Manage Special Payroll</small>
                </h2>
                
            </div>
            <div class="body">
                <div style="width:100%;padding-bottom:20px;" class="search_entry">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-info">Service / Division / Unit <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line division_select">
                                    <select class="division_id form-control" name="division_id" id="division_id" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <h5 class="text-info">Type of Bonus <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line bonuses_select">
                                    <select class="bonus_type form-control " name="bonus_type" id="bonus_type" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <h5 class="text-info">Year <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="year form-control" name="year" id="year" data-live-search="true">
                                        <?php 
                                        $years = array_combine(range(date("Y"), 2022), range(date("Y"), 2022));
                                        foreach ($years as $k => $v) {
                                            echo '<option value="'.$k.'">'.$v.'</option>';
                                        } 
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a id="searchEmployeeBonus">
                                <button type="button" class="btn btn-block btn-lg btn-info waves-effect">
                                    <i class="material-icons">people</i>
                                    <span> Search Record</span>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                <div id="table-holder" style="display:none">
                    <?php echo $table; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/transactions/specialpayroll.js"></script>