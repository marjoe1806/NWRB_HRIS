<style type="text/css">
   .input-text{
   border-radius: 5px;
   padding: 4px;
   border: 1px solid rgb(227,227,227);
   margin: 2px;
   color: black;
   }
   .input-text-table{
   border-radius: 5px;
   padding: 4px;
   border: 1px solid rgb(227,227,227);
   margin: 2px;
   width: 100%;
   text-align: center;
   }
   #ipcr_table label{
   color: rgb(136,136,136);
   font-size: 12px;
   }
   #ipcr_table tr td{
   border: 1px solid rgb(227,227,227);
   text-align: center;
   padding: 5px;
   }
   #ipcr_table_fourth .form-control{
   text-align: center;
   }
   /*#ipcr_table tr:nth-child(1) td{
   border: 0px;
   }*/
   #ipcr_table tr:nth-child(4) td table {
   border: 0px !important;
   }
   #ipcr_table_second tr td{
   border: 1px solid rgb(227,227,227);
   text-align: center;
   }
   #ipcr_table_second tr th{
   border: 1px solid rgb(227,227,227);
   text-align: center;
   padding: 5px;
   }
   #ipcr_table_second tbody td{
   padding: 8px;
   }
   #ipcr_table_second input[type="checkbox"]{
   width: 20px;
   }
   #ipcr_table_third tr td{
   border: 1px solid rgb(227,227,227);
   text-align: center;
   padding: 5px;
   }
   #ipcr_table_third label{
   color: rgb(136,136,136);
   font-size: 12px;
   }
   #ipcr_table_fourth tr td{
   border: 1px solid rgb(227,227,227);
   text-align: center;
   padding: 5px;
   }
   #ipcr_table_fourth label{
   color: rgb(136,136,136);
   font-size: 12px;
   }
   input:read-only {
   background: white !important;
   }
   .table-style tr th{
   border: 1px solid rgb(227,227,227);
   text-align: center;
   padding: 5px;
   color: rgb(136,136,136);
   font-size: 12px;
   }
   .table-style td{
   padding: 5px;
   border: 1px solid rgb(227,227,227);
   }
   .table-style .form-group, #ipcr_table .form-group, #ipcr_table_second .form-group, #ipcr_table_fourth .form-group{
   margin-bottom: 0px !important;
   }
