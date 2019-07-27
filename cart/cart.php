<?php
require_once('../utility/utility.php');
require_once('../utility/db.php');
require_once('../utility/html-components.php');
if (session_status() == PHP_SESSION_NONE) {
    sec_session_start();
}

if(login_check($conn) != true) {
  header('Location: /login/login.php');
  return;
}else {
  if($_SESSION['privileges'] != 0) header('Location: /admin/order-list-admin.php');
}

if(isset($_SESSION,$_SESSION["username"],$_SESSION["cart"])){
  if(!isset($_SESSION['products'])) {
    $sql = "SELECT *
    FROM product AS p
    JOIN category AS c ON p.idCategory=c.idCategory";
    $_SESSION["products"]=getArray($conn->query($sql));
  }
  $prodInCart = array();
  $_SESSION["nProd"]=0;
  $_SESSION["totCarrello"]=0;
  foreach($_SESSION["cart"] as $cartProd){
    foreach($_SESSION["products"] as $prod ){
      if(strcmp($cartProd["code"],$prod["code"]) === 0){
        $code=$prod["code"];
        $name=$prod["nameProduct"];
        $price=$prod["price"];
        $description=$prod["description"];
        $imagePath=$prod["imagesPath"];
        $qt=$cartProd["qt"];
        $_SESSION["nProd"]++;
        $_SESSION["totCarrello"]+=($price*$qt);
        array_push($prodInCart,array("code"=>$code,"nameProduct"=>$name,"price"=>$price,"description"=>$description,"qt"=>$qt,"imagesPath"=>$imagePath));
        break;
      }
    }
  }
}else header('Location: ../primaryPages/ourProducts.php?CATEGORY=tutti');

?>

<!DOCTYPE html>
<html lang="it">
<head>
  <title>InzenirDlaPida_Carrello</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
</head>
<body>

  <?php
  require_once('../default/nav.php');
  if(isset($_SESSION,$_SESSION["cartEmpty"])){
    echo '
    <div class="alert alert-danger" role="alert">
    <strong>Errore!</strong> Il carrello è vuoto
    </div>';
    unset($_SESSION["cartEmpty"]);
  }else{
    if(isset($_SESSION,$_SESSION["savedCorrectly"])){
      echo '
      <div class="alert alert-success" role="alert">
      <strong>Complimenti!</strong> Prodotto e quantità salvati con successo
      </div>';
      unset($_SESSION["savedCorrectly"]);
    }
  }
  ?>

  <div class="w-100" id="PAGE-BODY" style="padding-bottom:13%">
    <div class="row container-fluid justify-content-center mt-3 w-100 mr-0 ml-0 pl-3 pr-3">
      <div class="col-md-8 p-0 pb-4" style="background-color:	#F8F8FF">
        <div class="row justify-content-center rounded-top w-100 mr-0 ml-0" style="background-color:grey">
          <div class="col-auto text-white">Carrello</div>
        </div>
        <?php
        if($_SESSION["nProd"] == 0){
          echo '
          <div class="row justify-content-center w-100 mr-0 ml-0 pt-3">
            <div class="col-auto">Carrello vuoto! Nessun prodotto selezionato.</div>
          </div>
          ';
        }
        foreach ($prodInCart as $product) {
          echo getCartProduct($product['code'],$product['nameProduct'],$product["price"],$product['qt'], $product['description'],$product["imagesPath"]);
        }
        ?>
        <div class="row m-3 pl-0">
            <button type="button" class="SAVE btn btn-success btn w-25 " name="add to cart">Salva</button>

        </div>
      </div>
      <div class="col-1"></div>
      <div class="col-md-3 pr-0 pl-0 ">
        <div class="row justify-content-center rounded-top w-100 mr-0 ml-0" style="background-color:grey">
          <div class="col-auto text-white">Riepilogo</div>
        </div>
        <div class="row align-items-center justify-content-between mr-0 ml-0 pt-4 w-100" style="background-color:	#F8F8FF">
          <div class="col-auto" style="font-size:150%"> <label>Totale</label> <span class="badge badge-light pl-1 text-white" style="background-color:red" id="TOT_PRODOTTI"><?php echo $_SESSION["nProd"]; ?></span></div>
          <div class="col-3" id="TOTALE" ><?php echo number_format($_SESSION["totCarrello"],2,'.','').'€'; ?></div>
        </div>
        <div class="row align-items-center justify-content-center mr-0 ml-0 pt-4 w-100 " style="background-color:	#F8F8FF">
          <button type="button" class="COMPLETE btn btn-warning  h-100 btn-sm pr-2 pl-4 w-100" name="completa-ordine">Completa ordine</button>
        </div>

      </div>
    </div>
  </div>

  <?php require_once('../default/footer.php');?>

  <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
  <script src="cart.js" ></script>

</body>
</html>
