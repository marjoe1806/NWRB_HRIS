<div class="login-box">
    <div class="logo">
        <!-- <div class="bottom-right" style="position: absolute; bottom: 14px; right: 0px;">
            <span style="font-size: 75%;"><strong>Human Resource Management Information System</strong></span>
        </div> -->
        <br><br><br>
		<!-- <small>NWRB - Human Resource Information System</small> -->
    </div>
	<?php if(isset($serverMessage)): ?>
	<div class="alert <?php echo $serverCode == 0 ? 'alert-info' : 'alert-danger';?>">
		<a href="#" aria-hidden="true" data-dismiss="alert" class="close" style=""><i class="font-15 material-icons">close</i></a>
		<strong><?php echo $serverCode == 0 ? 'Great!' : 'Oh snap!'; ?></strong> <?php echo $serverMessage; ?>
	</div>
	<?php endif; ?>
    <div class="card">
        <a href="<?php echo base_url(); ?>">
            <img class="" style="height: 100%; width: 100%;" src="<?php echo base_url(); ?>assets/custom/images/Header.png" alt="" />
        </a>
        <div class="body">
            <form id="sign_in" action="<?php echo base_url(); ?>session/validate" method="POST">
                <div class="msg">Sign on to your account</div>
                <div class="username_content">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" style="text-transform: uppercase;" required autofocus>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 pull-right">
                            <button id="verify_username" class="btn btn-block bg-blue waves-effect" type="button">Verify</button>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <br><br><br>
                    </div>
                </div>
                <div class="password_content" style="display:none;">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 col-sm-8 col-md-8">
                            <div class="form-group">
                                <input type="checkbox"id="showPassword" class="chk"> Show Password
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 col-sm-8 col-md-8">
    						<a href="<?php echo base_url(); ?>session/ForgotPassword">Forgot Password?</a>
                        </div>
                        <div class="col-xs-4 col-sm-4  col-md-4 pull-right">
                            <button  type="submit" id="btn_sign_in" class="btn btn-block bg-blue waves-effect" style="white-space: normal;">SIGN ON</button>
                        </div>
                        <div class="col-sm-12 col-xs-12 col-md-12">
                            <a id="back_login" href="#">Use another existing account.</a>
                        </div>
                    </div>
    				<input type="hidden" name="ip" id="ip" />
                </div>
            </form>
            
        </div>
    </div>
	<center style="margin-top:-20px;">
		<small><label style="color:#ffffff;">HRIS SYSTEM &copy; 2021</label> <a style="color:#ffffff;" href="http://www.mobilemoney.ph" target="_blank">Telcom Live Content, Inc.</a></small>
	</center>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/session/session.js"></script>