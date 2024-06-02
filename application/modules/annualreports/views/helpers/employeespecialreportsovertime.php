<?php
//var_dump($Overtime);
?>
<div class="row">
    <div class="col-md-12 text-right">
        <button id="printClearance" class="btn bg-green btn-fab btn-fab-mini">Print Preview <i class = "material-icons">print</i></button>
    </div>
</div>
<hr>
<div class="row">
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
                    /*$Overtime = array_merge($Overtime,$Overtime);
                    $Overtime = array_merge($Overtime,$Overtime);
                    $Overtime = array_merge($Overtime,$Overtime);
                    $Overtime = array_merge($Overtime,$Overtime);
                    $Overtime = array_merge($Overtime,$Overtime);*/
                    $total_per_page = 10;
                    $total_next_page = 10;
                    $percent_dec = .5;
                    $total_page = floor(sizeof($Overtime)/$total_per_page) + 1;
                    if(( sizeof($Overtime) < $total_next_page && sizeof($Overtime) > floor($total_next_page*$percent_dec) ) || (sizeof($Overtime) > $total_next_page && (sizeof($Overtime)) - ($total_next_page * $total_page) > floor($total_next_page*.5) )){
                        $total_page = floor(sizeof($Overtime)/$total_per_page) + 2;
                    }
                ?>
                
                <?php
                // var_dump(sizeof($Overtime));die();

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
                    while(sizeof($Overtime) > 0){
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

                        //month in value
                            $month_val  = $month;
                        
                            $dbj   = DateTime::createFromFormat('!m', $month_val);

                            $mName = $dbj->format('F');
                            
                            $monthDate = array();
                            for($x = 0 ; sizeof($monthRange) > $x ; $x++){
                                array_push($monthDate, date('d', strtotime($monthRange[$x]['transaction_date'])));
                            }
                            //var_dump(reset($monthDate));
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
                                            <br>COMPUTATION OF OVERTIME PAY
                                            <br><?php echo 'FROM '.reset($monthDate)."-".end($monthDate).", ".$year ?> 
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
						WE HEREBY ACKNOWLEDGE to have received of <b>NATIONAL WATER RESOURCES BOARD  <?php echo strtoupper(@$payroll_grouping[0]['code']); ?> <?php echo strtoupper(@$pay_basis); ?></b> the sum therein specified opposite our respective names, being in full compensation for our services rendered for the period stated.
					</span>
                    </div>
                        <table style="width: 1000px;border:1px solid black;" class="table1">
                            <tbody>
							<!-- <tr style="border-top: 1px solid black;height:10px;">
                                    <td colspan="5"></td>
                                </tr> -->
                                <tr class="" style="border:1px solid black;" style="font-weight:bold;">
                                    <td style="text-align:center;width:30px;border:1px solid black;" valign="top" rowspan="3">NO.</td>
                                    <td style="text-align:center;width:30px;border:1px solid black;" valign="top" rowspan="3">EMP#</td>
                                    <td style="text-align:center;border:1px solid black;" valign="top" rowspan="3">NAME<br> (DESIGNATION)</td>
                                    <td style="text-align:center;border:1px solid black;" valign="middle" >MONTH</td>
                                    <td style="text-align:center;border:1px solid black;" valign="middle" rowspan="3">W/ TAX</td>
                                    <td style="text-align:center;border:1px solid black;" valign="middle" rowspan="3">TOTAL<br>AMOUNT</td>
                                    <td style="text-align:center;border:1px solid black;" valign="middle" rowspan="3">REMARKS</td>
                                    <td style="text-align:center;border:1px solid black;" valign="middle" rowspan="3">RECEIVE BY</td>
                                    
                                </tr>
                                <tr>
                                    <td style="text-align:center;border:1px solid black;"><?php echo $mName; ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;border:1px solid black;"><?php echo reset($monthDate)."-".end($monthDate).", ".$year; ?></td>
                                </tr>
								<?php 
                                   

								foreach ($Overtime as $k => $v) {
                                    
                                    $overtimeTotalAmount = $totalNumber[$v['employee_id']];
                                    //var_dump($overtimeTotalAmount);
								?>
                                <tr style="height:20px;border:1px solid black;" class="">
                                    <td nowrap="" valign="top">
                                       <?php echo $count; ?>  
                                    </td>
                                    <td valign="top" style="text-align:center;width:100px;border:1px solid black;"><?php echo $v['emp_id']; ?></td>
                                    <td nowrap="" style="text-align:left;width:300px;border:1px solid black;" valign="top" >
                                        <b><?php echo $v['employee_name'];?></b>
                                        <br>
                                        <?php echo $v['position_name']; ?>
                                    </td>
                                   
                                    <td nowrap style="text-align:center;border:1px solid black;" valign="middle">
                                        <?php $grand_total['amount'] += $overtimeTotalAmount; ?>
                                        <?php $page_total['amount'] += $overtimeTotalAmount; ?>
                                        <?php echo number_format((double)@$overtimeTotalAmount,2); ?>
                                    </td>                                
                                    <td style="text-align:center;border:1px solid black;">-</td>
                                    <td style="text-align:center;border:1px solid black;"><?php echo number_format((double)@$overtimeTotalAmount,2); ?></td>
                                     <td nowrap="" style="text-align:center;border:1px solid black;">
                                        <!-- <div style="width:90%;border-bottom:1px solid black;height:12px;float:left;"></div> -->
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
                                unset($Overtime[$k]); 
                                $count++;
                                $last_row ++;
                                if($last_row == $total_per_page){
                                    $total_per_page = $total_next_page;
                                } 
                                if((($k+1)/$total_per_page) === (intval(($k+1)/$total_per_page)) || sizeof($Overtime) == 0){ ?>
                                    <!-- <tr style="height:20px;">
                                        <td colspan="4"><br></td>
                                    </tr> -->
                                    <tr style="height:30px;text-align:center;font-weight:bold;border:1px solid black;" class="page_total">
                                        <td style="border:1px solid black;"></td>
                                      
                                        <td style="text-align:centerborder:1px solid black;" valign="bottom">PAGE TOTAL:</td>
                                        <td style="text-align:center;border:1px solid black;" ></td>
                                        <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                            
                                            <?php echo number_format((double)@$page_total['amount'],2); ?>
                                            
                                        </td>
                                        <td style="text-align:center;border:1px solid black;" ></td>
                                        <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                            
                                            <?php echo number_format((double)@$page_total['amount'],2); ?>
                                        </td> 
                                        <td colspan="4" style="border:1px solid black;"></td>
                                    </tr>
                                   
                                <?php if(sizeof($Overtime) == 0 &&  (( $count-1 <= $total_next_page && $count-1 <= floor($total_next_page*$percent_dec) ) || ($count-1 > $total_next_page && ($count-1) - ($total_next_page * $total_page) <= floor($total_next_page*.5) ) ) ): ?>
                                    
                                    <tr style="height:20px;text-align:center;font-weight:bold;" class="page_total">
                                        <td style="border:1px solid black;"></td>
                                        <td style="text-align:center;border:1px solid black;" valign="bottom">GRAND TOTAL:</td>
                                        <td style="text-align:center;border:1px solid black;" ></td>
                                        <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black">
                                            
                                            <?php echo number_format((double)@$grand_total['amount'],2); ?>
                                        </td>
                                        <td style="text-align:center;border:1px solid black;" ></td>
                                        <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                            
                                            <?php echo number_format((double)@$grand_total['amount'],2); ?>
                                        </td>
 
                                        <td colspan="4" style="border:1px solid black;"></td>
                                    </tr>
                                    <!-- <tr style="height:20px;">
                                        <td colspan="6"><br></td>
                                    </tr>
                                    <tr style="height:5px;">
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
								</b> -->

						<tr class="signatories" style="page-break-before:avoid;">
                                        <td colspan="4">
                                            <table style="width:1000px;">
                                             
                                                <tr style="border-top: 1px;height:100px;border:1px solid black;">
                                                    <td style="border:1px solid black;" nowrap="" valign="top">
                                                        (A) CERTIFIED: Services duly rendered as 
                                                        <br>stated in their respective DTR.
                                                        <br><br><br>
                                                        <?php
                                                        $box_a = "";
                                                        $box_a_department = "";

                                                        if(sizeof($signatories_a[0]) > 0){

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
                                                        <div  style="text-decoration: underline;" align="center"><?php echo $box_a; //name ?></div>
                                                        <div align="center"><?php if(sizeof($signatories_a[0]) > 0){ echo $box_a_department; }  //position ?></div>
                                                        <!-- <div align="center"><?php //echo $signatories[0]["division_designation"] != "" || $signatories[0]["division_designation"] != null ? $signatories[0]["division_designation"] : $signatories[0]["department"]; //position ?></div> -->
                                                        </center><br>
                                                    </td>
                                                    <!-- <td style="width:30%;"></td> -->
                                                    <td  nowrap="" valign="top">
                                                      APPROVED FOR PAYMENT:
                                                      <br><br><br><br>
                                                        <center>
                                                        <div  style="text-decoration: underline;" >
														<?php if($signatories[2]["employee_name"] == "SEVILLO D. DAVID"){ 
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
                                                    <td nowrap="" valign="top">
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
                    <?php if(sizeof($Overtime) == 0 &&  (( $count-1 <= $total_next_page && $count-1 > floor($total_next_page*$percent_dec) ) || ($count-1 > $total_next_page && ($count-1) - ($total_next_page * $total_page) > floor($total_next_page*.5) ) ) ):
                        $page++;
                    ?>
                    <div class="header-container page-break" style="width:1000px !important;margin:auto;">
                    <center>	
					<table  style="width:1000px;">
                            <thead>
								<tr>
								<td valign="top" style="width:33%;text-align:center" nowrap>
                                        <h4>
                                        <b>GENERAL PAYROLL
                                            <br>NATIONAL WATER RESOURCES BOARD
                                            <br>8TH FLOOR, NIA BUILDING EDSA, QUEZON CITY
                                            <br>COMPUTATION OF OVERTIME PAY
                                            <br><?php echo 'FROM '.reset($monthDate)."-".end($monthDate).", ".$year ?> 
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
						WE HEREBY ACKNOWLEDGE to have received of <b>NATIONAL WATER RESOURCES BOARD  <?php echo strtoupper(@$payroll_grouping[0]['code']); ?> <?php echo strtoupper(@$pay_basis); ?></b> the sum therein specified opposite our respective names, being in full compensation for our services rendered for the period stated.
					</span>
                    
                    </div>
                        <table style="width: 1000px;border:1px solid black; margin:auto;" >
                            <tbody>
                                <!-- <tr style="border-top: 1px solid black;height:12px;">
                                    <td colspan="4"></td>
                                </tr> -->
                                <tr class="" style="border:1px solid black;" style="font-weight:bold;">
                                <td style="text-align:center;width:30px;border:1px solid black;" valign="top" >NO.</td>
                                    <td style="text-align:center;width:30px;border:1px solid black;" valign="top">EMP#</td>
                                    <td style="text-align:center;border:1px solid black;" valign="top" rowspan="1">NAME<br> (DESIGNATION)</td>
                                    <td style="text-align:center;border:1px solid black;" valign="middle" >MONTH</td>
                                    <td style="text-align:center;border:1px solid black;" valign="middle" >W/ TAX</td>
                                    <td style="text-align:center;border:1px solid black;" valign="middle" >TOTAL<br>AMOUNT</td>
                                    <td style="text-align:center;border:1px solid black;" valign="middle">RECEIVE BY</td>
                                </tr>
                                <!-- <tr>
                                    <td colspan="4"><br></td>
                                </tr> -->
                                <!-- <tr style="height:20px;border:1px solid black;">
                                    <td colspan="4"><br></td>
                                </tr> -->
                                <tr style="height:20px;text-align:center;font-weight:bold;border:1px solid black;" class="page_total">
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:center" valign="bottom">GRAND TOTAL:</td>
                                    
                                    <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                        
                                        <?php echo number_format((double)@$grand_total['salary'],2); ?>
                                    </td>
                                    <td></td>
                                    <td valign="bottom" nowrap="" style="text-align:center;border:1px solid black;">
                                        
                                        <?php echo number_format((double)@$grand_total['amount'],2); ?>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr style="height:20px;border-bottom:1px solid black;">
                                    <td colspan="4"><br></td>
                                </tr>
                                <tr style="border-bottom: 1px solid black;height:5px;">
                                    <td colspan="4"></td>
                                </tr>
                            
                            </tbody>
                            <tr class="signatories" style="page-break-before:avoid;">
                                        <td colspan="7">
                                            <table style="width:1000px;">
                                             
                                                <tr style="border-top: 1px;height:100px;border:1px solid black;">
                                                    <td style="border:1px solid black;" nowrap="" valign="top">
                                                        (A) CERTIFIED: Services duly rendered as 
                                                        <br>stated in their respective DTR.
                                                        <br><br><br>
                                                        <?php
                                                        $box_a = "";
                                                        $box_a_department = "";

                                                        if(sizeof($signatories_a[0]) > 0){

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
                                                        <div  style="text-decoration: underline;" align="center"><?php echo $box_a; //name ?></div>
                                                        <div align="center"><?php if(sizeof($signatories_a[0]) > 0){ echo $box_a_department; }  //position ?></div>
                                                        <!-- <div align="center"><?php //echo $signatories[0]["division_designation"] != "" || $signatories[0]["division_designation"] != null ? $signatories[0]["division_designation"] : $signatories[0]["department"]; //position ?></div> -->
                                                        </center><br>
                                                    </td>
                                                    <!-- <td style="width:30%;"></td> -->
                                                    <td  nowrap="" valign="top">
                                                      APPROVED FOR PAYMENT:
                                                      <br><br><br><br>
                                                        <center>
                                                        <div  style="text-decoration: underline;" >
														<?php if($signatories[2]["employee_name"] == "SEVILLO D. DAVID"){ 
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
                                                    <td nowrap="" valign="top">
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
