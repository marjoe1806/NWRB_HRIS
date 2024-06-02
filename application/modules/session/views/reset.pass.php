<div class="fp-box">
        <div class="logo">
			<a href="<?php echo base_url(); ?>">
				<img class="p-b-20" src="<?php echo base_url(); ?>assets/custom/images/mmda.png" width=50 alt="" />
				<span class="font-50"><b>HRIS</b></span>
			</a>
			<small>MMDA - Human Resource Management System</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="forgot_password" action="<?php echo base_url(); ?>session/ForgotPassReset/change?lid=<?php echo $_GET['lid']; ?>" method="POST">
                    <div class="msg">
                        Enter your new password
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input id="password" type="password" class="form-control" name="password" placeholder="New Password" required autofocus />
                        </div>
                    </div>
					<div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input id="password2" type="password" class="form-control" name="password2" placeholder="Verify New Password" required autofocus />
                        </div>
                    </div>

                    <button class="btn btn-block btn-lg bg-yellow waves-effect" type="submit">CHANGE PASSWORD</button>
					
                    <!-- <div class="row m-t-20 m-b--5 align-center">
                        <a href="<?php echo base_url(); ?>session">Sign In!</a>
                    </div> -->
                </form>
            </div>
        </div>
    </div>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/js/pages/examples/forgot-password.js"></script>