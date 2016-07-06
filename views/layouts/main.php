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
    <link href="/css/jquery-ui.min.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/site.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>



</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php 
    NavBar::begin([
        'brandLabel' => Html::img('/images/logo.png'),
        'brandOptions' => ['class' => 'navbar-brand'],
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    if(Yii::$app->user->isGuest){
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'items' => [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'About', 'url' => ['/site/about']],
                ['label' => 'Products', 'url' => ['/site/products']],
            ],
        ]);
    }else if(Yii::$app->user->identity->role == User::ROLE_VENDOR){
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'items' => [
                ['label' => 'Dashboard', 'url' => ['/dashboard']],
                ['label' => 'Menu', 'url' => ['/menu'], 'active' => strpos(\Yii::$app->controller->getRoute(), 'menu') !== false ? true : false],
                ['label' => 'Customer', 'url' => ['/customer'], 'active' => strpos(\Yii::$app->controller->getRoute(), 'customer') !== false ? true : false],
                ['label' => 'Order', 'url' => ['/order'], 'active' => strpos(\Yii::$app->controller->getRoute(), 'order') !== false ? true : false],
                ['label' => 'Promotion', 'url' => ['/promotion'], 'active' => strpos(\Yii::$app->controller->getRoute(), 'promotion') !== false ? true : false],
                ['label' => 'Settings', 'url' => ['/vendor/settings'], 'active' => strpos(\Yii::$app->controller->getRoute(), 'settings') !== false ? true : false],

            ],
        ]);
    }else if(Yii::$app->user->identity->role== User::ROLE_CUSTOMER){

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'items' => [
                ['label' => 'Dashboard', 'url' => ['/dashboard']],
                ['label' => 'New Order', 'url' => ['/ordering'], 'active' => strpos(\Yii::$app->controller->getRoute(), 'ordering') !== false ? true : false],
                ['label' => 'Order History', 'url' => ['/ordering/history'], 'active' => strpos(\Yii::$app->controller->getRoute(), 'history') !== false ? true : false],
                ['label' => 'Profile', 'url' => ['/my/profile'], 'active' => strpos(\Yii::$app->controller->getRoute(), 'profile') !== false ? true : false],
                //['label' => 'Profile', 'url' => ['/vendor']],
            ],
        ]);
    }
    if(Yii::$app->user->isGuest){

        if(TenantHelper::isDefaultTenant()){

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Register', 'url' => ['/site/reg-vendor']],
                    ['label' => 'Login', 'url' => ['/site/login']],
                ],
            ]);
        }else{
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Register', 'url' => ['/site/reg-customer']],
                    ['label' => 'Login', 'url' => ['/site/login']],
                ],
            ]);
        }
    }else{
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
               (
                    '<li>'
                    . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->email . ')',
                        ['class' => 'btn btn-link']
                    )
                    . Html::endForm()
                    . '</li>'
                )
            ],
        ]);
    }

    NavBar::end();
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
  <div class="modal-dialog" role="document">
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

    <!--  -->
    <script src="/js/jquery.flexslider-min.js"></script>
    <script src="/js/jquery.bootpag.min.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <script src="/js/ga.js"></script>
    <script src="/js/clipboard.min.js"></script>
    <script src="/js/app.js"></script>
<style>
body > .wrap > .container {
    min-height: 500px;
    padding: 60px 15px 0;
}
</style>
</html>
<?php $this->endPage() ?>
