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

<div class="wrap-sign">

<div class="container-fluid row">
    <div class="hidden-xs col-sm-2 col-md-2 col-lg-3"></div>
    <div class="middle-sign col-xs-12 col-sm-8 col-md-8 col-lg-6 row">
        <div class="top-sign-picture col-xs-12"><img class="setka-pict" src="/frontend/web/uploads/setka.png" alt="SETKA" width="60"><a href="index.php">TRAVElllN</a></div>
        <div class="col-xs-12 col-md-8">
            <?= $content ?>
        </div>
        <div class="hidden-xs col-md-4 img-and-span-right">
            <img class="above-sign-span" src="/frontend/web/uploads/<?= $this->params['sign_type'] ?>.png" alt="SETKA" width="160">
            <span class="under-sign-img">Please fill out the following fields to <?= $this->params['sign_type'] ?>.</span>
        </div>
    </div>
    <div class="hidden-xs col-sm-2 col-md-2 col-lg-3"></div>
</div>

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
