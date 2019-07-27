<?php

require '../utility/utility.php';
require '../utility/db.php';
sec_session_start();
if(login_check($conn) != true) {
  header('Location: ../default/needLoginUser.html');
  return;
}else {
  if($_SESSION['privileges'] != 0) header('Location: /default/goBackAdmin.html');
}

if(isset($_POST['address'], $_POST['number'], $_POST['city'], $_POST['cap'], $_POST['province'], $_POST['country'])) {

  if ($insert_stmt = $conn->prepare("INSERT INTO address (nameAddress, email)
  VALUES (?, ?)")) {

    $address = $_POST['address'].':'.$_POST['number'].':'.$_POST['city'].':'.$_POST['cap'].':'.$_POST['province'].':'.$_POST['country'];

    $insert_stmt->bind_param('ss', $address, $_SESSION['username']);

    $insert_stmt->execute();
    $insert_stmt->store_result();

    if($insert_stmt->affected_rows > 0) header('Location: ./addresses.php?ok=True');
    else {
      if(mysqli_errno($conn) == 1062) header('Location: ./addresses.php?duplicate=True');
      else header('Location: ./addresses.php?error=1');
    }
  }
}


?>
