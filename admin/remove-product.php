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

if(isset($_POST, $_POST['product'])) {
  $product = $_POST["product"];

  if($sql = $conn->prepare("DELETE FROM product WHERE code = ?")){

    $sql->bind_param('s', $product);
    $sql->execute();
    $sql->store_result();

    if($sql->affected_rows > 0) {
      unset($_SESSION["products-admin"]);
      echo 1;
    } else echo 0;
  } else echo 3;
} else echo 2;
?>
