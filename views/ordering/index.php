<?php 

use app\models\VendorMenuItem;
use yii\widgets\MaskedInput;
$this->title = 'Menu';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if(\Yii::$app->getSession()->hasFlash('error')){?>
 <div class="">
<div class="alert alert-danger">
    <?php echo \Yii::$app->getSession()->getFlash('error'); ?>
</div>
 </div>
<?php } ?>
<?php if(\Yii::$app->getSession()->hasFlash('success')){?>
 <div class="">
<div class="alert alert-success">
    <?php echo \Yii::$app->getSession()->getFlash('success'); ?>
</div>
 </div>
<?php } ?>

<div class='row'>
    <div class='col-xs-12 text-center'>
        <h1>Menu</h1>
    </div>
</div>
<form action='/ordering/save' method='POST' id='customer-order-form'>
<table class='table table-condensed table-striped'>
<thead>
    <tr>
        <th width='60%'>Name</th>
        <th width='20%'>Amount</th>
        <th width='20%'>Quantity</th>
    </tr>
</thead>



    <?php 
    $menuItems = VendorMenuItem::findAll(['vendorMenuId' => $menu->id]);
    ?>
    
    <tbody>
<?php foreach($menuItems as $item){
        if($item->isArchived == 1)
            continue;
        
?>
         <tr>
            <td><a href='javascript: VendorMenu.openMenuDetails("<?php echo md5($item->id)?>")'><?php echo $item->name?></a>
                <div class='menu-details-<?php echo md5($item->id)?>' style='display: none'>
                <?php if($item->hasPhoto()){?>
                <img src='/menu-images/<?php echo $item->getPhotoPath() ?>' width='150px' height='150px'/>
                <?php }else{?>
                <img src='/images/placeholder.png' width='150px' height='150px'/>
                <?php }?>
                 <label class='form-label'><?php echo $item->description?></label>
                </div>
            </td>
            <td>$<?php echo $item->amount?></td>
            <td><input class='form-control order-quantity' type='number' name='Orders[<?php echo $item->id?>]' value='0' min='0'/>
            
          
        </td>
        </tr>
<?php 
      }
    ?>
</tbody>
</table>

<div class='row'>
    <div class='col-xs-12 text-center'>
        <button type='button' class='btn btn-success btn-submit-order'>View Order Summary</button>
    </div>
</div>

</form>
<script>
$("[type='number']").keypress(function (evt) {
    evt.preventDefault();
});
</script>