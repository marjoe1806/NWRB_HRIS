<?php //var_dump($otherEarnings);die(); 
    $month_name = array("January","February","March","April","May","June","July","August","September","October","November","December"); 
?>
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
                        <div class="col-md-12">
                            <fieldset>
                                <legend>ANNUAL GROSS COMPENSATION INCOME:</legend>
                                                       
                                <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                    <button class="btn btn-info waves-effect form control" type="button" 
                                    data-toggle="popover" title="Annual Salary" data-html="true" 
                                    data-content="<?php 
                                        echo    '<table style=width:100%;>';
                                        if(isset($annualSalary) && sizeof($annualSalary) > 0){
                                            $prev_salary = null;    
                                            $salary = null;
                                            $diff = 0;
                                            $totalSalary = 0;
                                            foreach($month_name AS $key => $value) {
                                                foreach ($annualSalary as $k3 => $v3) {
                                                    if($salary == null) {
                                                        $salary = $v3['salary'];
                                                        $diff = $v3['diff'];
                                                    }

                                                    if((($key+1) == $v3['month']) && $k3 != 0) {
                                                        $prev_salary = $salary;
                                                        $salary = $v3['salary'];
                                                        $diff = $v3['diff'];
                                                    }
                                                }

                                                if($diff == 0) {
                                                    $totalSalary += $salary;
                                                    echo    '<tr>'
                                                                .'<td>'.$value.'</td>'
                                                                .'<td style=text-align:right>'.number_format($salary,2).'</td>'
                                                            .'</tr>'; 
                                                } else {
                                                    $daily_rate = $prev_salary / 22;
                                                    $salary_diff = $daily_rate * $diff;

                                                    $daily_rate = $salary / 22;
                                                    $diff = 22 - $diff;
                                                    $salary_diff  += $daily_rate * $diff;

                                                    $totalSalary += $salary_diff;

                                                    echo    '<tr>'
                                                                .'<td>'.$value.'</td>'
                                                                .'<td style=text-align:right>'.number_format($salary_diff,2).'</td>'
                                                            .'</tr>';

                                                    $salary_diff = 0;
                                                    $diff = 0;
                                                }
                                                
                                            }
                                            
                                        } else {
                                            echo '<center><label class=text-danger>No data available.</label></center>';
                                        }
                                        echo '</table>'; 
                                    ?>" 
                                    data-placement="top">Annual Salary</button>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text"  value="<?php echo number_format($totalSalary,2); ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset>
                                <legend>NON-TAXABLE SSS, GSIS, PHIC, PAGIBIG AND UNION DUES:</legend>
                                
                                <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                    <button class="btn btn-info waves-effect form control" type="button" 
                                    data-toggle="popover" title="Pagibig Premiums" data-html="true" 
                                    data-content="<?php 
                                        echo    '<table style=width:100%;>';
                                        if(isset($annualPagibig) && sizeof($annualPagibig) > 0){
                                            $flag = 0;
                                            $pagibig_amt = null;
                                            $totalPagibigAmt = 0;
                                            $totalNontaxableAmount = 0;

                                            foreach($month_name AS $key => $value) {
                                                foreach ($annualPagibig as $k3 => $v3) {
                                                    if(($key+1) == $v3['month']) {
                                                        $pagibig_amt = $v3['pagibig_amt'];

                                                        if($k3+1 == sizeof($annualPagibig))
                                                            $flag = 1;
                                                        break;
                                                    } else {
                                                        if($flag == 0)
                                                            $pagibig_amt = null;
                                                    }
                                                }
                                                
                                                $totalPagibigAmt += $pagibig_amt;
                                                echo    '<tr>'
                                                            .'<td>'.$value.'</td>'
                                                            .'<td style=text-align:right>'.number_format($pagibig_amt,2).'</td>'
                                                        .'</tr>';                                 
                                            }
                                            $totalNontaxableAmount += $totalPagibigAmt;
                                        } else {
                                            echo '<center><label class=text-danger>No data available.</label></center>';
                                        }
                                        echo '</table>'; 
                                    ?>" 
                                    data-placement="top">Pagibig Premiums</button>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text"  value="<?php echo number_format($totalPagibigAmt,2); ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                    <button class="btn btn-info waves-effect form control" type="button" 
                                    data-toggle="popover" title="Philhealth Premiums" data-html="true" 
                                    data-content="<?php 
                                        echo    '<table style=width:100%;>';
                                        if(isset($annualPhilhealth) && sizeof($annualPhilhealth) > 0){
                                            $flag = 0;
                                            $philhealth_amt = null;
                                            $totalPhilhealthAmt = 0;
                                            foreach($month_name AS $key => $value) {
                                                foreach ($annualPhilhealth as $k3 => $v3) {
                                                    if(($key+1) == $v3['month']) {
                                                        $philhealth_amt = $v3['philhealth_amt'];

                                                        if($k3+1 == sizeof($annualPhilhealth))
                                                            $flag = 1;
                                                        break;
                                                    } else {
                                                        if($flag == 0)
                                                            $philhealth_amt = null;
                                                    }
                                                }
                                                
                                                $totalPhilhealthAmt += $philhealth_amt;
                                                echo    '<tr>'
                                                            .'<td>'.$value.'</td>'
                                                            .'<td style=text-align:right>'.number_format($philhealth_amt,2).'</td>'
                                                        .'</tr>';                                 
                                            }
                                            $totalNontaxableAmount += $totalPhilhealthAmt;
                                        } else {
                                            echo '<center><label class=text-danger>No data available.</label></center>';
                                        }
                                        echo '</table>'; 
                                    ?>" 
                                    data-placement="top">Philhealth Premiums</button>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text"  value="<?php echo number_format($totalPhilhealthAmt,2); ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                    <button class="btn btn-info waves-effect form control" type="button" 
                                    data-toggle="popover" title="Life & Ret. Premiums" data-html="true" 
                                    data-content="<?php 
                                        echo    '<table style=width:100%;>';
                                        if(isset($annualGSIS) && sizeof($annualGSIS) > 0){
                                            $flag = 0;
                                            $sss_gsis_amt = null;
                                            $totalGSISAmt = 0;
                                            foreach($month_name AS $key => $value) {
                                                foreach ($annualGSIS as $k3 => $v3) {
                                                    if(($key+1) == $v3['month']) {
                                                        $sss_gsis_amt = $v3['sss_gsis_amt'];

                                                        if($k3+1 == sizeof($annualGSIS))
                                                            $flag = 1;
                                                        break;
                                                    } else {
                                                        if($flag == 0)
                                                            $sss_gsis_amt = null;
                                                    }
                                                }
                                                
                                                $totalGSISAmt += $sss_gsis_amt;
                                                echo    '<tr>'
                                                            .'<td>'.$value.'</td>'
                                                            .'<td style=text-align:right>'.number_format($sss_gsis_amt,2).'</td>'
                                                        .'</tr>';                                 
                                            }
                                            $totalNontaxableAmount += $totalGSISAmt;
                                        } else {
                                            echo '<center><label class=text-danger>No data available.</label></center>';
                                        }
                                        echo '</table>'; 
                                    ?>" 
                                    data-placement="top">Life & Ret. Premiums</button>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text"  value="<?php echo number_format($totalGSISAmt,2); ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                    <button class="btn btn-info waves-effect form control" type="button" 
                                    data-toggle="popover" title="Union Dues" data-html="true" 
                                    data-content="<?php 
                                        echo    '<table style=width:100%;>';
                                        if(isset($annualUnionDues) && sizeof($annualUnionDues) > 0){
                                            $flag = 0;
                                            $union_dues_amt = null;
                                            $totalUnionDues = 0;
                                            foreach($month_name AS $key => $value) {
                                                foreach ($annualUnionDues as $k3 => $v3) {
                                                    if(($key+1) == $v3['month']) {
                                                        $union_dues_amt = $v3['union_dues_amt'];

                                                        if($k3+1 == sizeof($annualUnionDues))
                                                            $flag = 1;
                                                        break;
                                                    } else {
                                                        if($flag == 0)
                                                            $union_dues_amt = null;
                                                    }
                                                }
                                                
                                                $totalUnionDues += $union_dues_amt;
                                                echo    '<tr>'
                                                            .'<td>'.$value.'</td>'
                                                            .'<td style=text-align:right>'.number_format($union_dues_amt,2).'</td>'
                                                        .'</tr>';                                 
                                            }
                                            $totalNontaxableAmount += $totalUnionDues;
                                        } else {
                                            echo '<center><label class=text-danger>No data available.</label></center>';
                                        }
                                        echo '</table>'; 
                                    ?>" 
                                    data-placement="top">Union Dues</button>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text"  value="<?php echo number_format($totalUnionDues,2); ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-control-label">
                                        <label for="nontaxable_total">Total</label>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text"  value="<?php echo number_format($totalNontaxableAmount,2); ?>" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                        </div>

                        <div class="col-md-12">
                            <fieldset>
                                <legend>ANNUAL TAX DUE:</legend>
                                
                                <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                    <button class="btn btn-info waves-effect form control" type="button" 
                                    data-toggle="popover" title="Annual Tax Due" data-html="true" 
                                    data-content="<?php 
                                        echo    '<table style=width:100%;>';
                                        if(isset($annualTaxDue) && sizeof($annualTaxDue) > 0){
                                            $flag = 0;
                                            $wh_tax_amt = null;
                                            $wh_tax_amt_dec = null;
                                            $totalWhTaxAmt = 0;
                                            $expectedTaxAmt = 0;
                                            foreach($month_name AS $key => $value) {
                                                foreach ($annualTaxDue as $k3 => $v3) {
                                                    if(($key+1) == $v3['month']) {
                                                        $wh_tax_amt = $v3['wh_tax_amt'];

                                                        if($k3+1 == sizeof($annualTaxDue))
                                                            $flag = 1;
                                                        break;
                                                    } else {
                                                        if($flag == 0)
                                                            $wh_tax_amt = null;
                                                    }
                                                }
                                                
                                                if($key+1 == sizeof($month_name)) {
                                                    $expectedTaxAmt = $wh_tax_amt * 12;
                                                    $wh_tax_amt_dec = $expectedTaxAmt - $totalWhTaxAmt;
                                                    echo    '<tr>'
                                                                .'<td>'.$value.'</td>'
                                                                .'<td style=text-align:right>'.number_format($wh_tax_amt_dec,2).'</td>'
                                                            .'</tr>'; 
                                                } else {
                                                    $totalWhTaxAmt += $wh_tax_amt;
                                                    echo    '<tr>'
                                                                .'<td>'.$value.'</td>'
                                                                .'<td style=text-align:right>'.number_format($wh_tax_amt,2).'</td>'
                                                            .'</tr>'; 
                                                }
                                                                                
                                            }
                                        } else {
                                            echo '<center><label class=text-danger>No data available.</label></center>';
                                        }
                                        echo '</table>'; 
                                    ?>" 
                                    data-placement="top">Annual Tax Due</button>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text"  value="<?php echo number_format($expectedTaxAmt,2); ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset>
                                <legend>TOTAL NON-TAXABLE BONUS/INCENTIVES</legend>
                                                       
                                <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                    <button class="btn btn-info waves-effect form control" type="button" 
                                    data-toggle="popover" title="Bonus & Incentives" data-html="true" 
                                    data-content="<?php 
                                        $totalNonTaxableBonus = 0;
                                        $bonus_amt = 0;
                                        echo    '<table style=width:100%;>';
                                        if(isset($nonTaxableReleasedBonus) && sizeof($nonTaxableReleasedBonus) > 0){
                                            foreach ($nonTaxableReleasedBonus as $k1 => $v1) {
                                                $bonus_amt = $v1['amount'];
                                                $totalNonTaxableBonus += $bonus_amt;

                                                echo    '<tr>'
                                                            .'<td>'.$v1['bonus_type'].'</td>'
                                                            .'<td style=text-align:right>'.number_format($bonus_amt,2).'</td>'
                                                        .'</tr>';
                                            }
                                        } else {
                                            echo '<center><label class=text-danger>No data available.</label></center>';
                                        }

                                        echo '</table>'; 
                                    ?>" 
                                    data-placement="top">Bonus & Incentives</button>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text"  value="<?php echo number_format($totalNonTaxableBonus,2); ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                        </div>

                        <div class="col-md-12">
                            <fieldset>
                                <legend>TOTAL NON-TAXABLE COMPENSATION INCOME</legend>
                                                       
                                <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                    <button class="btn btn-info waves-effect form control" type="button" 
                                    data-toggle="popover" title="Non-taxable Compensation Income" data-html="true" 
                                    data-content="<?php 
                                        echo    '<table style=width:100%;>';
                                        echo    '<tr>'
                                                    .'<td>NON-TAXABLE SSS, GSIS, PHIC, PAGIBIG AND UNION DUES</td>'
                                                    .'<td style=text-align:right>'.number_format($totalNontaxableAmount,2).'</td>'
                                                .'</tr>';
                                        echo    '<tr>'
                                                    .'<td>&nbsp;</td>'
                                                    .'<td>&nbsp;</td>'
                                                .'</tr>';
                                        echo    '<tr>'
                                                    .'<td>TOTAL NON-TAXABLE BONUS/INCENTIVES</td>'
                                                    .'<td style=text-align:right>'.number_format($totalNonTaxableBonus,2).'</td>'
                                                .'</tr>';
                                        echo '</table>'; 
                                        $totalNonTaxable = $totalNontaxableAmount + $totalNonTaxableBonus;
                                    ?>" 
                                    data-placement="top">Compensation Income</button>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text"  value="<?php echo number_format($totalNonTaxable,2); ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                        </div>

                        <div class="col-md-12">
                            <fieldset>
                                <legend>TAXABLE (EXCESS) 13TH MONTH PAY & OTHER BENEFITS</legend>
                                                       
                                <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                    <button class="btn btn-info waves-effect form control" type="button" 
                                    data-toggle="popover" title="Bonus & Incentives" data-html="true" 
                                    data-content="<?php 
                                        $bonus_taxable = 0;
                                        $totalBonus = 0;
                                        $bonus_amt = 0;
                                        echo    '<table style=width:100%;>';
                                        if(isset($releasedBonus) && sizeof($releasedBonus) > 0){
                                            foreach ($releasedBonus as $k1 => $v1) {
                                                $bonus_amt = $v1['amount'];
                                                $totalBonus += $bonus_amt;

                                                echo    '<tr>'
                                                            .'<td>'.$v1['bonus_type'].'</td>'
                                                            .'<td style=text-align:right>'.number_format($bonus_amt,2).'</td>'
                                                        .'</tr>';
                                            }
                                            if(($bonus_amt-90000) > 0) 
                                                $bonus_taxable = $bonus_amt - 90000;
                                        } else {
                                            echo '<center><label class=text-danger>No data available.</label></center>';
                                        }

                                        echo '</table>'; 
                                    ?>" 
                                    data-placement="top">Bonus & Incentives</button>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text"  value="<?php echo number_format($totalBonus,2); ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-control-label">
                                        <label for="bonus_taxable">Taxable amount on bonus</label>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text"  value="<?php echo number_format($bonus_taxable,2); ?>" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-md-12">
                            <fieldset>
                                <legend>TAXABLE BASIC SALARY</legend>
                                                       
                                <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                    <button class="btn btn-info waves-effect form control" type="button" 
                                    data-toggle="popover" title="Taxable Basic Salary" data-html="true" 
                                    data-content="<?php 
                                        echo    '<table style=width:100%;>';
                                        echo    '<tr>'
                                                    .'<td>ANNUAL GROSS COMPENSATION INCOME</td>'
                                                    .'<td style=text-align:right>'.number_format($totalSalary,2).'</td>'
                                                .'</tr>';
                                        echo    '<tr>'
                                                    .'<td>&nbsp;</td>'
                                                    .'<td>&nbsp;</td>'
                                                .'</tr>';
                                        echo    '<tr>'
                                                    .'<td>NON-TAXABLE SSS, GSIS, PHIC, PAGIBIG AND UNION DUES</td>'
                                                    .'<td style=text-align:right>'.number_format($totalNontaxableAmount,2).'</td>'
                                                .'</tr>';
                                        echo '</table>'; 
                                        $taxableBasicSalary = $totalSalary - $totalNontaxableAmount;
                                    ?>" 
                                    data-placement="top">Taxable Basic Salary</button>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text"  value="<?php echo number_format($taxableBasicSalary,2); ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                        </div>

                        <div class="col-md-12">
                            <fieldset>
                                <legend>TOTAL TAXABLE COMPENSATION INCOME</legend>
                                                       
                                <div class="col-lg-3 col-md-3 col-sm-5 col-xs-6 form-control-label">
                                    <button class="btn btn-info waves-effect form control" type="button" 
                                    data-toggle="popover" title="Total Taxable Compensation Income" data-html="true" 
                                    data-content="<?php 
                                        echo    '<table style=width:100%;>';
                                        echo    '<tr>'
                                                    .'<td>TAXABLE BASIC SALARY</td>'
                                                    .'<td style=text-align:right>'.number_format($taxableBasicSalary,2).'</td>'
                                                .'</tr>';
                                        echo    '<tr>'
                                                    .'<td>&nbsp;</td>'
                                                    .'<td>&nbsp;</td>'
                                                .'</tr>';
                                        echo    '<tr>'
                                                    .'<td>TAXABLE (EXCESS) 13TH MONTH PAY & OTHER BENEFITS</td>'
                                                    .'<td style=text-align:right>'.number_format($totalBonus,2).'</td>'
                                                .'</tr>';
                                        echo '</table>'; 
                                        $totalTaxable = $taxableBasicSalary + $totalBonus;
                                    ?>" 
                                    data-placement="top">Compensation Income</button>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-6 text-right">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text"  value="<?php echo number_format($totalTaxable,2); ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                        </div>

                    </div>
                </div>
            </div>

        </form>
    </div>
    <br>
    <div class="text-right" style="width:100%;">
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>

