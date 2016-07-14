<?php 
$list = $items['list'];
$totalCount = $items['count'];
?>


<?php if($totalCount == 0){?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>SMS</th>
            <th>Date</th>                       
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan='2'>No Promo SMS</td>
        </tr>
    </tbody>
</table>
<?php }else{?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>Subject</th>
            <th>Date</th>          
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $promo){?>
        <tr class="" data-id="<?php echo $promo->id?>">
            <td><a href="javascript: VendorSettings.viewPromo(<?php echo $promo->id?>)"><?php echo $promo->html?></a></td>
            <td><?php echo date('m-d-Y H:i', strtotime($promo->date_created));?></td>            
        </tr>
        <?php }?>
    </tbody>
</table>

<?php }?>
<div class="promo-sms-pagination" data-user-id='<?php echo $userId?>' data-total-pages="<?php echo ceil($totalCount / 20)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>