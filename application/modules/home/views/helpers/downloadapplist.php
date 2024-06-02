<?php 
    // echo base_url() ;

    $android = base_url().'uploads/APK/NWRB_MOBILE_1_0_11.apk';
    // $android = 'https://tlcpay.ph/NWRBCHRIS/uploads/APK/NWRB_MOBILE_1_0_11.apk';
    $ios = '';
?>
<div class="table-responsive listTable" style="width:100%;">
    <table id="datatables" class="table table-hover table-striped">
        <thead> 
            <tr>
                <th style="min-width: 150px;">Operating System</th>
                <th style="min-width: 300px;">Download</th>                                
            </tr>
        </thead>
        <tbody>
                <tr>
                    <td>Android</td>
                    <td>
                        <a href="<?php echo $android; ?>">
                            <div class="icon">
                                <i class="material-icons" data-toggle="tooltip" data-placement="right" title="" data-original-title="Download App">cloud_download</i>
                            </div>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>IOS</td>
                    <td>
                        <a href="<?php echo $ios; ?>">
                            <div class="icon">
                                <i class="material-icons" data-toggle="tooltip" data-placement="right" title="" data-original-title="Download App">cloud_download</i>
                            </div>
                        </a>
                    </td>
                </tr>
        </tbody>
    </table>
</div>
<div class="text-right" style="width:100%;">
    <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
        <i class="material-icons">close</i><span> Close</span>
    </button>
</div>