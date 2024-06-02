<style>
/* The message box is shown when the user clicks on the password field */
#message {
  /* background: #f1f1f1; */
  color: #000;
  position: relative;
  padding: 20px 10px;
  /* margin-top: 10px; */
}

#message p {
  padding: 10px 35px;
  font-size: 14px;
}

/* Add a green text color and a checkmark when the requirements are right */
.valid {
  color: green;
}

.valid:before {
  position: relative;
  left: -20px;
  content: "✔";
}

/* Add a red text color and an "x" icon when the requirements are wrong */
.invalid {
  color: red;
}

.invalid:before {
  position: relative;
  left: -20px;
  content: "✕";
}
</style>
<div class="header">
    <div class="logo">
        <!-- <small>NWRB - Human Resource Information System</small> -->
    </div>
    <?php if(isset($serverMessage)): ?>
    <div class="alert <?php echo $serverCode == 0 ? 'alert-info' : 'alert-danger';?>">
        <a href="#" aria-hidden="true" data-dismiss="alert" class="close" style=""><i class="font-15 material-icons">close</i></a>
        <strong><?php echo $serverCode == 0 ? 'Great!' : 'Oh snap!'; ?></strong> <?php echo $serverMessage; ?>
    </div>
    <?php endif; ?>
    <div class="card" style="margin: 0 -50%;">
        <div class="body col-md-6">
            <div id="message">
                <h6>Password must contain the following:</h6>
                <p id="length" class="invalid">at least 8 characters or longer</p>
                <p id="letter" class="invalid">at least 1 lowercase character</p>
                <p id="capital" class="invalid">at least 1 uppercase character</p>
                <p id="number" class="invalid">at least 1 numeric character</p>
            </div>
        </div>
        <div class="body">
            <form id="frmChangePass" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2) ?>/cpass" method="POST" role="form" data-parsley-validate>
                <fieldset>
                    <div class="msg">
                        <h4 class="text-primary">Enter your new strong password.</h4>
                    </div><br>
                    <p class="text-danger" style="font-size: 12px;"><b>* All fields are required.</b></p>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                            <b class="text-danger"> *</b>
                        </span>
                        <div class="form-line">
                            <input id="password" type="password" class="required form-control" name="oldpass" placeholder="Old Password" required autofocus />
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                            <b class="text-danger"> *</b>
                        </span>
                        <div class="form-line">
                            <input id="password1" type="password" class="required form-control" name="newpass" placeholder="New Password" required autofocus />
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                            <b class="text-danger"> *</b>
                        </span>
                        <div class="form-line">
                            <input id="password2" type="password" class="required form-control" name="newpass2" placeholder="Verify New Password" required autofocus />
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="checkbox"id="showPassword" class="chk"> Show Password
                    </div>
                    <div class="pull-right">
                        <!-- <img src="<?php echo base_url(); ?>assets/img/ajax-loader-trans.gif" alt="" class="pull-right loader" style="display:none;" /> -->
                        <button type="submit" class="btn btn-primary btn-fill" id="btnUpdate">Submit</button>
                        <a role="button" data-dismiss="modal" class="btn btn-default btn-fill" href="<?php echo base_url().'session/logout'; ?>">Cancel</a>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    </div>
    <center>
        <small style="color:#281f5a;">HRIS &copy; 2021 <a style="color:#281f5a;" href="http://www.mobilemoney.ph" target="_blank">Telcom Live Content, Inc.</a></small>
    </center>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/session/changepass.js"></script>