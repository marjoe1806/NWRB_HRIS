    
    <div class = "container-fluid" id="printPreview" style="pointer-events: none;">
    <style type="text/css" media="all">
	        .break{
	            page-break-before: always;
	        }
            table tr td{
                padding-left: 3px;
                color: #000;
                font-size: 12px;
            }
            img{
                width: 90px;
                height: 90px;
            }
            .hdfont{
                font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif;
            }
            .aligncenter{
                text-align: center;
            }
            .tdheader{
                text-align: center;
                font-weight: bold;
            }
            /* .header {
                position: fixed;
                top: 0;
                width: 100%;
            } */
            .content{
                height: 700px;
            }
            .footer {
                position: fixed;
                bottom: 0;
                width: 100%;
            }
    </style>
    <div>
        <div class="header">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
                <tr>
                    <td width="50%" style="text-align: right"><img src="<?php echo base_url()."assets/custom/images/coap.png"; ?>" ></td>
                    <td width="50%" style="text-align: left"><img src="<?php echo base_url()."assets/custom/images/nwrb.jpg"; ?>" ></td>
                </tr>
                <tr>
                    <td colspan="2" class="aligncenter hdfont" style="font-size: 17px;"><span style="font-size:21px;">R</span>EPUBLIC OF THE <span style="font-size:21px;">P</span>HILIPPINES</td>
                </tr>
                <tr>
                    <td colspan="2"><hr style="margin: 0px;"></td>
                </tr>
                <tr>
                    <td colspan="2" class="aligncenter hdfont" style="font-size: 24px;"><span style="font-size:28px;">N</span>ATIONAL <span style="font-size:28px;">W</span>ATER <span style="font-size:28px;">R</span>ESOURCES <span style="font-size:28px;">B</span>OARD</td>
                </tr>
            </table>
        </div>
        <div class="content" style="margin-top: 3em">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
                <tr>
                    <td colspan="2">Service Record of:</td>
                    <td colspan="5"><?php echo $Data["employee"]["last_name"] . ((isset($Data["employee"]["extension"]) && $Data["employee"]["extension"] != "")?" ".$Data["employee"]["extension"]:"") . ", " .$Data["employee"]["first_name"]." ".$Data["employee"]["middle_name"]; ?></td>
                </tr>
                <tr>
                    <td>Birth:</td>
                    <td><?php echo $Data["employee"]["birthday"]; ?></td>
                    <td style="text-align: right;">Birthplace:</td>
                    <td colspan="4"><?php echo $Data["employee"]["birth_place"]; ?></td>
                </tr>
                <tr>
                    <td colspan="7" style="text-align: justify;">This is to certify that the employee named hereinabove actually rendered services in this Office as shown by the service record below, each line of which is supported by appointment and other papers actually issued by this Office and approved by the authorities concerned.</td>
                </tr>
                <tr>
                    <td style="width: 70px;">&nbsp;</td>
                    <td style="width: 120px;">&nbsp;</td>
                    <td style="width: 160px;">&nbsp;</td>
                    <td style="width: 160px;">&nbsp;</td>
                    <td style="width: 160px;">&nbsp;</td>
                    <td style="width: 160px;">&nbsp;</td>
                    <td style="width: 160px;">&nbsp;</td>
                </tr>
                <tr>
                    <td class="aligncenter" colspan="2">Inclusive Date</td>
                    <td class="aligncenter">Designation</td>
                    <td class="aligncenter">Agency</td>
                    <td class="aligncenter">Salary</td>
                    <td class="aligncenter">Status</td>
                    <td class="aligncenter">Remarks</td>
                </tr>
                <?php
                foreach($Data["Experience"] as $key => $value){?>
                <tr>
                    <td class="aligncenter" colspan="2"><?php echo date('m/d/y', strtotime($value["work_from"]))."-".date('m/d/y', strtotime($value["work_to"])); ?></td>
                    <td class="aligncenter"><?php echo $value["position"]; ?></td>
                    <td class="aligncenter"><?php echo $value["company"]; ?></td>
                    <td class="aligncenter"><?php echo number_format($value["monthly_salary"],2); ?></td>
                    <td class="aligncenter"><?php echo "";//$value[""]; ?></td>
                    <td class="aligncenter"><?php echo "";//$value[""]; ?></td>
                </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="7">x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-</td>
                </tr>
                <tr>
                    <td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Issued in compliance with Executive Order No. 54 dated August 10, 1954. and in accordance with Circular No. 58, dated August 10, 1954 of the System.</td>
                </tr>
            </table>
            <br>
            <br>
            <table width="30%" border="0" cellpadding="0" cellspacing="0" align="center" >
                <tr>
                    <td>CERTIFIED CORRECT:</td>
                </tr>
                <tr>
                    <td class="aligncenter">&nbsp;</td>
                </tr>
                <tr>
                    <td class="aligncenter" style="border-bottom: 1px solid black;">&nbsp;</td>
                </tr>
                <tr>
                    <td class="aligncenter" style="font-weight: bold;">&nbsp;<?php echo "MA. ISABEL M. DE GUZMAN"; //name ?></td>
                </tr>
                <tr>
                    <td class="aligncenter">&nbsp;<?php echo "Administrative Officer V"; //position ?></td>
                </tr>
            </table>
            <br>
            <br>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
                <tr>
                    <td width="5%" style="border-bottom: 1px solid black;">Date:</td>
                    <td width="5%" style="border-bottom: 1px solid black;"> <?php echo date('m/d/Y'); ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="vertical-align: top;" colspan="2">Status Legend:</td>
                    <td style="vertical-align: top;">T- Temporary/Casual  C - Contractual  P - Permanent  S - Substitute
                        <br>
                        X - Co-terminus  V - Probationary
                    </td>
                </tr>
            </table>
        </div>
        <div class="footer">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
            <tr>
                <td width="80%" style="text-align: center;">
                    8th Flr. NIA Building EDSA, Diliman, Quezon City, 1105 Philippines<br>
                    Tel. Nos. (632)8 920-2724 / (632)8 920 2641 ●<br>
                    Website: http://www.nwrb.gov.ph/ ●<br>
                    Email Address: nwrbphil@gmail.com
                </td>
                <td width="20%">
                <img src="<?php echo base_url()."assets/custom/images/ahilmqrz.png"; ?>">
                </td>
            </tr>
        </div>
    </div>
    </div>