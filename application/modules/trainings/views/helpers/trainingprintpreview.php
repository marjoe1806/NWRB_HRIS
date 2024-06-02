<div class = "container-fluid" id="printPreview" style="pointer-events: none;">
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
<?php if(isset($list) && sizeof($list) > 0){ ?>
<div class="row">
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
        </thead>
        <tbody>
            <tr>
                <td colspan="4" style="padding: 0;font-size: 5px;">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4">Title of Learning And Development Interventions / Training Programs:&nbsp;&nbsp;&nbsp;
                    <span class="bold"><?php echo $list["trainings"]["title"]; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="1">Conducted / Sponsored By:&nbsp;&nbsp;&nbsp;
                    <span class="bold"><?php echo $list["trainings"]["sponsor"]; ?></span>
                </td>
                <td colspan="3">Venue:&nbsp;&nbsp;&nbsp;
                    <span class="bold"><?php echo $list["trainings"]["venue"]; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="1">Start Date:&nbsp;&nbsp;&nbsp;
                    <span class="bold"><?php echo $list["trainings"]["start_date"]; ?></span>
                </td>
                <td colspan="3">End Date:&nbsp;&nbsp;&nbsp;
                    <span class="bold"><?php echo $list["trainings"]["end_date"]; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="1">No. of Hours:&nbsp;&nbsp;&nbsp;
                    <span class="bold"><?php echo $list["trainings"]["no_of_hours"]; ?></span>
                </td>
                <td colspan="3">Type of L&D:&nbsp;&nbsp;&nbsp;
                    <span class="bold"><?php echo $list["trainings"]["type"]; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="1">Fees:&nbsp;&nbsp;&nbsp;
                    <span class="bold"><?php echo $list["trainings"]["fees"]; ?></span>
                </td>
                <td colspan="3">Travel Allowance:&nbsp;&nbsp;&nbsp;
                    <span class="bold"><?php echo $list["trainings"]["travel_allowance"]; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="1">Travel Order:&nbsp;&nbsp;&nbsp;
                    <span class="bold"><?php echo $list["trainings"]["travel_order"]; ?></span>
                </td>
                <td colspan="3">Office Order:&nbsp;&nbsp;&nbsp;
                    <span class="bold"><?php echo $list["trainings"]["office_order"]; ?></span>
                </td>
            </tr>
        </tbody>
    </table>
    <table  width="100%" cellpadding="0" cellspacing="0" class="content" align="center" style="border-top: 0px;">
        <thead>
            <tr>
                <th colspan="9" class="bold aligncenter brtop">ATTENDEES</th>
            </tr>
            <tr>
                <th style="border-top: 1px solid black; padding: 2px; text-align: center;">Employee Name</th>
                <th style="border-top: 1px solid black; padding: 2px; text-align: center;">Position</th>
                <th style="border-top: 1px solid black; padding: 2px; text-align: center;">Department</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($list["employee"]) && $list["employee"]): ?>
                <?php foreach ($list["employee"] as $k => $v) : ?>
                    <tr>
                        <td><?php echo $v['name']?></td>
                        <td><?php echo $v['position_name']?></td>
                        <td><?php echo $v['department_name']?></td>
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
</div>
<!-- <hr>
<div class="row">
    <div class="col-md-12 text-right">
        <button id="printClearance" class="btn bg-green btn-fab btn-fab-mini">Print <i class = "material-icons">print</button>
    </div>
</div> -->

<?php } else{ ?>
<div class="row">
    <div class="col-md-12">
        <h3>No available records to show.</h3>
    </div>  
</div>
<?php } ?>

</div>