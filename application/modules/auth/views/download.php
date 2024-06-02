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
                <form id="form_authorize" action="<?php echo base_url(); ?>auth/dlink?q=<?php echo isset($q) ? $q : ""; ?>" method="POST">
                    <div class="msg">
                        Enter passcode to access file
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input id="passcode" type="password" class="form-control" name="Passcode" placeholder="Enter passcode" required autofocus />
                        </div>
                    </div>
                    <button class="btn btn-block btn-lg bg-green waves-effect" type="submit">DOWNLOAD NOW</button>
                </form>
            </div>
        </div>
    </div>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/auth/auth.js"></script>