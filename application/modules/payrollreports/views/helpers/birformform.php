<?php
// ini_set('max_input_vars', 5000);
?>

<div class="container-fluid" >
    <div class="col-md-12">
        <div style="width:100%; overflow-x:auto;">
            <div>
            <style>
					th{
						font-size: 8pt;
						text-align: center;
					}
					#table1 table.container{
						page-break-after: always;
					}
					#table1 .table1_header{
						display: table-header-group;
						
					}
					#table1 .text-align{
						text-align:left;
					}
					.container td{
						border-bottom: 1px dashed black;
						font-size: 10pt;
					}
					#table1{
						width: 250%;
					}
					.thead{
						border-bottom:1px solid black;
						padding: 5px;
					}
					.form{
						display: flex;
						flex:1;
						min-height: 0px;
					}	
				</style>
				<?php
					
				?>
				<!-- -->
				<form action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/birFormPdfView/'.$employee_id; ?>" method="POST" target="_blank">
                    <!-- <div class="col-md-12">
                        <h5 class="text-info">Whole Period <span class="text-danger">*</span></h5>
                        <div class="form-group">
                            <div class="form-line payroll_period_select">
                                <select class="payroll_period_id form-control" name="payroll_period_id" id="payroll_period_id" data-live-search="true">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div> -->
			<!-- <div class="row">
				<div class="col-md-4">
					<span>For BIR &nbsp;&nbsp; BCS/<br>Use Only Item:</span>
				</div>
				<div class="col-md-4" style="text-align:center;">
					<div>Republic of the Philippines</div>
					<div> Department of Finance</div>
					<div>Bureau of Internal Revenue</div>
				</div>
				
				
			</div>
				<div class="row">
					<div class="col-lg-12">
					<div class="col-md-2" style="text-align:center;">
						<div>BIR Form No.</div>
						<div>2316</div>
						<div>September 2021(ENCS)</div>
					</div>
					<div class="col-md-7" style="text-align:center;">
						<div>Certificate of Compensation</div>
						<div>Payment/Tax Withheld</div>
						<div>For Compensation Payment With or Without Tax Withheld</div>
					</div>
					<div class="col-md-3" style="text-align:center;">
						<div></div>
						<div>2316 9/21ENCS</div>
					</div>
					<div class="col-md-12">
						Fill in all applicable spaces. Mark all appropriate boxes with an "X".
					</div>
					<div class="col-md-6">
						<div class="col-sm-3">1 For the Year <br> (YYYY)</div>
						<div class="col-sm-6"><input type="text" class="form-control"></div>
						<div class="col-sm-12" style="text-align:center;">
							Part I - Employee Information
						</div>
						<div class="col-sm-2">
							3 TIN
						</div>
						<div class="col-sm-2"><input type="text" class="form-control"></div>
						<div class="col-sm-2"><input type="text" class="form-control"></div>
						<div class="col-sm-2"><input type="text" class="form-control"></div>
						<div class="col-sm-3"><input type="text" class="form-control"></div>
						<div class="col-sm-9">
							4 Employee's Name (Last Name, First Name, Middle Name) 
							<input type="text" class="form-control">
						</div>
						<div class="col-sm-3">
							5 RDO Code
							<input type="text" class="form-control">
						</div>
						<div class="col-sm-9">
							6 Registered Address
							<input type="text" class="form-control">
						</div>
						<div class="col-sm-3">
							6A ZIP Code
							<input type="text" class="form-control">
						</div>
						<div class="col-sm-9">
							6B Local Home Address
							<input type="text" class="form-control">
						</div>
						<div class="col-sm-3">
							6C ZIP Code
							<input type="text" class="form-control">
						</div>
						<div class="col-sm-12">
							6D Foreign Address
							<input type="text" class="form-control">
						</div>
						<div class="col-sm-5">
							7 Date of Birth (MM/DD/YYYY)
							<input type="text" class="form-control">
						</div>
						<div class="col-sm-7">
							8 Contact Number
							<input type="text" class="form-control">
						</div>
						<div class="col-sm-8">
							<p style="margin-top:10px !important;">
							9 Statutory Minimum Wage rate per day
							</p>
						</div>
						<div class="col-sm-4">
							<input type="text" class="form-control">
						</div>
						<div class="col-sm-8">
							<p style="margin-top:10px !important;">
							10 Statutory Minimum Wage rate per month
							</p>
						</div>
						<div class="col-sm-4">
							<input type="text" class="form-control">
						</div>
					</div>
					<div class="col-md-6">
						<div class="col-sm-3">2. For the Period <br> From (MM/DD)</div>
						<div class="col-sm-3"><input type="text" class="form-control"></div>
						<div class="col-sm-2"><br>To(MM/DD)</div>
						<div class="col-sm-3"><input type="text" class="form-control"></div>
						<div class="col-sm-12" style="text-align:center;">
						Part IV-B Details of Compensation Income & Tax Withheld from Present Employer
						</div>
					</div>
					</div>
				</div> -->
			
					
	    <div class="row">
            <div class="col-md-12"></div>
                <div class="col-md-12">
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
		</div>
	</form>