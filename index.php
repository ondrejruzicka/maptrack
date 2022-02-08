<?php

include_once('config.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MapTrack</title>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?php echo GOOGLE_MAPS_KEY ?>"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>


  <script type="text/javascript" src="./js/mapa.js"></script>
  <script type="text/javascript" src="./js/tracking.js"></script>



  <script type="text/javascript">


    var variable= new Array();


    var user_id;

    $(document).ready(function() {





  $(document).on('keyup', function(event) {
    if(event.key=="F2") {
      if(Tracker.startTracking) {
        end();
      }
      else {
        start();
      }
    }
  });

  function start() {

    if($('#user_id').val()=='') {
      $('#user_id').addClass('is-invalid');
      return;
    }

    $(".overlay").addClass('d-none');
    $(".overlay__start").addClass('d-none');
    user_id = $('#user_id').val();
    variable.push(user_id);
    startTracking();
  }

  function end() {
    $(".overlay__end").removeClass('d-none');
    $(".overlay").removeClass('d-none');
    stopTracking();
  }


});



</script>


<style type="text/css">

  .map-wrap {
    position: fixed;
    top: 0;
    bottom: 0;
    right: 0;
    left: 0;
    z-index: 100;
  }

  #map {
    width: 100%;
    height: 100%;
  }

  .overlay {
    position: fixed;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    top: 0;
    bottom: 0;
    right: 0;
    left: 0;
    background-color: rgba(0,0,0,0.7);
    z-index: 110;
  }



</style>


</head>
<body>



  <!-- Sem patří mapa - pozor na změnu id, je provazaná s Javascriptem v souboru js/mapa.js -->
  <div class="map-wrap">

    <div id="map">

    </div>
  </div>


  <div class="overlay">
    <div class="overlay__start">
      <div class="mb-5">
        <input class="form-control form-control-lg" type="text" id="user_id" required placeholder="User ID">
      </div>
      <h1 class="text-white">Fill ID and press F2 to start</h1>
    </div>
    <div class="overlay__end d-none">
      <h1 class="text-white">Tracking finished!</h1>
    </div>
  </div>








</body>

</html>