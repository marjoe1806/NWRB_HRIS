<div class="row clearfix" id="userLevelForm">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-blue">
				<h2>
					Offsetting
					<small>Manage Offsettings</small>
				</h2>
			</div>
			<div class="body">
                <div class="row">
                       <div class="col-md-3">
                           <label class="form-label">Status</label>
                           <div class="form-group">
                               <div class="form-line division_select">
                                   <select class="status form-control " name="status" id="status" data-live-search="true" >
                                       <option value=""></option>
                                       <option value="1">FOR CERTIFICATION</option>
                                       <option value="2">FOR RECOMMENDATION</option>
                                       <option value="3">FOR APPROVAL</option>
                                       <option value="4">APPROVED</option>
                                       <option value="5">DISAPPROVED</option>
                                       <option value="6">REJECTED</option>

                                   </select>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-3">
                           <a id="btnsearch" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>" data-toggle="tooltip" data-placement="top" title="Search">
                               <button type="button" class="btn btn-primary btn-circle-lg waves-effect waves-circle waves-float">
                                   <i class="material-icons">search</i>
                               </button>
                           </a>
                       </div>
						<?php if(Helper::role(ModuleRels::OFFSETTING_ADD_RECORDS)): ?>
					   <div class="col-md-6">
							<a id="addOffsettingForm" class="btn btn-info btn-lg waves-effect pull-right" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addOffsettingForm'; ?>">
									<i class="material-icons">add</i>
									<span>Add New</span>
							</a>
					   </div>
						<?php endif; ?>
                </div>
				<div id="table-holder">
					<?php echo $table; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/timekeeping/offset.js"></script>