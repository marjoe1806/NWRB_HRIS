    
    <div class = "container-fluid" id="printPreview" style="pointer-events: none;">
    <style type="text/css" media="all">
            @media print{@page {size: portrait}}
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
            #tblcontent{
                border-bottom: none;
            }
            #tblcontent td{
                border: 1px solid black;
            }
    </style>
    <div>
        <div class="header">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
                <tr>
                    <td class="tdheader hdfont" style="font-size: 16px;"><span style="font-size:20px;">R</span>EPUBLIC OF THE <span style="font-size:20px;">P</span>HILIPPINES</td>
                </tr>
                <tr>
                    <td class="tdheader hdfont" style="font-size: 16px;"><span style="font-size:20px;">N</span>ATIONAL <span style="font-size:20px;">W</span>ATER <span style="font-size:20px;">R</span>ESOURCES <span style="font-size:20px;">B</span>OARD</td>
                </tr>
                <tr>
                    <td class="tdheader hdfont" style="font-size: 16px;">
                        <br>
                        STATEMENT OF OVERTIME SERVICE RENDERED<br>
                        DURING THE MONTH(S) OF 
                        <span style="text-decoration: underline">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="content" style="margin-top: 3em">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="hdfont">
                <tr>
                    <td style="width: 10%">NAME:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
                <tr>
                    <td>DIVISION:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
            </table>
            <br>
            <br>
            <table id="tblcontent" width="100%" cellpadding="0" cellspacing="0" align="center" class="hdfont" style="font-size: 16px;" >
                <tr>
                    <td valign="middle" align="center" rowspan="2">DATE</td>
                    <td valign="middle" align="center" rowspan="2">DAY</td>
                    <td valign="middle" align="center" rowspan="2">MORNING</td>
                    <td valign="middle" align="center" rowspan="2">NOON</td>
                    <td valign="middle" align="center" rowspan="2">NOON</td>
                    <td valign="middle" align="center" rowspan="2">OUT</td>
                    <td valign="middle" align="center" colspan="2">EXTRA</td>
                    <td valign="middle" align="center" rowspan="2">TOTAL</td>
                </tr>
                <tr>
                    <td valign="bottom" align="center">OUT</td>
                    <td valign="bottom" align="center">IN</td>
                </tr>
            <?php for($i=0;$i<19;$i++){ ?>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php } ?>
                <tr style="border:none;">
                    <td colspan="8" align="right" style="border:none;">Total:</td>
                    <td style="border-left: none;border-right: none;border-bottom: 1px solid black;"></td>
                </tr>
            </table>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="hdfont">
                <tr>
                    <td width="25%">Checked as to Time Card:</td>
                    <td width="25%"></td>
                    <td width="25%">Submitted by:</td>
                    <td width="25%"></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid black;height: 40px;"></td>
                    <td></td>
                    <td style="border-bottom: 1px solid black;"></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Personnel Officer</td>
                    <td></td>
                    <td>Signature</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td style="height: 40px;" valign="bottom">CERTIFIED CORRECT:</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 40px;"></td>
                    <td></td>
                    <td style="border-bottom: 1px solid black;"></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
    </div>