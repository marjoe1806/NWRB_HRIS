<style>
table { border-collapse: collapse; width: 100%; }
th, td { background: #fff; padding: 8px 16px; }


.table-wrapper {
  overflow: auto;
  height: 300px;
}

.table-wrapper thead th {
  position: sticky;
  top: 0;
}
</style>
<div class="row clearfix" id="userLevelForm">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-blue">
				<h2>
					Import CTO Balance From Excel
					<small>Upload and Import CTO Balance via Excel File</small>
				</h2>
			</div>
			<div class="body">
				<div class="row">
					<div class="col-md-6">
						<input type="file" name="file[]" id="file" class="file form-control" multiple="">
					</div>
					<div class="col-md-6">
						<!-- <button type="button" id="viewfile" class="btn btn-info waves-effect">
				            <i class="material-icons">pageview</i>
				            <span> View</span>
				        </button> -->
				        <button type="button" id="importfile" class="btn btn-info waves-effect" data-baseurl="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'; ?>">
				            <i class="material-icons">cloud_upload</i>
				            <span> Import </span>
				        </button>
					</div>
				</div>
			</div>
		</div>
		<div class="card excel_card" style="display: none">
			<div class="body">
				<div class="row">
					<div class="col-md-12 table-wrapper">
						<table id="exceltable" class="table table-fixed"></table>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>


<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/timekeeping/importleavesfromexcel.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/excel/xls/xls.core.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/excel/xlsx/xlsx.core.min.js"></script>
