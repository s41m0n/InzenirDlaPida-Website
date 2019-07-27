<!DOCTYPE html>
<html lang="it">
<head>
  <title>InzenirDlaPida_Home</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    .PRODUCT{
      border-bottom: 1px solid grey;
    }
  </style>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

  <?php
  require_once('./utility/utility.php');
  require_once('./utility/db.php');
  if (session_status() == PHP_SESSION_NONE) {
      sec_session_start();
  }
  if(isset($_SESSION, $_SESSION['privileges']) && $_SESSION['privileges'] != 0) header('Location: /admin/order-list-admin.php');

  require_once('./default/nav.php');
  ?>

  <div  id="PAGE-BODY" style="padding-bottom:13%">

    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img class="d-block w-100" src="./images/Slider1.png" alt="First slide">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="./images/Slider2.png" alt="Second slide">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="./images/Slider3.png" alt="Third slide">
        </div>
      </div>
      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Precedente</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Successiva</span>
      </a>
    </div>

    <div class="container mt-2" id="PRODUCTS-LIST">
      <div class="row align-items-center bg-dark " id="HEADER" >
        <div class="col text-center text-light">Da noi consigliati</div>
      </div>
      <div class=" d-flex flex-wrap mt-2 ml-1 mr-1" >
        <div class="PRODUCT col-md-6 w-100  pl-0 pr-3 pt-4">
          <div class="media">
            <img class="mr-3 w-25" src="images/PiadinaIndex.png" alt="Piadine">
            <div class="media-body">
              <h5 class="mt-0">Piadine</h5>
              <p style="font-size:75%">Per gli amanti della tradizione romagnola la piadina rappresenta il piatto tipico per eccellenza.I veterani la consigliano con crudo squacquerone e ruccola.
                <a href="./primaryPages/ourProducts.php?CATEGORY=Piadina">Vedi di più..</a>
              </p>
            </div>
          </div>
        </div>
        <div class="PRODUCT col-md-6 w-100  pl-0 pr-3 pt-4">
          <div class="media">
            <img class="mr-3 w-25" src="images/CrescioneIndex.png" alt="Crescioni">
            <div class="media-body">
              <h5 class="mt-0">Crescioni</h5>
              <p style="font-size:75%">Il crescione è una tipica preparazione basata sulla piadina dove la sfoglia viene farcita, ripiegata e chiusa prima della cottura. I crescioni più amati dai romagnoli sono alle Erbe oppure Zucca-Patate-Salsiccia.
                <a href="./primaryPages/ourProducts.php?CATEGORY=Crescione">Vedi di più..</a>
               </p>
            </div>
          </div>
        </div>
        <div class="PRODUCT col-md-6 w-100  pl-0 pr-3 pt-4">
          <div class="media">
            <img class="mr-3 w-25" src="./images/RotoloIndex.png" alt="Rotoli">
            <div class="media-body">
              <h5 class="mt-0">Rotoli</h5>
              <p style="font-size:75%">Il rotolo è una delle ultime rivisitazioni della piadina dove la sfoglia viene farcita e arrotolata su se stessa prima di passare alla cottura. Per i rotoli non e consigliamo uno in particolare ma vi invitiamo a dare un'occhiata alla nostra numerosa lista
                . <a href="./primaryPages/ourProducts.php?CATEGORY=Rotolo">Vedi di più..</a></p>
            </div>
          </div>
        </div>
        <div class="PRODUCT col-md-6 w-100  pl-0 pr-3 pt-4">
          <div class="media">
            <img class="mr-3 w-25" src="images/BibitaIndex.png" alt="Bibita">
            <div class="media-body">
              <h5 class="mt-0">Bibite</h5>
              <p style="font-size:75%">Quale può essere il modo miglione per accompagnare una buona piadina se non con una bibita ghiacciata? Dai una occhiata alla nostra lista ne troverai per tutti i gusti.
                <a href="./primaryPages/ourProducts.php?CATEGORY=Bibita">Vedi di più..</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php require_once('./default/footer.php');?>

  <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
