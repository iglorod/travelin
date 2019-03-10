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
        'css/sweetalert2.min.css',
        'css/croppie.css',
        'https://fonts.googleapis.com/css?family=Roboto',
    ];
    public $js = [
        'js/my.js',
        'js/sweetalert2.min.js',
        'js/croppie.js',
        'https://unpkg.com/ionicons@4.2.2/dist/ionicons.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
