<?php //var_dump($otherEarnings);die(); ?>

<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <style>
        #viewProcessPayroll input { 
            text-align: right; 
        }
        #viewProcessPayroll fieldset {
            border: solid 1px #DDD !important;
            padding: 0 10px 10px 10px;
            border-bottom: none;
        }

        #viewProcessPayroll legend {
            width: auto !important;
            border: none;
            font-size: 14px;
            font-weight:bold;
            color:#0073b7;
            font-style: italic;
        }
        .popover{
            width: 700px; /* Max Width of the popover (depending on the container!) */
        }
    </style>
    <div class="form-elements-container">
    	<input type="hidden" class="id" name="id" value="">
        <input type="hidden" class="employee_id" name="employee_id" value="">
        <input type="hidden" class="payroll_period_id" name="payroll_period_id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
        <form class="form-horizontal">
            <div class="row">
                <div class="col-md-5">
                    
                </div>
                <div class="col-md-5">
                    
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="email_address_2">Rate</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5 text-right">
                            <div class="form-group">
                                <div class="form-line">

                                    <!-- <?php
                                        if (isset($payrollData) && count($payrollData) > 0) {
                                            var_dump($payrollData);
                                        }
                                    ?>  -->

                                    <input type="text"  value=".00" id="salary" class="salary form-control" readonly> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="email_address_2">Daily Rate</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5 text-right">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text"  value=".00" id="day_rate" class="day_rate form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="email_address_2">Basic Pay</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5 text-right">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text"  value=".00" id="salary" class="salary form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="email_address_2">Hourly Rate</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5 text-right">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text"  value=".00" id="hr_rate" class="hr_rate form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="email_address_2">Minute Rate</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5 text-right">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text"  value=".00" id="min_rate" class="min_rate form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="email_address_2">No. of Days</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5 text-right">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text"  value=".00" id="no_of_days" class="no_of_days form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <fieldset>
                        <legend>Allowances:</legend>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-8 col-xs-9 form-control-label">
                                <label for="email_address_2">PERA Allowance</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-4 col-xs-3 text-right">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  value="" id="pera_amt" class="pera_amt form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-8 col-xs-9 form-control-label">
                                <label for="email_address_2">Rep. Allowance</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-4 col-xs-3 text-right">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  value="" id="rep_allowance" class="rep_allowance form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-8 col-xs-9 form-control-label">
                                <label for="email_address_2">Transpo. Allowance</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-4 col-xs-3 text-right">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  value="" id="transpo_allowance" class="transpo_allowance form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                        if(isset($allowances) && sizeof($allowances) > 0): ?>
                        <?php
                            foreach ($allowances as $index => $value) { ?>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-9 form-control-label">
                                        <label for="email_address_2"><?php echo $value['allowance_name']; ?></label>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-4 col-xs-3 text-right">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text"  value="<?php echo number_format($value['amount'],2); ?>" id="amount" class=" form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        endif; ?>
                        
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-center">
                                (Non-tax)
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                            <button class="btn btn-info waves-effect form control" type="button" data-toggle="popover" title="Other Benefits" data-html="true" data-content="
                            <?php echo '<table style=width:100%;>';
                                if(isset($otherBenefits) && sizeof($otherBenefits) > 0){
                                    foreach ($otherBenefits as $k3 => $v3) {
                                        echo    '<tr>'
                                                    .'<td>'.$v3['description'].'</td>'
                                                    .'<td style=text-align:right>'.$v3['amount'].'</td>'
                                                .'</tr>'; 
                                    }
                                } else echo '<center><label class=text-danger>No data available.</label></center>';
                                echo '</table>'; 
                            ?>" data-placement="top">Other Benefits</button>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text"  value=".00" id="total_other_benefit_amt" class="total_other_benefit_amt form-control" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                            <button class="btn btn-info waves-effect form control" type="button" data-toggle="popover" title="Other Earnings" data-html="true" data-content="
                            <?php echo '<table style=width:100%;>';
                                if(isset($otherEarnings) && sizeof($otherEarnings) > 0){
                                    foreach ($otherEarnings as $k3 => $v3) {
                                        echo    '<tr>'
                                                    .'<td>'.$v3['description'].'</td>'
                                                    .'<td style=text-align:right>'.$v3['amount'].'</td>'
                                                .'</tr>'; 
                                    }
                                } else echo '<center><label class=text-danger>No data available.</label></center>';
                                echo '</table>'; 
                            ?>" data-placement="top">Other Earnings</button>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text"  value=".00" id="total_other_earning_amt" class="total_other_earning_amt form-control" readonly>
                                </div>
                            </div>
                        </div>
                        
                        
                    </fieldset>
                </div>
            </div>
            <?php
            // print_r('<pre>');
            // print_r($datas['data'][0]);
            //print_r($payrollData['data'][0]);
            $total = 0;
             
            ?>
            <div class="row">
                <div class="col-md-6">
                    <fieldset>
                        <legend>Deductions:</legend>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                <label for="email_address_2">Withholding Tax</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  value="<?php                                                                                        
                                            $total = $wh_tax_amt = $payrollData['data'][0]['wh_tax_amt'];  echo  number_format($wh_tax_amt,2);
                                        ?>" id="" class=" form-control" readonly>                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                <label for="email_address_2">SSS/ GSIS</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" value="<?php
                                       
                                            $total += $sss_gsis_amt = $payrollData['data'][0]['sss_gsis_amt'];  echo  number_format($sss_gsis_amt,2);
                                                                         
                                         ?>" id="" class=" form-control" readonly>                                
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                <label for="email_address_2">Philhealth</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                <div class="form-group">
                                    <div class="form-line">
                                        <!-- <input type="text"  value="<?php //echo ((isset($payrollinfo['data'][0]['philhealth_cos']) && $payrollinfo['data'][0]['philhealth_cos'] > 0 ) ? $payrollinfo['data'][0]['philhealth_cos'] : "0.00" );?>" id="" class=" form-control" readonly>
                                        <?php //$total += ((isset($payrollinfo['data'][0]['philhealth_cos']) && $payrollinfo['data'][0]['philhealth_cos'] > 0 ) ? $payrollinfo['data'][0]['philhealth_cos'] : 0.00 ); ?> -->
                                        <input type="text" value="<?php $total += $philhealth_amt =  $payrollData['data'][0]['philhealth_amt']; echo number_format($philhealth_amt,2); ?>" id="" class=" form-control" readonly>
                                         <?php  ?>
                                    
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                <label for="email_address_2">Pagibig</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" value="<?php $total += $pagibig_amt =  $payrollData['data'][0]['pagibig_amt']; echo number_format($pagibig_amt,2); ?>" id="" class=" form-control" readonly>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                <label for="email_address_2">NWRBEA Membership</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" value="<?php $total += $union_dues_amt =  $payrollData['data'][0]['union_dues_amt'];  echo number_format($union_dues_amt,2); ?>" id="" class=" form-control" readonly>
                                        <?php ((isset($datas['data'][0]['union_dues_amt']) && $datas['data'][0]['union_dues_amt'] > 0 ) ? $datas['data'][0]['union_dues_amt'] : 0.00 ); ?>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                <label for="email_address_2">MP2 Contribution</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                <div class="form-group">
                                    <div class="form-line">
                                    <input type="text" value="
                                        <?php 
                                        if (isset($payrollData['data'][0]['mp2_contribution_amt'])) {
                                            $total += $mp2_contribution_amt =$payrollData['data'][0]['mp2_contribution_amt'];
                                            echo number_format($mp2_contribution_amt, 2);
                                        } else {
                                            echo '0.00'; // or any default value you want to display
                                        }
                                        ?>" 
                                        id="" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php $total_loan_deduct = 0; ?>
                            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                <button class="btn btn-info waves-effect form control" type="button" 
                                data-toggle="popover" title="Loan Deductions" data-html="true" 
                                data-content="<?php               
                                echo    '<table style=width:100%;>';
                                if(isset($loanDeductions) && sizeof($loanDeductions) > 0){
                                    $code_loan = '';
                                    foreach ($loanDeductions as $k1 => $v1) {
                                        if($v1['code_loan'] != $code_loan){
                                            echo '<tr><td colspan=2 class=text-center><label class=text-primary>'.$v1['code_loan'].'</label></td></tr>';
                                        }
                                        echo    '<tr>'
                                                    .'<td>'.$v1['desc_sub'].'</td>'
                                                    .'<td style=text-align:right>'.$v1['amount'].'</td>'
                                                .'</tr>';
                                        $code_loan = $v1['code_loan']; 
                                        $total_loan_deduct += $v1['amount'];
                                    }
                                    echo '</table>'; 
                                } else {
                                    echo '<center><label class=text-danger>No data available.</label></center>';
                                }
                                ?>" 
                                data-placement="top">Loan Deductions</button>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  value="<?php echo number_format($total_loan_deduct,2); ?>" id="" class=" form-control" readonly>
                                        <?php $total += ((isset($total_loan_deduct) && $total_loan_deduct > 0) ? $total_loan_deduct : 0.00 ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                <button class="btn btn-info waves-effect form control" type="button"
                                data-toggle="popover" title="Other Deductions" data-html="true" 
                                data-content="<?php 
                                echo    '<table style=width:100%;>';
                                if(isset($otherDeductions) && sizeof($otherDeductions) > 0){
                                    foreach ($otherDeductions as $k4 => $v4) {
                                        echo    '<tr>'
                                                    .'<td>'.$v4['description'].'</td>'
                                                    .'<td style=text-align:right>'.$v4['amount'].'</td>'
                                                .'</tr>'; 
                                    }
                                    echo '</table>'; 
                                } else {
                                    echo '<center><label class=text-danger>No data available.</label></center>';
                                }
                                ?>"
                                data-placement="top">Other Deductions</button>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                <div class="form-group">
                                    <div class="form-line">
                                    <input type="text" value="<?php 
                                        if (isset($payrollData['data'][0]['total_other_deduct_amt'])) {
                                            $total += $total_other_deduct_amt = $payrollData['data'][0]['total_other_deduct_amt'];
                                            echo number_format($total_other_deduct_amt, 2);
                                        } else {
                                            echo '0.00'; // or any default value you want to display
                                        }
                                        ?>" 
                                        id="" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                       
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                <div class="form-group">
                                    <div class="form-line">                                       
                                        <input type="text"  value="<?php  echo isset($total) ? number_format($total, 2) : '0.00';?>" id="total_deduct_amt" class=" form-control" style="font-weight: bold;" readonly> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                               
                <div class="col-md-6">
                    <fieldset>
                        <legend>Additional Deductions for Leave without Pay:</legend>
                            <?php
                                $absent = $offset_days = 0;
                                $tot_tardiness_days = $tot_tardiness_hrs = $tot_tardiness_min = 0;
                                $tot_ut_days = $tot_ut_hrs = $tot_ut_min = 0;
                                $tot_off_days = $tot_off_hrs = $tot_off_min = $tot_off = 0;
                                $tot_tardiness = $tot_ut = $tot_offset = $tot_deduc = "";
                                $fraction = $leave_credits = $tot_absent_wout_credits = 0;

                                if(isset($ut)){
                                    $absent = $ut["absent"];
                                    $offset_days = $ut["offset_days"];
                                    //tardiness
                                    $tot_tardiness_min = fmod($ut["tardiness_min"], 60);
                                    $tot_tardiness_hrs	= fmod(floor($ut["tardiness_min"]/60) + $ut["tardiness_hrs"], 8);
                                    $tot_tardiness_days = floor((floor($ut["tardiness_min"]/60) + $ut["tardiness_hrs"])/8);
                                    $tot_tardiness = $tot_tardiness_days."° ".$tot_tardiness_hrs."' ".$tot_tardiness_min.'" ';
                                    //ut
                                    $tot_ut_min = fmod($ut["ut_min"], 60);
                                    $tot_ut_hrs	= fmod(floor($ut["ut_min"]/60) + $ut["ut_hrs"], 8);
                                    $tot_ut_days = floor((floor($ut["ut_min"]/60) + $ut["ut_hrs"])/8);
                                    $tot_ut = $tot_ut_days."° ".$tot_ut_hrs."' ".$tot_ut_min.'" ';
                                    //offset
                                    $tot_off_min = fmod($ut["offset_min"], 60);
                                    $tot_off_hrs = fmod(floor($ut["offset_min"]/60) + $ut["offset_hrs"], 8);
                                    $tot_off_days = floor((floor($ut["offset_min"]/60) + $ut["offset_hrs"])/8);

                                    $tot_ut_min -= $tot_off_min;
                                    if($tot_ut_min < 0){
                                        $tot_ut_min = 60 - $tot_ut_min;
                                        $tot_ut_hrs -= 1;
                                    }
                                    $tot_ut_hrs -= $tot_off_hrs;
                                    if($tot_ut_hrs < 0){
                                        $tot_ut_hrs = 8 - abs($tot_ut_hrs);
                                        $tot_ut_days -= 1;
                                    }
                                    $tot_ut_days -= $tot_off_days;
                                    if($tot_ut_days < 0) $tot_ut_days = 0;
                                    $min = fmod($tot_tardiness_min + $tot_ut_min, 60);
                                    $hrs = fmod(floor(($tot_tardiness_min + $tot_ut_min)/60) + ($tot_tardiness_hrs + $tot_ut_hrs), 8);
                                    $days =  ($absent - $ut["offset_days"]) + $tot_tardiness_days + $tot_ut_days + floor((floor(($tot_tardiness_min + $tot_ut_min)/60) + ($tot_tardiness_hrs + $tot_ut_hrs))/8);
                                    $tot_deduc = $days."° ".$hrs."' ".$min.'" ';
                                    $fraction = $ut["fraction"];
                                    $leave_credits = $ut["leave_credits_used"];
                                    $tot_absent_wout_credits = $fraction - $leave_credits;
                                    if($tot_absent_wout_credits < 0) $tot_absent_wout_credits = 0;
                                }
                            ?>
                            <div class="row">
                                <div class="col-lg-9 col-md-9 col-sm-5 col-xs-6 form-control-label">
                                    <label>Absences</label>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-7 col-xs-6 text-right">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text"  value="<?php echo $absent; ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-9 col-md-9 col-sm-5 col-xs-6 form-control-label">
                                    <label>Absence Offsets</label>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-7 col-xs-6 text-right">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text"  value="<?php echo $offset_days; ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th width="55%"></th>
                                                <th>Days</th>
                                                <th>Hrs</th>
                                                <th>Mins</th>
                                                <th width="25%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Tardiness</td>
                                                <td><span id="tardiness_day"><?php echo $tot_tardiness_days; ?></span></td>
                                                <td><span id="tardiness_hrs"><?php echo $tot_tardiness_hrs; ?></span></td>
                                                <td><span id="tardiness_min"><?php echo $tot_tardiness_min; ?></span></td>
                                                <td><span id="tardiness_total"><?php echo $tot_tardiness; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td>Undertime</td>
                                                <td><span id="ut_day"><?php echo $tot_ut_days; ?></span></td>
                                                <td><span id="ut_hrs"><?php echo $tot_ut_hrs; ?></span></td>
                                                <td><span id="ut_min"><?php echo $tot_ut_min; ?></span></td>
                                                <td><span id="ut_total"><?php echo $tot_ut; ?></span></td>
                                            </tr>
                                            <!-- <tr>
                                                <td>Undertime Offsets</td>
                                                <td><span><?php echo $tot_off_days; ?></span></td>
                                                <td><span><?php echo $tot_off_hrs; ?></span></td>
                                                <td><span><?php echo $tot_off_min; ?></span></td>
                                                <td><span><?php echo $tot_off; ?></span></td>
                                            </tr> -->
                                        </tbody>
                                        <tfoot>
                                            <tr><th colspan="4" align="right">Total</td><td><?php echo $tot_deduc; ?></th></tr>
                                            <!-- <tr><td colspan="4">Conversion</td><td><span id="total_ut_conversion"><?php echo number_format($fraction + $leave_credits, 3); ?></span></td></tr>
                                            <tr><td colspan="4">Leave Credits Used</td><td><span id="leave_credits_used"><?php echo number_format($leave_credits, 3); ?></span></td></tr>
                                            <tr><td colspan="4">Absences/Undertime W/out Pay Fraction</td><td><span id="remaining_ut"><?php echo number_format($fraction, 3); ?></span></td></tr>
                                            <tr><td colspan="4">Absences/Undertime W/out Pay Amount</td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text"  value=".00" id="total_tardiness_amt" class="total_tardiness_amt form-control" style="font-weight: bold;">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr> -->
                                        </tfoot>
                                    </table>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-5 col-xs-6 form-control-label">
                                <label for="email_address_2">Conversion</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-7 col-xs-6 text-right">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  value="<?php echo number_format($ut["ut_conversion"], 3); ?>" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-5 col-xs-6 form-control-label">
                                <label for="email_address_2">Leave Credits Used</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-7 col-xs-6 text-right">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  value="<?php echo number_format($leave_credits, 3); ?>" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-5 col-xs-6 form-control-label">
                                <label for="email_address_2">Absences/Undertime W/out Pay Fraction</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-7 col-xs-6 text-right">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  value="<?php echo number_format($fraction, 3); ?>" class="form-control" readonly> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-5 col-xs-6 form-control-label">
                                <label for="email_address_2">Absences/Undertime W/out Pay Amount</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-7 col-xs-6 text-right">
                                <div class="form-group">
                                    <div class="form-line">
                                        <?php
                                            $tardiness_amt = $payrollData['data'][0]['total_tardiness_amt'];
                                           
                                            if($tardiness_amt > 0){
                                                $val = $tardiness_amt;
                                            } else{
                                                $val = "0.00";
                                            }
                                        ?>
                                        <input type="text"  value="<?php echo number_format($val, 2); ?>" id="" class="form-control" style="font-weight: bold;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-2 col-xs-2 text-center">
                                (hrs)
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-5 col-xs-4">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                <label for="email_address_2">Tardiness</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-2 col-xs-2">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  value=".00" id="late" class="late form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-5 col-xs-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  value=".00" id="late_amt" class="late_amt form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                <label for="email_address_2">Undertime</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-2 col-xs-2">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  value=".00" id="utime" class="utime form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-5 col-xs-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  value=".00" id="utime_amt" class="utime_amt form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                <label for="email_address_2">Absent</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-2 col-xs-2">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  value=".00" id="abst" class="abst form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-5 col-xs-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  value=".00" id="abst_amt" class="abst_amt form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-2 col-xs-2">
                                Total
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-5 col-xs-4 text-right">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text"  value=".00" id="total_tardiness_amt" class="total_tardiness_amt form-control" style="font-weight: bold;">
                                    </div>
                                </div>
                            </div>
                        </div> -->
                      
                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-5 col-xs-6 form-control-label">
                                <label for="email_address_2">PERA (WOP)</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-7 col-xs-6 text-right">
                                <div class="form-group">
                                    <div class="form-line">
                                    <input type="text" value="<?php
                                        if($absent != 0){  
                                                $pera =  $payrollData['data'][0]['pera_amt'] / 22;

                                                $pera_wop =  $pera * $absent ;                                                                 
                                                //$pera_amt = $payrollData['data'][0]['pera_amt']                                            
                                                
                                                echo number_format($pera_wop, 2);
                                                $total += $pera_wop;                                       
                                        }else{
                                            echo 0.00;
                                        }
                                        ?>" 
                                        id="" class=" form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>


                    <br>
                    <br>
                    <div class="row hidden">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="email_address_2">Taxable Gross</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5 text-right">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text"  value=".00" id="taxable_gross" class="taxable_gross form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="email_address_2">Earned for the Period</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5 text-right">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text"  value=".00" id="earned_for_period" class="earned_for_period form-control"readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="email_address_2">Net Earned</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5 text-right">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text"  value=".00" id="net_earned" class="net_earned form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                 
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="email_address_2">Net Amount</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5 text-right">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text"  
                                    value=" <?php                    
                                                    
                                           

                                                //Allowance: $payrollData['data'][0]['rep_allowance'] + $payrollData['data'][0]['transpo_allowance']
                                                $total += $payrollData['data'][0]['total_tardiness_amt'];  
                                                $total_net = $payrollData['data'][0]['basic_pay'] - $total + $payrollData['data'][0]['pera_amt']; 

                                                $f = $total_net / 2;
                                                $f1 = floor($f);
                                                $firstCut = $f1;
                                                $secondCut =  $total_net - $firstCut;
                                            
                                                                     
                                            echo number_format($total_net, 2);
                                            //$net_pay = $employeeData['net_pay'];  echo $net_pay;                                            
                                        
                                         //var_dump($total_net);
                                    ?>" id="net_pay" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="email_address_2">Gross Pay</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5 text-right">
                            <div class="form-group">
                                <div class="form-line">                                                                    
                                        <input type="text"  
                                        value="<?php $total_gross = $payrollData['data'][0]['basic_pay'] + $payrollData['data'][0]['pera_amt']; echo number_format($total_gross, 2);?>" 
                                        id="gross_pay" class="form-control" readonly>                                                                
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="email_address_2">1st Cutoff</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5 text-right">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" value="<?php echo number_format($firstCut, 2)?>"                                
                                        id="cutoff_1" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="email_address_2">2nd Cutoff</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5 text-right">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" value="<?php echo number_format($secondCut, 2);  //prev val get from class cutoff_2?>" 
                                    id="cutoff_2" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                 
                    <!--<div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="email_address_2">3rd Cutoff</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5 text-right">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text"  value=".00" id="cutoff_3" class="cutoff_3 form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="email_address_2">4th Cutoff</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5 text-right">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text"  value=".00" id="cutoff_4" class="cutoff_4 form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>-->
                </div>   
            </div>
        </form>
    </div>
    <div class="text-right" style="width:100%;">
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>

