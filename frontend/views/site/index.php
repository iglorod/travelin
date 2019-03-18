<?php
 header("Access-Control-Allow-Origin: *");
use yii\bootstrap\Modal;
use frontend\MyWidgets\PostsList\PostsAllOut;
use frontend\MyWidgets\PostsList\assets\PostsAsset;
use yii\helpers\Html;
use kartik\range\RangeInput;
use yii\bootstrap\Button;
use yii\helpers\Url;

PostsAsset::register($this);

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<a href="" class="search-button-action-click"></a>
<div class="additional-searching">
  <div class="container additional-searching-background">
    <div class="nearby-searching-content">
      <div class="col-xs-12 col-md-3 button-search-nearby">
        <?= Html::textInput('searchNearby', "", ['id' => 'search-nearby-input', 'class' => 'form-control nearby-place-search-input', 'placeholder' => 'Start to type your address...']); ?>
        <div class="search-range-widget">
        <?php 
        echo RangeInput::widget([
          'name' => 'range_1',
          'value' => 300,
          'html5Container' => ['style' => 'width:50%'],
          'html5Options' => ['min' => 300, 'max' => 3000],
          'addon' => ['append' => ['content' => 'm']]
        ]);
        ?>
        </div>
        <div class="search-button-div"><a class="search-button">Search</a><a href="" class="search-button-action-click"></a></div>
      </div>
      <div class="col-xs-12 col-md-9">
        <div class="checking-search-parameters col-xs-12 col-sm-2">
          <div><ion-icon name="logo-model-s"></ion-icon></div>
          <div class="sub-search-nerby-text" type="taxi_stand">Taxi</div>
        </div>
        <div class="checking-search-parameters col-xs-12 col-sm-2">
          <div><ion-icon name="airplane"></ion-icon></div>
          <div class="sub-search-nerby-text" type="airport">Airport</div>
        </div>
        <div class="checking-search-parameters col-xs-12 col-sm-2">
          <div><ion-icon name="cafe"></ion-icon></div>
          <div class="sub-search-nerby-text" type="cafe">Cafe</div>
        </div>
        <div class="checking-search-parameters col-xs-12 col-sm-2">
          <div><ion-icon name="pulse"></ion-icon></div>
          <div class="sub-search-nerby-text" type="hospital">Hospital</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="modal-default" style="display: none; padding-right: 17px;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header row">
                <div class="form-group col-xs-11 search-place-div">
                    <input id="search_autocomplete_input" class="form-control search-place-input" type="text" placeholder="Where to?">
                  </div>
                <div class="col-xs-1">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="close-modal-span" aria-hidden="false">×</span>
                  </button>
                </div>
              </div>
              <div class="modal-body">
              <?php if(count($recently_searched) > 0) { ?>
                <div class="cookie-city">
                  <div class="recent-search">
                    <span>RECENTLY SEARCHED</span>
                  </div>
                  <?php foreach($recently_searched as $history) { ?>
                  <a href="index.php?r=site/searching&place_id=<?= $history->place_id ?>">
                  <div class="searched-city">
                    <span><?= $history->main_text ?></span>
                    <span class="city-parent"><?= $history->secondary_text ?></span>
                  </div>
                  </a>
                  <?php } ?>
                </div>
              <?php } ?>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
</div>
    <?php
    if(count($trips) == 0){ ?>        
      <div class="img-message-to-user"><img src="uploads/not_found_posts.png" alt=""></div>
    <?php }else { 
    echo PostsAllOut::widget([
			'trips'			=> $trips,
			'pagination'	=> $pagination
		]);
    }?>

<script>
function activeSearch(){
  var input = document.getElementById('search-nearby-input');
  autocomplete = new google.maps.places.Autocomplete(input);
}

$(function() {
  $('.input-group .form-control').attr('style', 'pointer-events:none;');
})

</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBB2gXITRe0zhIOQVJ_fyOVMt965cq_gO4&types=(cities)&libraries=places&callback=activeSearch" type="text/javascript"></script>