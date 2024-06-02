<div class="table-responsive listTable" style="width:100%;">
    <table id="datatables" class="table table-hover table-striped">
        <thead> 
            <tr  >
                <th>Payroll Period</th>
                <th>Monthly Amortization</th>
                <th>Total Amount Paid</th>    
                <th>Outstanding Balance</th>        
            </tr>
        </thead>
        <tbody>
            <?php 
            if(isset($list->Data->details) && sizeof($list->Data->details) > 0): 
                foreach ($list->Data->details as $index => $value) { ?>
                <tr>
                    <td><?php echo 'Adrian Vidal'//echo $value->userlevelname; ?></td>
                    <td><?php echo $value->designation; ?></td>
                    <td><?php echo $value->branch; ?></td>
                    <td><?php echo $value->branch; ?></td>
                </tr>
            <?php }
            endif; ?>
        </tbody>
    </table>
</div>