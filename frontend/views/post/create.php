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

<?php $form = ActiveForm::begin(['options' => ['class'=>'create-post-form']]); ?>

<div class="city-post-create">
    <p class='text-center cool-font-title'>Specify City</p>
    <p class='text-center cool-font-untitle'>Indicate which city you are going to report. Select it from the dropdown list.</p>

    <div class="set-city-input">
            <?= $form->field($model, 'id_place')->textInput(['placeholder' => 'Start Typing Place Name...', 'class' => 'form-control create-combo-input'])->label(false) ?>
            
            <?= $form->field($model, 'main_place_text')->hiddenInput(['class'=>'hidden-main-place'])->label(false); ?>
            <?= $form->field($model, 'secondary_place_name')->hiddenInput(['class'=>'hidden-secondary-place'])->label(false); ?>

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

    <?= $form->field($model, 'result_markers')->hiddenInput(['class'=>'hidden-input-markers'])->label(false); ?>
    <?= $form->field($model, 'result_polilyne')->hiddenInput(['class'=>'hidden-input-polyline'])->label(false); ?>

    <div class="form-group">
        <span id="click-span-back">Turn Back</span>
        <span id="click-span-travel">Travel Path</span>
        <?= Html::submitButton('Post', ['class' => 'btn btn-success btn-create-post']) ?>
    </div>

    </div>
</div>

<?php ActiveForm::end(); ?>

<?php $form1 = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class'=>'add-image-create-post-form']]); ?>

<?= $form1->field($model2, 'image')->fileInput(['maxlength' => true, 'class'=>'js-file-upload', 'style' => 'display: none;'])->label(false) ?>

<?= Html::submitButton('Upload', ['class' => 'btn btn-upload-image', 'style' => 'display: none;']) ?>

<?php ActiveForm::end(); ?>

<div id="scroll-to-div" class="post-map-create">
  <p id="changer-city-text" class='text-center cool-font-title'>Create Path</p>
  <p class='text-center cool-font-untitle'>Start building your travel path and add your photos to them.</p>

  <div id="button-add-path" class="unpushed-button-add-path"><ion-icon id="ion" name="git-compare"></ion-icon></div>
  <div id="map-create"></div>
  <div class="click-span-travel-back-div">
    <span id="click-span-travel-back">Turn Back</span>
    <span id="click-span-travel-add-photos">Add Photos</span>
    <span id="click-span-travel-add-descript">Add Description</span>
    <span id="click-span-travel-scroll-to-photos"><ion-icon name="arrow-round-down"></ion-icon></span>
  </div>
</div>

<?php $carousel = []; ?>

<div class="slide-show">
<div id="slide-upload-image">
  <div class="col-xs-6 fl-right">
    <ion-icon name="cloud-upload" class="upload-image-icon"></ion-icon>
  </div>
  <div class="col-xs-6 fl-left">
    <ion-icon name="trash" class="remove-image-icon"></ion-icon>
  </div>
</div>

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

<div class="place-brief-descr row">
  <div class="title-decr-div col-xs-12 col-sm-5">
    <input type="text" class="title-decr-input form-control" placeholder="Make Up The Title" maxlength="50">
  </div>
  <div class="text-descr-div col-xs-12 col-sm-7">
    <textarea name="text" id="text-decr-input" class="form-control" rows="1" placeholder="Add Brief Description About This Stage"></textarea>
  </div>
</div>

<div id="uploadimageModal" class="modal" role="dialog">
	<div class="modal-dialog create-modal-dialog">
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
						  <button class="btn crop_image">Crop & Upload Image</button>
					</div>
				</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      		</div>
    	</div>
    </div>
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

function activeSearch(){
  var input = document.getElementById('post-id_place');

  var options = {
    types: ['(cities)']
  };

  autocomplete = new google.maps.places.Autocomplete(input, options);
}

function initialize() {
    activeSearch();
    //initMapCreate();
}

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

  map.addListener('click', function(e) {
    var button = document.getElementById('button-add-path');
    if(button.className == "pushed-button-add-path"){
        placeMarkerAndPanTo(e.latLng, map, id_marker_start, allMarkers);
        id_marker_start++;
        flightPlanCoordinates.push(e.latLng);
        if(flightPlanCoordinates.length > 1) buildRoad(flightPlanCoordinates, map);
    }
  });
}

function placeMarkerAndPanTo(latLng, map, id_marker, allMarkers) {
        var marker = new google.maps.Marker({
          position: latLng,
          map: map,
          icon: "http://maps.google.com/mapfiles/ms/icons/purple-dot.png",
          id: id_marker,
          animation: google.maps.Animation.DROP
        });
        console.log(marker);
        map.panTo(latLng);
        console.log(marker);
        allMarkers.push(marker);

        marker.addListener('click', function() {
          loadPrevMarker(map, allMarkers);
          loadResource(marker.id);
          loadCurrMarker(map, marker);
        });

        loadPrevMarker(map, allMarkers);
        createMarkerObj(id_marker, latLng);
        loadCurrMarker(map, marker);
      }

// This function is called when the user clicks the UI button requesting
// a geocode of a place ID.
function geocodePlaceId(geocoder, map, infowindow) {
  if(typeof autocomplete.getPlace() === 'undefined') return;
  placeId = autocomplete.getPlace()['place_id'];

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
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBB2gXITRe0zhIOQVJ_fyOVMt965cq_gO4&types=(cities)&libraries=places&callback=initialize" type="text/javascript"></script>