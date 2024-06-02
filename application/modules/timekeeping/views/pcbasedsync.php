<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    PC Based Syncing <small>PC Based Syncing Logs</small>
                </h2>
                
            </div>
            <div class="body">
                <div style="width:100%;padding-bottom:20px;" class="search_entry">
                   
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Date from</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="date_from" id="date_from" class="date_from datepicker form-control"  required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date to</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="date_to" id="date_to" class="date_to form-control"  required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <a id="pcsync" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/pcSyncing' ?>" data-toggle="tooltip" data-placement="top" title="Sync PC Based">
                                <button type="button" class="btn btn-primary btn-circle-lg waves-effect waves-circle waves-float">
                                    <i class="material-icons">sync</i>
                                </button>
                            </a>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/timekeeping/pcbasedsync.js"></script>