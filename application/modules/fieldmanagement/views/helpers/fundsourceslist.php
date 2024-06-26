<?php //var_dump($this->uri->segment(1));die(); ?>
<div class="table-responsive listTable" style="width:100%;">
    <table id="datatables" class="table table-hover table-striped">
        <thead> 
            <tr  >
                <th>Fund Source</th>
                <th>Status</th>
                <th>Action</th>                                        
          </tr>
        </thead>
        <tbody>
            <?php 
            if(isset($list->Data->details) && sizeof($list->Data->details) > 0): 
                foreach ($list->Data->details as $index => $value) { ?>
                <tr>
                    <td><?php echo $value->fund_source; ?></td>
                    <td>
                        <?php echo ($value->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>'; ?></td>
                    <td>
                        <a  id="updateFundSourcesForm" 
                            class="updateFundSourcesForm btn btn-info btn-circle waves-effect waves-circle waves-float"
                            href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateFundSourcesForm'; ?>"  data-toggle="tooltip" data-placement="top" title="Update" 
                            data-id="<?php echo $value->id; ?>"
                            <?php foreach ($value as $k => $v) {
                                echo ' data-'.$k.'="'.$v.'" ';
                            } ?>"
                        >
                            <i class="material-icons">mode_edit</i>
                        </a>
                        <?php if($value->is_active == "1"){ ?>
                            <?php //if(Helper::role(ModuleRels::DEACTIVATE_DOCUMENT_TYPE)): ?>
                                <a class="deactivateFundSources btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Deactivate" 
                                    data-id="<?php echo $value->id; ?>"
                                    href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateFundSources'; ?>">
                                    <i class="material-icons">do_not_disturb</i>
                                </a>
                            <?php //endif; ?>
                        <?php }else{ ?>
                            <?php //if(Helper::role(ModuleRels::ACTIVATE_DOCUMENT_TYPE)): ?>
                            <a class="activateFundSources btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Activate" 
                                data-id="<?php echo $value->id; ?>"
                                href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateFundSources'; ?>">
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