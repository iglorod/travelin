/*
     function initMap()
    {
    var element = document.getElementById("map");
    var options = {
        zoom: 5,
        center: { lat: 50.431782, lng:30.516382 }
    };

    var myMap = new google.maps.Map(element, options);

    }


    function initMap() {
  var address = 'Kiev';
  var element = document.getElementById("map");

  var geocoder = new google.maps.Geocoder();
  geocoder.geocode({
    'address': address
  }, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      var Lat = results[0].geometry.location.lat();
      var Lng = results[0].geometry.location.lng();
      var myOptions = {
        zoom: 11,
        center: new google.maps.LatLng(Lat, Lng)
      };
      var map = new google.maps.Map(element, myOptions);
    } else {
      alert("Something got wrong " + status);
    }
  });
}*/

function Marker_obj() {
  this.image = [];
  this.text = [];
  this.mainText = "";
  this.mainTitle = "";
}

Marker_obj.prototype.addImage = function(image) {
  this.image.push(image);
  this.text[this.image.length-1] = "";
};

Marker_obj.prototype.addImageText = function(image_name, text) {
  var index = this.image.findIndex(x => x.image == image_name);
  this.text[index] = text;
};

var markerObjects = []

var current_marker_id;
var prev_marker_id;

$('#modal-default').on("keyup","#search_autocomplete_input", function(e){
  if($(this).val().length >= 3){
    $.getJSON('https://maps.googleapis.com/maps/api/place/autocomplete/json?types=(cities)&language=en&key=AIzaSyBB2gXITRe0zhIOQVJ_fyOVMt965cq_gO4', 
    {
      input: $(this).val()
    },
    function(data) {
      console.log(data);
      if(Object.values(data)[1] == "OK"){
        var value = Object.values(data)[0];
        var structure = '<div class="searching-city">';
        $(value).each(function(){
          var main_text = $(this)[0]['structured_formatting']['main_text'];
          var secondary_text = $(this)[0]['structured_formatting']['secondary_text'];
        
          structure += '<div class="searched-city"><span>'+main_text+'</span><span class="city-parent">'+secondary_text+'</span></div>';
        })
        structure += '</div>';
        
        $('.cookie-city').hide();
        $('.searching-city').remove();
        $('.modal-body').append(structure);
    }
    });
  }else
  {
    $('.searching-city').remove();
    $('.cookie-city').show();
  }
});

$(function() {
  if($('nav').length == 0) return;
  checkingNavBar();
});

$(window).scroll(function(){
  if($('nav').length == 0) return;
  checkingNavBar();
  
  var wintop = $(window).scrollTop(), docheight =
  $(document).height(), winheight = $(window).height();

  var scrolled = (wintop/(docheight-winheight))*100;

  $('.scroll-bar').css('width', (scrolled + '%'));

});

$('.search-posts').click(function(){
  $('#modal-default').modal('show');
});

$('.btn-create-post').click(function(){
  $('#post-id_place').val(autocomplete.getPlace()['place_id']);
})

$('#modal-default').on('hidden.bs.modal', function (e) {
  $('.search-place-input').val("");
  $('.searching-city').remove();
  $('.cookie-city').show();
})

$('#click-span').click(function(){
  if($('#post-id_place').val() == "" || typeof autocomplete.getPlace() === 'undefined') { 
    alert("Please set location of your trip. Start to type some text..."); 
    return; 
  }
  $('.city-post-create').css({'visibility':'hidden', 'margin-top': '-200px', 'opacity': '0'});

  $('.post-create').css({'visibility':'visible', 'position':'inherit', 'opacity':'1'});
  $('.redactor-toolbar').css({'display':'inherit'});
})

$('#click-span-back').click(function(){
  $('.city-post-create').css({'visibility':'visible', 'margin-top': '0px', 'opacity': '1'});

  $('.post-create').css({'visibility':'hidden', 'opacity':'0'});
  $('.redactor-toolbar').css({'display':'none'});
  setTimeout(function(){
    $('.post-create').css({'position':'absolute'});
 },500);
})

$('#click-span-travel').click(function(){
  $('.post-create').css({'visibility':'hidden', 'margin-top': '-200px', 'opacity': '0'});

  setTimeout(function(){
    $('.post-create').css({'position':'absolute'});
 },500);

  $('.post-map-create').css({'visibility':'visible', 'position':'relative', 'opacity':'1', 'margin-top': '0px' });
  $('#map-create').css({'visibility':'visible', 'position':'relative', 'opacity':'1', 'height': '400px' });
  $('.click-span-travel-back-div').css({'visibility':'visible', 'position':'inherit', 'opacity':'1'});
  $('.redactor-toolbar').css({'display':'none'});
  $('#changer-city-text').html(autocomplete.getPlace()['vicinity']);

  initMapCreate();
})

