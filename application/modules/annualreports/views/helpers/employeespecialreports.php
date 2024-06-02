<?php
    $bonus_type = $payroll[0]['bonus_type'];
    $amount_label = "Amount";

    if($bonus_type == "Year End")
        $amount_label = "Year End";
    if($bonus_type == "Mid Year")
        $amount_label = "Mid Year Bonus";
    if($bonus_type == "PBB")
        $amount_label = "PERFORMANCE BASE BONUS";
    if($bonus_type == "CNA")
        $amount_label = "CNA INCENTIVE"; 
    if($bonus_type == "Clothing")
        $amount_label = "Uniform";
?>
<div class="row">
    <div class="col-md-12 text-right">
        <button id="printClearance" class="btn bg-green btn-fab btn-fab-mini">Print Preview <i class = "material-icons">print</i></button>
    </div>
</div>
<hr>
<div class="row" >
    <div class="col-md-12">
        <style type="text/css">
        </style>
        <div>
            <div id="clearance-div">
                <style type = 'text/css'>
                    @media print{
                        /*280mm 378mm
                          11in 15in*/
                        
                        html {
                            height: 0;
                        }
                        @page { 
                            size: US Std Fanfold;
							width:88%; 

                        }
                        body {
                           font-family:Calibri;
                           color: black;
                        }
                        table tr td{
                           font-size: 20px;
                        }
                        table{
                            border-collapse: collapse;
                            page-break-inside:always;
                            width:1000px;
                        }
                        .page-break{
                            page-break-before: always;
                            margin-top:10px;
                        }
                        .table1{
							width: 1000px !important;
						}
                        #signatoriesTable{
                            width:1000px;
                        }
                    }

                </style> 
                <?php
                    /*$payroll = array_merge($payroll,$payroll);
                    $payroll = array_merge($payroll,$payroll);
                    $payroll = array_merge($payroll,$payroll);
                    $payroll = array_merge($payroll,$payroll);
                    $payroll = array_merge($payroll,$payroll);*/
                    $total_per_page = 10;
                    $total_next_page = 10;
                    $percent_dec = .5;
                    $total_page = floor(sizeof($payroll)/$total_per_page) + 1;
                    if(( sizeof($payroll) < $total_next_page && sizeof($payroll) > floor($total_next_page*$percent_dec) ) || (sizeof($payroll) > $total_next_page && (sizeof($payroll)) - ($total_next_page * $total_page) > floor($total_next_page*.5) )){
                        $total_page = floor(sizeof($payroll)/$total_per_page) + 2;
                    }
                ?>
                
                <?php
                // var_dump(sizeof($payroll));die();

                $page_count = 1;
                $grand_total = array(
                    'salary'=>0.00,
                    'amount'=>0.00,
                    'cash_gift'=>0.00,
                    'in_kind'=>0.00,
                    'union_fees'=>0.00,
                    'net_pay'=>0.00
                );
                $last_row = 0;
                $count = 1;
                $page = 0;
                
                ?>
                
                    <?php 
                    while(sizeof($payroll) > 0){
                        $page_total = array(
                            'salary'=>0.00,
                            'amount'=>0.00,
                            'cash_gift'=>0.00,
                            'in_kind'=>0.00,
                            'union_fees'=>0.00,
                            'net_pay'=>0.00
                        );
                        $page++; 
                    ?>
                    <?php 
                        // $class = "page-break";
                        // if($count == 1)
                        //     $class = "";

                    ?>
				<div style="width:1000px;margin:auto;">
                    <div class="header-container page-break" >
					<td valign="top" style="width:33%;text-align:left" nowrap>Date/Time Printed/User <?php echo date('m/d/Y  h:i:sa'); ?>  <?php echo Helper::get('first_name') ?></td>
					<center>	
					<table  style="width:1000px;">
                            <thead>
								<tr>
								<td valign="top" style="width:33%;text-align:center" nowrap>
                                        <h4> 
                                            <b>GENERAL PAYROLL
                                            <br>NATIONAL WATER RESOURCES BOARD
                                            <br>8TH FLOOR, NIA BUILDING EDSA, QUEZON CITY
                                            <br>COMPUTATION OF <?php echo strtoupper($bonus_name[0]['name']);?>
                                            <br><?php echo ' FY '.$year; ?> 
							<!--                                             <br>of
                                            <br><?php echo strtoupper(@$payroll_grouping[0]['code']); ?> -->
                                            </b>
                                        </h4>
                                    </td>
								</tr>
                            </thead>
                        </table>

					</center>
                        <span><b>Project: 11.A.11</b></span>
                        <br>
                        <span><b>Division: <?php echo $division_name[0]['department_name']; ?></b></span>
                        <br>
						<span>
						We acknowledge receipt of cash shown opposite our name as full compensation for services rendered for the period covered.n.
					</span>
                    </div>
                        <table style="width: 1000px;border:1px solid black;" class="table1">
                            <tbody>
							<!-- <tr style="border-top: 1px solid black;height:10px;">
                                    <td colspan="5"></td>
                                </tr> -->
                                <tr class="" style="border:1px solid black;" style="font-weight:bold;">
                                    <td style="text-align:center;width:30px;border:1px solid black;" valign="top" >NO.</td>
                                    <td style="text-align:left;border:1px solid black;" valign="top" >Name of Employee<br> Position</td>
                                    <td style="text-align:center;border:1px solid black;" valign="middle" >Basic Salary</td>
                                    <td style="text-align:center;border:1px solid black;" valign="middle" ><?php echo $amount_label; ?></td>
                                    <td style="text-align:center;border:1px solid black;" valign="middle" >Remarks</td>
                                    <?php 
                                        if($bonus_type == "Year End") {
                                            echo '<td style="text-align:center;" valign="middle" nowrap>Cash Gift</td>';
                                            echo '<td style="text-align:center;" valign="middle" nowrap>Net Pay</td>';
                                        }
                                        if($bonus_type == "Clothing") {
                                            echo '<td style="text-align:center;" valign="middle" nowrap>In-Kind</td>';
                                            echo '<td style="text-align:center;" valign="middle" nowrap>Net Pay</td>';
                                        }
                                        if($bonus_type == "CNA") {
                                            echo '<td style="text-align:center;" valign="middle" nowrap>Less Union Fees</td>';
                                            echo '<td style="text-align:center;" valign="middle" nowrap>Net Pay</td>';
                                        }
                                    ?>
                                    
                                    <td style="text-align:center;" valign="middle" >Signature</td>
                                </tr>
                                <!-- <tr>
                                    <td colspan="5"><br></td>
                                </tr> -->
								<?php 
								foreach ($payroll as $k => $v) {
								?>
                                <tr style="height:20px;border:1px solid black;" class="">
                                    <td nowrap="" valign="top" style="border:1px solid black;">
                                       <?php echo $count; ?>  
                                    </td>
                                    <td nowrap="" style="text-align:left;width:300px;border:1px solid black;" valign="top" >
                                        <b><?php echo $this->Helper->decrypt($v['last_name'],$v['employee_id']) .", " .$this->Helper->decrypt($v['first_name'],$v['employee_id']) ." " . $this->Helper->decrypt($v['middle_name'],$v['employee_id']); ?></b>
                                        <br>
                                        <?php echo $v['position_name']; ?>
                                    </td>
                                    <td nowrap style="text-align:center;border:1px solid black;" valign="middle">
                                        <?php $grand_total['salary'] += $v['salary']; ?>
                                        <?php $page_total['salary'] += $v['salary']; ?>
                                        <?php echo number_format((double)@$v['salary'],2); ?>
                                    </td>
                                    <td nowrap style="text-align:center;border:1px solid black;" valign="middle">
                                        <?php $grand_total['amount'] += $v['amount']; ?>
                                        <?php $page_total['amount'] += $v['amount']; ?>
                                        <?php echo number_format((double)@$v['amount'],2); ?>
                                    </td>
                                    <?php if($bonus_type == "Year End") : ?>
                                        <td nowrap style="text-align:center;border:1px solid black;" valign="middle">
                                            <?php $grand_total['cash_gift'] += $v['cash_gift']; ?>
                                            <?php $page_total['cash_gift'] += $v['cash_gift']; ?>
                                            <?php echo number_format((double)@$v['cash_gift'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:center;border:1px solid black;" valign="middle">
                                            <?php $grand_total['net_pay'] += $v['cash_gift'] + $v['amount']; ?>
                                            <?php $page_total['net_pay'] += $v['cash_gift'] + $v['amount']; ?>
                                            <?php echo number_format((double)@$v['cash_gift'] + $v['amount'],2); ?>
                                        </td>
                                    <?php endif; ?>

                                    <?php if($bonus_type == "Clothing") : ?>
                                        <td nowrap style="text-align:center;border:1px solid black;" valign="middle">
                                            <?php $grand_total['in_kind'] += $v['in_kind']; ?>
                                            <?php $page_total['in_kind'] += $v['in_kind']; ?>
                                            <?php echo number_format((double)@$v['in_kind'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:center;border:1px solid black;" valign="middle">
                                            <?php $grand_total['net_pay'] += $v['amount'] - $v['in_kind']; ?>
                                            <?php $page_total['net_pay'] += $v['amount'] - $v['in_kind']; ?>
                                            <?php echo number_format((double)@$v['amount'] - $v['in_kind'],2); ?>
                                        </td>
                                    <?php endif; ?>

                                    <?php if($bonus_type == "CNA") : ?>
                                        <td nowrap style="text-align:center;border:1px solid black;" valign="middle">
                                            <?php $grand_total['union_fees'] += $v['union_fees']; ?>
                                            <?php $page_total['union_fees'] += $v['union_fees']; ?>
                                            <?php echo number_format((double)@$v['union_fees'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:center;border:1px solid black;" valign="middle">
                                            <?php $grand_total['net_pay'] += $v['amount'] - $v['union_fees']; ?>
                                            <?php $page_total['net_pay'] += $v['amount'] - $v['union_fees']; ?>
                                            <?php echo number_format((double)@$v['amount'] - $v['union_fees'],2); ?>
                                        </td>
                                    <?php endif; ?>                                    
                                    <td nowrap="" style="text-align:center;border:1px solid black;" valign="bottom">
                                        <div style="width:90%;border-bottom:1px solid black;height:12px;float:left;"></div>
                                        <?php echo "&nbsp;"; ?> 
                                    </td>
                                    <td nowrap="" style="text-align:center;border:1px solid black;" valign="bottom">
                                        <div style="width:90%;border-bottom:1px solid black;height:12px;float:left;"></div>
                                        <?php echo $count; ?> 
                                    </td>
                                </tr>
                                <!-- <tr style="height:10px;">
                                    <td colspan="4"></td>
                                </tr> -->
                                <?php
                                unset($payroll[$k]); 
                                $count++;
                                $last_row ++;
                                if($last_row == $total_per_page){
                                    $total_per_page = $total_next_page;
                                } 
                                if((($k+1)/$total_per_page) === (intval(($k+1)/$total_per_page)) || sizeof($payroll) == 0){ ?>
                                    <!-- <tr style="height:20px;">
                                        <td colspan="4"><br></td>
                                    </tr> -->
                                    <tr style="height:30px;text-align:center;font-weight:bold;border:1px solid black;" class="page_total">
                                        <td></td>
                                        <td style="text-align:center; border:1px solid black;" valign="bottom">PAGE TOTAL:</td>
                                        <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                            
                                            <?php echo number_format((double)@$page_total['salary'],2); ?>
                                            
                                        </td>
                                        <td valign="bottom" nowrap="" style="text-align:center;">
                                            
                                            <?php echo number_format((double)@$page_total['amount'],2); ?>
                                        </td>

                                        <?php if($bonus_type == "Year End") : ?>
                                            <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">   
                                                <?php echo number_format((double)@$page_total['cash_gift'],2); ?>
                                            </td>
                                            <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                                <?php echo number_format((double)@$page_total['net_pay'],2); ?>
                                            </td>
                                        <?php endif; ?>

                                        <?php if($bonus_type == "Clothing") : ?>
                                            <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">   
                                                <?php echo number_format((double)@$page_total['in_kind'],2); ?>
                                            </td>
                                            <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                                <?php echo number_format((double)@$page_total['net_pay'],2); ?>
                                            </td>
                                        <?php endif; ?>

                                        <?php if($bonus_type == "CNA") : ?>
                                            <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">   
                                                <?php echo number_format((double)@$page_total['union_fees'],2); ?>
                                            </td>
                                            <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                                <?php echo number_format((double)@$page_total['net_pay'],2); ?>
                                            </td>
                                        <?php endif; ?>   
                                        <td style="border:1px solid black;"></td>
                                        <td style="border:1px solid black;"></td>
                                    </tr>
                                    <!-- <tr style="height:20px;">
                                        <td colspan="4"><br></td>
                                    </tr> -->
                                <?php if(sizeof($payroll) == 0 &&  (( $count-1 <= $total_next_page && $count-1 <= floor($total_next_page*$percent_dec) ) || ($count-1 > $total_next_page && ($count-1) - ($total_next_page * $total_page) <= floor($total_next_page*.5) ) ) ): ?>
                                    <!-- <tr style="height:20px;border:1px solid black;">
                                        <td colspan="4"><br></td>
                                    </tr> -->
                                    <tr style="height:20px;text-align:center;font-weight:bold;border:1px solid black;" class="page_total">
                                        <td></td>
                                        <td style="text-align:center;border:1px solid black;" valign="bottom">GRAND TOTAL:</td>
                                        <td valign="bottom" nowrap="" style="text-align:center;">
                                            
                                            <?php echo number_format((double)@$grand_total['salary'],2); ?>
                                        </td>
                                        <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                            
                                            <?php echo number_format((double)@$grand_total['amount'],2); ?>
                                        </td>

                                        <?php if($bonus_type == "Year End") : ?>
                                            <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                                <?php echo number_format((double)@$grand_total['cash_gift'],2); ?>
                                            </td>
                                            <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                                <?php echo number_format((double)@$grand_total['net_pay'],2); ?>
                                            </td>
                                        <?php endif; ?>

                                        <?php if($bonus_type == "Clothing") : ?>
                                            <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                                <?php echo number_format((double)@$grand_total['in_kind'],2); ?>
                                            </td>
                                            <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                                <?php echo number_format((double)@$grand_total['net_pay'],2); ?>
                                            </td>
                                        <?php endif; ?>

                                        <?php if($bonus_type == "CNA") : ?>
                                            <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                                <?php echo number_format((double)@$grand_total['union_fees'],2); ?>
                                            </td>
                                            <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                                <?php echo number_format((double)@$grand_total['net_pay'],2); ?>
                                            </td>
                                        <?php endif; ?>   
                                        <td style="border:1px solid black;"></td>
                                        <td style="border:1px solid black;"></td>
                                    </tr>
                                    <!-- <tr style="height:20px;border:1px solid black;">
                                        <td colspan="6"><br></td>
                                    </tr>
                                    <tr style="border:1px solid black;">
                                        <td colspan="6"></td>
                                    </tr> -->
                               
                                <?php endif; ?>
                                </tbody>
								<?php 
                                    
									break;
								}
								 
							} ?> 
                        </table>

								<!-- <b>
									CERTIFICATION:
									<br>&emsp; This is to certify that the employees included in this payroll have rendered at least six(6) months of service including leave of absence with pay from January 1,<?php echo $year; ?> to December 31,<?php echo $year; ?> 
									<br>and are expected to render service for another months from the day he receives the allowance.
								</b>
							<br>
							<br> -->
						    <tr class="signatories" style="page-break-before:avoid;">
                                        <td colspan="4">
                                            <table style="width:1000px;" id="signatoriesTable">
                                             
                                                <tr style="border-top: 1px;height:100px;border:1px solid black;">
                                                    <td style="border:1px solid black;width:500px;" nowrap="" valign="top">
                                                        (A) CERTIFIED: Services duly rendered as 
                                                        <br>stated in their respective DTR.
                                                        <br><br><br>
                                                        <?php
                                                            $box_a = "";
                                                            $box_a_department = "";

                                                            if(sizeof($signatories_a) > 0){

                                                            if($signatories_a[0]["employee_name"] == "ARCHIE EDSEL C. ASUNCION "){

                                                                    $box_a = 'ATTY. '.$signatories_a[0]["employee_name"];
                                                                    $box_a_department = "OIC, Deputy Executive Director";

                                                            }else if($signatories_a[0]["employee_name"] == "JUAN Y. CORPUZ JR."){

                                                                    $box_a = 'ATTY. '.$signatories_a[0]["employee_name"];
                                                                    $box_a_department = "Chief, ".$signatories_a[0]["department"];

                                                            }else if($signatories_a[0]["employee_name"] == "ELOISA L. LEGASPI "){
                                                                    $box_a = $signatories_a[0]["employee_name"];
                                                                    $box_a_department = "Chief, ".$signatories_a[0]["department"];

                                                            }else if($signatories_a[0]["employee_name"] == "LUIS S. RONGAVILLA "){

                                                                    $box_a = $signatories_a[0]["employee_name"];
                                                                    $box_a_department = "Chief, ".$signatories_a[0]["department"];

                                                            }else if($signatories_a[0]["employee_name"] == "SUSAN P. ABAÑO "){

                                                                    $box_a = $signatories_a[0]["employee_name"];
                                                                    $box_a_department = "Chief, ".$signatories_a[0]["department"];
                                                                    
                                                            }else if($signatories_a[0]["employee_name"] == "EVELYN V. AYSON "){

                                                                $box_a = $signatories_a[0]["employee_name"];
                                                                $box_a_department = "OIC, ".$signatories_a[0]["department"];
                                                            }
                                                            else{

                                                                $box_a = $signatories_a[0]["employee_name"];
                                                                $box_a_department = $signatories_a[0]["department"];
                                                            }
                                                        }
                                                        ?>
                                                        <center>
                                                        <div  style="text-decoration: underline;">
                                                        
														<?php 
                                                        // if($division == 1 || $division == 2){
                                                        //     if(sizeof($sign2) > 0){
                                                        //         if($sign2[0]["employee_name"] == "SEVILLO D. DAVID JR."){ 
                                                        //             echo "Dr. ".$sign2[0]["employee_name"].", JR., CESO III"; 
                                                        //             }elseif($sign2[0]["employee_name"] == "ARCHIE EDSEL C. ASUNCION"){ 
                                                        //             echo "Atty. ".$sign2[0]["employee_name"]; }else{
                                                        //                 echo $sign2[0]["employee_name"];
                                                        //             }
                                                        //         }
                                                        // }else{
                                                        //     if(sizeof($sign) > 0){
                                                        //         if($sign[0]["employee_name"] == "SEVILLO D. DAVID JR."){ 
                                                        //             echo "Dr. ".$sign[0]["employee_name"].", JR., CESO III"; 
                                                        //             }elseif($sign[0]["employee_name"] == "ARCHIE EDSEL C. ASUNCION"){ 
                                                        //             echo "Atty. ".$sign[0]["employee_name"]; }else{
                                                        //                 echo $sign[0]["employee_name"];
                                                        //             }
                                                        //         }
                                                        // }
                                                           echo (sizeof($signatories_a) > 0) ? $box_a : ''; //name
														?>
														</div>
                                                        <!-- <?php
                                                            //if($division == 1 ){
                                                        ?>
                                                        <div align="center"><?php //if(isset($sign2[0]["employee_name"])){echo $sign2[0]["position_designation"] != "" || $sign2[0]["position_designation"] != null ? $sign2[0]["position_designation"] : $sign2[0]["position_title"];} //position ?></div>
                                                        <div align="center"><?php //if(isset($sign2[0]["employee_name"])){ echo "Executive Director's Office"; }?></div> 
                                                        <?php
                                                            //}else if($division == 2){
                                                        ?>
                                                         <div align="center"><?php //if(isset($sign2[0]["employee_name"])){echo $sign2[0]["position_designation"] != "" || $sign2[0]["position_designation"] != null ? $sign2[0]["position_designation"] : $sign2[0]["position_title"];} //position ?></div>
                                                         <div align="center"><?php //if(isset($sign2[0]["employee_name"])){ echo "Deputy Executive Director's Office"; }?></div>
                                                        <?php
                                                            //}else {
                                                        ?> -->
                                                        <div align="center"><?php if(sizeof($signatories_a) > 0){ echo $box_a_department; } //position ?></div>
                                                        <!-- <div align="center"><?php //if(isset($sign[0]["employee_name"])){echo $sign[0]["position_designation"] != "" || $sign[0]["position_designation"] != null ? $sign[0]["position_designation"] : $sign[0]["position_title"];} //position ?></div>
                                                        <div align="center"><?php //if(isset($sign[0]["employee_name"])){echo $sign[0]["division_designation"] != "" || $sign[0]["division_designation"] != null ? $sign[0]["division_designation"] : $sign[0]["department"];} //position ?></div> -->
                                                        <?php
                                                          //  }
                                                        ?>  
                                                    </center><br>
                                                    </td>
                                                    <!-- <td style="width:30%;"></td> -->
                                                    <td style="" nowrap="" valign="top">
                                                         APPROVED FOR PAYMENT:
                                                         <br><br><br><br>
                                                        <center>
                                                        <div  style="text-decoration: underline;" >
														<?php if($signatories[2]["employee_name"] == "SEVILLO D. DAVID JR."){ 
															echo "Dr. ".$signatories[2]["employee_name"].", JR., CESO III"; 
															}elseif($signatories[2]["employee_name"] == "ARCHIE EDSEL C. ASUNCION"){ 
															echo "Atty.".$signatories[2]["employee_name"]; }else{ 
															echo $signatories[2]["employee_name"];} //name ?></div>
                                                        <div align="center"><?php echo $signatories[2]["position_designation"] != "" || $signatories[2]["position_designation"] != null ? $signatories[2]["position_designation"] : $signatories[2]["position_title"]; //position ?></div>
                                                        <!-- <div align="center"><?php echo $signatories[2]["division_designation"] != "" || $signatories[2]["division_designation"] != null ? $signatories[2]["division_designation"] : $signatories[2]["department"]; //position ?></div> -->
                                                        </center><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                <tr style="border:1px solid black;width:600px;">
                                                    <td style="border:1px solid black;" nowrap="" valign="top">
                                                        (B) CERTIFIED: Supporting documents complete and
                                                        <br>proper; and cash available in the amount of P___________.
                                                        <br><br><br>
                                                        <center>
                                                        <div  style="text-decoration: underline;" align="center"><?php if($signatories[1]["employee_name"] == "SEVILLO D. DAVID"){ 
															echo "Dr. ".$signatories[1]["employee_name"].", JR., CESO III"; 
															}elseif($signatories[1]["employee_name"] == "ARCHIE EDSEL C. ASUNCION"){ 
															echo "Atty.".$signatories[1]["employee_name"]; }else{ 
															echo $signatories[1]["employee_name"];} //name ?></div>
                                                        <div align="center"><?php echo $signatories[1]["position_designation"] != "" || $signatories[1]["position_designation"] != null ? $signatories[1]["position_designation"] : $signatories[1]["position_title"]; //position ?></div>
                                                        <!-- <div align="center"><?php echo $signatories[1]["division_designation"] != "" || $signatories[1]["division_designation"] != null ? $signatories[1]["division_designation"] : $signatories[1]["department"]; //position ?></div> -->
                                                        </center>
                                                    </td>
                                                    <!-- <td style="width:30%;"></td> -->
                                                    <td style="" nowrap="" valign="top">
                                                        CERTIFIED: Each employee whose name appears above &nbsp;&nbsp;&nbsp;
                                                        <br>has been paid the amount indicating opposite his/her 
                                                        <br>name
                                                        <br><br>
                                                        <center>
                                                        <div  style="text-decoration: underline;" align="center"><?php if($signatories[3]["employee_name"] == "SEVILLO D. DAVID"){ 
															echo "Dr. ".$signatories[3]["employee_name"].", JR., CESO III"; 
															}elseif($signatories[3]["employee_name"] == "ARCHIE EDSEL C. ASUNCION"){ 
															echo "Atty.".$signatories[3]["employee_name"]; }else{ 
															echo $signatories[3]["employee_name"];} //name ?></div>
                                                        <div align="center"><?php echo $signatories[3]["position_designation"] != "" || $signatories[3]["position_designation"] != null ? $signatories[3]["position_designation"] : $signatories[3]["position_title"]; //position ?></div>
                                                        <!-- <div align="center"><?php echo $signatories[3]["division_designation"] != "" || $signatories[3]["division_designation"] != null ? $signatories[3]["division_designation"] : $signatories[3]["department"]; //position ?></div> -->
                                                        </center>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

					</div>
					<!-- -->
                    <!-- Grand Total -->
                    <?php if(sizeof($payroll) == 0 &&  (( $count-1 <= $total_next_page && $count-1 > floor($total_next_page*$percent_dec) ) || ($count-1 > $total_next_page && ($count-1) - ($total_next_page * $total_page) > floor($total_next_page*.5) ) ) ):
                        $page++;
                    ?>
                    <div class="header-container page-break" style="width:100%;">
                        <table style="width:100%;border-bottom:0px;">
                            <thead>
                                <tr>
                                    <td valign="top" style="width:33%;text-align:left" nowrap>Date/Time Printed/User <?php echo date('m/d/Y  h:i:sa'); ?>  <?php echo Helper::get('first_name') ?></td>
                                    <td valign="top" style="width:33%;text-align:center" nowrap>
                                        <h4>
                                            <b>GENERAL PAYROLL
                                            <br>NATIONAL WATER RESOURCES BOARD
                                            <br>8TH FLOOR, NIA BUILDING EDSA, QUEZON CITY
                                            <br><?php echo $year; ?> BONUS PAYROLL
                                            <br>of
                                            <br><?php echo strtoupper(@$payroll_grouping[0]['code']); ?></b>
                                        </h4>
                                    </td>
                                    <td valign="top" style="width:33%;text-align:right" nowrap><!--<label>Page No.: <?php //echo $page.' of '.$total_page; ?></label>--></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="100" nowrap>
                                        &emsp;We acknowledge receipt of cash shown opposite our name as full compensation for services rendered for the period covered.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                        <table>
                            <tbody>
                                <tr style="border-top: 1px solid black;height:12px;">
                                    <td colspan="4"></td>
                                </tr>
                                <tr class="" style="border:1px solid black;" style="font-weight:bold;">
                                    <td style="text-align:center;width:30px;border:1px solid black;" valign="top" nowrap>NO.</td>
                                    <td style="text-align:left;border:1px solid black;" valign="top" nowrap>Name of Employee<br> Position</td>
                                    <td style="text-align:center;border:1px solid black;" valign="middle" nowrap>Basic Salary</td>
                                    <td style="text-align:center;border:1px solid black;" valign="middle" nowrap><?php echo $amount_label; ?></td>
                                    <?php 
                                        if($bonus_type == "Year End") {
                                            echo '<td style="text-align:center;" valign="middle" nowrap>Cash Gift</td>';
                                            echo '<td style="text-align:center;" valign="middle" nowrap>Net Pay</td>';
                                        }
                                        if($bonus_type == "Clothing") {
                                            echo '<td style="text-align:center;" valign="middle" nowrap>In-Kind</td>';
                                            echo '<td style="text-align:center;" valign="middle" nowrap>Net Pay</td>';
                                        }
                                        if($bonus_type == "CNA") {
                                            echo '<td style="text-align:center;" valign="middle" nowrap>Less Union Fees</td>';
                                            echo '<td style="text-align:center;" valign="middle" nowrap>Net Pay</td>';
                                        }
                                    ?>
                                    
                                    <td style="text-align:center;" valign="middle" nowrap>Signature</td>
                                </tr>
                                <tr>
                                    <td colspan="4"><br></td>
                                </tr>
                                <tr style="height:20px;border-top: 1px solid black;">
                                    <td colspan="4"><br></td>
                                </tr>
                                <tr style="height:20px;text-align:center;font-weight:bold; border:1px solid black;" class="page_total">
                                    <td></td>
                                    <td style="text-align:center; border:1px solid black;" valign="bottom">GRAND TOTAL:</td>
                                    <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                        
                                        <?php echo number_format((double)@$grand_total['salary'],2); ?>
                                    </td>
                                    <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                        
                                        <?php echo number_format((double)@$grand_total['amount'],2); ?>
                                    </td>

                                    <?php if($bonus_type == "Year End") : ?>
                                        <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                            <?php echo number_format((double)@$grand_total['cash_gift'],2); ?>
                                        </td>
                                        <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                            <?php echo number_format((double)@$grand_total['net_pay'],2); ?>
                                        </td>
                                    <?php endif; ?>

                                    <?php if($bonus_type == "Clothing") : ?>
                                        <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                            <?php echo number_format((double)@$grand_total['in_kind'],2); ?>
                                        </td>
                                        <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                            <?php echo number_format((double)@$grand_total['net_pay'],2); ?>
                                        </td>
                                    <?php endif; ?>

                                    <?php if($bonus_type == "CNA") : ?>
                                        <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                            <?php echo number_format((double)@$grand_total['union_fees'],2); ?>
                                        </td>
                                        <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                            <?php echo number_format((double)@$grand_total['net_pay'],2); ?>
                                        </td>
                                    <?php endif; ?>   
                                    
                                </tr>
                                <tr style="height:20px;border-bottom:1px solid black;">
                                    <td colspan="4"><br></td>
                                </tr>
                                <tr style="border-bottom: 1px solid black;height:5px;">
                                    <td colspan="4"></td>
                                </tr>
                                <tr class="signatories" style="page-break-before:avoid;">
                                    <td colspan="4">
                                        
                                    <table style="width:100%" class="signatureTable">
                                             
                                                <tr style="border-top: 1px;height:100px;border:1px solid black;">
                                                    <td style="padding-left:20px;" nowrap="" valign="top">
                                                        (A) CERTIFIED: Services duly rendered as stated in their respective DTR.
                                                        <br><br><br>
                                                        <?php
                                                            $box_a = "";
                                                            $box_a_department = "";

                                                            if(sizeof($signatories_a) > 0){

                                                            if($signatories_a[0]["employee_name"] == "ARCHIE EDSEL C. ASUNCION "){

                                                                    $box_a = 'ATTY. '.$signatories_a[0]["employee_name"];
                                                                    $box_a_department = "OIC, Deputy Executive Director";

                                                            }else if($signatories_a[0]["employee_name"] == "JUAN Y. CORPUZ JR."){

                                                                    $box_a = 'ATTY. '.$signatories_a[0]["employee_name"];
                                                                    $box_a_department = "Chief, ".$signatories_a[0]["department"];

                                                            }else if($signatories_a[0]["employee_name"] == "ELOISA L. LEGASPI "){
                                                                    $box_a = $signatories_a[0]["employee_name"];
                                                                    $box_a_department = "Chief, ".$signatories_a[0]["department"];

                                                            }else if($signatories_a[0]["employee_name"] == "LUIS S. RONGAVILLA "){

                                                                    $box_a = $signatories_a[0]["employee_name"];
                                                                    $box_a_department = "Chief, ".$signatories_a[0]["department"];

                                                            }else if($signatories_a[0]["employee_name"] == "SUSAN P. ABAÑO "){

                                                                    $box_a = $signatories_a[0]["employee_name"];
                                                                    $box_a_department = "Chief, ".$signatories_a[0]["department"];
                                                                    
                                                            }else if($signatories_a[0]["employee_name"] == "EVELYN V. AYSON "){

                                                                $box_a = $signatories_a[0]["employee_name"];
                                                                $box_a_department = "OIC, ".$signatories_a[0]["department"];
                                                            }
                                                            else{

                                                                $box_a = $signatories_a[0]["employee_name"];
                                                                $box_a_department = $signatories_a[0]["department"];
                                                            }
                                                        }
                                                        ?>
                                                        <center>
                                                        <div  style="text-decoration: underline;">
                                                        
														<?php 
                                                        // if($division == 1 || $division == 2){
                                                        //     if(sizeof($sign2) > 0){
                                                        //         if($sign2[0]["employee_name"] == "SEVILLO D. DAVID JR."){ 
                                                        //             echo "Dr. ".$sign2[0]["employee_name"].", JR., CESO III"; 
                                                        //             }elseif($sign2[0]["employee_name"] == "ARCHIE EDSEL C. ASUNCION"){ 
                                                        //             echo "Atty. ".$sign2[0]["employee_name"]; }else{
                                                        //                 echo $sign2[0]["employee_name"];
                                                        //             }
                                                        //         }
                                                        // }else{
                                                        //     if(sizeof($sign) > 0){
                                                        //         if($sign[0]["employee_name"] == "SEVILLO D. DAVID JR."){ 
                                                        //             echo "Dr. ".$sign[0]["employee_name"].", JR., CESO III"; 
                                                        //             }elseif($sign[0]["employee_name"] == "ARCHIE EDSEL C. ASUNCION"){ 
                                                        //             echo "Atty. ".$sign[0]["employee_name"]; }else{
                                                        //                 echo $sign[0]["employee_name"];
                                                        //             }
                                                        //         }
                                                        // }
                                                           echo (sizeof($signatories_a) > 0) ? $box_a : ''; //name
														?>
														</div>
                                                        <!-- <?php
                                                            //if($division == 1 ){
                                                        ?>
                                                        <div align="center"><?php //if(isset($sign2[0]["employee_name"])){echo $sign2[0]["position_designation"] != "" || $sign2[0]["position_designation"] != null ? $sign2[0]["position_designation"] : $sign2[0]["position_title"];} //position ?></div>
                                                        <div align="center"><?php //if(isset($sign2[0]["employee_name"])){ echo "Executive Director's Office"; }?></div> 
                                                        <?php
                                                            //}else if($division == 2){
                                                        ?>
                                                         <div align="center"><?php //if(isset($sign2[0]["employee_name"])){echo $sign2[0]["position_designation"] != "" || $sign2[0]["position_designation"] != null ? $sign2[0]["position_designation"] : $sign2[0]["position_title"];} //position ?></div>
                                                         <div align="center"><?php //if(isset($sign2[0]["employee_name"])){ echo "Deputy Executive Director's Office"; }?></div>
                                                        <?php
                                                            //}else {
                                                        ?> -->
                                                        <div align="center"><?php if(sizeof($signatories_a) > 0){ echo $box_a_department; } //position ?></div>
                                                        <!-- <div align="center"><?php //if(isset($sign[0]["employee_name"])){echo $sign[0]["position_designation"] != "" || $sign[0]["position_designation"] != null ? $sign[0]["position_designation"] : $sign[0]["position_title"];} //position ?></div>
                                                        <div align="center"><?php //if(isset($sign[0]["employee_name"])){echo $sign[0]["division_designation"] != "" || $sign[0]["division_designation"] != null ? $sign[0]["division_designation"] : $sign[0]["department"];} //position ?></div> -->
                                                        <?php
                                                          //  }
                                                        ?>  
                                                    </center><br>
                                                    </td>
                                                    <td style="width:30%;"></td>
                                                    <td style="padding-left:20px;width:35%;" nowrap="" valign="top">
                                                         APPROVED FOR PAYMENT:
                                                         <br><br><br><br>
                                                        <center>
                                                        <div  style="text-decoration: underline;" >
														<?php if($signatories[2]["employee_name"] == "SEVILLO D. DAVID JR."){ 
															echo "Dr. ".$signatories[2]["employee_name"].", JR., CESO III"; 
															}elseif($signatories[2]["employee_name"] == "ARCHIE EDSEL C. ASUNCION"){ 
															echo "Atty.".$signatories[2]["employee_name"]; }else{ 
															echo $signatories[2]["employee_name"];} //name ?></div>
                                                        <div align="center"><?php echo $signatories[2]["position_designation"] != "" || $signatories[2]["position_designation"] != null ? $signatories[2]["position_designation"] : $signatories[2]["position_title"]; //position ?></div>
                                                        <!-- <div align="center"><?php echo $signatories[2]["division_designation"] != "" || $signatories[2]["division_designation"] != null ? $signatories[2]["division_designation"] : $signatories[2]["department"]; //position ?></div> -->
                                                        </center><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                <tr style="border:1px solid black;">
                                                    <td style="padding-left:20px;" nowrap="" valign="top">
                                                        (B) CERTIFIED: Supporting documents complete and
                                                        <br>proper; and cash available in the amount of P___________.
                                                        <br><br><br>
                                                        <center>
                                                        <div  style="text-decoration: underline;" align="center"><?php if($signatories[1]["employee_name"] == "SEVILLO D. DAVID"){ 
															echo "Dr. ".$signatories[1]["employee_name"].", JR., CESO III"; 
															}elseif($signatories[1]["employee_name"] == "ARCHIE EDSEL C. ASUNCION"){ 
															echo "Atty.".$signatories[1]["employee_name"]; }else{ 
															echo $signatories[1]["employee_name"];} //name ?></div>
                                                        <div align="center"><?php echo $signatories[1]["position_designation"] != "" || $signatories[1]["position_designation"] != null ? $signatories[1]["position_designation"] : $signatories[1]["position_title"]; //position ?></div>
                                                        <!-- <div align="center"><?php echo $signatories[1]["division_designation"] != "" || $signatories[1]["division_designation"] != null ? $signatories[1]["division_designation"] : $signatories[1]["department"]; //position ?></div> -->
                                                        </center>
                                                    </td>
                                                    <td style="width:30%;"></td>
                                                    <td style="padding-left:20px;" nowrap="" valign="top">
                                                        CERTIFIED: Each employee whose name appears above has 
                                                        <br>been paid the amount indicating opposite his/her name
                                                        <br><br>
                                                        <center>
                                                        <div  style="text-decoration: underline;" align="center"><?php if($signatories[3]["employee_name"] == "SEVILLO D. DAVID"){ 
															echo "Dr. ".$signatories[3]["employee_name"].", JR., CESO III"; 
															}elseif($signatories[3]["employee_name"] == "ARCHIE EDSEL C. ASUNCION"){ 
															echo "Atty.".$signatories[3]["employee_name"]; }else{ 
															echo $signatories[3]["employee_name"];} //name ?></div>
                                                        <div align="center"><?php echo $signatories[3]["position_designation"] != "" || $signatories[3]["position_designation"] != null ? $signatories[3]["position_designation"] : $signatories[3]["position_title"]; //position ?></div>
                                                        <!-- <div align="center"><?php echo $signatories[3]["division_designation"] != "" || $signatories[3]["division_designation"] != null ? $signatories[3]["division_designation"] : $signatories[3]["department"]; //position ?></div> -->
                                                        </center>
                                                    </td>
                                                </tr>
                                            </table>
                                    </td>
                                </tr>
                            </tbody>
                    <?php 
                        endif; ?>
                        </table>
                    <?php } ?>
            </div>
        </div>
    </div>
</div>
<hr>
<!-- <div class="row">
    <div class="col-md-12 text-right">
        <button id="printClearance" class="btn bg-green btn-fab btn-fab-mini">Print Preview <i class = "material-icons">print</i></button>
    </div>
</div> -->
