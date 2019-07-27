<?php
require('../utility/db.php');
require('../utility/utility.php');

sec_session_start();
if(login_check($conn) != true) {
  header('Location: needLoginAdmin.html');
  return;
}else {
  if($_SESSION['privileges'] != 1) header('Location: /default/goBackUser.html');
}

if(isset($_POST, $_POST['product'], $_POST['price'])) {
  $product=$_POST["product"];
  $price=$_POST["price"];
  $sql;

  if($sql = $conn->prepare("UPDATE product SET price = ? WHERE code = ?")){
    $sql->bind_param('ds', $price, $product);
    $sql->execute();

    if($sql->affected_rows > 0) {
      unset($_SESSION["products-admin"]);
      echo 1;
    } else {
      echo 0;
    }
  }
} else echo 2;
?>
