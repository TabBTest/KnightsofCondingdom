<?php
use app\models\Orders;
$list = $customers['list'];
$totalCount = $customers['count'];
?>
<?php if($totalCount == 0){?>
<h2>No Customers</h2>
<?php }else{?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Street Address</th>
            <th>City</th>
            <th>State</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $customer){?>
        <tr class="" data-id="<?= $customer->id ?>">
            <td><?= $customer->name ?></td>
            <td><a href='tel:<?php echo $customer->phoneNumber?>'><?= $customer->phoneNumber?></a></td>
            <td><a href='mailto:<?php echo $customer->email?>'><?= $customer->email ?></td>
            <td><?= $customer->streetAddress?></td>
            <td><?= $customer->city ?></td>
            <td><?= $customer->state ?></td>
        </tr>
        <?php }?>
    </tbody>
</table>

<?php }?>
<div class="vendor-customer-pagination" data-user-id='<?php echo \Yii::$app->user->id?>' data-total-pages="<?php echo ceil($totalCount / 20)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>