</style>
<div class="row clearfix" id="userLevelForm">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="card">
         <div class="header bg-blue">
            <h2>
               IPCR 
            </h2>
         </div>
         <div class="content" style="background:; width:95%; margin:auto;">
         
         <form id = "ipcr_form" style="font-size: 15px;">
            <div class="row">
               <div class="col-md-4 pull-right">
                  <label class="form-label pull-right">SERVICE / DIVISION / UNIT <span class="text-danger"></span></label>
                  <div class="form-group">
                     <div class="form-line division_select">
                           <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" required>
                              <option value=""></option>
                           </select>
                     </div>
                  </div>
               </div>
            </div>
               <table class = "table-style" style="width: 100%;">
                  <tr>
                     <th>Employee:</th>
                     <th>Position Department:</th>
                     <th>Period</th>
                  </tr>
                  <tr>
                     <td>
                        <div class="form-group">
                           <div class="form-line employee_select">
                              <select class="employee_id form-control" name="employee_id" id="employee_id" data-live-search="true" required>
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
               <!-- <table id = "ipcr_table" style="width: 100%;">
                  <tr>
                     <td>
                        <label>REVIEWED BY:</label>
                     </td>
                     <td>
                        <label>APPROVED BY:</label>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <div class="form-group">
                           <div class="form-line">
                              <select class="reviewed_by form-control" name="reviewed_by" id="reviewed_by" data-live-search="true" required>
                                 <option></option>
                                 <?php foreach ($immediate_supervisor as $key => $value): ?>
                                 <option value="<?php echo $value->employee_id ?>">
                                    <?php echo $value->username; ?>
                                 </option>
                                 <?php endforeach; ?>
                              </select>
                           </div>
                        </div>
                        <center>
                           <label>IMMEDIATE SUPERVISOR</label>
                        </center>
                     </td>
                     <td>
                        <div class="form-group">
                           <div class="form-line">
                              <select class="approved_by form-control" name="approved_by" id="approved_by" data-live-search="true" required>
                                 <option></option>
                                 <?php foreach ($get_head_of_office as $key => $value): ?>
                                 <option value="<?php echo $value->employee_id ?>">
                                    <?php echo $value->username; ?>
                                 </option>
                                 <?php endforeach; ?>
                              </select>
                           </div>
                        </div>
                        <center>
                           <label>HEAD OF OFFICE</label>
                        </center>
                     </td>
                  </tr>
               </table>
               <br><br> -->
               <table class = "table-style" style="width: 100%;">
                  <thead>
                        <tr>
                           <th>Reviewed by:</th>   
                           <th>Date:</th> 
                           <th>Approved by:</th>
                           <th>Date:</th>  
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td>
                                 <div class="form-group">
                                    <div class="form-line">
                                       <select data-size="5" class="reviewed_by form-control" name="reviewed_by" id="reviewed_by" data-live-search="true" required>
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
                                       <input type="text" class="ipcr_date form-control date_review " name="date_review" value = "<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                 </div>
                           </td>
                           <td>
                                 <div class="form-group">
                                    <div class="form-line">
                                       <select class="approved_by form-control" name="approved_by" id="approved_by" data-live-search="true" required>
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
                                       <input type="text" class="date_approve ipcr_date form-control is_first_col_required " name="date_approve" value = "<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                 </div>
                           </td>
                        </tr>
                     </tbody>
               </table><br><br>
               <div class="content table-responsive table-full-width">
                  <table id = "ipcr_table_second" style="width: 100%;">
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
                           <th rowspan="2">
                              <label>ACTION</label>
                           </th>
                        </tr>
                        <tr>
                           <th>
                              <label>(TARGETS + MEASURES)</label>
                           </th>
                        </tr>
                     </thead>
                     <tbody>
                     <tr class = "strat"  data-count="00">
                           <td colspan="4">
                                 <label>A. Strategic Function</label>
                           </td>
                        </tr>
                        <tr class = "strat" data-count="0">
                           <td>
                              <div class="form-group">
                                 <div class="form-group">
                                    <div class="form-line">
                                       <input type="text" class="form-control is_first_col_required " name="strat_output[0]" aria-invalid="false" required>
                                    </div>
                                 </div>
                              </div>
                           </td>
                           <td>
                              <div class="form-group">
                                 <div class="form-group">
                                    <div class="form-line">
                                       <input type="text" class="form-control is_first_col_required" name="strat_success_ind[0]" aria-invalid="false" required>
                                    </div>
                                 </div>
                              </div>
                           </td>
                           <td>
                              <div class="form-group">
                                 <div class="form-group">
                                    <div class="form-line">
                                       <input type="text" class="form-control is_first_col_required" name="strat_actual_accom[0]" aria-invalid="false" required>
                                    </div>
                                 </div>
                              </div>
                           </td>
                           <td>
                              <center>
                                 <button class="btn btn-success btn-circle waves-effect waves-circle waves-float add" 
                                    data-toggle="tooltip" 
                                    data-placement="top" 
                                    data-key="strat"
                                    title="" 
                                    type="button"
                                    data-original-title="Add row"> 
                                 <i class = "fa fa-plus"></i>
                                 </button>
                                 <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float remove" 
                                    data-toggle="tooltip" 
                                    data-placement="top"
                                    data-key="strat" 
                                    title="" 
                                    type="button"
                                    data-original-title="Delete row"> 
                                 <i class = "fa fa-minus"></i>
                                 </button>
                              </center>
                           </td>
                        </tr>
                        <tr class = "core">
                           <th colspan="4">
                                 A. Core Function
                           </th>
                        </tr>
                        <tr class = "core">
                           <td>
                              <div class="form-group">
                                 <div class="form-group">
                                    <div class="form-line">
                                       <input type="text" class="form-control is_first_col_required " name="core_output[0]" aria-invalid="false" required>
                                    </div>
                                 </div>
                              </div>
                           </td>
                           <td>
                              <div class="form-group">
                                 <div class="form-group">
                                    <div class="form-line">
                                       <input type="text" class="form-control is_first_col_required" name="core_success_ind[0]" aria-invalid="false" required>
                                    </div>
                                 </div>
                              </div>
                           </td>
                           <td>
                              <div class="form-group">
                                 <div class="form-group">
                                    <div class="form-line">
                                       <input type="text" class="form-control is_first_col_required" name="core_actual_accom[0]" aria-invalid="false" required>
                                    </div>
                                 </div>
                              </div>
                           </td>
                           <td>
                              <center>
                                 <button class="btn btn-success btn-circle waves-effect waves-circle waves-float add" 
                                    data-toggle="tooltip" 
                                    data-placement="top" 
                                    data-key="core"
                                    title="" 
                                    type="button"
                                    data-original-title="Add row"> 
                                 <i class = "fa fa-plus"></i>
                                 </button>
                                 <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float remove" 
                                    data-toggle="tooltip" 
                                    data-placement="top" 
                                    data-key="core"
                                    title="" 
                                    type="button"
                                    data-original-title="Delete row"> 
                                 <i class = "fa fa-minus"></i>
                                 </button>
                              </center>
                           </td>
                        </tr>
                        <tr class = "support">
                           <th colspan="4">
                                 B. Support Function
                           </th>
                        </tr>
                        <tr class = "support">
                           <td>
                              <div class="form-group">
                                 <div class="form-group">
                                    <div class="form-line">
                                       <input type="text" class="form-control is_first_col_required " name="support_output[0]" aria-invalid="false" required>
                                    </div>
                                 </div>
                              </div>
                           </td>
                           <td>
                              <div class="form-group">
                                 <div class="form-group">
                                    <div class="form-line">
                                       <input type="text" class="form-control is_first_col_required" name="support_success_ind[0]" aria-invalid="false" required>
                                    </div>
                                 </div>
                              </div>
                           </td>
                           <td>
                              <div class="form-group">
                                 <div class="form-group">
                                    <div class="form-line">
                                       <input type="text" class="form-control is_first_col_required" name="support_actual_accom[0]" aria-invalid="false" required>
                                    </div>
                                 </div>
                              </div>
                           </td>
                           <td>
                              <center>
                                 <button class="btn btn-success btn-circle waves-effect waves-circle waves-float add" 
                                    data-toggle="tooltip" 
                                    data-placement="top" 
                                    data-key="support"
                                    title="" 
                                    type="button"
                                    data-original-title="Add row"> 
                                 <i class = "fa fa-plus"></i>
                                 </button>
                                 <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float remove" 
                                    data-toggle="tooltip" 
                                    data-placement="top"
                                    data-key="support"
                                    title=""
                                    type="button"
                                    data-original-title="Delete row"> 
                                 <i class = "fa fa-minus"></i>
                                 </button>
                              </center>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <table class = "table-style" style="width: 100%;">
                  <thead>
                        <tr>
                           <th style="width: 350px;">Discussed with:</th>   
                           <th>Date:</th> 
                           <th style="width: 350px;">Assessed by:</th>
                           <th>Date:</th>  
                           <th style="width: 350px;">Final Rating by:</th>
                           <th>Date:</th>  
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td>
                                 <div class="form-group">
                                    <div class="form-line">
                                       <select data-size="5" style="min-height:40px;" class="discussed_with_emp form-control" name="discussed_with_emp" id="discussed_with_emp" data-live-search="true" required>
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
                                       <input type="text" class="ipcr_date form-control date_discussed " name="date_discussed" value = "<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                 </div>
                           </td>
                           <td>
                                 <div class="form-group">
                                    <div class="form-line">
                                       <select data-size="5" class="assesed_by_supervisor form-control" name="assesed_by_supervisor" id="assesed_by_supervisor" data-live-search="true" required>
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
                                       <input type="text" class="ipcr_date date_assesed form-control is_first_col_required " name="date_assesed" value = "<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                 </div>
                           </td>
                           <td>
                                 <div class="form-group">
                                    <div class="form-line">
                                       <select data-size="5" class="final_rating_by_head_of_office form-control" name="final_rating_by_head_of_office" id="final_rating_by_head_of_office" data-live-search="true" required>
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
                                       <input type="text" class="ipcr_date dpcr_date date_final_rating form-control is_first_col_required " name="date_final_rating" value = "<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                 </div>
                           </td>
                        </tr>
                     </tbody>
               </table><br><br>
               <div class = "form-group">
                  <button type = "submit" class = "btn btn-success btn-fill btn-lg" style="width: 100%;">
                  SUBMIT
                  </button>
               </div><br>
         </div>
         </form>
      </div>
   </div>
</div>
</div>
<script type="text/javascript" src = "<?php echo base_url(); ?>assets/modules/js/performancemanagement/ipcr.js"></script>