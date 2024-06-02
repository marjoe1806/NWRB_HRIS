<div class="row clearfix" id="userLevelForm">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-blue">
				<h2>
					Salary Grades <small>Manage Salary Grades</small>
				</h2>
			</div>
			<div class="body">
				<div class="row clearfix">
					<div class="col-md-4" style="display: none;">
		                <h5 class="text-info">Pay Basis <span class="text-danger">*</span></h5>
		                <div class="form-group">
		                	<div class="form-line pay_basis_select">
		                        <select class="pay_basis form-control" name="pay_basis" id="pay_basis" data-live-search="true">
		                            <option value=""></option>
		                        </select>
		                    </div>
		            	</div>
		            </div>
		            <div class="col-md-2">
		                <h5 class="text-info">Effectivity <span class="text-danger">*</span></h5>
		                <div class="form-group">
		                	<div class="form-line">
		                		<input type="text" name="effectivity" value="<?php echo date('Y'); ?>" id="effectivity" class="form-control" required readonly>
		                	</div>
		            	</div>
		            </div>
				</div>
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active">
						<a href="#home_with_icon_title" data-toggle="tab" aria-expanded="true">
							<i class="material-icons">pageview</i> Overview
						</a>
					</li>
					<li role="presentation" class="">
						<a href="#profile_with_icon_title" data-toggle="tab" aria-expanded="false">
							<i class="material-icons">settings_applications</i> Settings
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade active in" id="home_with_icon_title">
						<div id="table-holder-semimonthly">
							<?php echo $table; ?>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="profile_with_icon_title">
						<div id="table-holder-monthly">
							<?php echo $form; ?>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- <div class="row clearfix" id="userLevelForm">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-yellow">
				<h2>
					Salary Grades <small>Manage Salary Grades</small>
				</h2>
			</div>
			<div class="body">
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade active in" id="home_with_icon_title">
						<div style="width:100%;padding-bottom:20px;">
							<a id="addSalaryGradesForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addSalaryGradesForm'; ?>">
								<button type="button" class="btn btn-info btn-lg waves-effect">
									<i class="material-icons">save</i>
									<span> Add Record</span>
								</button>
							</a>
						</div>
						<div id="table-holder-monthly">
							<?php echo $table_monthly; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/fieldmanagement/salarygrades.js"></script>
