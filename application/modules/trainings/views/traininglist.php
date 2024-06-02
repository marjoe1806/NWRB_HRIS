<div class="row clearfix" id="dvfilter">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                Training Report<small>Manage Training Report</small>
                </h2>
            </div>
            <div class="body">
                <div style="width:100%;" class="search_entry">
                    <div class="row">
                        <div class="col-md-2">
                            <h5 class="text-info">Year</h5>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="search_year form-control" name="search_year" id="search_year" data-live-search="true">
                                        <?php 
                                        $years = array_combine(range(date("Y"), 1900), range(date("Y"), 1900));
                                        foreach ($years as $k => $v) {
                                            echo '<option value="'.$k.'">'.$v.'</option>';
                                        } 
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <!-- <a href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>" data-toggle="tooltip" data-placement="top" title="Search"> -->
                                <button type="button" class="search_btn btn btn-primary btn-circle-lg waves-effect waves-circle waves-float">
                                    <i class="material-icons">search</i>
                                </button>
                            <!-- </a> -->
                        </div>
                    </div>
                </div>
                <div class="table-holder">
                    <?php echo $table ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/trainings/traininglist.js"></script>
