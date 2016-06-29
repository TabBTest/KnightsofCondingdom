<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\helpers\TouricoHelper;
use app\models\Locations;
use app\helpers\ExpediaHelper;
use app\models\Search;
use app\models\SearchResults;
use app\models\ExpediaHotels;
use app\helpers\UtilityHelper;
use app\models\AppLocation;
use AlgoliaSearch\Client;
use app\helpers\HotelIndexDocHelper;
use app\models\ShortcutRecords;
use app\models\User;
use app\helpers\TenantHelper;
use app\models\TenantInfo;
use app\models\VendorMenuItem;
use app\models\VendorMenu;
use app\models\Orders;
use app\models\OrderDetails;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SimulationController extends Controller
{
    /**
     * This command simulate the creation of order for the vendor.
     * @param string $subDomain the subdomain.
     * @param string $numOfOrders number of orders .
     */
    public function actionOrderCreation($subDomain, $numOfOrders){
        var_dump($subDomain);
        var_dump($numOfOrders);
        
        $tenantInfo = TenantInfo::findOne(['code' => TenantInfo::CODE_SUBDOMAIN, 'val' => $subDomain]);
        if($tenantInfo){
            $vendor = User::findOne($tenantInfo->userId);
            $user = User::find()->where('vendorId = '.$tenantInfo->userId)->limit(1)->one();
            if($user){
                $vendorMenu = VendorMenu::findOne(['isDefault' => 1, 'vendorId' => $tenantInfo->userId]);
                if($vendorMenu){
                    $vendorMenuItem = VendorMenuItem::findOne(['vendorMenuId' => $vendorMenu->id]);
                    if($vendorMenuItem){
                        
                        for($index = 1 ; $index <= $numOfOrders ; $index++){
                            $order = new Orders();
                            $order->transactionId = 'SIM-'.strtotime('now');
                            $order->status = Orders::STATUS_NEW;
                            $order->customerId = $user->id;
                            $order->vendorId = $vendor->id;
                            $order->cardLast4 = $user->cardLast4;
                            if($order->save()){
                                    $quantity = 2;
                                    $orderDetails = new OrderDetails();
                                    $orderDetails->orderId = $order->id;
                                    $orderDetails->vendorMenuItemId = $vendorMenuItem->id;
                                    $orderDetails->name = $vendorMenuItem->name;
                                    $orderDetails->amount = $vendorMenuItem->amount;
                                    $orderDetails->quantity = intval($quantity);
                                    $orderDetails->totalAmount = intval($quantity) * $vendorMenuItem->amount;
                            
                                    if($orderDetails->save()){
                                        echo 'Order # '.$index.' created<br />';
                                    }else{
                                        var_dump($orderDetails->errors);
                                    }                        
                                                           
                            }
                        }   
                    }else{
                        echo 'There is no menu item';
                    }
                }else{
                    echo 'There is no menu';
                }
            }else{
                echo 'Vendor has no customer';
            }
        }else{
            echo 'Vendor Not Existing';
        }
       
        
    }
   
}
