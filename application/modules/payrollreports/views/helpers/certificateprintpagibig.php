<?php if(isset($list) && sizeof($list) > 0){ ?>
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
                            font-family: "Times New Roman";
                            font-size:12px;
                        } 
                        table tr td{
                            font-family: "Times New Roman";
                            font-size:12px;

                        } 
                        @page {size: portrait}
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
                    <p align="right"><?php echo date("M d, Y");?></p>
                    <h3 align="center">CERTIFICATION</h3>
                    <h4>TO WHOM IT MAY CONCERN:</h4>

                    <?php if($list[0]['sub_loans_id'] == 21) { ?>
                    <p>This is to certify that the following <b>Short Term Loan (STL)</b>   
                    was remitted to <b>Home Development Mutual Fund</b> by the <b>NATIONAL WATER RESOURCES BOARD</b> 
                    with <b>Employer ID No. 202240010005</b> under the name of <b>Mr/Ms. <?php echo $list[0]['first_name'].' '.$list[0]['middle_name'].' '.$list[0]['last_name']; ?> </b>
                    with <b>MID No. <?php echo $list[0]['pagibig'];?></b> and <b>Application No./Agreement No. 182660000130061</b> as follows:
                    </p>
                    <table id="mainTable">
                        <thead style="text-align:center; font-weight: bold;">
                            <td style=" border: solid 1px #3b3b3b;" colspan="2">MONTH/YEAR</td>
                            <td style=" border: solid 1px #3b3b3b;">OR No./LDDAP-ADA No.</td>
                            <td style=" border: solid 1px #3b3b3b;">DATE</td>
                            <td style=" border: solid 1px #3b3b3b;">TOTAL AMOUNT</td>
                        </thead>
                        <?php 
                            $total_amount = 0;
                            if(isset($list) && sizeof($list) > 0):
                                foreach($list as $k => $v) {
                                    $total_amount += $v['amount'];
                        ?>
                            <tr>
                            <td style="text-align:center; border: solid 1px #3b3b3b;"><?php echo strtoupper(date("F", strtotime($v['payroll_period']))); ?></td>
                                <td style="text-align:center; border: solid 1px #3b3b3b;"><?php echo date("Y", strtotime($v['payroll_period'])); ?></td>
                                <td style="text-align:center; border: solid 1px #3b3b3b;"><?php echo $v['official_receipt_no']; ?></td>
                                <td style="text-align:center; border: solid 1px #3b3b3b;"><?php echo date('m/d/Y',strtotime($v['date_posted'])); ?></td>
                                <td style="text-align:center; border: solid 1px #3b3b3b;"><?php echo number_format($v['amount'],2); ?></td>
                                <!-- <td style="text-align:center; border: solid 1px #3b3b3b;"><?php //echo number_format($v['employeer_share'],2); ?></td> -->
                            </tr>
                        <?php
                                } 
                            endif; 
                        ?>
                        <tr>
                            <td style="text-align:right; border: solid 1px #3b3b3b; font-weight: bold; padding-right:20px;" colspan="4">TOTAL</td>
                            <td style="text-align:center; border: solid 1px #3b3b3b; font-weight: bold;"><?php echo number_format($total_amount,2); ?></td>
                        </tr>
                    </table>
                    <?php }?>
                    <p>This certification is being issued upon request of the above-named employee for whatever legal purpose it may serve him/her.</p><br>
                    <b>
                        <p style="margin-left:70%;">CERTIFIED CORRECT:</p><br>
                        <p style="margin-left:70%;">JESUSA T. DELOS SANTOS <br>Administrative Officer V</p>
                    </b>
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

<?php } else{ ?>
<div class="row">
    <div class="col-md-12">
        <h3>No available records to show.</h3>
    </div>  
</div>
<?php } ?>