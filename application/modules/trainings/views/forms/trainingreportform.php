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

            @media only screen and (min-width: 992px) {
                #logo {
                    height: auto;
                    width: 8%;
                    /* margin-top: -8px; */
                    margin-left: 175px;
                    position: absolute;
                }
            }
            /*PRINT PREVIEW*/
            @media only print {
                #logo {
                    height: auto;
                    width: 8%;
                    margin-left: -190px;
                    /* margin-top: -7px; */
                    position: absolute;
                }
            }
    </style>
    <?php if($data["key"] === "formContainer"):?>
    <div id="form-container">
    <?php endif; ?>
    <?php if($data["key"] === "viewTrainingReport" || $data["key"] === "viewTrainingReportSummary"): ?>
    <div class="content <?php echo ($queue%2 !== 0)?"break":""; ?>" style="margin-top: 1em">
        <table width="100%" cellpadding="0" cellspacing="0" class="content" align="center" style="border-top: 1px solid black;">
            <thead>
            <tr>
                <th colspan="4">&nbsp;</th>
            </tr>
            <tr rowspan="4">
                <th colspan="4">
                    <img src="<?php echo base_url()."assets/custom/images/nwrb.png"; ?>" id="logo">
                </th>
            </tr>
            <tr>
                <th colspan="4" class="bold aligncenter">Republic of the Philippines</th>
            </tr>
            <tr>
                <th colspan="4" class="bold aligncenter">NATIONAL WATER RESOURCES BOARD</th>
            </tr>
            <tr>
                <th colspan="4" class="bold aligncenter">8th Floor NIA Bldg., EDSA, Quezon City</th>
            </tr>
            <tr>
                <th colspan="4">&nbsp;</th>
            </tr>
            <tr>
                <th colspan="4" class="bold aligncenter brtop">TRAINING REPORT</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" style="padding: 0;font-size: 5px;">&nbsp;</td>
                </tr>
                <tr>
                    <td >Name:</td>
                    <td colspan="3" class="bold "><?php echo $data["employee"]["first_name"]." ".$data["employee"]["middle_name"] ." ". $data["employee"]["last_name"] ?></td>
                </tr>
                <tr>
                    <td >Position:</td>
                    <td colspan="3" class=""><?php echo $data["employee"]["position_name"]; ?></td>
                </tr>
                <tr>
                    <td >Division:</td>
                    <td colspan="3" class=""><?php echo (isset($data["employee"]["branch"]))?$data["employee"]["branch"]:""; ?></td>
                </tr>
                <tr>
                    <td width="25%">No. of Trainings Attended:</td>
                    <td colspan="3" class=""><?php echo (isset($data["trainings"]))?count($data["trainings"]):0; ?></td>
                </tr>
                <tr>
                    <td colspan="4" style="padding: 0;font-size: 5px;">&nbsp;</td>
                </tr>
            </tbody>
        </table>
        <table  width="100%" cellpadding="0" cellspacing="0" class="content" align="center" style="border-top: 0px;">
            <thead>
                <tr>
                    <th colspan="9" class="bold aligncenter brtop">TRAININGS ATTENDED</th>
                </tr>
                <tr>
                    <th style="border-top: 1px solid black; padding: 2px; text-align: center;">Title of Learning And Development Interventions / Training Programs</th>
                    <th style="border-top: 1px solid black; padding: 2px; text-align: center;">Conducted / Sponsored By</th>
                    <th style="border-top: 1px solid black; padding: 2px; text-align: center;">Venue</th>
                    <th style="border-top: 1px solid black; padding: 2px; text-align: center;">Start Date</th>
                    <th style="border-top: 1px solid black; padding: 2px; text-align: center;">End Date</th>
                    <th style="border-top: 1px solid black; padding: 2px; text-align: center;">Fees</th>
                    <th style="border-top: 1px solid black; padding: 2px; text-align: center;">Travel Order</th>
                    <th style="border-top: 1px solid black; padding: 2px; text-align: center;">Office Order</th>
                    <th style="border-top: 1px solid black; padding: 2px; text-align: center;">Travel Allowance</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($data["trainings"]) && $data["trainings"]): ?>
                    <?php foreach ($data["trainings"] as $k => $v) : ?>
                        <tr>
                            <td><?php echo $v['title']?></td>
                            <td><?php echo $v['sponsor']?></td>
                            <td><?php echo $v['venue']?></td>
                            <td><?php echo $v['start_date']?></td>
                            <td><?php echo $v['end_date']?></td>
                            <td align="right"><?php echo number_format((float)$v['fees'], 2, '.', '')?></td>
                            <td><?php echo $v['travel_order']?></td>
                            <td><?php echo $v['office_order']?></td>
                            <td align="right"><?php echo number_format((float)$v['travel_allowance'], 2, '.', '');?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tablesig" align="center">
            <tr>
                <td><h6>Certified Correct:</h6></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td width="40%" class="aligncenter" style="border-bottom: 1px solid black;">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <div class="break"></div>
    </div>
    <?php endif; ?>
    <?php if($data["key"] === "formContainer"):?>
    </div>
    <?php endif; ?>
</div>