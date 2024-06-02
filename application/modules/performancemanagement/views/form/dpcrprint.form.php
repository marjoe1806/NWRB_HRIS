<?php 
   // var_dump($data); die();
    $name = $data[0]->full_name;
    $position = $data[0]->position;
    $period_of = $data[0]->period_of;
    $date_review = $data[0]->date_review;
    $date_approve = $data[0]->date_approve;
    $posted_date = $data[0]->posted_date;
    $date_review = ($data[0]->date_review == null ? "PENDING" : date('M-d-Y', strtotime($data[0]->date_review)));
    $approved_by = $data[0]->approved_by_name;
    $date_approve = ($data[0]->date_approve == null ? "PENDING" : date('M-d-Y', strtotime($data[0]->date_approve)));
    $posted_date = ($data[0]->posted_date == null ? "PENDING" : date('M-d-Y', strtotime($data[0]->posted_date)));
    $final_average_rating = $data[0]->final_average_rating;
    $comments = $data[0]->comments;
    $discussed_with_emp = $data[0]->discussed_with_emp_name;
    $date_discussed = ($data[0]->date_discussed == null ? "PENDING" : date('M-d-Y', strtotime($data[0]->date_discussed)));
    $assesed_by_supervisor = $data[0]->assesed_by_supervisor_name;
    $date_assesed = ($data[0]->date_assesed == null ? "PENDING" : date('M-d-Y', strtotime($data[0]->date_assesed)));
    $final_rating_by_head_of_office = $data[0]->final_rating_by_head_of_office_name;
    // var_dump($data[0]->date_final_rating); die();
    $date_final_rating = ($data[0]->date_final_rating == null ? "PENDING" : date('M-d-Y', strtotime($data[0]->date_final_rating)));
    $reviewed_by = $data[0]->reviewed_by_name;
    $ratee = $data[0]->ratee_name;
    $form_id = $data[0]->form_id;
?>



