<?php

namespace app\assets;

use yii\web\AssetBundle;

class AlertConfirmAsset extends AssetBundle
{
    public $sourcePath = '@bower/sweetalert';
    public $css = [
        'dist/sweetalert.css',
        'themes/google/google.css',
    ];
    public $js = [
        'dist/sweetalert.min.js',
    ];
}
