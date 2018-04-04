<?php


namespace app\extentions\components;

use yii\web\AssetBundle;

class iCheckAsset extends AssetBundle
{
    public $sourcePath = '@bower/gentelella/vendors/iCheck/';
    public $css = [
        'skins/all.css',
    ];
    public $js = [
        'icheck.min.js',
    ];
}