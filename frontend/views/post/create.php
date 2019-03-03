<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
/* @var $this yii\web\View */
/* @var $model frontend\models\Post */

$this->title = 'Create Post';
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(); ?>

<div class="city-post-create">
    <p class='text-center cool-font-title'>Specify City</p>
    <p class='text-center cool-font-untitle'>Indicate which city you are going to report. Select it from the dropdown list.</p>

    <div class="set-city-input">
            <?= $form->field($model, 'id_place')->textInput(['placeholder' => 'Start Typing Place Name...', 'class' => 'form-control create-combo-input'])->label(false) ?>
    </div>
    <span id="click-span">Confirm</span>
</div>

<div class="post-create">

    <p class='text-center cool-font-title'>Place Review</p>
    <p class='text-center cool-font-untitle'>Create post and add on the map road of your trip with photos and comments.</p>

    <div class="post-form">

    <?= $form->field($model, 'text')->widget(Widget::className(), [
        'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'imageUpload' => Url::to(['post/image-upload']),
        'plugins' => [
            'imagemanager',
            'clips',
            'fullscreen',
        ],
        'clips' => [
            ['Lorem ipsum...', 'Lorem...'],
            ['red', '<span class="label-red">red</span>'],
            ['green', '<span class="label-green">green</span>'],
            ['blue', '<span class="label-blue">blue</span>'],
        ],
        ],
    ])->label(false);
    ?>

    <span id="click-span-back">Click here</span>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-create-post']) ?>
    </div>


    </div>

</div>

<?php ActiveForm::end(); ?>

<script>
  var autocomplete;
function activeSearch(){
  document.getElementById
  var input = document.getElementById('post-id_place');
  autocomplete = new google.maps.places.Autocomplete(input);
}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBB2gXITRe0zhIOQVJ_fyOVMt965cq_gO4&types=(cities)&libraries=places&callback=activeSearch" type="text/javascript"></script>