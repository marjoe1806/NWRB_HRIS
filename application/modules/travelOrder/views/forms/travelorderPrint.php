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
                        @media print {
                            @page { 
                                /* size: legal portrait; */
                                size: A4 portrait;
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
                        }
                    </style>
                    <br>
                    <!-- <div style="text-align: center;"><b>NATIONAL WATER RESOURCES BOARD</b><br><span style="font-size: 10px;">8th Floor, NIA Bldg., EDSA, Quezon City</span></div>
                    <br>
                    <div style="float: right;padding-right: 50px;">TRAVEL ORDER NO.<u>&emsp;&emsp;<span class="travel_order_no"></span>&emsp;&emsp;</u></div>
                    <br>
                    <br> -->
                    <div class = "container-fluid">
                            <table width="100%" align="center" style="border: 1px solid black;border-collapse: separate;border-bottom: none;">
                                <tr align="left">
                                    <td>
                                        <p style="padding-left:10px;">
                                            <b>NATIONAL WATER RESOURCES BOARD</b><br>
                                            <span style="font-size: 10px;">8th Floor, NIA Bldg., EDSA, Quezon City</span>
                                        </p>
                                    </td>
                                    <td style="float:right;"><br>
                                        <b>TRAVEL ORDER NO.</b>
                                        <u>&emsp;&emsp;<span class="travel_order_no"></span>&emsp;&emsp;</u><br><br>
                                        1. Travel Date: <div style="float: right;padding-right: 50px;" class="date"></div>
                                    </td>
                                </tr>
                            </table>
                            <table width="100%" align="center" style="border: 1px solid black;border-collapse: separate;border-spacing: 15px;">
                                <tr align="left">
                                    <td style="width:50%;">2. For:
                                        <div style="width:90%; text-align:center; border-bottom:1px solid black; for_height" class="boxes"><span class="for">
                                            <ul class="employee-list">
                                            <!-- The employee names will be added dynamically here -->
                                            </ul></span>
                                        </div>
                                    </td>
                                    <td style="width:20%;">3. Vehicle No.:
                                        <div style="width:90%; text-align:center; border-bottom:1px solid black; for_height" class="boxes"><span class="vehicle_no"></span></div>
                                    </td>
                                    <td style="width:30%;">4. Driver:
                                        <div style="width:90%; text-align:center; border-bottom:1px solid black; for_height" class="boxes"><span class="driver"></span></div>
                                    </td>
                                </tr>
                            </table>
                            <table width="100%" align="center" style="border: 1px solid black;border-collapse: separate;border-spacing: 15px;">
                                <tr align="left">
                                    <td>5. Description of Official Business / Remarks :
                                        <!-- <tr align="left">
                                            <td>
                                                <div class="boxes">Destination/Location: <span style="width: 100%; border-bottom: 1px solid black;height: 40px" class="location"></span></div>
                                            </td>
                                            <td>                                            
                                                <div class="boxes">Offical Purpose: <span style="width: 100%; border-bottom: 1px solid black;height: 40px" class="official_purpose"></span></div>
                                            </td>
                                        </tr> -->
                                </tr>
                                <tr align="left">
                                    <td>
                                        <table width="100%" align="center">
                                            <tr align="left">
                                                <td style="width: 100%;">
                                                    <b>Destination/Location:</b>
                                                    <span style="text-align: justify" class="location"></span>
                                                </td>
                                            </tr>
                                            <tr align="left">
                                                <td style="width: 100%;">
                                                    <b>Offical Purpose:</b>
                                                    <span class="official_purpose" style="text-align: justify"></span>
                                                </td>
                                            </tr>
                                        </table>
                                    <td>
                                </tr>
                            </table>
                            <table width="100%" align="center" style="border: 1px solid black;border-collapse: separate;border-spacing: 15px;">
                                <tr align="left">
                                    <td>6. Requested / Recommended by: 
                                        <tr align="center">
                                            <td>
                                                <div style="width: 80%; border-bottom: 1px solid black;" class="last_recommend boxes"></div>
                                                <div style="width: 80%;line-height: 1; margin-top: 4px;" class="position_recommend boxes">DIVISION HEAD</div>
                                            </td>
                                            <td style="padding-left:10%;">
                                                <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span class="return"></span></div>
                                                <span style="float: left; display: block;">&nbsp To return to the Office</span><br>
                                                <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span class="not_return"></span></div>
                                                <span style="float: left; display: block;">&nbsp Not to return to the Office because</span><br>
                                                <div style="padding:0; width: 80%; border-bottom: 1px solid black;height: 20px" class="reason boxes"></div>
                                            </td>
                                        </tr>
                                    </td>
                                </tr>  
                                </tr> 
                            </table>
                            <table width="100%" align="center" style="border: 1px solid black;border-collapse: separate;border-spacing: 15px;">
                                <tr align="left">
                                    <td>7. Approved subject to the usual accounting and auditing regulations : 
                                        <tr align="center">
                                            <td>
                                                <div style="width: 50%; border-bottom: 1px solid black;" class="approver boxes"></div>
                                                <div style="width: 50%;line-height: 1; margin-top: 4px;" class="approver_position boxes">DIRECTOR</div>
                                            </td>
                                        </tr>

                                    </td>
                                </tr> 
                                </tr> 
                            </table>
                            <table width="100%" align="center" style="border: 1px solid black;border-collapse: separate;border-spacing: 15px;border-bottom: none;">
                                <tr align="left">
                                    <td>CERTIFICATION OF TRAVEL COMPLETION 
                                        <tr align="left">
                                            <td colspan="3" style="font-size: 13px">&emsp;&emsp;I certify that I have completed the travel authorized in this Order under the conditions marked below with the corresponding explanations as well as the evedences of travel attached here to:</td>
                                        </tr>
                                        <tr>
                                            <td align="left" width="33%">
                                                    <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span id=""></span></div>
                                                    <span style = "float: left; display: block;">&nbsp; as approved itinerary</span>&emsp;
                                            </td>
                                            <td align="left" width="33%">
                                                    <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span id=""></span></div>
                                                    <span style = "float: left; display: block;">&nbsp; cut short</span>&emsp;
                                            </td>
                                            <td align="left" width="33%">
                                                    <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span id=""></span></div>
                                                    <span style = "float: left; display: block;">&nbsp; extended</span>
                                            </td>
                                        </tr>
                                    </td>
                                </tr>
                            </table>
                            <table width="100%" align="center" style="border: 1px solid black;border-collapse: separate;border-spacing: 15px;border-top: none;border-bottom: none;">
                                <tr align="center">
                                    <td>
                                        <div style="width: 90%; border-bottom: 1px solid black;" class="boxes"></div>
                                        <div style="width: 80%;line-height: 1; margin-top: 4px;" class="boxes">Signature</div>
                                    </td>
                                    <td>
                                        <div style="width: 90%; border-bottom: 1px solid black;" class="boxes"></div>
                                        <div style="width: 80%;line-height: 1; margin-top: 4px;" class="boxes">Signature</div>
                                    </td>
                                    <td>
                                        <div style="width: 90%; border-bottom: 1px solid black;" class="boxes"></div>
                                        <div style="width: 80%;line-height: 1; margin-top: 4px;" class="boxes">Signature</div>
                                    </td>
                                    <td>
                                        <div style="width: 90%; border-bottom: 1px solid black;" class="boxes"></div>
                                        <div style="width: 80%;line-height: 1; margin-top: 4px;" class="boxes">Signature</div>
                                    </td>
                                </tr>
                            </table>
                            <table width="100%" align="center" style="border: 1px solid black;border-collapse: separate;border-spacing: 15px;border-top: none;border-bottom: none;">
                                <tr align="center">
                                    <td>
                                        <div style="width: 90%; border-bottom: 1px solid black;" class="boxes"></div>
                                        <div style="width: 80%;line-height: 1; margin-top: 4px;" class="boxes">Signature</div>
                                    </td>
                                    <td>
                                        <div style="width: 90%; border-bottom: 1px solid black;" class="boxes"></div>
                                        <div style="width: 80%;line-height: 1; margin-top: 4px;" class="boxes">Signature</div>
                                    </td>
                                    <td>
                                        <div style="width: 90%; border-bottom: 1px solid black;" class="boxes"></div>
                                        <div style="width: 80%;line-height: 1; margin-top: 4px;" class="boxes">Signature</div>
                                    </td>
                                    <td>
                                        <div style="width: 90%; border-bottom: 1px solid black;" class="boxes"></div>
                                        <div style="width: 80%;line-height: 1; margin-top: 4px;" class="boxes">Signature</div>
                                    </td>
                                </tr>
                            </table>
                            <table width="100%" align="center" style="border: 1px solid black;border-collapse: separate;border-spacing: 15px;border-top: none;">
                                <tr align="left">
                                    <td>
                                        Travel Duration : <u class="duration"></u>
                                    </td>
                                    <td>
                                        Hours / Days : <u class="no_days"></u>
                                    </td>                                    
                                </tr>
                            </table>
                            <table width="100%" align="center" style="border: 1px solid black;border-collapse: separate;border-spacing: 15px;">
                                <tr align="left">
                                    <td><b>CERTIFICATION OF SUPERVISOR </b>
                                        <tr align="left">
                                            <td colspan="2" style="font-size: 13px">&emsp;&emsp;On evidence and information of which I have knowledge, the above mentioned travel was actualy undertaken.</td>
                                        </tr>
                                        
                                        <tr>
                                            <td style="width: 50%">
                                                <center>
                                                    <div style="width: 80%; border-bottom: 1px solid black;" class="div_head boxes"></div>
                                                    <div style="width: 80%;line-height: 1; margin-top: 4px;" class="boxes">Name/Signature</div>
                                                </center>
                                            </td>
                                            <td style="width: 50%">
                                                <center><br>
                                                    <div style="width: 80%; border-bottom: 1px solid black;height: 20px" class="div_head_date boxes"></div>
                                                    <div style="width: 80%;line-height: 1; margin-top: 4px;" class="boxes">Date</div>
                                                </center>
                                            </td>
                                        </tr>
                                        <tr align="left">
                                            <td colspan="2" style="font-size: 13px">&emsp;&emsp;<b><u>Note:</u></b> After completion of travel, this fully accomplished Form shall be submitted to the General Service Section for appropriate action.</td>
                                        </tr>
                                    </td>
                                </tr>
                            </table>
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
