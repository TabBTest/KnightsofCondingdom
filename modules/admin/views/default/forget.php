
<div class="row">
    <div class="col-xs-10 col-sm-8 col-md-6 col-xs-offset-1 col-sm-offset-2 col-md-offset-3">
    <form method="POST" action="/admin/default/forget">
        <fieldset class="well form-horizontal">
            <h1 class="text-center">Recover Password</h1>
           
            <?php if(isset($error)){?>
            <div class="alert alert-danger"><?php echo $error?></div>
            <?php }?>
            <?php if(isset($message)){?>
            <div class="alert alert-success"><?php echo $message?></div>
            <?php }?>

            <div class="form-group">
                <label class="col-xs-4 text-right">Username / Email</label>
                <div class="col-xs-8 col-md-6">
                    <input required type="text" id="username" name="username" class="form-control" placeholder="Username / Email"/>
                </div>
            </div>

            <div class="form-group text-center">
                <input type="submit" id="gologin" class="btn btn-success btn-login" value="Submit"/>
            </div>

            <div class="form-group text-center">
                <a href="/admin/default/login">Back to Login</a>
            </div>

        </fieldset>
    </form>
    </div>
</div>

<style>
    label{line-height: 34px; font-weight: normal;}
    h1{ padding-bottom: 25px;}
</style>

