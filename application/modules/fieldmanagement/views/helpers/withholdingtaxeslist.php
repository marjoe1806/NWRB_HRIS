<?php //var_dump($this->uri->segment(1));die(); ?>
<div class="table-responsive listTable" style="width:100%;">
    <table id="datatables" class="table table-hover table-striped table-bordered">
        <thead >
            <tr>
                <th rowspan="2">Action</th>
                <th rowspan="2">#</th>
                <th colspan="2" class="text-center" valign="bottom">COMPENSATION LEVEL (Php.)</th>
                <th rowspan="2">TAX PERCENTAGE (%)</th>
                <th rowspan="2">TAX ADDITIONAL (Php.)</th>
                <th rowspan="2">STATUS</th>
                <th rowspan="2"></th>
            </tr>
            <tr>
                <th class="text-center">From</th>
                <th class="text-center">To</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if(isset($list->Data->details) && sizeof($list->Data->details) > 0):
                foreach ($list->Data->details as $index => $value) { ?>
                <tr>
                    <td>
                        <a  id="updateWithHoldingTaxesForm" 
                            class="updateWithHoldingTaxesForm btn btn-info btn-circle waves-effect waves-circle waves-float"
                            href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateWithHoldingTaxesForm'; ?>"  data-toggle="tooltip" data-placement="top" title="Update" 
                            data-id="<?php echo $value->id; ?>"
                            <?php foreach ($value as $k => $v) {
                                echo ' data-'.$k.'="'.$v.'" ';
                            } ?>
                        >
                            <i class="material-icons">mode_edit</i>
                        </a>
                        <?php if($value->is_active == "1"){ ?>
                            <?php //if(Helper::role(ModuleRels::DEACTIVATE_DOCUMENT_TYPE)): ?>
                                <a class="deactivateWithHoldingTaxes btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Deactivate" 
                                    data-id="<?php echo $value->id; ?>"
                                    href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateWithHoldingTaxes'; ?>">
                                    <i class="material-icons">do_not_disturb</i>
                                </a>
                            <?php //endif; ?>
                        <?php }else{ ?>
                            <?php //if(Helper::role(ModuleRels::ACTIVATE_DOCUMENT_TYPE)): ?>
                            <a class="activateWithHoldingTaxes btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Activate" 
                                data-id="<?php echo $value->id; ?>"
                                href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateWithHoldingTaxes'; ?>">
                                <i class="material-icons">done</i>
                            </a>
                            <?php //endif; ?>
                        <?php } ?>
                    </td>
                    <td><?php echo $index+1; ?></td>
                    <td><?php echo $value->compensation_level_from; ?></td>
                    <td><?php echo $value->compensation_level_to; ?></td>
                    <td><?php echo $value->tax_percentage; ?></td>
                    <td><?php echo $value->tax_additional; ?></td>
                    <td>
                        <?php echo ($value->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>'; ?>
                    </td>
                </tr>
            <?php }
            endif; ?>
        </tbody>
    </table>
</div>