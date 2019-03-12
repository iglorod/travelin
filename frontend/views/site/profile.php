<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */
/* @var $form ActiveForm */
?>
<div class="site-profile">

	<div class="container profile-background-data">
		<div>
			<div class="inl-blocks">
			<div class="profile-circle-image">
				<span class="change-avatar-span">Upload Image</span>
				<img src="/frontend/web/uploads/profile_avatar/<?= $model->avatar; ?>" class="avatar-circle-image" alt="Profile.img">
			</div>
			<div>
				<div class="profile-long-name"><span><?= $model->first_name; ?> <?= $model->second_name; ?></span></div>
				<div class="profile-username"><span><?= Yii::$app->user->identity->username ?></span></div>
			</div>
			<div>
				<div class="profile-stat">
					<div class="profile-stat-title">Publications</div>
					<div class="profile-stat-data">3</div>
				</div>
			</div>
			<div>
				<div class="profile-stat">
					<div class="profile-stat-title">Subscribers</div>
					<div class="profile-stat-data">3</div>
				</div>		
			</div>
				<div>
					<div class="profile-stat">
						<div class="profile-stat-title">Subscribed</div>
						<div class="profile-stat-data">3</div>
					</div>
				</div>
			</div>

			<div class="inl-blocks inl-blocks-button">
				<div>
					<button class="btn btn-edit-profile" data-toggle="modal" data-target="#profile-user-data"><span>Edit profile</span> <ion-icon name="browsers"></ion-icon></button>
				</div>
			</div>
		</div>
		<div class="profile-links">
			<a href="#">
				<div>
					<span>Trips</span>
				</div>
			</a>
			<a href="#">
				<div>
					<span>Liked</span>
				</div>
			</a>
			<a href="#">
				<div>
					<span>Reposts</span>
				</div>
			</a>
			<a href="#">
				<div>
					<span>Travel history</span>
				</div>
			</a>
		</div>
	</div>

	<div id="uploadBackImage" class="modal" role="dialog">
		<div class="modal-dialog profile-modal-dialog">
			<div class="modal-content">
      			<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal">&times;</button>
        			<h4 class="modal-title">Upload & Crop Image</h4>
      			</div>
      			<div class="modal-body">
        			<div class="row">
  						<div class="col-xs-12 text-center">
							<div id="image_demo" style="width:100%; margin-top:30px"></div>
  						</div>
  						<div class="col-xs-12 button-crop-image" style="padding-top:30px;">
							<button class="btn crop-back-profile">Crop & Upload Image</button>
						</div>
					</div>
      			</div>
      			<div class="modal-footer">
        			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      			</div>
    		</div>
    	</div>
	</div>

	<div id="uploadAvatarImage" class="modal" role="dialog">
		<div class="modal-dialog create-modal-dialog">
			<div class="modal-content">
      			<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal">&times;</button>
        			<h4 class="modal-title">Upload & Crop Image</h4>
      			</div>
      			<div class="modal-body">
        			<div class="row">
  						<div class="col-xs-12 text-center">
							<div id="avatar_demo" style="width:100%; margin-top:30px"></div>
  						</div>
  						<div class="col-xs-12 button-crop-image" style="padding-top:30px;">
							<button class="btn crop-back-avatar">Crop & Upload Image</button>
						</div>
					</div>
      			</div>
      			<div class="modal-footer">
        			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      			</div>
    		</div>
    	</div>
	</div>

	<div class="modal fade" id="profile-user-data">
    	<div class="modal-dialog">
      		<div class="modal-content">

        		<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
          			<h4 class="modal-title modal-title-info-update">Edit profile info</h4>
        		</div>
        
        		<div class="modal-body">
					<div class="row">
						<div class="col-xs-2"></div>
						<div class="col-xs-8">
							<?php $form = ActiveForm::begin(); ?>
							<?= $form->field($model, 'background_url')->fileInput(['maxlength' => true, 'class'=>'profile-js-file-upload', 'style' => 'display: none;'])->label(false) ?>
							<?= $form->field($model, 'first_name')->textInput(['placeholder' => 'Name', 'class' => 'form-control sign-inputs'])->label(false) ?>
							<?= $form->field($model, 'second_name')->textInput(['placeholder' => 'Surname', 'class' => 'form-control sign-inputs'])->label(false) ?>
							<?= $form->field($model, 'middle_name')->textInput(['placeholder' => 'Patronymic', 'class' => 'form-control sign-inputs'])->label(false) ?>
							<? echo $form->field($model, 'birthday')->widget(DatePicker::classname(), [
								'options' => ['placeholder' => 'Birthday', 'class'=>'sign-inputs'],
								'removeButton' => false,
								'pluginOptions' => [
									'autoclose' => true,
									'todayHighlight' => true,
									'format' => 'dd-mm-yyyy'
									]
									])->label(false);
									?>
							<?php 
							$items = [
								0 => 'Female',
								1 => 'Male',
							]
							?>
							<?= $form->field($model, 'gender')->dropDownList($items, ['class' => 'form-control sign-inputs'])->label(false) ?>
							<?= $form->field($model, 'avatar')->fileInput(['maxlength' => true, 'class'=>'avatar-js-file-upload', 'style' => 'display: none;'])->label(false) ?>
							<div class="form-group">
								<?= Html::submitButton('Update', ['class' => 'sign-btn']) ?>
							</div>
							<?php ActiveForm::end(); ?>
						</div>
						<div class="col-xs-2"></div>
					</div>
				</div>				
				
				<div class="modal-footer">
					<button type="button" class="btn btn-close-modal-profile-info" data-dismiss="modal">Close</button>
				</div> 
			</div>
		</div>
	</div>
	
</div>

<!-- site-profile -->
