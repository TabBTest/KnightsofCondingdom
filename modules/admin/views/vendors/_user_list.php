<?php
use app\models\Orders;
use app\models\AppConfig;
use app\models\VendorAppConfigOverride;
$list = $vendors['list'];
$totalCount = $vendors['count'];
$appConfigs = AppConfig::find()->where('')->all();

?>
<?php if($totalCount == 0){?>
<h2>No Vendors</h2>
<?php }else{?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>Business Name</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Phone</th>
            <th>Email</th>
            <?php
foreach($appConfigs as $conf){
    ?>
    <th><?php echo $conf->name?></th>
<?php }?>
 <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $customer){?>
        <tr class="" data-id="<?= $customer->id ?>">
            <td><?= $customer->businessName ?></td>
            <td><?= $customer->firstName ?></td>
            <td><?= $customer->lastName ?></td>
            <td><a href='tel:<?php echo $customer->phoneNumber?>'><?= $customer->phoneNumber?></a></td>
            <td><a href='mailto:<?php echo $customer->email?>'><?= $customer->email ?></td>
                         <?php
foreach($appConfigs as $conf){
    $overrideVal = VendorAppConfigOverride::getVendorOverride($customer->id, $conf->code);
    $isNewValue = $overrideVal != $conf->val ? true : false
    ?>
    <td class='<?php echo $isNewValue ? 'override' : ''?>'><?php echo $isNewValue ? $overrideVal : '-'?></td>
<?php }?> 
<td><a href="/admin/vendors/config?id=<?php echo $customer->id?>"><i class='fa fa-pencil'></i></a></td>       
        </tr>
        <?php }?>
        
    </tbody>
</table>

<?php }?>
<div class="overrides-user-pagination" data-user-id='<?php echo \Yii::$app->user->id?>' data-total-pages="<?php echo ceil($totalCount / 20)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>
<style>
td.override{
	background-color: #009688;    
}
</style>