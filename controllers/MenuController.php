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
                        'actions' => ['index','add-category', 'save-category', 'edit-category', 'add-item','edit-item', 'save-item', 'delete-item', 'save-menu-sort', 'save-category-sort'],
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
        
        $vendorCategories = MenuCategories::find()->where('vendorId = '.$user->id.' order by sorting asc')->all();
        
        return $this->render('index', ['menu' => $vendorMenu, 'vendorCategories' => $vendorCategories]);
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
    
    public function actionSaveItem(){
        
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
        return $this->redirect('/menu');
    }
    
    public function actionAddCategory(){
        
        $sorting = $_REQUEST['sorting'];
        $menuCategory = new MenuCategories();
        $menuCategory->vendorId = \Yii::$app->user->id;
        $menuCategory->sorting = $sorting;
        return $this->renderPartial('add-category', ['category' => $menuCategory]);
    }
    
    public function actionEditCategory(){
        $categoryId = $_REQUEST['id'];
        $menuCategory = MenuCategories::findOne($categoryId);
        return $this->renderPartial('add-category', ['category' => $menuCategory]);
    }
    
    public function actionSaveCategory(){
    
        if(count($_POST) > 0){
            $categoryId = $_POST['id'];
            $category = MenuCategories::findOne($categoryId);
            if($category == null){
                $category = new MenuCategories();
                $category->sorting = intval($_POST['sorting']);
                $category->vendorId = intval($_POST['vendorId']);
            }
    
            $category->name = $_POST['name'];
            $category->description = $_POST['description'];
            $category->save();
                
    
            \Yii::$app->getSession()->setFlash('success', 'Menu Category Saved Successfully');
    
        }
        return $this->redirect('/menu');
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
