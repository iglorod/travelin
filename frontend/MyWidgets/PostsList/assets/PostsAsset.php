<?php

namespace frontend\MyWidgets\PostsList\assets;

use yii\web\AssetBundle;

class PostsAsset extends AssetBundle {

    public $sourcePath = '@frontend/MyWidgets/PostsList/assets/src';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/slider.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}