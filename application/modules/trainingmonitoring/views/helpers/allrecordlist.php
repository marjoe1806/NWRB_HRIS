<style>
    .displaynone{
        display: none;
    }
</style>
<div class="table-responsive listTable">
    <table id="datatables" class="table table-hover table-striped" style="width:100%;">
        <thead> 
            <tr >
                <th>Start Date</th>
                <th>End Date</th>
                <th>Seminar / Training / Conference</th>
                <th>Conducted / Sponsored By</th>
                <th>Place</th>
                <th>Country</th>
                <th>Participants</th>
                <th>Action</th>                                        
            </tr>
        </thead>
        
    </table>
</div>
<!-- Print All Records -->
<div class="modal fade" id="printReportModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="printThis">
                    <style type="text/css">
                        @media print {
                            body {
                                color: #333;
                            }
                            table {
                                width: 100%;
                                border-spacing: 0;
                                border-collapse: collapse;
                                font-size: 9px;
                                font-family: Arial;
                            }
                            table thead th {
                                border: 1px solid;
                            }
                            .text-center {
                                text-align: center;
                            }
                            .text-right {
                                text-align: right;
                            }
                            .text-left {
                                text-align: left;
                            }
                            .row {
                                margin-right: -15px;
                                margin-left: -15px;
                            }
                        }
                    </style>
                    <table id="tblupdatereport" class="table table-bordered table-striped table-hover dataTable">
                        <thead>
                            <tr>
                                <td colspan="7">
                                    <CENTER>
                                        <label>NATIONAL WATER RESOURCES BOARD TRAINING AND SEMINAR MONITORING
                                            <br> ALL TRAINING RECORDS REPORT
                                        </label>
                                    </CENTER>
                                    <br>
                                    <br>
                                </td>
                            </tr>
                            <tr class="text-center">
                                <th>Training / Seminar / Conference Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Place</th>
                                <th>Country</th>
                                <th>Participant/s</th>
                            </tr>
                        </thead>
                        <tbody class="tblupdatereportbody" style="padding-left: 10px">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>