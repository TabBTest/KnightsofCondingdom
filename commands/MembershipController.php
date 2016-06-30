<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models\User;
use app\helpers\TenantHelper;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MembershipController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionChargeVendors(){

        $sql = "select u.*, vm.endDate from user u left join (select vendorId, max(endDate) endDate from vendor_membership group by vendorId) vm on u.id = vm.vendorId
        where u.role = ".User::ROLE_VENDOR." and (vm.endDate is null or date(date_sub(vm.endDate, interval 7 day)) <= date(NOW()))";
	    $command = \Yii::$app->db->createCommand($sql);
	    $expiringOrNoMembershipVendors = $command->queryAll();
	    $membershipStatus = [];
	    foreach($expiringOrNoMembershipVendors as $vendor){
	        //var_dump($vendor['id']);
	        
	        $stat = (TenantHelper::doMembershipPayment($vendor['id']));
	        $membershipStatus[$vendor['id']] =$stat;
	    }
	   // TenantHelper::doMembershipPayment(3);
	   // var_dump($membershipStatus);
	    foreach($membershipStatus as $vendorId => $stat){
	        if($stat === false){
	            //we send notification that membership did not work
	            ;
	        }
	    }
    }
   
}
