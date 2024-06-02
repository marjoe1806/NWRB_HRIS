<style type="text/css">

    /* CHANGE COLOR HERE */ 
    ol.etapier li.done {
      border-color: yellowgreen ;
    }
    /* CHANGE COLOR HERE */ 
    ol.etapier li.done:before {
        background-color: yellowgreen;
        border-color: yellowgreen;
    }
    ol.etapier {
        display: table;
        list-style-type: none;
        margin: 0 auto 20px auto;
        padding: 0;
        table-layout: fixed;
        width: 100%;
    }
    ol.etapier a {
        display: table-cell;
        text-align: center;
        white-space: nowrap;
        position: relative;
    }
    ol.etapier a li {
        display: block;
        text-align: center;
        white-space: nowrap;
        position: relative;
    }
    ol.etapier li {
        display: table-cell;
        text-align: center;
        padding-bottom: 10px;
        white-space: nowrap;
        position: relative;
    }

    ol.etapier li a {
        color: inherit;
    }

    ol.etapier li {
        color: silver; 
        border-bottom: 4px solid silver;
    }
    ol.etapier li.done {
        color: black;
    }

    ol.etapier li:before {
        position: absolute;
        bottom: -11px;
        left: 50%;
        margin-left: -7.5px;

        color: white;
        height: 15px;
        width: 15px;
        line-height: 15px;
        border: 2px solid silver;
        border-radius: 15px;
        
    }
    ol.etapier li.done:before {
        content: "\2713";
        color: white;
    }
    ol.etapier li.todo:before {
        content: " " ;
        background-color: white;
    }

</style>

<div class="row clearfix" id="userLevelForm">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="card">
         <div class="header bg-blue">
            <h2>
               DPCR REVIEW
            </h2>
         </div>
         <div class="body">
         <div class="row">
            <div class="col-md-4">
                <label class="form-label">SERVICE / DIVISION / UNIT <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line division_select">
                        <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" required>
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
	           <div class="col-md-4">
					<label class="form-label">EMPLOYEE NAME
						<span class="text-danger">*</span>
					</label>
					<div class="form-group">
						<div class="form-line employee_select">
							<select class="employee_id form-control" name="employee_id" id="employee_id" data-live-search="true" required>
								<option disabled selected></option>
							</select>
						</div>
					</div>
				</div>
            <div class="col-md-3">
                <a id="getDpcrReview" href="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Search">
                    <button type="button" class="btn btn-primary btn-circle-lg waves-effect waves-circle waves-float">
                        <i class="material-icons">search</i>
                    </button>
                </a>
            </div>
         </div>
         <div class="fresh-datatables">
                    <table id="dpcr_reviews" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Period Of</th>
                                <th>Date Posted</th>
                                <th class="disabled-sorting text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript" src = "<?php echo base_url(); ?>assets/modules/js/performancemanagement/dpcrreviews.js"></script>