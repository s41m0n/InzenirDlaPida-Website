<?php

require_once('../utility/utility.php');
require_once('../utility/db.php');
require_once('../default/nav.php');
if (session_status() == PHP_SESSION_NONE) {
    sec_session_start();
}

if(login_check($conn) == true) {
  if($_SESSION['privileges'] == 1) header('Location: /admin/order-list-admin.php');
  else header('Location: /index.php');
  return;
}else {
  if(isset($_GET,$_GET['id'],$_GET['email'])) {
    if ($stmt = $conn->prepare("UPDATE user SET privileges = 0 WHERE email = ?")) {
      $stmt->bind_param('s', $_GET['email']);
      $stmt->execute();
      $stmt->store_result();
      if($stmt->affected_rows > 0) header('Location: login.php?confirmed=True');
      else header('Location: login.php?error=5');
    }
  }
}

 ?>
