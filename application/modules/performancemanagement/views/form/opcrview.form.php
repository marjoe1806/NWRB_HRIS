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
    $assessed_by_pmt = $data[0]->assessed_by_pmt;
    // $assessed_date = $data[0]->assessed_date == null ? "PENDING" : date('M-d-Y',strtotime($data[0]->assessed_date));

//    $officer_assessed_date =  $data[0]->officer_assessed_date;
//    var_dump($data[0]->officer_assessed_date); die();

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

    .table-style{
        width: 100%;
    }

    .table-style th{
        color: rgb(136,136,136);
        font-size: 12px;
        text-align: center;
        border: 1px solid rgb(227,227,227);
        padding: 5px;
    }

    .table-style tbody td{
        padding: 10px;
        border: 1px solid rgb(227,227,227);
        text-align: center;
    }

    .table-style input[type="checkbox"]{
        width: 20px;
    }

    .total-style{
        width: 100%;
    }
    .total-style .form-group{
        margin-bottom: 0px !important;
    }

    .total-style th{
        color: rgb(136,136,136);
        font-size: 12px;
        text-align: center;
        border: 1px solid rgb(227,227,227);
        padding: 5px;
    }

    .total-style tbody td{
        padding: 10px;
        border: 1px solid rgb(227,227,227);
        text-align: center;
    }

    .total-style input[type="checkbox"]{
        width: 20px;
    }

    input:read-only {
        background: white !important;
    }

    .input-text{
        border-radius: 5px;
        padding: 4px;
        border: 1px solid rgb(227,227,227);
        margin: 2px;
        color: black;
    }

    hr{
        width: 100%; 
        text-align: center; 
        margin-top: 2px;
        margin-bottom: 2px;
        border: 0.5px black;
       
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
            <button type = "button" rel="tooltip" title = "Assessed and Reviwed" class="btn btn-default btn-fill btn-social btn-round step2" title="">
                <i class="fa fa-file-text"></i>
            </button>
        </li>
        <li class="todo">
            <button type = "button" rel="tooltip" title = "Validated" class="btn btn-default btn-fill btn-social btn-round step3" title="">
                <i class="fa fa-thumbs-up"></i>
            </button>
        </li>
        <li class="todo">
            <button type = "button" rel="tooltip" title = "Attested" class="btn btn-default btn-fill btn-social btn-round step4" title="">
                <i class="fa fa-check"></i>
            </button>
        </li>
        <li class="todo">
            <button type = "button" rel="tooltip" title = "Final Approved" class="btn btn-default btn-fill btn-social btn-round step5" title="">
                <i class="fa fa-check"></i>
            </button>
        </li>
    </ol>  -->

    <div class = "content table-responsive table-full-width">
    <div class = "content table-responsive table-full-width">
            <table class = "table-style" style="width: 100%;">
                <tbody>
                    <tr>
                        <th colspan = "2" style="font-size: 25px;">
                            Office Performance Commitment and Review (OPCR)
                        </th>
                    </tr>   
                    <tr>
                        <td style="width: 80%;">
                            <b>I, <?php echo $name; ?> Head of the <?php echo $position; ?>, commit to deliver and agree to be rated on the attainment of the following  targets in accordance with the indicated measures for the period  <?php echo $period_of; ?>.</b>
                        </td>  
                        <td>
                            <?php if($ratee != null): ?>
                                <b>Ratee:</b>
                                <?php echo $ratee; ?> 
                            <?php endif; ?> <br>
                            <b>Date:</b>
                            <?php echo date('M-d-Y',strtotime($submitted_date)); ?> 
                        </td>    
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
        <table class = "table-style" id="tbl-ratings">
            <thead>
                <tr>
                    <th rowspan="2">Major Final Output (MFO)</th>
                    <th rowspan="2">Success Indicators <br> (Targets + Measures)</th>
                    <th rowspan="2">Alloted Budget </th>
                    <th rowspan="2">Office/Individual Accountable</th>
                    <th rowspan="2">Actual Accomplishments</th>
                    <th colspan="4" style="width:20%;">Rating<br>
                            <span id="ratings-required" style="color:red; align:center;"><span></th>
                    <th rowspan="2">Remarks</th>
                </tr>
                <tr>
                    <th>Quality</th>
                    <th>Efficiency</th>
                    <th>Timeliness</th>
                    <th>Avarage</th>
                </tr>
            </thead>
            <tbody>
                <tr class = "strat">
                    <th colspan="11">
                        A. Strategic Priorities: (30%)
                    </th>
                </tr>
                <?php foreach ($data as $key => $value): ?>
                    <?php if($value->weight_of_output == "Strategic"): $strategic_loop++; ?>
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
                            <td>
                                <center>
                                    <!-- <input type="checkbox" class = "form-control" name="strat_rating[<?php echo $count; ?>][]" value = "q1"> -->
                                    <!-- <input type="checkbox" class="checkbox_table strat_rating " id="q1checkbox<?php echo $count; ?>" name="strat_rating[<?php echo $count;?>][]" value="q1">
		                            <label for="q1checkbox<?php echo $count; ?>"></label>  -->
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number" min="1" max="5" class="form-control" name="q1[<?php echo $count;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <!-- <input type="checkbox" class = "form-control" name="strat_rating[<?php echo $count; ?>][]" value = "e2"> -->
                                    <!-- <input type="checkbox" class="checkbox_table strat_rating" id="e2checkbox<?php echo $count; ?>" name="strat_rating[<?php echo $count;?>][]" value="e2">
		                            <label for="e2checkbox<?php echo $count; ?>"></label>  -->
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number" min="1" max="5" class="form-control" name="e2[<?php echo $count;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <!-- <input type="checkbox" class = "form-control" name="strat_rating[<?php echo $count; ?>][]" value = "t3"> -->
                                    <!-- <input type="checkbox" class="checkbox_table strat_rating" id="t3checkbox<?php echo $count; ?>" name="strat_rating[<?php echo $count;?>][]" value="t3">
		                            <label for="t3checkbox<?php echo $count; ?>"></label>  -->
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number" min="1" max="5" class="form-control" name="t3[<?php echo $count;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <!-- <input type="checkbox" class = "form-control" name="strat_rating[<?php echo $count; ?>][]" value = "a4"> -->
                                    <!-- <input type="checkbox" class="checkbox_table strat_rating" id="a4checkbox<?php echo $count; ?>" name="strat_rating[<?php echo $count;?>][]" value="a4">
		                            <label for="a4checkbox<?php echo $count; ?>"></label>  -->
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number" min="1" max="5" class="form-control" name="a4[<?php echo $count;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td>
                                <input type="hidden" name="id_answer[<?php echo $count; ?>]" value = "<?php echo $value->id; ?>">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="remarks[<?php echo $count; ?>]" aria-invalid="false" required>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php if($strategic_loop == $count_strategic): ?>
                            <tr class = "support">
                                <th colspan="11">
                                    C. Support Functions: (20%)
                                </th>
                            </tr>
                        <?php endif; ?>
                    
                    <?php elseif($value->weight_of_output == "Support"): $support_loop++; ?>
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
                            <td>
                                <center>
                                    <!-- <input type="checkbox" class = "form-control" name="strat_rating[<?php echo $count; ?>][]" value = "q1"> -->
                                    <!-- <input type="checkbox" class="checkbox_table strat_rating " id="q1checkbox<?php echo $count; ?>" name="strat_rating[<?php echo $count;?>][]" value="q1">
		                            <label for="q1checkbox<?php echo $count; ?>"></label>  -->
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number" min="1" max="5" class="form-control" name="q1[<?php echo $count;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <!-- <input type="checkbox" class = "form-control" name="strat_rating[<?php echo $count; ?>][]" value = "e2"> -->
                                    <!-- <input type="checkbox" class="checkbox_table strat_rating" id="e2checkbox<?php echo $count; ?>" name="strat_rating[<?php echo $count;?>][]" value="e2">
		                            <label for="e2checkbox<?php echo $count; ?>"></label>  -->
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number" min="1" max="5" class="form-control" name="e2[<?php echo $count;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <!-- <input type="checkbox" class = "form-control" name="strat_rating[<?php echo $count; ?>][]" value = "t3"> -->
                                    <!-- <input type="checkbox" class="checkbox_table strat_rating" id="t3checkbox<?php echo $count; ?>" name="strat_rating[<?php echo $count;?>][]" value="t3">
		                            <label for="t3checkbox<?php echo $count; ?>"></label>  -->
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number" min="1" max="5" class="form-control" name="t3[<?php echo $count;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <!-- <input type="checkbox" class = "form-control" name="strat_rating[<?php echo $count; ?>][]" value = "a4"> -->
                                    <!-- <input type="checkbox" class="checkbox_table strat_rating" id="a4checkbox<?php echo $count; ?>" name="strat_rating[<?php echo $count;?>][]" value="a4">
		                            <label for="a4checkbox<?php echo $count; ?>"></label>  -->
                                    <div class="form-group">
                                       <div class="form-line">
                                          <input type="number" min="1" max="5" class="form-control" name="a4[<?php echo $count;?>]" aria-invalid="false" required>
                                       </div>
                                    </div>
                                </center>
                            </td>
                            <td>
                                <input type="hidden" name="id_answer[<?php echo $count; ?>]" value = "<?php echo $value->id; ?>">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="remarks[<?php echo $count; ?>]" aria-invalid="false" required>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php if($support_loop == $count_support): ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php $count++; ?>
                <?php endforeach; ?>
                     <tr id="final-avg-rating">
                        <th colspan="5" style="text-align:left;">
                            Final Average Rating
                        </th>
                        <td>
                            <center>
                                <div class="form-group">
                                   <div class="form-line">
                                      <input type="number" min="1" max="5" class="form-control" name="q1[<?php echo count($data) - 1; ?>]" aria-invalid="false" required>
                                   </div>
                                </div>
                            </center>
                        </td>
                        <td>
                            <center>
                                <div class="form-group">
                                   <div class="form-line">
                                      <input type="number" min="1" max="5" class="form-control" name="e2[<?php echo count($data) - 1; ?>]" aria-invalid="false" required>
                                   </div>
                                </div>
                            </center>
                        </td>
                        <td>
                            <center>
                                <div class="form-group">
                                   <div class="form-line">
                                      <input type="number" min="1" max="5" class="form-control" name="t3[<?php echo count($data) - 1; ?>]" aria-invalid="false" required>
                                   </div>
                                </div>
                            </center>
                        </td>
                        <td>
                            <center>
                                <div class="form-group">
                                   <div class="form-line">
                                      <input type="number" min="1" max="5" class="form-control" name="a4[<?php echo count($data) - 1; ?>]" aria-invalid="false" required>
                                   </div>
                                </div>
                            </center>
                        </td>
                        <td>
                            <input type="hidden" name="id_answer[<?php echo count($data) - 1; ?>]" value = "<?php echo $value->id; ?>">
                            <input type="hidden" class = "form-control" name="form_id" value="<?php echo $form_id; ?>">
                        </td>
                    </tr>
                    <!-- <tr>
                    <th colspan="11" style="text-align: right;">
                        Average Rating =  <input type="number" step="0.01" class = "input-text required" name="initial_avg_rating">
                        <input type="hidden" class = "form-control" name="form_id" value="<?php echo $form_id; ?>">
                    </th>
                    </tr> -->
            </tbody>
        </table>
    </div>
    <br>
    <!-- <div class = "content table-responsive table-full-width" id = "total">
        <table class = "total-style">
            <thead>
                <tr>
                    <th>Sub-total Strategic:</th>
                    <th>Sub-total Core:</th>
                    <th>Sub-total Support:</th>
                    <th>Grand-Total:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td id = "sub_strat"></td>
                    <td id = "sub_core"></td>
                    <td id = "sub_support"></td>
                    <td id = "grand_total"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <br> -->
    <div class = "content table-responsive table-full-width">
            <table class = "total-style" style="width: 100%;">
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
                        <td id = "strat_mfo">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="strat_mfo" aria-invalid="false" required>
                                </div>
                            </div>
                        </td> 
                        <td id = "strat_rating">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="strat_rating" aria-invalid="false" required>
                                </div>
                            </div>
                        </td>   
                    </tr>
                    <tr>
                        <td style="text-align:left;">
                            <label>Core Functions</label>
                        </td>   
                        <td id = "core_mfo">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="core_mfo" aria-invalid="false" required>
                                </div>
                            </div>
                        </td> 
                        <td id = "core_rating">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="core_rating" aria-invalid="false" required>
                                </div>
                            </div>
                        </td>   
                    </tr>
                    <tr>
                        <td style="text-align:left;">
                            <label>Support Functions</label>
                        </td>   
                        <td id = "support_mfo">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="support_mfo" aria-invalid="false" required>
                                </div>
                            </div>
                        </td> 
                        <td id = "support_rating">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="support_rating" aria-invalid="false" required>
                                </div>
                            </div>
                        </td>   
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <label>Total Overall Rating</label>
                        </td>   
                        <td id = "overall_rating_mfo">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" class="form-control" name="overall_rating_mfo" aria-invalid="false" required>
                                </div>
                            </div>
                        </td> 
                        <td id = "overall_rating">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="overall_rating" aria-invalid="false" required>
                                </div>
                            </div>
                        </td>   
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <label>Final Average Rating</label>
                        </td>   
                        <td id = "avg_rating_mfo">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" class="form-control" name="avg_rating_mfo" aria-invalid="false" required>
                                </div>
                            </div>
                        </td> 
                        <td id = "avg_rating">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="avg_rating" aria-invalid="false" required>
                                </div>
                            </div>
                        </td>   
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <label>Adjectival Rating</label>
                        </td>   
                        <td id = "adj_rating_mfo">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" class="form-control" name="adj_rating_mfo" aria-invalid="false" required>
                                </div>
                            </div>
                        </td> 
                        <td id = "adj_rating">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="adj_rating" aria-invalid="false" required>
                                </div>
                            </div>
                        </td>   
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
    <!-- <div class = "content table-responsive table-full-width">
        <table class = "total-style">
            <thead>
                <tr>
                    <th>Submitted By:</th>
                    <th>Position:</th>
                    <th>Submitted Date:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <hr>
                        <?php echo $name; ?>
                    </td>
                    <td>
                        <?php echo $position; ?>
                    </td>
                    <td>
                        <?php echo $submitted_date; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br> -->
    <div class = "content table-responsive table-full-width">
        <table class = "total-style">
            <thead>
                 <tr>
                    <th colspan="4" style="text-align:left;">Assessed By:</th>
                    <th colspan="1">Final Rating By:</th>
                    <th colspan="1">Date:</th>
                </tr>
                <!-- <tr>
                    <th>Planning Office:</th>
                    <th>Date:</th>
                    <th>PMT</th>
                    <th>Date:</th>
                    <th></th>
                    <th>Date:</th>
                </tr>    -->
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 30px;">
                    <?php 
                         if($data[0]->assessed_by_planning_officer == null){
                                echo "PENDING";
                            }
                            else{
                                echo $data[0]->assessed_by_planning_officer."<hr>"."Planning Office:";
                            }
                        ?>

                    </td>
                    <td style="padding: 30px;">
                        <?php echo date('M-d-Y',strtotime($data[0]->officer_assessed_date))."<hr>"."Date:"; ?>
                    </td>
                    <td style="padding: 30px;">
                        <?php 
                            if($assessed_by_pmt == null){
                                echo "PENDING";
                            }
                            else{
                                echo $assessed_by_pmt."<hr>"."PMT:";
                            }
                        ?>

                    </td>
                    <td style="padding: 30px;">
                        <?php echo date('M-d-Y',strtotime($data[0]->pmt_assessed_date))."<hr>"."Date:"; ?>
                    </td>
                    <td style="padding: 30px;">
                        <?php 
                            if($final_rating_by == null){
                                echo "PENDING";
                            }
                            else{
                                echo $final_rating_by."<hr>"."Head  of Agency:";
                            }
                        ?>

                    </td>
                    <td style="padding: 30px;">
                        <?php echo date('M-d-Y',strtotime($data[0]->final_rating_date)); ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <!-- <div class = "content table-responsive table-full-width">
        <table class = "total-style">
            <thead>
                <tr>
                    <th>Attested By:</th>
                    <th>Attested Date:</th>
                    <th>Final Rating By:</th>
                    <th>Final Rating Date:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php 
                            if($attested_by == null){
                                echo "PENDING";
                            }
                            else{
                                echo "<hr>".$attested_by;
                            }
                        ?>

                    </td>
                    <td>
                        <?php echo $attested_date; ?>
                    </td>
                    <td>
                        <?php 
                            if($final_rating_by == null){
                                echo "PENDING";
                            }
                            else{
                                echo $final_rating_by;
                            }
                        ?>
                    </td>
                    <td>
                        <?php echo $final_rating_date; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div> -->
</div>

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
        }
    </style>
    <center>
        <table >
            <tr>
                <th style="padding: 10px; font-size: 20px;">OFFICE PERFORMANCE COMMITMENT AND REVIEW (OPCR)</th>
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
        <table class = "print">
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
        <table class = "print">
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
        <!-- <table class = "print">
            <thead>
                <tr>
                    <th>Sub-total Strategic:</th>
                    <th>Sub-total Core:</th>
                    <th>Sub-total Support:</th>
                    <th>Grand-Total:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php echo $data[0]->subtotal_strat; ?>
                    </td>
                    <td>
                        <?php echo $data[0]->subtotal_core; ?>
                    </td>
                    <td>
                        <?php echo $data[0]->subtotal_support; ?>
                    </td>
                    <td>
                        <?php echo $data[0]->grand_total; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <br> -->
        <table class = "print">
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
        <table class = "print">
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

