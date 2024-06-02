<?php //var_dump($this->uri->segment(1));die(); ?>
<div class="table-responsive listTable" style="width:100%;">
    <table id="datatables-gsis" class="table table-hover table-striped table-bordered">
        <thead> 
            <tr>
                <th>Salary Bracket</th>
                <th>Range 1</th>
                <th>Range 2</th>
                <th>Monthly Salary Credit</th>
                <th>Employer</th>
                <th>Employee</th>
                <th>E.C.</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>                                        
            </tr>
        </thead>
        <tbody>
            <?php 
            if(isset($list->Data->details) && sizeof($list->Data->details) > 0): 
                foreach ($list->Data->details as $index => $value) { ?>
                <tr>
                    <td><?php echo $value->id; ?></td>
                    <td><?php echo $value->compensation_range_1; ?></td>
                    <td><?php echo $value->compensation_range_2; ?></td>
                    <td><?php echo $value->monthly_salary_credit; ?></td>
                    <td><?php echo $value->monthly_contribution_employer; ?></td>
                    <td><?php echo $value->monthly_contribution_employee; ?></td>
                    <td><?php echo $value->ec; ?></td>
                     <td><?php echo $value->total; ?></td>
                    <td>
                        <?php echo ($value->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>'; ?></td>
                    <td>
                        <a  id="updateContributionsGSISForm" 
                            class="updateContributionsGSISForm btn btn-info btn-circle waves-effect waves-circle waves-float"
                            href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateContributionsGSISForm'; ?>"  data-toggle="tooltip" data-placement="top" title="Update" 
                            data-id="<?php echo $value->id; ?>"
                            <?php foreach ($value as $k => $v) {
                                echo ' data-'.$k.'="'.$v.'" ';
                            } ?>"
                        >
                            <i class="material-icons">mode_edit</i>
                        </a>
                        <?php if($value->is_active == "1"){ ?>
                            <?php //if(Helper::role(ModuleRels::DEACTIVATE_DOCUMENT_TYPE)): ?>
                                <a class="deactivateContributionsGSIS btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Deactivate" 
                                    data-id="<?php echo $value->id; ?>"
                                    href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateContributionsGSIS'; ?>">
                                    <i class="material-icons">do_not_disturb</i>
                                </a>
                            <?php //endif; ?>
                        <?php }else{ ?>
                            <?php //if(Helper::role(ModuleRels::ACTIVATE_DOCUMENT_TYPE)): ?>
                            <a class="activateContributionsGSIS btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Activate" 
                                data-id="<?php echo $value->id; ?>"
                                href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateContributionsGSIS'; ?>">
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