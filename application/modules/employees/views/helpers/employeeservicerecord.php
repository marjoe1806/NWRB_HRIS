<div class="container-fluid" id="printPreview" style="pointer-events: none;">
<style type="text/css">
  @page {
    size: A4;
    margin: 45mm 10mm 25mm 10mm; /* Increased top margin for printing */
  }

  body {
    width: 210mm;
    height: 297mm;
  }

  /* Fixed-top header */
  .fixed-top {
    position: fixed;
    top: 2%; /* Adjust as needed */
    width: 100%;
    z-index: 1000; /* Ensure it's above other content */
  }

  @media print {
    .fixed-top {
      display: none; /* Hide the fixed header during printing */
    }
  }

  .content {
    width: 100%;
    min-height: initial;
    box-shadow: initial;
    background: initial;
    page-break-after: always;
    margin-top: 40mm; /* Adjust the top margin for printing */
  }

  .ServiceTitle {
    text-align: center;
  }

  .ServiceTitle h2 {
    margin-top: 0;
    text-align: center;
  }

  #titleSub {
    margin-top: -10px;
    text-align: center;
  }

  table thead {
    position: sticky;
  }

  #maintable tfoot td {
    border: none;
  }

  #maintable tr:nth-child(even) {
    background-color: #fff;
  }

  #maintable tr:hover {
    background-color: #fff;
  }

  #maintable td {
    width: auto;
    overflow: hidden;
    word-wrap: break-word;
    font-size: 12pt;
    font-family: Arial Narrow;
  }

  #maintable th {
    text-align: left;
    font-family: Arial Narrow;
  }

  .aligncenter {
    text-align: center;
    font-size: 12pt;
    font-family: Arial Narrow;
  }

  .name {
    white-space: nowrap;
    text-align: left;
    color: #000;
    font-size: 13pt;
    font-family: Arial;
    font-weight: bold;
  }

  .des {
    font-size: 12pt;
  }

  .lblname {
    font-size: 12pt;
    font-family: Arial;
    font-weight: normal;
    border-top: 1px dashed black;
  }

  .lbl {
    font-weight: normal;
    word-break: normal;
    text-align: left;
    font-size: 12pt;
    font-family: Arial;
  }

  .note {
    text-align: justify;
    font-weight: normal;
    font-size: 11pt;
  }

  .subnote {
    text-align: justify;
    font-weight: normal;
    font-size: 9.5pt;
  }

  .line {
    border-top: 2px solid black;
  }

  thead tr:nth-child(1) th {
    position: sticky;
    top: 0;
  }

  thead tr:nth-child(2) th {
    position: sticky;
    top: 43px;
  }

  .small {
    width: 5%;
  }

  .salary {
    white-space: nowrap;
  }

  .subname1 {
    white-space: nowrap;
    font-weight: normal;
    font-size: 9.5pt;
  }

  .sublbl {
    font-weight: normal;
    font-size: 9.5pt;
  }

  #date {
    color: black;
    font-size: 12pt;
    letter-spacing: 1px;
    margin-left: 0;
    text-align: center;
  }

  .line-date {
    left: 60px;
    bottom: 30px;
    border-top: 1px solid black;
    width: 100%;
  }

  #certified {
    color: black;
    white-space: nowrap;
    font-size: 12pt;
    letter-spacing: 1px;
    text-align: left;
    padding-left: 5px;
  }

  .data {
    text-align: center;
    font-size: 11pt;
  }

  #maintable {
    font-family: "Arial Narrow";
    border-collapse: collapse;
    width: 100%;
  }

  .header2nd th {
    border-bottom: 4px double;
  }

  .header1st th {
    border-top: 4px double;
  }

  #maintable {
    width: 960px;
    line-height: 1;
  }

  @media print {
    .content {
      page-break-after: always;
    }

    .ServiceTitle {
      text-align: center;
      position: fixed;
      top: 10px;
      left: 50%;
      transform: translateX(-50%);
    }

    .ServiceTitle h2 {
      margin-top: 0;
      font-size: 30px;
    }

    #titleSub {
      margin-top: 10px;
      font-size: 15px;
    }

    #maintable td {
      font-size: 12pt;
    }

    .data {
      font-size: 12pt;
    }
  }
