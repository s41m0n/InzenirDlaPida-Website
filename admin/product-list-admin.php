<!DOCTYPE html>
<html lang="it">
<head>
  <title>Inzenirdlapida_ProdottiAdmin</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
</head>
<body>

  <?php
  require 'nav-admin.php';
  require '../utility/utility.php';
  require '../utility/db.php';
  sec_session_start();
  if(login_check($conn) != true) {
    header('Location: /login/login.php');
    return;
  }else {
    if($_SESSION['privileges'] != 1) header('Location: /index.php');
  }

  if(isset($_GET['error'])){
    switch ($_GET['error']) {
      case 0: {
        echo '
        <div class="alert alert-danger">
        <strong>Errore!</strong> Prodotto è in un ordine
        </div>
        ';
        break;
      }
      case 1: {
        echo '
        <div class="alert alert-danger">
        <strong>Errore!</strong> Prodotto non aggiunto
        </div>
        ';
        break;
      }
      case 2: {
        echo '
        <div class="alert alert-danger">
        <strong>Errore!</strong> Prodotto non aggiornato
        </div>
        ';
        break;
      }
      default: {
        break;
      }
    }
  }

  if(isset($_GET['ok'])) {
    echo '
    <div class="alert alert-success">
    <strong>Complimenti!</strong> Prodotto aggiunto con successo.
    </div>
    ';
  }

  if(isset($_GET['duplicate'])) {
    echo '
    <div class="alert alert-danger">
    <strong>Errore!</strong> Prodotto esistente
    </div>
    ';
  }

  if(isset($_GET['updated'])){
    echo '
    <div class="alert alert-success">
    <strong>Complimenti!</strong> Prodotto aggiornato con successo.
    </div>
    ';
  }

  if(isset($_GET['deleted'])) {
    echo '
    <div class="alert alert-success">
    <strong>Complimenti!</strong> Prodotto rimosso con successo.
    </div>
    ';
  }

  ?>

  <div class="w-100 pt-4" id="PAGE-BODY" style="padding-bottom:13%">
    <div class="container-fluid w-100" id="PRODOTTI-ADMIN" >
      <div class="container col-md-6 bg-dark rounded">
        <ul class="nav  justify-content-center">
          <li class="nav-item">
            <a class="nav-link active text-white" href="product-list-admin.php?CATEGORY=Tutti">Tutti</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active text-white" href="product-list-admin.php?CATEGORY=Piadina">Piadina</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="product-list-admin.php?CATEGORY=Crescione">Crescioni</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="product-list-admin.php?CATEGORY=Rotolo">Rotoli</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="product-list-admin.php?CATEGORY=Bibita">Bibite</a>
          </li>
        </ul>
      </div>

      <div class="row justify-content-center ml-0 mr-0 mt-2 rounded-top bg-dark">
        <div class="col-auto text-white">
          Lista Prodotti
        </div>
      </div>
      <div class="container-fluid w-100 pl-0 pr-0">
        <div class="row justify-content-center w-100 mr-0 ml-0">
          <ul class="list-group w-100">

            <?php
            require('../utility/html-components.php');

            $category;
            $sql;
            $productShown;

            if(isset($_GET["CATEGORY"])){
              $category=$_GET["CATEGORY"];
            }

            if(!isset($_SESSION["products-admin"])){
              $_SESSION["products-admin"] = array();
              if($sql = $conn->prepare("SELECT code, nameProduct, price, imagesPath, nameCategory FROM product AS p JOIN category AS c ON p.idCategory=c.idCategory")){
                $sql->execute();
                $sql->bind_result($code, $nameProduct, $price, $imagesPath, $nameCategory);
                $sql->store_result();

                while($sql->fetch()) {
                  $tmp = array();
                  $tmp['code'] = $code;
                  $tmp['nameProduct'] = $nameProduct;
                  $tmp['price'] = $price;
                  $tmp['imagesPath'] = $imagesPath;
                  $tmp['nameCategory'] = $nameCategory;
                  array_push($_SESSION["products-admin"],$tmp);
                }
              }
            }

            $productShown = $_SESSION["products-admin"];

            if(isset($category)){
              if($category != "Tutti"){
                $productShown= array();
                foreach ($_SESSION["products-admin"] as $product) {
                  if($product["nameCategory"] == $category){
                    array_push($productShown,$product);
                  }
                }
              }
            }

            foreach ($productShown as $product) {
              echo getProductAdmin($product['code'],$product['nameProduct'],$product['price'],$product['imagesPath']);
            }
            ?>

          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid w-100 pt-4" id="registerCard" >
    <div class="row justify-content-center ml-0 mr-0 rounded-top bg-dark">
      <div class="col-auto text-white">
        Registra nuovo Prodotto
      </div>
    </div>
    <div class="row ml-3 mr-0 pt-4 align-items-center">
      <form enctype="multipart/form-data"  id="formAdd" method="post" action="addProduct.php">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group mr-5">
              <label for="name">Nome Prodotto:</label>
              <input type="text" class="form-control" name="name" id="name" placeholder="Piadina Semplice" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group mr-5">
              <label for="categories">Categoria:</label>
              <select class="form-control form-group" name="categories" id="categories">

                <?php

                if ($stmt = $conn->prepare("SELECT nameCategory FROM category")) {
                  $stmt->execute();
                  $stmt->bind_result($nameCategory);
                  $stmt->store_result();

                  while($stmt->fetch()) {
                    echo '<option>'.$nameCategory.'</option>';
                  }
                }
                ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="price">Prezzo(€):</label>
              <input type="number" step="0.10" class="form-control w-50" min="0.10" name="price" id="price" placeholder='2,10' required>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group mr-5">
              <label for="nutritionalValue">Valori Nutrizionali(Kcal):</label>
              <input type="number" step="20" class="form-control" min="20" name="nutritionalValue" id="nutritionalValue" placeholder='1000' required>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="path">Percorso Immagine:</label>
              <input type="file" id="path" name="myImage" accept="image/*" required/>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group mr-5">
            <label for="description" class="col-auto col-form-label">Descrizione:</label>
            <div class="col-auto">
              <textarea class="form-control mt-1" id="description" cols="80" rows="4" name="description" placeholder="Inserisci la descrizione . . ."></textarea>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary " id="register">Registra</button>
      </form>
    </div>
  </div>

<?php require_once('footer-admin.php');?>

<script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="function.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>

</body>
</html>
