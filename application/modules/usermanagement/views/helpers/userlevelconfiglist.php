<?php //var_dump($this->uri->segment(1));die(); ?>
<div class="table-responsive listTable" style="width:100%;">
    <table id="datatables" class="table table-hover table-striped">
        <thead class="text-primary">
            <tr>
                <th>User Account Level</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if(isset($list->Data->details) && sizeof($list->Data->details) > 0): 
                foreach ($list->Data->details as $index => $value) {
                    ?>

                <tr>
                    <td><?php echo $value->userlevelname; ?></td>
                    <td><?php echo $value->description; ?></td>
                    <td>
                        <?php 
                            if($value->status == "ACTIVE")
                                $textClass = "text-success";
                            else
                                $textClass = "text-danger";
                            echo '<label class="'.$textClass.'">'.$value->status.'</label>'; 
                        ?>
                    </td>
                    <td style="width:112px;">

                        <!--<a id="updateUserLevelConfigForm" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateUserLevelConfigForm'; ?>" class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Update" 
                            data-id="<?php echo $value->userlevelid; ?>"
                            data-status="<?php echo $value->status; ?>">
                            <i class="material-icons">mode_edit</i>
                        </a>-->

                        <a id="updateUserLevelConfigForm" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'updateUserLevelConfigForm'; ?>" class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Update" 
                                data-id="<?php echo $value->userlevelid; ?>"
                                data-status="<?php echo $value->status; ?>">
                                    <i class="material-icons">mode_edit</i>
                        </a>

                    </td>
                </tr>
            <?php }
            endif; ?>
        </tbody>
    </table>
</div>