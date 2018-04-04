<?php


namespace app\extentions\components;


use yiister\gentelella\assets\Asset;

class AssetBundler extends Asset
{
    public $depends = [
        'yiister\gentelella\assets\ThemeAsset',
        'yiister\gentelella\assets\ExtensionAsset',
        iCheckAsset::class,
        CmsBundle::class
    ];
}