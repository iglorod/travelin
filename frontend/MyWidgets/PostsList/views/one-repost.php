<?php 
use yii\helpers\Url;
use yii\helpers\Html;;
?>

<div class="col-sm-offset-1 col-sm-10  col-md-offset-2 col-md-8 background-post-list">
            <div class="profile-standart-data">
                <a href="<?= Url::to(["/site/profile"]) ?>&id=<?= $model->user->id ?>&type=trips_list">
                    <div>
                        <img src="/frontend/web/uploads/profile_avatar/<?= $model->user->profile->avatar ?>" alt="Profile.img">
                    </div>
                    <div class="post-author-own-data">
                        <div class="post-author-full-name"><?= $model->user->profile->first_name ?> <?= $model->user->profile->second_name ?> <span class="post-type-text">reposted a trip</span></div>
                        <div class="post-update-date"><?= date("M. d",$model->created_at) ?></div>
                    </div>
                </a>
                <div class="action-menu-post">
                    <button type="button" class="simple-popover-button" 
                    data-container="body"
                    data-toggle="popover" 
                    data-placement="bottom"
                    data-content="
                    <?php if($model->user->id == Yii::$app->user->identity->id) echo '<div><a href=' . Url::to(["/post/delete"]) . '>Delete</a></div>'; ?>
                    <?/*php if($model->author->id != Yii::$app->user->identity->id || Yii::$app->user->isGuest) echo '<div><a href=' . Url::to(["/post/repost"]) . '>Repost</a></div>'; */?>
                    ">
                    <ion-icon name="more"></ion-icon>
                    </button>
                </div>
            </div>
            <hr>
            <div class="profile-standart-data under-repost-standart-data">
                <?php 
                    $model_start = $model;
                    $model = $model->post; 
                ?>
                <a href="<?= Url::to(["/site/profile"]) ?>&id=<?= $model->author->id ?>&type=trips_list">
                    <div>
                        <img src="/frontend/web/uploads/profile_avatar/<?= $model->author->profile->avatar ?>" alt="Profile.img">
                    </div>
                    <div class="post-author-own-data">
                        <div class="post-author-full-name"><?= $model->author->profile->first_name ?> <?= $model->author->profile->second_name ?> <span class="post-type-text"> is report author</span></div>
                        <div class="post-update-date"><?= date("M. d",$model->updated_at) ?></div>
                    </div>
                </a>
            </div>
            <div class="post-standart-data">
                <a href="<?= Url::to(["/post/view"]) ?>&id=<?= $model->id ?>">
                    <?= getImage($model->text) ?>
                </a>
            </div>
            <div class="footer-standart-data">
            <?php
                $array_markers = array();
                foreach($model->markers as $marker){
                    $curr_marker = [
                        "lat" => $marker["lat"],
                        "lng" => $marker["lng"],
                    ];
                    array_push($array_markers, $curr_marker);
                }

                if(count($array_markers) > 0)  { ?>
                
                    <div class="autoplay post-autoplay-<?= $model->id ?>">
                    </div>
                    <?//php echo '<script>getPlacesOfInterest(' . json_encode($array_markers) . ',' . $model->id . ');</script>';?>           
                    <hr>
                <?php } ?>

                <div class="post-availible-actions">
                    <div class="post-attributes post-attributes-data">
                        <div>Likes <span><?= $model_start->getRepostLikesCount() ?></span></div>
                        <div>Reposts <?= $model->getRepostsCount() ?></div>
                        <div class="other-review-small"><a href="">other reviews</a></div>
                    </div>
                    <hr>
                    <div class="post-attributes post-attributes-actions">
                        <?php if(Yii::$app->user->isGuest) { ?>
                        <div>
                            <a href="<?= Url::to(["/site/sign-up"]) ?>">Like <ion-icon name="thumbs-up"></ion-icon></a>
                        </div>
                        <div>
                            <a href="<?= Url::to(["/site/sign-up"]) ?>">Repost <ion-icon name="repeat"></ion-icon></a>
                        </div>
                        <?php }else{ ?>
                        <div>
                            <span class="like_repost <?= $model_start->getIsUserLikedRepost() ?>" post="<?= $model_start->id ?>">Like <ion-icon name="thumbs-up"></ion-icon></span>
                        </div>
                        <div>
                        <span class="repost_post <?= $model->getIsUserReposted() ?>" post="<?= $model->id ?>" <?php if(!$model->getIsUserReposted()){ ?>data-toggle="modal" data-target="#reposting-post" <?php } ?>>Repost <ion-icon name="repeat"></ion-icon></span>
                        </div>
                        <?php } ?>
                        <div>
                        <a 
                        tabindex="0" 
                        role="button" 
                        class="toggle-popover-link"
                        post_id="<?= $model->id ?>"
                        data-toggle="popover" 
                        data-trigger="focus"
                        data-container="body"
                        data-content='<span class="copy-share-link">Copy link</span>'>Share <ion-icon name="share-alt"></ion-icon>
                        </a>
                        </div>
                    </div>
                </div>
                
                </div>
        </div>
