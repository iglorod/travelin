<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use yii\bootstrap\Carousel;


$this->title = 'Trip Report';
?>

<?php $form = ActiveForm::begin(['options' => ['class'=>'create-post-form']]); ?>

<div class="post-view">

    <p class='text-center cool-font-title'>Place Report</p>
    <p class='text-center cool-font-untitle'>This is created post with added on the map road of trip, with photos and comments.</p>

    <div class="post-form">

    <div class="col-sm-offset-1 col-sm-10  col-md-offset-2 col-md-8 background-post-list">
            <div class="profile-standart-data">
                <a href="<?= Url::to(["/site/profile"]) ?>&id=<?= $model->author->id ?>&type=trips_list">
                    <div>
                        <img src="/frontend/web/uploads/profile_avatar/<?= $model->author->profile->avatar ?>" alt="Profile.img">
                    </div>
                    <div class="post-author-own-data">
                        <div class="post-author-full-name"><?= $model->author->profile->first_name ?> <?= $model->author->profile->second_name ?> <span class="post-type-text">made a report</span></div>
                        <div class="post-update-date"><?= date("M. d",$model->updated_at) ?></div>
                    </div>
                </a>
                <div class="action-menu-post">
                    <button type="button" class="simple-popover-button" 
                    data-container="body"
                    data-toggle="popover" 
                    data-placement="bottom"
                    data-content="
                    <?php if($model->author->id == Yii::$app->user->identity->id) echo '<div><a href=' . Url::to(["/post/update"]) . '&id=' . $model->id . '>Update</a></div>'; ?>
                    <?php if($model->author->id == Yii::$app->user->identity->id) echo '<div><a href=' . Url::to(["/post/delete"]) . '>Delete</a></div>'; ?>
                    <?php if($model->author->id != Yii::$app->user->identity->id || Yii::$app->user->isGuest) echo '<div><a href=' . Url::to(["/post/repost"]) . '>Repost</a></div>'; ?>
                    ">
                    <ion-icon name="more"></ion-icon>
                    </button>
                </div>
            </div>
            <div class="post-standart-data-view">
              <?= $model->text ?>
            </div>
      </div>


    <?= $form->field($model, 'id_place')->hiddenInput(['class'=>'hidden-input-id-place'])->label(false); ?>
    <?= $form->field($model, 'polilynes')->hiddenInput(['class'=>'hidden-input-polyline-view'])->label(false); ?>


    <?= Html::hiddenInput('markers', $markers, ['class'=>'hidden-input-markers']); ?>
    <?= Html::hiddenInput('images', $images, ['class'=>'hidden-input-images']); ?>


    <div class="form-group text-center col-xs-12">
        <span id="click-span-view-travel">Travel Path</span>
    </div>

    </div>
</div>

<div id="scroll-to-div" class="post-map-create">
  <p id="changer-city-text" class='text-center cool-font-title'>Created Path</p>
  <p class='text-center cool-font-untitle'>Builded travel path with added photos and detail rewiev of interested place.</p>

  <div id="map-create"></div>
  <div class="click-span-travel-back-div">
    <span id="click-span-travel-back-view">Turn Back</span>
    <span id="click-span-travel-add-photos-view">Watch Photos</span>
    <span id="click-span-travel-scroll-to-photos"><ion-icon name="arrow-round-down"></ion-icon></span>
  </div>
</div>

<?php $carousel = []; ?>

<div class="slide-show">
<?php echo Carousel::widget([
  'items' => $carousel,
  'options' => ['class' => 'carousel slide', 'data-interval' => 'false'],
  'controls' => [
  '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>',
  '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>'
  ]
 ]);
?>
</div>

<script>
  var map;
  var placeId;
  var intervalId;
  var autocomplete;
  var mapIsCreated = 0;
  var allMarkers = [];
  var flightPathArray= [];
  var flightPlanCoordinates = [];

function initMapCreate() {
  map = new google.maps.Map(document.getElementById('map-create'), {
    zoom: 8,
    center: {lat: 40.72, lng: -73.96}
  });
  var geocoder = new google.maps.Geocoder;
  var infowindow = new google.maps.InfoWindow;
  mapIsCreated = 1;

  geocodePlaceId(geocoder, map, infowindow);

  var id_marker_start = 0;

  refreshMarkerObj();
}

function placeMarkerAndPanTo(latLng, map, id_marker, allMarkers) {
        var marker = new google.maps.Marker({
          position: latLng,
          map: map,
          icon: "http://maps.google.com/mapfiles/ms/icons/purple-dot.png",
          id: id_marker
        });
        map.panTo(latLng);
        allMarkers.push(marker);

        marker.addListener('click', function() {
          loadPrevMarker(map, allMarkers);
          loadResource(marker.id);
          loadCurrMarker(map, marker);
        });

        loadPrevMarker(map, allMarkers);
        loadCurrMarker(map, marker);
}

function placeMarkerAndPanToView(latLng, map, id_marker, allMarkers) {
        var marker = new google.maps.Marker({
          position: latLng,
          map: map,
          icon: "http://maps.google.com/mapfiles/ms/icons/purple-dot.png",
          id: id_marker
        });
        map.panTo(latLng);
        allMarkers.push(marker);

        marker.addListener('click', function() {
          loadPrevMarker(map, allMarkers);
          loadResource(marker.id);
          loadCurrMarkerForView(map, marker);
        });
}

function bildPolilynes(){
  var lineSymbol = {
    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
  };
  var flightPath = new google.maps.Polyline({
          path: flightPlanCoordinates,
          icons: [{
            icon: lineSymbol,
            offset: '100%'
          }],
          geodesic: true,
          strokeColor: '#982dfc',
          strokeOpacity: 1.0,
          strokeWeight: 2
        });

        flightPath.setMap(map);
  
        window.clearInterval(intervalId);
        animateCircle(flightPath);
}

// This function is called when the user clicks the UI button requesting
// a geocode of a place ID.
function geocodePlaceId(geocoder, map, infowindow) {
  placeId = $('.hidden-input-id-place').val();

  geocoder.geocode({'placeId': placeId}, function(results, status) {
    if (status === 'OK') {
      if (results[0]) {
        map.setZoom(12);
        map.setCenter(results[0].geometry.location);
      } else {
        window.alert('No results found');
      }
    } else {
      window.alert('Geocoder failed due to: ' + status);
    }
  });
}

function buildRoad(values, map){

  var lineSymbol = {
    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
};

  var flightPath = new google.maps.Polyline({
          path: values,
          icons: [{
            icon: lineSymbol,
            offset: '100%'
          }],
          geodesic: true,
          strokeColor: '#982dfc',
          strokeOpacity: 1.0,
          strokeWeight: 2
        });

        flightPathArray.push(flightPath);

        flightPath.setMap(map);

        window.clearInterval(intervalId);
        animateCircle(flightPath);
}

function animateCircle(line) {
          var count = 0;
          intervalId = window.setInterval(function() {
            count = (count + 1) % 200;

            var icons = line.get('icons');
            icons[0].offset = (count / 2) + '%';
            line.set('icons', icons);
        }, 20);
}

</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBB2gXITRe0zhIOQVJ_fyOVMt965cq_gO4&types=(cities)&libraries=places" type="text/javascript"></script>