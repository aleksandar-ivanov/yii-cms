<?php


namespace app\extentions\components;


use yii\web\AssetBundle;

class CmsBundle extends AssetBundle
{
    public $baseUrl = '/';

    public $js = [
        'js/cms.js'
    ];

    public $css = [
        'css/cms.css',
    ];
}