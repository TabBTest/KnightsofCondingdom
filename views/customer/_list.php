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
            <th>Address</th>          
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $customer){?>
        <tr class="" data-id="<?php echo $customer->id?>">
            <td><?php echo $customer->name?></td>
            <td><?php echo $customer->phoneNumber?></td>
            <td><?php echo $customer->email?></td>
            <td><?php echo $customer->address?></td>            
           
        </tr>
        <?php }?>
    </tbody>
</table>

<?php }?>
<div class="vendor-customer-pagination" data-user-id='<?php echo \Yii::$app->user->id?>' data-total-pages="<?php echo ceil($totalCount / 20)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>