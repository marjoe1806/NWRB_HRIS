
    <!-- Advanced Form Example With Validation -->
    <style type="text/css">
    	.form-group{
    		margin-bottom: 0px;
    	}
        @media (min-width: 992px){
            .modal-lg {
                width: 1200px;
            }
        }
        @media (min-width: 768px){
            .modal-dialog {
                width: 1200px;
                margin: 30px auto;
            }
        }
        table tr td,table tr th{ 
            text-align: center;
        }
        .headcol {
            position: absolute;
            left: 1;
            background: #fff;
            width: 130px;
            border: hidden !important;
            height: auto;
        }
    </style>
                        <form id="<?php echo $key; ?>" enctype="multipart/form-data" accept-charset="utf-8">
                                <input type="hidden" class="id" name="id" id="id">
                                <!-- <div class="row">
                                    <div class="col-md-5 col-xs-12 col-sm-12">
                                        <label class="form-label">File Title</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control inputRequired" name="file_title" id="file_title" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-xs-12 col-sm-12">
                                        <label class="form-label">File Upload</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="file" class="form-control inputRequired" name="uploaded_file" id="uploaded_file" >
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="row">
                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                            <h5 class="emp_name"></h5>
                                            <br>
                                            <h4>Attachments</h4>
                                            <table class="table table-bordered" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>File Title</th>
                                                        <th>Current File</th>
                                                        <th>New File</th>
                                                        <th>Action <button type="button" id="btnViewAddFile" class="btn btn-info btn-sm pull-right"><i class="material-icons">add</i> Add File</button></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbFiles">
                                                    <!-- <tr>
													    <td><div class="form-group"><div class="form-line">
																<input type="hidden" name="cur_file_name[]">
																<input type="hidden" name="index_id[]" value="">
																<input type="hidden" name="cur_file[]" value="">' +
																<textarea rows="1" style="overflow: hidden; overflow-wrap: break-word;" class="form-control no-resize auto-growth inputRequired" name="file_title[]" id="file_title[]" ></textarea>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><a href="../../uploads/employees/" target="_blank" >View Uploaded File</a></td>
														<td><div class="form-group"><div class="form-line"><input type="file" class="form-control inputRequired" name="new_file[]" id="new_file[]" ></div></div></td>
                                                        <td><button type="button" class="btn btn-warning btn-sm update_file_row" style="float: right"><i class="material-icons">mode_edit</i></button>&nbsp;<button type="button" class="btn btn-danger btn-sm delete_file_row" style="float: right"><i class="material-icons">remove</i></button>
                                                        </td>
                                                    </tr> -->
                                                </tbody>
                                            </table>
                                        </div>
                                    <div class="col-md-12 col-xs-12 col-sm-12">
                                    <button type="submit" class="btn btn-info btn-sm pull-right">Submit</button>
                                    </div>
                                </div>
                        </form>