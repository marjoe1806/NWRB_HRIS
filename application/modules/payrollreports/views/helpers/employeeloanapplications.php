<?php
    $to = "";
    $from = "";
    $payroll_period = "";
    if(isset($list) && sizeof($list) > 0){
        $to = $list[0]['end_date'];
        $from = $list[0]['start_date'];
        $payroll_period = $list[0]['payroll_period'];
    }

?>
<div class="row">
    <div class="col-md-12">
        <style type="text/css">
        </style>
        <div style="width:100%; overflow-x:auto;">
            <div id="clearance-div">
                <style>
                    @media print{
                        body{
                            margin: 10mm 10mm 10mm 10mm;
                            font-family: "Arial";
                            font-size:12px;
                        } 
                        table tr td{
                            font-family: "Arial";
                            font-size:12px;

                        } 
                        @page {size: landscape}
                    }
                    @media screen and (min-width: 961px){
                        #certificate-container{
                            padding:20px 20px 20px 20px; 
                        }
                    }
                    @media screen and (max-width: 960px){
                        #certificate-container{
                            padding:10px 10px 10px 10px; 
                        }
                    }
                    #certificate-container{
                        font-size:12px;
                        width:100%;
                        text-align: justify;
                        text-justify: inter-word;

                    }
                    #certificate-container div{
                        display: inline-block;
                    }
                    #certificate-container .fixdiv{

                        border-bottom:1px solid gray;
                    }
                    #certificate-container #mainTable {
                        border-collapse: collapse;

                    }
                    #certificate-container table{
                        width:100%;
                    }
                     #certificate-container table tr td{
                        padding:2px; 
                    }
                </style>
                <div id="certificate-container">
                    <h2>List of Loan Applications</h2>
                    <table id="mainTable">
                        <tr style="text-align:center;">
                            <td style=" border: solid 1px #3b3b3b;">Employee Name</td>
                            <td style=" border: solid 1px #3b3b3b;">Category</td>
                            <td style=" border: solid 1px #3b3b3b;">Type</td>
                            <td style=" border: solid 1px #3b3b3b;">Date Issued</td>
                            <td style=" border: solid 1px #3b3b3b;">Loan Amt</td>
                            <td style=" border: solid 1px #3b3b3b;">Maturity Date</td>
                            <td style=" border: solid 1px #3b3b3b;">Amortization</td>
                        </tr>
                        <?php 
                            if(isset($list) && sizeof($list) > 0):
                                foreach($list as $k => $v) {
                                
                        ?>
                                <tr>
                                    <td><?php echo $v['first_name'].' '.$v['middle_name'].' '.$v['last_name']; ?></td>
                                    <td><?php echo $v['category_code']; ?></td>
                                    <td><?php echo $v['type_code']; ?></td>
                                    <td style="text-align:center;"><?php echo date('m/d/Y',strtotime($v['date_started'])); ?></td>
                                    <td style="text-align:right;"><?php echo number_format($v['loan_amount'],2); ?></td>
                                    <td style="text-align:center;"><?php echo date('m/d/Y',strtotime($v['maturity_date'])); ?></td>
                                    <td style="text-align:right;"><?php echo number_format($v['amortization_per_month'],2); ?></td>
                                </tr>
                        <?php
                                } 
                            endif; 
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12 text-right">
        <button id="printClearance" class="btn bg-green btn-fab btn-fab-mini">Print <i class = "material-icons">print</button>
    </div>
</div>
