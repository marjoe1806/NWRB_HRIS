<div class="table-responsive listTable" style="overflow-y: hidden;overflow-x: hidden;">
    <div class="row">
        <div class="col-md-5">
            <div>
                <h5 class="text-info">Location <span class="text-danger">*</span></h5>
                <div class="form-group">
                    <div class="form-line">
                        <select class="location_id form-control" name="location_id" id="location_id" data-live-search="true">
                            <option value="" selected></option>
                            <?php foreach ($locations as $k => $v) : ?>
                                <option value="<?php echo $v['id']?>"><?php echo $v['name']?></option>
                            <?php endforeach; ?>
                            
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-5">
            <div>
                <h5 class="text-info">Department <span class="text-danger">*</span></h5>
                <div class="form-group">
                    <div class="form-line">
                        <select class="department_id form-control" name="department_id" id="department_id" data-live-search="true">
                            <option value="" selected></option>
                            <?php foreach ($departments as $k => $v) : ?>
                                <option value="<?php echo $v['id']?>"><?php echo $v['department_name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div>
                <h5 class="text-info">Status <span class="text-danger">*</span></h5>
                <div class="form-group">
                    <div class="form-line">
                        <select class="status form-control" name="status" id="status">
                            <option value="ACTIVE" selected>ACTIVE</option>
                            <option value="INACTIVE">INACTIVE</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <a id="viewFilteredMobileUsers" href="#">
                <button type="button" class="btn btn-block btn-lg btn-info waves-effect">
                    <i class="material-icons">people</i> Load Filtered Mobile Users
                </button>
            </a>
        </div>
    </div>


        
    
    <table id="datatables" class="table table-hover table-striped">
        <thead> 
            <tr  >
                <th>Action</th>            
                <th style="width:15%">Employee No.</th>
                <th>Name</th>
                <th>Location</th>
                <th style="width:12%">Password</th>
                <th style="width:10%">Status</th>                            
          </tr>
        </thead>
    </table>
</div>