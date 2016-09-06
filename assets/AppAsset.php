<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css',
        'https://fonts.googleapis.com/css?family=Open+Sans:400,700',
        'css/bootstrap-switch.min.css',
        'css/bootstrap-datepicker3.min.css',
        'css/bootstrap-timepicker.min.css',
        'css/site.css',
    ];

    public $js = [
        'js/bootstrap-datepicker.js',
        'js/jquery.bootpag.min.js',
        'js/bootstrap-switch.min.js',
        'js/clipboard.min.js',
        'js/bootstrap-timepicker.js',
        'js/ga.js',
        '/js/jquery.payment.min.js',
        '/js/jquery.mask.js',
        'js/app.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'app\assets\JQueryUIAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\assets\BootstrapMaterialDesignAsset',
        'app\assets\AlertConfirmAsset',
    ];
}
