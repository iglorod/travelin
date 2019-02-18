<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Post */

$this->title = 'Create Post';
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-create">

    <p class='text-center cool-font-title'>Create Post</p>
    <p class='text-center cool-font-untitle'>Create post and add on the map road of your trip with photos and comments.</p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
