<?php 
    // var_dump($data); die();
    $form_id = $data[0]->form_id;
    $name = $data[0]->full_name;
    $position = $data[0]->position;
    $submitted_date = date('M-d-Y',strtotime($data[0]->submitted_date));
    $validated_by = $data[0]->validated_name == null ? "PENDING" : $data[0]->validated_name;
    // $assessed_reviewed_by = $data[0]->assessed_reviewed_name == null ? "PENDING" : $data[0]->assessed_reviewed_name;
    $attested_by = $data[0]->attested_name;
    $final_rating_by = $data[0]->final_rating_name;

    $validate_date = $data[0]->validate_date == null ? "PENDING" : date('M-d-Y',strtotime($data[0]->validate_date));
    $attested_date = $data[0]->attested_date == null ? "PENDING" : date('M-d-Y',strtotime($data[0]->attested_date));
    $final_rating_date = $data[0]->final_rating_date == null ? "PENDING" : date('M-d-Y',strtotime($data[0]->final_rating_date));

    $ratee = $data[0]->ratee_name;
    $period_of = $data[0]->period_of;

    $count_strategic = 0;
    $count_core = 0;
    $count_support = 0;
    $strategic_loop = 0;
    $core_loop = 0;
    $support_loop = 0;
    $count = 0;
    foreach ($data as $key => $value) {
        if($value->weight_of_output == "Strategic"){
            $count_strategic++;
        }
        elseif($value->weight_of_output == "Core"){
            $count_core++;
        }
        else{
            $count_support++;
        }
    }
?>




<?php
    $count_strategic_print = 0;
    $count_core_print = 0;
    $count_support_print = 0;
    $strategic_loop_print = 0;
    $core_loop_print = 0;
    $support_loop_print = 0;
    foreach ($data as $key => $value) {
        if($value->weight_of_output == "Strategic"){
            $count_strategic_print++;
        }
        elseif($value->weight_of_output == "Core"){
            $count_core_print++;
        }
        else{
            $count_support_print++;
        }
    }
?>

