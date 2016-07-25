<?php

namespace app\assets;

use yii\web\AssetBundle;

class BootstrapMaterialDesignAsset extends AssetBundle
{
    public $sourcePath = '@app/frontend/gulp-build';
    public $css = [
        'css/bootstrap-material-design.min.css',
        'css/ripples.min.css',
    ];
    public $js = [
        'js/ripples.min.js',
        'js/material.min.js',
    ];
}
