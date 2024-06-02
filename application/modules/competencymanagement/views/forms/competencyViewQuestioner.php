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



		
			<div>
			<?php
				if(isset($list->Data->details) && sizeof($list->Data->details) > 0){ 
					$i = 1;
					foreach ($list->Data->details as $k => $v) { ?>
					<label for=""><?php echo $i++ .'.) ' ?><?php echo $v->question; ?></label></br>
					<?php
					if(isset($v->choices)){
						$array = explode(',', $v->choices);

						foreach($array as $k => $v) { 
						?>	
							<input type="radio" id="<?php echo $v ?>" name="fav_language" value="<?php echo $v ?>">
							<label for="<?php echo $v ?>"><?php echo $v ?></label><br>
						<?php 
						}

					}else{
						?>	
							<label for=""></label></br>
						<?php 
					}
						
					?>
				<?php }
				}else{ ?>
					<label for="">No Questions</label></br>
				<?php } ?>
			</div>
		</div>
    </div>
    <div class="text-right" style="width:100%;">
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>

