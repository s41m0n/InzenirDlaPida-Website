 <?php
require_once('../utility/db.php');
require('../utility/utility.php');
require('../utility/html-components.php');
define("TOT-PROD","10");

sec_session_start();

if(isset($_SESSION['privileges']) && $_SESSION['privileges'] != 0) header('Location: /default/goBackAdmin.html');

if(isset($_GET['CATEGORY'])){
  $category=$_GET["CATEGORY"];
  if(!isset($_SESSION["products"])){
    $sql = "SELECT *
    FROM product AS p
    JOIN category AS c ON p.idCategory=c.idCategory";
    $_SESSION["products"]=getArray($conn->query($sql));
  }

  if($category == "Tutti"){
    $productShown=$_SESSION["products"];
  }else{
    $productShown= array();
    foreach ($_SESSION["products"] as $product) {
      if($product["nameCategory"] == $category){
        array_push($productShown,$product);
      }
    }

  }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <title>InzenirDlaPida_Prodotti</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
</head>
<body>
  <?php require '../default/nav.php';
  if(isset($_GET['error'])) {
    echo '
    <div class="alert alert-danger">
    <strong>Errore!</strong>il prodorro non è stato caricato correttamente.
    </div>
    ';
  }

  if(isset($_GET['ok'])) {
    echo '
    <div class="alert alert-success">
     Prodotto aggiunto correttamente al carrello.
    </div>
    ';
  }
  ?>
  <div class="w-100 pt-4" id="PAGE-BODY" style="padding-bottom:13%">

    <div class="container-fluid w-100" id=NOSTRI-PRODOTTI >

      <div class="container col-md-6 bg-dark text-white rounded">
        <ul class="nav  justify-content-center">
          <li class="nav-item">
            <a class="nav-link active text-white" href="ourProducts.php?CATEGORY=Tutti">Tutti</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active text-white" href="ourProducts.php?CATEGORY=Piadina">Piadina</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="ourProducts.php?CATEGORY=Crescione">Crescioni</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="ourProducts.php?CATEGORY=Rotolo">Rotoli</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="ourProducts.php?CATEGORY=Bibita">Bibite</a>
          </li>
        </ul>
      </div>

      <div class="container mt-2" style="background-color:	#F8F8FF">
        <div class="row align-items-center bg-dark " id="HEADER" >
          <div class="CATEGORY col font-weight-bold text-white text-center"><?php if(isset($_GET["CATEGORY"])) {echo $_GET["CATEGORY"];} ?></div>
        </div>
        <div class=" d-flex flex-wrap mt-2 ml-1 mr-1" id="PRODUCTS-LIST" >
          <?php
          foreach ($productShown as $product) {
            echo getProduct($product['code'],$product['nameProduct'],$product['price'],$product['imagesPath'], $product['description']);
          }
          ?>
        </div>
      </div>
    </div>
  </div>


    <?php require_once('../default/footer.php');?>

    <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
    <script src="ourProducts.js"></script>
  </body>
  </html>
