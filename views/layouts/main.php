<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\User;
use app\helpers\TenantHelper;
use app\helpers\UtilityHelper;

AppAsset::register($this);
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

<div class="wrap">
    
 <nav class="navbar-inverse navbar-fixed-top navbar">
  <div class="container">
    <div class="navbar-header">
      <a href="/" class="navbar-brand"><img alt="" src="/images/logo.png"></a>
    </div>
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
     <li class="<?php echo  strpos(\Yii::$app->controller->getRoute(), 'order') !== false ? 'active' : ''?>"><a href="/order">Order</a></li>
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
      
    <?php 
    }else if(Yii::$app->user->identity->role== User::ROLE_CUSTOMER){
       
    ?>
    <li class="<?php echo  strpos(\Yii::$app->controller->getRoute(), 'ordering') !== false && strpos(\Yii::$app->controller->getRoute(), 'history') === false ? 'active' : ''?>"><a href="/ordering">New Order</a></li>
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
    <li><a href="/site/logout"><span class="glyphicon glyphicon-user"></span> <?php echo 'Logout (' . Yii::$app->user->identity->email . ')' ?></a></li>
    <?php 
    }
        
    ?>
    </ul>
      
      
  </div>
</nav>
<?php 
  
    ?>

    

    <div class="container">
      <?php echo $this->render('_card_warning', []);?>
      <?php echo $this->render('_vendor_warning', []);?>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
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
