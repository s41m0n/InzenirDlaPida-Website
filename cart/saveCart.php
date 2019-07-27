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

if(isset($_SESSION,$_SESSION["cart"],$_SESSION["username"], $_POST['cartString'])){
  $cartString=$_POST["cartString"];
  $_SESSION["cart"]=cartAsArray($cartString);
  $email=$_SESSION["username"];
  if ($stmt = $conn->prepare("UPDATE user SET shoppingCart=? WHERE email=?")) {
    $stmt->bind_param('ss', $cartString,$email);
    $stmt->execute();
    $_SESSION["savedCorrectly"]='1';
    echo '0';
  }
}

?>
