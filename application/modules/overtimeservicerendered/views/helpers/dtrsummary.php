<?php

?>
<div class="row">
    <div class="col-md-12 text-right">
        <button id="printClearance" class="btn bg-green btn-fab btn-fab-mini">Print Preview <i class = "material-icons">print</i></button>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <style type="text/css">
        </style>
        <div style="width:100%; overflow-x:auto;">
            <div id="clearance-div">
                <style type = 'text/css'>
                    @media print{
                        /*280mm 378mm
                          11in 15in
                        */
                        html {
                            height: 0;
                        }
                        @page { 
                            size: US Std Fanfold; 

                        }
                        body {
                           font-family:Calibri;
                           font-size: 10;
                           color: black;
                        }
                        table{
                            border-collapse: collapse;
                        }
                        .page-break{
                            display: table;
                            vertical-align:top;
                            width: 100% !important;
                            page-break-after: always !important;
                            table-layout: inherit;
                            margin-top:2px;
                        }
                    }
                </style> 
                <style type="text/css" media="all">
                    table#tmpTable thead tr th, table#tmpTable tbody tr td{
                        padding: 2px;
                    }
                    table#tmpTable thead tr th{
                        border: 1px solid black;
                    }
                    table#main-table{
                        display:none;
                    }
                </style>
                <div class="header-container" style="width:100%;">
                    <!-- <table style="width:100%;border-bottom:0px;" id="tmpTable">
                        <thead>
                            <tr>
                                <td style="width:33%;text-align:left" nowrap>Date/Time Printed/User <?php echo date('m/d/Y  h:i:sa'); ?>  <?php echo Helper::get('first_name') ?></td>
                                <td style="width:33%;text-align:center" nowrap><label>GENERAL PAYROLL</label></td>
                                <td style="width:33%;text-align:right" nowrap><label>AS-PBD-005 &emsp;<?php echo strtoupper($payroll_type); ?> PAYROLL <?php echo strtoupper(@$pay_basis); ?>&emsp; Page No.: 1 of <?php echo $total_page; ?></label></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="100" nowrap>
                                    &emsp;WE HEREBY ACKNOWLEDGE to have received of <b>NATIONAL WATER RESOURCES BOARD <?php echo @$payroll_grouping[0]['code']; ?>&emsp; <?php echo @$pay_basis; ?></b> the sum therein specified opposite our names being in full compensation for
                                    <br> our services for the period &emsp;&emsp; <b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['start_date'])); ?></b> &emsp; to &emsp; <b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['end_date'])); ?></b>&emsp; except as noted otherwise in the Remarks Column.
                                </td>
                            </tr>
                        </tbody>
                    </table> -->
                    <div class="table-container table-responsive">
                        <table style="width:100%;" id="main-table" style="display: none;">
                            <thead>
                                <tr style="vertical-align: middle; text-align: center;">
                                    <th class="text-center" rowspan="2" style="vertical-align: middle; text-align: center; background: #fff"  width="5%">Date</th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle; text-align: center; background: #fff" width="5%">Day</th>
                                    <th class="text-center" rowspan="2">MORNING</th>
                                    <th class="text-center" rowspan="2">NOON</th>
                                    <th class="text-center" rowspan="2">NOON</th>
                                    <th class="text-center" rowspan="2">OUT</th>
                                    <th class="text-center" colspan="2">EXTRA</th>
                                    <th class="text-center" rowspan="2">TOTAL</th>
                                </tr>
                                <tr style="vertical-align: middle; text-align: center;">
                                    <th class="text-center" rowspan="2">IN</th>
                                    <th class="text-center" rowspan="2">OUT</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                    if(isset($list) && sizeof($list) > 0):
                                        $day  = 1;
                                        $total_tardiness_hrs = 0;
                                        $total_tardiness_min = 0;
                                        $total_ut_hrs = 0;
                                        $total_ut_min = 0;	
                                        $total_ot_hrs = 0;
                                        $total_ot_min = 0;
                                        
                                        foreach($list as $k => $v) {
                                            if($v['adjustment_am_arrival'] != null || $v['adjustment_am_departure'] != null || $v['adjustment_pm_arrival'] != null || $v['adjustment_pm_departure'] != null) {
                                                $arrival_am = @$v['adjustment_am_arrival'] != "" ? date('h:i a', strtotime($v['adjustment_am_arrival'])) : "";
                                                $departure_am = $v['adjustment_am_departure'] != "" ? date('h:i a', strtotime($v['adjustment_am_departure'])) : "";
                                                $arrival_pm = $v['adjustment_pm_arrival'] != "" ? date('h:i a', strtotime($v['adjustment_pm_arrival'])) : "";
                                                $departure_pm = $v['adjustment_pm_departure'] != "" ? date('h:i a', strtotime($v['adjustment_pm_departure'])) : "";
                                                $overtime_in = $v['adjustment_overtime_in'] != "" ? date('h:i a', strtotime($v['adjustment_overtime_in'])) : "";
                                                $overtime_out = $v['adjustment_overtime_out'] != "" ? date('h:i a', strtotime($v['adjustment_overtime_out'])) : "";
                                            } else {
                                                $arrival_am = @$v['actual_am_arrival'] != "" ? date('h:i a', strtotime($v['actual_am_arrival'])) : "";
                                                $departure_am = @$v['actual_am_departure'] != "" ? date('h:i a', strtotime($v['actual_am_departure'])) : "";
                                                $arrival_pm = @$v['actual_pm_arrival'] != "" ? date('h:i a', strtotime($v['actual_pm_arrival'])) : "";
                                                $departure_pm = @$v['actual_pm_departure'] != "" ? date('h:i a', strtotime($v['actual_pm_departure'])) : "";
                                                $overtime_in = @$v['actual_overtime_in'] != "" ? date('h:i a', strtotime($v['actual_overtime_in'])) : "";
                                                $overtime_out = @$v['actual_overtime_out'] != "" ? date('h:i a', strtotime($v['actual_overtime_out'])) : "";
                                            }
                                            $remarks = $v['remarks'];
                                ?>
                                <tr class="<?php echo isset($v['undertime_hours']) ? " text-danger": "0" ?>">
                                    <td class="fixed-height"> <?php echo date('F d, Y', strtotime($k)); ?> </td>
                                    <td class="fixed-height"> <?php echo date('l', strtotime($k)); ?> </td>
                                    <td class="fixed-height"> <?php echo $arrival_am; ?> </td>
                                    <td class="fixed-height"> <?php echo $departure_am; ?> </td>
                                    <td class="fixed-height"> <?php echo $arrival_pm; ?> </td>
                                    <td class="fixed-height"> <?php echo $departure_pm; ?> </td>
                                    <td class="fixed-height"> <?php echo $overtime_in; ?> </td>
                                    <td class="fixed-height"> <?php echo $overtime_out; ?> </td>
                                    <td class="fixed-height">
                                        <?php
                                            $a = $list[$k]['adjustment_overtime_in'];
                                            $b = $list[$k]['adjustment_overtime_out'];
                                            $w = $list[$k]['actual_overtime_in'];
                                            $x = $list[$k]['actual_overtime_out'];

                                            $hours = 0;
                                            $mins = 0;
                                            // check if attendance has adjustments
                                            if($a != null && $b != null) {
                                                $a = strtotime($a);
                                                $b = strtotime($b);
                                                $elapsed = $b - $a;
                                                $years = abs(floor($elapsed / 31536000));
                                                $days = abs(floor(($elapsed-($years * 31536000))/86400));
                                                $hours = abs(floor(($elapsed-($years * 31536000)-($days * 86400))/3600));
                                                $mins = abs(floor(($elapsed-($years * 31536000)-($days * 86400)-($hours * 3600))/60));
                                                $total_ot_hours = $hours.":".$mins;
                                            } else {
                                                if($w != null && $x != null) {
                                                    $w = strtotime($w);
                                                    $x = strtotime($x);
                                                    $elapsed = $x - $w;
                                                    $years = abs(floor($elapsed / 31536000));
                                                    $days = abs(floor(($elapsed-($years * 31536000))/86400));
                                                    $hours = abs(floor(($elapsed-($years * 31536000)-($days * 86400))/3600));
                                                    $mins = abs(floor(($elapsed-($years * 31536000)-($days * 86400)-($hours * 3600))/60));
                                                    $total_ot_hours = $hours.":".$mins;
                                                } else {
                                                    $total_ot_hours = "";
                                                }
                                            }
                                            $total_ot_hrs += (float) $hours;	
                                            $total_ot_min += (float) $mins;
                                            echo $total_ot_hours;
                                        ?>
                                    </td>
                                </tr>
                                <?php

                                $day++; } endif; ?>
                                <tr class="<?php echo isset($v['undertime_hours']) ? " text-danger": "0" ?>" style="background: #ccc">
                                    <td style="font-weight: bold; border-left: 0px solid; border-right: 0px solid; border-bottom: 1px dotted #333 !important;">
                                        Total
                                    </td>
                                    <td class="no-print" colspan="7"></td>
                                    <td class="print-only" colspan="8" style="font-weight: bold; border-left: 0px solid; border-right: 0px solid; border-bottom: 1px dotted #333 !important;"></td>
                                    <td class="text-danger" style="font-weight: bold; border-bottom: 1px dotted #333 !important;">
                                        <?php echo $total_ot_hrs + floor($total_ot_min/60).":".fmod($total_ot_min,60); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>

<?php

	function checkTime($time, $type){
		$ante = $time != null && $time != '' ? date('a', strtotime($time)) : '';
		if($type != null && $type != '') {
			switch ($type) {
				case 'am':
					return $ante == 'am' ? date('G:i:s', strtotime($time)) : '';
					break;
				case 'pm':
					return $ante == 'pm' ? date('G:i:s', strtotime($time)) : '';
					break;
				default:
					return false;
					break;
			}
		} else {
			return false;
		}
	}

	function convertTime($dec, $type) {
		$seconds = ($dec * 3600);
		$hours = floor($dec);
		$seconds -= $hours * 3600;
		$minutes = floor($seconds / 60);
		$seconds -= $minutes * 60;
		switch ($type) {
			case 'h':
				return $hours;
			break;
			case 'i':
				return $minutes;
			break;
			case 's':
				return $seconds;
			break;
			default:
				return false;
			break;
		}
	}

	function lz($num) {
		return (strlen($num) < 2) ? "0{$num}" : $num;
	}
?>