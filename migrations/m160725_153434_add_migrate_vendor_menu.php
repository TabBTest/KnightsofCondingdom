<?php

use yii\db\Migration;
use app\models\VendorMenu;
use app\helpers\UtilityHelper;
use app\models\User;
use app\models\MenuCategories;

class m160725_153434_add_migrate_vendor_menu extends Migration
{
    public function up()
    {
        $menus = MenuCategories::find()->where('')->all();
        foreach($menus as $menuCat){
            $defaultMenu = User::getVendorDefaultMenu(User::findOne($menuCat->vendorId));
            $menuCat->vendorMenuId = $defaultMenu->id;
            $menuCat->save();
        }
    }

    public function down()
    {
        echo "m160725_153434_add_migrate_vendor_menu cannot be reverted.\n";

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
