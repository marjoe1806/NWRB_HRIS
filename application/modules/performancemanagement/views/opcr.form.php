<style type="text/css">
    .input-text{
        border-radius: 5px;
        padding: 4px;
        border: 1px solid rgb(227,227,227);
        margin: 2px;
        color: black;
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
    }

    .table-style input[type="checkbox"]{
        width: 20px;
    }

    input:read-only {
        background: white !important;
    }
</style>
<div class="row clearfix" id="userLevelForm">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="card">
         <div class="header bg-blue">
            <h2>
               OPCR 
            </h2>
         </div>
         <div class="content" style="background:; width:95%; margin:auto;">
         <form id = "opcr_form" style="font-size: 15px;"><br><br><br>
         <div class="row">
               <div class="col-md-4 pull-right">
                  <label class="form-label pull-right">SERVICE / DIVISION / UNIT <span class="text-danger"></span></label>
                  <div class="form-group">
                     <div class="form-line division_select">
                           <select data-size="5" class="division_id form-control " name="division_id" id="division_id" data-live-search="true" required>
                              <option value=""></option>
                           </select>
                     </div>
                  </div>
               </div>
            </div>
               <table class = "table-style" style="width: 100%;">
                  <tr>
                     <th>Employee:</th>
                     <th>Head Department of:</th>
                     <th>Period</th>
                  </tr>
                  <tr>
                     <td>
                        <div class="form-group">
                           <div class="form-line employee_select">
                              <select data-size="5" class="employee_id form-control" name="employee_id" id="name" data-live-search="true" required>
                                 <option disabled selected></option>
                              </select>
                           </div>
                        </div>
                     </td>
                     <td>
                        <div class="form-group">
                           <div class="form-group">
                              <div class="form-line">
                                 <input type="text" class="form-control is_first_col_required " name="position" aria-invalid="false" required>
                              </div>
                           </div>
                        </div>
                     </td>
                     <td>
                        <div class="form-group">
                           <div class="form-line">
                              <input type="text" class="form-control is_first_col_required " name="period_of" aria-invalid="false" required>
                           </div>
                        </div>
                     </td>
                  </tr>
               </table><br>
               <table class = "table-style" style="width: 100%;">
                  <tr>
                     <th>APPROVER:</th>
                     <th>DATE APPROVED:</th>
                  </tr>
                  <tr>
                  <td>
                        <div class="form-group">
                           <div class="form-line">
                              <select data-size="5" class="approved_by form-control" name="approved_by" id="approved_by" data-live-search="true" required>
                                 <option></option>
                                    <?php foreach ($get_employee as $key => $value): ?>
                                        <option value="<?php echo $value->id ?>">
                                        <?php echo $value->emp_id.' - '.$value->f_name.' '.$value->m_name.' '.$value->l_name; ?>
                                        </option>
                                    <?php endforeach; ?>
                            </select>
                           </div>
                        </div>
                     </td>
                     <td>
                        <div class="form-group">
                           <div class="form-line">
                              <input type="text" value="<?php echo date('Y-m-d') ?>" class="form-control date_approved opcr_date  " name="date_approved" id="date_approved" aria-invalid="false" required>
                           </div>
                        </div>
                     </td>
                  </tr>
               </table>
                    <div class = "content table-responsive table-full-width">
                        <table class = " table-style">
                            <thead>
                                <tr>
                                    <th colspan="5">EQUIVALENT WEIGHT OF OUTPUT</th>
                                </tr>
                                <tr>
                                    <th>Functions</th>
                                    <th>Strategic</th>
                                    <th>Core</th>
                                    <th>Support</th>
                                    <th>Total</th>
                                </tr>
                                <tr>
                                    <th>Weight</th>
                                    <th>30%</th>
                                    <th>50%</th>
                                    <th>20%</th>
                                    <th>100%</th>
                                </tr>
                            </thead>
                        </table>
                    </div>  
                    <div class = "content table-responsive table-full-width">
                        <table class = " table-style">
                            <thead>
                                <tr>
                                    <th rowspan="2">Major Final Output (MFO)/(PAP)</th>
                                    <th rowspan="2">Success Indicators <br> (Targets + Measures)</th>
                                    <th rowspan="2">Alloted Budget</th>
                                    <th rowspan="2">Division/ Individuals Accountable</th>
                                    <th rowspan="2">Actual Accomplishments</th>
                                    <!-- <th colspan="4">Rating <br> (f)</th>
                                    <th rowspan="2">Remarks <br> (g)</th> -->
                                    <th rowspan="2">Action</th>
                                    </tr>
                            </thead>
                            <tbody>
                                <tr class = "strat">
                                    <th colspan="6">
                                         Strategic Priorities:
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control is_first_col_required " name="strat_major[0]" aria-invalid="false" required>
                                            </div>
                                        </div>
                                    </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control is_first_col_required " name="strat_success[0]" aria-invalid="false" required>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control is_first_col_required " name="strat_alloted[0]" aria-invalid="false" required>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control is_first_col_required " name="strat_office[0]" aria-invalid="false" required>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control is_first_col_required " name="strat_actual[0]" aria-invalid="false" required>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <center>

                                            <button class="btn btn-success btn-circle waves-effect waves-circle waves-float add_strat" 
                                                    data-toggle="tooltip" 
                                                    data-placement="top" 
                                                    title="" 
                                                    data-original-title="Add row"> 
                                                <i class = "fa fa-plus"></i>
                                            <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float remove_strat" 
                                                    data-toggle="tooltip" 
                                                    data-placement="top" 
                                                    title="" 
                                                    data-original-title="Delete row"> 
                                                <i class = "fa fa-minus"></i>
                                            </button>   
                                        </center>    
                                    </td>
                                </tr>
                                <!-- <tr class = "core">
                                    <th colspan="6">
                                        B. Core Functions: (50%)
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control is_first_col_required " name="core_major[0]" aria-invalid="false" required>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control is_first_col_required " name="core_success[0]" aria-invalid="false" required>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control is_first_col_required " name="core_alloted[0]" aria-invalid="false" required>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control is_first_col_required " name="core_office[0]" aria-invalid="false" required>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control is_first_col_required " name="core_actual[0]" aria-invalid="false" required>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <center>

                                            <button class="btn btn-success btn-circle waves-effect waves-circle waves-float add_core" 
                                                    data-toggle="tooltip" 
                                                    data-placement="top" 
                                                    title="" 
                                                    data-original-title="Add row"> 
                                                <i class = "fa fa-plus"></i>
                                            <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float remove_core" 
                                                    data-toggle="tooltip" 
                                                    data-placement="top" 
                                                    title="" 
                                                    data-original-title="Delete row"> 
                                                <i class = "fa fa-minus"></i>
                                            </button>   
                                        </center>    
                                    </td>
                                </tr> -->
                                <tr class = "support">
                                    <th colspan="6">
                                         Support Functions:
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control is_first_col_required " name="support_major[0]" aria-invalid="false" required>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control is_first_col_required " name="support_success[0]" aria-invalid="false" required>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control is_first_col_required " name="support_alloted[0]" aria-invalid="false" required>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control is_first_col_required " name="support_office[0]" aria-invalid="false" required>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control is_first_col_required " name="support_actual[0]" aria-invalid="false" required>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <center>
                                            <!-- <button type = "button" class = "btn btn-success btn-social btn-simple add_support">
                                                <i class = "fa fa-plus"></i>
                                            </button>
                                            <button type = "button" class = "btn btn-danger btn-social btn-simple remove_support">
                                                <i class = "fa fa-minus"></i>
                                            </button>  -->

                                            <button class="btn btn-success btn-circle waves-effect waves-circle waves-float add_support" 
                                                    data-toggle="tooltip" 
                                                    data-placement="top" 
                                                    title="" 
                                                    data-original-title="Add row"> 
                                                <i class = "fa fa-plus"></i>
                                            <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float remove_support" 
                                                    data-toggle="tooltip" 
                                                    data-placement="top" 
                                                    title="" 
                                                    data-original-title="Delete row"> 
                                                <i class = "fa fa-minus"></i>
                                            </button>    
                                        </center>    
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div><br> 
                        <table class = "table-style">
                            <thead>
                            <tr>
                                    <th colspan="4"><label>Assesed By:</label></th>
                                    <th colspan="2"><label>Final Rating By:</label></th>   
                                </tr>
                                <tr>
                                    <th style="width: 350px;">Planning Office:</th>   
                                    <th >Date:</th> 
                                    <th style="width: 350px;">PMT:</th>
                                    <th>Date:</th>  
                                    <th style="width: 350px;">Head of Agency:</th>
                                    <th>Date:</th>  
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select data-size="5" class="assessed_by_planning_officer form-control" name="assessed_by_planning_officer" id="assessed_by_planning_officer" data-live-search="true" required>
                                                    <option></option>
                                                    <?php foreach ($get_employee as $key => $value): ?>
                                                        <option value="<?php echo $value->id ?>">
                                                        <?php echo $value->emp_id.' - '.$value->f_name.' '.$value->m_name.' '.$value->l_name; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="opcr_date form-control is_first_col_required " name="officer_assessed_date" value = "<?php echo date('Y-m-d'); ?>" >
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select data-size="5" class="assessed_by_pmt form-control" name="assessed_by_pmt" id="assessed_by_pmt" data-live-search="true" required>
                                                    <option></option>
                                                    <?php foreach ($get_employee as $key => $value): ?>
                                                        <option value="<?php echo $value->id ?>">
                                                        <?php echo $value->emp_id.' - '.$value->f_name.' '.$value->m_name.' '.$value->l_name; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="opcr_date form-control is_first_col_required " name="pmt_assessed_date" value = "<?php echo date('Y-m-d'); ?>" >
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select data-size="5" class="final_rating_by form-control" name="final_rating_by" id="final_rating_by" data-live-search="true" required>
                                                    <option></option>
                                                    <?php foreach ($get_employee as $key => $value): ?>
                                                        <option value="<?php echo $value->id ?>">
                                                        <?php echo $value->emp_id.' - '.$value->f_name.' '.$value->m_name.' '.$value->l_name; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="opcr_date form-control is_first_col_required " name="final_rating_date" value = "<?php echo date('Y-m-d'); ?>" >
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table><br><br>
                    <div class = "form-group">
                        <button type = "submit" class = "btn btn-success btn-fill btn-wd btn-lg" style="width: 100%;">
                            Submit
                        </button>
                    </div><br>
                </form>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript" src = "<?php echo base_url(); ?>assets/modules/js/performancemanagement/opcr.js"></script>