
<div class="row">
    <div class="col-xs-10 col-sm-8 col-md-6 col-xs-offset-1 col-sm-offset-2 col-md-offset-3">
    <form method="POST" action="/admin/default/reset">
    <input type="hidden" name="key" value="<?php echo $key?>"/>
        <fieldset class="well form-horizontal">
            <h1>Reset Password</h1>
           
<?php if(isset($error) && $error !== false){?>
<div class="alert alert-danger"><?php echo $error?></div>
<?php }?>

<?php if(isset($message)){?>
<div class="alert alert-success"><?php echo $message?></div>
<?php }?>
            <div class="form-group">
                <label class="control-label">New Password</label>
                <input required type="password" id="password" class="form-control" name="password" placeholder="New Password"/>
            </div>

            <div class="form-group">
                <label class="control-label">Confirm Password</label>
                <input required type="password" id="password" class="form-control" name="confirmPassword" placeholder="Confirm Password"/>
            </div>

            <div class="form-group text-center">
                <input type="submit" id="gologin" class="btn btn-default btn-login" value="Submit"/>
            </div>
        </fieldset>
    </form>
    </div>
</div>

<style>

</style>

