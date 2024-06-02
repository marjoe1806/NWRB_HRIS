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
<style type="text/css">

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


<div class = "container-fluid">
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
            <table class = "form_table" style="width: 100%;">
                <tbody>
                    <tr>
                        <th colspan = "2" style="font-size: 25px;">
                            Division Performance Commitment and Review (DPCR)
                        </th>
                    </tr>   
                    <tr>
                        <td style="width: 80%;">
                            <b>I, <?php echo $name; ?> of the <?php echo $position; ?>, commit to deliever and agree to be rated on the attainment of the following Targets in accordance with the indicated measures for the period of <?php echo $period_of; ?>.</b>
                        </td>  
                        <td>
                            <?php if($ratee != null): ?>
                                <b>Ratee:</b>
                                <?php echo $ratee; ?> 
                            <?php endif; ?> <br>
                            <b>Date:</b>
                            <?php echo date('M-d-Y',strtotime($posted_date)); ?> 
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
                        <th>REVIEWED BY:</th>
                        <th>REVIEWED DATE:</th>
                        <th>APPROVED BY:</th>
                        <th>APPROVED DATE:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding-top: 40px;">
                            <?php echo $reviewed_by; ?>
                            <hr style="width: 90%; text-align: center; margin-top: 2px; margin-bottom: 2px;">
                            IMMEDIATE SUPERVISOR
                        </td>   
                        <td id = "reviewed_date">
                            <?php echo $date_review; ?>
                        </td> 
                        <td style="padding-top: 40px;">
                            <?php echo $approved_by; ?>
                            <hr style="width: 90%; text-align: center; margin-top: 2px; margin-bottom: 2px;">
                            DIRECTOR
                        </td>  
                        <td id = "approved_date">
                            <?php echo $date_approve; ?>
                        </td>    
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
        <div class = "content table-responsive table-full-width">
            <table class = "form_table strategic" style="width: 100%;">
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
                        <th colspan="4" id = "rating_ans" style="width:20%;">
                            <label>RATING</label><br>
                            <span id="ratings-required" style="color:red; align:center;"><span>
                        </th>
                        <th rowspan="2">
                            <label>REMARKS</label><br>
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
                </tbody>
            </table>
        </div>
        <br>
        <div class = "content table-responsive table-full-width">
            <table class = "form_table core-support" style="width: 100%;">
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
                        <th colspan="4" id = "rating_ans" style="width: 20%;">
                            <label>RATING</label><br>
                            <span id="ratings-required" style="color:red; align:center;"><span>
                        </th>
                        <th rowspan="2">
                            <label>REMARKS</label><br>
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
                    </tr>
                    <?php foreach($data as $key=>$value): ?>
                        <?php if($value->weight_of_output == 'Core'): ?>
                        <tr>
                            <td> <?php echo $value->output; ?> </td>
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
                </tbody>
            </table>
            



        </div>
        <br>
        <div class = "content table-responsive table-full-width">
        <table class = "form_table" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="text-align:left;">COMMENTS AND RECOMMENDATIONS FOR DEPELOPMENT FOR PURPOSES:</th>
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
                        <th>DISSCUSSED WITH:</th>
                        <th>DISSCUSSED DATE:</th>
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
                            <hr style="width: 90%; text-align: center; margin-top: 2px; margin-bottom: 2px;">
                            EMPLOYEE
                        </td>  
                        <td>
                            <?php echo $date_discussed; ?>
                        </td>
                        <td style="padding-top: 40px;">
                            <?php echo $assesed_by_supervisor; ?>
                            <hr style="width: 90%; text-align: center; margin-top: 2px; margin-bottom: 2px;">
                            SUPERVISOR
                        </td> 
                        <td id = "date_assesed">
                             <?php echo $date_assesed; ?>
                        </td>   
                        <td style="padding-top: 40px;">
                            <?php echo $final_rating_by_head_of_office; ?>
                            <hr style="width: 90%; text-align: center; margin-top: 2px; margin-bottom: 2px;">
                            DIRECTOR
                        </td> 
                        <td id = "date_final_rating">
                           <?php echo $date_final_rating; ?>
                        </td> 
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


<div id = "content_print">
    <style type="text/css" media="print">
        @media print {
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
                width: 100%;
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
        }
    </style>
    <center>
        <table>
            <tr>
                <th style="padding: 10px; font-size: 20px;">DIVISION PERFORMANCE COMMITMENT AND REVIEW (DPCR)</th>
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
        <table class = "print" style="width: 100%;">
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
        <table class = "print" style="width: 100%;">
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
                    <td rowspan="2" style="padding-top: 40px;">
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
            </tbody>
        </table>
        <table style="width:50%; position:absolute; left:10px; ">
            <tr>
                <td><i>Legent</i></td>
                <td><i>1 - Quantity</i></td>
                <td><i>2 - Efficiency</i></td>
                <td><i>3 - Timeliness</i></td>
                <td><i>4 - Average</i></td>
            </tr>
        </table>
    </center>
</div>

