function getPlacesOfInterest(get_markers, element_id){

    $.each(get_markers, function( index, value ) {
        $.getJSON('https://maps.googleapis.com/maps/api/place/textsearch/json?types=point_of_interest&location=' + value['lat'] + ',' + value['lng'] + '&radius=5&key=AIzaSyBB2gXITRe0zhIOQVJ_fyOVMt965cq_gO4',
            function(data) {
                $.each(data['results'], function( ind, result ) {
                    $.getJSON('https://maps.googleapis.com/maps/api/place/details/json?placeid=' + result.place_id + '&fields=type,url,vicinity,name,photo&key=AIzaSyBB2gXITRe0zhIOQVJ_fyOVMt965cq_gO4',
                        function(data) {  
                            if(typeof data.result.photos != 'undefined')
                            if(data.result.photos.length > 0){   
                                callbackResult(data.result.photos[0].photo_reference, data.result.types[0], data.result.name, data.result.url, data.result.vicinity, element_id);
                            }
                        })
                });
        });
    });
  }
  
  function callbackResult(photo, type, name, url, vicinity, element_id){
    $('.post-autoplay-'+element_id).slick('slickAdd','<div><div class="data-slide-center"><div class="img-autoplay"><a href="' + url + '"><img src="https://maps.googleapis.com/maps/api/place/photo?maxwidth=900&photoreference=' + photo + '&key=AIzaSyBB2gXITRe0zhIOQVJ_fyOVMt965cq_gO4" alt="Photo"></a></div><div class="row datas-div"><div class="col-xs-12"><div class="datas-address-div"><a href="' + url + '">' + name + '</a></div><div class="datas-type-div">' + type + '</div></div><div class="col-xs-12 datas-vicinity-div"><ion-icon name="pin"></ion-icon>' + vicinity + '</div></div></div></div>');   
  }