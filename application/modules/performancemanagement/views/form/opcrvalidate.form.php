<?php 
    $form_id = $data[0]->form_id;
    $name = $data[0]->full_name;
    $position = $data[0]->position;
    $submitted_date = date('M-d-Y',strtotime($data[0]->submitted_date));
    $validated_by = $data[0]->validated_name == null ? "PENDING" : $data[0]->validated_name;
    $assessed_reviewed_by = $data[0]->assessed_reviewed_name == null ? "PENDING" : $data[0]->assessed_reviewed_name;
    $attested_by = $data[0]->attested_name;
    $final_rating_by = $data[0]->final_rating_name;
    $assessed_date = $data[0]->assessed_date == null ? "PENDING" : date('M-d-Y',strtotime($data[0]->assessed_date));
    $validate_date = $data[0]->validate_date == null ? "PENDING" : date('M-d-Y',strtotime($data[0]->validate_date));
    $attested_date = $data[0]->attested_date == null ? "PENDING" : date('M-d-Y',strtotime($data[0]->attested_date));
    $final_rating_date = $data[0]->final_rating_date == null ? "PENDING" : date('M-d-Y',strtotime($data[0]->final_rating_date));
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
        width: 90%; 
        text-align: center; 
        margin-top: 50px; 
        margin-bottom: 2px;
        border: .5px solid black;
    }

</style>

<div class = "container-fluid">
    <div class = "content table-responsive table-full-width">
        <table class = "table-style">
            <thead>
                <tr>
                    <th rowspan="2">Major Final Output (MFO)/ <br> Program, Activity & Project <br> (PAP) <br> (a)</th>
                    <th rowspan="2">Success Indicators <br> (Targets + Measures) <br> (b)</th>
                    <th rowspan="2">Alloted Budget <br> (in Pesos) <br> (c)</th>
                    <th rowspan="2">Office/Individual Accountable <br> (d)</th>
                    <th rowspan="2">Actual Accomplishments <br> (e)</th>
                    <th colspan="4">Rating <br> (f)</th>
                    <th rowspan="2">Remarks <br> (g)</th>
                </tr>
                <tr>
                    <th>Q1</th>
                    <th>E2</th>
                    <th>T3</th>
                    <th>A4</th>
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
                                <?php echo $value->q1 == 1 ? "<i class = 'fa fa-check'></i>" : "";?>
                            </td>
                            <td>
                                <?php echo $value->e2 == 1 ? "<i class = 'fa fa-check'></i>" : "";?>
                            </td>
                            <td>
                                <?php echo $value->t3 == 1 ? "<i class = 'fa fa-check'></i>" : "";?>
                            </td>
                            <td>
                                <?php echo $value->a4 == 1 ? "<i class = 'fa fa-check'></i>" : "";?>
                            </td>
                            <td>
                                <?php echo $value->remarks;?>
                            </td>
                        </tr>
                        <?php if($strategic_loop == $count_strategic): ?>
                            <tr class = "core">
                                <th colspan="11">
                                    B. Core Functions: (50%)
                                </th>
                            </tr>
                        <?php endif; ?>
                    <?php elseif($value->weight_of_output == "Core"): $core_loop++; ?>
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
                                <?php echo $value->q1 == 1 ? "<i class = 'fa fa-check'></i>" : "";?>
                            </td>
                            <td>
                                <?php echo $value->e2 == 1 ? "<i class = 'fa fa-check'></i>" : "";?>
                            </td>
                            <td>
                                <?php echo $value->t3 == 1 ? "<i class = 'fa fa-check'></i>" : "";?>
                            </td>
                            <td>
                                <?php echo $value->a4 == 1 ? "<i class = 'fa fa-check'></i>" : "";?>
                            </td>
                            <td>
                                <?php echo $value->remarks;?>
                            </td>
                        </tr>
                        <?php if($core_loop == $count_core): ?>
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
                                <?php echo $value->q1 == 1 ? "<i class = 'fa fa-check'></i>" : "";?>
                            </td>
                            <td>
                                <?php echo $value->e2 == 1 ? "<i class = 'fa fa-check'></i>" : "";?>
                            </td>
                            <td>
                                <?php echo $value->t3 == 1 ? "<i class = 'fa fa-check'></i>" : "";?>
                            </td>
                            <td>
                                <?php echo $value->a4 == 1 ? "<i class = 'fa fa-check'></i>" : "";?>
                            </td>
                            <td>
                                <?php echo $value->remarks;?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <br>
    <div class = "content table-responsive table-full-width" id = "total">
        <table class = "table-style">
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
    </div>
    <br>
    <div class = "content table-responsive table-full-width">
        <table class = "table-style">
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
    <br>
    <div class = "content table-responsive table-full-width">
        <table class = "table-style">
            <thead>
                <tr>
                    <th>Assessed and Reviewed By:</th>
                    <th>Assessed and Reviewed Date:</th>
                    <th>Validated By:</th>
                    <th>Validated Date:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php 
                            if($assessed_reviewed_by == null){
                                echo "PENDING";
                            }
                            else{
                                echo "<hr>".$assessed_reviewed_by;
                            }
                        ?>

                    </td>
                    <td>
                        <?php echo $assessed_date; ?>
                    </td>
                    <td>
                        <?php 
                            if($validated_by == null){
                                echo "PENDING";
                            }
                            else{
                                echo $validated_by;
                            }
                        ?>
                    </td>
                    <td>
                        <?php echo $validate_date; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <div class = "content table-responsive table-full-width">
        <table class = "table-style">
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
        <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
    </div>
</div>


