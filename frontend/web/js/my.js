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
  var navPos = $('nav').offset().top + $('nav').height();

  if($('#back-top').length > 0){ var navBackPos = $('#back-top').outerHeight(); var is_orange = true; }
  if($('#back-top-simple').length > 0){ var navBackPos = $('#back-top-simple').outerHeight(); var is_orange = false; }

  if(navPos > navBackPos){
    $('.navbar').addClass('menu-down');
    $('.navbar li>a').css({"color": "#333"});
    $('.navbar .navbar-brand').css({"color": "#333"});
    $('.navbar .active>a').css({"background": "#f3f3f3"});
    $('.navbar .navbar-toggle').css({"background": "#ffbf38"});
    $('.navbar').css({"box-shadow": "0px 1px 10px #c9c9c9"});
    if(!is_orange)$('.navbar-simple .navbar-collapse').css({'padding-bottom': '0px', 'border-bottom': 'none'});
  }
  else{
    if(is_orange){
      $('.navbar').removeClass('menu-down');
      $(document).width()>767 ? $('.navbar li>a').css({"color": "rgba(255, 255, 255, 1)"}) : $('.navbar li>a').css({"color": "#ffbf38"});
      $('.navbar .navbar-brand').css({"color": "rgba(255, 255, 255, 1)"});
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
});

$(window).scroll(function(){
  if($('nav').length == 0) return;
  var navPos = $('nav').offset().top + $('nav').height();
  
  if($('#back-top').length > 0){ var navBackPos = $('#back-top').outerHeight(); var is_orange = true; }
  if($('#back-top-simple').length > 0){ var navBackPos = $('#back-top-simple').outerHeight(); var is_orange = false; }

  if(navPos > navBackPos){
    $('.navbar').addClass('menu-down');
    $('.navbar li>a').css({"color": "#333"});
    $('.navbar .navbar-brand').css({"color": "#333"});
    $('.navbar .active>a').css({"background": "#f3f3f3"});
    $('.navbar .navbar-toggle').css({"background": "#333"});
    $('.navbar').css({"box-shadow": "0px 1px 10px #c9c9c9"});
    if(!is_orange) $('.navbar-simple .navbar-collapse').css({'padding-bottom': '0px', 'border-bottom': 'none'});
  }
  else{
    if(is_orange){
      $('.navbar').removeClass('menu-down');
      $(document).width()>767 ? $('.navbar li>a').css({"color": "rgba(255, 255, 255, 1)"}) : $('.navbar li>a').css({"color": "#ffbf38"});
      $('.navbar .navbar-brand').css({"color": "rgba(255, 255, 255, 1)"});
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
  

  var wintop = $(window).scrollTop(), docheight =
  $(document).height(), winheight = $(window).height();

  var scrolled = (wintop/(docheight-winheight))*100;

  $('.scroll-bar').css('width', (scrolled + '%'));

});

$('.search-posts').click(function(){
  $('#modal-default').modal('show');
});

$('#modal-default').on('hidden.bs.modal', function (e) {
  $('.search-place-input').val("");
  $('.searching-city').remove();
  $('.cookie-city').show();
})

$(function () {
  $('[data-toggle="popover"]').popover({html:true, trigger: 'focus'})
})

