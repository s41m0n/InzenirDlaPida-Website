<?php
require_once('../utility/html-components.php');
require_once('../utility/utility.php');
require_once('../utility/db.php');
if (session_status() == PHP_SESSION_NONE) {
    sec_session_start();
}

if(login_check($conn) != true) {
  header('Location: /login/login.php');
  return;
}else {
  if($_SESSION['privileges'] != 0) header('Location: /admin/order-list-admin.php');
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <title>InzenirDlaPida_Ordini</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
</head>
<body>

<?php

  require_once('../default/nav.php');

  if(isset($_GET['error'])) {
    echo '
    <div class="alert alert-danger">
    <strong>Errore!</strong> Il tuo ordine è già stato completato o è in preparazione
    </div>
    ';
  }

  if(isset($_GET['undo'])) {
    echo '
    <div class="alert alert-success">
    <strong>Complimenti!</strong> Ordine annullato con successo.
    </div>
    ';
  }

  if(isset($_GET['ok'])) {
    echo '
    <div class="alert alert-success">
    <strong>Complimenti!</strong> Hai effettuato con successo l\'ordine
    </div>
    ';
  }
?>

  <div class="w-100 pt-4" id="PAGE-BODY" style="padding-bottom:13%">

    <div class="container-fluid w-100" id="ORDINI-ADMIN" >
      <div class="row justify-content-center ml-0 mr-0 rounded-top bg-dark">
        <div class="col-auto text-white">
          Lista Ordini richiesti
        </div>
      </div>
      <div class="container-fluid w-100 pl-0 pr-0">
        <div class="row justify-content-center w-100 mr-0 ml-0">
          <ul class="list-group w-100">

            <?php

            $detailShown = array();
            $orderShown = array();

            if($sql = $conn->prepare("SELECT idRequest, deliveryAddress, deliveryTime, orderState FROM request WHERE email = ?")){
              $sql->bind_param('s', $_SESSION['username']);
              $sql->execute();
              $sql->store_result();
              $sql->bind_result($idRequest, $deliveryAddress, $delivery, $state);

              while($sql->fetch()) {
                $tmp = array();
                list($address, $number, $city, $cap, $province, $country) = explode(":",$deliveryAddress);
                $tmp['idRequest'] = $idRequest;
                $tmp['address'] = $address;
                $tmp['number'] = $number;
                $tmp['city'] = $city;
                $tmp['deliveryTime'] = $delivery;
                $tmp['orderState'] = $state;
                array_push($orderShown, $tmp);
              }
            }

            if($sql = $conn->prepare("SELECT r.idRequest, p.nameProduct, rc.quantity FROM product AS p, requestComposition AS rc, request AS r WHERE rc.code=p.code AND r.idRequest=rc.idRequest AND r.email= ?")){
              $email = $_SESSION['username'];
              $sql->bind_param('s', $email);
              $sql->execute();
              $sql->store_result();
              $sql->bind_result($idRequest, $nameProduct, $quantity);

              while($sql->fetch()) {
                $tmp = array();
                $tmp['idRequest'] = $idRequest;
                $tmp['nameProduct'] = $nameProduct;
                $tmp['quantity'] = $quantity;
                array_push($detailShown, $tmp);
              }
            }

            foreach ($orderShown as $order) {
              $details = array();
              foreach ($detailShown as $detail){
                if($order['idRequest'] == $detail['idRequest']){
                  array_push($details, $detail);
                }
              }
              echo getOrderUser($order['idRequest'],$order['address'],$order['number'],$order['city'],$order['deliveryTime'],$order['orderState'], $details);
            }
            ?>

          </ul>
        </div>
      </div>
    </div>
  </div>

  <?php require_once('../default/footer.php');?>

  <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
  <script>
  function undo(idOrder) {
    $.confirm({
      title: 'Informazione!',
      content: 'Sicuro di voler annullare l\'ordine?',
      buttons: {
        conferma: function () {
          $.post("undoOrder.php",{
            idRequest: idOrder
          }, function(e) {
            if(e == 1) location.href = 'orders.php?error=1';
            else if(e == 0) location.href = 'orders.php?undo=True';
          });
        },
        annulla: function () {
        },
      }
    });
  }
  </script>
</body>
</html>
