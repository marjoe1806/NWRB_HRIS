<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
	<div class="body">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs tab-nav-right" role="tablist">
			<li role="presentation" class="active"><a href="#enumeration" data-toggle="tab" aria-expanded="true">ENUMERATION</a></li>
			<li role="presentation" class=""><a href="#fill" data-toggle="tab" aria-expanded="false">FILL IN THE BLANKS</a></li>
			<li role="presentation"><a href="#essay" data-toggle="tab">ESSAY</a></li>
			<li role="presentation"><a href="#multiple" data-toggle="tab">MULTIPLE CHOICE</a></li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<input type="hidden" class="id" name="id" value="">
			<input type="hidden" class="type" name="type" value="">
			<!-- ENUMERATION START -->
			<div role="tabpanel" class="tab-pane fade active in" id="enumeration">
				<hr>
				<div class="form-elements-container-enumeration">
					<div class="row clearfix div_enumeration" style="border: 0px solid gray;padding:5px;">
						<input type="hidden" name="order_no[enumeration][0]" class="order_no" value="1">
						<div class="col-md-2">
							<label class="form-label">Sequence No. <span class="text-danger">*</span></label>
							<div class="form-group">
								<div class="form-line">
									<input type="text" name="sequence[enumeration][0]" id="sequence[enumeration][0]" class="sequence form-control" value="1" required>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<label class="form-label">Points <span class="text-danger">*</span></label>
							<div class="form-group">
								<div class="form-line">
									<input type="number" name="points[enumeration][0]" id="points[enumeration][0]" class="points form-control" min="1" value="1" required>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label class="form-label">Question <span class="text-danger">*</span></label>
							<div class="form-group">
								<div class="form-line">
									<textarea name="question[enumeration][0]" rows="1" id="question[enumeration][0]" class="required question form-control"></textarea>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<label class="form-label">Possible Answer <span class="text-danger">*</span></label>
							<div class="form-group">
								<div class="form-line">
									<input type="text" name="answer[enumeration][0]" id="answer[enumeration][0]" class="answer form-control" required>
								</div>
							</div>
						</div>
						<div>
							<button type="button" class="btn btn-primary btn-sm btnAddQuestion" data-type="div_enumeration" style="float: right;margin: 20px;"><i class="material-icons">add</i> Add Question</button>
						</div>
					</div>
				</div>
			</div>
			<!-- ENUMERATION END -->

			<!-- FILL IN THE BLANK START -->
			<div role="tabpanel" class="tab-pane fade" id="fill">
				<hr>
				<div class="form-elements-container-fill">
					<div class="row clearfix div_fill" style="border: 0px solid gray;padding:5px;">
						<input type="hidden" name="order_no[fill][0]" class="order_no" value="1">
						<div class="col-md-2">
							<label class="form-label">Sequence No. <span class="text-danger">*</span></label>
							<div class="form-group">
								<div class="form-line">
									<input type="text" name="sequence[fill][0]" id="sequence[fill][0]" class="sequence form-control" value="1" required>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<label class="form-label">Points <span class="text-danger">*</span></label>
							<div class="form-group">
								<div class="form-line">
									<input type="number" name="points[fill][0]" id="points[fill][0]" class="points form-control" min="1" value="1" required>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label class="form-label">Question <span class="text-danger">*</span></label>
							<div class="form-group">
								<div class="form-line">
									<textarea name="question[fill][0]" rows="1" id="question[fill][0]" class="required question form-control"></textarea>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<label class="form-label">Possible Answer <span class="text-danger">*</span></label>
							<div class="form-group">
								<div class="form-line">
									<input type="text" name="answer[fill][0]" id="answer[fill][0]" class="answer form-control" required>
								</div>
							</div>
						</div>
						<div>
							<button type="button" class="btn btn-primary btn-sm btnAddQuestion" data-type="div_fill" style="float: right;margin: 20px;"><i class="material-icons">add</i> Add Question</button>
						</div>
					</div>
				</div>				
			</div>
			<!-- FILL IN THE BLANK END -->
			
			<!-- ESSAY START -->
			<div role="tabpanel" class="tab-pane fade" id="essay">
				<hr>
				<div class="form-elements-container-essay">
					<div class="row clearfix div_essay" style="border: 0px solid gray;padding:5px;">
						<input type="hidden" name="order_no[essay][0]" class="order_no" value="1">
						<div class="col-md-2">
							<label class="form-label">Sequence No. <span class="text-danger">*</span></label>
							<div class="form-group">
								<div class="form-line">
									<input type="text" name="sequence[essay][0]" id="sequence[essay][0]" class="sequence form-control" value="1" required>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<label class="form-label">Points <span class="text-danger">*</span></label>
							<div class="form-group">
								<div class="form-line">
									<input type="number" name="points[essay][0]" id="points[essay][0]" class="points form-control" min="1" value="1" required>
								</div>
							</div>
						</div>
						<div class="col-md-8">
							<label class="form-label">Question <span class="text-danger">*</span></label>
							<div class="form-group">
								<div class="form-line">
									<textarea name="question[essay][0]" rows="5" id="question[essay][0]" class="required question form-control"></textarea>
								</div>
							</div>
						</div>
						<div>
							<button type="button" class="btn btn-primary btn-sm btnAddQuestion" data-type="div_essay" style="float: right;margin: 20px;"><i class="material-icons">add</i> Add Question</button>
						</div>
					</div>
				</div>				
			</div>
			<!-- ESSAY END -->

			<!-- MULTIPLE CHOICE START -->
			<div role="tabpanel" class="tab-pane fade" id="multiple">
				<hr>
				<div class="form-elements-container-multiple">
					<div class="row clearfix div_multiple" style="border: 0px solid gray;padding:5px;">
						<input type="hidden" name="order_no[multiple][0]" class="order_no" value="1">
						<div class="col-md-2">
							<label class="form-label">Sequence No. <span class="text-danger">*</span></label>
							<div class="form-group">
								<div class="form-line">
									<input type="text" name="sequence[multiple][0]" id="sequence[multiple][0]" class="sequence form-control" value="1" required readonly>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label class="form-label">Question <span class="text-danger">*</span></label>
							<div class="form-group">
								<div class="form-line">
									<textarea name="question[multiple][0]" rows="1" id="question[multiple][0]" class="required question form-control"></textarea>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<label class="form-label">Possible Answer <span class="text-danger">*</span></label>
							<div class="form-group">
								<div class="form-line">
									<input type="text" name="answer[multiple][0]" id="answer[multiple][0]" class="answer form-control" required>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<label class="form-label lbl_choices">Choices <span class="text-danger">*</span></label>
							<button type="button" class="btn btn-primary btn-xs btnAddChoices" style="margin-left: 20px;"><i class="material-icons">add</i></button>
						</div>
						<div class="col-md-10 container_choices"></div>
						<div>
							<button type="button" class="btn btn-primary btn-sm btnAddQuestion" data-type="div_multiple" style="float: right;margin: 20px;"><i class="material-icons">add</i> Add Question</button>
						</div>
					</div>
				</div>
			</div>
			<!-- MULITPLE CHOICE END -->
		</div>
	</div>
    <div class="row clearfix mt-10">
		<div class="text-right" style="width:100%;">
			<button id="saveUserLevelConfig" class="btn btn-info btn-sm waves-effect" type="submit">
				<i class="material-icons">save</i><span> Save</span>
			</button>
			<button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
				<i class="material-icons">close</i><span> Close</span>
			</button>
		</div>
	</div>
</form>

