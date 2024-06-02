<?php //var_dump($this->uri->segment(1));die(); ?>
<div class="table-responsive listTable" style="width:100%;">
    <table id="datatables2" class="table table-hover table-striped">
        <thead> 
            <tr  >
                <th>Head Signatory</th>
                <th>Position</th>
                <th>Payroll Group</th>
                <th>E-Signature</th>
                <th>Status</th>
                <th>Action</th>                                        
          </tr>
        </thead>
        <tbody>
            <?php 
            // print_r($headlist->Data->details);
            if(isset($headlist->Data->details) && sizeof($headlist->Data->details) > 0): 
                foreach ($headlist->Data->details as $index => $value) { ?>
                <tr>
                    <td><?php echo $value->signatory; ?></td>
                    <td><?php echo $value->employee_id; ?></td>
                    <td><?php echo $value->payroll_code; ?></td>
                    <td> <img style="display:block; width:150px; height:auto;" src="<?php echo str_replace("./",base_url(),$value->file_dir).$value->file_name; ?>"/></td>
                    <td>
                        <?php echo ($value->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>'; ?></td>
                    <td>
                        <a  id="updateHeadSignatoriesForm" 
                            class="updateHeadSignatoriesForm btn btn-info btn-circle waves-effect waves-circle waves-float"
                            href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateHeadSignatoriesForm'; ?>"  data-toggle="tooltip" data-placement="top" title="Update" 
                            data-id="<?php echo $value->id; ?>"
                            <?php foreach ($value as $k => $v) {
                                echo ' data-'.$k.'="'.$v.'" ';
                            } ?>"
                        >
                            <i class="material-icons">mode_edit</i>
                        </a>
                        <?php if($value->is_active == "1"){ ?>
                            <?php //if(Helper::role(ModuleRels::DEACTIVATE_DOCUMENT_TYPE)): ?>
                                <a class="deactivateHeadSignatories btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Deactivate" 
                                    data-id="<?php echo $value->id; ?>"
                                    href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateHeadSignatories'; ?>">
                                    <i class="material-icons">do_not_disturb</i>
                                </a>
                            <?php //endif; ?>
                        <?php }else{ ?>
                            <?php //if(Helper::role(ModuleRels::ACTIVATE_DOCUMENT_TYPE)): ?>
                            <a class="activateHeadSignatories btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Activate" 
                                data-id="<?php echo $value->id; ?>"
                                href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateHeadSignatories'; ?>">
                                <i class="material-icons">done</i>
                            </a>
                            <?php //endif; ?>
                        <?php } ?>
                    </td>
                </tr>
            <?php }
            endif; ?>
        </tbody>
    </table>
</div>