<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use frontend\MyWidgets\PostsList\PostsAllOut;
use frontend\MyWidgets\AllRoadsWidget\AllRoads;
use frontend\MyWidgets\PostsList\assets\PostsAsset;

PostsAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\Profile */
/* @var $form ActiveForm */
?>
<div class="site-profile">

	<div class="container profile-background-data">
		<div>
			<div class="inl-blocks">
			<?php if($is_author) { ?>
			<div class="profile-circle-image upload-circle-image">
				<span class="change-avatar-span">Upload Image</span>
				<img src="/frontend/web/uploads/profile_avatar/<?= $model->avatar; ?>" class="avatar-circle-image" alt="Profile.img">
			</div>
			<?php } else{?>
			<div class="profile-circle-image">
				<img src="/frontend/web/uploads/profile_avatar/<?= $model->avatar; ?>" class="avatar-circle-image" alt="Profile.img">
			</div>
			<?php } ?>
			<div>
			<div class="profile-long-name"><span><?= $model->first_name; ?> <?= $model->second_name; ?> <?php if($model->prime){ ?> <ion-icon name="at" class="verify-icon"></ion-icon> <?php } ?></span></div>
				<div class="profile-username"><span><?= $model->user->username ?></span></div>
			</div>
			<div class="profile-static-data-div">
				<div class="profile-stat">
					<div class="profile-stat-title">Publications</div>
					<div class="profile-stat-data"><?= $model->user->getCountPosts() ?></div>
				</div>
			</div>
			<div class="profile-static-data-div">
				<div class="profile-stat">
					<div class="profile-stat-title">Followers</div>
					<div class="profile-stat-data subscribers-profile-count"><?= $model->user->getCountFollowers() ?></div>
				</div>		
			</div>
			<div class="profile-static-data-div">
				<div class="profile-stat">
					<div class="profile-stat-title">Following</div>
						<div class="profile-stat-data"><?= $model->user->getCountFollowing() ?></div>
					</div>
				</div>
			</div>

			<?php if($is_author) { ?>
			<div class="inl-blocks inl-blocks-button">
				<div>
					<button class="btn btn-edit-profile" data-toggle="modal" data-target="#profile-user-data"><span>Edit profile</span> <ion-icon name="browsers"></ion-icon></button>
				</div>
			</div>
			<?php } else if(!Yii::$app->user->isGuest && Yii::$app->user->identity->profile->admin == "0") { ?>
				<div class="inl-blocks inl-blocks-button">
				<div>
				<?php if($model->user->getIsUserFollowing()){?>
					<button class="btn btn-follow-profile btn-follow-profile-clicked" user="<?= $model->user->id ?>"><span>Unfollow user</span> <ion-icon name="person-add"></ion-icon></button>
				<?php }else{?>
					<button class="btn btn-follow-profile" user="<?= $model->user->id ?>"><span>Follow user</span> <ion-icon name="person-add"></ion-icon></button>
				<?php } ?>
				</div>
			</div>
			<?php }else if(Yii::$app->user->identity->profile->admin == "1") { ?>
				<div class="inl-blocks inl-blocks-button">
				<div>
				<?php if($model->user->profile->isBanned()){?>
					<button class="btn btn-ban-profile btn-banned-profile-clicked" profile="<?= $model->user->profile->id ?>"><span>Unban user</span> <ion-icon name="alert"></ion-icon></button>
				<?php }else{?>
					<button class="btn btn-ban-profile" profile="<?= $model->user->profile->id ?>"><span>Ban user</span> <ion-icon name="alert"></ion-icon></button>
				<?php } ?>
				</div>
			</div>
			<?php } ?>
		</div>
		<div class="profile-links">
			<a href="<?= Url::to(["/site/profile"]) ?>&id=<?= $id ?>&type=trips_list">
				<div class="<?php if($current_type == 'trips_list') echo 'profile-link-active'; ?>">
					<span>Trips</span>
				</div>
			</a>
			<a href="<?= Url::to(["/site/profile"]) ?>&id=<?= $id ?>&type=liked_list">
				<div class="<?php if($current_type == 'liked_list') echo 'profile-link-active'; ?>">
					<span>Liked</span>
				</div>
			</a>
			<a href="<?= Url::to(["/site/profile"]) ?>&id=<?= $id ?>&type=reposted_list">
				<div class="<?php if($current_type == 'reposted_list') echo 'profile-link-active'; ?>">
					<span>Reposts</span>
				</div>
			</a>
			<a href="<?= Url::to(["/site/profile"]) ?>&id=<?= $id ?>&type=travel_history">
				<div class="<?php if($current_type == 'travel_history') echo 'profile-link-active'; ?>">
					<span>Travel history</span>
				</div>
			</a>
		</div>
	</div>

	<?php if($current_type == 'trips_list' || $current_type == 'liked_list' || $current_type == 'reposted_list') {
		echo PostsAllOut::widget([
			'trips'			=> $trips,
			'pagination'	=> $pagination
		]);
	} else if($current_type == 'travel_history') { 
		echo AllRoads::widget([
			'trip_polilynes'	=> $polilynes,
			'image_maded'		=> $image_maded
		]);
	} ?>

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
