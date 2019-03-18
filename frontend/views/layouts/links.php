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
        $menuItems[] = '<li class="li-heigth-max-guest">'
            . '<button type="button" class="btn-profile-guest"'
            . 'data-container="body"'
            . 'data-toggle="popover"'
            . 'data-placement="bottom"'
            . 'data-content="'
            . '<div><a href=' .  Url::to(["/site/login"]) . ' data-method=' . 'post' . '>Login</a></div>'
            . '<div><a href=' .  Url::to(["/site/signup"]) . ' data-method=' . 'post' . '>Sign up</a></div>'
            . '">'
            . '<ion-icon name="help"></ion-icon>'
            . '</button>'
            . '</li>';
    } else if(!Yii::$app->user->identity->profile->admin){
        $menuItems[] = '<li class="li-heigth-max">'
            . '<button type="button" class="btn-profile"'
            . 'data-container="body"'
            . 'data-toggle="popover"'
            . 'data-placement="bottom"'
            . 'data-content="'
            . '<div><a href=' .  Url::to(["/site/profile"]) . "&id=" . Yii::$app->user->identity->id . "&type=trips_list" . ' data-method=' . 'post' . '>Show profile</a></div>'
            . '<div><hr></div>'
            . '<div><a href=' .  Url::to(["/post/create"]) . ' data-method=' . 'post' . '>Create post</a></div>'
            . '<div><a href=' .  Url::to(["/site/logout"]) . ' data-method=' . 'post' . '>Sign out</a></div>'
            . '">'
            . '<img src="/frontend/web/uploads/profile_avatar/' . Yii::$app->user->identity->profile->avatar . '" class="btn-profile-on-nav">'
            . '</button>'
            . '</li>';
    }else{
        $menuItems[] = '<li class="li-heigth-max">'
        . '<button type="button" class="btn-profile"'
        . 'data-container="body"'
        . 'data-toggle="popover"'
        . 'data-placement="bottom"'
        . 'data-content="'
        . '<div><a href=' .  Url::to(["/site/profile"]) . "&id=" . Yii::$app->user->identity->id . "&type=trips_list" . ' data-method=' . 'post' . '>Show profile</a></div>'
        . '<div><hr></div>'
        . '<div><a href=' .  Url::to(["/post/create"]) . ' data-method=' . 'post' . '>Create post</a></div>'
        . '<div><a href=' .  Url::to(["/site/users-list"]) . ' data-method=' . 'post' . '>Users list</a></div>'
        . '<div><a href=' .  Url::to(["/site/logout"]) . ' data-method=' . 'post' . '>Sign out</a></div>'
        . '">'
        . '<img src="/frontend/web/uploads/profile_avatar/' . Yii::$app->user->identity->profile->avatar . '" class="btn-profile-on-nav">'
        . '</button>'
        . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>