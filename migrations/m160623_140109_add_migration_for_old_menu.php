<?php

use yii\db\Migration;
use app\models\VendorMenu;
use app\models\MenuCategories;
use app\models\VendorMenuItem;

class m160623_140109_add_migration_for_old_menu extends Migration
{
    public function up()
    {
        $vendorMenus = VendorMenu::findAll(['isDefault' => 1]);
        foreach($vendorMenus as $vendorMenu){
            $menuCat = new MenuCategories();
            $menuCat->vendorId = $vendorMenu->vendorId;
            $menuCat->name = 'Main';
            $menuCat->sorting = 1;
            $menuCat->save();
            //now we need to update all menus items
            $menuItems = VendorMenuItem::findAll(['vendorMenuId' => $vendorMenu->id]);
            foreach($menuItems as $index => $item){
                $item->menuCategoryId = $menuCat->id;
                $item->sorting = $index+1;
                $item->save();
            }
        } 
    }

    public function down()
    {
        echo "m160623_140109_add_migration_for_old_menu cannot be reverted.\n";

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
