<?php 
$required = ""; 
$inputRequired = "inputRequired"; 

?>

    <div class = "container-fluid" >
    <style type="text/css" media="all">
	        .break{
	            page-break-before: always;
	        }
            .form{
                display: flex;
                flex:1;
                min-height: 0px;
            }
            table tr td{
                padding-left: 3px;
                color: #000;
                font-size: 12px;
            }
            #Line{
                border-bottom: 1px black;
            }
            #SR{
                border-bottom: 1px dashed black;
                color: black;
            }
            #serviceOfrecord td{
                border-right: .5px dashed black;
            }
            #name{
                word-spacing: 30px;
                /* border-bottom: 1px dashed black; */
                /* display: inline-block; */
            }
            #birth{
                word-spacing: 30px;
                /* border-bottom: 1px dashed black; */
            }
            .nameDiv{
                display: flex;
            }
            #sub{
                position: absolute;
                left: 50%;
            }
            #lbl{
                word-spacing: 50px;
                padding-left: 70px;
            }
            #lblBirth{
                word-spacing: 200px;
                padding-left: 70px;
            }
            .line-1 {
                position: absolute;
                left: 85px;
                border-top: 1px dotted black;
                width: 35%;
            }
            .line-2 {
                border-top: 1px solid black;
            }
            .line-date{
                position: absolute;
                left: 60px;
                bottom: 30px;
                border-top: 1px dotted black;
                width: 100%;
            }
            .ROF{
                word-spacing: 30px;
            }
            #SubHead{
                border-bottom: 2px solid black;
            }
            #ROA, #office, #sep, #IncDate{
                border-bottom: 1px dashed black;
                display: inline-block;
            }
            #certified{
                color: black;
                position: absolute;
                padding-left: 56%;
                font-size: 13px;
                letter-spacing: 1px;
                text-align: center;
            }
            #date{
                color: black;
                font-size: 13px;
                letter-spacing: 1px;
                position: absolute;
                text-align: center;
                padding-left: 100px;
                margin-top: 50px;         
               }
            #issueNote{
                color: black;
                text-indent: 50px;
            }
            img{
                width: 90px;
                height: 90px;
            }
            .hdfont{
                font-family: TimesNewRoman, Times New Roman, Times, Baskerville, Georgia, serif;
            }
            .aligncenter{
                text-align: center;
            }
            .fromTo{
                padding-left: 33px;
                word-spacing: 10px;
            }
            .tdheader{
                text-align: center;
                font-weight: bold;
            }
            /* .header {
                position: fixed;
                top: 0;
                width: 100%;
            } */
            .content{
                min-height: 700px;
            }
            .footer {
                position: relative;
                bottom: 0;
                width: 100%;
                padding-top: 150px;
                padding-left: 200px;
            }

           ::placeholder{
                font-size: 12px;
                text-align: center;
            }

            textarea{
                font-size: 13px;
                text-align: left;
            }

            .form-group{
    		margin-bottom: 0px;
    	    }
        @media (min-width: 992px){
            .modal-lg {
                width: 1200px;
            }
        }
        @media (min-width: 768px){
            .modal-dialog {
                width: 1200px;
                margin: 30px auto;
            }
        }
        table tr td,table tr th{
            text-align: center;
        }
        .headcol {
            position: absolute;
            left: 1;
            background: #fff;
            width: 130px;
            border: hidden !important;
            height: auto;
        }
        input[type="number"]::-webkit-outer-spin-button, input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        table#tblqs tr td{
            text-align: left;
        }

		.name {
            white-space: nowrap;
            text-align: left;
            color: #000;
            font-size: 9pt;
            font-family: Arial;
          }
		  .lblname {
            font-size: 10pt;
            font-family: Arial;
            font-weight: normal;
            border-top: 1px dashed black;
          }

          .lbl {
            font-weight: normal;
            word-break: normal;
            text-align: left;
            font-size: 10pt;
            font-family: Arial;
          }
		  .note {
            text-align: justify;
            font-weight: normal;
            font-size: 9.5pt;
          }
        
    </style>
		<div class="content" style="margin-top: 3em">
		<div style="text-align:center;margin-top:-3em;font-family:Arial;">
						<h4>Service Record</h4>
						<p style="margin-top:-10px;" id="titleSub">
						(To be accomplished by Employer)
					</p>
					</div>
                    <input type="hidden" id="salary" name="salary" value="<?php echo $Data['employee']['salary'];?> ">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
			<thead>
            <tr>
                <th>NAME:</th>
                <th class="name" style="border-bottom:2px dashed black;text-align:center;"> <?php echo $Data["employee"]["last_name"]; ?> </th>
				<th class="name" style="border-bottom:2px dashed black;text-align:right;" ><?php echo $Data["employee"]["first_name"]; if($Data["employee"]["extension"] != ""){ echo ",  ".$Data["employee"]["extension"]; } ?></th>
				<th style="border-bottom:2px dashed black;text-align:center;"></th>
                <th class="name" style="border-bottom:2px dashed black;text-align:center;" ><?php echo $Data["employee"]["middle_name"]; ?></th>
				<th style="width:20px;"></th>
                <th colspan="5" class="lbl">(If married woman, give also full maiden name)</th>
              </tr>
				<tr>
					<th></th>
					<th style="text-align:center;">(Surname)</th>
					<th style="text-align:right;">(GivenName)</th>
					<th style="width:20px;"></th>
					<th style="text-align:center;">(MiddleName)</th>
					<th></th>
					<?php
							if($Data['employee']['gender'] == "Female" && $Data['employee']['civil_status'] == "Married"){
					?>
					<th class="name" colspan="1" style="border-bottom:2px dashed black;text-align:left;width:250px;"><?php echo $Data["employee"]["first_name"]; ?> <?php echo $Data["employee"]["maiden_last_name"]; ?> <?php echo $Data["employee"]["middle_name"]; ?> </th>
				<?php
							}
				?>
				<th></th>
				</tr>
				<tr>
                <th>BIRTH:</th>
                <th class="name" colspan="2" style="border-bottom:2px dashed black;text-align:center;"> <?php echo $Data["employee"]["birthday"]; ?> </th>
                <th class="name" colspan="2" style="border-bottom:2px dashed black;text-align:center;"> <?php echo $Data["employee"]["birth_place"]; ?></th>
				<th style="width:20px;"></th>
                <th colspan="6" class="lbl">(Data herein should be checked from birth or baptismal certificate or some other reliable documents)</th>
						</tr>
							<tr>
								<th></th>
								<th colspan="2" style="text-align:center;">(Date)</th>
								<th colspan="2" style="text-align:center;">(Place)</th>
								<th></th>
							</tr>
              <tr>
                <th colspan="10" class="note">
                  This is to certify that the employee named herein above actually rendered services in this Office as shown by the service record below, each line of which is supported by appointment and other papers actually issued by this Office and approved by the authorities concerned. 
                </th>
							</tr>
							<tr>
								<th></th>
							</tr>
            </thead>
                </table>
                
                <!-- Form -->
            <form id="<?= $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>"  method="POST">
                <div class="form-elements-container">
                <div class="table-responsive shadow">
				
                <table id = "serviceOfrecord" classs="table table-bordered" style="width:100%;">
                <thead class="bordered">
                <tr>
                        <th style="width: 70px;">&nbsp;</th>
                        <th style="width: 120px;">&nbsp;</th>
                        <th style="width: 160px;">&nbsp;</th>
                        <th style="width: 160px;">&nbsp;</th>
                        <th style="width: 160px;">&nbsp;</th>
                        <th style="width: 160px;">&nbsp;</th>
                        <th style="width: 160px;">&nbsp;</th>
                    </tr>
                    <tr>
                        <td class="aligncenter" colspan="2">SERVICE <p style = "border-bottom: 1px dashed black;text-align:center;">(Inclusive Dates)</p></td>
                        <td class="aligncenter" colspan="3"><p id="ROA">RECORD OF APPOINTMENT</p></td>
                        <td class="aligncenter" colspan="2"><p id="office">OFFICE ENTITY/DIV.</p></td>
                        <td class="aligncenter">LV/ABS W/O PAY:</td>
                        <td class="aligncenter" colspan="2"><p id="sep">SEPARATION(4)</p></td>
                        <!-- <td class="aligncenter">Status</td> -->
                        <!-- <td class="aligncenter">Remarks</td> -->
						<td>Action</td>
                    </tr>

                    <tr id = "SubHead">
                        <td class="aligncenter" style="width: 100px;" >From</td>
                        <td class="aligncenter">To</td>
                        <td class="aligncenter ROF" colspan="3">Designation : Status(1) : Salary(2)</td>
                        <td class="aligncenter">Station/Place of Assignment</td>
                        <td class="aligncenter">Branch(3) </td>
                        <td class="aligncenter"></td>
                        <td class="aligncenter" colspan="2">Date : Cause </td>
						<td><button type="button" id="add_fieldss" data-num="0" style="float: right; margin: 10px 5px;" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Add">
							<i class="material-icons">add</i>
						</button> </td>
                        <!-- <td><button type="button" id="btnAddCH" class="btn btn-primary btn-sm" style="float: right;"><i class="material-icons">add</i></button></td> -->
                        <!-- <td class="aligncenter"></td>
                        <td class="aligncenter"></td> -->
                    </tr>
                </thead>
                <input type="hidden" name="employee_id[]" id="employee_id" value="<?php echo $Data["employee"]["employee_id"];?>">
           	        <tbody id="append_fields">
                        <?php if(sizeof($Data['Experience']) == 0){ ?>
                           
                         <?php
                        }
                        ?>
                            <!--  <tr>
                        <input type="hidden" name="employee_id[]" id="employee_id" value="<?php echo $Data["employee"]["employee_id"];?>">
                        <td style="min-width: 90px" class="aligncenter">
                            <div class="form-group">
                                <div class="form-line">
                                <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired "   name="work_from[]" id="work_from" <?php if(sizeof($Data['Experience']) == 0){ echo "required";} ?> placeholder="01-01-2022"></textarea>
                            </div>
                        </div>
                        </td>
                        <td style="min-width: 90px" class="aligncenter">
                            <div class="form-group ">
                                <div class="form-line">
                                    <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired "  name="work_to[]" id="work_to"  <?php if(sizeof($Data['Experience']) == 0){ echo "required";} ?> placeholder="01-01-2022 / PRESENT"></textarea>
                            </div>
                        </div>
                        </td>
                        <td style="min-width: 160px" class="aligncenter">
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired "   name="position[]" id="position"  <?php if(sizeof($Data['Experience']) == 0){ echo "required";} ?>></textarea>
                            </div>
                        </div>
                        </td>
                        <td style="min-width: 70px" class="aligncenter">
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired "  name="status_appointment[]" id="status_appoinment"  <?php if(sizeof($Data['Experience']) == 0){ echo "required";} ?>></textarea>
                                </div>
                            </div>                
                        </td>
                        <td style="min-width: 120px" class="aligncenter">
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired " name="monthly_salary[]" id="monthly_salary"  placeholder="10,0000/a" <?php if(sizeof($Data['Experience']) == 0){ echo "required";} ?>></textarea>
                                </div>
                            </div>      
                        </td>
                        <td class="aligncenter" style="min-width: 100px">
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired" name="company[]" id="company"  <?php if(sizeof($Data['Experience']) == 0){ echo "required";} ?> ></textarea>
                            </div>
                        </div>
                        </td>
                        <td style="min-width: 100px" class="aligncenter">
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;"  class="form-control no-resize auto-growth inputRequired"  name="branch[]" id="branch" <?php if(sizeof($Data['Experience']) == 0){ echo "required";} ?> ></textarea>
                                </div>
                            </div>      
                        </td>
                        <td style="min-width: 100px" class="aligncenter">
                            <div class="form-group">
                                <div class="form-line">
                                <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;"  class="form-control no-resize auto-growth inputRequired"  name="lv_abs_wo_pay[]" id="lv_abs_wo_pay" <?php if(sizeof($Data['Experience']) == 0){ echo "required";} ?>></textarea>
                            </div>
                        </div>      
                        </td>
                        <td style="min-width: 100px" class="aligncenter">
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired"   name="seperation_date[]" id="seperation_date" placeholder="01-01-2022"></textarea>
                            </div>
                        </div>      
                        </td>
                        <td style="min-width: 120px" class="aligncenter">
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea rows="3"  style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired" name="seperation_cause[]" id="seperation_cause" <?php if(sizeof($Data['Experience']) == 0){ echo "required";} ?>></textarea>
                            </div>
                        </div>      
                        </td>
                        <td></td>
                        </tr> 
                         -->
                    <?php if(sizeof($Data['Experience']) <= 0){?>
			 
				<?php }else{ ?>
                    <?php
                        $count = 1;
                    ?>
                    <?php foreach($Data["Experience"] as $key => $value): 
                       
                    ?>
                    
                    <tr id="fields_line<?php echo $count;?>">
                    <input type="hidden" name="id[]" id="id" value="<?php echo $value['id'];?>">
                    <td style="min-width: 90px" class="aligncenter">
                        <div class="form-group">
                            <div class="form-line">
                                <?php if(strtolower($value["work_from"]) == 'n/a' || strtolower($value["work_from"]) == 'retired'  || strtolower($value["work_from"]) == 'present' ):?>
                                    <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize  inputRequired "   name="work_from[]" id="work_from" ><?php echo $value["work_from"];?></textarea>
                                <?php else:?>
                                    <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize  inputRequired "   name="work_from[]" id="work_from" ><?php echo date('m-d-Y', strtotime(str_replace('-', '/',$value["work_from"])));?></textarea>
                                <?php endif;?>
                        </div>
                    </div>
                    </td>
                    <td style="min-width: 90px" class="aligncenter">
                        <div class="form-group ">
                            <div class="form-line">
                            <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize inputRequired "  name="work_to[]" id="work_to" ><?php echo $value["work_to"];?></textarea>
                            
                        </div>
                    </div>
                    </td>
                    <td style="min-width: 160px" class="aligncenter">
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize inputRequired "   name="position[]" id="position"  ><?php echo $value['position'];?></textarea>
                        </div>
                    </div>
                    </td>
                    <td style="min-width: 70px" class="aligncenter">
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize  inputRequired "  name="status_appointment[]" id="status_appoinment" ><?php echo $value["status_appointment"]; ?></textarea>
                            </div>
                        </div>                
                    </td>
                    <td style="min-width: 120px" class="aligncenter">
                        <div class="form-group">
                            <div class="form-line">
                                <?php if(strtolower($value["monthly_salary"]) == 'n/a'):?>
                                    <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired " name="monthly_salary[]" id="monthly_salary" ><?php $salary = $value["monthly_salary"];  echo $value["monthly_salary"]; ?></textarea>
                                <?php elseif(is_numeric($value["monthly_salary"])):?>
                                    <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired " name="monthly_salary[]" id="monthly_salary" ><?php $salary = $value["monthly_salary"];  echo number_format($salary,2,'.', ','); ?></textarea>
                                <?php else:?>
                                    <!--  -->
                                    <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired " name="monthly_salary[]" id="monthly_salary" ><?php $salary = explode("/",$value["monthly_salary"]); $salary1 = preg_replace('/[^0-9.]/','',$salary[0]); echo number_format($salary1, 2,'.', ','); if(!empty($salary[1])){echo "/".$salary[1];}?></textarea>
                                <?php endif;?>
                            </div>
                        </div>      
                    </td>
                    <td class="aligncenter" style="min-width: 100px">
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize  inputRequired" name="company[]" id="company" ><?php echo $value["company"]; ?></textarea>
                        </div>
                    </div>
                    </td>
                    <td style="min-width: 100px" class="aligncenter">
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;"  class="form-control no-resize  inputRequired"  name="branch[]" id="branch" ><?php echo $value["branch"]; ?></textarea>
                            </div>
                        </div>      
                    </td>
                    <td style="min-width: 100px" class="aligncenter">
                        <div class="form-group">
                            <div class="form-line">
                            <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;"  class="form-control no-resize auto-growth inputRequired text-capitalized"  name="lv_abs_wo_pay[]" id="lv_abs_wo_pay" ><?php echo $value["lv_abs_wo_pay"]; ?></textarea>
                        </div>
                    </div>      
                    </td>
                    <td style="min-width: 100px" class="aligncenter">
                        <div class="form-group">
                            <div class="form-line">
                                <?php if(!empty($value['seperation_date'])):?>
                                <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize  inputRequired"   name="seperation_date[]" id="seperation_date" ><?php echo $value["seperation_date"];?></textarea>
                                <?php else:?>
                                <textarea rows="3" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize  inputRequired"   name="seperation_date[]" id="seperation_date"  ></textarea>
                                <?php endif;?>
                         </div>
                    </div>      
                    </td>
                    <td style="min-width: 120px" class="aligncenter">
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="3"  style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize inputRequired" name="seperation_cause[]" id="seperation_cause"><?php echo $value["seperation_cause"];?></textarea>
                        </div>
                    </div>      
                    </td>
                    <td style="min-width: 50px">
                        <div class="form-button">
                            <div class="form-line">
                                <button type="button" id="deleteRecord" class="removeFields btn-danger" data-id="<?php echo $value['id'];?>"  data-emp="<?php echo $Data["employee"]["employee_id"];?>" data-url="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deleteServiceRecord'; ?>" data-url2="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addServiceRecordForm'; ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Remove">
                                <i class="material-icons">remove</i>
                                </button>
                                <button type="button" id="add_fields" data-num="<?php echo $count;?>" style="float: right; margin: 10px 5px;" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Add">
                                    <i class="material-icons">add</i>
                                </button>
                            </div>
                        </div>
                    </td>
                    </tr><?php
                     $count++;
                    ?>
                <?php endforeach;?> 
                <?php 
			}
				?>      
            </tbody>
        </table>

</div>
<div class="row">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                                <button type="submit" id="update" name="btnSubmit" style="float: right; margin: 10px 5px;" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Save">
                                    <i class="material-icons">save</i> Submit
                                </button> 
                           
                                <button style="float: right; margin: 10px 5px;" id="cancel" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Cancel">
                                    <i class="material-icons">cancel</i> Cancel
                                </button> 
                        </div>
                    </div>
                </div>
            </div> 
	</form> 