<style type="text/css">
    .button{
        /*position: absolute;*/
        padding-top: 30px;
    }
</style>
<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="body">
                <div class="row">
                    <div class="col-md-2 box">
                        <h5 class="text-info">Pay Basis</h5>
                        <div class="pay_basis_select">
                            <select class="pay_basis form-control" name="pay_basis" id="pay_basis" data-live-search="true">
                                <option value=""></option>
                            </select>
                        </div>                  
                    </div>
                    <div class="col-md-3 box">
                        <h5 class="text-info">Candidates</h5>
                        <div class="candidate">
                            <select class="candidate form-control" name="candidate" id="candidate" data-live-search="true">
                                <option value=""></option>
                                <option class="all" value="">All</option>
                                <option class="step_inc" value="step_inc">Step Increment</option>
                                <option class="loyalty" value="loyalty">Loyalty</option>
                            </select>
                        </div>                  
                    </div>
                    <div class="col-md-3 period_div" style="display: none;">
                        <h5 class="text-info">Period</h5>
                        <div class="period_select">
                            <select class="period form-control" name="period" id="period" data-live-search="true">
                                <option class="" value=""></option>
                                <option class="this_month" value="this_month">This Month</option>
                                <option class="this_year" value="this_year">This Year</option>
                            </select>
                        </div>                  
                    </div>
                    <div class="col-md-3 loyalty_div" style="display: none;">
                        <h5 class="text-info">For Loyalty</h5>
                        <div class="forloyal">
                            <select class="forloyal form-control" name="forloyal" id="forloyal" data-live-search="true">
                                <option value=""></option>
                                <option class="" value="10">10th Year</option>
                                <option class="" value="15">15th Year</option>
                                <option class="" value="20">20th Year</option>
                                <option class="" value="25">25th Year</option>
                                <option class="" value="30">30th Year</option>
                                <option class="" value="35">35th Year</option>
                                <option class="" value="40">40th Year</option>
                                <option class="" value="45">45th Year</option>
                            </select>
                        </div>                  
                    </div>
                    <div class="col-md-3 box">
                        <h5 class="text-info">Division</h5>
                        <div class="division_select">
                            <select class="division_id form-control" name="division_id" id="division_id" data-live-search="true">
                                <option value=""></option>
                            </select>
                        </div>                  
                    </div>
                    <div class="col-md-1 button">
                        <div class="form-group">
                            <button type="button" id="btnsearch" class="btn btn-primary btn-circle waves-effect waves-circle waves-float"><i class="material-icons">search</i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>    
<br>
<div class="table-responsive listTable" style="width:100%;">
    <table id="datatables" class="table table-hover table-striped">
        <thead> 
            <tr>
                <th>Employee #</th>
                <th width="20%">Name</th>
                <th>Pay Basis</th>
                <th>Current Department</th>
                <th>Start Date</th>
                <th>Last Promotion</th>
                <th width="20%">Years of Service</th>                                 
          </tr>
        </thead>
    </table>
</div>