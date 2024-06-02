<div class="modal fade" id="print_preview_modal" tabindex="-1" role="dialog" aria-labelledby="print_preview_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
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
                        }
                    </style>
                    <br><br>
                    <div style="text-align: center;"><b>NATIONAL WATER RESOURCES BOARD</b><br><span style="font-size: 10px;">8th Floor, NIA Bldg., EDSA, Quezon City</span></div>
                    <br>
                    <div style="float: right;padding-right: 50px;" class="filing_date"></div>
                    <br><br><br>
                    <div class = "container-fluid">
                            
                             <p style="text-indent:10%;">The undersigned requests permission to leave office premises <u class="time1"></u> o'clock AM/PM on <u class="date"></u>. I shall be at <u class="venue"></u> for the following reasons; <u class="name_activity"></u>.</p>
                             <br>
                             <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span id="official"></span></div>
                             <span style = "float: left; display: block;">&nbsp Official</span>
                             <br>
                             <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span id="personal"></span></div>
                             <span style = "float: left; display: block;">&nbsp Personal</span>
                             <br>
                             <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span id="expected_time_return"></span></div>
                             <span style = "float: left; display: block;">&nbsp Expected time of return <u class="time2"></u></span>
                             <br>
                             <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span id="expected_not_back"></span></div>
                             <span style = "float: left; display: block;">&nbsp Expected not to back</span>
                             <br><br>
                        <p>Approved:</p>
                        <p style="text-align: right;padding-right: 50px;" class="name">name</p>
                        <p style="text-align: right;padding-right: 70px;font-size: 15px;" class="desig">desig</p>
                        <br>
                        <p style="padding-left: 50px;border-top: 1px solid black;width: 23%;" class="authorized">Authorized Official</p>
                        <br>
                        <p >Time Out - &emsp;&emsp;&emsp;&emsp;<u class="time_out"></u></p>
                        <p >Returned to Office - <u class="return_office"></u></p>
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
