<?php

?>
<div class="row">
    <div class="col-md-12">
        <style type="text/css">
        </style>
        <div style="width:100%; overflow-x:auto;">
            <!-- <link href="<?php echo base_url(); ?>assets/plugins/paper/paper.css" rel="stylesheet"> -->
            <div id="clearance-div">
                <?php if($pay_basis == "Permanent-Casual"): ?>
                <style type = 'text/css'>
                    @media print{
                        /*280mm 378mm
                          11in 15in
                        */
                        @page { 
                            size: US Std Fanfold; 

                        }
                        .page{page-break-after: always;height: 100%;}
                        body {
                           transform: scale(1);
                        }
                        /*table{
                            display: block;
                            transform-origin: top left;
                            transform: rotate(-90deg) translate(-100%);
                            margin-top: 0%;
                            white-space: nowrap;
                        }*/
                    }
                    <!--
                    .flxmain_table {table-layout:fixed; border-collapse:collapse;border-spacing: 0}
                    table.flxmain_table td {overflow:hidden;padding: 0 1.5pt}
                    .flxmain_bordered_table {table-layout:fixed; border-collapse:collapse;border-spacing: 0;border:1px solid white}
                    .flxmain_bordered_table td {overflow:hidden;padding: 0 1.5pt;border:1px solid white}
                    .imagediv {position:absolute;border:none}
                    table td.imagecell {vertical-align:top;text-align:left;padding:0}
                    table td.flxHeading {background-color:#E7E7E7;text-align:center;border: 1px solid black;font-family:helvetica,arial,sans-serif;font-size:10pt}
                    .flx0 {
                        background-color:white;
                        color:black;
                        font-size:11pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:Calibri;
                        text-align:left;
                        vertical-align:bottom;
                        white-space:nowrap;
                    }

                    .flx1 {
                        background-color:white;
                        color:black;
                        font-size:12pt;
                        font-weight:bold;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:center;
                        vertical-align:bottom;
                        white-space:nowrap;
                    }

                    .flx2 {
                        background-color:white;
                        color:black;
                        font-size:12pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:left;
                        vertical-align:bottom;
                        white-space:nowrap;
                    }

                    .flx3 {
                        background-color:white;
                        color:black;
                        font-size:14pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:left;
                        vertical-align:bottom;
                        white-space:nowrap;
                    }

                    .flx4 {
                        background-color:white;
                        color:black;
                        font-size:14pt;
                        font-weight:bold;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:center;
                        vertical-align:bottom;
                        white-space:nowrap;
                    }

                    .flx5 {
                        background-color:white;
                        color:black;
                        font-size:12pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:'MS Sans Serif';
                        text-align:left;
                        vertical-align:bottom;
                        white-space:nowrap;
                    }

                    .flx6 {
                        background-color:white;
                        color:black;
                        font-size:14pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:'MS Sans Serif';
                        text-align:left;
                        vertical-align:bottom;
                        white-space:nowrap;
                    }

                    .flx7 {
                        background-color:white;
                        color:black;
                        font-size:14pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:center;
                        vertical-align:middle;
                        white-space:nowrap;
                    }

                    .flx8 {
                        background-color:white;
                        color:black;
                        font-size:14pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:left;
                        vertical-align:middle;
                        white-space:nowrap;
                    }

                    .flx9 {
                        background-color:white;
                        color:black;
                        font-size:12pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:center;
                        vertical-align:middle;
                        white-space:nowrap;
                    }

                    .flx10 {
                        background-color:white;
                        color:black;
                        font-size:12pt;
                        font-weight:bold;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:left;
                        vertical-align:middle;
                        white-space:nowrap;
                    }

                    .flx11 {
                        background-color:white;
                        color:black;
                        font-size:12pt;
                        font-weight:bold;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:left;
                        vertical-align:middle;
                        white-space:nowrap;
                    }

                    .flx12 {
                        background-color:white;
                        color:black;
                        font-size:12pt;
                        font-weight:bold;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:right;
                        vertical-align:middle;
                        white-space:nowrap;
                    }

                    .flx13 {
                        background-color:white;
                        color:black;
                        font-size:12pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:right;
                        vertical-align:middle;
                        white-space:nowrap;
                    }

                    .flx14 {
                        background-color:white;
                        color:black;
                        font-size:10pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:center;
                        vertical-align:bottom;
                        white-space:nowrap;
                    }

                    .flx15 {
                        background-color:white;
                        color:black;
                        font-size:10pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:center;
                        vertical-align:bottom;
                        white-space:nowrap;
                    }

                    .flx16 {
                        background-color:white;
                        color:black;
                        font-size:12pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:left;
                        vertical-align:middle;
                        white-space:nowrap;
                    }

                    .flx17 {
                        background-color:white;
                        color:black;
                        font-size:14pt;
                        font-weight:bold;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:right;
                        vertical-align:middle;
                        white-space:nowrap;
                    }

                    .flx18 {
                        background-color:white;
                        color:black;
                        font-size:12pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:Tahoma;
                        text-align:left;
                        vertical-align:middle;
                        white-space:nowrap;
                    }

                    .flx19 {
                        background-color:white;
                        color:black;
                        font-size:14pt;
                        font-weight:bold;
                        font-style:normal;
                        font-family:Tahoma;
                        text-align:left;
                        vertical-align:middle;
                        white-space:nowrap;
                    }

                    .flx20 {
                        background-color:white;
                        color:black;
                        font-size:14pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:Tahoma;
                        text-align:right;
                        vertical-align:middle;
                        white-space:nowrap;
                    }

                    .flx21 {
                        background-color:white;
                        color:black;
                        font-size:12pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:Tahoma;
                        text-align:right;
                        vertical-align:middle;
                        white-space:nowrap;
                    }

                    .flx22 {
                        background-color:white;
                        color:black;
                        font-size:12pt;
                        font-weight:bold;
                        font-style:normal;
                        font-family:Tahoma;
                        text-align:right;
                        vertical-align:middle;
                        white-space:nowrap;
                    }

                    .flx23 {
                        background-color:white;
                        color:black;
                        font-size:10pt;
                        font-weight:bold;
                        font-style:normal;
                        font-family:Arial;
                        text-align:left;
                        vertical-align:bottom;
                        white-space:nowrap;
                    }

                    .flx24 {
                        background-color:white;
                        color:black;
                        font-size:10pt;
                        font-weight:bold;
                        font-style:normal;
                        font-family:Arial;
                        text-align:center;
                        vertical-align:bottom;
                        white-space:nowrap;
                    }

                    .flx25 {
                        background-color:white;
                        color:black;
                        font-size:8pt;
                        font-weight:bold;
                        font-style:normal;
                        font-family:Arial;
                        text-align:left;
                        vertical-align:bottom;
                        white-space:nowrap;
                    }

                    .flx26 {
                        background-color:white;
                        color:black;
                        font-size:8pt;
                        font-weight:bold;
                        font-style:normal;
                        font-family:Arial;
                        text-align:center;
                        vertical-align:bottom;
                        white-space:nowrap;
                    }

                    .flx27 {
                        background-color:white;
                        color:black;
                        font-size:14pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:center;
                        vertical-align:bottom;
                        white-space:nowrap;
                    }

                    .flx28 {
                        background-color:white;
                        color:black;
                        font-size:14pt;
                        font-weight:bold;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:left;
                        vertical-align:bottom;
                        white-space:nowrap;
                    }

                    .flx29 {
                        background-color:white;
                        color:black;
                        font-size:12pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:'Times New Roman';
                        text-align:center;
                        vertical-align:middle;
                        white-space:normal;
                    }

                    .flx30 {
                        background-color:white;
                        color:black;
                        font-size:12pt;
                        font-weight:normal;
                        font-style:normal;
                        font-family:'MS Sans Serif';
                        text-align:left;
                        vertical-align:bottom;
                        white-space:normal;
                    }

                -->
                </style>
                <div class="page">
                    <table class='flxmain_bordered_table' border='1' cellpadding='0' cellspacing='0' style='width:1477.46pt' summary="Excel Sheet: Sheet1">
                        <col class='flx0' style ='width:26.83pt;'>
                        <col class='flx0' style ='width:63.93pt;'>
                        <col class='flx0' style ='width:127.86pt;'>
                        <col class='flx0' style ='width:21.3pt;'>
                        <col class='flx0' style ='width:70.23pt;'>
                        <col class='flx0' style ='width:54.46pt;'>
                        <col class='flx0' style ='width:63.93pt;'>
                        <col class='flx0' style ='width:75.76pt;'>
                        <col class='flx0' style ='width:72.61pt;'>
                        <col class='flx0' style ='width:69.46pt;'>
                        <col class='flx0' style ='width:63.93pt;'>
                        <col class='flx0' style ='width:107.34pt;'>
                        <col class='flx0' style ='width:85.24pt;'>
                        <col class='flx0' style ='width:83.66pt;'>
                        <col class='flx0' style ='width:82.08pt;'>
                        <col class='flx0' style ='width:63.13pt;'>
                        <col class='flx0' style ='width:77.36pt;'>
                        <col class='flx0' style ='width:71.83pt;'>
                        <col class='flx0' style ='width:74.18pt;'>
                        <col class='flx0' style ='width:95.51pt;'>
                        <col class='flx0' style ='width:26.83pt;'>
                        <tr style='display:none'>
                            <td style = 'padding:0;width:26.83pt;'></td>
                            <td style = 'padding:0;width:63.93pt;'></td>
                            <td style = 'padding:0;width:127.86pt;'></td>
                            <td style = 'padding:0;width:21.3pt;'></td>
                            <td style = 'padding:0;width:70.23pt;'></td>
                            <td style = 'padding:0;width:54.46pt;'></td>
                            <td style = 'padding:0;width:63.93pt;'></td>
                            <td style = 'padding:0;width:75.76pt;'></td>
                            <td style = 'padding:0;width:72.61pt;'></td>
                            <td style = 'padding:0;width:69.46pt;'></td>
                            <td style = 'padding:0;width:63.93pt;'></td>
                            <td style = 'padding:0;width:107.34pt;'></td>
                            <td style = 'padding:0;width:85.24pt;'></td>
                            <td style = 'padding:0;width:83.66pt;'></td>
                            <td style = 'padding:0;width:82.08pt;'></td>
                            <td style = 'padding:0;width:63.13pt;'></td>
                            <td style = 'padding:0;width:77.36pt;'></td>
                            <td style = 'padding:0;width:71.83pt;'></td>
                            <td style = 'padding:0;width:74.18pt;'></td>
                            <td style = 'padding:0;width:95.51pt;'></td>
                            <td style = 'padding:0;width:26.83pt;'></td>
                        </tr>
                        <tr  style='height:15.29pt;'>
                            <td class = 'flx0'></td>

                            <td class='flx1' colspan = '16' rowspan ='1'>G E N E R A L &nbsp;&nbsp;P A Y R O L L</td>
                            <td class = 'flx0'></td>
                            <td class = 'flx0'></td>
                            <td class = 'flx0'></td>
                            <td class = 'flx0'></td>

                        </tr>
                        <tr  style='height:15.29pt;'>
                            <td class = 'flx0'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>

                            <td class='flx2'>&nbsp;</td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx0'></td>
                            <td class = 'flx0'></td>
                            <td class = 'flx0'></td>
                            <td class = 'flx0'></td>

                        </tr>
                        <tr class='flx3' style='height:19.52pt;'>
                            <td class = 'flx3'></td>

                            <td class='flx3' colspan = '11'><span class='flx3' style ='height:19.52pt;'>WE HEREBY ACKNOWLEDGE to have received from &nbsp;&nbsp;&nbsp;&nbsp;<span style ='font-weight:bold;'>
                                <?php echo @$payroll[0]['department_name']; ?>
                            </span></span></td>
                            <td class='flx3' colspan = '8'>the sum herein opposite our respective names being in full compensation for </td>
                            <td class = 'flx3'></td>

                        </tr>
                        <tr class='flx3' style='height:18.93pt;'>
                            <td class='flx3' colspan = '5'> our services for the period</td>
                            <td class='flx4' colspan = '4' rowspan ='1'>
                                <?php
                                    $from = date('M d, Y',strtotime(@$payroll_period[0]['start_date']));
                                    $to = date('M d, Y',strtotime(@$payroll_period[0]['end_date']));
                                ?>
                                <?php echo $from.' to '.$to; ?>
                            </td>
                            <td class = 'flx3'></td>

                            <td class='flx3' colspan = '4'>except as noted otherwise in the remarks column.</td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                        </tr>
                        <tr class='flx5' style='height:19.52pt;'>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx5' style = 'border-bottom:medium solid black;'></td>

                        </tr>
                        <tr class='flx6' style='height:19.52pt;'>
                            <td class='flx7' style ='border-top:1px none black;'>No.</td>
                            <td class='flx8' style ='border-top:1px none black;' colspan = '2' rowspan ='1'>Name of Employee&nbsp;</td>
                            <td class = 'flx6' style = 'border-top:1px none black;'></td>

                            <td class='flx7' style ='border-top:1px none black;'>Monthly</td>
                            <td class='flx7' style ='border-top:1px none black;'>Under&nbsp;</td>
                            <td class='flx7' style ='border-top:1px none black;'>PERA</td>
                            <td class='flx7' style ='border-top:1px none black;' colspan = '2' rowspan ='1'>GSIS&nbsp;</td>
                            <td class='flx7' style ='border-top:1px none black;'><span class='flx7' style ='height:19.52pt;'>GSIS Optional</span></td>
                            <td class='flx7' style ='border-top:1px none black;'>S.D.O.&nbsp;</td>
                            <td class='flx7' style ='border-top:1px none black;'>GSIS&nbsp;</td>
                            <td class='flx7' style ='border-top:1px none black;'>&nbsp;GSIS&nbsp;</td>
                            <td class='flx7' style ='border-top:1px none black;'>GSIS</td>
                            <td class='flx7' style ='border-top:1px none black;'>GSIS&nbsp;</td>
                            <td class='flx9' style ='border-top:1px none black;'>OLD GSIS&nbsp;</td>
                            <td class='flx7' style ='border-top:1px none black;'>GSIS&nbsp;</td>
                            <td class='flx7' style ='border-top:1px none black;'>Gross Pay</td>
                            <td class='flx7' style ='border-top:1px none black;'>Net Pay:</td>
                            <td class='flx7' style ='border-top:medium solid black;' colspan = '2' rowspan ='1'>Remarks</td>
                        </tr>
                        <tr class='flx6' style='height:19.52pt;'>
                            <td class = 'flx6'></td>

                            <td class='flx8' colspan = '2' rowspan ='1'>Position</td>
                            <td class = 'flx6'></td>

                            <td class='flx7'>Rate&nbsp;</td>
                            <td class='flx7'>Payment</td>
                            <td class = 'flx6'></td>

                            <td class='flx7' colspan = '2' rowspan ='1'>Life &amp; Retirement</td>
                            <td class='flx7'>Insurance</td>
                            <td class='flx7'>Cash&nbsp;</td>
                            <td class='flx7'>Enhanced Salary&nbsp;</td>
                            <td class='flx7'>Consolidated&nbsp;</td>
                            <td class='flx7'>ECard</td>
                            <td class='flx7'>Emergency&nbsp;</td>
                            <td class='flx7'>Loan&nbsp;</td>
                            <td class='flx7'>Educational</td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>

                        </tr>
                        <tr class='flx6' style='height:19.52pt;'>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>

                            <td class='flx7'>P/Share</td>
                            <td class='flx7'>G/Share</td>
                            <td class = 'flx6'></td>

                            <td class='flx7'>Advance&nbsp;</td>
                            <td class = 'flx6'></td>

                            <td class='flx7'>Loan</td>
                            <td class = 'flx6'></td>

                            <td class='flx7'>Loan</td>
                            <td class = 'flx6'></td>

                            <td class='flx7'>Loan</td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>

                        </tr>
                        <tr class='flx6' style='height:19.52pt;'>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx7'></td>

                            <td class='flx7'>Over&nbsp;</td>
                            <td class = 'flx6'></td>

                            <td class='flx7'>GSIS&nbsp;</td>
                            <td class='flx7'>GSIS</td>
                            <td class='flx7'>GSIS&nbsp;</td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>

                            <td class='flx7'>Union&nbsp;</td>
                            <td class='flx7'>Withholding&nbsp;</td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>

                        </tr>
                        <tr class='flx6' style='height:19.52pt;'>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>

                            <td class='flx7'>Payment</td>
                            <td class='flx7'>(PERA)&nbsp;</td>
                            <td class='flx7'>Housing&nbsp;</td>
                            <td class='flx7'>Optional</td>
                            <td class='flx7'>Policy&nbsp;</td>
                            <td class='flx7' colspan = '2' rowspan ='1'>PAG-IBIG FUND</td>
                            <td class='flx7'>PAG-IBIG&nbsp;</td>
                            <td class='flx7'>PAG-IBIG&nbsp;</td>
                            <td class='flx7'>Phil.Health</td>
                            <td class='flx7'>Dues&nbsp;</td>
                            <td class='flx7'>Tax&nbsp;</td>
                            <td class='flx7'>Total&nbsp;</td>
                            <td class = 'flx7'>Net Pay/2</td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>

                        </tr>
                        <tr class='flx6' style='height:19.52pt;'>
                            <td class = 'flx6'></td>

                            <td class='flx4' colspan = '2' rowspan ='1'>SUPPLEMENTAL</td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx7'></td>
                            <td class = 'flx6'></td>

                            <td class='flx7'>Loan</td>
                            <td class='flx7'>Loan</td>
                            <td class='flx7'>Loan</td>
                            <td class='flx7'>&nbsp;P/Share</td>
                            <td class='flx7'>&nbsp;G/Share</td>
                            <td class='flx7'>Housing Loan</td>
                            <td class='flx7' colspan = '2'>Multi-Purpose</td>
                            <td class = 'flx7'></td>
                            <td class = 'flx7'></td>

                            <td class='flx7'>Deduction</td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>

                        </tr>
                        <tr class='flx6' style='height:19.52pt;'>
                            <td class = 'flx6'></td>

                            <td class='flx4' colspan = '2' rowspan ='1'>PAYROLL</td>
                            <td class = 'flx6'></td>
                            <td class = 'flx7'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>

                            <td class='flx7'>PAG-IBIG</td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>

                            <td class='flx7'>Provident</td>
                            <td class='flx7'>Other&nbsp;</td>
                            <td class='flx7'>Absence&nbsp;</td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>
                            <td class = 'flx6'></td>

                        </tr>
                        <tr class='flx6' style='height:19.52pt;'>
                            <td class = 'flx6' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx6' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx6' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx6' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx7' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx7' style = 'border-bottom:medium solid black;'>Damayan</td>

                            <td class='flx7' style ='border-bottom:medium solid black;'>E.C.C</td>
                            <td class='flx9' style ='border-bottom:medium solid black;'>SAKAMAY</td>
                            <td class='flx7' style ='border-bottom:medium solid black;'>PSMBFund</td>
                            <td class='flx7' style ='border-bottom:medium solid black;'>KOOP</td>
                            <td class='flx7' style ='border-bottom:medium solid black;'>Grocery</td>
                            <td class='flx9' style ='border-bottom:medium solid black;'>CALAMITY LOAN</td>
                            <td class='flx7' style ='border-bottom:medium solid black;'>NHMFC</td>
                            <td class='flx7' style ='border-bottom:medium solid black;'>Lost T.V.R.</td>
                            <td class='flx9' style ='border-bottom:medium solid black;'>Fund P/Share</td>
                            <td class='flx7' style ='border-bottom:medium solid black;'>Loans</td>
                            <td class='flx7' style ='border-bottom:medium solid black;'>Without Pay</td>
                            <td class = 'flx6' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx6' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx6' style = 'border-bottom:medium solid black;'></td>
                            <td class = 'flx6' style = 'border-bottom:medium solid black;'></td>

                        </tr>
                        <tr class='flx5' style='height:15.29pt;'>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>
                            <td class = 'flx5' style = 'border-top:1px none black;'></td>

                        </tr>
                        <!-- Employees -->
                        <?php 

                        $grand_total = array(
                            'salary'=>0.00,
                            'utime_amt'=>0.00,
                            'pera_amt'=>0.00,
                            'sss_gsis_amt'=>0.00,
                            'sss_gsis_amt_employer'=>0.00,
                            'gsis_optional_ins'=>0.00,
                            'gsis_sdo_ca'=>0.00,
                            'gsis_enhanced'=>0.00,
                            'gsis_consolidated'=>0.00,
                            'gsis_ecard_plus'=>0.00,
                            'gsis_old_gsis'=>0.00,
                            'gsis_emergency'=>0.00,
                            'gsis_housing'=>0.00,
                            'gsis_educational'=>0.00,
                            'gsis_psmbfund'=>0.00,
                            'gsis_policy'=>0.00,
                            'gsis_sakamay'=>0.00,
                            'gsis_nhmfc'=>0.00,
                            'gsis_union_dues'=>0.00,
                            'gsis_koop_loan'=>0.00,
                            'gsis_lost_tvr'=>0.00,
                            'gsis_optional_loan'=>0.00,
                            'gsis_other_loans'=>0.00,
                            'pagibig_housing'=>0.00,
                            'pagibig_multipurpose'=>0.00,
                            'pagibig_calamity'=>0.00,
                            'gross_pay'=>0.00,
                            'net_pay'=>0.00,
                            'total_ot_amt'=>0.00,
                            'pagibig_amt'=>0.00,
                            'pagibig_amt_employer'=>0.00,
                            'philhealth_amt'=>0.00,
                            'wh_tax_amt'=>0.00,
                            'total_deduct_amt'=>0.00,
                            'cutoff_1'=>0.00,
                            'acpcea_amt'=>0.00,
                            'damayan_amt'=>0.00,
                            'loan_grocery'=>0.00,
                            'abst_amt'=>0.00,
                            'provident_amt'=>0.00

                        );
                        $page_total = array(
                            'salary'=>0.00,
                            'utime_amt'=>0.00,
                            'pera_amt'=>0.00,
                            'sss_gsis_amt'=>0.00,
                            'sss_gsis_amt_employer'=>0.00,
                            'gsis_optional_ins'=>0.00,
                            'gsis_sdo_ca'=>0.00,
                            'gsis_enhanced'=>0.00,
                            'gsis_consolidated'=>0.00,
                            'gsis_ecard_plus'=>0.00,
                            'gsis_old_gsis'=>0.00,
                            'gsis_emergency'=>0.00,
                            'gsis_housing'=>0.00,
                            'gsis_educational'=>0.00,
                            'gsis_psmbfund'=>0.00,
                            'gsis_policy'=>0.00,
                            'gsis_sakamay'=>0.00,
                            'gsis_nhmfc'=>0.00,
                            'gsis_union_dues'=>0.00,
                            'gsis_koop_loan'=>0.00,
                            'gsis_lost_tvr'=>0.00,
                            'gsis_optional_loan'=>0.00,
                            'gsis_other_loans'=>0.00,
                            'pagibig_housing'=>0.00,
                            'pagibig_multipurpose'=>0.00,
                            'pagibig_calamity'=>0.00,
                            'gross_pay'=>0.00,
                            'net_pay'=>0.00,
                            'total_ot_amt'=>0.00,
                            'pagibig_amt'=>0.00,
                            'pagibig_amt_employer'=>0.00,
                            'philhealth_amt'=>0.00,
                            'wh_tax_amt'=>0.00,
                            'total_deduct_amt'=>0.00,
                            'cutoff_1'=>0.00,
                            'acpcea_amt'=>0.00,
                            'damayan_amt'=>0.00,
                            'loan_grocery'=>0.00,
                            'abst_amt'=>0.00,
                            'provident_amt'=>0.00

                        );

                        if(sizeof($payroll) > 0): 
                            $count = 0;
                            foreach ($payroll as $k1 => $v) {
                                $count+=1;
                            
                        ?>
                            <tr class='flx2' style='height:16.75pt;'>
                                <td class='flx9'><?php echo $count; ?></td>
                                <td class='flx10' colspan = '2' rowspan ='1'>&nbsp;
                                    <?php 
                                        $employee_number = $this->Helper->decrypt($v['employee_number'],$v['employee_id']);
                                        $first_name = $this->Helper->decrypt($v['first_name'],$v['employee_id']);
                                        $last_name = $this->Helper->decrypt($v['last_name'],$v['employee_id']);
                                        $middle_name = $this->Helper->decrypt($v['middle_name'],$v['employee_id']);
                                        $employee_id_number = $this->Helper->decrypt($v['employee_id_number'],$v['employee_id']);
                                        $middle_initial = isset($middle_name) && $middle_name != " " ? substr($middle_name, 0, 1).'.':"";
                                    ?>
                                    <?php echo strtoupper($last_name.', '.$first_name.' '.$middle_initial); ?>
                                </td>
                                <td class = 'flx11'></td>

                                <td class='flx12'>
                                    <?php $grand_total['salary'] += $v['salary']; ?>
                                    <?php $page_total['salary'] += $v['salary']; ?>
                                    <?php echo number_format((double)@$v['salary'],2); ?>&nbsp;

                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['utime_amt'] += $v['utime_amt']; ?>
                                    <?php $page_total['utime_amt'] += $v['utime_amt']; ?>
                                    <?php echo number_format((double)@$v['utime_amt'],2); ?>&nbsp;
                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['pera_amt'] += $v['pera_amt']; ?>
                                    <?php $page_total['pera_amt'] += $v['pera_amt']; ?>
                                    <?php echo number_format((double)@$v['pera_amt'],2); ?>&nbsp;
                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['sss_gsis_amt'] += $v['sss_gsis_amt']; ?>
                                    <?php $page_total['sss_gsis_amt'] += $v['sss_gsis_amt']; ?>
                                    <?php echo number_format((double)@$v['sss_gsis_amt'],2); ?>&nbsp;
                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['sss_gsis_amt_employer'] += $v['sss_gsis_amt_employer']; ?>
                                    <?php $page_total['sss_gsis_amt_employer'] += $v['sss_gsis_amt_employer']; ?>
                                    <?php echo number_format((double)@$v['sss_gsis_amt_employer'],2); ?>&nbsp;
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Optional Ins."){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_optional_ins'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_optional_ins'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "S.D.O. CA"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_sdo_ca'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_sdo_ca'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Enhanced"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_enhanced'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_enhanced'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Consolidated"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_consolidated'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_consolidated'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "ECard Plus"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_ecard_plus'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_ecard_plus'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Emergency"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_emergency'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_emergency'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Old GSIS"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_old_gsis'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_old_gsis'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Educational"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_educational'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_educational'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx12'>
                                    <?php $grand_total['gross_pay'] += $v['gross_pay']; ?>
                                    <?php $page_total['gross_pay'] += $v['gross_pay']; ?>
                                    <?php echo number_format((double)@$v['gross_pay'],2); ?>
                                </td>
                                <td class='flx12'>
                                    <?php $grand_total['net_pay'] += $v['net_pay']; ?>
                                    <?php $page_total['net_pay'] += $v['net_pay']; ?>
                                    <?php echo number_format((double)@$v['net_pay'],2); ?>&nbsp;
                                </td>
                                <td class='flx14' style ='border-bottom:1px solid black;'><?php echo strtoupper(date('F, Y',strtotime(@$payroll_period[0]['start_date']))); ?></td>
                                <td class='flx15'><?php echo $count; ?></td>
                            </tr>
                            <tr class='flx2' style='height:18.21pt;'>
                                <td class = 'flx15'></td>

                                <td class='flx15' colspan = '3' rowspan ='1'><?php echo strtoupper(@$v['position_name']) ?></td>
    <!--                             <td class = 'flx2'></td> -->
                                <td class = 'flx13'></td>

                                <td class='flx13'>
                                    <!--<?php $grand_total['total_ot_amt'] += $v['total_ot_amt']; ?>
                                    <?php $page_total['total_ot_amt'] += $v['total_ot_amt']; ?>
                                    <?php echo $v['total_ot_amt']; ?>&nbsp;-->
                                    <?php $grand_total['total_ot_amt'] += $v['total_other_deduct_amt']; ?>
                                    <?php $page_total['total_ot_amt'] += $v['total_other_deduct_amt']; ?>
                                    <?php echo number_format((double)@$v['total_other_deduct_amt'],2); ?>&nbsp;
                                </td>
                                <td class='flx13'>0.00&nbsp;</td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Housing"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_housing'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_housing'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Optional Loan"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_optional_loan'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_optional_loan'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Policy Loan"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_policy'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_policy'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['pagibig_amt'] += $v['pagibig_amt']; ?>
                                    <?php $page_total['pagibig_amt'] += $v['pagibig_amt']; ?>
                                    <?php echo number_format((double)@$v['pagibig_amt'],2); ?>&nbsp;
                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['pagibig_amt_employer'] += $v['pagibig_amt_employer']; ?>
                                    <?php $page_total['pagibig_amt_employer'] += $v['pagibig_amt_employer']; ?>
                                    <?php echo number_format((double)@$v['pagibig_amt_employer'],2); ?>&nbsp;
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "PAG-IBIG" && $vl1['code_sub'] == "Housing"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['pagibig_housing'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['pagibig_housing'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "PAG-IBIG" && $vl1['code_sub'] == "Multi-Purpose"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['pagibig_multipurpose'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['pagibig_multipurpose'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['philhealth_amt'] += $v['philhealth_amt']; ?>
                                    <?php $page_total['philhealth_amt'] += $v['philhealth_amt']; ?>
                                    <?php echo number_format((double)@$v['philhealth_amt'],2); ?>&nbsp;
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    /*$loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "UNION DUES"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }*/
                                    $loan = number_format(@$v['union_dues_amt'],2);
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_union_dues'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_union_dues'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['wh_tax_amt'] += $v['wh_tax_amt']; ?>
                                    <?php $page_total['wh_tax_amt'] += $v['wh_tax_amt']; ?>
                                    <?php echo number_format((double)@$v['wh_tax_amt'],2); ?>&nbsp;
                                </td>
                                <td class='flx12'> 
                                    <?php $grand_total['total_deduct_amt'] += $v['total_deduct_amt']; ?>
                                    <?php $page_total['total_deduct_amt'] += $v['total_deduct_amt']; ?>
                                    <?php echo number_format((double)@$v['total_deduct_amt'],2); ?>&nbsp;
                                </td>
                                <td class ='flx12'>
                                    <?php $grand_total['cutoff_1'] += $v['cutoff_1']; ?>
                                    <?php $page_total['cutoff_1'] += $v['cutoff_1']; ?>
                                    <?php echo number_format((double)@$v['cutoff_1'],2); ?>&nbsp;
                                </td>
                                <td class = 'flx2' style = 'border-top:1px none black;'></td>
                                <td class = 'flx15'></td>

                            </tr>
                            <tr class='flx2' style='height:16.75pt;'>
                                <td class = 'flx15'></td>
                                <td class = 'flx16'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx13'>
                                    <?php $grand_total['damayan_amt'] += $v['damayan_amt']; ?>
                                    <?php $page_total['damayan_amt'] += $v['damayan_amt']; ?>
                                    <?php echo number_format((double)@$v['damayan_amt'],2); ?>&nbsp;
                                </td>

                                <td class='flx13'>
                                    <?php $grand_total['acpcea_amt'] += $v['acpcea_amt']; ?>
                                    <?php $page_total['acpcea_amt'] += $v['acpcea_amt']; ?>
                                    <?php echo number_format((double)@$v['acpcea_amt'],2); ?>&nbsp;
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "Other Loans" && $vl1['code_sub'] == "Sakamay"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_sakamay'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_sakamay'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "Other Loans" && $vl1['code_sub'] == "PSMBFund"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_psmbfund'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_psmbfund'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "Other Loans" && $vl1['code_sub'] == "KOOP"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_koop_loan'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_koop_loan'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $allowance = "0.00";
                                    // if(sizeof($allowances[$v['employee_id']]) > 0){
                                    //     foreach ($allowances[$v['employee_id']] as $a => $av) {
                                    //         if($av['allowance_name'] == "Grocery"){
                                    //             $allowance = number_format($av['amount'],2);
                                    //             break;
                                    //         }
                                    //         else
                                    //             $allowance = "0.00";

                                    //     }
                                    // }
                                    echo $allowance.'&nbsp;';
                                    ?>
                                    <?php $grand_total['loan_grocery'] += $allowance; ?>
                                    <?php $page_total['loan_grocery'] += $allowance; ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "PAG-IBIG" && $vl1['code_sub'] == "Calamity"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['pagibig_calamity'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['pagibig_calamity'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "Other Loans" && $vl1['code_sub'] == "NHMFC"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_nhmfc'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_nhmfc'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Lost TVR"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_lost_tvr'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_lost_tvr'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                        $loan = "0.00";
                                        if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                            foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                                if($vl1['code_loan'] == "Other Loans" && $vl1['code_sub'] == "Provident"){
                                                    $loan = number_format($vl1['amount'],2);
                                                    break;
                                                }
                                                else
                                                    $loan = "0.00";

                                            }
                                        }
                                        //echo $loan;
                                    ?>
                                    <?php echo number_format((double)(@$v['provident_amt'] + $loan),2); ?>&nbsp;
                                    <?php $grand_total['provident_amt'] += (@$v['provident_amt'] + $loan); ?>
                                    <?php $page_total['provident_amt'] += (@$v['provident_amt'] + $loan); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Other Loans"){
                                                $loan = number_format($vl1['amount'],2);
                                                // var_dump($loan);die();
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_other_loans'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_other_loans'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['abst_amt'] += $v['abst_amt']; ?>
                                    <?php $page_total['abst_amt'] += $v['abst_amt']; ?>
                                    <?php echo number_format((double)@$v['abst_amt'],2); ?>&nbsp;
                                </td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx15'></td>

                            </tr>
                            <tr class='flx2' style='height:15.29pt;'>
                                <td class = 'flx15'></td>
                                <td class = 'flx16'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx15'></td>

                            </tr>
                        <?php 
                                array_shift($payroll);
                                if($k1 == 9){
                                    break;
                                }
                            }
                        endif; ?>
                        <!-- // Employees -->

                        <tr class='flx2' style='height:15.29pt;'>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>

                        </tr>
                        <!-- Page Total -->
                        <tr class='flx2' style='height:19.52pt;'>
                            <td class = 'flx2'></td>

                            <td class='flx10' colspan = '2' rowspan ='1'>PAGE TOTAL:</td>
                            <td class = 'flx2'></td>

                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['salary'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['utime_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['pera_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['sss_gsis_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['sss_gsis_amt_employer'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gsis_optional_ins'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gsis_sdo_ca'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gsis_enhanced'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gsis_consolidated'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gsis_ecard_plus'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gsis_emergency'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gsis_old_gsis'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gsis_educational'],2); ?>&nbsp; 
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gross_pay'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['net_pay'],2); ?>&nbsp;
                            </td>
                            <td class = 'flx12'></td>
                            <td class = 'flx12'></td>

                        </tr>
                        <tr class='flx2' style='height:19.52pt;'>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>

                            <td class='flx12'>
                            <?php echo number_format((double)@$page_total['total_ot_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>0.00&nbsp;</td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['gsis_housing'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['gsis_optional_loan'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                            <?php echo number_format((double)@$page_total['gsis_policy'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['pagibig_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['pagibig_amt_employer'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['pagibig_housing'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['pagibig_multipurpose'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['philhealth_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['gsis_union_dues'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['wh_tax_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['total_deduct_amt'],2); ?>&nbsp;
                            </td>
                            <td class = 'flx12'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>

                        </tr>
                        <tr class='flx2' style='height:19.52pt;'>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx12' style = 'border-bottom:medium double black;'></td>
                            <td class = 'flx12' style = 'border-bottom:medium double black;'><?php echo number_format((double)@$page_total['damayan_amt'],2); ?>&nbsp;</td>

                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['acpcea_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['gsis_sakamay'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['gsis_psmbfund'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['gsis_koop_loan'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['loan_grocery'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['pagibig_calamity'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['gsis_nhmfc'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                            <?php echo number_format((double)@$page_total['gsis_lost_tvr'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['provident_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['gsis_other_loans'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['abst_amt'],2); ?>&nbsp;
                            </td>
                            <td class = 'flx12' style = 'border-bottom:medium double black;'></td>
                            <td class = 'flx12' style = 'border-bottom:medium double black;'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>

                        </tr>
                        <tr class='flx2' style='height:19.52pt;'>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx2' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>

                        </tr>
                        <?php
                            $page_total = array(
                                'salary'=>0.00,
                                'utime_amt'=>0.00,
                                'pera_amt'=>0.00,
                                'sss_gsis_amt'=>0.00,
                                'sss_gsis_amt_employer'=>0.00,
                                'gsis_optional_ins'=>0.00,
                                'gsis_sdo_ca'=>0.00,
                                'gsis_enhanced'=>0.00,
                                'gsis_consolidated'=>0.00,
                                'gsis_ecard_plus'=>0.00,
                                'gsis_old_gsis'=>0.00,
                                'gsis_emergency'=>0.00,
                                'gsis_housing'=>0.00,
                                'gsis_educational'=>0.00,
                                'gsis_psmbfund'=>0.00,
                                'gsis_policy'=>0.00,
                                'gsis_sakamay'=>0.00,
                                'gsis_nhmfc'=>0.00,
                                'gsis_union_dues'=>0.00,
                                'gsis_koop_loan'=>0.00,
                                'gsis_lost_tvr'=>0.00,
                                'gsis_optional_loan'=>0.00,
                                'gsis_other_loans'=>0.00,
                                'pagibig_housing'=>0.00,
                                'pagibig_multipurpose'=>0.00,
                                'pagibig_calamity'=>0.00,
                                'gross_pay'=>0.00,
                                'net_pay'=>0.00,
                                'total_ot_amt'=>0.00,
                                'pagibig_amt'=>0.00,
                                'pagibig_amt_employer'=>0.00,
                                'philhealth_amt'=>0.00,
                                'wh_tax_amt'=>0.00,
                                'total_deduct_amt'=>0.00,
                                'cutoff_1'=>0.00,
                                'acpcea_amt'=>0.00,
                                'damayan_amt'=>0.00,
                                'loan_grocery'=>0.00,
                                'abst_amt'=>0.00,
                                'provident_amt'=>0.00

                            );
                        ?>
                        <!-- //Page Total -->
                    </table>
                </div>
                <!-- Loop More pges -->
                <?php 
                // var_dump($payroll);die();

                while(sizeof($payroll) > 0){ ?>
                <div class="page">
                        <?php

                        
                        // $count = 0;
                            
                        foreach ($payroll as $k1 => $v) {
                            $count+=1;
                            
                        ?>
                        <table class='flxmain_bordered_table' border='1' cellpadding='0' cellspacing='0' style='width:1477.46pt' summary="Excel Sheet: Sheet1">
                            <col class='flx0' style ='width:26.83pt;'>
                            <col class='flx0' style ='width:63.93pt;'>
                            <col class='flx0' style ='width:127.86pt;'>
                            <col class='flx0' style ='width:21.3pt;'>
                            <col class='flx0' style ='width:70.23pt;'>
                            <col class='flx0' style ='width:54.46pt;'>
                            <col class='flx0' style ='width:63.93pt;'>
                            <col class='flx0' style ='width:75.76pt;'>
                            <col class='flx0' style ='width:72.61pt;'>
                            <col class='flx0' style ='width:69.46pt;'>
                            <col class='flx0' style ='width:63.93pt;'>
                            <col class='flx0' style ='width:107.34pt;'>
                            <col class='flx0' style ='width:85.24pt;'>
                            <col class='flx0' style ='width:83.66pt;'>
                            <col class='flx0' style ='width:82.08pt;'>
                            <col class='flx0' style ='width:63.13pt;'>
                            <col class='flx0' style ='width:77.36pt;'>
                            <col class='flx0' style ='width:71.83pt;'>
                            <col class='flx0' style ='width:74.18pt;'>
                            <col class='flx0' style ='width:95.51pt;'>
                            <col class='flx0' style ='width:26.83pt;'>
                            <tr class='flx5' style='height:15.29pt;'>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>
                                <td class = 'flx5' style = 'border-top:1px none black;'></td>

                            </tr>
                            <tr class='flx2' style='height:16.75pt;'>
                                <td class='flx9'><?php echo $count; ?></td>
                                <td class='flx10' colspan = '3' rowspan ='1'>&nbsp;
                                    <?php 
                                        $employee_number = $this->Helper->decrypt($v['employee_number'],$v['employee_id']);
                                        $first_name = $this->Helper->decrypt($v['first_name'],$v['employee_id']);
                                        $last_name = $this->Helper->decrypt($v['last_name'],$v['employee_id']);
                                        $middle_name = $this->Helper->decrypt($v['middle_name'],$v['employee_id']);
                                        $employee_id_number = $this->Helper->decrypt($v['employee_id_number'],$v['employee_id']);
                                        $middle_initial = substr($middle_name, 0, 1);
                                    ?>
                                    <?php echo strtoupper($last_name.', '.$first_name.' '.$middle_initial.'.'); ?>
                                </td>
                                <!-- <td class = 'flx11'></td> -->

                                <td class='flx12'>
                                    <?php $grand_total['salary'] += $v['salary']; ?>
                                    <?php $page_total['salary'] += $v['salary']; ?>
                                    <?php echo number_format((double)@$v['salary'],2); ?>&nbsp;

                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['utime_amt'] += $v['utime_amt']; ?>
                                    <?php $page_total['utime_amt'] += $v['utime_amt']; ?>
                                    <?php echo number_format((double)@$v['utime_amt'],2); ?>&nbsp;
                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['pera_amt'] += $v['pera_amt']; ?>
                                    <?php $page_total['pera_amt'] += $v['pera_amt']; ?>
                                    <?php echo number_format((double)@$v['pera_amt'],2); ?>&nbsp;
                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['sss_gsis_amt'] += $v['sss_gsis_amt']; ?>
                                    <?php $page_total['sss_gsis_amt'] += $v['sss_gsis_amt']; ?>
                                    <?php echo number_format((double)@$v['sss_gsis_amt'],2); ?>&nbsp;
                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['sss_gsis_amt_employer'] += $v['sss_gsis_amt_employer']; ?>
                                    <?php $page_total['sss_gsis_amt_employer'] += $v['sss_gsis_amt_employer']; ?>
                                    <?php echo number_format((double)@$v['sss_gsis_amt_employer'],2); ?>&nbsp;
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Optional Ins."){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_optional_ins'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_optional_ins'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "S.D.O. CA"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_sdo_ca'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_sdo_ca'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Enhanced"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_enhanced'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_enhanced'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Consolidated"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_consolidated'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_consolidated'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "ECard Plus"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_ecard_plus'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_ecard_plus'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Emergency"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_emergency'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_emergency'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Old GSIS"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_old_gsis'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_old_gsis'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Educational"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_educational'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_educational'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx12'>
                                    <?php $grand_total['gross_pay'] += $v['gross_pay']; ?>
                                    <?php $page_total['gross_pay'] += $v['gross_pay']; ?>
                                    <?php echo number_format((double)@$v['gross_pay'],2); ?>
                                </td>
                                <td class='flx12'>
                                    <?php $grand_total['net_pay'] += $v['net_pay']; ?>
                                    <?php $page_total['net_pay'] += $v['net_pay']; ?>
                                    <?php echo number_format((double)@$v['net_pay'],2); ?>&nbsp;
                                </td>
                                <td class='flx14' style ='border-bottom:1px solid black;'><?php echo strtoupper(date('F, Y',strtotime(@$payroll_period[0]['start_date']))); ?></td>
                                <td class='flx15'><?php echo $count; ?></td>
                            </tr>
                            <tr class='flx2' style='height:18.21pt;'>
                                <td class = 'flx15'></td>

                                <td class='flx15' colspan = '2' rowspan ='1'><?php echo strtoupper(@$v['position_name']) ?></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx13'></td>

                                <td class='flx13'>
                                    <?php $grand_total['total_ot_amt'] += $v['total_ot_amt']; ?>
                                    <?php $page_total['total_ot_amt'] += $v['total_ot_amt']; ?>
                                    <?php echo $v['total_ot_amt']; ?>&nbsp;
                                </td>
                                <td class='flx13'>0.00&nbsp;</td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Housing"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_housing'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_housing'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Optional Loan"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_optional_loan'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_optional_loan'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Policy Loan"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_policy'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_policy'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['pagibig_amt'] += $v['pagibig_amt']; ?>
                                    <?php $page_total['pagibig_amt'] += $v['pagibig_amt']; ?>
                                    <?php echo number_format((double)@$v['pagibig_amt'],2); ?>&nbsp;
                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['pagibig_amt_employer'] += $v['pagibig_amt_employer']; ?>
                                    <?php $page_total['pagibig_amt_employer'] += $v['pagibig_amt_employer']; ?>
                                    <?php echo number_format((double)@$v['pagibig_amt_employer'],2); ?>&nbsp;
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "PAG-IBIG" && $vl1['code_sub'] == "Housing"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['pagibig_housing'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['pagibig_housing'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "PAG-IBIG" && $vl1['code_sub'] == "Multi-Purpose"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['pagibig_multipurpose'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['pagibig_multipurpose'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['philhealth_amt'] += $v['philhealth_amt']; ?>
                                    <?php $page_total['philhealth_amt'] += $v['philhealth_amt']; ?>
                                    <?php echo number_format((double)@$v['philhealth_amt'],2); ?>&nbsp;
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    /*$loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "UNION DUES"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }*/
                                    $loan = number_format(@$v['union_dues_amt'],2);
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_union_dues'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_union_dues'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['wh_tax_amt'] += $v['wh_tax_amt']; ?>
                                    <?php $page_total['wh_tax_amt'] += $v['wh_tax_amt']; ?>
                                    <?php echo number_format((double)@$v['wh_tax_amt'],2); ?>&nbsp;
                                </td>
                                <td class='flx12'> 
                                    <?php $grand_total['total_deduct_amt'] += $v['total_deduct_amt']; ?>
                                    <?php $page_total['total_deduct_amt'] += $v['total_deduct_amt']; ?>
                                    <?php echo number_format((double)@$v['total_deduct_amt'],2); ?>&nbsp;
                                </td>
                                <td class ='flx12'>
                                    <?php $grand_total['cutoff_1'] += $v['cutoff_1']; ?>
                                    <?php $page_total['cutoff_1'] += $v['cutoff_1']; ?>
                                    <?php echo number_format((double)@$v['cutoff_1'],2); ?>&nbsp;
                                </td>
                                <td class = 'flx2' style = 'border-top:1px none black;'></td>
                                <td class = 'flx15'></td>

                            </tr>
                            <tr class='flx2' style='height:16.75pt;'>
                                <td class = 'flx15'></td>
                                <td class = 'flx16'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx13'>
                                    <?php $grand_total['damayan_amt'] += $v['damayan_amt']; ?>
                                    <?php $page_total['damayan_amt'] += $v['damayan_amt']; ?>
                                    <?php echo number_format((double)@$v['damayan_amt'],2); ?>&nbsp;
                                </td>

                                <td class='flx13'>
                                    <?php $grand_total['acpcea_amt'] += $v['acpcea_amt']; ?>
                                    <?php $page_total['acpcea_amt'] += $v['acpcea_amt']; ?>
                                    <?php echo number_format((double)@$v['acpcea_amt'],2); ?>&nbsp;
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "Other Loans" && $vl1['code_sub'] == "Sakamay"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_sakamay'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_sakamay'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "Other Loans" && $vl1['code_sub'] == "PSMBFund"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_psmbfund'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_psmbfund'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "Other Loans" && $vl1['code_sub'] == "KOOP"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_koop_loan'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_koop_loan'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "Other Loans" && $vl1['code_sub'] == "Grocery"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['loan_grocery'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['loan_grocery'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "PAG-IBIG" && $vl1['code_sub'] == "Calamity"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['pagibig_calamity'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['pagibig_calamity'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "Other Loans" && $vl1['code_sub'] == "NHMFC"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_nhmfc'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_nhmfc'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Lost TVR"){
                                                $loan = number_format($vl1['amount'],2);
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    <?php $grand_total['gsis_lost_tvr'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_lost_tvr'] += (double) str_replace( ',', '', $loan ); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                        $loan = "0.00";
                                        if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                            foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                                if($vl1['code_loan'] == "Other Loans" && $vl1['code_sub'] == "Provident"){
                                                    $loan = number_format($vl1['amount'],2);
                                                    break;
                                                }
                                                else
                                                    $loan = "0.00";

                                            }
                                        }
                                        //echo $loan;
                                    ?>
                                    <?php echo number_format((double)(@$v['provident_amt'] + $loan),2); ?>&nbsp;
                                    <?php $grand_total['provident_amt'] += (@$v['provident_amt'] + $loan); ?>
                                    <?php $page_total['provident_amt'] += (@$v['provident_amt'] + $loan); ?>
                                </td>
                                <td class='flx13'>
                                    <?php 
                                    $loan = "0.00";
                                    if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                        foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                            if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Other Loans"){
                                                $loan = number_format($vl1['amount'],2);
                                                // var_dump($loan);die();
                                                break;
                                            }
                                            else
                                                $loan = "0.00";

                                        }
                                    }
                                    echo $loan.'&nbsp';
                                    ?>
                                    
                                </td>
                                <td class='flx13'>
                                    <?php $grand_total['gsis_other_loans'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $page_total['gsis_other_loans'] += (double) str_replace( ',', '', $loan ); ?>
                                    <?php $grand_total['abst_amt'] += $v['abst_amt']; ?>
                                    <?php $page_total['abst_amt'] += $v['abst_amt']; ?>
                                    <?php echo number_format((double)@$v['abst_amt'],2); ?>&nbsp;
                                </td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx15'></td>

                            </tr>
                            <tr class='flx2' style='height:15.29pt;'>
                                <td class = 'flx15'></td>
                                <td class = 'flx16'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx13'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx2'></td>
                                <td class = 'flx15'></td>

                            </tr>
                        <?php 
                                array_shift($payroll);
                                if($k1 == 10){
                                    break;
                                }
                            }
                        ?>
                        <!-- // Employees -->

                        <tr class='flx2' style='height:15.29pt;'>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>

                        </tr>
                        <!-- Page Total -->
                        <tr class='flx2' style='height:19.52pt;'>
                            <td class = 'flx2'></td>

                            <td class='flx10' colspan = '2' rowspan ='1'>PAGE TOTAL:</td>
                            <td class = 'flx2'></td>

                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['salary'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['utime_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['pera_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['sss_gsis_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['sss_gsis_amt_employer'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gsis_optional_ins'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gsis_sdo_ca'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gsis_enhanced'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gsis_consolidated'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gsis_ecard_plus'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gsis_emergency'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gsis_old_gsis'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gsis_educational'],2); ?>&nbsp; 
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['gross_pay'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$page_total['net_pay'],2); ?>&nbsp;
                            </td>
                            <td class = 'flx12'></td>
                            <td class = 'flx12'></td>

                        </tr>
                        <tr class='flx2' style='height:19.52pt;'>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>

                            <td class='flx12'>
                            <?php echo number_format((double)@$page_total['total_ot_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>0.00&nbsp;</td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['gsis_housing'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['gsis_optional_loan'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                            <?php echo number_format((double)@$page_total['gsis_policy'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['pagibig_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['pagibig_amt_employer'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['pagibig_housing'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['pagibig_multipurpose'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['philhealth_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['gsis_union_dues'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['wh_tax_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$page_total['total_deduct_amt'],2); ?>&nbsp;
                            </td>
                            <td class = 'flx12'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>

                        </tr>
                        <tr class='flx2' style='height:19.52pt;'>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx12' style = 'border-bottom:medium double black;'></td>
                            <td class = 'flx12' style = 'border-bottom:medium double black;'><?php echo number_format((double)@$page_total['damayan_amt'],2); ?>&nbsp;</td>

                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['acpcea_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['gsis_sakamay'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['gsis_psmbfund'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['gsis_koop_loan'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['loan_grocery'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['pagibig_calamity'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['gsis_nhmfc'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                            <?php echo number_format((double)@$page_total['gsis_lost_tvr'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['provident_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['gsis_other_loans'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$page_total['abst_amt'],2); ?>&nbsp;
                            </td>
                            <td class = 'flx12' style = 'border-bottom:medium double black;'></td>
                            <td class = 'flx12' style = 'border-bottom:medium double black;'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>

                        </tr>
                        <tr class='flx2' style='height:19.52pt;'>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx2' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>

                        </tr>
                        <?php
                            $page_total = array(
                                'salary'=>0.00,
                                'utime_amt'=>0.00,
                                'pera_amt'=>0.00,
                                'sss_gsis_amt'=>0.00,
                                'sss_gsis_amt_employer'=>0.00,
                                'gsis_optional_ins'=>0.00,
                                'gsis_sdo_ca'=>0.00,
                                'gsis_enhanced'=>0.00,
                                'gsis_consolidated'=>0.00,
                                'gsis_ecard_plus'=>0.00,
                                'gsis_old_gsis'=>0.00,
                                'gsis_emergency'=>0.00,
                                'gsis_housing'=>0.00,
                                'gsis_educational'=>0.00,
                                'gsis_psmbfund'=>0.00,
                                'gsis_policy'=>0.00,
                                'gsis_sakamay'=>0.00,
                                'gsis_nhmfc'=>0.00,
                                'gsis_union_dues'=>0.00,
                                'gsis_koop_loan'=>0.00,
                                'gsis_lost_tvr'=>0.00,
                                'gsis_optional_loan'=>0.00,
                                'gsis_other_loans'=>0.00,
                                'pagibig_housing'=>0.00,
                                'pagibig_multipurpose'=>0.00,
                                'pagibig_calamity'=>0.00,
                                'gross_pay'=>0.00,
                                'net_pay'=>0.00,
                                'total_ot_amt'=>0.00,
                                'pagibig_amt'=>0.00,
                                'pagibig_amt_employer'=>0.00,
                                'philhealth_amt'=>0.00,
                                'wh_tax_amt'=>0.00,
                                'total_deduct_amt'=>0.00,
                                'cutoff_1'=>0.00,
                                'acpcea_amt'=>0.00,
                                'damayan_amt'=>0.00,
                                'loan_grocery'=>0.00,
                                'abst_amt'=>0.00,
                                'provident_amt'=>0.00

                            );
                        ?>
                        <!-- //Page Total -->
                    </table>
                </div>
                <?php } ?>
                <!-- End loop more page -->
                <div class="page"> 
                    <table class='flxmain_bordered_table' border='1' cellpadding='0' cellspacing='0' style='width:1477.46pt;' summary="Excel Sheet: Sheet1">
                        <col class='flx0' style ='width:26.83pt;'>
                        <col class='flx0' style ='width:63.93pt;'>
                        <col class='flx0' style ='width:127.86pt;'>
                        <col class='flx0' style ='width:21.3pt;'>
                        <col class='flx0' style ='width:70.23pt;'>
                        <col class='flx0' style ='width:54.46pt;'>
                        <col class='flx0' style ='width:63.93pt;'>
                        <col class='flx0' style ='width:75.76pt;'>
                        <col class='flx0' style ='width:72.61pt;'>
                        <col class='flx0' style ='width:69.46pt;'>
                        <col class='flx0' style ='width:63.93pt;'>
                        <col class='flx0' style ='width:107.34pt;'>
                        <col class='flx0' style ='width:85.24pt;'>
                        <col class='flx0' style ='width:83.66pt;'>
                        <col class='flx0' style ='width:82.08pt;'>
                        <col class='flx0' style ='width:63.13pt;'>
                        <col class='flx0' style ='width:77.36pt;'>
                        <col class='flx0' style ='width:71.83pt;'>
                        <col class='flx0' style ='width:74.18pt;'>
                        <col class='flx0' style ='width:95.51pt;'>
                        <col class='flx0' style ='width:26.83pt;'>
                        <!-- Grand Total -->
                        <tr class='flx2' style='height:19.52pt;'>
                            <td class = 'flx2'></td>

                            <td class='flx10' colspan = '2' rowspan ='1'><b>GRAND TOTAL:</b></td>
                            <td class = 'flx2'></td>

                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$grand_total['salary'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$grand_total['utime_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$grand_total['pera_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$grand_total['sss_gsis_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$grand_total['sss_gsis_amt_employer'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$grand_total['gsis_optional_ins'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$grand_total['gsis_sdo_ca'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$grand_total['gsis_enhanced'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$grand_total['gsis_consolidated'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$grand_total['gsis_ecard_plus'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$grand_total['gsis_emergency'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$grand_total['gsis_old_gsis'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$grand_total['gsis_educational'],2); ?>&nbsp; 
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$grand_total['gross_pay'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-top:1px none black;'>
                                <?php echo number_format((double)@$grand_total['net_pay'],2); ?>&nbsp;
                            </td>
                            <td class = 'flx12'></td>
                            <td class = 'flx12'></td>

                        </tr>
                        <tr class='flx2' style='height:19.52pt;'>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>

                            <td class='flx12'>
                            <?php echo number_format((double)@$grand_total['total_ot_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>0.00&nbsp;</td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$grand_total['gsis_housing'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$grand_total['gsis_optional_loan'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                            <?php echo number_format((double)@$grand_total['gsis_policy'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$grand_total['pagibig_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$grand_total['pagibig_amt_employer'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$grand_total['pagibig_housing'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$grand_total['pagibig_multipurpose'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$grand_total['philhealth_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$grand_total['gsis_union_dues'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$grand_total['wh_tax_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12'>
                                <?php echo number_format((double)@$grand_total['total_deduct_amt'],2); ?>&nbsp;
                            </td>
                            <td class = 'flx12'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>

                        </tr>
                        <tr class='flx2' style='height:19.52pt;'>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx12' style = 'border-bottom:medium double black;'></td>
                            <td class = 'flx12' style = 'border-bottom:medium double black;'><?php echo number_format((double)@$grand_total['damayan_amt'],2); ?>&nbsp;</td>

                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$grand_total['acpcea_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$grand_total['gsis_sakamay'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$grand_total['gsis_psmbfund'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$grand_total['gsis_koop_loan'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$grand_total['loan_grocery'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$grand_total['pagibig_calamity'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$grand_total['gsis_nhmfc'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                            <?php echo number_format((double)@$grand_total['gsis_lost_tvr'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                            <?php echo number_format((double)@$grand_total['provident_amt'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$grand_total['gsis_other_loans'],2); ?>&nbsp;
                            </td>
                            <td class='flx12' style ='border-bottom:medium double black;'>
                                <?php echo number_format((double)@$grand_total['abst_amt'],2); ?>&nbsp;
                            </td>
                            <td class = 'flx12' style = 'border-bottom:medium double black;'></td>
                            <td class = 'flx12' style = 'border-bottom:medium double black;'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>

                        </tr>
                        <tr class='flx2' style='height:19.52pt;'>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx2' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx12' style = 'border-top:1px none black;'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>

                        </tr>
                        <!-- //Grand Total -->
                        <tr class='flx2' style='height:19.52pt;'>
                            <td class = 'flx2'></td>

                            <td class='flx3' colspan = '2' rowspan ='1'>CETIFICATION:</td>
                            <td class = 'flx3'></td>
                            <td class = 'flx17'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx17'></td>
                            <td class = 'flx17'></td>
                            <td class = 'flx17'></td>
                            <td class = 'flx12'></td>
                            <td class = 'flx12'></td>
                            <td class = 'flx12'></td>

                            <td class='flx3' colspan = '2' rowspan ='1'>CETIFICATION:</td>
                            <td class = 'flx12'></td>
                            <td class = 'flx12'></td>
                            <td class = 'flx12'></td>
                            <td class = 'flx12'></td>
                            <td class = 'flx12'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>

                        </tr>
                        <tr class='flx2' style='height:19.52pt;'>
                            <td class = 'flx2'></td>

                            <td class='flx3' colspan = '7'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that the name listed in this payroll are bonafide employee of</td>
                            <td class = 'flx17'></td>
                            <td class = 'flx12'></td>
                            <td class = 'flx12'></td>
                            <td class = 'flx12'></td>

                            <td class='flx3' colspan = '8'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that the name contained in this payroll is not included in the payroll prepared for the period</td>
                            <td class = 'flx2'></td>

                        </tr>
                        <tr class='flx2' style='height:18.21pt;'>
                            <td class = 'flx2'></td>

                            <td class='flx3' colspan = '7'>this Authority. This is to certify further that the amountcorresponding to the leave</td>
                            <td class = 'flx3'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx2'></td>

                            <td class='flx3' colspan = '8'><b><?php echo date('F d',strtotime(@$payroll_period[0]['start_date'])); ?></b> to <b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['end_date'])); ?></b>&nbsp;</span>nor he/she included in the transmittal list submitted to PNB/PVB payroll&nbsp;</td>
                            <td class = 'flx2'></td>

                        </tr>
                        <tr class='flx5' style='height:18.21pt;'>
                            <td class = 'flx18'></td>

                            <td class='flx3' colspan = '7'>absences, tardiness, halfdays and undertime incurred without pay during the current&nbsp;</td>
                            <td class = 'flx20'></td>
                            <td class = 'flx21'></td>
                            <td class = 'flx21'></td>
                            <td class = 'flx21'></td>

                            <td class='flx3'>service.</td>
                            <td class = 'flx21'></td>
                            <td class = 'flx21'></td>
                            <td class = 'flx21'></td>
                            <td class = 'flx21'></td>
                            <td class = 'flx21'></td>
                            <td class = 'flx22'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>

                        </tr>
                        <tr class='flx3' style='height:18.21pt;'>
                            <td class = 'flx3'></td>

                            <td class='flx3' colspan = '7'>and or pay period are deducted accordingly, except those with HOLD marks.</td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                            <td class='flx3' colspan = '7'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued in support of the claim of the aforementioned employee.</td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                        </tr>
                        <tr class='flx3' style='height:18.21pt;'>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                        </tr>
                        <tr class='flx3' style='height:18.21pt;'>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx4'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx23'></td>

                            <td class='flx24' colspan = '2' rowspan ='1'></td>
                        </tr>
                        <tr class='flx3' style='height:18.21pt;'>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                            <td class='flx4' colspan = '2'><?php echo @$signatories[3]['signatory']; ?></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx25'></td>

                            <td class='flx26' colspan = '2' rowspan ='1'></td>
                        </tr>
                        <tr class='flx3' style='height:20.39pt;'>
                            <td class = 'flx3'></td>
                            <td class = 'flx2'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                            <td class='flx27' colspan = '3'style="text-align:left;"><?php echo @$signatories[3]['employee_id']; ?>&nbsp;</td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                        </tr>
                        <tr class='flx3' style='height:18.21pt;'>
                            <td class = 'flx3'></td>
                            <td class = 'flx2' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>

                        </tr>
                        <tr class='flx3' style='height:18.21pt;'>
                            <td class = 'flx3'></td>

                            <td class='flx3' style ='border-top:1px none black;' colspan = '7'>(1) I CERTIFY on my official oath that the above paryoll is correct and the</td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                            <td class='flx3' style ='border-top:1px none black;' colspan = '8'>(3) &nbsp;I CERTIFY on my official oath that I have paid each employee whose name appears on the above payroll</td>
                            <td class = 'flx3' style = 'border-top:1px none black;'></td>

                        </tr>
                        <tr class='flx3' style='height:21.12pt;'>
                            <td class = 'flx3'></td>

                            <td class='flx3' colspan = '4'>service have been duly rendered as stated.</td>
                            <td class = 'flx27'></td>
                            <td class = 'flx27'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                            <td class='flx3' colspan = '7'>the amount set opposite his/her name, he/she having presented his/her Residence Certificate.&nbsp;</td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                        </tr>
                        <tr class='flx3' style='height:18.21pt;'>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx27'></td>
                            <td class = 'flx27'></td>
                            <td class = 'flx27'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                        </tr>
                        <tr class='flx3' style='height:18.21pt;'>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx27'></td>
                            <td class = 'flx27'></td>
                            <td class = 'flx27'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                        </tr>
                        <tr class='flx3' style='height:18.21pt;'>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                            <td class='flx4' colspan = '5'>
                                <?php echo @$signatories[0]['signatory']; ?>
                                &nbsp;
                            </td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                        </tr>
                        <tr class='flx3' style='height:21.85pt;'>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                            <td class='flx27' colspan = '4'>
                                &nbsp;<?php echo @$signatories[0]['employee_id']; ?>
                            </td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                        </tr>
                        <tr class='flx3' style='height:18.21pt;'>
                            <td class = 'flx3'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx27' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>
                            <td class = 'flx3' style = 'border-bottom:1px solid black;'></td>

                        </tr>
                        <tr class='flx3' style='height:18.21pt;'>
                            <td class = 'flx3'></td>

                            <td class='flx3' style ='border-top:1px none black;' colspan = '5'>(2) &nbsp;APPROVED, payable from appropriation for;</td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                            <td class='flx3' colspan = '2'>APPROVED:</td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                            <td class='flx3' style ='border-top:1px none black;' colspan = '8'>(4) &nbsp;I CERTIFY on my official oath that I have witnessed payment to each person, whose name appears hereon</td>
                            <td class = 'flx3' style = 'border-top:1px none black;'></td>

                        </tr>
                        <tr class='flx3' style='height:18.21pt;'>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                            <td class='flx3' colspan = '4'>BY AUTHORITY OF THE &nbsp;CHAIRMAN</td>
                            <td class='flx3' colspan = '5'>of the amount set opposite his/her name and my initials.</td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                        </tr>
                        <tr class='flx3' style='height:18.21pt;'>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx28'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                        </tr>
                        <tr class='flx3' style='height:18.21pt;'>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                            <td class='flx4' colspan = '4' rowspan ='1'>
                                <?php echo @$signatories[1]['signatory']; ?>
                            </td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                        </tr>
                        <tr class='flx3' style='height:18.21pt;'>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                            <td class='flx27' colspan = '4' rowspan ='1'>
                                <?php echo @$signatories[1]['employee_id']; ?>
                            </td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                            <td class='flx28' colspan = '3'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo @$signatories[2]['signatory']; ?></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                        </tr>
                        <tr class='flx3' style='height:18.21pt;'>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                            <td class='flx27' colspan = '5' rowspan ='1'></td>
                            <td class = 'flx3'></td>

                            <td class='flx3' colspan = '3'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo @$signatories[2]['employee_id']; ?></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                        </tr>
                        <tr class='flx3' style='height:18.21pt;'>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>
                            <td class = 'flx3'></td>

                        </tr>
                        <tr class='flx5' style='height:15.29pt;'>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>

                        </tr>
                        <tr class='flx5' style='height:12.38pt;'>
                            <td class = 'flx16'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>

                            <td class='flx29' colspan = '6' rowspan ='2'><div class='flx29' style ='height:27.67pt;'></div></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>

                        </tr>
                        <tr class='flx5' style='height:15.29pt;'>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>

                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>

                        </tr>
                        <tr class='flx5' style='height:15.29pt;'>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx30'></td>
                            <td class = 'flx30'></td>
                            <td class = 'flx30'></td>
                            <td class = 'flx30'></td>
                            <td class = 'flx30'></td>
                            <td class = 'flx30'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>

                        </tr>
                        <tr class='flx5' style='height:15.29pt;'>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>

                            <td class='flx29' colspan = '6' rowspan ='3'></td>
                            <td class = 'flx9'></td>
                            <td class = 'flx9'></td>
                            <td class = 'flx9'></td>
                            <td class = 'flx9'></td>

                        </tr>
                        <tr class='flx5' style='height:15.29pt;'>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>

                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>

                        </tr>
                        <tr class='flx5' style='height:15.29pt;'>
                            <td class = 'flx16'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx16'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>

                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>
                            <td class = 'flx5'></td>

                        </tr>
                    </table>
                </div>
                <?php endif; ?>
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
