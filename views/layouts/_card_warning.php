<?php  use app\models\User;
if(!Yii::$app->user->isGuest){
        //Yii::$app->user->identity->cardExpiry = '2015-06-26';
        $cardState = Yii::$app->user->identity->getCardState();
        $url = '/vendor/settings?view=billing';
        if(Yii::$app->user->identity->role == User::ROLE_CUSTOMER)
            $url = '/my/profile?view=billing';
        
        if($cardState == User::CARD_STATE_EXPIRED){
            ?>
            <div class='alert alert-danger'>Card Information is expired, update your card <a href="<?php echo $url?>/">here</a>.</div>
        <?php
        }else if($cardState == User::CARD_STATE_NEAR_EXPIRE){
            ?>
            <div class='alert alert-warning'>Your Card would expire in <?php echo date('M Y', strtotime(Yii::$app->user->identity->cardExpiry))?>, update your card <a href="<?php echo $url?>/">here</a>.</div>
            <?php            
        }else if($cardState == User::CARD_STATE_NOT_EXISTING){
            ?>
            <div class='alert alert-danger'>Please add your card billing information, update your card <a href="<?php echo $url?>/">here</a>.</div>
            <?php
        }
        
        
        if(Yii::$app->session->get('role') == User::ROLE_VENDOR){
            //we check if membership is expired
             if(Yii::$app->user->identity->isMembershipExpired()){
                 ?>
                 <div class='alert alert-danger'>Membership is expired</div>
             <?php
             }
           
        }
        
}?>