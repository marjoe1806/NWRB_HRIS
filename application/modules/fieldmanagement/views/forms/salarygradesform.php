<?php 
	$readonly = "";
?>
<form id="addSalaryGrades" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addSalaryGrades' ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" name="id" class="id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<hr>
		<div class="row clearfix">
			<div class="table-responsive">
				<table class="table table-hover table-bordered" id="formTable">
					<thead >
						<tr>
							<th class="bg-info text-center" valign="bottom">Grade</th>
							<th class="head_step" nowrap valign="bottom" id="step_1">Step <span value="" class="step_value">1</span>&emsp;&nbsp;<i class="fa fa-times text-danger" style="visibility:hidden;"></i></th>
							<th class="head_step" nowrap valign="bottom" id="step_2">Step <span value="" class="step_value">2</span>
								&emsp;&nbsp;<a class="removeStep" style="visibility:hidden;" href="#"><i class="fa fa-times text-danger"></i></a>
							</th>
							<th class="head_step" nowrap valign="bottom" id="step_3">Step <span value="" class="step_value">3</span>
								&emsp;&nbsp;<a class="removeStep" style="visibility:hidden;" href="#"><i class="fa fa-times text-danger"></i></a>
							</th>
							<th class="head_step" nowrap valign="bottom" id="step_4">Step <span value="" class="step_value">4</span>
								&emsp;&nbsp;<a class="removeStep" style="visibility:hidden;" href="#"><i class="fa fa-times text-danger"></i></a>
							</th>
							<th class="head_step" nowrap valign="bottom" id="step_5">Step <span value="" class="step_value">5</span>
								&emsp;&nbsp;<a class="removeStep" style="visibility:hidden;" href="#"><i class="fa fa-times text-danger"></i></a>
							</th>
							<th class="head_step" nowrap valign="bottom" id="step_6">Step <span value="" class="step_value">6</span>
								&emsp;&nbsp;<a class="removeStep" style="visibility:hidden;" href="#"><i class="fa fa-times text-danger"></i></a>
							</th>
							<th class="head_step" nowrap valign="bottom" id="step_7">Step <span value="" class="step_value">7</span>
								&emsp;&nbsp;<a class="removeStep" style="visibility:hidden;" href="#"><i class="fa fa-times text-danger"></i></a>
							</th>
							<th class="head_step" nowrap valign="bottom" id="step_8">Step <span value="" class="step_value">8</span>
								&emsp;&nbsp;<a class="removeStep" style="visibility:hidden;" href="#"><i class="fa fa-times text-danger"></i></a>
							</th>

							<th class="addStep">
								<button type="button" id="addNewStep" class="form-control btn btn-success waves-effect waves-float"><i class="material-icons">add</i> Add Step</button>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr class="grade_0 first_row">
							<td class="bg-info">
								<div class="form-group">
				                	<div class="form-line">
				                		<input type="text" name="salary[0][grade]" id="grade1" class="grade currency form-control" value="1" required readonly="">
				                	</div>
				            	</div>	
							</td>
							<td>
								<div class="form-group">
				                	<div class="form-line">
				                		<input type="text" name="salary[0][step][0]" id="step_1" value="" class="step currency form-control" required <?php echo $readonly ?>>
				                	</div>
				            	</div>	
							</td>
							<td>
								<div class="form-group">
				                	<div class="form-line">
				                		<input type="text" name="salary[0][step][1]" id="step_2" value="" class="step currency form-control" required <?php echo $readonly ?>>
				                	</div>
				            	</div>	
							</td>
							<td>
								<div class="form-group">
				                	<div class="form-line">
				                		<input type="text" name="salary[0][step][2]" id="step_3" value="" class="step currency form-control" required <?php echo $readonly ?>>
				                	</div>
				            	</div>	
							</td>
							<td>
								<div class="form-group">
				                	<div class="form-line">
				                		<input type="text" name="salary[0][step][3]" id="step_4" value="" class="step currency form-control" required <?php echo $readonly ?>>
				                	</div>
				            	</div>	
							</td>
							<td>
								<div class="form-group">
				                	<div class="form-line">
				                		<input type="text" name="salary[0][step][4]" id="step_5" value="" class="step currency form-control" required <?php echo $readonly ?>>
				                	</div>
				            	</div>	
							</td>
							<td>
								<div class="form-group">
				                	<div class="form-line">
				                		<input type="text" name="salary[0][step][5]" id="step_6" value="" class="step currency form-control" required <?php echo $readonly ?>>
				                	</div>
				            	</div>	
							</td>
							<td>
								<div class="form-group">
				                	<div class="form-line">
				                		<input type="text" name="salary[0][step][6]" id="step_7" value="" class="step currency form-control" required <?php echo $readonly ?>>
				                	</div>
				            	</div>	
							</td>
							<td>
								<div class="form-group">
				                	<div class="form-line">
				                		<input type="text" name="salary[0][step][7]" id="step_8" value="" class="step currency form-control" required <?php echo $readonly ?>>
				                	</div>
				            	</div>	
							</td>
							<td class="text-right remove-container">
								<button type="button" id="removeGradeRow" style="visibility:hidden;" class="removeGradeRow btn btn-danger btn-circle waves-effect waves-circle waves-float"><i class="material-icons">remove</i></button>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="10" class="addGradeCon text-right"><button type="button" id="addNewGradeRow" class="btn btn-info btn-circle waves-effect waves-circle waves-float"><i class="material-icons">add</i></button></td>
						</tr>
					</tfoot>
					
				</table>
			</div>
		</div>
    </div>
    <div class="text-right" style="width:100%;">
    	
		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
            <i class="material-icons">save</i><span> Save</span>
        </button>
    </div>
</form>

