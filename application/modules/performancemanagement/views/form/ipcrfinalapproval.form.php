<?php 
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
    $date_assesed = $date_discussed = ($data[0]->date_assesed == null ? "PENDING" : date('M-d-Y', strtotime($data[0]->date_assesed)));
    $final_rating_by_head_of_office = $data[0]->final_rating_by_head_of_office_name;
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

</style>


<div class = "container-fluid">

    <div class = "step2_form">
        <!-- <div class = "row">
            <div class = "col-md-9">
                <div class = "typo-line">
                    <p>
                        I, <?php echo $name; ?> of the <?php echo $position; ?>, commit to deliever and agree to be rated on the attainment of the following Targets in accordance with the indicated measures for the period of <?php echo $period_of; ?>.

                    </p>
                </div>
            </div>
            <div class = "col-md-3">
                <div class = "typo-line">
                    <?php if($ratee != null): ?>
                        <p style="text-align: right;">
                            <b>Ratee:</b>
                            <?php echo $ratee; ?> 
                        </p>
                    <?php endif; ?>
                    <p style="text-align: right;">
                        <b>Date:</b>
                        <?php echo date('M-d-Y',strtotime($posted_date)); ?> 
                    </p>
                </div>
            </div>  
        </div>  -->
        <div class = "content table-responsive table-full-width">
            <table class = "form_table" style="width: 100%;">
                <tbody>
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
                        <th>DATE:</th>
                        <th>APPROVED BY:</th>
                        <th>DATE:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding-top: 40px;">
                            <?php echo $reviewed_by; ?>
                            <hr style="width: 90%; text-align: center; margin-top: 2px; margin-bottom: 2px;">
                            IMMEDIATE SUPERVISOR
                        </td>   
                        <td>
                            <?php echo $date_review; ?>
                        </td> 
                        <td style="padding-top: 40px;">
                            <?php echo $approved_by; ?>
                            <hr style="width: 90%; text-align: center; margin-top: 2px; margin-bottom: 2px;">
                            HEAD OF OFFICE
                        </td>  
                        <td>
                            <?php echo $date_approve; ?>
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
                        <th rowspan="2">
                            <label>MAJOR FINAL OUTPUT</label>
                        </th>
                        <th>
                            <label>SUCCESS INDICATORS</label>
                        </th>
                        <th rowspan="2">
                            <label>ACTUAL ACCOMPLISHMENTS</label>
                        </th>
                        <th colspan="4">
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
                        <?php if($value->weight_of_output == "Strategic"): ?>
                        <tr>
                            <td> <?php echo $value->output; ?> </td>
                            <td> <?php echo $value->success_target; ?> </td>
                            <td> <?php echo $value->actual_accomplishments; ?> </td>
                            <td> 
                                <?php echo ($value->q1 == 1 ? "<i class = 'fa fa-check'></i>" : ""); ?> 
                            </td>
                            <td> 
                                <?php echo ($value->e2 == 1 ? "<i class = 'fa fa-check'></i>" : ""); ?> 
                            </td>
                            <td> 
                                <?php echo ($value->t3 == 1 ? "<i class = 'fa fa-check'></i>" : ""); ?> 
                            </td>
                            <td> 
                                <?php echo ($value->a4 == 1 ? "<i class = 'fa fa-check'></i>" : ""); ?> 
                            </td>
                            <td> <?php echo $value->remarks; ?> </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <br>
        <div class = "content table-responsive table-full-width">
            <table class = "form_table" style="width: 100%;">
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
                        <th colspan="4">
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
                        <?php if($value->weight_of_output == "Core"): ?>
                        <tr>
                            <td> <?php echo $value->output; ?> </td>
                            <td> <?php echo $value->success_target; ?> </td>
                            <td> <?php echo $value->actual_accomplishments; ?> </td>
                            <td> 
                                <?php echo ($value->q1 == 1 ? "<i class = 'fa fa-check'></i>" : ""); ?> 
                            </td>
                            <td> 
                                <?php echo ($value->e2 == 1 ? "<i class = 'fa fa-check'></i>" : ""); ?> 
                            </td>
                            <td> 
                                <?php echo ($value->t3 == 1 ? "<i class = 'fa fa-check'></i>" : ""); ?> 
                            </td>
                            <td> 
                                <?php echo ($value->a4 == 1 ? "<i class = 'fa fa-check'></i>" : ""); ?> 
                            </td>
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
                        <?php if($value->weight_of_output == "Support"): ?>
                        <tr>
                            <td> <?php echo $value->output; ?> </td>
                            <td> <?php echo $value->success_target; ?> </td>
                            <td> <?php echo $value->actual_accomplishments; ?> </td>
                            <td> 
                                <?php echo ($value->q1 == 1 ? "<i class = 'fa fa-check'></i>" : ""); ?> 
                            </td>
                            <td> 
                                <?php echo ($value->e2 == 1 ? "<i class = 'fa fa-check'></i>" : ""); ?> 
                            </td>
                            <td> 
                                <?php echo ($value->t3 == 1 ? "<i class = 'fa fa-check'></i>" : ""); ?> 
                            </td>
                            <td> 
                                <?php echo ($value->a4 == 1 ? "<i class = 'fa fa-check'></i>" : ""); ?> 
                            </td>
                            <td> <?php echo $value->remarks; ?> </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <br>
        <div class = "content table-responsive table-full-width">
            <table class = "form_table" style="width: 100%;">
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
        </div>
        <br>
        <div class = "content table-responsive table-full-width">
            <table class = "form_table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>DISSCUSSED WITH:</th>
                        <th>DATE:</th>
                        <th>ASSESSED BY:</th>
                        <th>DATE:</th>
                        <th>FINAL RATING BY:</th>
                        <th>DATE:</th>
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
                        <td>
                            <?php echo $date_assesed; ?>
                        </td>   
                        <td style="padding-top: 40px;">
                            <?php echo $final_rating_by_head_of_office; ?>
                            <hr style="width: 90%; text-align: center; margin-top: 2px; margin-bottom: 2px;">
                            HEAD OF OFFICE
                        </td> 
                        <td>
                            <?php echo $date_final_rating; ?>
                        </td> 
                    </tr>
                </tbody>
            </table>
        </div>
        <input type="hidden" name="id" value="<?php echo $form_id; ?>">
    </div>
</div>

