<?php //var_dump($this->uri->segment(1));die(); ?>
<?php if(!in_array(17001,$_SESSION["sessionModules"])): ?>
<div class="button-holder"></div>
<?php endif; ?>
<hr>
<div class="table-responsive listTable" style="width:100%;">
    <table id="datatables" class="table table-hover table-striped">
        <thead> 
            <tr  >
                <th>Action</th>    
                <th>Employee Number</th>
                <th>Full Name</th>
                <th>Position</th>
                <th>Division</th>                                    
          </tr>
        </thead>
    </table>
</div>