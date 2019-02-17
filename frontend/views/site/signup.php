<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
?>
<div class="site-signup">
    <div>
        <div>
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            
                <?= $form->field($model, 'username')->textInput(['placeholder' => 'Username', 'class' => 'form-control sign-inputs'])->label(false) ?>

                <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email', 'class' => 'form-control sign-inputs'])->label(false) ?>

                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password', 'class' => 'form-control sign-inputs'])->label(false) ?>

                <div class="form-group">
                    <?= Html::submitButton('SIGNUP', ['class' => 'sign-btn', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
