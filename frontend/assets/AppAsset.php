<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',               // jQuery ni yuklaydi
        'yii\bootstrap5\BootstrapAsset',  // Bootstrap CSS ni yuklaydi
        'yii\bootstrap5\BootstrapPluginAsset', // <--- MANA SHU KERAK (Dropdown ishlashi uchun JS)
    ];
}