</style>


			<?php
				$totalNum = 0; 
				$numExperience = sizeof($Data['Experience']) / 15;
				$wholeNumExperience = floor($numExperience);
				$decNumExperience = $wholeNumExperience - $numExperience;
				$totalNum = $wholeNumExperience;
				//var_dump($decNumExperience);

				if($decNumExperience < 0){
					$totalNum += 1;
				}

				//var_dump(sizeof($Data['Experience']));
			//var_dump($decNumExperience);
				if(sizeof($Data['Experience']) == 0){
					echo "<h2> NO AVAILABLE DATA </h2>";
				}
			?>
			<?php
				$count1 = 0;
				$count2 = 15;
				for($x = 0; $x < $totalNum; $x++){

			?>
      <div>
        <div class="content" style="margin-top: 6em">
					<div class="ServiceTitle">
						<h2>Service Record</h2>
						<p style="margin-top:-10px;" id="titleSub">(To be accomplished by Employer)</p>
					</div>
				<?php
					if($totalNum > 1){
				?>
        <div style="position:absolute;right:0;margin-right:100px;margin-top:-30px;">
					<p>Page <?php echo $x + 1; ?> of <?php echo $totalNum; ?></p>
				</div>
				<?php
					}
				?>
          <table id="maintable">
            <thead>
              <tr>
                <th>NAME:</th>
                <th class="name " style="border-bottom:2px dashed black;text-align:center;"> <?php echo $Data["employee"]["last_name"]; ?> </th>
								<th class="name" style="border-bottom:2px dashed black;text-align:center;" ><?php echo $Data["employee"]["first_name"]; if($Data["employee"]["extension"] != ""){ echo ",  ".$Data["employee"]["extension"]; } ?></th>
								<th class="des" style="border-bottom:2px dashed black;text-align:center;"></th>
                <th class="name" style="border-bottom:2px dashed black;text-align:center;" ><?php echo $Data["employee"]["middle_name"]; ?></th>
								<th style="width:20px;"></th>
                <th class="des" colspan="5" class="lbl">(If married woman, give also full maiden name)</th>
              </tr>
							<tr>
								<th></th>
								<th class="" style="text-align:center;">(Surname)</th>
								<th class="" style="text-align:center;">(GivenName)</th>
								<th style="width:20px;"></th>
								<th class="" style="text-align:center;">(MiddleName)</th>
								<th class=""></th>
								<?php
										if($Data['employee']['gender'] == "Female" && $Data['employee']['civil_status'] == "Married"){
								?>
							 <th colspan="4" style="border-bottom:2px dashed black;text-align:center;">
                  <?php 
                  // Convert specific values to uppercase
                    if(strtoupper($Data["employee"]["first_name"] == "FLERILYNN") || strtoupper($Data["employee"]["middle_name"]) == "MACALINGA"){
                      echo 
                      "FLERILYNN" . " " .                                 
                      "MACALINGA" . " " .  
                      "ESTORNINOS";
                    }
                    else
                    {
                      echo 
                      $Data["employee"]["first_name"] . " " .                                 
                      $Data["employee"]["maiden_last_name"] . " " .  
                      $Data["employee"]["middle_name"]  ;
                    }
                  ?>
              </th>
							<?php
										}
							?>
							</tr>
							<tr>
                <th>BIRTH:</th>
                <th class="name" colspan="2" style="border-bottom:2px dashed black;text-align:center;"> <?php echo $Data["employee"]["birthday"]; ?> </th>
                <th class="name" colspan="2" style="border-bottom:2px dashed black;text-align:center;"> <?php echo $Data["employee"]["birth_place"]; ?></th>
								<th style="width:20px;"></th>
                <th colspan="6" class="">(Data herein should be checked from birth or baptismal certificate or some other reliable documents)</th>
						</tr>
							<tr>
								<th></th>
								<th class="des" colspan="2" style="text-align:center;">(Date)</th>
								<th class="des" colspan="2" style="text-align:center;">(Place)</th>
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
						<tr class="header1st">
						  <th class="aligncenter name " colspan="2" style="width:50px;">SERVICE <span style="float:right;">:</span><br>
                  <!-- <span class="subname1">From : To</span> -->
                </th>
                <th class="aligncenter name record" colspan="3" >RECORD OF APPOINTMENT <span style="float:right;">:</span><br>
                 
                  <!-- <span class="subname1"> Designation : Status(1) : Salary(2) </span> -->
                </th>
                <th class="aligncenter name office" colspan="2" >OFFICE ENTITY/DIV. <span style="float:right;">:</span><br>
                 
                  <!-- <span class="subname1">Station/POA : Branch(3) </span> -->
                </th>
                <th class="aligncenter small name" ><span style="float:right;">:</span><br></th>
                <th class="aligncenter name separation" colspan="2" >SEPARATION<br>(4)
                
                  <!-- <span class="subname1">Date : Cause </span> -->
                </th>
                <!-- <td class="aligncenter">Status</td> -->
                <!-- <td class="aligncenter">Remarks</td> -->
            </tr style="margin-top:-10px;">
              <th class="aligncenter " colspan="2">(Inclusive Dates)<span style="float:right;">:</span>
              <th class="record"><div style="margin:auto;border-bottom:1px dashed black;width:80%;margin-top:-4px;"></div></th>
              <th class="record"><div style="margin:auto;border-bottom:1px dashed black;width:80%;margin-top:-4px;"></div></th>
              <th class="record"><div style="margin:auto;border-bottom:1px dashed black;width:80%;"></div><span style="float:right;margin-top:-12px;">:</span></th>
              <th class="office" colspan="2" ><div style="margin:auto;border-bottom:1px dashed black;width:90%;"></div><span style="float:right;margin-top:-12px;">:</span></th>
              <th class="aligncenter">LV/ABS<span style="float:right;">:</span></th>
              <th class="separation" colspan="2" ><div style="margin:auto;border-bottom:1px dashed black;width:90%;"></div></th>
            </tr>
            <tr>
              <th><div style="margin:auto;border-bottom:1px dashed black;width:80%;"></div><span style="float:right;margin-top:-12px;">:</span></th>
              <th><div style="margin:auto;border-bottom:1px dashed black;width:80%;"></div><span style="float:right;margin-top:-12px;">:</span></th>
              <th><span style="float:right;margin-top:-12px;">:</span></th>
              <th><span style="float:right;margin-top:-12px;">:</span></th>
              <th><span style="float:right;margin-top:-12px;">:</span></th>
              <th><span style="float:right;margin-top:-12px;">:</span></th>
              <th><span style="float:right;margin-top:-12px;">:</span></th>
              <th class="aligncenter">W/O PAY<span style="float:right;margin-top:-12px;">:</span></th>
              <th><span style="float:right;margin-top:-12px;">:</span></th>
              <th></th>
            </tr>

            <tr class="header2nd">
								<th style="text-align:center;width:100px;">FROM<span style="float:right;">:</span><br><span style="float:right;">:</span></th>
								<th style="text-align:center;width:80px;">TO<span style="float:right;">:</span><br><span style="float:right;">:</span></th>
								<th style="text-align:center;width:140px;">Designation<span style="float:right;">:</span><br><span style="float:right;">:</span></th>
								<th style="text-align:center;width:50px">Status<span style="float:right;">:</span><br>(1)<span style="float:right;">:</span></th>
								<th style="text-align:center;width:50px;">Salary<span style="float:right;">:</span><br>(2)<span style="float:right;">:</span></th>
								<th style="text-align:center;width:50px;">Station<span style="float:right;">:</span><br>/POA<span style="float:right;">:</span></th>
								<th style="text-align:center;width:30px;">Branch<span style="float:right;">:</span><br>(3)<span style="float:right;">:</span></th>
								<th><span style="float:right;">:</span><br><span style="float:right;">:</span></th>
								<th style="text-align:center;width:50px;">Date<span style="float:right;">:</span></br><span style="float:right;">:</span></th>
								<th style="text-align:center;width:120px;">Cause</th>
							</tr>
									<?php
									// $totalData =  
									for($t = $count1; $t < sizeof($Data['Experience']); $t++){
			

										?>
										<tr>
										<td class="data dates" style="width:100px;">
                      <?php echo $Data['Experience'][$t]["work_from"];?><span style="float:right;">:</span>
									</td>
                  <td class="data dates" style="width:80px;">
                    <?php echo $Data['Experience'][$t]["work_to"];?><span style="float:right;">:</span>	
                  </td>
										<td class="data designation" style="width:140px;"><?php echo $Data['Experience'][$t]["position"]; ?><span style="float:right;">:</span></td>
										<td class="data status" style="width:50px;"><?php echo $Data['Experience'][$t]["status_appointment"];?><span style="float:right;">:</span></td>
                    <?php if(strtolower($Data['Experience'][$t]["monthly_salary"]) == 'n/a'):?>
                        <td class="data salary" style="width:50px;"><?php $salary = $Data['Experience'][$t]["monthly_salary"]; echo $salary;?><span style="float:right;">:</span></td>
										<?php elseif(is_numeric($Data['Experience'][$t]["monthly_salary"])):?>
                      <td class="data salary" style="width:50px;"><?php $salary = $Data['Experience'][$t]["monthly_salary"]; echo number_format($salary,2,'.', ',');?><span style="float:right;">:</span></td>
                    <?php else: ?>
                      <td class="data salary" style="width:50px;"><?php $salary = explode("/",$Data['Experience'][$t]["monthly_salary"]); $salary1 = preg_replace('/[^0-9.]/','',$salary[0]); echo number_format($salary1, 2,'.', ','); if(!empty($salary[1])){echo "/".$salary[1];}?><span style="float:right;">:</span></td>
                    <?php endif;?>
										<td class="data station" style="width:50px;"><?php echo $Data['Experience'][$t]["company"]; ?><span style="float:right;">:</span></td>
										<td class="data branch" style="width:30px;"><?php echo $Data['Experience'][$t]['branch'];//$value[""]; ?><span style="float:right;">:</span></td>
										<td class="data"><?php echo $Data['Experience'][$t]['lv_abs_wo_pay']; ?><span style="float:right;">:</span></td>
										<td class="data"style="width:50px;"><?php echo $Data['Experience'][$t]['seperation_date']; ?><span style="float:right;">:</span></td>
                    <td class="data" style="text-align:left;width:120px;font-size:10pt; margin:0,10,0,10; !important;">
                    <?php 
                     $separationCause = $Data['Experience'][$t]['seperation_cause'];
                     $trimmedString = trim($separationCause);
                     $length = mb_strlen($trimmedString);
                                                       
                     if ($length >= 13) {
                         $first15Characters = mb_substr($trimmedString, 0, 13);
                         $extraCharacters = mb_substr($trimmedString, 13);
                         
                         echo  $first15Characters . "<br>";
                         echo  $extraCharacters;
                     } else {
                         echo $separationCause ;
                     }                     
                     ?>
                   </td>
								</tr>
								<?php
										if($t == $count2){
											$count1 += 1;
											break;
										}
								}

							
								?>
								<?php
									if($x < $totalNum - 1){
								?>
									<tr>
										<td colspan="10" style="text-align:center;">Continued Next page</td>
								</tr>
									<?php
									}else{
									?>
											<tr>
											<td class="des" colspan="10" style="text-align:center; font-weight:800; color: black;">-Nothing Follows-</td>
									</tr>
									<?php
									}
									?>
            <tfoot style="border-top:double; border-width: thick; font-weight:800">
              <tr>
                <td colspan="10" class="subnote">Issued in compliance with Executive Order No. 54 dated August 10, 1954. and in accordance with Circular No. 58, dated August 10, 1954 of the System.</td>
              </tr>
              <tr>
                <td colspan="10">&nbsp;</td>
              </tr>
              <tr>
                <!-- <td>&nbsp;</td> -->
                <td colspan="2">
                <div id="date" style="text-align: center;">
                  <p style="margin-bottom: 5px;">
                    <?php echo date('F d, Y'); ?>
                  </p>
                  <div class="line-date" style="height: 1px; background-color: black; margin-bottom: 5px;"></div>
                  <p>
                    <b>Date</b>
                  </p>
                </div>
              </td>
                <td colspan="3">&nbsp;</td>
                <td colspan="3">
                  <div id="certified">
                    <p><b>CERTIFIED CORRECT:</b> </p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p style="line-height: 1;font-family:Tahoma; font-size:12pt;">Authorized Signatory
                    </p>
                    <p> <?php //echo $Data['signatories'][0]["division_designation"] != "" || $Data['signatories'][0]["division_designation"] != null ? $Data['signatories'][0]["division_designation"] : $Data['signatories'][0]["department"]; //position ?> </p>
                  </div>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="10">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="10">&nbsp;</td>
              </tr>
            </tfoot>
          </table>
          <!--  <div class = "footer"><table width="100%" border="0" cellpadding="0" cellspacing="0" class=""><tr><td width="50%" style="text-align: center;color: #002448;font-size: 8.5pt;" class="hdfont" >
                                8th Flr. NIA Building EDSA, Diliman, Quezon City, 1105 Philippines<br>
                                Tel. Nos. (632)8 920-2724 / (632)8 920 2641 ●<br>
                                Website: http://www.nwrb.gov.ph/ ●<br>
                                Email Address: nwrbphil@gmail.com
                        </td><td width="50%"><img src="
                                                                    <?php echo base_url()."assets/custom/images/socotec.png"; ?>" style="width: 80px;height: 120px;"></td></tr></table></div> -->
        </div>
      </div>
			<?php 
				$count1 += 15;
				$count2 += 16;
				}
			?>
    </div>
