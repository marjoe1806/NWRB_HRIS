<?php if($Data["key"] !== "viewLeaveCreditSummary"):?>   
<div class = "container-fluid" id="printPreview" style="pointer-events: none;">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css" media="all">
	        .break{
	            page-break-before: always;
	        }
            table.content tr td{
                padding: 5px 3px 5px;
                color: #000;
                font-size: 11px;
                font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif;
                border: 1px solid black;
            }
            table.content thead{
                border-top: 1px solid black;
                border-bottom: 1px solid black;
            }
            table.content tr th{
                color: #000;
                font-size: 11px;
                font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif;
                padding: 0px;
                border-left: 1px solid black;
                border-right: 1px solid black;
            }
            table.content tr th.brtop{
                border-top: 1px solid black;
            }
            table.tablesig tr td{
                border: none;
                padding: 0px;
            }
            .hdfont{
                font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif;
            }
            .aligncenter{
                text-align: center;
            }
            .bold{
                font-weight: bold;
            }
    </style>
    <?php //if($Data["key"] === "formContainer"):?>
    <div id="form-container">
    <?php endif; ?>
        <?php if($Data["key"] === "viewLeaveCredit" || $Data["key"] === "viewLeaveCreditSummary"): ?>
        <div class="content <?php echo ($queue%2 !== 0)?"break":""; ?>" style="margin-top: 5em">
            <table width="85%" cellpadding="0" cellspacing="0" class="content" align="center" style="border-top: 1px solid black;">
                <thead>
                <tr rowspan="4">
                    <th colspan="4">
                        <!-- <img src="<?php echo base_url()."assets/custom/images/nwrb.png"; ?>" id="logo"> -->
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border: 0;">
                        <tr>
                            <td width="30%" style="text-align: right;border: 0;">
                            <img width="20%" src="<?php echo base_url()."assets/custom/images/nwrb.png"; ?>" id="logo">
                            </td>
                            <td width="40%" style="text-align: center; border: 0;">
                            Republic of the Philippines<br>
                            NATIONAL WATER RESOURCES BOARD<br>
                            8th Floor NIA Bldg., EDSA, Quezon City
                            </td>
                            <td width="30%" style="border: 0;"></td>
                        </tr>
                        </table>
                    </th>
                </tr>
                <tr>
                    <th colspan="4" class="bold aligncenter brtop">CERTIFICATE OF LEAVE CREDITS</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" style="padding: 0;font-size: 5px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td >Name:</td>
                        <td colspan="3" class="bold "><?php echo $Data["employee"]["first_name"]." ".$Data["employee"]["middle_name"] ." ". $Data["employee"]["last_name"] ?></td>
                    </tr>
                    <tr>
                        <td >Position:</td>
                        <td colspan="3" class=""><?php echo $Data["employee"]["position_name"]; ?></td>
                    </tr>
                    <tr>
                        <td >Division:</td>
                        <td colspan="3" class=""><?php echo (isset($Data["employee"]["branch"]))?$Data["employee"]["branch"]:""; ?></td>
                    </tr>
                    <tr>
                        <td width="25%">Balance as of:</td>
                        <td width="20%" class="bold aligncenter">VL</td>
                        <td width="20%" class="bold aligncenter">SL</td>
                        <td width="35%" class="bold aligncenter">TOTAL</td>
                    </tr>
                    <tr>
                        <td class="bold aligncenter"><?php echo ($Data["employee"]["period"])?@$Data["employee"]["period"]:date('F d, Y'); ?></td>
                        <td class="aligncenter"><?php echo ($Data["employee"]["vl"])?number_format(@$Data["employee"]["vl"],3):"0"; ?></td>
                        <td class="aligncenter"><?php echo ($Data["employee"]["sl"])?number_format(@$Data["employee"]["sl"],3):"0"; ?></td>
                        <td class="bold aligncenter"><?php echo (isset($Data["employee"]["leaveCreditsTotal"]))?number_format($Data["employee"]["leaveCreditsTotal"],2):""; ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="padding: 0;font-size: 5px;">&nbsp;</td>
                    </tr>
                    <tr><td colspan="4" style="padding: 0px;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tablesig" align="center">
                            <tr>
                                <td width="30%">&nbsp;</td>
                                <td>Certified Correct:</td>
                                <td width="30%">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="30%">&nbsp;</td>
                                <td width="40%" class="aligncenter" style="border-bottom: 1px solid black;">&nbsp;</td>
                                <td width="30%">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td class="aligncenter" style="font-weight: bold;">&nbsp;
                                    <?php 
                                        if ($Data["employee"]["first_name"] != 'JESUSA' && $Data["employee"]["last_name"] != 'DELOS SANTOS' && $Data["employee"]["middle_name"] != 'TENORIO'){
                                            echo $signatories[0]["employee_name"]; //name 
                                        }else{
                                            echo $signatories[1]["employee_name"]; //name 
                                        }
                                    ?>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td class="aligncenter">
                                    <?php 
                                        if ($Data["employee"]["first_name"] != 'JESUSA' && $Data["employee"]["last_name"] != 'DELOS SANTOS' && $Data["employee"]["middle_name"] != 'TENORIO'){
                                            echo $signatories[0]["position_designation"] != "" || $signatories[0]["position_designation"] != null ? $signatories[0]["position_designation"] : $signatories[0]["position_title"]; //position 
                                        }else{
                                            echo $signatories[1]["position_designation"] != "" || $signatories[1]["position_designation"] != null ? $signatories[1]["position_designation"] : $signatories[1]["position_title"]; //position 
                                        }
                                    ?>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                    </td></tr>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    <?php if($Data["key"] === "viewLeaveCreditSummary"):?>
    </div>
    <?php //endif; ?>
</div>
<?php endif; ?>