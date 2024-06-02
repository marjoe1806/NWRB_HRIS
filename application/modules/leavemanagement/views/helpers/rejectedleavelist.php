<?php //var_dump($this->uri->segment(1));die(); ?>
<div class="table-responsive listTable" style="width:100%;">
    <table id="datatables" class="table table-hover table-striped">
        <thead> 
            <tr  >
                <th>Name</th>
                <th>Date Filed</th>
                <th>Kind</th>
                <th>Type</th>
                <th>Status</th>
                <th>Action</th>                                        
          </tr>
        </thead>
        <tbody>
            <?php 
            if(isset($list->Data->details) && sizeof($list->Data->details) > 0): 
                foreach ($list->Data->details as $index => $value) { ?>
                <tr>
                    <td><?php echo $value->leave_employee_name;//echo $value->userlevelname; ?></td>
                    <td>
                        <?php 
                            $date_created = strtotime($value->leave_created);  
                            echo date('M d, Y',$date_created); 
                        ?>
                    </td>
                    <td><?php echo $value->leave_kind; ?></td>
                    <td><?php echo ucwords(str_replace("_"," ",$value->leave_type)); ?></td>                    
                    <td><?php echo $value->leave_status; ?></td>
                    <td>
                        <a  id="viewRejectedLeaveDetails"
                            class="viewRejectedLeave<?php echo $value->leave_kind; ?>Details btn btn-info btn-circle waves-effect waves-circle waves-float"
                            href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewRejectedLeave'.$value->leave_kind.'Details'; ?>"  data-toggle="tooltip" data-placement="top" title="View Details"
                            data-id="<?php echo $value->leave_id; ?>"
                            <?php foreach ($value as $k => $v) {
                                echo ' data-'.$k.'="'.$v.'" ';
                            } ?>
                        >
                            <i class="material-icons">remove_red_eye</i>
                        </a>
                    </td>
                </tr>
            <?php }
            endif; ?>
        </tbody>
    </table>
</div>