<div id = "content_print" style="border: 1px solid black;">
    <style type="text/css" media="all">
        
            @page { 
                size: legal landscape;
                /*margin: 0 20px 0 20px;*/
                /*width:816px;*/
                /*height:1344px;*/
                margin: 5mm 5mm 5mm 5mm;
            }

            .print{
                font-family: calibri;   
                border-collapse: collapse;
                border: 1px solid black;
                margin: auto;
            }

            .print th{
                border: 1px solid black;
                text-align: center;
            }

            .print td{
                border: 1px solid black;
                padding: 5px;
                text-align: center;
            }
            .table-scale {
            border: 1px solid black;
            border-collapse: collapse;
            font-family: calibri;   
            position: relative;
            left:33%;
            }
            .tblratee {
                font-family: calibri;   
                position: relative;
                left:30%;
                width: 20%
            }

            hr{
                width: 90%; 
                text-align: center; 
                margin-top: 50px; 
                margin-bottom: 2px;
                border: .5px solid black;
            }

            footer {
                page-break-after: auto;
            }
  
    </style>
    <center>
        <table >
            <tr>
                <th style="padding: 10px; font-size: 20px;"><center>OFFICE PERFORMANCE COMMITMENT AND REVIEW (OPCR)</center></th>
            </tr>
            <tr>
                <td style="padding: 10px; text-align:left;">
                    <b>I, <?php echo $name; ?> of the <?php echo $position; ?> Head of the sample position, commit to deliver and agree to be rated on the attainment of the following targets in accordance with the indicated measures for the period <?php echo $period_of; ?>.</b>
                </td>
                
            </tr>
        </table>
        <br>
        <table class="tblratee">
            <thead>
            <tr>
                <th>
                <center>
                    <?php echo $ratee != null ? $ratee:""; ?> 
                   <hr style="width: 90%; text-align: center; margin-top: 2px; margin-bottom: 2px;">
                            Ratee
                </center>
                </th>
            </tr>
            </thead>
        </table><br><br>
        <table class="tblratee">
            <thead>
            <tr>
                <th style="text-align:left; padding-left:10%;">
                    <b>Date:</b>
                    <?php echo date('M-d-Y',strtotime($submitted_date)); ?> 
                </th>
            </tr>
            </thead>
        </table><br><br>
        <table class = "print" style="width: 98%;">
            <thead>
                <tr>
                    <th style="width:70%;">APPROVED BY:</th>
                    <th>REVIEWED DATE:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding-top: 10px;">
                        <?php echo @$data[0]->approver_name; ?>
                        <hr style="width: 90%; text-align: center; margin-top: 0px; margin-bottom: 2px; border: .5px solid black;">
                        HEAD OF AGENCY
                    </td>   
                    <td id = "">
                        <?php echo date('M-d-Y',strtotime($data[0]->date_approved)); ?>
                    </td> 
                    
                </tr>
            </tbody>
        </table><br><br>
        <table class="table-scale">
            <thead>
            <tr>
                <th style="width;40%;">
                    <label>5</label>
                </th>
                <th style="text-align:left; padding-left:10px;">
                    <label>Outstanding</label>
                </th>
            </tr>
            <tr>
                <th>
                    <label>4</label>
                </th>
                <th style="text-align:left; padding-left:10px;">
                    <label>Very Satisfactory</label>
                </th>
            </tr>
            <tr>
                <th>
                    <label>3</label>
                </th>
                <th style="text-align:left; padding-left:10px;">
                    <label>Satisfactory</label>
                </th>
            </tr>
            <tr>
                <th>
                    <label>2</label>
                </th>
                <th style="text-align:left; padding-left:10px;">
                    <label>Unsatisfactory</label>
                </th>
            </tr>
            <tr>
                <th>
                    <label>1</label>
                </th>
                <th style="text-align:left; padding-left:10px;">
                    <label>Poor</label>
                </th>
            </tr>
            </thead>
        </table><br><br>
        <br>
        <table class = "print" style="width: 98%;">
            <thead>
                <tr>
                    <th rowspan="2">Major Final Output (MFO)/ <br> Program, Activity & Project <br> (PAP)</th>
                    <th rowspan="2">Success Indicators <br> (Targets + Measures)</th>
                    <th rowspan="2">Alloted Budget <br> </th>
                    <th rowspan="2">Office/Individual Accountable <br></th>
                    <th rowspan="2">Actual Accomplishments <br></th>
                    <th colspan="4">Rating <br></th>
                    <th rowspan="2">Remarks <br> </th>
                </tr>
                <tr>
                    <th>Quality</th>
                    <th>Efficiency</th>
                    <th>Timeliness</th>
                    <th>Average</th>
                </tr>
            </thead>
            <tbody>
                <tr class = "strat">
                    <th colspan="11">
                       Strategic Priority:
                    </th>
                </tr>
                <?php foreach ($data as $key => $value): ?>
                    <?php if($value->weight_of_output == "Strategic"): $strategic_loop_print++; ?>
                        <tr>
                            <td>
                                <?php echo $value->mfo_pap; ?>
                            </td>
                            <td>
                                <?php echo $value->success_target; ?>
                            </td>
                            <td>
                                <?php echo $value->allotted_budget; ?>
                            </td>
                            <td>
                                <?php echo $value->office_individual; ?>
                            </td>
                            <td>
                                <?php echo $value->actual_accomplishments; ?>
                            </td>
                            <td> <?php echo $value->q1; ?> </td>
                            <td> <?php echo $value->e2; ?> </td>
                            <td> <?php echo $value->t3; ?> </td>
                            <td> <?php echo $value->a4; ?> </td>
                            <td> <?php echo $value->remarks; ?> </td>
                        </tr>    
                        <?php if($strategic_loop_print == $count_strategic_print): ?>
                            <!-- <tr class = "core">
                                <th colspan="11">
                                    B. Core Functions: 
                                </th>
                            </tr> -->
                            <tr class = "support">
                                <th colspan="11">
                                  Support Functions:
                                </th>
                            </tr>
                        <?php endif; ?>
                    
                    <?php elseif($value->weight_of_output == "Support"): $support_loop_print++; ?>
                        <tr>
                            <td>
                                <?php echo $value->mfo_pap; ?>
                            </td>
                            <td>
                                <?php echo $value->success_target; ?>
                            </td>
                            <td>
                                <?php echo $value->allotted_budget; ?>
                            </td>
                            <td>
                                <?php echo $value->office_individual; ?>
                            </td>
                            <td>
                                <?php echo $value->actual_accomplishments; ?>
                            </td>
                            <td> <?php echo $value->q1; ?> </td>
                            <td> <?php echo $value->e2; ?> </td>
                            <td> <?php echo $value->t3; ?> </td>
                            <td> <?php echo $value->a4; ?> </td>
                            <td> <?php echo $value->remarks; ?> </td>
                        </tr> 
                    <?php endif; ?>
                <?php endforeach; ?>
                        <tr>
                            <th colspan="5" style="text-align:left;">
                                Final Average Rating
                            </th>
                            <td> <?php echo $data[count($data) - 1]->q1; ?> </td>
                            <td> <?php echo $data[count($data) - 1]->e2; ?> </td>
                            <td> <?php echo $data[count($data) - 1]->t3; ?> </td>
                            <td> <?php echo $data[count($data) - 1]->a4; ?> </td>
                            <td> <?php echo $data[count($data) - 1]->remarks; ?> </td>
                        </tr> 
            </tbody>
        </table>
        <br>
        <table class = "print" style="width: 98%;">
                <thead>
                    <tr>
                        <th>CATEGORY:</th>
                        <th>MFO:</th>
                        <th>RATING:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align:left;">
                            <label>Strategic Functions</label>
                        </td>   
                        <td>
                            <?php echo $data[0]->strat_mfo; ?>
                        </td> 
                        <td>
                            <?php echo $data[0]->strat_rating; ?>
                        </td>   
                    </tr>
                    <tr>
                        <td style="text-align:left;">
                            <label>Core Functions</label>
                        </td>   
                        <td>
                            <?php echo $data[0]->core_mfo; ?>
                        </td> 
                        <td>
                            <?php echo $data[0]->core_rating; ?>
                        </td>   
                    </tr>
                    <tr>
                        <td style="text-align:left;">
                            <label>Support Functions</label>
                        </td> 
                        <td>
                            <?php echo $data[0]->support_mfo; ?>  
                        </td> 
                        <td>
                            <?php echo $data[0]->support_rating; ?>
                        </td>   
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <label>Total Overall Rating</label>
                        </td>   
                        <td>
                            <?php echo $data[0]->overall_rating_mfo; ?>  
                        </td> 
                        <td>
                            <?php echo $data[0]->overall_rating; ?>  
                        </td>   
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <label>Final Average Rating</label>
                        </td>   
                        <td>
                            <?php echo $data[0]->avg_rating_mfo; ?>  
                        </td> 
                        <td>
                        <?php echo $data[0]->avg_rating; ?>  
                        </td>   
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <label>Adjectival Rating</label>
                        </td>   
                        <td>
                            <?php echo $data[0]->adj_rating_mfo; ?>  
                        </td> 
                        <td>
                            <?php echo $data[0]->adj_rating; ?>  
                        </td>   
                    </tr>
                 
                </tbody>
            </table>
        
        <!-- <table class = "print" style="width: 100%;">
            <thead>
                <tr>
                    <th>FINAL AVERAGE RATING:</th>
                    <th>COMMENTS AND RECOMMENDATIONS FOR DEVELOPMENT PURPOSES:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td id = "final_average">
                        <?php echo $final_average_rating; ?>
                    </td>   
                    <td id = "comments">
                        <?php echo $comments; ?>
                    </td>   
                </tr>
            </tbody>
        </table> -->
        <br>
        <table class = "print" style="width: 98%;">
            <thead>
                <tr>
                    <th colspan="4" style="width:60%; text-align:left;">Assessed By:</th>
                    <th>Final Rating By:</th>
                    <th>Date:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding-top: 20px;">
                        <?php echo @$data[0]->assessed_by_planning_officer; ?>
                        <hr style="width: 90%; text-align: center; margin-top: 0px; margin-bottom: 2px; border: .5px solid black;">
                        Planning Office
                    </td>
                     <td style="padding-top: 20px;">
                        <?php echo date('M-d-Y',strtotime($data[0]->officer_assessed_date)); ?>
                    </td>
                    <td style="padding-top: 20px;">
                        <?php echo @$data[0]->assessed_by_pmt; ?>
                        <hr style="width: 90%; text-align: center; margin-top: 0px; margin-bottom: 2px; border: .5px solid black;">
                        PMT
                    </td>
                    <td style="padding-top: 20px;">
                        <?php echo date('M-d-Y',strtotime($data[0]->pmt_assessed_date)); ?>
                    </td>
                   <td style="padding-top: 20px;">
                        <?php echo @$data[0]->final_rating_name; ?>
                        <hr style="width: 90%; text-align: center; margin-top: 0px; margin-bottom: 2px; border: .5px solid black;">
                        Head of Agency
                    </td>
                     <td style="padding-top: 20px;">
                        <?php echo date('M-d-Y',strtotime($data[0]->final_rating_date)); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:left;">
                        <i>Legent</i> <i>1 - Quantity</i> <i>2 - Efficiency</i> <i>3 - Timeliness</i> <i>4 - Average</i>
                    </td>
                </tr>
            </tbody>
        </table>
   <!--      <table class="print" style="width:50%; position:absolute; left:10px; ">
            <tr>
                <td><i>Legent</i></td>
                <td><i>1 - Quantity</i></td>
                <td><i>2 - Efficiency</i></td>
                <td><i>3 - Timeliness</i></td>
                <td><i>4 - Average</i></td>
            </tr>
        </table> -->
    </center><br><br>
</div>

