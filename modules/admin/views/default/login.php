
<div class="row">
    <div class="col-xs-10 col-sm-8 col-md-6 col-xs-offset-1 col-sm-offset-2 col-md-offset-3">
    <form method="POST" action="/admin/default/login">
        <fieldset class="well form-horizontal">
            <h1 class="text-center">Log In</h1>
           
<?php if(isset($error)){?>
<div class="alert alert-danger"><?php echo $error?></div>
<?php }?>
<?php if(isset($inactive) && $inactive ){?>
<div class="alert alert-danger"><?php echo $error2?>
</div>
<?php }?>
<?php if(isset($message)){?>
<div class="alert alert-success"><?php echo $message?></div>
<?php }?>


            <div class="form-group">
                <label class="col-xs-4 text-right">Username</label>
                <div class="col-xs-8 col-md-6">
                    <input required type="text" id="username" name="email" class="form-control" placeholder="Username"/>
                </div>
            </div>

            <div class="form-group">
                <label  class="col-xs-4 text-right">Password</label>
                <div class="col-xs-8 col-md-6">
                    <input required type="password" id="password" class="form-control" name="password" placeholder="Password"/>
                </div>
            </div>

            <div class="form-group text-center">
                <input type="submit" id="gologin" class="btn btn-success btn-login" value="Login"/>
            </div>
            <div class="form-group text-center" style="display: none">
                <a href="/admin/default/forget">Forget Password</a>
            </div>

        </fieldset>
    </form>
    </div>
</div>

<style>
    label{line-height: 34px; font-weight: normal;}
    h1{ padding-bottom: 25px;}
</style>
