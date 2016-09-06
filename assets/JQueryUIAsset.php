<?php

namespace app\assets;

use yii\web\AssetBundle;

class JQueryUIAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-ui';

    public $js = [
        'jquery-ui.min.js'
    ];
}