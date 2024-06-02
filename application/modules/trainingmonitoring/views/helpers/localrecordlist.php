<?php //var_dump($this->uri->segment(1));die(); ?>
<div class="table-responsive listTable">
    <table id="datatables" class="table table-hover table-striped" style="width:100%;">
        <thead> 
            <tr >
                <th>Start Date</th>
                <th>End Date</th>
                <th>Seminar / Training / Conference</th>
                <th>Conducted / Sponsored By</th>
                <th>Place</th>
                <th>Country</th>
                <th>Action</th>                                        
            </tr>
        </thead>
        <tbody>
            <?php 
            if(isset($list->Data->details) && sizeof($list->Data->details) > 0): 
                foreach ($list->Data->details as $index => $value) { ?>
                <tr>
                    <td><?php echo @$value->tm_start_date;//$value->type_name; ?></td>
                    <td><?php echo $value->tm_end_date; ?></td>
                    <td><?php echo @@$value->tm_seminar_training; ?></td>
                    <td><?php echo @$value->sponsored_by; ?></td>
                    <td><?php echo @$value->tm_place; ?></td>
                    <td><?php echo @$value->tm_country; ?></td>
                    <td>
                        <a  id="updateLocalRecordForm" 
                            class="updateLocalRecordForm btn btn-info btn-circle waves-effect waves-circle waves-float"
                            href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateLocalRecordForm'; ?>"  data-toggle="tooltip" data-placement="top" title="Update" 
                            data-id="<?php echo $value->tm_id; ?>"
                            <?php foreach ($value as $k => $v) {
                                echo ' data-'.$k.'="'.$v.'" ';
                            } ?>"
                        >
                            <i class="material-icons">mode_edit</i>
                        </a>
                        <a  id="previewLocalRecord" 
                            class="previewLocalRecord btn btn-info btn-circle waves-effect waves-circle waves-float"
                            href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/previewLocalRecord'; ?>"  data-toggle="tooltip" data-placement="top" title="Preview" 
                            data-id="<?php echo $value->tm_id; ?>"
                            <?php foreach ($value as $k => $v) {
                                echo ' data-'.$k.'="'.$v.'" ';
                            } ?>"
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