<?php //print_r($data); ?>
<?php if(isset($data)) : ?>
<style>
    /* disabled a tag */
    .disabled {
       pointer-events: none;
       cursor: default;
       filter: brightness(0.75);
    }

    /* remove arrow button in input type number for Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    /* remove arrow button in input type number for Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }
</style>
<div class="listTable">
    <table id="datatables" class="table table-hover table-striped" style="width:100%;">
        <thead> 
            <tr  >
                <th>Employee Name</th>
                <th style="width: 8%">Salary</th>
                <th style="width: 15%">Amount</th>
                <th style="width: 20%">Issued By</th>
                <th style="width: 20%">Issued Date</th>
                <th style="width: 15%">Action</th>                                         
          </tr>
        </thead>
        <tbody>
        <?php foreach($data as $k => $v): ?>
        <?php
            $v['first_name'] = $this->Helper->decrypt($v['first_name'],$v['id']);
            $v['middle_name'] = $this->Helper->decrypt($v['middle_name'],$v['id']);
            $v['last_name'] = $this->Helper->decrypt($v['last_name'],$v['id']);

            $fullname = $v['last_name'].", ".$v['first_name'];
            if($v['middle_name'] != null)
                $fullname .= " ".$v['middle_name'][0].".";
        ?>
            <tr>
                <td><?php echo $fullname; ?></td>
                <td><?php echo number_format($v['salary'], 2); ?></td>
                <td>
                    <input type="number" class="form-control amount amount<?php echo $k; ?>" value="<?php echo $v['amount']; ?>" style="display:none">
                    <input type="text" class="form-control disp_amount<?php echo $k; ?>" value="<?php echo number_format($v['amount'], 2); ?>" style="text-align: right;" readonly>
                </td>
                <td><input type="text" class="form-control username username<?php echo $k; ?>" value="<?php echo $v['username']; ?>" readonly></td>
                <td><input type="text" class="form-control date_modified date_modified<?php echo $k; ?>" value="<?php echo $v['date_modified']; ?>" readonly></td>
                <td>
                    <a id="editSpecialPayroll" class="editSpecialPayroll<?php echo $k; ?>" style="text-decoration: none;" data-row="<?php echo $k; ?>" data-id="<?php echo $v['id']; ?>">  
                        <button class="btn btn-secondary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Edit"> 
                            <i class="material-icons">mode_edit</i>  
                        </button>  
                    </a>
                    <a id="saveSpecialPayroll" class="saveSpecialPayroll<?php echo $k; ?> disabled" style="text-decoration: none;" data-row="<?php echo $k; ?>" data-id="<?php echo $v['id']; ?>">  
                        <button class="btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Save"> 
                            <i class="material-icons">save</i>  
                        </button>  
                    </a>
                    <a id="cancelSpecialPayroll" class="saveSpecialPayroll<?php echo $k; ?> disabled" style="text-decoration: none;" data-row="<?php echo $k; ?>" data-id="<?php echo $v['id']; ?>" data-amount="<?php echo $v['amount']; ?>" data-username="<?php echo $v['username']; ?>" data-date_modified="<?php echo $v['date_modified']; ?>">  
                        <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Cancel"> 
                            <i class="material-icons">cancel</i>  
                        </button>  
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>