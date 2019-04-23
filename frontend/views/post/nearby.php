<?php 
use yii\helpers\Html;

$this->title = 'Search Nearby';
?>

<?= Html::hiddenInput('type', $type, ['class'=>'hidden-input-type']); ?>
<?= Html::hiddenInput('lat', $lat, ['class'=>'hidden-input-lat']); ?>
<?= Html::hiddenInput('lng', $lng, ['class'=>'hidden-input-lng']); ?>
<?= Html::hiddenInput('radius', $radius, ['class'=>'hidden-input-radius']); ?>

<div class="stat-nearby-result row">
  <div class="col-xs-12 col-sm-4">
  <div class="back-total-results">
      <div><ion-icon name="funnel"></ion-icon> <span class="total-current-nearby-info total-result-count">0 pcs</span></div>
      <div class="under-total-info">result count</div>
    </div>
  </div>

  <div class="col-xs-12 col-sm-4">
    <div class="back-total-results">
      <div><ion-icon name="open"></ion-icon> <span class="total-current-nearby-info total-open-now">0 pcs</span></div>
      <div class="under-total-info">open now</div>
    </div>
  </div>

  <div class="col-xs-12 col-sm-4">
    <div class="back-total-results">
      <div><ion-icon name="star-outline"></ion-icon> <span class="total-current-nearby-info total-avg-rating">0 st</span></div>
      <div class="under-total-info">average rating</div>
    </div>
  </div>
</div>

<div id="append-to-nearby">
</div>

<div class="modal fade" id="view-map-nearby" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header grey-modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title modal-title-repost-post"> <ion-icon name="globe"></ion-icon> Road to place</h4>
      </div>
      <div class="modal-body body-slide-show">
        <div id="map-create-nearby"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript">
    var map;
    var allMarkers = [];
    var flightPath;
    var flightPlanCoordinates = [];

    var type = $('.hidden-input-type').val();
    var lat = $('.hidden-input-lat').val();
    var lng = $('.hidden-input-lng').val();
    var radius = $('.hidden-input-radius').val();

    function initMapCreate() {
        map = new google.maps.Map(document.getElementById('map-create-nearby'), {
        zoom: 8,
        center: {lat: 40.72, lng: -73.96}
    });
    var geocoder = new google.maps.Geocoder;
    var infowindow = new google.maps.InfoWindow;
    }

    $(document).on("click", ".back-nearby-result", function() {
        $.each(allMarkers, function( ind, result ) {
            result.setMap(null);
        })

        if(typeof flightPath != 'undefined') flightPath.setMap(null);
        flightPlanCoordinates = [];

        var myLatLng = new google.maps.LatLng({lat: Number(lat), lng: Number(lng)});
        flightPlanCoordinates.push(myLatLng);

        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          icon: "http://maps.google.com/mapfiles/ms/icons/purple-dot.png",
        });
        map.panTo(myLatLng);
        allMarkers.push(marker);

        var curr_lat = $(this).attr('lat');
        var curr_lng = $(this).attr('lng');
        var currLatLng = new google.maps.LatLng({lat: Number(curr_lat), lng: Number(curr_lng)});
        flightPlanCoordinates.push(currLatLng);

        var marker1 = new google.maps.Marker({
          position: currLatLng,
          map: map,
          icon: "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png",
        });
        map.panTo(currLatLng);
        allMarkers.push(marker1);

        var lineSymbol = {
            path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
        };

        flightPath = new google.maps.Polyline({
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
        animateCircle(flightPath);

        $('#view-map-nearby').modal('show');
    })

    function animateCircle(line) {
          var count = 0;
          intervalId = window.setInterval(function() {
            count = (count + 1) % 200;

            var icons = line.get('icons');
            icons[0].offset = (count / 2) + '%';
            line.set('icons', icons);
        }, 20);
}
    
    $( document ).ready(function() {
    $.getJSON('https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=' + lat + ',' + lng + '&radius=' + radius + '&type=' + type + '&key=AIzaSyBB2gXITRe0zhIOQVJ_fyOVMt965cq_gO4',
    function(data) {
        var total_count = data['results'].length;
        if(total_count == 0){
            var out = '<div class="img-message-to-user"><img src="uploads/not_found_posts.png" alt=""></div>';
            $('#append-to-nearby').append(out);
            return true;
        }

        var is_open_now = 0;
        var avg_rating = 0;
        var i=0;
        $.each(data['results'], function( ind, result ) {
            var curr_lat = result['geometry']['location']['lat'];
            var curr_lng = result['geometry']['location']['lng'];

            var out = '<div class="back-nearby-result row" lat="' + curr_lat + '" lng="' + curr_lng + '"><div class="image-nearby-result-div col-xs-5">';
            if(typeof result['photos'] != 'undefined'){
                var photo = result['photos'][0]['photo_reference'];
                out += '<img src="https://maps.googleapis.com/maps/api/place/photo?maxwidth=900&photoreference=' + photo + '&key=AIzaSyBB2gXITRe0zhIOQVJ_fyOVMt965cq_gO4" alt="Photo">';
            }else{
                out += '<img src="uploads/no_picture.png" alt="Photo">';
            }
            out += '</div><div class="col-xs-7">';
            if(typeof result['name'] != 'undefined'){
                var place_name = result['name'];
                out += '<div class="main-nearby-name">' + place_name + '</div>';
            }
            out += '<div class="stars-open-nearby">';
            if(typeof result['rating'] != 'undefined' && typeof result['user_ratings_total'] != 'undefined'){
                var stars = result['rating'];
                avg_rating += stars;
                var users_rated = result['user_ratings_total'];
                out += '<div class="stars-nearby"><ion-icon name="star"></ion-icon> ' + stars + ' <span>(' + users_rated + ' голоса)</span></div>';
            }
            if(typeof result['opening_hours'] != 'undefined'){
                var open_now = result['opening_hours']['open_now'];
                if(open_now) is_open_now++;
                out += '<div class="open-now-nearby"><ion-icon name="alarm"></ion-icon>Open now: <span>' + open_now + '</span></div>';
            }
            out += '</div>';
            if(typeof result['vicinity'] != 'undefined'){
                var vicinity = result['vicinity'];
                out += '<div class="address-nearby"><ion-icon name="pin"></ion-icon>' + vicinity + '</div>';
            }
            out += '</div></div>';

            $('#append-to-nearby').append(out);

            if(i+1 == total_count){
                setStat(total_count, is_open_now, avg_rating);
            }
            i++;
        })
    })
})

setStat = function(total_count, is_open_now, avg_rating){
    $('.total-result-count').text(total_count + ' pcs');
    $('.total-open-now').text(is_open_now + ' pcs');
    if(total_count != 0) $('.total-avg-rating').text((avg_rating/total_count).toFixed(1) + ' st');
}
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBB2gXITRe0zhIOQVJ_fyOVMt965cq_gO4&types=(cities)&libraries=places&callback=initMapCreate" type="text/javascript"></script>