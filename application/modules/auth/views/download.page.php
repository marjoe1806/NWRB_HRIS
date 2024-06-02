<?php 
    function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'KB', 'MB', 'GB', 'TB');   

        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }
    //var_dump($data);die();
?>
<!-- Modal -->
<div class="modal fade" id="attachmentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-blue">
            	<button type="button" class="close" data-dismiss="modal"><i class="material-icons">close</i></button>
                <h4 class="modal-title" id="defaultModalLabel">Attachment Lists </h4>
            </div>
            <div class="modal-body">
            	<div class="table-responsive" id="attachmentsContainer">
				    <table id="attachmentsTable" class="table table-hoverun" style="width:100%;">
				        <thead>
				            <tr>
				                <th>File Title</th>
				                <th>Description</th>
				                <th>Uploaded Document</th>
				            </tr>
				        </thead>
				        <tbody>
				             <?php 
				            if(isset($attachments) && sizeof($attachments) > 0){
				                foreach($attachments as $k=>$v){
				            ?>
				            <tr>
				               
				                <td><?php echo $v->filetitle ?></td>
				                <td><?php echo $v->description ?></td>
				                <td>
				                    <?php 
				                        $path = strstr($v->filepath, 'assets');
				                        //var_dump($path);die();
				                    ?>
				                    <a href="<?php echo base_url().$path; ?>" class="btn btn-success waves-effect" style="width:100%" download>Download (<?php echo formatBytes($v->filesize); ?>)</a>
				                </td>
				                
				            </tr>
				            <?php }
				            } ?>
				        </tbody>
				    </table>
				</div>
            </div>
        </div>
    </div>
</div>
<!-- ./Modal -->
<div class="fp-box">
    <div class="logo">
		<a href="<?php echo base_url(); ?>">
			<img class="p-b-20" src="<?php echo base_url(); ?>assets/custom/images/singlelgalogo.png" width=50 alt="" />
			<span class="font-50"><b>EDRMS</b></span>
		</a>
		<small>LGA - Electronic Documents and Records Management System Authorization Portal</small>
    </div>
    <div class="card">
        <div class="body">
			<a class="btn btn-block btn-lg bg-green waves-effect" href="<?php echo base_url() . $fp; ?>" download>DOWNLOAD NOW</a>
			<br><br>
			<a class="viewAttachments btn btn-block btn-lg bg-deep-purple waves-effect" data-id="<?php echo $_GET['q']; ?>" data-toggle="modal" data-target="#attachmentModal">VIEW ATTACHMENTS</a>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/auth/auth.js"></script>