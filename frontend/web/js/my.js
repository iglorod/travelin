function Marker_obj() {
  this.image = [];
  this.text = [];
  this.mainText = "";
  this.mainTitle = "";
  this.lat = "";
  this.lng = "";
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

refreshMarkerObj = function(){
  var mas_markers = JSON.parse($('.hidden-input-markers').val());
  var mas_images = JSON.parse($('.hidden-input-images').val());
  /*flightPlanCoordinates*/var all_polilines = JSON.parse($('.hidden-input-polyline-view').val());
  markerObjects = [];
  current_marker_id = 0;

  $.each(mas_markers, function( index, value ) {
    markerObjects[current_marker_id] = new Marker_obj();
    markerObjects[current_marker_id].lat = value.lat;
    markerObjects[current_marker_id].lng = value.lng;
    markerObjects[current_marker_id].mainText = value.text;
    markerObjects[current_marker_id].mainTitle = value.title;
    $.each(mas_images, function( i, image ) {
      if(image.id_marker == value.id){
        markerObjects[current_marker_id].image.push(image.name);
        markerObjects[current_marker_id].text.push(image.text);
      }
    })

    var myLatLng = new google.maps.LatLng({lat: value.lat, lng: value.lng});
    placeMarkerAndPanToView(myLatLng, map, index, allMarkers);
    current_marker_id++;
  })

  $.each(all_polilines, function( index, value ) {
    flightPlanCoordinates.push(value);
    bildPolilynes();
  })
  current_marker_id--;
  
}

$('#modal-default').on("keyup","#search_autocomplete_input", function(e){
  if($(this).val().length >= 3){
    $.getJSON('https://maps.googleapis.com/maps/api/place/autocomplete/json?types=(cities)&language=en&key=AIzaSyBB2gXITRe0zhIOQVJ_fyOVMt965cq_gO4', 
    {
      input: $(this).val()
    },
    function(data) {
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

$('.push-and-push').click(function(){
    $.getJSON('https://maps.googleapis.com/maps/api/place/textsearch/json?types=point_of_interest&location=32.772553335091,-97.366847991943&radius=10&key=AIzaSyBB2gXITRe0zhIOQVJ_fyOVMt965cq_gO4',
    function(data) {
    console.log(data);
    });
})

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

$('.create-post-form').on('beforeSubmit', function(){
  $('#post-id_place').val(placeId);
  
  var markersArray = [];

  $.each(markerObjects, function( index, value ) {
    if(value.lat == "" || value.lng == "") return;
    markersArray.push(value);
  })

  markersArray = JSON.stringify(markersArray);
  $('.hidden-input-markers').val(markersArray);

  var planCoordinates = JSON.stringify(flightPlanCoordinates);
  $('.hidden-input-polyline').val(planCoordinates);
})

$('#modal-default').on('hidden.bs.modal', function (e) {
  $('.search-place-input').val("");
  $('.searching-city').remove();
  $('.cookie-city').show();
})

$('#click-span').click(function(){
  if($('#post-id_place').val() == "" || typeof autocomplete.getPlace() === 'undefined') {
    Swal.fire(
      'Place Name',
      'Please set location of your trip. Start to type some text...',
      'info'
    )
    return; 
  }

  if(placeId == undefined) placeId = autocomplete.getPlace()['place_id'];
  else if(placeId != autocomplete.getPlace()['place_id']) { 
    mapIsCreated = 0;
    removeAllMarkersData(0);
    removeAllMarkersFromMap();
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

  if(mapIsCreated == 0) initMapCreate();
})

$('#click-span-view-travel').click(function(){
  $('.post-view').css({'visibility':'hidden', 'margin-top': '-200px', 'opacity': '0'});

  setTimeout(function(){
    $('.post-view').css({'position':'absolute'});
 },500);

  $('.post-map-create').css({'visibility':'visible', 'position':'relative', 'opacity':'1', 'margin-top': '0px' });
  $('#map-create').css({'visibility':'visible', 'position':'relative', 'opacity':'1', 'height': '400px' });
  $('.click-span-travel-back-div').css({'visibility':'visible', 'position':'inherit', 'opacity':'1'});
  $('.redactor-toolbar').css({'display':'none'});

  if(mapIsCreated == 0) initMapCreate();
})

$('#click-span-travel-back').click(function(){
  if($('.pushed-button-add-path').length >= 1) $('#button-add-path').click();
  hideDescription();

  $('.post-create').css({'visibility':'visible', 'margin-top': '0px', 'opacity': '1', 'position': 'inherit'});

  $('.post-map-create').css({'visibility':'hidden', 'position':'absolute', 'opacity':'0', 'margin-top': '200px' });
  $('#map-create').css({'visibility':'hidden', 'position':'absolute', 'opacity':'0', 'height': '0px'});
  $('.click-span-travel-back-div').css({'visibility':'hidden', 'position':'absolute', 'opacity':'0'});
  $('.redactor-toolbar').css({'display':'inherit'});

  hideSlideShow();
  hideDescription();
})

$('#click-span-travel-back-view').click(function(){
  $('.post-view').css({'visibility':'visible', 'margin-top': '0px', 'opacity': '1', 'position': 'relative'});

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
      $('.navbar').removeClass('menu-down');/**#ffbf38 */
      $(document).width()>767 ? $('.navbar li>a').css({"color": "rgba(255, 255, 255, 1)"}) : $('.navbar li>a').css({"color": "black"});
      $('.navbar .navbar-brand').css({"color": "rgba(255, 255, 255, 1)", "font-weight": "400"});
      $(document).width()>767 ? $('.navbar .active>a').css({"color": "black", "background": "rgba(255, 255, 255, 1)"}) : $('.navbar .active>a').css({"color": "black", "background": "#f0f1f2"})
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
  refreshDescription();
}

$('#slide-upload-image .upload-image-icon').click(function(){
  if(markerObjects[current_marker_id].image.length >= 5){
    Swal.fire(
      'Uploading Photos',
      'Restriction on loading pictures. Allowed to upload not more than 5 images on one marker.',
      'info'
    )
    return;
  }

  $('.js-file-upload').click();
})

$(".js-file-upload").on("change", function(e){
  $('.btn-upload-image').click();
})


$('.add-image-create-post-form').on('beforeSubmit', function(){

  if($('.js-file-upload')[0].files.length == 0){
    return false;
  }
  
  var my_arr = $('.js-file-upload')[0].files[0]['name'].split(".");
  if(my_arr[1] != 'png' && my_arr[1] != 'jpeg' && my_arr[1] != 'jpg') {
    Swal.fire(
      'Pictures Format',
      "Available formats are '.png' '.jpg' or '.jpeg'",
      'info'
    )
    refreshImageUploader();
    return false;
  }  

  $image_crop = $('#image_demo').croppie('destroy');

  $image_crop = $('#image_demo').croppie({
    enableExif: true,
    viewport: {
      width:600,
      height:350,
      type:'square' //circle
    },
    boundary:{
      width:698,
      height:350
    }
  })

  var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL($('.js-file-upload')[0].files[0]);
    $('#uploadimageModal').modal('show');

    return false;
});

$('.crop_image').click(function(event){
  $image_crop.croppie('result', {
    type: 'canvas',
    size: 'viewport'
  }).then(function(response){

    var formData = new FormData();
    formData.append('image', response);
    formData.append('image_name', $('.js-file-upload')[0].files[0]);
    formData.append('action', 'upload');

    $.ajax({
      url: 'index.php?r=post/create',
      type: 'POST',
      data: formData,
        success: function(res){
          markerObjects[current_marker_id].addImage(res);
          refreshImageUploader();
          refreshImagesSlideShow();
          $('#uploadimageModal').modal('hide');
        },
        error: function(){
          alert('Error!');
        },
      cache: false,
      contentType: false,
      processData: false
    });
  })
});

$('#uploadimageModal').on('hidden.bs.modal', function () {
  refreshImageUploader();
})

refreshImageUploader = function(){
  $('.js-file-upload').val("");
}

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

    var tags = '<div class="item ' + is_last + '"><img class="slide-images-style" src="uploads/marker_images/' + value + '"><div class="carousel-caption"><input type="text" class="cool-input-for-slide" maxlength="40" image_num="' + index + '" value="' + markerObjects[current_marker_id].text[index] + '"></div></div>';
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

$(document).on('keyup', "input[class='cool-input-for-slide']",function () {
  var image_num = $(this).attr("image_num");
  markerObjects[current_marker_id].text[image_num] = $(this).val();
})

createMarkerObj = function(id){
  markerObjects[id] = new Marker_obj();
  markerObjects[id].lat = allMarkers[id].position.lat();
  markerObjects[id].lng = allMarkers[id].position.lng();
  current_marker_id = id;

  refreshImagesSlideShow();
  refreshDescription();
}

$("#click-span-travel-add-photos").on("click", function(){
  if(typeof current_marker_id == 'undefined') {
    Swal.fire({
      title: 'Please, check the marker...',
      animation: false,
      customClass: 'animated tada'
    })
    return;
  }

  if($(this).text() == "Add Photos"){
    hideDescription();
    refreshImagesSlideShow();
    showSlideShow();
    scrollToSlideShow();
    $(this).text("Remove All Photos");
  }else if($(this).text() == "Remove All Photos"){
    checkIfUserIsShure();
  }
})

$("#click-span-travel-add-photos-view").on("click", function(){
  if(typeof current_marker_id == 'undefined') {
    Swal.fire({
      title: 'Please, check the marker...',
      animation: false,
      customClass: 'animated tada'
    })
    return;
  }

  if($(this).text() == "Watch Photos"){
    refreshImagesSlideShow();
    showSlideShow();
    scrollToSlideShow();
    $(this).text("Hide Photos");
  }else if($(this).text() == "Hide Photos"){
    hideSlideShow();
    $(this).text("Watch Photos");
  }
})

$('#click-span-travel-scroll-to-photos').click(function(){
  scrollToSlideShow();
})

scrollToSlideShow = function(){
  $('html,body').animate({
    scrollTop: $("#scroll-to-div").offset().top + $("#scroll-to-div").outerHeight(true) - 30
}, 'slow');
}

showSlideShow = function(){
  $('#click-span-travel-scroll-to-photos').css({"visibility": "visible"});
  $('.slide-show').css({'position': 'relative', 'visibility': 'visible', 'height': '350px', 'opacity': '1'});
  $('.carousel').css({'position': 'relative', 'visibility': 'visible', 'height': '350px', 'opacity': '1'});  
}

hideSlideShow = function(){ 
  $('#click-span-travel-scroll-to-photos').css({"visibility": "hidden"});
  $("#click-span-travel-add-photos").text("Add Photos");
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

loadCurrMarkerForView = function(map, marker){
  marker.setIcon("http://maps.google.com/mapfiles/ms/icons/yellow-dot.png"); //here we changing marker icon 
  loadInfoWindowForView(map, marker);
}

standartizeAllMarkers = function(){
  $.each(allMarkers, function( index, marker ) {
    marker.setIcon("http://maps.google.com/mapfiles/ms/icons/purple-dot.png");
    hideInfoWindow(map, marker)
    return;
  });
  current_marker_id = undefined;
}

loadInfoWindow = function(map, marker){
  var titleText;  //before we add some text to info-window
  var mainText;
  
  if(markerObjects[current_marker_id].mainTitle == "") titleText = "Brief description";
  else titleText = markerObjects[current_marker_id].mainTitle;

  if(markerObjects[current_marker_id].mainText == "") mainText = "Describe your stop at this place. You can also upload multiple photos from this location.";
  else mainText = markerObjects[current_marker_id].mainText;

  marker.info = new google.maps.InfoWindow({
    content: '<div class="title-info-window">' + titleText +'</div><div class="description-info-window">' + mainText + '</div><div class="button-info-window"><ion-icon onclick="removeMarker()" id="remove-marker-icon" name="close-circle-outline" mark="' + current_marker_id + '"></ion-icon></div>'
  });

  marker.info.open(map, marker);
}

loadInfoWindowForView = function(map, marker){
  var titleText;  //before we add some text to info-window
  var mainText;
  
  if(markerObjects[current_marker_id].mainTitle == "") titleText = "Brief description";
  else titleText = markerObjects[current_marker_id].mainTitle;

  if(markerObjects[current_marker_id].mainText == "") mainText = "Describe your stop at this place. You can also upload multiple photos from this location.";
  else mainText = markerObjects[current_marker_id].mainText;

  marker.info = new google.maps.InfoWindow({
    content: '<div class="title-info-window">' + titleText +'</div><div class="description-info-window">' + mainText + '</div>'
  });

  marker.info.open(map, marker);
}

hideInfoWindow = function(map, marker){
  if(typeof marker.info != 'undefined')
  marker.info.close(map, marker);
};

removeMarker = function(){
  Swal.fire({
    type: 'success',
    title: 'Marker information and photos has been deleted.',
    showConfirmButton: false,
    timer: 2000
  })

  var marker_id = $('#remove-marker-icon').attr('mark');
  var lat = allMarkers[marker_id].position.lat();
  var lng = allMarkers[marker_id].position.lng(); 
  allMarkers[marker_id].setMap(null);
  removeMarkerData(marker_id);
  deletePolilines();
  deletePlanCoordinates(lat, lng);
  buildRoad(flightPlanCoordinates, map);
  hideSlideShow();
  hideDescription();
  current_marker_id = undefined;
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

removeAllMarkersData = function(deleteOnlyImages){
  var imageDeleteList = [];
  $.each(markerObjects, function( index, value ) {
    $.each(value.image, function( index, image ) {
      imageDeleteList.push(image);
    });
    value.image = [];
    value.text = [];
    if(deleteOnlyImages == 0){
      value.mainText = "";
      value.mainTitle = "";
      value.lat = "";
      value.lng = "";
    }
  });

  imageDeleteList = JSON.stringify(imageDeleteList);
  
  var data = {
    'image_list': imageDeleteList,
    'action': "delete"
  }

    $.ajax({
        url: 'index.php?r=post/create',
        type: 'POST',
        data: data,
        success: function(res){
          standartizeAllMarkers();
        },
        error: function(){
            alert('Error!');
        }
    });
}

removeMarkerData = function(id){
  var imageDeleteList = [];
  $.each(markerObjects[id].image, function( index, image ) {
    imageDeleteList.push(image);
  });
  markerObjects[id].image = [];
  markerObjects[id].text = [];
  markerObjects[id].mainText = "";
  markerObjects[id].mainTitle = "";
  markerObjects[id].lat = "";
  markerObjects[id].lng = "";

  imageDeleteList = JSON.stringify(imageDeleteList);
  
  var data = {
    'image_list': imageDeleteList,
    'action': "delete"
  }
    $.ajax({
        url: 'index.php?r=post/create',
        type: 'POST',
        data: data,
        success: function(res){
        },
        error: function(){
            alert('Error!');
        }
    });
}

checkIfUserIsShure = function(){
  Swal.fire({
    title: 'Are you sure?',
    text: 'You will not be able to recover deleted photos!',
    type: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'No, keep it'
  }).then((result) => {
    if (result.value) {
      removeAllMarkersData(1);
      hideSlideShow();
      Swal.fire(
        'Deleted!',
        'Your imaginary file has been deleted.',
        'success'
      )
    // For more information about handling dismissals please visit
    // https://sweetalert2.github.io/#handling-dismissals
    }
  })
};

removeAllMarkersFromMap = function(){
  $.each(allMarkers, function( index, value ) {
    value.setMap(null);
  })
  allMarkers = [];
  markerObjects = [];
  deletePolilines();
  flightPathArray = [];
  flightPlanCoordinates = [];
}

$('#click-span-travel-add-descript').click(function(){

  if(typeof current_marker_id == 'undefined') {
    Swal.fire({
      title: 'Please, add or check the marker for adding description to him...',
      animation: false,
      customClass: 'animated tada'
    })
    return;
  }

  if($('#click-span-travel-add-descript').text() == 'Add Description'){
    showDescription();
  }else if($('#click-span-travel-add-descript').text() == 'Hide Description'){
    hideDescription();
  }
})

refreshDescription = function(){
  $('.title-decr-input').val(markerObjects[current_marker_id].mainTitle);
  $('#text-decr-input').val(markerObjects[current_marker_id].mainText);
}

showDescription = function(){
  hideSlideShow();

  $('.place-brief-descr').css({'position':'relative', 'opacity': "1", 'height': 'auto', 'margin-top': '30px', 'visibility': 'visible', 'z-index': 'unset'});

  refreshDescription();

  $('#click-span-travel-add-descript').text('Hide Description');
}

hideDescription = function(){
  $('.place-brief-descr').css({'opacity': "0", 'height': '0px', 'margin-top': '0px', 'visibility': 'hidden', 'z-index': '0'});
  setTimeout(function(){
    $('.place-brief-descr').css({'position':'absolute'});
  },500);

 $('#click-span-travel-add-descript').text('Add Description');
}

$('#text-decr-input').on('keyup', function(){
  var el = this;
  setTimeout(function(){
    el.style.cssText = 'height:auto; padding:0';
    el.style.cssText = 'height:' + el.scrollHeight + 'px';
  },0);

  markerObjects[current_marker_id].mainText = $('#text-decr-input').val();
})

$('.title-decr-input').on('keyup', function(){
  markerObjects[current_marker_id].mainTitle = $('.title-decr-input').val();
})

$('#slide-upload-image .remove-image-icon').click(function(){
  var image_num = $('.carousel .carousel-inner > .active > .carousel-caption > .cool-input-for-slide').attr('image_num');

  if(typeof image_num == 'undefined') {
    refreshImagesSlideShow();
    return;
  }

  var imageDeleteList = [];
  imageDeleteList.push(markerObjects[current_marker_id].image[image_num]);

  imageDeleteList = JSON.stringify(imageDeleteList);
  
  var data = {
    'image_list': imageDeleteList,
    'action': "delete"
  }

    $.ajax({
        url: 'index.php?r=post/create',
        type: 'POST',
        data: data,
        success: function(res){
          markerObjects[current_marker_id].image.splice(image_num, 1);
          markerObjects[current_marker_id].text.splice(image_num, 1);
          refreshImagesSlideShow();
        },
        error: function(){
            alert('Error!');
        }
    });
})

$('.upload-background-span').click(function(){
  $('.profile-js-file-upload').click();
})

$(".profile-js-file-upload").on("change", function(e){
  if($('.profile-js-file-upload')[0].files.length == 0){
    return false;
  }

  var my_arr = $('.profile-js-file-upload')[0].files[0]['name'].split(".");
  if(my_arr[1] != 'png' && my_arr[1] != 'jpeg' && my_arr[1] != 'jpg') {
    Swal.fire(
      'Pictures Format',
      "Available formats are '.png' '.jpg' or '.jpeg'",
      'info'
    )
    refreshProfileImageUploader();
    return false;
  }  
  $image_crop = $('#image_demo').croppie('destroy');
  
  $image_crop = $('#image_demo').croppie({
    enableExif: false,
    viewport: {
      width:$( window ).width(),
      height:250,
      type:'square' //circle
    },
    boundary:{
      width:$( window ).width(),
      height:350
    }
  })

  var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL($('.profile-js-file-upload')[0].files[0]);
    $('#uploadBackImage').modal('show');
})

$('.crop-back-profile').click(function(event){
  $image_crop.croppie('result', {
    type: 'canvas',
    size: 'viewport'
  }).then(function(response){

    var formData = new FormData();
    formData.append('image', response);
    formData.append('image_name', $('.profile-js-file-upload')[0].files[0]);
    formData.append('action', 'upload-back-image');

    $.ajax({
      url: 'index.php?r=site/profile',
      type: 'POST',
      data: formData,
        success: function(res){
          //$('.profile-upload-div').css({'display': 'none'});
          refreshProfileImageUploader();
          setBackProfileImage(res);
          $('#uploadBackImage').modal('hide');
        },
        error: function(){
          alert('Error!');
        },
      cache: false,
      contentType: false,
      processData: false
    });
  })
});

$('#uploadBackImage').on('hidden.bs.modal', function () {
  refreshProfileImageUploader();
})

refreshProfileImageUploader = function(){
  $('.profile-js-file-upload').val("");
}

setBackProfileImage = function(res){
  $('.profile-back-top').removeAttr("style");
  $('.profile-back-top').attr("style", "background: url('/frontend/web/uploads/profile/" + res + "') 100% 100% no-repeat; background-size: cover;");
}

$('.upload-circle-image').click(function(){
  $('.avatar-js-file-upload').click();
})

$(".avatar-js-file-upload").on("change", function(e){
  if($('.avatar-js-file-upload')[0].files.length == 0){
    return false;
  }

  var my_arr = $('.avatar-js-file-upload')[0].files[0]['name'].split(".");
  if(my_arr[1] != 'png' && my_arr[1] != 'jpeg' && my_arr[1] != 'jpg') {
    Swal.fire(
      'Pictures Format',
      "Available formats are '.png' '.jpg' or '.jpeg'",
      'info'
    )
    refreshAvatarImageUploader();
    return false;
  }  

  $image_crop = $('#avatar_demo').croppie('destroy');
  
  $image_crop = $('#avatar_demo').croppie({
    enableExif: false,
    viewport: {
      width: 250,
      height:250,
      type:'circle' //square
    },
    boundary:{
      width:300,
      height:300
    }
  })

  var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL($('.avatar-js-file-upload')[0].files[0]);
    $('#uploadAvatarImage').modal('show');
})

$('.crop-back-avatar').click(function(event){
  $image_crop.croppie('result', {
    type: 'canvas',
    size: 'viewport'
  }).then(function(response){

    var formData = new FormData();
    formData.append('image', response);
    formData.append('image_name', $('.avatar-js-file-upload')[0].files[0]);
    formData.append('action', 'upload-avatar-image');

    $.ajax({
      url: 'index.php?r=site/profile',
      type: 'POST',
      data: formData,
        success: function(res){
          //$('.profile-upload-div').css({'display': 'none'});
          refreshAvatarImageUploader();
          setAvatarProfileImage(res);
          $('#uploadAvatarImage').modal('hide');
        },
        error: function(){
          alert('Error!');
        },
      cache: false,
      contentType: false,
      processData: false
    });
  })
});

refreshAvatarImageUploader = function(){
  $('.avatar-js-file-upload').val("");
}

setAvatarProfileImage = function(res){
  $('.avatar-circle-image').attr("src", "/frontend/web/uploads/profile_avatar/" + res + "");
}

$('#uploadAvatarImage').on('hidden.bs.modal', function () {
  refreshAvatarImageUploader();
})

$('.autoplay').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  autoplay: false,
  autoplaySpeed: 1500,
});

$(".like_post").click(function(){
  var id_post = {
      'id':$(this).attr('post')
  };

  $(this).toggleClass("done-by-user");
 // var res = "sss";
  //$(this).parent().parent().parent().first().children().first().children().first().children().text(res);
  var span = this;
  $.ajax({
    url: 'index.php?r=post/like-post',
    type: 'POST',
    data: id_post,
      success: function(res){
        $(span).parent().parent().parent().first().children().first().children().first().children().text(res);
      },
      error: function(){
        alert('Error!');
      }
  });
}
);

$(".like_repost").click(function(){
  var id_repost = {
      'id':$(this).attr('post')
  };

  $(this).toggleClass("done-by-user");
 // var res = "sss";
  //$(this).parent().parent().parent().first().children().first().children().first().children().text(res);
  var span = this;
  $.ajax({
    url: 'index.php?r=post/like-repost',
    type: 'POST',
    data: id_repost,
      success: function(res){
        $(span).parent().parent().parent().first().children().first().children().first().children().text(res);
      },
      error: function(){
        alert('Error!');
      }
  });
}
);

$('.repost_post').click(function(event){
  var post_id = $(this).attr('post');
  $('#reposting-post .btn-repost').attr('post_id', post_id);
})

$('.btn-repost').click(function(){
  var id_post = {
      'id':$(this).attr('post_id'),
      'description': $('.description-repost-area').val()
  };

  $.ajax({
    url: 'index.php?r=post/repost-post',
    type: 'POST',
    data: id_post
  });
})

$(document).on("click", ".copy-share-link", function() {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($('#hidden-link-post').val()).select();
  document.execCommand("copy");
  $temp.remove();
});

$('.toggle-popover-link').click(function(){
  var link = $(this).attr('post_id');
  $('#hidden-link-post').val($('#hidden-link-const').val() + link);
})