<div id = "content_print" style=" border: 1px solid black; ">
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
                width: 98%;
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

            hr{
                width: 90%; 
                text-align: center; 
                margin-top: 2px; 
                margin-bottom: 2px;
                border: .5px solid black;
            }

            footer {
                page-break-after: auto;
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
    </style>
    <center>
        <table>
            <tr>
                <th style="padding: 10px; font-size: 20px;"><center>DIVISION PERFORMANCE COMMITMENT AND REVIEW (DPCR)</center></th>
            </tr>
            <tr>
                <td style="padding: 10px; text-align:left;">
                    <b>I,  <?php echo $name; ?> of the <?php echo $position; ?>, commit to deliever and agree to be rated on the attainment of the following Targets in accordance with the indicated measures for the period of <?php echo $period_of; ?>.</b>
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
                    <?php echo date('M-d-Y',strtotime($posted_date)); ?> 
                </th>
            </tr>
            </thead>
        </table><br><br>
        <table class = "print">
            <thead>
                <tr>
                    <th>REVIEWED BY:</th>
                    <th>REVIEWED DATE:</th>
                    <th>APPROVED BY :</th>
                    <th>APPROVED DATE:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding-top: 40px;">
                        <?php echo $reviewed_by; ?>
                        <hr>
                        IMMEDIATE SUPERVISOR
                    </td>   
                    <td id = "">
                        <?php echo $date_review; ?>
                    </td> 
                    <td style="padding-top: 40px;">
                        <?php echo $approved_by; ?>
                        <hr>
                        DIRECTOR
                    </td>  
                    <td id = "">
                        <?php echo $date_approve; ?>
                    </td>    
                </tr>
            </tbody>
        </table>
        <br><br>
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
           
            </thead>
        </table><br><br>
        <!-- <table class = "print">
            <thead>
                <tr>
                    <th rowspan="2">
                        <label>MAJOR FINAL OUTPUT</label>
                    </th>
                    <th>
                        <label>SUCCESS INDICATORS</label>
                    </th>
                    <th rowspan="2">
                        <label>ACTUAL ACCOMPLISHMENTS</label>
                    </th>
                    <th colspan="4" id = "">
                        <label>RATING</label>
                    </th>
                    <th rowspan="2">
                        <label>REMARKS</label>
                    </th>
                </tr>
                <tr>
                    <th>
                        <label>(TARGETS + MEASURES)</label>
                    </th>
                    <th>
                        <label>Q1</label>
                    </th>
                    <th>
                        <label>E2</label>
                    </th>
                    <th>
                        <label>T3</label>
                    </th>
                    <th>
                        <label>A4</label>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="text-align:left;">Strategic Functions</th>
                    <th>
                        <label></label>
                    </th>
                    <th>
                        <label></label>
                    </th>
                    <th colspan="4">
                        <label></label>
                    </th>
                    <th>
                        <label></label>
                    </th>
                </tr>
                <?php foreach($data as $key=>$value): ?>
                    <?php if($value->weight_of_output == 'Strategic'): ?>
                    <tr>
                        <td> <?php echo $value->output; ?> </td>
                        <td> <?php echo $value->success_target; ?> </td>
                        <td> <?php echo $value->actual_accomplishments; ?> </td>
                        <td> <?php echo $value->q1; ?> </td>
                        <td> <?php echo $value->e2; ?> </td>
                        <td> <?php echo $value->t3; ?> </td>
                        <td> <?php echo $value->a4; ?> </td>
                        <td> <?php echo $value->remarks; ?> </td>
                    </tr>
                    <?php endif ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br> -->
        
        <table class = "print">
            <thead>
                <tr>
                    <th rowspan="2">
                        <label>MAJOR FINAL OUTPUT</label>
                    </th>
                    <th>
                        <label>SUCCESS INDICATORS</label>
                    </th>
                    <th rowspan="2">
                        <label>ACTUAL ACCOMPLISHMENTS</label>
                    </th>
                    <th colspan="4" id = "">
                        <label>RATING</label>
                    </th>
                    <th rowspan="2">
                        <label>REMARKS</label>
                    </th>
                </tr>
                <tr>
                    <th>
                        <label>(TARGETS + MEASURES)</label>
                    </th>
                    <th>
                        <label>Quality</label>
                    </th>
                    <th>
                        <label>Efficiency</label>
                    </th>
                    <th>
                        <label>Timeliness</label>
                    </th>
                    <th>
                        <label>Average</label>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="text-align:left;">Strategic Functions</th>
                    <th>
                        <label></label>
                    </th>
                    <th>
                        <label></label>
                    </th>
                    <th colspan="4">
                        <label></label>
                    </th>
                    <th>
                        <label></label>
                    </th>
                </tr>
                <?php foreach($data as $key=>$value): ?>
                    <?php if($value->weight_of_output == 'Strategic'): ?>
                    <tr>
                        <td> <?php echo $value->output; ?> </td>
                        <td> <?php echo $value->success_target; ?> </td>
                        <td> <?php echo $value->actual_accomplishments; ?> </td>
                        <td> <?php echo $value->q1; ?> </td>
                        <td> <?php echo $value->e2; ?> </td>
                        <td> <?php echo $value->t3; ?> </td>
                        <td> <?php echo $value->a4; ?> </td>
                        <td> <?php echo $value->remarks; ?> </td>
                    </tr>
                    <?php endif ?>
                <?php endforeach; ?>
                <tr>
                    <th style="text-align:left;">Core Functions</th>
                    <th>
                        <label></label>
                    </th>
                    <th>
                        <label></label>
                    </th>
                    <th colspan="4">
                        <label></label>
                    </th>
                    <th>
                        <label></label>
                    </th>
                </tr>
                <?php foreach($data as $key=>$value): ?>
                    <?php if($value->weight_of_output == 'Core'): ?>
                        <tr>
                        <td> <?php echo $value->output; ?> </td>
                        <td> <?php echo $value->success_target; ?> </td>
                        <td> <?php echo $value->actual_accomplishments; ?> </td>
                        <td> <?php echo $value->q1; ?> </td>
                        <td> <?php echo $value->e2; ?> </td>
                        <td> <?php echo $value->t3; ?> </td>
                        <td> <?php echo $value->a4; ?> </td>
                        <td> <?php echo $value->remarks; ?> </td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                <tr>
                    <th style="text-align:left;">Support Functions</th>
                    <th>
                        <label></label>
                    </th>
                    <th>
                        <label></label>
                    </th>
                    <th colspan="4">
                        <label></label>
                    </th>
                    <th>
                        <label></label>
                    </th>
                </tr>
                <?php foreach($data as $key=>$value): ?>
                    <?php if($value->weight_of_output == 'Support'): ?>
                        <tr>
                        <td> <?php echo $value->output; ?> </td>
                        <td> <?php echo $value->success_target; ?> </td>
                        <td> <?php echo $value->actual_accomplishments; ?> </td>
                        <td> <?php echo $value->q1; ?> </td>
                        <td> <?php echo $value->e2; ?> </td>
                        <td> <?php echo $value->t3; ?> </td>
                        <td> <?php echo $value->a4; ?> </td>
                        <td> <?php echo $value->remarks; ?> </td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php foreach($data as $key=>$value): ?>
                    <?php if($value->weight_of_output == 'Final-Rating'): ?>
                        <tr>
                        <th colspan="3" style="text-align:left;"> Final Rating </th>
                        <td> <?php echo $value->q1; ?> </td>
                        <td> <?php echo $value->e2; ?> </td>
                        <td> <?php echo $value->t3; ?> </td>
                        <td> <?php echo $value->a4; ?> </td>
                        <td> </td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table><br><br>
        <table class = "print">
            <thead>
                <tr>
                    <th>COMMENTS AND RECOMMENDATIONS FOR DEPELOPMENT FOR PURPOSES:</th>
                </tr>
            </thead>
            <tbody>
                <tr> 
                    <td style="padding-top: 40px;" >
                        <?php echo @$data[0]->comments; ?>
                    </td> 
                </tr>
            </tbody>
        </table><br><br>
        <table class = "print" >
            <thead>
                <tr>
                    <th>DISSCUSSED WITH:</th>
                    <th>DISSCUSSED DATE:</th>
                    <th style="width:20%;">ASSESSED BY:</th>
                    <th>ASSESSED DATE:</th>
                    <th>FINAL RATING BY:</th>
                    <th>FINAL RATE DATE:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding-top: 40px;">
                    </td>  
                    <td id = "date_discussed">
                    </td>  
                    <td style="padding-top: 40px;">
                        I certify that I discussed my assessment of the performance with the employee
                    </td> 
                    <td id = "">
                    </td>   
                    <td style="padding-top: 40px;">
                    </td> 
                    <td id = "">
                    </td> 
                </tr>
                <tr>
                    <td style="padding-top: 40px;">
                        <?php echo $discussed_with_emp; ?>
                        <hr>
                        EMPLOYEE
                    </td>  
                    <td id = "date_discussed">
                        <?php echo $date_discussed; ?>
                    </td>  
                    <td style="padding-top: 40px;">
                        <?php echo $assesed_by_supervisor; ?>
                        <hr>
                        SUPERVISOR
                    </td> 
                    <td id = "">
                        <?php echo $date_assesed; ?>
                    </td>   
                    <td style="padding-top: 40px;">
                        <?php echo $final_rating_by_head_of_office; ?>
                        <hr>
                        DIRECTOR
                    </td> 
                    <td id = "">
                        <?php echo $date_final_rating; ?>
                    </td> 
                </tr>
                <tr>
                    <td colspan="6" style="text-align:left;">
                        <i>Legent</i> <i>1 - Quantity</i> <i>2 - Efficiency</i> <i>3 - Timeliness</i> <i>4 - Average</i>
                    </td>
                </tr>
            </tbody>
        </table>
       <!--  <table style="width:50%; position:absolute; left:10px; ">
            <tr>
                <td><i>Legent</i></td>
                <td><i>1 - Quantity</i></td>
                <td><i>2 - Efficiency</i></td>
                <td><i>3 - Timeliness</i></td>
                <td><i>4 - Average</i></td>
            </tr>
        </table> -->
    </center><br>
</div>

