<?php
namespace app\helpers;

use yii\helpers\Html;
use app\models\User;
use app\models\AppConfig;
class UtilityHelper {
/**
	 * Check operating system
	 *
	 * @return boolean true if it's Windows OS
	 */
	protected function isWindows()
	{
		if (PHP_OS == 'WINNT' || PHP_OS == 'WIN32') {
			return true;
		} else {
			return false;
		}
	}
	static public function runCommand($command, $params){
    
        if (self::isWindows() === true) {
            pclose(popen('start /b ' . \Yii::$app->basePath.'\yii.bat '.$command.' '.$params, 'r'));
        } else {
            //pclose(popen(\Yii::$app->basePath.'/yii '. $command.' '.$params . ' /dev/null &', 'r'));
            shell_exec(\Yii::$app->basePath.'/yii '. $command.' '.$params . ' > /dev/null 2>/dev/null &');
        }
        return true;
    }
    
    static public function cryptPass($x){
        //$password =  hash("sha256",$x);
        //return str_replace(array('0','o','1','l','i'), '', $password);
        return crypt($x, \Yii::$app->params['salt']);
    }
    
    static public function generateRandomPassword($length = 8){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $password = substr( str_shuffle( $chars ), 0, $length );
        return $password;
    }
    
    public static function createPath($path) {
        if (is_dir($path)) return true;
        $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1 );
        $return = self::createPath($prev_path);
        return ($return && is_writable($prev_path)) ? mkdir($path) : false;
    }
    
    static public function formatAmountForDisplay($amount){
        return number_format($amount, 2, '.', ',');
    }
    
    static public function getStateList(){
        return ['AL','AK','AZ','AR','CA','CO','CT','DE','DC',
                'FL','GA','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH',
                'NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY'
            ];
    }
    
    public static function getAllVendors(){
        $vendors = User::findAll(['role' => User::ROLE_VENDOR]);
        $vendorList = [];
        foreach($vendors as $vendor){
            $vendorList[$vendor->id] = $vendor->name;
        }
        return $vendorList;
    }
    
    public static function buildActionWrapper($path, $linkID, $showDelete = true, $extraLinks = null, $extraHtmlLinks = false, $showView = true){
    
        $extra = '';
    
        if(is_array($extraLinks)){
            foreach($extraLinks as $link ){
                $elink = '<i class="fa '.$link['ico'].'" style="width:15px"></i><span style="font-size: 14px;"> ' . $link['label'] . '</span>';
                $ehtml = Html::a($elink, $link['url'], ['class' => '']);
                $extra .= '<li>' . $ehtml .'</li>';
            }
        }
        if($extraHtmlLinks !== false){
            $extra .= $extraHtmlLinks;
        }
        $linkView = '';
        if($showView){
            $linkViewHTML =     '<i class="fa fa-eye" style="width:15px"></i><span style="font-size: 14px;"> View</span>';
            $linkView = Html::a($linkViewHTML, [$path.'/view', 'id'=>$linkID], ['class'=>'']);
        }
        $linkEdit = '';
        if(false){
            $linkEditHTML =     '<i class="fa fa-pencil" style="width:15px"></i><span style="font-size: 14px;"> Edit</span>';
            $linkEdit = Html::a($linkEditHTML, [$path.'/update', 'id'=>$linkID], ['class'=>'']);
        }
        $linkDelHTML =     '<i class="fa fa-trash" style="width:15px"></i><span style="font-size: 14px;"> Delete</span>';
        $linkDel = Html::a($linkDelHTML, [$path.'/delete', 'id'=>$linkID], ['class'=>'link-delete',/*'data-confirm' => "Are you sure you want to delete?", 'data-method'=>'post'*/]);
    
        $rest = '<a href="#" class="show-action"><i class="fa fa-cogs"></i> Actions</a>
                    <div class="pop-content" style="display: none">
                        <ul style="list-style-type: none; margin: 0; padding: 0;">
                            <li>' . $linkView . '</li>
                            <li>' . $linkEdit . '</li>
                            '. $extra;
    
        if($showDelete){
            $rest .=      '<li>' . $linkDel . '</li>';
        }
    
        $rest .=    '</ul></div>';
        return $rest;
    }
    
    public static function getDays(){
        return [0 => 'Sun',
            1 => 'Mon',
            2 => 'Tue',
            3 => 'Wed',
            4 => 'Thu',
            5 => 'Fri',
            6 => 'Sat',
            
        ];
    }
    public static function getOperatingTime(){
        $start=strtotime('00:00');
        $end=strtotime('24:00');
        $timeSlot = [];
        for ($i=$start;$i<=$end;$i = $i + 15*60)
        {
        
            //write your if conditions and implement your logic here
            $timeInfo = '';
            $timeDisplay = '';
            if($i == $end){
                $timeInfo =  '24:00';
                $timeDisplay = '12:00 am';
            }else{
                $timeInfo = date('H:i',$i);
                $timeDisplay = date('g:i a',$i);
        
            }
            $timeSlot[$timeInfo] = $timeDisplay;
        
        }
        return $timeSlot;
    }  
    public static function getTimeZoneDisplay($vendorTimeZone){
        foreach(self::getAvailableTimezones() as $key => $timezone){
            if($vendorTimeZone == $timezone['timezone']){
                return $timezone['textDisplay'];
            }
        }
        return '';
    }
    public static function getAvailableTimezones(){

        $return = array();
        $timezone_identifiers_list = timezone_identifiers_list();
        foreach($timezone_identifiers_list as $timezone_identifier){
            $date_time_zone = new \DateTimeZone($timezone_identifier);
            $date_time = new \DateTime('now', $date_time_zone);
            $hours = floor($date_time_zone->getOffset($date_time) / 3600);
            $mins = floor(($date_time_zone->getOffset($date_time) - ($hours*3600)) / 60);
            $hours = 'GMT' . ($hours < 0 ? $hours : '+'.$hours);
            $mins = ($mins > 0 ? $mins : '0'.$mins);
            $text = str_replace("_"," ",$timezone_identifier);
            $return[$timezone_identifier] = ['timezone' => $timezone_identifier, 'textDisplay' => '('.$hours.':'.$mins.') - '.$text, 'offset' => $date_time_zone->getOffset($date_time) ];
        }
        
        usort($return, function ($a, $b)
        {
            if($a['offset'] == $b['offset']){ return 0 ; }
            return ($a['offset'] < $b['offset']) ? -1 : 1;
        });
        
        return $return;
    }
    public static function getAppConfig($code, $defaultVal){
        $appConfig = AppConfig::findOne(['code' => $code]);
        if($appConfig != null){
            return $appConfig->val;
        }
        return $defaultVal;
    }
}