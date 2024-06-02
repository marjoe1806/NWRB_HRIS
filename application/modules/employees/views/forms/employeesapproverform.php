<?php 
$required = ""; 
$inputRequired = "inputRequired"; 
?>
    <style type="text/css">
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
        
        .displaynone{
            display: none;
        }
    </style>
        <div class="card">
            <div class="header">
                <h1>Approvers</h1>
            </div>
            <div class="body">
                <form id="approverCTOForm" enctype="multipart/form-data">
                    <input type="hidden" name="id_cto" id="id_cto" readonly>
                    <input type="hidden" name="approver_id_cto" id="approver_id_cto" readonly>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Employee</label>
                                <div class="form-line employee_select">
                                    <select name="employee_id" id="employee_id" class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type</label>
                                <div class="form-line">
                                    <select name="approve_type_cto" id="approve_type_cto" class="form-control">
                                        <option value="1">For Certification (Head HR)</option>
                                        <option value="2">For Recommendation (Section Head)</option>
                                        <option value="3">For Recommendation (Division Head)</option>
                                        <option value="4">For Approval (Deputy)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Status</label>
                                <br>
                                <input type="checkbox" class="form-control chk" name="isActive_cto" id="isActive_cto">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-info btn-md">Submit</button>
                            <button type="button" id="btnUpdate_cto" class="btn btn-warning btn-md displaynone">Update</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <h4>Compensatory Time Off Request Approver</h4>
                    <table class="table table-bordered table-hover nowrap" id="tblctoapprovers">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <hr>
                <form id="approverLeaveForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id" readonly>
                    <input type="hidden" name="approver_id" id="approver_id" readonly>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Employee</label>
                                <div class="form-line employee_select">
                                    <select name="employee_id" id="employee_id" class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type</label>
                                <div class="form-line">
                                    <select name="approve_type" id="approve_type" class="form-control">
                                        <option value="1">FOR CERTIFICATION</option>
                                        <option value="2">FOR RECOMMENDATION (Supervisor)</option>
                                        <option value="3">FOR RECOMMENDATION (Dept.Head)</option>
                                        <option value="8">FOR RECOMMENDATION (Deputy)</option>
                                        <option value="4">FOR APPROVAL</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Status</label>
                                <br>
                                <input type="checkbox" class="form-control chk" name="isActive" id="isActive">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-info btn-md">Submit</button>
                            <button type="button" id="btnUpdate" class="btn btn-warning btn-md displaynone">Update</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <h4>Leave Request Approver</h4>
                    <table class="table table-bordered table-hover nowrap" id="tblleaveapprovers">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <hr>
                <form id="approverOBForm" enctype="multipart/form-data">
                    <input type="hidden" name="id_OB" id="id_OB" readonly>
                    <input type="hidden" name="approver_id_OB" id="approver_id_OB" readonly>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Employee</label>
                                <div class="form-line employee_select">
                                    <select name="employee_id" id="employee_id" class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type</label>
                                <div class="form-line">
                                    <select name="approve_type_OB" id="approve_type_OB" class="form-control">
                                        <option value="2">FOR RECOMMENDATION (Supervisor)</option>
                                        <option value="4">FOR APPROVAL</option>
                                        <option value="5">FOR ASSIGNING DRIVER AND VEHICLE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Status</label>
                                <br>
                                <input type="checkbox" class="form-control chk" name="isActive_OB" id="isActive_OB">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-info btn-md">Submit</button>
                            <button type="button" id="btnUpdateOB" class="btn btn-warning btn-md displaynone">Update</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <h4>Locator Slip Approver</h4>
                    <table class="table table-bordered table-hover nowrap" id="tblobapprovers">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <hr>
                <form id="approverTravelForm" enctype="multipart/form-data">
                    <input type="hidden" name="id_TO" id="id_TO" readonly>
                    <input type="hidden" name="approver_id_TO" id="approver_id_TO" readonly>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Employee</label>
                                <div class="form-line employee_select">
                                    <select name="employee_id" id="employee_id" class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type</label>
                                <div class="form-line">
                                    <select name="approve_type_TO" id="approve_type_TO" class="form-control">
                                        <option value="0">FOR RECOMMENDATION <br><small>(Sec. Head)</small></option>
                                        <option value="1">FOR RECOMMENDATION <br><small>(Div. Head)</small></option>
                                        <option value="2">FOR CERTIFICATION <br><small>(Deputy)</small></option>
                                        <option value="3">FOR APPROVAL <br><small>(Director)</small></option>
                                        <option value="4">FOR DRIVER AND VEHICLE ASSIGNING <br><small>(GSS)</small></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Status</label>
                                <br>
                                <input type="checkbox" class="form-control chk" name="isActive_TO" id="isActive_TO">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-info btn-md">Submit</button>
                            <button type="button" id="btnUpdateTO" class="btn btn-warning btn-md displaynone">Update</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <h4>Travel Order Request Approver</h4>
                    <table class="table table-bordered table-hover nowrap" id="tbltravelorderapprover">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <hr> <!--OT Request-->
                <!-- <form id="approverOvertimeForm" enctype="multipart/form-data">
                    <input type="hidden" name="id_OT" id="id_OT" readonly>
                    <input type="hidden" name="approver_id_OT" id="approver_id_OT" readonly>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Employee</label>
                                <div class="form-line employee_select">
                                    <select name="employee_id" id="employee_id" class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type</label>
                                <div class="form-line">
                                    <select name="approve_type_OT" id="approve_type_OT" class="form-control">
                                        <option value="6">WITH PAY</option>
                                        <option value="7">FOR CTO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Status</label>
                                <br>
                                <input type="checkbox" class="form-control chk" name="isActive_Overtime" id="isActive_Overtime">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-info btn-md">Submit</button>
                            <button type="button" id="btnUpdateOT" class="btn btn-warning btn-md displaynone">Update</button>
                        </div>
                    </div>
                </form> -->
                <!-- <div class="table-responsive">
                    <h4>Overtime Request Approver</h4>
                    <table class="table table-bordered table-hover nowrap" id="tblovertimeapprovers">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div> -->
            </div>
        </div>

    

