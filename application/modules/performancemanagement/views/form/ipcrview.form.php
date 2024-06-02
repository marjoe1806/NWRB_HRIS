<?php 
   // var_dump($data); die();
    // print_r($approvedBy['name']);
    // print_r($reviewedBy['name']);
    // print_r($reviewedBy['name']);
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
<style type="text/css">
    *{
        color: black;
    }
    /* CHANGE COLOR HERE */ 
    ol.etapier li.done {
      border-color: yellowgreen ;
    }
    /* CHANGE COLOR HERE */ 
    ol.etapier li.done:before {
        background-color: yellowgreen;
        border-color: yellowgreen;
    }
    ol.etapier {
        display: table;
        list-style-type: none;
        margin: 0 auto 20px auto;
        padding: 0;
        table-layout: fixed;
        width: 100%;
    }
    ol.etapier a {
        display: table-cell;
        text-align: center;
        white-space: nowrap;
        position: relative;
    }
    ol.etapier a li {
        display: block;
        text-align: center;
        white-space: nowrap;
        position: relative;
    }
    ol.etapier li {
        display: table-cell;
        text-align: center;
        padding-bottom: 10px;
        white-space: nowrap;
        position: relative;
    }

    ol.etapier li a {
        color: inherit;
    }

    ol.etapier li {
        color: silver; 
        border-bottom: 4px solid silver;
    }
    ol.etapier li.done {
        color: black;
    }

    ol.etapier li:before {
        position: absolute;
        bottom: -11px;
        left: 50%;
        margin-left: -7.5px;

        color: white;
        height: 15px;
        width: 15px;
        line-height: 15px;
        border: 2px solid silver;
        border-radius: 15px;
        
    }
    ol.etapier li.done:before {
        content: "\2713";
        color: white;
    }
    ol.etapier li.todo:before {
        content: " " ;
        background-color: white;
    }

    .form_table th{
        border: 1px solid rgb(227,227,227);
        text-align: center;
        padding: 5px;
    }

    .form_table td{
        border: 1px solid rgb(227,227,227);
        text-align: center;
        padding: 5px;
    }

    .form_table input[type="checkbox"]{
        width: 20px;
    }
    .form_table .form-group{
        margin-bottom: 0px !important;
    }
</style>


