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

<div id = "content_print" class="card" style="padding: 3%;">
    <style type="text/css" media="all">
            * {
                padding: 0px;
                margin: 0px;
                color: black;
            }
            @page { 
                size: legal landscape;
                /*margin: 0 20px 0 20px;*/
                /*width:816px;*/
                /*height:1344px;*/
                margin: 5mm 5mm 5mm 5mm;
            }

             @media print {
                .tbody_border{
                background:red;
                border-collapse: collapse;
                border-spacing: 0;
                }
                .print_only{
                    height: 100px; 
                    padding: 50px;
                }
            }

            .print{
                font-family: calibri;   
                border-collapse: collapse;
                border: 1px solid black;
                width: 100%;
                margin: auto;
                border-spacing: 0;
                
            }

            .print th{
                border: 1px solid black;
                text-align: center;
                border-collapse: collapse;
            }
            .print tr{
                border: 1px solid black;
                text-align: center;
                border-collapse: collapse;
                border-spacing: 0;
            }

            .print td{
                border: 1px solid black;
                padding: 5px;
                text-align: center;
                border-collapse: collapse;
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
    </style>
        <table class="p-2" style="width: 100%;">
            <thead>
            <tr>
                <th colspan="4" style="padding: 10px; font-size: 20px;"><center>INDIVIDUAL PERFORMANCE COMMITMENT AND REVIEW (IPCR)</center></th>
            </tr>
            <tr>
                <td colspan="4" style="padding: 15px; text-align:left;">
                  <p style="font-weight: bold; fort-size: 15px;">  I,  <span><u><?php echo $name; ?></u></span> of the <span><u>National Water Resources Board</u></span>, under the <span><u><?php echo $position; ?></u></span>, commit to deliver and agree to be rated on the attainment of the following targets in accordance with the indicated measures for the period of <span><u><?php echo $period_of; ?></u></span>.</p>
                </td>
            </tr>
            <tr>
                <td style="width: 75%;">  &nbsp; &nbsp;</td>
                <td>  &nbsp; &nbsp;</td>
                <td>  &nbsp; &nbsp;</td>
                <th>
                    <center>
                        <p style="text-align:center; margin-top: 20px; font-weight: bold; fort-size: 15px;"><span><?php echo $discussed_with_emp; ?> </span></p>
                    </center>
                </th>
            </tr>
            <tr>
                <td>  &nbsp; &nbsp;</td>
                <td>  &nbsp; &nbsp;</td>
                <td>  &nbsp; &nbsp;</td>
                <th>
                    <center>
                        <p style="text-align:center;">Employee</p>
                    </center>
                </th>
            </tr>
            <tr>
                <td>  &nbsp; &nbsp;</td>
                <td>  &nbsp; &nbsp;</td>
                <td>  &nbsp; &nbsp;</td>
                <th>
                    <center>
                        <p style="text-align:center; margin-top: 20px; margin-bottom: 20px;"><span style="margin-right: 10px;">Date:</span> <?php echo date('F d, Y',strtotime($posted_date)); ?> </p>
                    </center>
                </th>
            </tr>
            </thead>
        </table>

        <table class = "print">
        <tr class = "print">
                <th style="padding: 10px; border: 1px solid black; border-collapse: collapse;">REVIEWED BY:</th>
                <th style="padding: 10px; border: 1px solid black; border-collapse: collapse;">REVIEWED DATE:</th>
                <th style="padding: 10px; border: 1px solid black; border-collapse: collapse;">APPROVED BY :</th>
                <th style="padding: 10px; border: 1px solid black; border-collapse: collapse;">APPROVED DATE:</th>
            </tr>
            <tr class = "print">
                    <td style="border: 1px solid black; text-align:center; padding-top: 40px; font-size: 15px; font-weight: bold;">
                        <span><?php echo $reviewed_by;?></span>
                    </td>   
                    <td style=" border: 1px solid black; padding-top: 40px;">
                        <?php echo $date_review; ?>
                    </td> 
                    <td style="border: 1px solid black; text-align:center; padding-top: 40px; font-size: 15px; font-weight: bold;">
                       <span> <?php echo $approved_by; ?>  </span>                     
                    </td>  
                    <td style=" border: 1px solid black; padding-top: 40px; text-align:center;">
                        <?php echo $date_approve; ?>
                    </td>    
            </tr>
            <tr class = "print" style="text-align:center; text-align:center;">
                    <td style=" border: 1px solid black; text-align:center;">
                     <?php echo $reviewedBy['name']; ?>
                    </td>
                    <td style=" border: 1px solid black; text-align:center;"></td>
                    <td style=" border: 1px solid black; text-align:center;">
                    <?php echo $approvedBy['name']; ?>
                    </td>
                    <td style="border: 1px solid black; text-align:center;"></td>
            </tr>
        </table>
        <br>
        <table style="margin-left: 80%;">
            <tr>
                <td style="padding: 15px; border: 1px solid black;">
                <table>
                        <tr>
                            <th>
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
                    </table>
                </td>
            </tr>
        </table>
        <br>
       
        <table class = "print w-100">
            <thead>
                <tr>
                    <th rowspan="2" style="border: 1px solid black;">
                        <label>PROGRAM</label>
                    </th>
                    <th style="border: 1px solid black; border-bottom: 1px solid #FFF;">
                        <label>SUCCESS INDICATORS</label>
                    </th>
                    <th rowspan="2" style="border: 1px solid black;">
                        <label>ACTUAL ACCOMPLISHMENTS</label>
                    </th>
                    <th colspan="4"  style="border: 1px solid black;">
                        <label>RATING</label>
                    </th>
                    <th rowspan="2" style="border: 1px solid black;">
                        <label>REMARKS</label>
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid black;">
                        <label>(TARGETS + MEASURES)</label>
                    </th>
                    <th style="border: 1px solid black;">
                        <label>Q<sup>1</sup></label>
                    </th>
                    <th style="border: 1px solid black;">
                        <label>Q<sup>2</sup></label>
                    </th>
                    <th style="border: 1px solid black;">
                        <label>T<sup>3</sup></label>
                    </th>
                    <th style="border: 1px solid black;">
                        <label>A<sup>4</sup></label>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th colspan="8" style="text-align:left; border: 1px solid black;">
                        <span>General Administration and Support</span>
                    </th>
                </tr>
                <?php foreach($data as $key=>$value): ?>
                    <?php if($value->weight_of_output == 'Strategic'): ?>
                    <tr>
                        <td style="border: 1px solid black;"> <?php echo $value->output; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->success_target; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->actual_accomplishments; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->q1; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->e2; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->t3; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->a4; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->remarks; ?> </td>
                    </tr>
                    <?php endif ?>
                <?php endforeach; ?>

                <?php foreach($data as $key=>$value): ?>
                    <?php if($value->weight_of_output == 'Core'): ?>
                        <tr>
                        <td style="border: 1px solid black;"> <?php echo $value->output; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->success_target; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->actual_accomplishments; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->q1; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->e2; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->t3; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->a4; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->remarks; ?> </td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php foreach($data as $key=>$value): ?>
                    <?php if($value->weight_of_output == 'Support'): ?>
                        <tr>
                        <td style="border: 1px solid black;"> <?php echo $value->output; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->success_target; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->actual_accomplishments; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->q1; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->e2; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->t3; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->a4; ?> </td>
                        <td style="border: 1px solid black;"> <?php echo $value->remarks; ?> </td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php foreach($data as $key=>$value): ?>
                    <?php if($value->weight_of_output == 'Final-Rating'): ?>
                    <tr>
                        <th colspan="3" style="text-align:left; padding: 1px solid black;"> Final Average Rating </th>
                        <td style="border: 1px solid black;"> <?php  $value->q1; ?> </td>
                        <td style="border: 1px solid black;"> <?php  $value->e2; ?> </td>
                        <td style="border: 1px solid black;"> <?php  $value->t3; ?> </td>
                        <td style="border: 1px solid black;"> <?php $avg = $value->q1 + $value->e2 + $value->t3 + $value->a4; $avg1 = $avg / 4; echo $avg1 ?> </td>
                        <td style="border: 1px solid black;"> </td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                    <tr>
                        <th colspan="3" style="text-align:left; padding: 1px solid black;" > Adjectival Rating </th>
                        <td style="padding: 1px solid black;">  </td>
                        <td style="padding: 1px solid black;">  </td>
                        <td style="padding: 1px solid black;">  </td>
                        <td style="padding: 1px solid black;">  
                        <?php 
                                $avg = $value->q1 + $value->e2 + $value->t3 + $value->a4; 
                                $avg1 = $avg / 4; 
                                if(round($avg1) == 1){
                                    echo "P";
                                }
                                elseif(round($avg1) == 2){
                                    echo "U";
                                }
                                elseif(round($avg1) == 3){
                                    echo "S";
                                }
                                elseif(round($avg1) == 4){
                                    echo "VS";
                                }elseif(round($avg1) == 5){
                                    echo "O";
                                }
                                
                                ?>
                        </td>
                        <td style="padding: 1px solid black;"> </td>
                    </tr>
            </tbody>
        </table>
        <table class="print_only"  style="border: 1px solid #fff;">
        </table>
        <table class = "print">
            <thead>
                    <tr>
                        <th colspan="6" style="text-align: left;">COMMENTS AND RECOMMENDATIONS FOR DEVELOPMENT FOR PURPOSES:</th>
                    </tr>
                    <?php if(!empty(@$data[0]->comments)):?>
                   <tr> 
                        <td colspan="6" style="padding-top: 20px; text-align: left;" >
                            <?php echo @$data[0]->comments; ?>
                        </td> 
                    </tr>
                    <?php endif;?>
                    <!-- <td colspan="6" style="border-left: 1px solid #fff; border-right: 1px solid #fff;" ></td>  -->
                    </tr>
                <tr>
                    <th>Discussed with:</th>
                    <th>Date:</th>
                    <th style="width:20%;">Assesed by:</th>
                    <th>Date:</th>
                    <th>Final Rating By:</th>
                    <th>Final Rate Date:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border-bottom: 1px solid #fff;">
                    </td>  
                    <td id = "date_discussed" style="border-bottom: 1px solid #fff;">
                    </td>  
                    <td style=" border-bottom: 1px solid #fff;">
                        I certify that I discussed my assessment of the performance with the employee
                    </td> 
                    <td id = "" style="border-bottom: 1px solid #fff;">
                    </td>   
                    <td style=" border-bottom: 1px solid #fff;">
                    </td> 
                    <td id = "" style="border-bottom: 1px solid #fff;">
                    </td> 
                </tr>
                <tr>
                    <td style="padding-top: 20px;">
                      <b> <span> <?php echo $discussed_with_emp; ?></span>  </b>                   
                    </td>  
                    <td style="padding-top: 20px;" id = "date_discussed">
                        <?php echo $date_discussed; ?>
                    </td>  
                    <td style="padding-top: 20px;">
                      <b> <span> <?php echo $assesed_by_supervisor; ?></span></b>
                       
                    </td> 
                    <td style="padding-top: 20px";>
                        <?php echo $date_assesed; ?>
                    </td>   
                    <td style="padding-top: 20px;">
                     <b> <span><?php echo $final_rating_by_head_of_office; ?></span></b>
                    </td> 
                    <td style="padding-top: 20px;">
                        <?php echo $date_final_rating; ?>
                    </td> 
                </tr>
                <tr>
                    <td><?php echo $employee['name']; ?></td>
                    <td></td>
                    <td><?php echo $supervisor['name']; ?></td>
                    <td></td>
                    <td><?php echo $OfficeHead['name']; ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:left;">
                        <b><i>Legend:</i></b> <i>1 - Quantity</i> <i>2 - Efficiency</i> <i>3 - Timeliness</i> <i>4 - Average</i>
                    </td>
                </tr>
            </tbody>
        </table>
</div>

