<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Approved Leave/s <small>Manage Approved Leave/s</small>
                </h2>
                
            </div>
            <div class="body">
                <div class="col-md-8">
                </div>
                <?php 
                    $selected1 = isset($_GET['Kind']) && $_GET['Kind'] == "Regular"?"selected":""; 
                    $selected2 = isset($_GET['Kind']) && $_GET['Kind'] == "Special"?"selected":""; 
                ?>
                <div class="col-md-4">
                    <label class="form-label">Leave Kind</label>
                    <div class="form-group">
                        <div class="form-line">
                            <select class="leave_kind_search form-control" name="leave_kind" id="leave_kind_search">
                                <option value="" selected>All</option>
                                <option value="Regular" <?php echo $selected1; ?>>Regular</option>
                                <option value="Special" <?php echo $selected2; ?>>Special</option>
                            </select>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/leavemanagement/approvedleave.js"></script>