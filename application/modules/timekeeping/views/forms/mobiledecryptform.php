<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/dropzone/dropzone.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/dropzone/dropzone.css">

<style>
	.dropzone {
    background: white !important;
    border-radius: 5px !important;
    border: 5px dashed #ddd !important;
    border-image: none !important;
    margin-left: auto !important;
    margin-right: auto !important;
	}

	.dropzone div {
		z-index: 1;
	}

</style>

<div class="row clearfix">
	<div class="col-md-10 col-md-offset-1">
	<h4>&nbsp;</h4>
		<form action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" class="dropzone"
		 id="my-dropzone"></form>
	</div>
</div>
<div class="row clearfix">
	<div class="col-md-10 col-md-offset-1">
		<div class="text-right" style="width:100%;">
			<button id="importRawDTR" class="btn btn-primary btn-sm waves-effect" type="submit">
				<i class="material-icons">cloud_upload</i>
				<span> Import Data</span>
			</button>
			<button id="clear-dropzone" class="btn btn-warning btn-sm waves-effect" type="submit">
				<i class="material-icons">clear</i>
				<span> Clear</span>
			</button>
		</div>
	</div>
</div>
