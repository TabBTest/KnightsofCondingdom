<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>

<style>
    .error-container {padding-top: 50px; padding-bottom: 50px;}
    .error-container .lead{ margin-top: 2.5em; margin-bottom: 1.25em; color:#b94a48;}
</style>
<?php
$exception = \Yii::$app->errorHandler->exception;
$code = $exception->statusCode;
    switch($code){
        case '400' : $error_img = "e400.png";break;
        case '401' : $error_img = "e401.png";break;
        case '403' : $error_img = "e403.png";break;
        case '404' : $error_img = "e404.png";break;
        case '500' : $error_img = "e500.png";break;
        default    : $error_img = "e500.png";
    }
?>
<div class="container error-container">
    <div class="row">
        <div class="col-xs-offset-1 col-xs-10 text-center">
            <img src="/images/errors/<?php echo $error_img;?>" style="margin:0 auto;" class="img-responsive" />
        </div>
    </div>
    <div class="row">
        <p class="lead text-center">
            <?= nl2br(Html::encode($message)) ?>
        </p>
    </div>
</div>

