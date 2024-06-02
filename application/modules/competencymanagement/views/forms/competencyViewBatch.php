<?php 
	$readonly = "";
	if($key == "viewLoansDetails")
		$readonly = "disabled";
?>

    <div class="form-elements-container">
    	<input type="hidden" name="id" class="id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<h3 class="loan_title text-primary">Competency Test Exam Result</h3>
		<hr>
		<div class="table-responsive sub_loan_table">
			<form id="addSubLoans" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addSubLoans'; ?>" method="POST">
				<table class="table">
					<tfoot>
						<!-- <tr>
							<td colspan="4">
								<h4>View Batch</h4>
							</td>
						</tr> -->

						<tr>
							<td>
								<label for="">Batch Number</label></br>
								<?php echo $list->Data->reference ?>
								
							</td>
							<td>
								<label for="">Date</label></br>
								<?php echo $list->Data->date ?>
								
							</td>
							<td></td>
							<td class="text-right">
								<!-- <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
									<i class="material-icons">add</i><span> Add</span>
								</button> -->
							</td>
						</tr>
					</tfoot>
				</table>
			</form>
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<td>Examinee</td>
						<td>Rate</td>
						<td>Initial Score</td>
						<td>Additional Score</td>
						<td>Final Score</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
					<?php
					if(isset($list->Data->details) && sizeof($list->Data->details) > 0){ 
						foreach ($list->Data->details as $k => $v) { ?>
						<tr>
							<td><?php echo $v->emailaddress; ?></td>
							<td>
								<!-- Rate -->
								<?php 
								
								$total_score = $v->multiplication_res + $v->fill_res +  $v->enumeration_res + $v->essay_res;
								$total_points = $list->Data->total_points;


								if($total_score == 0){
									$rate_percent = 0;
								}else{
									$rate_percent = $total_score/$total_points;
									$rate_percent = $rate_percent*100;
								}	
								
								if($rate_percent > 70){
									echo 'Pass';
								}else{
									echo 'Fail';
								}


								?>
								
							</td>
							<td>
								<!-- Initial Score -->
								<?php echo $v->multiplication_res + $v->fill_res + $v->enumeration_res; ?>
							</td>
							<td>
								<!-- Additional Score -->
								<?php echo $v->essay_res; ?>
							</td>
							<td>
								<!-- Final Score -->
								<?php echo $v->multiplication_res + $v->fill_res +  $v->enumeration_res + $v->essay_res; ?>
							</td>
							<td>
								
								<!-- Buttons -->
								<a id="updateScore" class="updateScore btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top"
								title="Update Score" data-id="<?php echo $v->access_id; ?>" 
								data-loan_id="<?php echo $v->access_id; ?>" 
								href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateScoreForm'; ?>">
									<i class="material-icons">mode_edit</i>
								</a>
								
							</td>
						</tr>	
					<?php }
					}else{ ?>
						<tr>
							<td colspan="4">No data available.</td>
						</tr>
					<?php } ?>
					
				</tbody>
			</table>
		</div>
    </div>
    <div class="text-right" style="width:100%;">
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>

