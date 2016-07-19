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
        'css/jquery-ui.min.css',
        'css/bootstrap-switch.min.css',
        'css/site.css',
    ];

    public $js = [
        'js/jquery-ui.min.js',
        'js/jquery.bootpag.min.js',
        'js/bootstrap-switch.min.js',
        'js/clipboard.min.js',
        'js/ga.js',
        'js/app.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\assets\BootstrapMaterialDesignAsset',
        'app\assets\AlertConfirmAsset',
    ];
}
