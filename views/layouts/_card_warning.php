<?php  use app\models\User;
if(!Yii::$app->user->isGuest){
        //Yii::$app->user->identity->cardExpiry = '2015-06-26';
        $cardState = Yii::$app->user->identity->getCardState();
        
        if($cardState == User::CARD_STATE_EXPIRED){
            ?>
            <div class='alert alert-danger'>Card Information is expired</div>
        <?php
        }else if($cardState == User::CARD_STATE_NEAR_EXPIRE){
            ?>
            <div class='alert alert-warning'>Your Card would expire in <?php echo date('M Y', strtotime(Yii::$app->user->identity->cardExpiry))?></div>
            <?php            
        }else if($cardState == User::CARD_STATE_NOT_EXISTING){
            ?>
            <div class='alert alert-danger'>Please add your card billing information</div>
            <?php
        }
        
    }?>