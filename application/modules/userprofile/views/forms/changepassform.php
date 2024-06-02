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

<form id="frmChangePass" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2) ?>/changePass" method="POST" role="form" data-parsley-validate>
    <div class="col-md-6">
        <div class="msg"><b>Password must contain the following:</b></div>
        <div id="message">
            <!-- <h6>Password must contain the following:</h6> -->
            <p id="length" class="invalid">at least 8 characters or longer</p>
            <p id="letter" class="invalid">at least 1 lowercase character</p>
            <p id="capital" class="invalid">at least 1 uppercase character</p>
            <p id="number" class="invalid">at least 1 numeric character</p>
        </div>
    </div>
    <fieldset>
        <div class="msg"><b>Enter your new password</b></div><br>
        <p class="text-danger" style="font-size: 12px;"><b>* All fields are required.</b></p>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="material-icons">lock</i>
                <b class="text-danger"> *</b>
            </span>
            <div class="form-line">
                <input id="password" type="password" class="required form-control" name="oldpass" placeholder="Old Password" required />
            </div>
        </div>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="material-icons">lock</i>
                <b class="text-danger"> *</b>
            </span>
            <div class="form-line">
                <input id="password1" type="password" class="required form-control" name="newpass" placeholder="New Password" required />
            </div>
        </div>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="material-icons">lock</i>
                <b class="text-danger"> *</b>
            </span>
            <div class="form-line">
                <input id="password2" type="password" class="required form-control" name="newpass2" placeholder="Verify New Password" required />
            </div>
        </div>
        <div class="form-group">
            <input type="checkbox" id="showPassword" class="chk"> Show Password
        </div>
        <div class="pull-right">
            <!-- <img src="<?php echo base_url(); ?>assets/img/ajax-loader-trans.gif" alt="" class="pull-right loader" style="display:none;" /> -->
            <button type="submit" class="btn btn-primary btn-fill" id="btnUpdate">Change</button>
            <button type="button" data-dismiss="modal" class="btn btn-default btn-fill" id="bCancel">Cancel</button>
        </div>
    </fieldset>
</form>