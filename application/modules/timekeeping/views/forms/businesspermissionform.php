<div class="modal fade" id="print_preview_modal" tabindex="-1" role="dialog" aria-labelledby="print_preview_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
                <h4 class="modal-title">Print Preview</h4>
            </div>
            <div class = "table-responsive">
                <div id = "content">
                    <style type="text/css" media="all">
                        @media screen {
                            .print-only {
                                display: none;
                            }
                        }
                        
                        @media print {
                            @page { 
                                size: legal portrait;
                                margin: none;
                            }

                            #foot label{
                                color: black;
                                /* font-family: arial; */
                                font-size: 6pt;
                            }

                            .break{
                                page-break-before: always;
                            }

                            table{
                                font-family: calibri;
                                font-size: 10pt;
                            }

                            h5{
                                font-family: calibri;
                                font-size: 10pt;
                            }
                            .text-center{
                                text-align: center;
                            }
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
                                margin-left: -230px;
                                /* margin-top: -7px; */
                                position: absolute;
                            }
                        }
                    </style>
                    <br><br>
                    
                    <p style="padding: 10px;"><b><span class="control_no"></span></b></p>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
                        <thead>
                            <tr rowspan="2">
                                <th colspan="2">
                                    <img src="<?php echo base_url()."assets/custom/images/nwrb.png"; ?>" id="logo">
                                </th>
                            </tr>
                            <tr>
                                <th colspan="2" class="bold aligncenter"><div style="text-align: center;"><b style="font-size: 14pt;">NATIONAL WATER RESOURCES BOARD</b><br><span style="font-size: 12pt;">8th Floor, NIA Bldg., EDSA, Quezon City</span></div></th>
                            </tr>
                        </thead>
                    </table>
                    <br>
                    <div style="float: right;padding-right: 50px; font-size: 12pt;" class="filing_date"></div>
                    <br><br><br>
                    <div class = "container-fluid">
                            
                             <p style="text-indent:10%; font-size: 12pt;">The undersigned requests permission to leave office premises <u class="time1"></u> o'clock AM/PM on <u class="date"></u>. I shall be at <u class="venue"></u> for the following reasons; <u class="name_activity"></u>.</p>
                             <br>
                             <div style="margin-bottom: 3px">
                                <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span class="official"></span></div>
                                <span style = "float: left; display: block; font-size: 12pt">&nbsp Official</span>
                                <br>
                            </div>
                            <div style="margin-bottom: 3px">
                                <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span class="personal"></span></div>
                                <span style = "float: left; display: block; font-size: 12pt">&nbsp Personal</span>
                                <br>
                            </div>
                            <div style="margin-bottom: 3px">
                                <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span class="expected_time_return"></span></div>
                                <span style = "float: left; display: block; font-size: 12pt">&nbsp Expected time of return <u class="time2"></u></span>
                                <br>
                            </div>
                            <div style="margin-bottom: 3px">
                                <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span class="expected_not_back"></span></div>
                                <span style = "float: left; display: block; font-size: 12pt">&nbsp Expected not to back</span>
                                <br>
                            </div>
                            <div style="margin-bottom: 3px">
                                <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span class="with_vehicle"></span></div>
                                <span style = "float: left; display: block; font-size: 12pt">&nbsp With Vehicle</span>
                                <br>
                            </div>
                            <div style="margin-bottom: 3px">
                                <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span class="no_vehicle"></span></div>
                                <span style = "float: left; display: block; font-size: 12pt">&nbsp No Vehicle</span>
                                <br>
                            </div>
                             <br>
                            <table style="float:right;">
                                <tr>
                                    <td>
                                        <center>
                                        <div style="width: 80%; height: 30px" class="applicantSign"></div>
                                        <div class="name" style="font-size: 12pt"></div>
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="text-center desig"></p>
                                    </td>
                                </tr>
                            </table>
                        <table>
                            <tr>
                                <td>
                                    <p style="font-size: 12pt">Approved:</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <center>
                                        <p style="border-bottom: 1px solid black; font-size: 12pt" class="authorized"></p>
                                    </center>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-center"  style="font-size: 12pt">Authorized Official</p>
                                </td>
                            </tr>
                        </table>
                        <!-- <p>Approved:</p>
                        <p style="text-align: right;padding-right: 50px;" class="name">name</p>
                        <p style="text-align: right;padding-right: 70px;font-size: 15px;" class="desig">desig</p>
                        <br>
                        <p style="padding-left: 50px;border-top: 1px solid black;width: 23%;" class="authorized">Authorized Official</p> -->
                        <!-- <br>
                        <p >Time Out - &emsp;&emsp;&emsp;&emsp;<u class="time_out"></u></p>
                        <p >Returned to Office - <u class="return_office"></u></p> -->
                    </div>   

                    <div class="print-only">
                        <p style="padding: 10px;"><b><span class="control_no"></span></b></p>
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
                            <thead>
                                <tr rowspan="2">
                                    <th colspan="2">
                                        <img src="<?php echo base_url()."assets/custom/images/nwrb.png"; ?>" id="logo">
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="bold aligncenter"><div style="text-align: center;"><b style="font-size: 14pt;">NATIONAL WATER RESOURCES BOARD</b><br><span style="font-size: 12pt;">8th Floor, NIA Bldg., EDSA, Quezon City</span></div></th>
                                </tr>
                            </thead>
                        </table>
                        <br>
                        <div style="float: right;padding-right: 50px; font-size: 12pt;" class="filing_date"></div>
                        <br><br><br>
                        <div class = "container-fluid">
                                
                                <p style="text-indent:10%; font-size: 12pt;">The undersigned requests permission to leave office premises <u class="time1"></u> o'clock AM/PM on <u class="date"></u>. I shall be at <u class="venue"></u> for the following reasons; <u class="name_activity"></u>.</p>
                                <br>
                                <div style="margin-bottom: 3px">
                                    <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span class="official"></span></div>
                                    <span style = "float: left; display: block; font-size: 12pt">&nbsp Official</span>
                                    <br>
                                </div>
                                <div style="margin-bottom: 3px">
                                    <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span class="personal"></span></div>
                                    <span style = "float: left; display: block; font-size: 12pt">&nbsp Personal</span>
                                    <br>
                                </div>
                                <div style="margin-bottom: 3px">
                                    <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span class="expected_time_return"></span></div>
                                    <span style = "float: left; display: block; font-size: 12pt">&nbsp Expected time of return <u class="time2"></u></span>
                                    <br>
                                </div>
                                <div style="margin-bottom: 3px">
                                    <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span class="expected_not_back"></span></div>
                                    <span style = "float: left; display: block; font-size: 12pt">&nbsp Expected not to back</span>
                                    <br>
                                </div>
                                <div style="margin-bottom: 3px">
                                    <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span class="with_vehicle"></span></div>
                                    <span style = "float: left; display: block; font-size: 12pt">&nbsp With Vehicle</span>
                                    <br>
                                </div>
                                <div style="margin-bottom: 3px">
                                    <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span class="no_vehicle"></span></div>
                                    <span style = "float: left; display: block; font-size: 12pt">&nbsp No Vehicle</span>
                                    <br>
                                </div>
                                <br>
                                <table style="float:right;">
                                    <tr>
                                        <td>
                                            <center>
                                            <div style="width: 80%; height: 30px" class="applicantSign"></div>
                                            <div class="name" style="font-size: 12pt"></div>
                                            </center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="text-center desig"></p>
                                        </td>
                                    </tr>
                                </table>
                            <table>
                                <tr>
                                    <td>
                                        <p style="font-size: 12pt">Approved:</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        &nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <center>
                                            <p style="border-bottom: 1px solid black; font-size: 12pt" class="authorized"></p>
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="text-center"  style="font-size: 12pt">Authorized Official</p>
                                    </td>
                                </tr>
                            </table>
                            <!-- <p>Approved:</p>
                            <p style="text-align: right;padding-right: 50px;" class="name">name</p>
                            <p style="text-align: right;padding-right: 70px;font-size: 15px;" class="desig">desig</p>
                            <br>
                            <p style="padding-left: 50px;border-top: 1px solid black;width: 23%;" class="authorized">Authorized Official</p> -->
                            <!-- <br>
                            <p >Time Out - &emsp;&emsp;&emsp;&emsp;<u class="time_out"></u></p>
                            <p >Returned to Office - <u class="return_office"></u></p> -->
                        </div>   
                    </div>
                </div>
            </div>
            <div class = "modal-footer">
                <div class = "container-fluid">  
                    <button type="submit" class="btn btn-success" id = "print_preview_appleave">Print</button>
                </div>
            </div>
        </div>
    </div>
</div>
