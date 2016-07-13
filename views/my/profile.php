<?php

use app\models\VendorMenuItem;
use app\models\TenantInfo;
use yii\widgets\MaskedInput;
use app\helpers\UtilityHelper;
use app\models\User;
use app\helpers\TenantHelper;
$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;

$jsSelectState = <<<JS
$('#select-state').val('$model->state');
JS;



$this->registerJs('Stripe.setPublishableKey(\'' . \Yii::$app->params['stripe_publishable_key'] . '\');', $this::POS_READY);
$this->registerJs($jsSelectState, $this::POS_READY);

?>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<?php echo $this->render('//partials/_show_message', []);?>

<ul class="nav nav-tabs">    
    <li class="<?php echo $_REQUEST['view'] == 'info' ? 'active' : ''?>"><a data-toggle="tab" href="#tab-profile">Profile</a></li>
    <li class="<?php echo $_REQUEST['view'] == 'billing' ? 'active' : ''?>"><a data-toggle="tab" href="#billing-info">Billing Info</a></li>
</ul>

<div class="tab-content">
    <?php echo $this->render('//partials/_profile', ['model' => $model]);?>
    <?php echo $this->render('//partials/_billing', ['model' => $model]);?>
    
</div>
