<?php 
	$readonly = "";
	if($key == "viewWithHoldingTaxesDetails")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" name="id" class="id" value="">
    	<?php if($key == "addWithHoldingTaxes"): ?>
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<div class="row clearfix">
			<div class="col-md-6">
                <label class="form-label">Pay Basis <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="pay_basis" id="pay_basis" class="form-control" value="Monthly" required readonly>
                	</div>
            	</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Effectivity <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="effectivity" id="effectivity" class="daterangepicker form-control" required <?php echo $readonly ?>>
                	</div>
            	</div>
            </div>
		</div>
		<hr>
		<div class="row clearfix">
			<table class="table table-hover table-bordered" id="formTable">
				<thead >
					<tr>
						<th colspan="2" class="text-center" valign="bottom">COMPENSATION LEVEL (Php.)</th>
						<th rowspan="2">Tax Percentage (%)</th>
						<th rowspan="2">Tax Additional (Php.)</th>
						<th rowspan="2"></th>
					</tr>
					<tr>
						<th class="text-center">From</th>
						<th class="text-center">To</th>
					</tr>
				</thead>
				<tbody>
					<tr class="first_row">
						<td>
							<div class="form-group">
			                	<div class="form-line">
			                		<input type="text" name="compensation_level_from[]" id="compensation_level_from" class="compensation_level_from currency form-control" required <?php echo $readonly ?>>
			                	</div>
			            	</div>	
						</td>
						<td>
							<div class="form-group">
			                	<div class="form-line">
			                		<input type="text" name="compensation_level_to[]" id="compensation_level_to" class="compensation_level_to currency form-control" required <?php echo $readonly ?>>
			                	</div>
			            	</div>	
						</td>
						<td>
							<div class="form-group">
			                	<div class="form-line">
			                		<input type="text" name="tax_percentage[]" id="tax_percentage" class="tax_percentage form-control currency" required <?php echo $readonly ?>>
			                	</div>
			            	</div>	
						</td>
						<td>
							<div class="form-group">
			                	<div class="form-line">
			                		<input type="text" name="tax_additional[]" id="tax_additional" class="tax_additional form-control currency" required <?php echo $readonly ?>>
			                	</div>
			            	</div>	
						</td>
						<td class="text-right">
							<button type="button" id="removeTaxRow" style="visibility:hidden;" class="removeTaxRow btn btn-danger btn-circle waves-effect waves-circle waves-float"><i class="material-icons">remove</i></button>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5" class="text-right"><button type="button" id="addNewTaxRow" class="btn btn-info btn-circle waves-effect waves-circle waves-float"><i class="material-icons">add</i></button></td>
					</tr>
				</tfoot>
				
			</table>
		</div>
		<?php endif; ?>
		<?php if($key == "updateWithHoldingTaxes"): ?>
		<div class="row clearfix">
			<div class="col-md-6">
				<label class="form-label">Compensation Level From (Php.)<span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="compensation_level_from" id="compensation_level_from" class="compensation_level_from form-control" required <?php echo $readonly ?>>
                	</div>
            	</div>	
            </div>
            <div class="col-md-6">
                <label class="form-label">Compensation Level To (Php.)<span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="compensation_level_to" id="compensation_level_to" class="compensation_level_to form-control" required <?php echo $readonly ?>>
                	</div>
            	</div>	
            </div>
            <div class="col-md-6">
                <label class="form-label">Tax Percentage (%)<span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="tax_percentage" id="tax_percentage" class="tax_percentage currency form-control" required <?php echo $readonly ?>>
                	</div>
            	</div>	
            </div>
            <div class="col-md-6">
                <label class="form-label">Tax Additional (Php.)<span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="text" name="tax_additional" id="tax_additional" class="tax_additional currency form-control" required <?php echo $readonly ?>>
                	</div>
            	</div>	
            </div>
		</div>
	<?php endif; ?>
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addWithHoldingTaxes"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateWithHoldingTaxes"): ?>
	        <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update</span>
	        </button>
	        <!-- <?php if($status == "INACTIVE"): ?>
		        <a id="activateUserLevelConfig" class="activateUserLevelConfig" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'activateUserLevelConfig'; ?>">
		            <button class="btn btn-success btn-sm waves-effect" type="button">
		                <i class="material-icons">visibility</i><span> Activate</span>
		            </button>
	            </a>
	        <?php endif; ?>
	        <?php if($status == "ACTIVE"): ?>
            <a id="deactivateUserLevelConfig" class="deactivateUserLevelConfig" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'deactivateUserLevelConfig'; ?>">
	            <button  class="btn btn-danger btn-sm waves-effect" type="button">
	                <i class="material-icons">visibility_off</i><span> Deactivate</span>
	            </button>
            </a>
            <?php endif; ?> -->
            
        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>

