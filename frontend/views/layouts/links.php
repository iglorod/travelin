<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

?>
    <?php
    NavBar::begin([
        'brandUrl' => Yii::$app->homeUrl,
        'brandLabel' => "Travellln",
        'options' => [
            'class' => 'navbar-inverse ' . $navbar_class . ' navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'About', 'url' => ['/site/about']],
        ['label' => 'Contact', 'url' => ['/site/contact']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li class="li-heigth-max">'
            . '<button type="button" class="btn-profile"'
            . 'data-container="body"'
            . 'data-toggle="popover"'
            . 'data-placement="bottom"'
            . 'data-content="'
            . '<div><a href=' .  Url::to(["/site/profile"]) . ' data-method=' . 'post' . '>Show profile</a></div>'
            . '<div><hr></div>'
            . '<div><a href=' .  Url::to(["/post/create"]) . ' data-method=' . 'post' . '>Create post</a></div>'
            . '<div><a href=' .  Url::to(["/site/logout"]) . ' data-method=' . 'post' . '>Sign out</a></div>'
            . '">'
            . '<span class="glyphicon glyphicon-user"></span>'
            . '</button>'
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>