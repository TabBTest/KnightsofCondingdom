<?php

use yii\db\Migration;
use app\models\User;
use app\models\UserCardHistory;

class m160624_023404_add_code_store_stripe_info extends Migration
{
    public function up()
    {
       $users = User::find()->where("stripeId != ''")->all();
        foreach($users as $user){
            \Stripe\Stripe::setApiKey(\Yii::$app->params['stripe_secret_key']);
            $customer = \Stripe\Customer::retrieve($user->stripeId);
        
            $customInfo = $customer->__toArray(true);
            if($customInfo['object'] == 'customer'){
                //we get the card info to store
                $cardInfo = $customInfo['sources']['data'][0];
                $last4 = $cardInfo['last4'];
                $expiry = $cardInfo['exp_year'].'-'.sprintf("%02d", $cardInfo['exp_month']).'-01';                
                $user->cardLast4 = $last4;
                $user->cardExpiry = $expiry;
                $user->save();
                
                $cardHistory = new UserCardHistory();
                $cardHistory->userId = $user->id;
                $cardHistory->cardLast4 = $last4;
                $cardHistory->cardExpiry = $expiry;
                $cardHistory->save();
            }
            
        }
    }

    public function down()
    {
        echo "m160624_023404_add_code_store_stripe_info cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