<div class = "container-fluid card">
    <!-- <ol class="etapier">
        <li class="todo">
            <button type = "button" rel="tooltip" title = "Fill-up" class="btn btn-default btn-fill btn-social btn-round step1" title="">
                <i class="fa fa-pencil"></i>
            </button>
        </li>
        <li class="todo">
            <button type = "button" rel="tooltip" title = "Answered" class="btn btn-default btn-fill btn-social btn-round step2" title="">
                <i class="fa fa-file-text"></i>
            </button>
        </li>
        <li class="todo">
            <button type = "button" rel="tooltip" title = "Validated" class="btn btn-default btn-fill btn-social btn-round step3" title="">
                <i class="fa fa-thumbs-up"></i>
            </button>
        </li>
        <li class="todo">
            <button type = "button" rel="tooltip" title = "Approved" class="btn btn-default btn-fill btn-social btn-round step4" title="">
                <i class="fa fa-check"></i>
            </button>
        </li>
        <li class="todo">
            <button type = "button" rel="tooltip" title = "Final Approved" class="btn btn-default btn-fill btn-social btn-round step5" title="">
                <i class="fa fa-check"></i>
            </button>
        </li>
    </ol>  -->
    <div class = "step2_form" id = "">
        <style type="text/css" media="print">
            @media print {
                @page { 
                    size: legal landscape;
                    width:816px;
                    height:1344px;
                    margin: 10mm 10mm 10mm 10mm;
                }

                #foot label{
                    color: black;
                    font-family: arial;
                    font-size: 10pt;
                }
            }
        </style>
        <div class = "content table-responsive table-full-width">
            <table class = "" style="width: 100%;">
                <tbody>
                    <tr>
                        <th colspan = "4" style="font-size: 25px; text-align:center;">
                            Individual Performance Commitment and Review (IPCR)
                        </th>
                    </tr>   
                    <tr>
                        <td colspan="4" style="text-align:center;">
                            <b>I, <?php echo $name; ?> of the <?php echo $position; ?>, commit to deliever and agree to be rated on the attainment of the following Targets in accordance with the indicated measures for the period of <?php echo $period_of; ?>.</b>
                        </td>    
                    </tr>
                  
                </tbody>
            </table>
        </div>
        <br>
        <div class = "content table-responsive table-full-width">
            <table class = "form_table" style="width: 100%;">
                <thead>
                    <tr>
                        <td colspan="3" rowspan="3" style="border: 1px solid #FFF;"></td>
                        <td style="text-align:center; padding-top: 20px; border: 1px solid #FFF;" >
                            <?php if($ratee != null): ?>
                              <b>  <?php echo $discussed_with_emp; ?> </b>
                            <?php endif; ?> <br>
                        </td>  
                    </tr>
                    <tr style="border: 1px solid #FFF;">
                        <td style="text-align:center; border: 1px solid #FFF;" >
                            Employee
                        </td>  
                    </tr>
                    <tr >
                        
                        <td style="text-align:center; border: 1px solid #FFF;"> 
                            <b>Date:</b>&nbsp;&nbsp;
                            <?php echo date('F d, Y',strtotime($posted_date)); ?> 
                        </td>
                    </tr>
                    <tr >
                        <td colspan="4"></td>
                    </tr>
                    <tr>
                        <th>REVIEWED BY:</th>
                        <th>REVIEWED DATE:</th>
                        <th>APPROVED BY:</th>
                        <th>APPROVED DATE:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding-top: 40px;">
                           <b> <?php echo $reviewed_by; ?></b>
                           
                        </td>   
                        <td  style="padding-top: 40px;">
                            <?php echo $date_review; ?>
                        </td> 
                        <td style="padding-top: 40px;">
                           <b> <?php echo $approved_by; ?></b>
                        </td>  
                        <td  style="padding-top: 40px;">
                            <?php echo $date_approve; ?>
                        </td>    
                    </tr>
                    <tr>
                        <td><?php echo $reviewedBy['name']; ?></td>
                        <td></td>
                        <td><?php echo $approvedBy['name']; ?></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
        <div class = "content table-responsive table-full-width">
            <table class = "form_table strategic" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 200px" rowspan="2">
                            <label>PROGRAM</label>
                        </th>
                        <th style="width: 300px">
                            <label>SUCCESS INDICATORS</label>
                        </th>
                        <th style="width: 350px" rowspan="2">
                            <label>ACTUAL ACCOMPLISHMENTS</label>
                        </th>
                        <th colspan="4" id = "rating_ans" style="width:300px;">
                            <label>RATING</label><br>
                            <span id="ratings-required" style="color:red; align:center;"><span>
                        </th>
                        <th rowspan="2">
                            <label>REMARKS</label><br>
                        </th>
                    </tr>
                    <tr>
                        <th style="width: 300px">
                            <label>(TARGETS + MEASURES)</label>
                        </th>
                        <th style="width: 100px">
                            <label>Quality</label>
                        </th>
                        <th style="width: 100px">
                            <label>Efficiency</label>
                        </th>
                        <th style="width: 100px">
                            <label>Timeliness</label>
                        </th>
                        <th style="width: 100px">
                            <label>Average</label>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="strategic">
                        <th colspan="8" style="text-align:left;"><b>Strategic Function</b></th>
                    </tr>
                    <?php foreach($data as $key=>$value): ?>
                        <?php if($value->weight_of_output == 'Strategic'): ?>
                        <tr>
                            <td style="width: 200px"> <?php echo $value->output; ?> </td>
                            <td style="width: 300px"> <?php echo $value->success_target; ?> </td>
                            <td style="width: 350px"> <?php echo $value->actual_accomplishments; ?> </td>
                            <td style="width: 100px"> 
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number" min="1" max="5" class="form-control" name="q1[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td style="width: 100px"> 
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number"  min="1" max="5" class="form-control" name="e2[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td style="width: 100px"> 
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number"  min="1" max="5" class="form-control" name="t3[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td style="width: 100px">  
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number"  min="1" max="5" class="form-control" name="a4[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="text" class="form-control" name="remarks[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                    <input type="hidden" name="id[<?php echo $key;?>]" value="<?php echo $value->id; ?>">
                                </center>
                            </td>
                        </tr>
                        <?php endif ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>
        <div class = "content table-responsive table-full-width">
            <table class = "form_table core-support Final-Rating" style="width: 100%;">
            <thead>
                    <tr>
                        <th style="width: 200px" rowspan="2">
                            <label>PROGRAM</label>
                        </th>
                        <th style="width: 300px">
                            <label>SUCCESS INDICATORS</label>
                        </th>
                        <th style="width: 350px" rowspan="2">
                            <label>ACTUAL ACCOMPLISHMENTS</label>
                        </th>
                        <th colspan="4" id = "rating_ans" style="width:300px;">
                            <label>RATING</label><br>
                            <span id="ratings-required" style="color:red; align:center;"><span>
                        </th>
                        <th rowspan="2">
                            <label>REMARKS</label><br>
                        </th>
                    </tr>
                    <tr>
                        <th style="width: 300px">
                            <label>(TARGETS + MEASURES)</label>
                        </th>
                        <th style="width: 100px">
                            <label>Quality</label>
                        </th>
                        <th style="width: 100px">
                            <label>Efficiency</label>
                        </th>
                        <th style="width: 100px">
                            <label>Timeliness</label>
                        </th>
                        <th style="width: 100px">
                            <label>Average</label>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th colspan="8" style="text-align:left;">Core Functions</th>
                    </tr>
                    <?php foreach($data as $key=>$value): ?>
                        <?php if($value->weight_of_output == 'Core'): ?>
                        <tr>
                            <td style="width: 200px"> <?php echo $value->output; ?> </td>
                            <td style="width: 300px"> <?php echo $value->success_target; ?> </td>
                            <td style="width: 350px"> <?php echo $value->actual_accomplishments; ?> </td>
                            <td style="width:100px">  
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number" min="1" max="5" class="form-control" name="q1[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td style="width:100px"> 
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number"  min="1" max="5" class="form-control" name="e2[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td style="width:100px">  
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number"  min="1" max="5" class="form-control" name="t3[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td style="width:100px"> 
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number"  min="1" max="5" class="form-control" name="a4[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="text" class="form-control" name="remarks[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                    <input type="hidden" name="id[<?php echo $key;?>]" value="<?php echo $value->id; ?>">
                                </center>
                            </td>
                        </tr>
                        <?php endif ?>
                    <?php endforeach; ?>
                    <tr>
                        <th colspan="8" style="text-align:left;">Support Functions</th>
                    </tr>
                    <?php foreach($data as $key=>$value): ?>
                        <?php if($value->weight_of_output == 'Support'): ?>
                        <tr>
                            <td style="width: 200px"> <?php echo $value->output; ?> </td>
                            <td> <?php echo $value->success_target; ?> </td>
                            <td> <?php echo $value->actual_accomplishments; ?> </td>
                            <td> 
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number" min="1" max="5" class="form-control" name="q1[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td> 
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number"  min="1" max="5" class="form-control" name="e2[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td> 
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number"  min="1" max="5" class="form-control" name="t3[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td> 
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number"  min="1" max="5" class="form-control" name="a4[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="text" class="form-control" name="remarks[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                    <input type="hidden" name="id[<?php echo $key;?>]" value="<?php echo $value->id; ?>">
                                </center>
                            </td>
                        </tr>
                        <?php endif ?>
                    <?php endforeach; ?>
                     <?php foreach($data as $key=>$value): ?>
                        <?php if($value->weight_of_output == 'Final-Rating'): ?>
                        <tr>
                            <th colspan = "3" style="text-align:left;" id="final_avg_ratings">Final Average Ratings</th>
                            <td> 
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number" min="1" max="5" class="form-control" name="q1[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td> 
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number"  min="1" max="5" class="form-control" name="e2[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td> 
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number"  min="1" max="5" class="form-control" name="t3[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td> 
                                <center>
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number"  min="1" max="5" class="form-control" name="a4[<?php echo $key;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <input type="hidden" name="id[<?php echo $key;?>]" value="<?php echo $value->id; ?>">
                                </center>
                            </td>
                        </tr>
                        <?php endif ?>
                    <?php endforeach; ?>
                    <!-- <tr>
                        <th colspan="3" style="text-align:left; padding: 1px solid black;" id="final_avg_ratings"> Adjectival Rating </th>
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
                    </tr> -->
                </tbody>
            </table>
        </div>
        <br>
        <div class = "content table-responsive table-full-width">
        <table class = "form_table" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="text-align:left;">COMMENTS AND RECOMMENDATIONS FOR DEVELOPMENT FOR PURPOSES:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr> 
                        <td style="padding-top: 40px;"  style="text-align:left;" id="comments">
                            <div class="form-group">
                                <textarea class="form-control" name="comments" rows="3"></textarea>
                            </div>
                        </td> 
                    </tr>
                </tbody>
            </table>
            
        </div>
        <br>
        <div class = "content table-responsive table-full-width">
            <table class = "form_table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>DISCUSSED WITH:</th>
                        <th>DISCUSSED DATE:</th>
                        <th>ASSESSED BY:</th>
                        <th>ASSESSED DATE:</th>
                        <th>FINAL RATING BY:</th>
                        <th>FINAL RATE DATE:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding-top: 40px;">
                            <?php echo $discussed_with_emp; ?>
                        </td>  
                        <td>
                            <?php echo $date_discussed; ?>
                        </td>
                        <td style="padding-top: 40px;">
                            <?php echo $assesed_by_supervisor; ?> 
                        </td> 
                        <td id = "date_assesed">
                             <?php echo $date_assesed; ?>
                        </td>   
                        <td style="padding-top: 40px;">
                            <?php echo $final_rating_by_head_of_office; ?>
                        </td> 
                        <td id = "date_final_rating">
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
                </tbody>
            </table>
        </div>
        <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
        <br>
        <?php if($ratee == null): ?>
            <div class = "form-group">
                <label>Ratee:</label>
                <select class = "form-control select required" name="ratee">
                    <?php foreach ($users as $key => $value): ?>
                        <option></option>
                        <option value="<?php echo $value->user_id ?>">
                            <?php echo $value->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>   
                <!-- <input type="text" class = "form-control required" name="ratee"> -->
            </div>
        <?php endif; ?>
    </div>
</div>


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