$('#click-span-travel-back').click(function(){
  $('.post-create').css({'visibility':'visible', 'margin-top': '0px', 'opacity': '1', 'position': 'inherit'});

  $('.post-map-create').css({'visibility':'hidden', 'position':'absolute', 'opacity':'0', 'margin-top': '200px' });
  $('#map-create').css({'visibility':'hidden', 'position':'absolute', 'opacity':'0', 'height': '0px'});
  $('.click-span-travel-back-div').css({'visibility':'hidden', 'position':'absolute', 'opacity':'0'});
  $('.redactor-toolbar').css({'display':'inherit'});

  hideSlideShow();
})

$(function () {
  $('[data-toggle="popover"]').popover({html:true, trigger: 'focus'})
})

checkingNavBar = function(){
  var navPos = $('nav').offset().top + $('nav').height();
  
  if($('#back-top').length > 0){ var navBackPos = $('#back-top').outerHeight(); var is_orange = true; }
  if($('#back-top-simple').length > 0){ var navBackPos = $('#back-top-simple').outerHeight(); var is_orange = false; }


  if(navPos > navBackPos){
    $('.navbar').addClass('menu-down');
    $('.navbar li>a').css({"color": "#333"});
    if(is_orange) $('.navbar .navbar-brand').css({"color": "#333", "font-weight": "300"});
    $('.navbar .active>a').css({"background": "#f3f3f3"});
    if(is_orange) $('.navbar .navbar-toggle').css({"background": "#333"});
    $('.navbar').css({"box-shadow": "0px 1px 10px #c9c9c9"});
    if(!is_orange) $('.navbar-simple .navbar-collapse').css({'padding-bottom': '0px', 'border-bottom': 'none'});
  }
  else{
    if(is_orange){
      $('.navbar').removeClass('menu-down');
      $(document).width()>767 ? $('.navbar li>a').css({"color": "rgba(255, 255, 255, 1)"}) : $('.navbar li>a').css({"color": "#ffbf38"});
      $('.navbar .navbar-brand').css({"color": "rgba(255, 255, 255, 1)", "font-weight": "400"});
      $(document).width()>767 ? $('.navbar .active>a').css({"color": "black", "background": "rgba(255, 255, 255, 1)"}) : $('.navbar .active>a').css({"color": "white", "background": "#ffbf38"})
      $('.navbar .navbar-toggle').css({"background": "transparent"});
      $('.navbar').css({"box-shadow": "none"});
    }else{
      $('.navbar').removeClass('menu-down');
      $('.navbar li>a').css({"color": "black"});
      $('.navbar .navbar-brand').css({"color": "black"});
      $('.navbar .active>a').css({"color": "black", "background": "rgba(255, 255, 255, 1)"});
      $('.navbar .navbar-toggle').css({"background": "transparent"});
      $('.navbar').css({"box-shadow": "none"});
      $('.navbar-simple .navbar-collapse').css({'padding-bottom': '5px', 'border-bottom': '1px solid rgb(236, 236, 236);'});
    }
  }

  if(!is_orange){
    var plug_top = $('.post-form').offset().top;
    if(navPos >= plug_top){
      $('.redactor-toolbar').css({'transition': 'all .5s'});
      $('.redactor-toolbar').css({'padding-top': '60px'});
    }else{
      $('.redactor-toolbar').css({'padding-top': '18px'});
    }
  }
}

$('#button-add-path').click(function(){
  $(this).toggleClass("pushed-button-add-path");
  $(this).toggleClass("unpushed-button-add-path");

  if($('.unpushed-button-add-path').length > 0){
    $('.gm-svpc').css({'opacity': '1'});
  }
  else{
    $('.gm-svpc').css({'opacity': '0'});
  }
})

loadResource = function(id){
  current_marker_id = id;
  refreshImagesSlideShow();
}

$('#slide-upload-image').click(function(){
  $('.js-file-upload').click();
})

$(".js-file-upload").on("change", function(e){
  $('.btn-upload-image').click();
})

$('#w1').on('beforeSubmit', function(){

  if($('.js-file-upload')[0].files.length == 0){
    return false;
  }
  
  var my_arr = $('.js-file-upload')[0].files[0]['name'].split(".");
  if(my_arr[1] != 'png' && my_arr[1] != 'jpeg' && my_arr[1] != 'jpg') {
    alert("Available formats are '.png' '.jpg' or '.jpeg'");
    return false;
  }
  
  var formData = new FormData();
  formData.append('image', $('.js-file-upload')[0].files[0]);

    $.ajax({
        url: 'index.php?r=post/create',
        type: 'POST',
        data: formData,
        success: function(res){
          markerObjects[current_marker_id].addImage(res);
          refreshImagesSlideShow();
        },
        error: function(){
            alert('Error!');
        },
        cache: false,
        contentType: false,
        processData: false
    });
    return false;
});

