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
        input, textarea{
            text-transform: uppercase;
        }
        table#tblqs tr td{
            text-align: left;
        }


        
    </style>
<?php
	// var_dump($Data['Experience']);
?>
  <form id="<?php echo isset($key) && $key === 'updateServiceRecord' ?>"  enctype="multipart/form-data" accept-charset="utf-8">
  <input type="hidden" class="id" name="id" id="id" value="<?php echo isset($key) && $key === 'employeeeditservicerecord' ? Helper::get('employee_id'):''; ?>">
    <div>
        <div class="header">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
                <tr>
                    <!-- <td width="50%" style="text-align: right"><img src="<?php echo base_url()."assets/custom/images/coap.png"; ?>" ></td> -->
                    <td width="50%" style="text-align: center"><img src="<?php echo base_url()."assets/custom/images/nwrb.png"; ?>" ></td>
                </tr>
                <tr>
                    <td colspan="2" class="aligncenter hdfont" style="font-size: 17px;"><span style="font-size:21px;">R</span>EPUBLIC OF THE <span style="font-size:21px;">P</span>HILIPPINES</td>
                </tr>
                <tr>
                    <td colspan="2"><hr style="margin: 0px;"></td>
                </tr>
                <tr>
                    <td colspan="2" class="aligncenter hdfont" style="font-size: 24px;"><span style="font-size:28px;">N</span>ATIONAL <span style="font-size:28px;">W</span>ATER <span style="font-size:28px;">R</span>ESOURCES <span style="font-size:28px;">B</span>OARD</td>
                </tr>
            </table>
        </div>
        <div class="content" style="margin-top: 3em">
            <center> <h4 id="SR">Service Record</h4>  </center>
            <center> <p>(To be accomplished by Employer)</p>  </center>
         
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
                <tr>
                    <td colspan="8"> 
                        <div class = "nameDiv">
                            <h5 id='name'>
                                NAME: <?php echo $Data["employee"]["last_name"] . ((isset($Data["employee"]["extension"]) && $Data["employee"]["extension"] != "")?" ".$Data["employee"]["extension"]:"") . ", " .$Data["employee"]["first_name"]." ".$Data["employee"]["middle_name"]; ?>
                                <div class="line-1"></div>
                            </h5> 
                            <p id="sub">(If married woman, give also full maiden name)</p>       
                        </div>                      
                        <p id="lbl">(Surname) (GivenName) (MiddleName)</p>
                    </td>
                    
                </tr>

                <tr>
                    <td colspan="8"> 
                        <div class = "nameDiv">
                            <h5 id='birth'>
                                BIRTH:  <?php echo $Data["employee"]["birthday"]; ?>
                                <?php echo $Data["employee"]["birth_place"]; ?>
                                <div class="line-1"></div>

                            </h5> 
                            <p id="sub">(Data herein should be checked from birth or baptismal certificate or some other reliable documents)</p> 
                        </div>                      
                        <p id="lblBirth">(Date) (Place)</p>
                    </td>
                </tr>
              
                <tr>
                    <td colspan="7" style="text-align: justify;">
                        This is to certify that the employee named hereinabove actually rendered services in this Office as shown by the service record below, each line of which is supported by appointment and other papers actually issued by this Office and approved by the authorities concerned.
                        <div class="line-2"></div> 
                        <div class="line-2"></div> 
                    </td>
                </tr>
                </table>

                
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
                        <td class="aligncenter" colspan="2">SERVICE <p style = "border-bottom: 1px dashed black;">(Inclusive Dates)</p></td>
                        <td class="aligncenter" colspan="3"><p id="ROA">RECORD OF APPOINTMENT</p></td>
                        <td class="aligncenter" colspan="2"><p id="office">OFFICE ENTITY/DIV.</p></td>
                        <td class="aligncenter">LV/ABS W/O PAY:</td>
                        <td class="aligncenter" colspan="2"><p id="sep">SEPARATION(4)</p></td>
                        <!-- <td class="aligncenter">Status</td> -->
                        <!-- <td class="aligncenter">Remarks</td> -->
                    </tr>

                    <tr id = "SubHead">
                        <td class="aligncenter" style="width: 100px;" >From</td>
                        <td class="aligncenter">To</td>
                        <td class="aligncenter ROF" colspan="3">Designation : Status(1) : Salary(2)</td>
                        <td class="aligncenter">Station/Place of Assignment</td>
                        <td class="aligncenter">Branch(3) </td>
                        <td class="aligncenter"></td>
                        <td class="aligncenter" colspan="2">Date : Cause </td>
                        <!-- <td><button type="button" id="btnAddCH" class="btn btn-primary btn-sm" style="float: right;"><i class="material-icons">add</i></button></td> -->
                        <!-- <td class="aligncenter"></td>
                        <td class="aligncenter"></td> -->
                    </tr>
                </thead>
            <tbody>
            <?php if(sizeof($Data['Experience']) <= 0){?>
				<tr>
					<td colspan="8">No Available Data</td>
				</tr>
				<?php }else{ ?>
            <?php foreach($Data["Experience"] as $key => $value):?>
                    <tr>
                    <td style="min-width: 90px" class="aligncenter">
                        <span class="edit"><?php date('m/d/y', strtotime($value["work_from"])) ?></span>
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="4" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired table_data"   name="work_from" id="work_from" value="<?php echo date('m/d/y', strtotime($value["work_from"]));?>"><?php echo date('m/d/y', strtotime($value["work_from"]));?></textarea>
                        </div>
                    </div>
                    </td>
                    <td style="min-width: 90px" class="aligncenter">
                        <span class="edit"><?php $value["work_to"] != "PRESENT" ? date('m/d/y', strtotime($value["work_to"])) : $value["work_to"];?></span>
                        <div class="form-group ">
                            <div class="form-line">
                                <textarea rows="4" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired table_data"  name="work_to" id="work_to" value="<?php echo $value["work_to"] != "PRESENT" ? date('m/d/y', strtotime($value["work_to"])) : $value["work_to"];?>"> <?php echo $value["work_to"] != "PRESENT" ? date('m/d/y', strtotime($value["work_to"])) : $value["work_to"];?></textarea>
                        </div>
                    </div>
                    </td>
                    <td style="min-width: 160px" class="aligncenter">
                        <span class='edit'> <?php $value["position"]; ?> </span>
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="4" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired table_data"   name="position" id="position" value="<?php echo $value['position'];?>"><?php echo $value['position'];?></textarea>
                        </div>
                    </div>
                    </td>
                    <td style="min-width: 130px" class="aligncenter">
                        <span class='edit'> <?php $value["status_appointment"]; ?> </span>
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="4" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired table_data"  name="status_appooinment" id="status_appoinment" value="<?php echo $value["status_appointment"]; ?>"><?php echo $value["status_appointment"]; ?> </textarea>
                            </div>
                        </div>                
                    </td>
                    <td style="min-width: 130px" class="aligncenter">
                        <span class='edit'> <?php $value["monthly_salary"] * 12 ?> </span>
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="4" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired table_data" name="monthly_salary" id="monthly_salary" value="<?php echo $value["monthly_salary"]; ?>"><?php echo $value["monthly_salary"]; ?> </textarea>
                            </div>
                        </div>      
                    </td>
                    <td class="aligncenter" style="min-width: 250px">
                        <span class='edit'> <?php $value["company"]; ?></span>
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="4" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired table_data" name="company" id="company" value=" <?php echo $value["company"]; ?>  " ><?php echo $value["company"]; ?></textarea>
                        </div>
                    </div>
                    </td>
                    <td style="min-width: 100px" class="aligncenter">
                        <span class='edit'>  </span>
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="4" style=" overflow: hidden; overflow-wrap: break-word;"  class="form-control no-resize auto-growth inputRequired table_data"  name="id" id="" value="<?php echo $value["id"]; ?>">
                             
                            </textarea>
                            </div>
                        </div>      
                    </td>
                    <td style="min-width: 100px" class="aligncenter">
                        <span class='edit'>  </span>
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="4" style=" overflow: hidden; overflow-wrap: break-word;"  class="form-control no-resize auto-growth inputRequired table_data" name="" id="" value="" >
                             
                            </textarea>
                        </div>
                    </div>      
                    </td>
                    <td style="min-width: 100px" class="aligncenter">
                        <span class='edit'> </span>
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="4" style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired"   class="txtedit form-control" > 
                             
                            </textarea>
                        </div>
                    </div>      
                    </td>
                    <td style="min-width: 100px" class="aligncenter">
                        <span class='edit'> </span>
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="4"  style=" overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired table_data" name="" id="" value="" > 
                           
                            </textarea>
                        </div>
                    </div>      
                    </td>
                    </tr>    
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
                            <a data-employee_id="" style="text-decoration: none;" 
                                href="" > 
                                <button type="submit" name="btnSubmit" style="float: right; margin: 10px 5px;" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Save" <?php if(sizeof($Data['Experience']) <= 0){ echo "disabled"; }?>>
                                    <i class="material-icons">save</i> Save
                                </button> 
						     </a> 
                            <a data-employee_id="<?=$value['id'];?>" style="text-decoration: none;" 
                                href="<?=base_url().$this->uri->segment(1);?>/ServiceRecords" > 
                                <button style="float: right; margin: 10px 5px;" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Cancel">
                                    <i class="material-icons">cancel</i> Cancel
                                </button> 
						     </a> 
                        </div>
                    </div>
                </div>
            </div> 
            </form>
    
 
            
            <div class="line-2"></div> 
            <div class="line-2"></div> 

            <p id="issueNote">Issued in compliance with Executive Order No. 54 dated August 10, 1954. and in accordance with Circular No. 58, dated August 10, 1954 of the System.</p>
            
            <div id="date">
                <p> <?php echo date('m/d/Y'); ?> </p>
                <div class="line-date"></div> 
                <p> <b>Date </b> </p>
            </div>

            <div id="certified">
                <p>CERTIFIED CORRECT: </p>
                <p>&nbsp;</p>
                <p><b><?php echo $Data['signatories'][0]["employee_name"]; //name ?></b></p>
                <p><?php echo $Data['signatories'][0]["position_designation"] != "" || $Data['signatories'][0]["position_designation"] != null ? $Data['signatories'][0]["position_designation"] : $Data['signatories'][0]["position_title"]; //position ?></p>
                <p><?php echo $Data['signatories'][0]["division_designation"] != "" || $Data['signatories'][0]["division_designation"] != null ? $Data['signatories'][0]["division_designation"] : $Data['signatories'][0]["department"]; //position ?></p>
            </div>
            <div class = "footer">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
                    <tr>
                        <td width="80%" style="text-align: center;color: #002448;font-size: 8.5pt" class="hdfont">
                                8th Flr. NIA Building EDSA, Diliman, Quezon City, 1105 Philippines<br>
                                Tel. Nos. (632)8 920-2724 / (632)8 920 2641 ●<br>
                                Website: http://www.nwrb.gov.ph/ ●<br>
                                Email Address: nwrbphil@gmail.com
                        </td>
                        <td width="20%">
                        <img src="<?php echo base_url()."assets/custom/images/socotec.png"; ?>" style="width: 80px;height: 120px;">
                        </td>
                    </tr>
                </table>    
            </div>
        </div>
    </div>
    </div>



    