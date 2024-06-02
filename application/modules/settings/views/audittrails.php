<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Audit Trails <small>Track System Logs</small>
                </h2>
                
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Day</label>
                            <select name="day" id="day" class="form-control">
                                <?php 
                                    for($i = 1;$i <= 31;$i++){
                                        echo '<option value="'.$i.'" '. ($i == date("d") ? 'selected' : '') .'>'.$i.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Month</label>
                            <select name="month" id="month" class="form-control">
                                <option value="1" <?php echo (int)date("m") == 1 ? "selected" : ""; ?>>January</option>
                                <option value="2" <?php echo (int)date("m") == 2 ? "selected" : ""; ?>>February</option>
                                <option value="3" <?php echo (int)date("m") == 3 ? "selected" : ""; ?>>March</option>
                                <option value="4" <?php echo (int)date("m") == 4 ? "selected" : ""; ?>>April</option>
                                <option value="5" <?php echo (int)date("m") == 5 ? "selected" : ""; ?>>May</option>
                                <option value="6" <?php echo (int)date("m") == 6 ? "selected" : ""; ?>>June</option>
                                <option value="7" <?php echo (int)date("m") == 7 ? "selected" : ""; ?>>July</option>
                                <option value="8" <?php echo (int)date("m") == 8 ? "selected" : ""; ?>>August</option>
                                <option value="9" <?php echo (int)date("m") == 9 ? "selected" : ""; ?>>September</option>
                                <option value="10" <?php echo (int)date("m") == 10 ? "selected" : ""; ?>>October</option>
                                <option value="11" <?php echo (int)date("m") == 11 ? "selected" : ""; ?>>November</option>
                                <option value="12" <?php echo (int)date("m") == 12 ? "selected" : ""; ?>>December</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Year</label>
                            <select name="year" id="year" class="form-control">
                                <?php 
                                    $y = (int)date("Y");
                                    for($i = 0;$i <= 4;$i++){
                                        $yr = ($y - $i);
                                        echo '<option value="'.$yr.'" '. ($yr == date("Y") ? 'selected' : '') .'>'.$yr.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="button" id="btnsearch" class="btn btn-primary btn-circle-lg waves-effect waves-circle waves-float"><i class="material-icons">search</i></button>
                        </div>
                    </div>
                </div>
                <div id="table-holder">
                    <?php echo $table; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/settings/audittrails.js"></script>