refreshImagesSlideShow = function(){
  if(typeof markerObjects[current_marker_id].image == 'undefined') {
    standartImagesSlideShow();
    return;
  }
  else if(markerObjects[current_marker_id].image.length == 0) {
    standartImagesSlideShow();
    return;
  }

  $(".carousel-inner .item").remove();
  $(".carousel-indicators li").remove();

  $.each(markerObjects[current_marker_id].image, function( index, value ) {
    var is_last = "";
    if(index == (markerObjects[current_marker_id].image.length - 1)) var is_last = "active";

    var tags = '<div class="item ' + is_last + '"><img class="slide-images-style" src="uploads/marker_images/' + value + '"><div class="carousel-caption"><input type="text" class="cool-input-for-slide" maxlength="40" value="' + markerObjects[current_marker_id].text[index] + '"></div></div>';
    $('.carousel-inner').append(tags);

    var li = '<li class="' + is_last + '" data-target="#w2" data-slide-to="' + index + '"></li>';
    $('.carousel-indicators').append(li);
  });
}

standartImagesSlideShow = function(){
  $(".carousel-inner .item").remove();

  var random = getRandomArbitrary(0, 4);
  var images = ['travel_profile.jpg', 'travel_profile1.jpg', 'travel_profile2.jpg', 'travel_profile3.jpg', 'travel_profile4.jpg'];

  var selected_image = images[random]; 
  var tags = '<div class="item active"><img class="slide-images-style" src="uploads/' + selected_image + '"><div class="carousel-caption"><p class="standart-photos-p">Add your travel photos</p></div></div>';
  
  $('.carousel-inner').append(tags);

  $(".carousel-indicators li").remove();
  var li = '<li class="active" data-target="#w2" data-slide-to="0"></li>';
  $('.carousel-indicators').append(li);
}

createMarkerObj = function(id){
  markerObjects[id] = new Marker_obj();
  current_marker_id = id;

  hideSlideShow()
}

$("#click-span-travel-add-photos").on("click", function(){
  if(typeof current_marker_id == 'undefined') {
    alert('Please, add or check the marker for inserting photo...');
    return;
  }

  refreshImagesSlideShow();

  $('.slide-show').css({'position': 'relative', 'visibility': 'visible', 'height': '350px', 'opacity': '1'});
  $('.carousel').css({'position': 'relative', 'visibility': 'visible', 'height': '350px', 'opacity': '1'});  
})

hideSlideShow = function(){ 
  $('.slide-show').css({'position': 'absolute', 'visibility': 'hidden', 'height': '0px', 'opacity': '0'});
  $('.carousel').css({'position': 'absolute', 'visibility': 'hidden', 'height': '0px', 'opacity': '0'});
}

getRandomArbitrary = function(min, max) {
  var rand = min - 0.5 + Math.random() * (max - min + 1)
  rand = Math.round(rand);
  return rand;
}

loadPrevMarker = function(map, allMarkers){
  if(typeof current_marker_id == 'undefined') return;

  $.each(allMarkers, function( index, marker ) {
    if(marker.id == current_marker_id) {
      marker.setIcon("http://maps.google.com/mapfiles/ms/icons/purple-dot.png");
      hideInfoWindow(map, marker)
      return;
    }
  });
}

loadCurrMarker = function(map, marker){
  marker.setIcon("http://maps.google.com/mapfiles/ms/icons/yellow-dot.png"); //here we changing marker icon 
  loadInfoWindow(map, marker);
}

loadInfoWindow = function(map, marker){
  var titleText;  //before we add some text to info-window
  var mainText;
  
  if(markerObjects[current_marker_id].mainText == "") titleText = "Brief description";
  else titleText = markerObjects[id].mainText;

  if(markerObjects[current_marker_id].mainTitle == "") mainText = "Describe your stop at this place. You can also upload multiple photos from this location.";
  else mainText = markerObjects[id].mainTitle;

  marker.info = new google.maps.InfoWindow({
    content: '<div class="title-info-window">' + titleText +'</div><div class="description-info-window">' + mainText + '</div><div class="button-info-window"><ion-icon onclick="removeMarker()" id="remove-marker-icon" name="close-circle-outline" mark="' + current_marker_id + '"></ion-icon></div>'
  });

  marker.info.open(map, marker);
}

hideInfoWindow = function(map, marker){
  marker.info.close(map, marker);
};

removeMarker = function(){
  var marker_id = $('#remove-marker-icon').attr('mark');
  var lat = allMarkers[marker_id].position.lat();
  var lng = allMarkers[marker_id].position.lng(); 
  allMarkers[marker_id].setMap(null);
  deletePolilines();
  console.log('Need:' + lat);
  console.log('Polil:' + flightPlanCoordinates);
  deletePlanCoordinates(lat, lng);
  buildRoad(flightPlanCoordinates, map);
}

deletePlanCoordinates = function(lat, lng){
  var index_del;
  $.each(flightPlanCoordinates, function( index, value ) {
    if(value.lat() == lat && value.lng() == lng) { 
      index_del = index;
      return false;
    }
  });

  if(typeof index_del != 'undefined') flightPlanCoordinates.splice(index_del, 1);
}

deletePolilines = function(){
  $.each(flightPathArray, function( index, value ) {
    value.setMap(null);
  });
}