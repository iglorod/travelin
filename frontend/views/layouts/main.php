<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">

<div id="back-top" class="container">
<?= $this->render('links') ?>
<div class="scroll-bar"></div>
<div class="text-center row middle-cont">
    <div class="col-12 col-lg-12">
        <img class="setka-pict" src="/frontend/web/uploads/setka.png" alt="SETKA" width="130">
    </div>
    <div class="col-12 col-lg-12">
        <div class="search-posts">
            <span class="text-search">Where to?</span>
            <span class="glyphicon glyphicon-stats span-icon"></span>
        </div>
    </div>
</div>
</div>

<div class="container">
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <?= Alert::widget() ?>
    <?= $content ?>
</div>

</div>
<footer class="big-footer">
    <div class="container row">
        <div class="col-12 col-lg-4">
            <a href="https://www.gofundme.com/"><p class="text-left font-footer"><img src="/frontend/web/uploads/haski.png" alt="asd" width="50" style="border-radius: 50%;"> Нагодуй мене</p></a>
        </div>
        <div class="col-12 col-lg-4">
        <p class="text-justify font-footer">Nisl pretium fusce id velit ut. Turpis massa sed elementum tempus. A arcu cursus vitae congue mauris rhoncus aenean vel. Amet mauris commodo quis imperdiet. Arcu cursus euismod quis viverra. Ipsum dolor sit amet consectetur adipiscing elit. Diam donec adipiscing tristique risus nec feugiat in. Nascetur ridiculus mus mauris vitae ultricies leo integer. Suspendisse ultrices gravida dictum fusce ut. Tempus iaculis urna id volutpat lacus. Tempus egestas sed sed risus pretium quam vulputate dignissim suspendisse. Ut lectus arcu bibendum at varius vel pharetra vel turpis.</p>
        </div>
        <div class="col-12 col-lg-4">
        <p class="text-justify font-footer">Nisl pretium fusce id velit ut. Turpis massa sed elementum tempus. A arcu cursus vitae congue mauris rhoncus aenean vel. Amet mauris commodo quis imperdiet. Arcu cursus euismod quis viverra. Ipsum dolor sit amet consectetur adipiscing elit. Diam donec adipiscing tristique risus nec feugiat in. Nascetur ridiculus mus mauris vitae ultricies leo integer. Suspendisse ultrices gravida dictum fusce ut. Tempus iaculis urna id volutpat lacus. Tempus egestas sed sed risus pretium quam vulputate dignissim suspendisse. Ut lectus arcu bibendum at varius vel pharetra vel turpis.</p>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
