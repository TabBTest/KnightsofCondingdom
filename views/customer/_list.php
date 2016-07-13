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
            <th>First Name</th>
            <th>Last Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Street Address</th>
            <th>City</th>
            <th>State</th>
            <th>Is Active?</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $customer){?>
        <tr class="" data-id="<?= $customer->id ?>">
            <td><?= $customer->firstName ?></td>
            <td><?= $customer->lastName ?></td>
            <td><a href='tel:<?php echo $customer->phoneNumber?>'><?= $customer->phoneNumber?></a></td>
            <td><a href='mailto:<?php echo $customer->email?>'><?= $customer->email ?></td>
            <td><?= $customer->streetAddress?></td>
            <td><?= $customer->city ?></td>
            <td><?= $customer->state ?></td>
            <td>
             <?php if($customer->isActive == 1){?>
            <i style="width:15px" class="fa fa-thumbs-up"></i>
            <?php }else{?>
            <i style="width:15px" class="fa fa-thumbs-down"></i>
            <?php }?>
            </td>
            <td>
            <a class="show-action" href="#" data-original-title="" title=""><i class="fa fa-cogs"></i> Actions</a>
            <div style="display: none" class="pop-content">
                <ul style="list-style-type: none; margin: 0; padding: 0;">                       
                    <li>
                        <a href='/ordering/history/?id=<?php echo base64_encode($customer->id )?>'><i style="width:15px" class="fa fa-list" aria-hidden="true"></i><span style="font-size: 14px;">View Orders</span></a>
                    </li>
                    <li>
                        <?php if($customer->isActive == 1){?>
                        <a href="javascript: Customer.deactivate('<?php echo base64_encode($customer->id)?>')" class=""><i style="width:15px" class="fa fa-thumbs-down"></i><span style="font-size: 14px;">Deactivate Account</span></a>
                        <?php }else{?>
                        <a href="javascript: Customer.activate('<?php echo base64_encode($customer->id)?>')" class=""><i style="width:15px" class="fa fa-thumbs-up"></i><span style="font-size: 14px;"> Activate Account</span></a>
                        <?php }?>
                    </li>
                </ul>
            </div>
                            
            &nbsp;
            </td>
        </tr>
        <?php }?>
    </tbody>
</table>

<?php }?>
<div class="vendor-customer-pagination" data-user-id='<?php echo \Yii::$app->user->id?>' data-total-pages="<?php echo ceil($totalCount / 20)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>
