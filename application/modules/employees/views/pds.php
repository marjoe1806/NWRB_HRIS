<div class="row clearfix" id="pdsForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                    <a  id="viewEmployeesForm" class="viewEmployeesForm" href="<?php echo base_url(); ?>employees/PDS/viewEmployeesForm" <?php echo $buttons_data;?>>
                        <button class="btn btn-success pull-right" data-toggle="tooltip" data-placement="top" title="Print Preview">Print Preview
                            <i class="material-icons">print</i>
                        </button> 
                    </a>
                <h2>
                    My PDS<small>Personal Data Sheet</small>
                </h2>
            </div>
            <div class="body">
                <?php echo isset($form)?($form):"";?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/employees/employeepds.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/employees/employees.js"></script> -->