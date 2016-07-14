<form id='change-password'>
<input type='hidden' name='id' value='<?php echo $id?>' />
<div class='row'>
    <div class='col-xs-12 form-group'>
        <label>* Minimum of 6 characters</label>
    </div>
    <div class='col-xs-12 form-group'>
        <label>New Password</label>
        <input type='password' class='form-control' name='password'  value=''/>
    </div>
    <div class='col-xs-12 form-group'>
        <label>Confirm Password</label>
        <input type='password' class='form-control' name='confirmPassword'  value=''/>
    </div>
    <div class='col-xs-12 form-group text-center'>
       <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
       <button type='button' class='btn btn-success save-new-password'>Submit</button>
    </div>
</div>
</form>