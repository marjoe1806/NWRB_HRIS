<style type="text/css">
    .button{
        /*position: absolute;*/
        padding-top: 30px;
    }
</style>
<div class="row">
    <div class="col-md-12 text-right">
	<button type="button" id="btnXls" class="btn btn-success btn-circle-lg waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Export xls">
				<i class="material-icons">archive</i>
			</button>
    </div>
</div>
<hr>
<div class="table-responsive listTable" style="width:100%;">
    <table id="datatables1" class="table table-hover table-striped">
        <thead> 
            <tr>
                <th width="5%">No.</th>
                <th width="30%">Name</th>
                <th width="15%">Date of Filing</th>
                <th width="25%">Inclusive Dates</th>
                <th width="25%">Status</th>
                <!-- <th>Remarks</th>                              -->
          </tr>
        </thead>
    </table>
</div>