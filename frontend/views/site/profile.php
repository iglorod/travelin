<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */
/* @var $form ActiveForm */
?>
<div class="site-profile">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'first_name') ?>
        <?= $form->field($model, 'second_name') ?>
        <?= $form->field($model, 'middle_name') ?>
        <?= $form->field($model, 'birthday') ?>
        <?= $form->field($model, 'gender') ?>
        <?= $form->field($model, 'avatar') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-profile -->
