<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use app\models\User;
use app\helpers\TenantHelper;
use app\helpers\UtilityHelper;

AppAsset::register($this);
$model = false ;
if(\Yii::$app->user->identity != null)
    $model = User::findOne(\Yii::$app->user->id);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?php 
 if(\Yii::$app->user->identity != null && \Yii::$app->user->identity->role == User::ROLE_ADMIN){
        //
        \Yii::$app->user->logout();
?>
<script>
window.location.href = '/';
</script>
<?php 
    }
?>
<div class="wrap">
    
 <nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <?php if (TenantHelper::isDefaultTenant()) { ?>
            <a href="/" class="navbar-brand"><img alt="" class="img-responsive img-rounded" src="/images/logo.png"></a>
        <?php } else { ?>
            <a href="/" class="navbar-brand"><img alt="" class="img-responsive img-rounded" style="height: 100%;" src="/images/users/<?= TenantHelper::getVendorImageFromUrl() ?>" /></a>
            <span class="navbar-brand navbar-brand-text"><?= TenantHelper::getVendorNameFromUrl() ?></span>
        <?php } ?>
    </div>
    <div class="navbar-collapse navbar-responsive-collapse collapse">
    <ul class="nav navbar-nav navbar-left">
    <?php 
    if(Yii::$app->user->isGuest){        
    ?>
    
     <li class=""><a href="/site/index">Home</a></li>
     <li class=""><a href="/site/about">About</a></li>
     <li class=""><a href="/site/products">Products</a></li>
    <?php 
    }else  if(Yii::$app->user->identity->role == User::ROLE_VENDOR){       
    ?>
     <li class="<?php echo  strpos(\Yii::$app->controller->getRoute(), 'order') !== false && strpos(\Yii::$app->controller->getRoute(), 'coupon') === false ? 'active' : ''?>"><a href="/order">Order</a></li>
     <li class="<?php echo  strpos(\Yii::$app->controller->getRoute(), 'menu') !== false ? 'active' : ''?>"><a href="/menu">Menu</a></li>
     <li class="<?php echo  strpos(\Yii::$app->controller->getRoute(), 'customer') !== false ? 'active' : ''?>"><a href="/customer">Customer</a></li>
     
     <li class="dropdown <?php echo  strpos(\Yii::$app->controller->getRoute(), 'promotion') !== false || strpos(\Yii::$app->controller->getRoute(), 'coupon') !== false ? 'active' : ''?> ">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Promotion
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="/promotion?view=email">Email</a></li>
          <li><a href="/promotion?view=sms">SMS</a></li>
          <li><a href="/coupon">Coupons</a></li>
        </ul>
      </li>
      
     
     <li class="dropdown  <?php echo  strpos(\Yii::$app->controller->getRoute(), 'settings') !== false ? 'active' : ''?>">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Profile
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="/vendor/settings?view=settings">Settings</a></li>
          <li><a href="/vendor/settings?view=info">Restaurant Info</a></li>
          <li><a href="/vendor/settings?view=billing">Billing Info</a></li>
          <li><a href="/vendor/settings?view=history">Billing History</a></li>
        </ul>
      </li>
      <li class="<?php echo  strpos(\Yii::$app->controller->getRoute(), 'sales') !== false ? 'active' : ''?>"><a href="/sales">Sales</a></li>
      
    <?php 
    }else if(Yii::$app->user->identity->role== User::ROLE_CUSTOMER){
       
    ?>
    <li class="<?php echo  strpos(\Yii::$app->controller->getRoute(), 'ordering') !== false && strpos(\Yii::$app->controller->getRoute(), 'history') === false ? 'active' : ''?>"><a href="/ordering/menu">New Order</a></li>
    <li class="<?php echo  strpos(\Yii::$app->controller->getRoute(), 'history') !== false ? 'active' : ''?>"><a href="/ordering/history">Order History</a></li>
    
    <li class="dropdown  <?php echo  strpos(\Yii::$app->controller->getRoute(), 'profile') !== false ? 'active' : ''?>">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Profile
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="/my/profile?view=info">Profile Info</a></li>
          <li><a href="/my/profile?view=billing">Billing Info</a></li>
        </ul>
      </li>
      
    <?php 
    }
    ?>
    </ul>
    <ul class="nav navbar-nav navbar-right">
    <?php 
    if(Yii::$app->user->isGuest){

        if(TenantHelper::isDefaultTenant()){          
        ?>
        <li><a href="/site/reg-vendor"><span class="glyphicon glyphicon-user"></span> Register</a></li>
        <li><a href="/site/login"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        <?php 
        }else{
            
        ?>
        <li><a href="/site/reg-customer"><span class="glyphicon glyphicon-user"></span> Register</a></li>
        <li><a href="/site/login"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        <?php 
        }
    }else{
        
    ?>
    <?php if ($model !== false && $model->imageFile) { ?>
    <li>
      <img alt="" style="padding-top: 5px; max-height: 50px;" class="img-responsive" src="/images/users/<?= $model->imageFile ?>" />
    </li>
    <li>
    <a href="/site/logout">
    <?php } else { ?>
    <li>
      <a href="/site/logout">
      <span class="glyphicon glyphicon-user"></span>
    <?php } ?>
      <?php echo 'Logout (' . Yii::$app->user->identity->email . ')' ?></a></li>
    <?php 
    }
        
    ?>
    </ul>
    </div>
  </div>
</nav>
<?php 
  
    ?>

    

    <div class="container-fluid">
      <?php echo $this->render('_card_warning', []);?>
      <?php echo $this->render('_vendor_warning', []);?>
      <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container-fluid">
        <p class="pull-left">&copy; Restalutions <?= date('Y') ?></p>
    </div>
</footer>

<!-- Modal -->
<div class="modal fade" id="custom-modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
    </div>
  </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
