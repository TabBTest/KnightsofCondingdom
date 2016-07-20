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
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="/" class="navbar-brand"><img alt="" class="img-responsive img-rounded" src="/images/logo.png"></a>
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
        }else  if(Yii::$app->user->identity->role == User::ROLE_ADMIN){       
            
        ?>
         <li class="<?php echo  strpos(\Yii::$app->controller->getRoute(), 'home') !== false ? 'active' : ''?>"><a href="/admin/home">Home</a></li>
         
         <li class="dropdown <?php echo  strpos(\Yii::$app->controller->getRoute(), 'vendors') !== false ? 'active' : ''?> ">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Vendors
            <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="/admin/vendors">List</a></li>
              <li><a href="/admin/vendors/overrides">Overrides</a></li>
            </ul>
          </li>
          
         <li class="<?php echo  strpos(\Yii::$app->controller->getRoute(), 'settings') !== false ? 'active' : ''?>"><a href="/admin/settings">Settings</a></li>
         
         <li class="dropdown <?php echo  strpos(\Yii::$app->controller->getRoute(), 'promotion') !== false || strpos(\Yii::$app->controller->getRoute(), 'coupon') !== false ? 'active' : ''?> ">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Promotion
            <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="/admin/promotion?view=email">Email</a></li>
              <li><a href="/admin/promotion?view=sms">SMS</a></li>
            </ul>
          </li>
          
        <?php 
        }
        ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
        <?php 
        if(Yii::$app->user->isGuest){
    
                
            ?>
            <li><a href="/admin/default/login"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
            <?php 
            
        }else{
            
        ?>
        <li><a href="/admin/default/logout"><span class="glyphicon glyphicon-user"></span> <?php echo 'Logout (' . Yii::$app->user->identity->email . ')' ?></a></li>
        <?php 
        }
            
        ?>
        </ul>
          
          </div>
      </div>
    </nav>


    <div class="container">      
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

