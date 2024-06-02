<?php
	$jsondata = isset($data->Data) ? $data->Data->details : array();
	$userlevelid 	=  isset($jsondata->userlevelid)?$jsondata->userlevelid:"";
	$description 	=  isset($jsondata->description)?$jsondata->description:"";
	$userlevelname  = isset($jsondata->userlevelname)?$jsondata->userlevelname:"";
	$status 		= isset($jsondata->status)?$jsondata->status:"";
	$levelmodules 	= isset($jsondata->modules)?$jsondata->modules:array();	
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" name="UserLevelId" value="<?php echo $userlevelid; ?>">
    	<input type="hidden" name="Status" value="<?php echo ($status == 'ACTIVE')?'1':'0'; ?>">
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div class="form-group form-float">
					<label class="form-label">User Level Name <?php //echo $key; ?><span class="text-danger">*</span></label>
					<div class="form-line <?php echo ($key=='updateUserLevelConfig')?'focused':''; ?>">
						<input type="text" class="required form-control" name="UserLevelName" required="" aria-required="true" value="<?php echo $userlevelname; ?>" style="text-transform:uppercase;" placeholder="User Level Name">
					</div>
				</div>
				<br>
				<div class="form-group form-float">
					<label class="form-label">Description <span class="text-danger">*</span></label>
					<div class="form-line <?php echo ($key=='updateUserLevelConfig')?'focused':''; ?>">
						<input type="text" class="form-control" name="Description" required="" aria-required="true" value="<?php echo $description; ?>" placeholder="Description">
					</div>
				</div>
				<br>
				<div class="form-group form-float">
					<label class="form-label">Status <span class="text-danger">*</span></label>
					<div class="form-line <?php echo ($key=='updateUserLevelConfig')?'focused':''; ?>">
						<select class="required form-control" name="Status">
							<option value="ACTIVE" <?php echo ($status == "ACTIVE")?"selected":""; ?>>ACTIVE</option>
							<option value="INACTIVE" <?php echo ($status == "INACTIVE")?"selected":""; ?>>INACTIVE</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
				<div style="height:275px;overflow:auto;">
					<table id="<?php echo "tbl-".$key; ?>" class="table table-hover table-striped">
						<thead>
							<tr>
								<th style="width:5%;">
					       			<!-- <input type="checkbox" id="check_all<?php //echo $key; ?>" <?php echo (sizeof($modules->Data->details) == sizeof($levelmodules))?"checked":""; ?>>
                                    <label for="check_all<?php //echo $key; ?>"></label> -->
								</th>
								<th style="width:50%;">Description</th>
								<th style="width:15%;">Module</th>
								<th style="width:10%;">Status</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if(isset($modules->Data->details) && sizeof($modules->Data->details) > 0): 
            					foreach ($modules->Data->details as $index => $value) { ?>
									<tr>
										<td style="width:5%;">
											<input type="checkbox" class="checkbox_table" id="checkbox<?php echo $index.$key; ?>" value="<?php echo $value->module; ?>" name="Roles[]" <?php echo (in_array($value->module,$levelmodules))?"checked":""; ?> >
		                                    <label for="checkbox<?php echo $index.$key; ?>"></label> 
		                                </td>
										<td style="width:50%;"><?php echo $value->description; ?></td>
										<td style="width:15%;"><?php echo $value->module; ?></td>
										<td style="width:10%;"><?php echo $value->status; ?></td>
									</tr>
							<?php }
							endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>        
    </div>
    <!-- 
    <div class="form-group">
        <input type="checkbox" id="checkbox" name="checkbox">
        <label for="checkbox">Assistant Override</label>
    </div> -->
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addUserLevelConfig"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add_circle</i><span> Add User Level</span>
	        </button>
	        <button id="cancelUpdateForm" class="btn btn-warning btn-sm waves-effect" data-dismiss="modal" type="button">
                <i class="material-icons">close</i><span> Close</span>
            </button>
    	<?php endif; ?>
    	<?php if($key == "updateUserLevelConfig"): ?>
	        <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Save</span>
	        </button>
	        <?php if($status == "INACTIVE"): ?>
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
            <?php endif; ?>
            <button id="cancelUpdateForm" class="btn btn-warning btn-sm waves-effect" data-dismiss="modal" type="button">
                <i class="material-icons">close</i><span> Close</span>
            </button>
        <?php endif; ?>
    </div>
</form>

