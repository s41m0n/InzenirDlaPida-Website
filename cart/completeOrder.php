<?php
require('../utility/utility.php');
require('../utility/db.php');
require('../utility/html-components.php');
define('MIN_TIME_LUNCH','12:00');
define('MAX_TIME_LUNCH','15:00');
define('MIN_TIME_DINNER','17:00');
define('MAX_TIME_DINNER','22:00');

if (session_status() == PHP_SESSION_NONE) {
    sec_session_start();
}

if(login_check($conn) != true) {
  header('Location: /login/login.php');
  return;
}else {
  if($_SESSION['privileges'] != 0) header('Location: /admin/order-list-admin.php');
}


if(isset($_SESSION,$_SESSION["cart"],$_SESSION["username"],$_POST,
$_POST["deliveryAddress"],$_POST["deliveryTime"],$_POST["paymentMethod"],$_POST["cardNumber"],$_POST["deliveryMode"])){

  $orderDate = new DateTime();
  $orderDate->add(new DateInterval('PT1H')); // The order has to be done at least 1 hour before

  $deliveryTime = new DateTime($_POST["deliveryTime"]);
  $deliverHour = $deliveryTime->format("H:i");

  $totalPrice = $_SESSION["totCarrello"]+$_SESSION["spedizione"];
  $orderState= 'Attesa';
  $paymentMethod=$_POST["paymentMethod"];
  $email=$_SESSION["username"];
  $cart=$_SESSION["cart"];


  if($deliveryTime < $orderDate  || $deliverHour < MIN_TIME_LUNCH || ($deliverHour>MAX_TIME_LUNCH && $deliverHour<MIN_TIME_DINNER ) || $deliverHour >MAX_TIME_DINNER  ){
    header('Location:./checkOrder.php?errordata=1');
  }else{
    if(strcmp($_POST["deliveryMode"],'myaddress') === 0 ){
      list($way,$num,$city,$prov,$country,$cap) = explode('-',$_POST["deliveryAddress"]);
      $deliveryAddress = ''.$way.':'.$num.':'.$city.':'.$prov.':'.$country.':'.$cap;
    }else{
      $deliveryAddress = ''.$_POST["via"].':'.$_POST["numero"].':'.$_POST["citta"].':'.$_POST["provincia"].':'.$_POST["paese"].':'.$_POST["cap"];
    }

    if(strcmp($_POST["paymentMethod"],'Con carta') === 0){
      $cardNumber=$_POST["cardNumber"];
    }

    if ($stmt = $conn->prepare("INSERT INTO request(idRequest, orderDate, totalPrice, deliveryTime, orderState, paymentMethod, deliveryAddress, email)
    VALUES (NULL,?,?,?,?,?,?,?)")) {
      $date = $orderDate->format('Y-m-d');
      $time = $deliveryTime->format("Y-m-d H:i:s");
      $stmt->bind_param('sdsssss',$date,$totalPrice,$time,$orderState,$paymentMethod,$deliveryAddress,$email);
      $stmt->execute();
      $stmt->store_result();
      if($stmt->affected_rows > 0) {

        if ($stmt = $conn->prepare("SELECT MAX(idRequest) AS maxId FROM request")){
          $stmt->execute();
          $stmt->bind_result($maxId);
          $stmt->store_result();
          $stmt->fetch();

          foreach($cart as $cartline){
            if($stmt = $conn->prepare("INSERT INTO requestComposition (code,idRequest,quantity) VALUES (?,?,?)")){
              $stmt->bind_param('ssi',$cartline["code"],$maxId,$cartline["qt"]);
              $stmt->execute();
              $stmt->store_result();
              if($stmt->affected_rows <= 0) header('Location: ./checkOrder.php?error_order=1');
            }
          }
          unset($_SESSION['totCarrello'], $_SESSION['spedizione'], $_SESSION['nProd'], $_SESSION['savedCorrectly']);
          if ($stmt = $conn->prepare("UPDATE user SET shoppingCart = NULL WHERE email = ?")){
            $stmt->bind_param('s', $_SESSION['username']);
            $stmt->execute();
          }
          unset($_SESSION['nProd'], $_SESSION['totCarrello']);
          $_SESSION['cart'] = array();
          header('Location: /profile/orders.php?ok=1');
        }else header('Location: ./checkOrder.php?error_order=1');
      }else header('Location: ./checkOrder.php?error_order=1');
    }
  }
}
?>
