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
  //$('#post-id_place').val(autocomplete.getPlace()['place_id']);
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

addInfo = function(){
  
}

getPos = function(id){
  alert(id);
}

$('#slide-upload-image').click(function(){
  $('.js-file-upload').click();
})
/*
$("#uploadButton").on("click", function(){
    var fd = new FormData();
    var file = $(".js-file-upload")[0].files[0];
    fd.append('files', file);

  $.ajax({
      type: "POST",
      url: 'index.php?r=post/add-image',
      type: 'POST',
      data: fd,
      cache: false,
      contentType: false,
      processData: false,
      success: function (data) {
          //show content
          console.log(data);
      }
  });

})*/

$(".js-file-upload").on("change", function(e){
  
  $(".btn-upload-image").click();
/*
  $.ajax({
    url: 'index.php?r=post/create',
    method: 'POST',
    dataType: 'json',
    contentType: 'application/json; charset=utf-8',
    success: function(data) {
        alert(data);
    },
    fail: function(jqXHR, textStatus) {
        alert("Request failed: " + textStatus);
    }
});*/
})

$('.btn-upload-image').on('click', function () {
  $.ajax({
    url: 'index.php?r=post/create',
    method: 'POST',
    success: function(data) {
        alert(data);
    },
    fail: function(jqXHR, textStatus) {
        alert("Request failed: " + textStatus);
    }
  });
})
