
<div class = "container-fluid" id="printPreview" style="pointer-events: none;">
	<style type="text/css" media="all">
		@page {
			/* size: A4; */
			size: 13in 8.5in;
			margin: 5px;
		}
		.displaynone{
			border-top: none;
			border-left: none;
			border-right: none;
			padding: none;
		}
		.break{
			margin-top: 10px;
			page-break-before: always;
		}
		table.pv{
			font-family: "Arial Narrow", Arial, sans-serif;
			font-size: 7.8pt;
			border: 2px solid black;
			color: #000;
		}
		table.pv tr td.tdmargin, table.pv tbody tr td{
			/* padding-left: 1px;
			padding-right: 1px; */
			padding: 4.4px 1px 4.4px 1px;
			height: 27px;
		}
		label#lblbybirth{
			margin-left:120px;
		}
		#printPreview table:first-child tr:nth-child(n+6 of .tdmargin) td {
			height: 100px;
		}
	</style>
	<table class="break pv">
		<tr style="height: 0 !important;line-height:0;opacity: 0;border-bottom: hidden;font-size: 0pt;">
			<td style="padding: 0px !imporant;height: 0px !important;line-height: 0;border-left: 0px solid black;border-right: 0px solid black;" width="17px"></td>
			<td style="padding: 0px !imporant;height: 0px !important;line-height: 0;border-left: 0px solid black;border-right: 0px solid black;" width="20px"></td>
			<td style="padding: 0px !imporant;height: 0px !important;line-height: 0;border-left: 0px solid black;border-right: 0px solid black;" width="350px"></td>
			<td style="padding: 0px !imporant;height: 0px !important;line-height: 0;border-left: 0px solid black;border-right: 0px solid black;" width="131px"></td>
			<td style="padding: 0px !imporant;height: 0px !important;line-height: 0;border-left: 0px solid black;border-right: 0px solid black;" width="131px"></td>
			<td style="padding: 0px !imporant;height: 0px !important;line-height: 0;border-left: 0px solid black;border-right: 0px solid black;" width="115px"></td>
			<td style="padding: 0px !imporant;height: 0px !important;line-height: 0;border-left: 0px solid black;border-right: 0px solid black;" width="120px"></td>
			<td style="padding: 0px !imporant;height: 0px !important;line-height: 0;border-left: 0px solid black;border-right: 0px solid black;" width="120px"></td>
			<td style="padding: 0px !imporant;height: 0px !important;line-height: 0;border-left: 0px solid black;border-right: 0px solid black;" width="120px"></td>
			<td style="padding: 0px !imporant;height: 0px !important;line-height: 0;border-left: 0px solid black;border-right: 0px solid black;" width="120px"></td>
			<td style="padding: 0px !imporant;height: 0px !important;line-height: 0;border-left: 0px solid black;border-right: 0px solid black;" width="100px"></td>
			<td style="padding: 0px !imporant;height: 0px !important;line-height: 0;border-left: 0px solid black;border-right: 0px solid black;" width="100px"></td>
			<td style="padding: 0px !imporant;height: 0px !important;line-height: 0;border-left: 0px solid black;border-right: 0px solid black;" width="17px"></td>
			<td style="padding: 0px !imporant;height: 0px !important;line-height: 0;border-left: 0px solid black;border-right: 0px solid black;" width="17px"></td>
		</tr>
		<tr>
			<!-- <td colspan="2">&nbsp;</td> -->
			<!-- <td colspan="8" style="height: 0px !important;border-bottom: none;border: none;font-size: 10pt;padding:0px !important;">
				<div style="border: 1px solid black; width: 15%;text-align: center;"><strong>CS Form No.9</strong> <br>
				Series of 2017</div>
			</td> -->
			<!-- <td colspan="4" style="height: 0px !important;border-bottom: none;border: none;padding:0px !important;">
				<div style="border: 1px solid black; width: 90%; text-align: center">Electronic copy to be submitted to the CSC <br>
				FO must be in MS Excel format
				</div>
			</td> -->
		</tr>
		<tr>
			<td colspan="14" style="text-align: center;font-weight: bold; border: none;border-right: 0px solid black;font-size: 10pt;padding:0 !important;height: 0 !important;">Republic of the Philippines</td>
		</tr>
		<tr>
			<td colspan="14" style="text-align: center;font-weight: bold; border: none;border-right: 0px solid black;font-size: 10pt;padding:0 !important;height: 0 !important;">NATIONAL WATER RESOURCES BOARD</td>
		</tr>
		<tr>
			<td colspan="14" style="text-align: center;font-weight: bold; border: none;border-right: 0px solid black;font-size: 10pt;padding:0 !important;height: 0 !important;">Request for Publication of Vacant Position</td>
		</tr>
		<tr>
			<td colspan="14"></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
			<td colspan="12" style="height: 0 !important;line-height:1;border-bottom: none;font-size: 10pt;border-right: 0px solid black;padding:0 !important;">All interested applicants including persons with disability (PWD), members of indigenous communities, and those from any sexual orientation and gender identities (SOG) are encourage to apply for the position:</td>
		</tr>
		<!-- <tr>
			<td colspan="10">&nbsp;</td>
			<td colspan="4" style="height: 0 !important;line-height:0;border-bottom: none;font-weight: bold;font-size: 10pt;border-right: 0px solid black;padding:0 !important;text-align: center;"><u>&emsp;&emsp;
				<?php 
				if (isset($signatories[0]['employee_name']) && $signatories[0]['employee_name'] != "" && $signatories[0]['employee_name'] == "SEVILLO D. DAVID JR."){
					echo "DR. ".$signatories[0]['employee_name']. ", CESO III";
				}else{
					echo $signatories[0]['employee_name'];
				}
				?>&emsp;&emsp;</u></td>
		</tr>
		<tr>
			<td colspan="10">&nbsp;</td>
			<td colspan="4" style="height: 0 !important;line-height:0;text-align: center;border-bottom: none;font-weight: bold;font-size: 10pt;border-right: 0px solid black;padding:0 !important;">Executive Director</td>
		</tr>
		<tr>
			<td colspan="10">&nbsp;</td>
			<td colspan="4" style="height: 0 !important;border-bottom: none;font-size: 10pt;border-right: 0px solid black;padding-left:50px !important;">Date: _________________________________________</td>
		</tr> -->
		<tr>
			<td colspan="14" style="height: 0 !important;line-height:0;border-bottom: none;"></td>
		</tr>
		<tr>
			<td></td>
			<td style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;border-bottom: hidden;"></td>
			<td style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;border-right: 1px solid black;border-bottom: hidden;"></td>
			<td style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;border-right: 1px solid black;border-bottom: hidden;"></td>
			<td style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;border-right: 1px solid black;border-bottom: hidden;"></td>
			<td style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;border-right: 1px solid black;border-bottom: hidden;"></td>
			<td colspan="6" style="padding: 0px !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;border-right: 1px solid black;text-align: center;">Qualification Standards</td>
			<td style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;border-right: 1px solid black;border-bottom: hidden;"></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td style="padding: 0 !imporant;height: 0 !important;font-weight: bold;border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;text-align: center;">No</td>
			<td style="padding: 0 !imporant;height: 0 !important;font-weight: bold;border-top: 1px solid black;text-align: center;border-right: 1px solid black; width: 5%;">Position Title</td>
			<td style="padding: 0 !imporant;height: 0 !important;font-weight: bold;border-top: 1px solid black;text-align: center;border-right: 1px solid black;width: 7%;">Plantilla Item No.</td>
			<td style="padding: 0 !imporant;height: 0 !important;font-weight: bold;border-top: 1px solid black;text-align: center;border-right: 1px solid black; width: 2%;">Salary/Job/Pay Grade</td>
			<td style="padding: 0 !imporant;height: 0 !important;border-top: 1px solid black;text-align: center;border-right: 1px solid black;width: 7%;"><b>Monthly Salary</b></td>
			<td style="padding: 0 !imporant;height: 0 !important;font-weight: bold;border-top: 1px solid black;text-align: center;border-right: 1px solid black;width: 7%;">Education</td>
			<td style="padding: 0 !imporant;height: 0 !important;font-weight: bold;border-top: 1px solid black;text-align: center;border-right: 1px solid black;width: 7%;">Experience</td>
			<td style="padding: 0 !imporant;height: 0 !important;font-weight: bold;border-top: 1px solid black;text-align: center;border-right: 1px solid black;width: 7%;">Training</td>
			<td style="padding: 0 !imporant;height: 0 !important;font-weight: bold;border-top: 1px solid black;text-align: center;border-right: 1px solid black;width: 7%;">Eligibility</td>
			<td style="padding: 0 !imporant;height: 0 !important;border-top: 1px solid black;text-align: center;border-right: 1px solid black;width: 7%;"><strong>Competency</strong> (if applicable)</td>
			<td style="padding: 0 !imporant;height: 0 !important;border-top: 1px solid black;text-align: center;border-right: 1px solid black;width: 30%;"><strong>Duties and Responsibilities</strong></td>
			<td style="padding: 0 !imporant;height: 0 !important;border-top: 1px solid black;text-align: center;border-right: 1px solid black;width: 7%;"><strong>Place of Assignment</strong></td>
		</tr>
		<tr>
			<td></td>
			<td class="tdmargin id" style="padding: 0 !imporant;height: 0 !important;border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black; text-align: center;"></td>
			<td class="tdmargin name" style="padding: 0 !imporant;height: 0 !important;border-top: 1px solid black;text-align: center;border-right: 1px solid black; "></td>
			<td class="tdmargin code" style="padding: 0 !imporant;height: 0 !important;border-top: 1px solid black;text-align: center;border-right: 1px solid black; "></td>
			<td class="tdmargin grade" style="padding: 0 !imporant;height: 0 !important;border-top: 1px solid black;text-align: center;border-right: 1px solid black; "></td>
			<td class="tdmargin salary" style="padding: 0 !imporant;height: 0 !important;border-top: 1px solid black;text-align: center;border-right: 1px solid black; "></td>
			<td class="tdmargin education" style="padding: 0 !imporant;border-top: 1px solid black;text-align: center;border-right: 1px solid black; "></td>
			<td class="tdmargin experience" style="padding: 0 !imporant;border-top: 1px solid black;text-align: center;border-right: 1px solid black; "></td>
			<td class="tdmargin training" style="padding: 0 !imporant;border-top: 1px solid black;text-align: center;border-right: 1px solid black; "></td>
			<td class="tdmargin eligibility" style="padding: 0 !imporant;border-top: 1px solid black;text-align: center;border-right: 1px solid black; "></td>
			<td class="tdmargin competency" style="padding: 0 !imporant;border-top: 1px solid black;text-align: center;border-right: 1px solid black; "></td>
			<td class="tdmargin duties" style="padding: 0 !imporant;border-top: 1px solid black;text-align: center;border-right: 1px solid black; "></td>
			<td class="tdmargin place" style="padding: 0 !imporant;border-top: 1px solid black;text-align: center;border-right: 1px solid black; "></td>
			<td style="border-bottom: hidden;"></td>
		</tr>
		<tr>
			<td class="tdmargin" style="padding: 0 !imporant;height: 0 !important;line-height: 0;"></td>
			<td class="tdmargin" style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;"></td>
			<td class="tdmargin" style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;"></td>
			<td class="tdmargin" style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;"></td>
			<td class="tdmargin" style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;"></td>
			<td class="tdmargin" style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;"></td>
			<td class="tdmargin" style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;"></td>
			<td class="tdmargin" style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;"></td>
			<td class="tdmargin" style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;"></td>
			<td class="tdmargin" style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;"></td>
			<td class="tdmargin" style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;"></td>
			<td class="tdmargin" style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;"></td>
			<td class="tdmargin" style="padding: 0 !imporant;height: 0 !important;line-height: 0;border-top: 1px solid black;"></td>
		</tr>
		<!-- <tr>
			<td style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="12" style="padding: 0 !important; height: 0 !important;line-height: 1.5;font-size: 10pt">Interested and qualified applicants should signify their interest in writing.  Attach the following documents to the application letter and send to the address below not </td>
		</tr> -->
		<!-- <tr>
			<td style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="12" style="padding: 0 !important; height: 0 !important;line-height: 1.5;font-size: 10pt">later than  ________________________________ </td>
		</tr> -->
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;line-height: 1.5;font-size: 9pt"><b>Assesment Process:</b></td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding-top: 0;padding-bottom: 0;padding-left: 20px !important; height: 0 !important;font-size: 9pt">1.	Initial Assessment Steps</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding-top: 0;padding-bottom: 0;padding-left: 40px !important; height: 0 !important;font-size: 9pt">a.	Personnel & Records Section Pre-screening</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;"></td>
			<td colspan="11" style="padding-top: 0;padding-bottom: 0;padding-left: 40px !important; height: 0 !important;font-size: 9pt">b.	Human Resources Merit Promotion and Selection Board (HRMPSB) screening (Paper Evaluation)</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding-top: 0;padding-bottom: 0;padding-left: 20px !important; height: 0 !important;font-size: 9pt">2.	Further Assessment Steps</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding-top: 0;padding-bottom: 0;padding-left: 40px !important; height: 0 !important;font-size: 9pt">a.	Interview</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding-top: 0;padding-bottom: 0;padding-left: 40px !important; height: 0 !important;font-size: 9pt">b.	Other work-related written examinations</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;line-height: 1.5;font-size: 9pt"><b>Documentary Requirements:</b></td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;line-height: 1.5;font-size: 9pt">*All interested qualified applicants shall submit the following to the Personnel & Records Section, 8th Floor NIA Bldg. EDSA, Q.C., or email to: personnel@nwrb.gov.ph</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding-top: 0;padding-bottom: 0;padding-left: 20px !important; height: 0 !important;font-size: 9pt">a. Application letter(indicating the position applied for, item number, and the name of the division where the vacancy is)</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding-top: 0;padding-bottom: 0;padding-left: 20px !important; height: 0 !important;font-size: 9pt">b. Fully accomplished Personal Data Sheet (PDS) with recent passport-sized picture (CS Form No. 212, Revised 2017) which can be downloaded at www.csc.gov.ph;</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding-top: 0;padding-bottom: 0;padding-left: 20px !important; height: 0 !important;font-size: 9pt">c. Transcript of Records (photocopy)</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding-top: 0;padding-bottom: 0;padding-left: 20px !important; height: 0 !important;font-size: 9pt">d. Diploma and/or certificate of eligibility/rating/license (photocopy)</td>
		</tr>
		<!-- <tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;line-height: 1.5;font-size: 11pt">5. Authenticated copy of certificate of eligibility/rating/license (photocopy)</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;line-height: 1.5;font-size: 11pt">6. Certificate of employment with actual duties and responsibilities (if applicable)</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;line-height: 1.5;font-size: 11pt">7. Certificate of training/seminars attended (photocopy)</td>
		</tr> -->
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding-top: 0;padding-bottom: 0;padding-left: 20px !important; height: 0 !important;font-size: 9pt">e. Performance rating in the last two rating period (if applicable)</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;line-height: 1.5;font-size: 9pt">*Applicant who fail to submit complete documentary requirements as prescribed shall not be included in the evaluation.</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;line-height: 1.5;font-size: 9pt"><b>Other Relevant Information:</b></td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;line-height: 1.5;font-size: 9pt">*Candidate/s found by the HRMPSB to have met the Minimum Qualification Requirements and succesfully hurdled the Assesment Process will be certified by the HRMPSB as qualified for appointment/promotion to the subject vacancy.</td>
		</tr>
		<!-- <tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 3;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;line-height: 1.5;font-size: 9pt"><strong>QUALIFIED APPLICANT</strong> are advised to hand in or send through courier/email their application to:</td>
		</tr> -->
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;font-size: 9pt"><strong>&nbsp;</strong></td>
		</tr>
		<tr>

			<td colspan="2" style="padding: 0 !important; height: 0 !important;"></td>
			<!-- <td colspan="11" style="padding: 0 !important; height: 0 !important;font-size: 10pt"><strong><u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u></strong></td> -->
			<td colspan="11" style="padding: 0 !important; height: 0 !important;font-size: 9pt"><strong class="signatory">
				<u>&emsp;&emsp;&emsp;
				<?php 
				if (isset($signatories[0]['employee_name']) && $signatories[0]['employee_name'] != "" && $signatories[0]['employee_name'] == "RICKY A. ARZADON "){
					// echo "DR. ".$signatories[0]['employee_name']. ", CESO III";
					echo $signatories[0]['employee_name']. ", CESO IV";
				}else{
					echo $signatories[0]['employee_name'];
				} 
				?>
				&emsp;&emsp;&emsp;&emsp;</u></strong></td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="10" style="padding: 0 !important; height: 0 !important;font-size: 9pt;">&emsp;&emsp;&emsp;&emsp;&emsp;<strong>OIC Executive Director</strong></td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;font-size: 9pt"><strong>&nbsp;</strong></td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;font-size: 9pt">Date of Publication: <span><u class="publication">&nbsp;&nbsp;&nbsp;</u></td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1.5;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;font-size: 9pt">Date of Posting: <span><u class="posting">&nbsp;&nbsp;&nbsp;</u></strong></td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;font-size: 9pt"><strong>&nbsp;</strong></td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;font-size: 8pt">NATIONAL WATER RESOURCES BOARD</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;font-size: 8pt">8th Floor, NIA Building, EDSA, Diliman, Quezon City</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;font-size: 8pt"><u>personnel@nwrb.gov.ph/personnel.nwrb@gmail.com</u></td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0 !important; height: 0 !important;line-height: 1;"></td>
			<td colspan="11" style="padding: 0 !important; height: 0 !important;font-size: 8pt"><strong>APPLICATIONS WITH INCOMPLETE DOCUMENTS SHALL NOT BE ENTERTAINED.</strong></td>
		</tr>

	</table>
