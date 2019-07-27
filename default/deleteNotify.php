<?php

require '../utility/db.php';
require '../utility/utility.php';
sec_session_start();
if(login_check($conn) != true) {
  header('Location: ../default/needLoginUser.html');
  return;
}else {
  if($_SESSION['privileges'] != 0) header('Location: /default/goBackAdmin.html');
}

if(isset($_SESSION, $_SESSION['username'])) {
  if($insert_stmt = $conn->prepare("UPDATE user SET notifications = NULL WHERE email = ?")) {
    $insert_stmt->bind_param('s', $_SESSION['username']);
    $insert_stmt->execute();
  }
}
 ?>
