<?php

namespace app\assets;

use yii\web\AssetBundle;

class BootstrapMaterialDesignAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-material-design/dist';
    public $css = [
        'css/bootstrap-material-design.min.css',
        'css/ripples.min.css',
    ];
    public $js = [
        'js/material.min.js',
        'js/ripples.min.js',
    ];
}
