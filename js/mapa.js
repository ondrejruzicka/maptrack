
// Google Maps Demo
//////////////////////////////////
 var map;
 function initialize() {
    var latlng = new google.maps.LatLng(50,17);
    var myOptions = {
      zoom: 9,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map"),
        myOptions);

  }


// Map creation
//////////////////////////////////

$(document).ready(function() {
  initialize();
});



