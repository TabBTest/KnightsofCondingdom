<?php
namespace app\helpers;

class UtilityHelper {
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
}