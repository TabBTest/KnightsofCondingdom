<?php

namespace app\controllers;

use Yii;
use app\models\ApplicationType;
use app\models\ApplicationTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\ViewContextInterface;
use app\models\ApplicationTypeFormSetup;
use app\models\Candidates;
use yii\base\Application;
use app\models\User;
use app\models\VendorMenu;
use app\models\VendorMenuItem;
use app\helpers\UtilityHelper;
use app\models\MenuCategories;
use app\models\VendorMenuItemAddOns;

/**
 * ApplicationController implements the CRUD actions for ApplicationType model.
 */
class MenuController extends CController
{
   
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['edit', 'save-menu', 'delete-menu', 'create', 'add-category-add-ons','edit-category-add-ons', 'save-category-add-ons', 'delete-category-add-ons', 'add-item-add-ons','edit-item-add-ons', 'save-item-add-ons', 'delete-item-add-ons', 'index','add-category', 'save-category', 'edit-category', 'add-item','edit-item', 'save-item', 'delete-item','delete-category', 'save-menu-sort','save-menu-add-on-sort', 'save-category-sort'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    public function actionDeleteMenu(){
        $menu = VendorMenu::findOne($_REQUEST['id']);
        if($menu){
            $menu->delete();
            \Yii::$app->getSession()->setFlash('success', 'Menu Category Deleted Successfully');
        }
    }
    public function actionSaveMenu(){
        $nextUrl = '/menu';
        if(count($_POST) > 0){
            
            
            
            $menuId = $_POST['id'];
            $vendorMenu = VendorMenu::findOne($menuId);
            if($vendorMenu == null){
                $vendorMenu = new VendorMenu();
                $vendorMenu->vendorId = isset($_POST['vendorId']) ? intval($_POST['vendorId']) : 0;
                $vendorMenu->isDefault = 0;
            }
            
            $vendorMenu->name = $_POST['name'];
            $vendorMenu->startTime = $_POST['startTime'];
            $vendorMenu->endTime = $_POST['endTime'];
            $vendorMenu->save();
            
            if(Yii::$app->user->identity->role == User::ROLE_ADMIN){
                $nextUrl = '/admin/vendors/menu?id='.$vendorMenu->vendorId.'&menuId='.$vendorMenu->id;
            }else{
                $nextUrl .= '?menuId='.$vendorMenu->id;
            }
            
            \Yii::$app->getSession()->setFlash('success', 'Menu Saved Successfully');
            
        }
        return $this->redirect($nextUrl);
    }
    public function actionCreate(){
        $model = new VendorMenu();
        $model->vendorId = $_REQUEST['vendorId'];
        return $this->renderPartial('menu-form', ['model' => $model]);
    }
    public function actionEdit(){
        $model = VendorMenu::findOne($_REQUEST['id']);
        return $this->renderPartial('menu-form', ['model' => $model]);
    }

    /**
     * Lists all ApplicationType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $user = User::findOne(\Yii::$app->user->id);
        $vendorMenu = User::getVendorDefaultMenu($user);
        if($vendorMenu === false){
            $vendorMenu = new VendorMenu();
            $vendorMenu->name = 'default menu';
            $vendorMenu->vendorId = $user->id;
            $vendorMenu->isDefault = 1;
            $vendorMenu->save();
        }
        $vendorId = \Yii::$app->user->id;
        $vendorCategories = MenuCategories::find()->where('isArchived = 0 and vendorId = '.$user->id.' order by sorting asc')->all();
        
        return $this->render('index', ['vendorId' => $vendorId, 'menu' => $vendorMenu, 'vendorCategories' => $vendorCategories]);
    }
    
    public function actionAddItem(){
        $vendorMenuId = $_REQUEST['id'];
        $categoryId = $_REQUEST['categoryId'];
        $sorting = $_REQUEST['sorting'];
        $vendorMenuItem = new VendorMenuItem();
        $vendorMenuItem->vendorMenuId = $vendorMenuId;
        $vendorMenuItem->menuCategoryId = $categoryId;
        $vendorMenuItem->sorting = $sorting;
        return $this->renderPartial('add-item', ['menuItem' => $vendorMenuItem]);
    }
    
    public function actionEditItem(){
        $vendorMenuItemId = $_REQUEST['id'];
        $vendorMenuItem = VendorMenuItem::findOne($vendorMenuItemId);
        return $this->renderPartial('add-item', ['menuItem' => $vendorMenuItem]);
    }
    
    public function actionDeleteItem(){
        $vendorMenuItemId = $_REQUEST['id'];
        $vendorMenuItem = VendorMenuItem::findOne($vendorMenuItemId);
        $vendorMenuItem->isArchived = 1;
        $vendorMenuItem->save();
        \Yii::$app->getSession()->setFlash('success', 'Menu Item Deleted Successfully');
    }
    public function actionDeleteCategory(){
        $menuCategoryId = $_REQUEST['id'];
        $menuCategory = MenuCategories::findOne($menuCategoryId);
        $menuCategory->isArchived = 1;
        $menuCategory->save();
        \Yii::$app->getSession()->setFlash('success', 'Menu Category Deleted Successfully');
    }
    public function actionSaveItem(){
        $nextUrl = '/menu';
        if(count($_POST) > 0){
            
            
            
            $menuItemId = $_POST['id'];
            $vendorMenuItem = VendorMenuItem::findOne($menuItemId);
            if($vendorMenuItem == null){
                $vendorMenuItem = new VendorMenuItem();
                $vendorMenuItem->vendorMenuId = intval($_POST['vendorMenuId']);
                $vendorMenuItem->menuCategoryId = intval($_POST['menuCategoryId']);
                $vendorMenuItem->sorting = intval($_POST['sorting']);
            }
            
            $vendorMenuItem->name = $_POST['name'];
            $vendorMenuItem->description = $_POST['description'];
            $vendorMenuItem->amount = floatval($_POST['amount']);
            $vendorMenuItem->save();
            
            $menuCategory = MenuCategories::findOne($vendorMenuItem->menuCategoryId);
            if(Yii::$app->user->identity->role == User::ROLE_ADMIN){
                $nextUrl = '/admin/vendors/menu?id='.$menuCategory->vendorId;
            }
            if(isset($_FILES['photo'])){
                 
                $filename = $_FILES['photo']["name"];
                $source = $_FILES['photo']["tmp_name"];
                $type = $_FILES['photo']["type"];
                 
                $imagePath = '/menu-images/'.md5($vendorMenuItem->vendorMenuId);
                $targetPath = realpath(Yii::$app->basePath).'/web' . $imagePath;
                 
                UtilityHelper::createPath($targetPath);
                 
                $target_path = $targetPath.'/'.md5($vendorMenuItem->id);  // change this to the correct site path
                if(is_file($target_path)){
                    unlink($target_path);
                }
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
                    
                     
                }
            }
            
            \Yii::$app->getSession()->setFlash('success', 'Menu Item Saved Successfully');
            
        }
        return $this->redirect($nextUrl);
    }
    
    public function actionAddCategoryAddOns(){
        $vendorCategoryId = $_REQUEST['id'];
        $vendorMenuItemAddOns = new VendorMenuItemAddOns();
        $vendorMenuItemAddOns->vendorMenuItemId = 0;
        $vendorMenuItemAddOns->menuCategoryId = $vendorCategoryId;
        
        //$vendorMenuItemAddOns->sorting = $sorting;
    
        $exclusiveList = VendorMenuItemAddOns::find()->where('menuCategoryId = ' . $vendorMenuItemAddOns->menuCategoryId . ' and isArchived = 0 and isExclusive = 1 order by sorting asc')->all();
        $nonExclusiveList =VendorMenuItemAddOns::find()->where('menuCategoryId = ' . $vendorMenuItemAddOns->menuCategoryId . ' and isArchived = 0 and isExclusive = 0 order by sorting asc')->all();
    
        return $this->renderPartial('add-item-add-ons', ['menuItemAddOns' => $vendorMenuItemAddOns, 'exclusiveList' => $exclusiveList, 'nonExclusiveList' => $nonExclusiveList]);
    }
    
    public function actionAddItemAddOns(){
        $vendorMenuItemId = $_REQUEST['id'];
       
        //$sorting = $_REQUEST['sorting'];
        $vendorMenuItemAddOns = new VendorMenuItemAddOns();
        $vendorMenuItemAddOns->vendorMenuItemId = $vendorMenuItemId;
        //$vendorMenuItemAddOns->sorting = $sorting;
        
        $exclusiveList = VendorMenuItemAddOns::find()->where('vendorMenuItemId = ' . $vendorMenuItemAddOns->vendorMenuItemId . ' and isArchived = 0 and isExclusive = 1 order by sorting asc')->all();
        $nonExclusiveList =VendorMenuItemAddOns::find()->where('vendorMenuItemId = ' . $vendorMenuItemAddOns->vendorMenuItemId . ' and isArchived = 0 and isExclusive = 0 order by sorting asc')->all();
        
        return $this->renderPartial('add-item-add-ons', ['menuItemAddOns' => $vendorMenuItemAddOns, 'exclusiveList' => $exclusiveList, 'nonExclusiveList' => $nonExclusiveList]);
    }
    
    public function actionEditItemAddOns(){
        $vendorMenuItemAddOnId = $_REQUEST['id'];
        $vendorMenuItemAddOns = VendorMenuItemAddOns::findOne($vendorMenuItemAddOnId);
        $exclusiveList = [];
        $nonExclusiveList = [];
        if($vendorMenuItemAddOns->menuCategoryId > 0){
            $exclusiveList = VendorMenuItemAddOns::find()->where('menuCategoryId = ' . $vendorMenuItemAddOns->menuCategoryId . ' and isArchived = 0 and isExclusive = 1 order by sorting asc')->all();
            $nonExclusiveList =VendorMenuItemAddOns::find()->where('menuCategoryId = ' . $vendorMenuItemAddOns->menuCategoryId . ' and isArchived = 0 and isExclusive = 0 order by sorting asc')->all();
        }else{
            $exclusiveList = VendorMenuItemAddOns::find()->where('vendorMenuItemId = ' . $vendorMenuItemAddOns->vendorMenuItemId . ' and isArchived = 0 and isExclusive = 1 order by sorting asc')->all();
            $nonExclusiveList =VendorMenuItemAddOns::find()->where('vendorMenuItemId = ' . $vendorMenuItemAddOns->vendorMenuItemId . ' and isArchived = 0 and isExclusive = 0 order by sorting asc')->all();
        }
        return $this->renderPartial('add-item-add-ons', ['menuItemAddOns' => $vendorMenuItemAddOns, 'exclusiveList' => $exclusiveList, 'nonExclusiveList' => $nonExclusiveList]);
    }
    
    public function actionDeleteItemAddOns(){
        $vendorMenuItemAddOnId = $_REQUEST['id'];
        $vendorMenuItemAddOn = VendorMenuItemAddOns::findOne($vendorMenuItemAddOnId);
        $vendorMenuItemAddOn->isArchived = 1;
        $vendorMenuItemAddOn->save();
        die;
        //\Yii::$app->getSession()->setFlash('success', 'Menu Item Add On Deleted Successfully');
    }
    
    public function actionSaveItemAddOns(){
    
        $resp = [];
        if(count($_POST) > 0){
            $menuItemAddOnsId = $_POST['id'];
            $vendorMenuItemAddOn = VendorMenuItemAddOns::findOne($menuItemAddOnsId);
            $type = '';
            if($vendorMenuItemAddOn == null){
                $vendorMenuItemAddOn = new VendorMenuItemAddOns();
                $vendorMenuItemAddOn->vendorMenuItemId = intval($_POST['vendorMenuItemId']);
                $vendorMenuItemAddOn->menuCategoryId = intval($_POST['menuCategoryId']);
                $vendorMenuItemAddOn->sorting = intval($_POST['sorting']);
                
                $allAddOns = [];
                if($vendorMenuItemAddOn->menuCategoryId > 0){
                    $allAddOns = VendorMenuItemAddOns::findAll(['menuCategoryId' => $vendorMenuItemAddOn->menuCategoryId , 'isArchived' => 0, 'isExclusive' => intval($_POST['isExclusive'])]);
                    $type = 'category';
                }else{
                    $allAddOns = VendorMenuItemAddOns::findAll(['vendorMenuItemId' => $vendorMenuItemAddOn->vendorMenuItemId , 'isArchived' => 0, 'isExclusive' => intval($_POST['isExclusive'])]);
                    $type = 'menu-item';
                }
                $vendorMenuItemAddOn->sorting = count($allAddOns)  + 1;
            }
    
            $vendorMenuItemAddOn->name = $_POST['name'];
            $vendorMenuItemAddOn->description = $_POST['description'];
            $vendorMenuItemAddOn->amount = floatval($_POST['amount']);
            $vendorMenuItemAddOn->isExclusive = intval($_POST['isExclusive']);
            $vendorMenuItemAddOn->save();
    
            
    
            \Yii::$app->getSession()->setFlash('success', 'Menu Item Add On Saved Successfully');
            $resp['status'] = 1;
            $resp['id'] = $vendorMenuItemAddOn->id;
            $resp['type'] = $type;
        }
        echo json_encode($resp);
        die;
        //return $this->redirect('/menu');
    }
    
    public function actionAddCategory(){
        
        $sorting = $_REQUEST['sorting'];
        $menuCategory = new MenuCategories();
        $menuCategory->vendorId = \Yii::$app->user->id;
        $menuCategory->vendorMenuId = $_REQUEST['menuId'];
        $menuCategory->sorting = $sorting;
        return $this->renderPartial('add-category', ['category' => $menuCategory]);
    }
    
    public function actionEditCategory(){
        $categoryId = $_REQUEST['id'];
        $menuCategory = MenuCategories::findOne($categoryId);
        return $this->renderPartial('add-category', ['category' => $menuCategory]);
    }
    
    public function actionSaveCategory(){
        $nextUrl = '/menu';
        if(count($_POST) > 0){
            
            
            $categoryId = $_POST['id'];
            $category = MenuCategories::findOne($categoryId);
            if($category == null){
                $category = new MenuCategories();
                $category->sorting = intval($_POST['sorting']);
                $category->vendorId = intval($_POST['vendorId']);
                $category->vendorMenuId = intval($_POST['vendorMenuId']);
            }
    
            $category->name = $_POST['name'];
            $category->description = $_POST['description'];
            $category->save();
                
            if(Yii::$app->user->identity->role == User::ROLE_ADMIN){
                $nextUrl = '/admin/vendors/menu?id='.$category->vendorId.'&menuId='.$category->vendorMenuId;
            }else{
                $nextUrl .= '?menuId='.$category->vendorMenuId;
            }
    
            \Yii::$app->getSession()->setFlash('success', 'Menu Category Saved Successfully');
    
        }
        return $this->redirect($nextUrl);
    }
    
    public function actionSaveMenuSort(){
        $sort = $_POST['sort'];
        if($sort != ''){
            $sortInfo = explode(',', $sort);
            foreach($sortInfo as $info){
                $sortData = explode(':', $info);
                $item = VendorMenuItem::findOne($sortData[0]);
                if($item){
                    $item->sorting = intval($sortData[1]);
                    $item->save();
                }
            }
        }
    }
    public function actionSaveMenuAddOnSort(){
        $sort = $_POST['sort'];
        if($sort != ''){
            $sortInfo = explode(',', $sort);
            foreach($sortInfo as $info){
                $sortData = explode(':', $info);
                $item = VendorMenuItemAddOns::findOne($sortData[0]);
                if($item){
                    $item->sorting = intval($sortData[1]);
                    $item->save();
                }
            }
        }
    }
    public function actionSaveCategorySort(){
        $sort = $_POST['sort'];
        if($sort != ''){
            $sortInfo = explode(',', $sort);
            foreach($sortInfo as $info){
                $sortData = explode(':', $info);
                $item = MenuCategories::findOne($sortData[0]);
                if($item){
                    $item->sorting = intval($sortData[1]);
                    $item->save();
                }
            }
        }
    }
}
