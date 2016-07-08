<?php 
use app\helpers\UtilityHelper;
$vendorId = $params['userId'];
?>


<div class='col-xs-12' style='text-align: center'>
    <label class='form-label'>Operating Hours</label>
</div>

<?php 
$operatingTime = UtilityHelper::getOperatingTime();
foreach(UtilityHelper::getDays() as $key => $val){
?>
<div class='col-xs-12' style='margin-bottom: 10px'>
     <div class='col-xs-2'>
        <label for="inputEmail3" class="col-xs-2 control-label"><?php echo $val?></label>
     </div>
 
     <div class='col-xs-10'>
         <?php foreach($params['startTime'][$key] as $index => $time){
             $closeTime = $params['endTime'][$key][$index];
             
             if($time == '' || $closeTime == '')
                 continue;
         ?>
            <div class='col-xs-12'><label class='control-label'>
            <?php foreach($operatingTime as $val => $display){?>
            <?php echo $val == $time ?  $display : ''?>
            <?php }?>
            -
            <?php foreach($operatingTime as $val => $display){?>
            <?php echo $val == $closeTime ?  $display : ''?>
            <?php }?>
            </label></div>
         <?php
         }
         ?>
     </div>
</div>
<?php 
}      
?>
                
