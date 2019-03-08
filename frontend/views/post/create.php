<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use yii\bootstrap\Carousel;


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
            ['red', '<span class="label-red">red</span>'],
            ['green', '<span class="label-green">green</span>'],
            ['blue', '<span class="label-blue">blue</span>'],
        ],
        ],
    ])->label(false);
    ?>

    <div class="form-group">
        <span id="click-span-back">Turn Back</span>
        <span id="click-span-travel">Travel Path</span>
        <?= Html::submitButton('Post', ['class' => 'btn btn-success btn-create-post']) ?>
    </div>

    </div>

</div>
<?php ActiveForm::end(); ?>


<?php $form1 = ActiveForm::begin(); ?>

<?= $form1->field($model2, 'image')->fileInput(['maxlength' => true, 'class'=>'js-file-upload', 'style' => 'display: none;'])->label(false) ?>
<?= $form1->field($model2, 'marker_id')->textInput(['class' => 'form-control'])->label(false) ?>
<?= $form1->field($model2, 'image_id')->textInput(['class' => 'form-control'])->label(false) ?>

<?= Html::submitButton('Upload', ['class' => 'btn btn-upload-image']) ?>

<?php ActiveForm::end(); ?>


<div class="post-map-create">
  <p id="changer-city-text" class='text-center cool-font-title'>Create Path</p>
  <p class='text-center cool-font-untitle'>Start building your travel path and add your photos to them.</p>

  <div id="button-add-path" class="unpushed-button-add-path"><ion-icon id="ion" name="git-compare"></ion-icon></div>
  <div id="map-create"></div>
  <div class="click-span-travel-back-div">
    <span id="click-span-travel-back">Turn Back</span>
  </div>
</div>

<?php
$carousel = [
 [
 'content' => '<img class="slide-images-style" src="https://cdn.pixabay.com/photo/2016/12/01/19/44/gangster-1876017__340.jpg"/>',
 'caption' => '<input type="text" class="cool-input-for-slide" maxlength="22">',
 'options' => []
 ],
 [
 'content' => '<img class="slide-images-style" src="http://static-29.sinclairstoryline.com/resources/media/3adcd899-09c9-468a-a11e-9ad839192359-large16x9_1280x720_81012B00VMPWE.jpg?1540999477954"/>',
 'caption' => '',
 'options' => []
 ],
 [
 'content' => '<img class="slide-images-style" src="http://images4.fanpop.com/image/photos/17500000/cool-background-random-17506456-1869-1168.jpg"/>',
 'caption' => '',
 'options' => ['class' => 'my-class']
 ]
];
?>

<div class="slide-show">
<div id="slide-upload-image"><ion-icon name="add"></ion-icon></div>
<?php echo Carousel::widget([
  'items' => $carousel,
  'options' => ['class' => 'carousel slide', 'data-interval' => '12000'],
  'controls' => [
  '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>',
  '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>'
  ]
 ]);
?>

</div>

<script>
  var autocomplete;
function activeSearch(){
  document.getElementById
  var input = document.getElementById('post-id_place');
  autocomplete = new google.maps.places.Autocomplete(input);
}

function initialize() {
    activeSearch();
    initMapCreate();
}

function initMapCreate() {
  var map = new google.maps.Map(document.getElementById('map-create'), {
    zoom: 8,
    center: {lat: 40.72, lng: -73.96}
  });
  var geocoder = new google.maps.Geocoder;
  var infowindow = new google.maps.InfoWindow;

  var flightPlanCoordinates = [];

  geocodePlaceId(geocoder, map, infowindow);

  var id_marker_start = 0;

  map.addListener('click', function(e) {
    var button = document.getElementById('button-add-path');
    if(button.className == "pushed-button-add-path"){
        placeMarkerAndPanTo(e.latLng, map, id_marker_start);
        id_marker_start++;
        flightPlanCoordinates.push(e.latLng);
        if(flightPlanCoordinates.length > 1) buildRoad(flightPlanCoordinates, map);
        addInfo();
    }
  });
}

function placeMarkerAndPanTo(latLng, map, id_marker) {
        var marker = new google.maps.Marker({
          position: latLng,
          map: map,
          icon: "http://maps.google.com/mapfiles/ms/icons/purple-dot.png",
          id: id_marker
        });
        map.panTo(latLng);

        marker.addListener('click', function() {
          loadResource(marker.id);
        });
      }

// This function is called when the user clicks the UI button requesting
// a geocode of a place ID.
function geocodePlaceId(geocoder, map, infowindow) {
  if(typeof autocomplete.getPlace() === 'undefined') return;
  var placeId = autocomplete.getPlace()['place_id'];

  geocoder.geocode({'placeId': placeId}, function(results, status) {
    if (status === 'OK') {
      if (results[0]) {
        map.setZoom(12);
        map.setCenter(results[0].geometry.location);
       /* var marker = new google.maps.Marker({
          map: map,
          position: results[0].geometry.location
        });
        infowindow.setContent(results[0].formatted_address);
        infowindow.open(map, marker);*/
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

        flightPath.setMap(map);
}

</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBB2gXITRe0zhIOQVJ_fyOVMt965cq_gO4&types=(cities)&libraries=places&callback=initialize" type="text/javascript"></script>
<script src="https://unpkg.com/ionicons@4.2.2/dist/ionicons.js"></script>