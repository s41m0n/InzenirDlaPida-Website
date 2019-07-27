<?php
require('../utility/utility.php');
require('../utility/db.php');
require('../utility/html-components.php');

sec_session_start();
if(login_check($conn) != true) {
  echo 'login';
  return;
}else {
  if($_SESSION['privileges'] != 0) header('Location: /default/goBackAdmin.html');
}

function isProdInCart($cart,$prod,$qt){ // This function checks if the cart contains code recived;
  for($i=0;$i<count($cart);$i++){
    if($cart[$i]["code"] == $prod ){
      $cart[$i]["qt"]+=$qt;
      $_SESSION["cart"]=$cart;
      return true;
    }
  }
  return false;
}


if (isset($_SESSION,$_SESSION["username"],$_POST,$_POST["cod"],$_POST["qt"])){
  $code=$_POST["cod"];
  $qt=$_POST["qt"];
  $cartline= array("code"=>$code,"qt"=>$qt);


  if(isset($_SESSION["cart"])){
    if(!isProdInCart($_SESSION["cart"],$code,$qt)){
      array_push($_SESSION["cart"],$cartline);
    }
  }else{
    $_SESSION["cart"]= array();
    array_push($_SESSION["cart"],$cartline);
  }

  if ($stmt = $conn->prepare("UPDATE user SET shoppingCart = ? WHERE email = ?")) {
    $cartString = cartAsString($_SESSION['cart']);
    $stmt->bind_param('ss', $cartString, $_SESSION['username']);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->affected_rows>0){
      echo 'ok';
    }else{
      echo 'error';
    }
  }
}else{
  echo 'error';
}

 ?>
