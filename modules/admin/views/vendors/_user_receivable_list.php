<?php
use app\models\Orders;
use app\models\AppConfig;
use app\models\VendorAppConfigOverride;
$list = $vendors['list'];
$totalCount = $vendors['count'];

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
            <th>Total Faxes</th>
            <th>Total Cost</th>
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
            <td>$<?php echo $customer->getTotalFaxes($fromDate, $toDate)?></td>
            <td>$<?php echo $customer->getTotalReceivableCost($fromDate, $toDate)?></td>
            <td><a href="/admin/vendors/receivable-summary-details?id=<?php echo $customer->id?>&fromDate=<?php echo $fromDate?>&toDate=<?php echo $toDate?>"><i class='fa fa-eye'></i></a></td>       
        </tr>
        <?php }?>
        
    </tbody>
</table>

<?php }?>
<div class="payable-user-pagination" data-user-id='<?php echo \Yii::$app->user->id?>' data-total-pages="<?php echo ceil($totalCount / 20)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>