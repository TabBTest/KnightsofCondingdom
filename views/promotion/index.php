<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
use app\models\Promotion;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Promotions';
$this->params['breadcrumbs'][] = $this->title;
?>



    
<ul class="nav nav-tabs">    
    <li class="<?php echo $_REQUEST['view'] == 'email' ? 'active' : ''?>"><a data-toggle="tab" href="#tab-promo-email">Email</a></li>
    <li class="<?php echo $_REQUEST['view'] == 'sms' ? 'active' : ''?>"><a data-toggle="tab" href="#tab-promo-sms">SMS</a></li>
</ul>

<div class="tab-content">
    <div id="tab-promo-email" class="tab-pane <?php echo $_REQUEST['view'] == 'email' ? 'active' : ''?>" style='margin-top: 10px'>    
         
        <div class="col-xs-12">
            <?php echo $this->render('email',[]);?>
        </div>
        <div class="col-xs-12">
            <h2>Past Promo Emails</h2>
        </div>        
        <div class="col-xs-12 promo-email-body" data-url='<?php echo $url?>'>
            <?php echo $this->render('_email_list', ['items' => $emailList, 'userId' => $vendorId]);?>
        </div>
    </div>
    <div id="tab-promo-sms" class="tab-pane <?php echo $_REQUEST['view'] == 'sms' ? 'active' : ''?>" style='margin-top: 10px'>
        <div class="col-xs-12">
            <?php echo $this->render('sms', []);?>
        </div>
        <div class="col-xs-12">
            <h2>Past Promo SMS</h2>
        </div>        
        <div class="col-xs-12 promo-sms-body" data-url='<?php echo $urlSms?>'>
            <?php echo $this->render('_sms_list', ['items' => $smsList, 'userId' => $vendorId]);?>
        </div>
    </div>
</div>