<?php use yii\helpers\Html; ?>

<div id="all-roads-map"></div>
<?= Html::hiddenInput('polilynes', $trip_polilynes, ['class'=>'hidden-all-polylines']); ?>

<div class="total-travel-user-info">
  <div class="col-xs-12 col-sm-4">
  <div class="back-total-info-user">
      <div><ion-icon name="map"></ion-icon> <span class="total-current-user-info total-km-distance">0 km</span></div>
      <div class="under-total-info">kilometers traveled</div>
    </div>
  </div>

  <div class="col-xs-12 col-sm-4">
    <div class="back-total-info-user">
      <div><ion-icon name="globe"></ion-icon> <span class="total-current-user-info total-countries-visited">0%</span></div>
      <div class="under-total-info">countries are visited</div>
    </div>
  </div>

  <div class="col-xs-12 col-sm-4">
    <div class="back-total-info-user">
      <div><ion-icon name="images"></ion-icon> <span class="total-current-user-info"><?= $image_maded ?> pcs</span></div>
      <div class="under-total-info">photos are taken</div>
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
  var totalDistance = 0;
  var stringWithCountry = "";
  var totalCountryCount;

function initialize() {
    //initMapCreate();
}

function initMapCreate() {
  map = new google.maps.Map(document.getElementById('all-roads-map'), {
    zoom: 2,
    center: {lat: 40.72, lng: -73.96}
  });
  var geocoder = new google.maps.Geocoder;
  var infowindow = new google.maps.InfoWindow;
  mapIsCreated = 1;
  buildRoad();

  getVisitedCountries();
}

function pushToString(val){
  stringWithCountry+= "'" + val + "',";
}

function onlyUnique(value, index, self) { 
    return self.indexOf(value) === index;
}

function isLast(){
  stringWithCountry = stringWithCountry.substring(0, stringWithCountry.length-1);
  var world_geometry = new google.maps.FusionTablesLayer({
  query: {
    select: 'geometry',
    from: '1N2LBk4JHwWpOY4d9fobIn27lfnZ5MDy-NoqqRpk',
    where: "ISO_2DIGIT IN (" + stringWithCountry + ")"
  },
  styles: [{
    polygonOptions: {
        fillColor: "#ffffff",
        strokeColor: "#982dfc",
        fillOpacity: ".1"
    }
  }],
  map: map,
  suppressInfoWindows: true
});

  var array_of_str = stringWithCountry.split(",");
  array_of_str = array_of_str.filter( onlyUnique );

  totalCountryCount = array_of_str.length;
  $('.total-countries-visited').text((100*totalCountryCount/252).toFixed(1) + "%");
}

function getVisitedCountries(){
  var countStart = 0;
  $.each(flightPlanCoordinates, function( index, value ) {
        $.getJSON('https://maps.googleapis.com/maps/api/geocode/json?latlng=' + value[0]['lat'] + ',' + value[0]['lng'] + '&key=AIzaSyBB2gXITRe0zhIOQVJ_fyOVMt965cq_gO4',
        function(data) {
          $.each(data.results[0].address_components, function( ind, component ) {
            if(component.types[0] == 'country'){
              pushToString(component.short_name);
              
              if((countStart+1) == flightPlanCoordinates.length) isLast();
              countStart++;
              return false;
            }
          });
        });
  });
}

function buildRoad(){
  var all_polilines = JSON.parse($('.hidden-all-polylines').val());
  
  $.each(all_polilines, function( index, value ) {
    flightPlanCoordinates.push(value);
  });

  $('.hidden-all-polylines').remove();

  bildPolilynes();
  $('.total-km-distance').text(totalDistance.toFixed(3) + ' km');
}

function bildPolilynes(){
  var lineSymbol = {
    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
  };

  $.each(flightPlanCoordinates, function( index, value ) {
  var flightPath = new google.maps.Polyline({
          path: value,
          icons: [{
            icon: lineSymbol,
            offset: '100%'
          }],
          geodesic: true,
          strokeColor: '#ff0f0f',
          strokeOpacity: 1,
          strokeWeight: 2
        });

        flightPath.setMap(map);
  
        animateCircle(flightPath);
        getAllDistance(value);
  })

}

getAllDistance = function(points){
  var ind = 0;
  $.each(points, function( index, value ) {
    if(index+1 == points.length) return false;
    totalDistance += getDistance(value, points[index+1]);
  })
}

rad = function(x) {
  return x * Math.PI / 180;
};

getDistance = function(p1, p2) {
  var R = 6378137; // Earth’s mean radius in meter
  var dLat = rad(p2.lat - p1.lat);
  var dLong = rad(p2.lng - p1.lng);
  var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(rad(p1.lat)) * Math.cos(rad(p2.lat)) *
    Math.sin(dLong / 2) * Math.sin(dLong / 2);
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  var d = R * c;
  return d/1000; // returns the distance in meter
};

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
<script/* async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBB2gXITRe0zhIOQVJ_fyOVMt965cq_gO4&&callback=initialize" type="text/javascript">*/</script>