<div class="fp-box">
        <br><br><br>
        <div class="logo">
            <!-- <small>NWRB - Human Resource Information System</small> -->
        </div>
		<?php if(isset($serverCode)): ?>
		<div class="alert <?php echo $serverCode == 0 ? 'alert-info' : 'alert-danger';?>">
			<a href="#" aria-hidden="true" data-dismiss="alert" class="close" style=""><i class="font-15 material-icons">close</i></a>
			<strong><?php echo $serverCode == 0 ? 'Great!' : 'Oh snap!'; ?></strong> <?php echo $serverMessage; ?>
		</div>
		<?php endif; ?>
        <div class="card">
            <div class="body">
                <form id="forgot_password" action="<?php echo base_url(); ?>session/ForgotPassword/sendemail" method="POST">
                    <div class="msg">
                        Enter your username that you used for your account registration. We'll send you an email with your username and temporary password.
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control" name="username" placeholder="username" style="text-transform: uppercase;" required autofocus />
                        </div>
                    </div>
                    <!-- <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control" name="email" placeholder="Email" required autofocus />
                        </div>
                    </div> -->

                    <button class="btn btn-block btn-lg bg-red waves-effect" type="submit">RESET MY PASSWORD</button>
					
                    <div class="row m-t-20 m-b--5 align-center">
                        <a href="<?php echo base_url(); ?>session">Sign In!</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/js/pages/examples/forgot-password.js"></script>