<!DOCTYPE html>
  <html lang="it">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InzenirDlaPida_Contatti</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
  </head>
  <body>

    <?php
    require_once('../utility/utility.php');
    require_once('../utility/db.php');
    require_once('../default/nav.php');
    if (session_status() == PHP_SESSION_NONE) {
        sec_session_start();
    }

    if(isset($_POST, $_POST['email'], $_POST['message'])) {
      $msg = '
      <strong>
        '.$_POST['message'].'
      </strong>';
      sendMail('Cliente', $_POST['email'], 'inzenirdlapida@altervista.org' ,'Cliente richiede aiuto' , $msg);
      echo '<div class="alert alert-success">
              <strong>Email inviata!</strong> Grazie per averci contattato.
            </div>';
    }
    ?>

    <div class="container-fluid w-100 " >
      <!--Contacts main title-->
      <div class="row justify-content-center ml-0 mr-0 mt-3 rounded-top bg-dark">
        <div class="col-auto text-white">
          Contatti
        </div>
      </div>

      <!--Contacts field email-->
      <div class="row justify-content-center ml-0 mr-0 mt-3 rounded-top">
        <div class="col-auto">
          <label><img src="../images/EmailLogo.png" alt="Email logo" class="img-responsive" height="30" width="30" /> Email:</label>
        </div>
        <div class="col-auto">
          <label>inzenirdlapida@altervista.org</label>
        </div>
      </div>
      <!--Contacts field phone-->
      <div class="row justify-content-center ml-0 mr-0 mt-3 rounded-top">
        <div class="col-auto">
          <label><img src="../images/Contatti.png" alt="Contact logo" class="img-responsive" height="30" width="30" /> Telefono:</label>
        </div>
        <div class="col-auto">
          <label>333 3333333</label>
        </div>
      </div>

      <div class="container w-70" style="height: 400px;" id="googleMap"></div>

      <div class="row justify-content-center ml-0 mr-0 mt-3 rounded-top bg-dark">
        <div class="col-auto text-white">
          Sentiti libero di contattarci
        </div>
      </div>
      <div class="container col-md-6 col-12 mt-3" id="formContainer">
        <form id="contactUs" action="contact.php" method="post" autocomplete="on" >
          <div class="form-group row justify-content-center">
            <label for="email" class="col-auto col-form-label">Inserisci la tua email</label>
            <div class="col-auto">
              <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
            </div>
          </div>
          <div class="form-group row justify-content-center">
            <label for="message" class="col-auto col-form-label">Messaggio</label>
            <div class="col-12 col-md-8">
              <textarea class="form-control" cols="100" rows="4" id="message" name="message" placeholder="Type your text here . . ."></textarea>
            </div>
          </div>
          <div class="form-group row justify-content-center">
            <div class="col-auto">
              <button type="submit" class="btn btn-primary">Invia</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <?php require_once('../default/footer.php'); ?>

    <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>

    function myMap() {
      let mapCanvas = document.getElementById("googleMap");
      let myCenter = new google.maps.LatLng(44.147619, 12.236213);
      let mapOptions = {center: myCenter, zoom: 12};
      let map = new google.maps.Map(mapCanvas,mapOptions);
      let marker = new google.maps.Marker({
        position: myCenter,
        animation: google.maps.Animation.BOUNCE
      });
      marker.setMap(map);

      google.maps.event.addListener(marker,'click',function() {
        let infowindow = new google.maps.InfoWindow({
          content:"Noi siamo qui!"
        });
        infowindow.open(map,marker);
      });

      google.maps.event.addListener(marker,'click',function() {
        map.setZoom(17);
        map.setCenter(marker.getPosition());
      });
    }

    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
      $(".alert").slideUp(500);
    });

    $("#contactUs").submit(function(e) {
      e.preventDefault();
      $.confirm({
        title: 'Informazione!',
        content: 'Sicuro di voler inviarci una mail?',
        buttons: {
          conferma: function () {
            e.currentTarget.submit();
          },
          annulla: function () {
          },
        }
      });
    });

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAKXFW6aoedfGVTcFDJWt03fKo2ttRdGjk&callback=myMap"></script>

  </body>
  </html>
