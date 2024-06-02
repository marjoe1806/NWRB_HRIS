<?php //var_dump($this->uri->segment(1));die(); ?>
<?php if(!in_array(17001,$_SESSION["sessionModules"])): ?>
<div class="button-holder"></div>
<?php endif; ?>
<hr>
<div class="table-responsive listTable" style="width:100%;">
    <table id="datatables" class="table table-hover table-striped">
        <thead> 
            <tr  >
                <th style="min-width:70px">Action</th>  
                <th style="min-width:80px">Employee Number</th>
                <th style="min-width:170px">Full Name</th>
                <th style="min-width:150px">Position</th>
                <th style="min-width:170px">Division</th>
                <th style="min-width:80px">Basic Salary</th>
                <th style="min-width:100px">Employment Status</th>
                <!-- <th>Status</th> -->                                      
          </tr>
        </thead>
    </table>
</div>