<?php 
use app\models\Orders;
$list = $transactions['list'];
$totalCount = $transactions['count'];
?>
<?php if($totalCount == 0){?>
<h2>No Transactions</h2>
<?php }else{?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>Card Last 4</th>
            <th>Transaction Id</th>
            <th>Description</th>         
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $membershipInfo){
            $description = 'Membership Payment for '.date('M d, Y', strtotime($membershipInfo->startDate)).' to '. date('M d, Y', strtotime($membershipInfo->endDate));
        ?>
        <tr class="" data-id="<?php echo $membershipInfo->id?>">
            <td><?php echo $membershipInfo->cardLast4?></td>
            <td><?php echo $membershipInfo->transactionId?></td>
           <td><?php echo $description?></td>
        </tr>
        <?php }?>
    </tbody>
</table>

<?php }?>
<div class="vendor-billing-pagination" data-user-id='<?php echo \Yii::$app->user->id?>' data-total-pages="<?php echo ceil($totalCount / 20)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>