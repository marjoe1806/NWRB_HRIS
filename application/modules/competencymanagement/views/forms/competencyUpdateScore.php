<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" name="access_id" class="id" value="<?php echo $list->Data->access_id ?>">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<h3 class="loan_title text-primary">Competency Test Exam Result</h3>
		<hr>
		<div class="table-responsive sub_loan_table">
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
					<hr>
			<div>

				<h3>Essay</h3>
				<?php
				if(isset($list->Data->essay) && sizeof($list->Data->essay) > 0){ 
					$i = 1;
					foreach ($list->Data->essay as $k => $v) { ?>
					<label for=""><?php echo $i .'.) ' ?><?php echo $v->question; ?></label></br>
					<label for="">Answer: <?php echo $v->examinee_answer; ?></label></br>
					<label for="">Score: <input type="text" name=<?php echo 'examinee_score'. $i ?> id=<?php echo $v->examinee_answer_id; ?> value="<?php echo $v->score; ?>"></label></br>
					<label for=""><input type="hidden" name=<?php echo 'examinee_id'. $i++ ?> id=<?php echo $v->examinee_answer_id; ?> value="<?php echo $v->examinee_answer_id?>"></label></br>
				<?php }
				}else{ ?>
					<label for="">No Questions</label></br>
				<?php } ?>

				<input type="hidden" name='total_essay' id=total_essay value="<?php echo $i-1 ?>"></label></br>
			</div>

		</div>
    </div>
    <div class="text-right" style="width:100%;">
		<!-- <button id="sumitUpdateScore" class="btn btn-primary btn-sm waves-effect" type="submit">
            <i class="material-icons">save</i><span> Update</span>
        </button> -->
		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update</span>
	        </button>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>	

