<?php 

use app\models\Orders;
$this->title = 'Order History';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class='row'>
    <div class='col-xs-12 text-center'>
        <h1>Order History</h1>
    </div>
</div>
<div class="col-xs-12 customer-order-body" data-url="<?php echo $url?>">
    <?php echo $this->render('_history', ['orders' => $orders, 'userId' => $userId]);?>
</div>