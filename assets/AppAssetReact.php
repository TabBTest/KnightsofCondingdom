<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAssetReact extends AssetBundle
{
    public $sourcePath = '@app/frontend/build';

    public $js = [
        'bundle.js'
    ];
}
