<?php
require('../utility/utility.php');
require('../utility/db.php');
require('../utility/html-components.php');

sec_session_start();
if(login_check($conn) != true) {
  header('Location: ../default/needLoginUser.html');
  return;
}else {
  if($_SESSION['privileges'] != 0) header('Location: /default/goBackAdmin.html');
}

if(isset($_SESSION,$_SESSION["cart"],$_SESSION["username"], $_POST['code'])){
  $code=$_POST["code"];
  $cart = $_SESSION["cart"];
  $email = $_SESSION["username"];
  for($i=0;$i<count($cart);$i++){
    if(strcmp($cart[$i]["code"],$code) === 0){
      array_splice($_SESSION["cart"],$i,1);
    }
  }
  $cartAsString = cartAsString($_SESSION["cart"]);
  if ($stmt = $conn->prepare("UPDATE user SET shoppingCart=? WHERE email=?")) {
    $stmt->bind_param('ss', $cartAsString,$email);
    $stmt->execute();
    if($stmt->affected_rows == 0) {
      echo 'Error!';
    } else {
      echo '0';
    }
  }
}

 ?>
