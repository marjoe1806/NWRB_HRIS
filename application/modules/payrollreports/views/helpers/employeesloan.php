<div class="row">
    <div class="col-md-12">
        <div class="btn-group btn-group-lg btn-group-justified" role="group" aria-label="Justified button group">
            <a id="printPreviewButton" class="btn bg-green waves-effect"><i class="material-icons">print</i> <span>Print Preview</span></a>
        </div>
        <!-- <h4 class="text-info text-center">Employee Daily Time Record</h4> -->
        </div>
        <div id="clearance-div">
            <style type = 'text/css'>
                @media print{
                    /*280mm 378mm
                      11in 15in
                    */
                    html {
                        height: 0;
                    }
                    @page { 
                        size: US Std Fanfold; 

                    }
                    body {
                       font-family:Calibri;
                       font-size: 12;
                       color: black;
                    }
                    table{
                        border-collapse: collapse;
                        width:100%;
                        
                    }
                    .page-break{
                        display: table;
                        vertical-align:top;
                        width: 100% !important;
                        page-break-inside: avoid !important;
                        table-layout: inherit;
                        margin-top:2px;
                    }                        
                }
            </style> 
            <?php
                $payroll = $list;
                // var_dump(json_encode($payroll));
                /*$payroll = array_merge($payroll,$payroll);
                $payroll = array_merge($payroll,$payroll);*/
                $payroll_orig = $payroll;
                // var_dump($payroll);die();
                $total_per_page = 10;
                $total_next_page = 10;
                $percent_dec = .5;
                $total_page = floor(sizeof($payroll)/$total_per_page) + 1;
                if(( sizeof($payroll) < $total_next_page && sizeof($payroll) > floor($total_next_page*$percent_dec) ) || (sizeof($payroll) > $total_next_page && (sizeof($payroll)) - ($total_next_page * $total_page) > floor($total_next_page*$percent_dec) )){
                    $total_page = floor(sizeof($payroll)/$total_per_page) + 2;
                }
            ?>
            <div class="header-container" style="width:100%;">
                <table style="width:100%;border-bottom:0px;">
                    <thead>
                        <tr>
                            <td style="width:33%;text-align:left" nowrap valign="top">Date/Time Printed <?php echo date('m/d/Y  h:i:sa'); ?></td>
                            <td style="width:33%;text-align:center" nowrap valign="top"><label>
                            NATIONAL WATER RESOURCES BOARD
                                <br><?php 
                                if(isset($payroll[0]['amountPShare'])){
                                    echo strtoupper($payroll_orig[0]['loan']);
                                } else{
                                    echo "GENERAL PAYROLL";
                                } ?>
                                <br><?php echo strtoupper(@$payroll_grouping[0]['payroll_grouping_name']); ?>
                                <br>
                                <b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['start_date'])); ?></b> &emsp; to &emsp; <b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['end_date'])); ?></b>
                            </label></td>
                            <td style="width:33%;text-align:right" nowrap valign="top"><label>Page No.: 1 of <?php echo $total_page; ?></label></td>
                        </tr>
                    </thead>
                </table>
                <?php
                // var_dump(sizeof($payroll));die();

                $page_count = 1;
                $grand_total = array(
                    'amount'=>0.00,
                    'amountGShare'=>0.00,
                    'othersAmount' =>0.00
                );
                $last_row = 0;
                $count = 1;
                $page = 0;
                
                ?>
                <div class="table-container table-responsive">
                    <table style="width:100%;" id="#main_table">
                    <?php 
                    while(sizeof($payroll) > 0){
                        $page++; 
                        $page_total = array(
                            'amount'=>0.00,
                            'amountGShare'=>0.00,
                            'othersAmount' =>0.00
                        );
                    ?>
                        <tbody class="page-break">
                            <?php if($count > 1): ?>
                            <tr>
                                <td colspan="100" style="text-align:right"><label>Page No.: <?php echo $page.' of '.$total_page; ?></label></td>
                            </tr>
                            <?php endif; ?>
                            <tr style="border-top: 1px solid black;">
                                <td colspan="100" style="height:10px;"></td>
                            </tr>
                            <tr class="" style="border-bottom: 1px solid black;border-top:1px solid black;" style="font-weight:bold;">
                                <td style="text-align:center;width:30px;" valign="top" nowrap>NO.</td>
                                <td style="text-align:left;" colspan="3" valign="middle" nowrap>
                                    NAME OF EMPLOYEE
                                </td>
                                <td style="text-align:center;" valign="middle" nowrap>Position Title</td>
                                <?php if(isset($payroll_orig[0]['amountPShare'])){ ?>
                                <td style="text-align:center;" valign="middle" nowrap>P/Share</td>
                                <td style="text-align:center;" valign="middle" nowrap>G/Share</td>
                                <?php } else { ?>
                                <td style="text-align:center;" valign="middle" nowrap><?php echo $payroll_orig[0]['loan']; ?></td>
                                     <?php if(isset($payroll_orig[0]['otherAmount'])){ ?>
                                         <td style="text-align:center;" valign="middle" nowrap>Other Deduction</td>
                                     <?php } ?>
                                <?php } ?>
                                    <td style="text-align:center;" valign="middle" nowrap>Remarks</td>
                            </tr>
                            <?php 
                            foreach ($payroll as $k => $v) {
                                // $totAmount += ((isset($v["amountPShare"]))?(float)$v["amountPShare"]:0);
                                // $totGS += ((isset($v["amountGShare"]))?(float)$v["amountGShare"]:0);
                                // $totPS += ((isset($v["amount"]))?(float)$v["amount"]:0);
                                // if($v["amount"] != 0):
                            ?>
                                <tr style="height:80px;" class="">
                                    <td nowrap="" valign="middle">
                                       <?php echo $count; ?> 
                                    </td>
                                    <td nowrap="" style="text-align:left;" valign="middle">
                                        <?php echo $v['last_name']; ?>
                                        
                                    </td>
                                    <td nowrap="" style="text-align:left;" valign="middle">
                                        <?php echo $v['first_name']; ?>
                                    </td>
                                    <td nowrap="" style="text-align:left;" valign="middle">
                                        <?php echo $v['middle_name']; ?>
                                    </td nowrap="" style="text-align:left;" valign="middle">
                                    <td nowrap style="text-align:center;" valign="middle">
                                        <?php echo $v['position_name']; ?>
                                    </td>
                                    <?php if(isset($payroll_orig[0]['amountPShare'])){ ?>
                                    <td style="text-align:center;" valign="middle" nowrap>
                                        <?php $grand_total['amount'] += $v['amountPShare']; ?>
                                        <?php $page_total['amount'] += $v['amountPShare']; ?>
                                        <b><?php echo number_format((double)@$v['amountPShare'],2); ?></b>
                                    </td>
                                    <td style="text-align:center;" valign="middle" nowrap>
                                        <?php $grand_total['amountGShare'] += $v['amountGShare']; ?>
                                        <?php $page_total['amountGShare'] += $v['amountGShare']; ?>
                                        <b><?php echo number_format((double)@$v['amountGShare'],2); ?></b>
                                    </td>
                                    <?php } else { ?>
                                    <td nowrap style="text-align:center;" valign="middle">
                                    	<?php 
                                    		if($v['loan'] == "Net Pay 50%")
                                    			$v['amount'] = $v['amount']/2;
                                    	?>
                                        <?php $grand_total['amount'] += $v['amount']; ?>
                                        <?php $page_total['amount'] += $v['amount']; ?>
                                        <b><?php echo number_format((double)@$v['amount'],2); ?></b>
                                    </td>
                                         <?php if(isset($payroll_orig[0]['otherAmount'])){ 
                                            $grand_total['othersAmount'] += $v['otherAmount'];
                                            $page_total['othersAmount'] += $v['otherAmount'];

                                        ?>
                                            <td style="text-align:center;" valign="middle" nowrap><?php echo $payroll_orig[0]['otherAmount']; ?></td>
                                        <?php } ?>
                                    <?php } ?>
                                    <td style="text-align:center; width: 80px;" valign="middle">
                                        <div style="width:80%;border-bottom:1px solid black;height:30px;text-align:left"></div>
                                    </td>
                                </tr>
                                <?php
                                // endif;
                                unset($payroll[$k]); 
                                $count++;
                                $last_row ++;
                                if($last_row == $total_per_page){
                                    $total_per_page = $total_next_page;
                                } 
                                if((($k+1)/$total_per_page) === (intval(($k+1)/$total_per_page)) || sizeof($payroll) == 0){ ?>
                                    <tr style="height:30px;text-align:center;font-weight:bold;border-top:1px solid black;border-bottom:1px solid black;" class="page_total">
                                        <td style="text-align:center" valign="middle" colspan="5">PAGE TOTAL:</td>
                                        
                                        <td valign="middle" style="text-align:center;">
                                            <?php echo number_format((double)@$page_total['amount'],2); ?>
                                        </td>
                                         <?php if(isset($payroll_orig[0]['otherAmount'])) { ?>
                                        <td valign="middle" style="text-align:center;">
                                            <?php echo number_format((double)@$page_total['othersAmount'],2); ?>
                                        </td>
                                        <?php } ?>
                                        <?php if(isset($payroll_orig[0]['amountGShare'])) { ?>
                                        <td valign="middle" style="text-align:center;">
                                            <?php echo number_format((double)@$page_total['amountGShare'],2); ?>
                                        </td>
                                        <?php } ?>
                                        <td style="width: 80px;"></td>
                                    </tr>
                                <?php if(sizeof($payroll) == 0 &&  (( $count-1 <= $total_next_page && $count-1 <= floor($total_next_page*$percent_dec) ) || ($count-1 > $total_next_page && ($count-1) - ($total_next_page * $total_page) <= floor($total_next_page*$percent_dec) ) ) ): ?>
                                    <tr style="height:30px;text-align:center;font-weight:bold;border-top:1px solid black;border-bottom:1px solid black;" class="page_total">
                                        <td style="text-align:right" valign="top" colspan="5">Certified:<span style="visibility: hidden;"> Funds available in the amount of</span>
                                            <br>Funds available in the amount of
                                        </td>
                                        
                                        <td valign="middle" style="text-align:center;">
                                           
                                            <div style="width:100%;border-bottom:1px solid black; height:30px;text-align:center;">
                                                 <?php echo number_format((double)@$grand_total['amount'],2); ?>
                                            </div>
                                        </td>
                                        <?php if(isset($payroll_orig[0]['amountGShare'])){ ?>
                                        <td valign="middle" style="text-align:center;">
                                           
                                            <div style="width:100%;border-bottom:1px solid black;height:30px;text-align:center;">
                                                 <?php echo number_format((double)@$grand_total['amountGShare'],2); ?>
                                            </div>
                                        </td>
                                        <?php } ?>
                                        <td style="width: 35px;"></td>
                                    </tr>
                                    <tr class="signatories" style="margin-top:10px;border-top: 1px solid; ">
                                        <td colspan="100">
                                            <?php
                                                // $signatory3 = "";
                                                // $signatory3_position = "";
                                                // if(sizeof($signatories) > 0){
                                                //     foreach ($signatories as $k1 => $v1) {
                                                //         if($v1['signatory_no'] == "3" ){
                                                //             $signatory3 = $v1['signatory'];
                                                //             $signatory3_position = $v1['employee_id'];
                                                //         }
                                                //     }
                                                // }
                                                // $signatory2 = "";
                                                // $signatory2_position = "";
                                                // if(sizeof($signatories) > 0){
                                                //     foreach ($signatories as $k1 => $v1) {
                                                //         if($v1['signatory_no'] == "2" ){
                                                //             $signatory2 = $v1['signatory'];
                                                //             $signatory2_position = $v1['employee_id'];
                                                //         }
                                                //     }
                                                // }
                                                
                                            ?>

                                            <table style="width: 100%;text-align: left;">
                                                <tr>
                                                    <td style="text-align: left;width:50%;" nowrap></td>
                                                    <td style="text-align: left;width:50%;" nowrap>
                                                        APPROVED
                                                        <br> BY AUTHORITY OF THE CHAIRMAN
                                                    </td>
                                                </tr>
                                                <tr style="text-align: left;" nowrap>
                                                    <td colspan="2">
                                                        <br>
                                                        <br>
                                                    </td>
                                                </tr>
                                                <tr style="text-align: left;" nowrap>
                                                    <td style="text-align: left;width:50%;" nowrap>
                                                        <b><?php //echo strtoupper(@$signatory2); ?></b>
                                                        <br><?php //echo ucfirst(@$signatory2_position); ?>
                                                    </td>
                                                    <td style="text-align: left;width:50%;" nowrap>
                                                        <b><?php //echo strtoupper(@$signatory3); ?></b>
                                                        <br><?php //echo ucfirst(@$signatory3_position); ?>
                                                    </td>
                                                    
                                                </tr>
                                                <tr style="text-align: left;" nowrap>
                                                    <td colspan="2" style="text-align: left;width:50%;" nowrap>
                                                        <br>
                                                        <br>
                                                        CERTIFIED: Each employees whose name appears above has been paid the amount indicated his/her name.
                                                    </td>
                                                </tr>
                                                <tr style="text-align: left;" nowrap>
                                                    <td></td>
                                                    <td style="text-align:center;" nowrap="">
                                                        <div style="width:50%;align:middle;border-bottom:1px solid black;height:30px;text-align:center;margin-left:25%;">
                                                            <?php echo $signatories[0]["employee_name"]; //name ?>
                                                        </div>
                                                        <?php echo $signatories[0]["position_designation"] != "" || $signatories[0]["position_designation"] != null ? $signatories[0]["position_designation"] : $signatories[0]["position_title"]; //position ?><br>
                                                        <?php echo $signatories[0]["division_designation"] != "" || $signatories[0]["division_designation"] != null ? $signatories[0]["division_designation"] : $signatories[0]["department"]; //position ?><br>
                                                        Disbursing Officer
                                                    </td>
                                                </tr>
                                            </table>
                                            
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                                <?php 
                                    break;
                                }
                                 
                            } ?> 
                            <!-- Grand Total -->
                            <?php if(sizeof($payroll) == 0 &&  (( $count-1 <= $total_next_page && $count-1 > floor($total_next_page*$percent_dec) ) || ($count-1 > $total_next_page && ($count-1) - ($total_next_page * $total_page) > floor($total_next_page*$percent_dec) ) ) ):
                                $page++;
                            ?>
                            <tfoot class="page-break">
                                <tr>
                                    <td colspan="100" style="text-align:right"><label>Page No.: <?php echo $page.' of '.$total_page; ?></label></td>
                                </tr>
                                <tr style="height:30px;text-align:center;font-weight:bold;border-top:1px solid black;border-bottom:1px solid black;" class="page_total">
                                    <td style="text-align:right" valign="top" colspan="5">Certified:<span style="visibility: hidden;"> Funds available in the amount of</span>
                                    	<br>Funds available in the amount of
                                    </td>
                                    
                                    <td valign="middle" style="text-align:right;">
                                       
                                        <div style="width:70%;border-bottom:1px solid black;height:30px;text-align:right;">
                                        	 <?php echo number_format((double)@$grand_total['amount'],2); ?>
                                        </div>
                                    </td>
                                    <?php if(isset($payroll_orig[0]['amountGShare'])){ ?>
                                    <td valign="middle" style="text-align:center;">
                                       
                                        <div style="width:100%;border-bottom:1px solid black;height:30px;text-align:center;">
                                             <?php echo number_format((double)@$grand_total['amountGShare'],2); ?>
                                        </div>
                                    </td>
                                    <?php } ?>
                                    <td style="width: 35px;"></td>
                                </tr>
                                <tr class="signatories" style="margin-top:10px;border-top: 1px solid; ">
                                    <td colspan="100">
                                        <?php
                                            // $signatory3 = "";
                                            // $signatory3_position = "";
                                            // if(sizeof($signatories) > 0){
                                            //     foreach ($signatories as $k1 => $v1) {
                                            //         if($v1['signatory_no'] == "3" ){
                                            //             $signatory3 = $v1['signatory'];
                                            //             $signatory3_position = $v1['employee_id'];
                                            //         }
                                            //     }
                                            // }
                                            // $signatory2 = "";
                                            // $signatory2_position = "";
                                            // if(sizeof($signatories) > 0){
                                            //     foreach ($signatories as $k1 => $v1) {
                                            //         if($v1['signatory_no'] == "2" ){
                                            //             $signatory2 = $v1['signatory'];
                                            //             $signatory2_position = $v1['employee_id'];
                                            //         }
                                            //     }
                                            // }
                                            
                                        ?>

                                        <table style="width: 100%;text-align: left;">
                                        	<tr>
                                        		<td style="text-align: left;width:50%;" nowrap></td>
                                        		<td style="text-align: left;width:50%;" nowrap>
                                        			APPROVED
                                        			<br> BY AUTHORITY OF THE CHAIRMAN
                                        		</td>
                                        	</tr>
                                        	<tr style="text-align: left;" nowrap>
                                        		<td colspan="2">
                                        			<br>
                                        			<br>
                                        		</td>
                                        	</tr>
                                        	<tr style="text-align: left;" nowrap>
                                        		<td style="text-align: left;width:50%;" nowrap>
                                        			<b><?php //echo strtoupper(@$signatories_head[0]['signatory']); ?></b>
                                                    <br><?php //echo ucfirst(@$signatories_head[0]['employee_id']); ?>
                                        		</td>
                                        		<td style="text-align: left;width:50%;" nowrap>
                                        			<b><?php //echo strtoupper(@$signatory2); ?></b>
                                                    <br><?php //echo ucfirst(@$signatory2_position); ?>
                                        		</td>
                                        		
                                    		</tr>
                                    		<tr style="text-align: left;" nowrap>
                                        		<td colspan="2" style="text-align: left;width:50%;" nowrap>
                                        			<br>
                                        			<br>
                                        			CERTIFIED: Each employees whose name appears above has been paid the amount indicated his/her name.
                                        		</td>
                                        	</tr>
                                        	<tr style="text-align: left;" nowrap>
                                        		<td></td>
                                        		<td style="text-align:center;" nowrap="">
                                        			<div style="width:50%;align:middle;border-bottom:1px solid black;height:30px;text-align:right;margin-left:25%;">
			                                      
			                                        </div>
			                                        <br>
			                                        Disbursing Officer
                                        		</td>
                                        	</tr>
                                        </table>
                                        
                                    </td>
                                </tr>
                            </tfoot>
                            <?php 
                                endif; ?>
                        
                    <?php } ?>
                    </table>
                </div>
            </div> 
        </div>


    </div>
</div>
</div>