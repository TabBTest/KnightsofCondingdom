<?php 

use app\models\VendorMenuItem;
use app\models\Orders;
use app\models\User;
$this->title = 'Customer Management';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if(\Yii::$app->getSession()->hasFlash('error')){?>
 <div class="">
<div class="alert alert-danger">
    <?php echo \Yii::$app->getSession()->getFlash('error'); ?>
</div>
 </div>
<?php } ?>
<?php if(\Yii::$app->getSession()->hasFlash('success')){?>
 <div class="">
<div class="alert alert-success">
    <?php echo \Yii::$app->getSession()->getFlash('success'); ?>
</div>
 </div>
<?php } ?>

<div class='row'>
    <div class='col-xs-12 text-center'>
        <h1><?php echo Yii::$app->session->get('name')?></h1>
    </div>
</div>

<div class="col-xs-12 vendor-customer-body">
    <?php echo $this->render('_list', ['customers' => User::getVendorCustomers(\Yii::$app->user->id, 20, 1)]);?>
</div>