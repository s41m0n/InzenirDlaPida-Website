<?php

require '../utility/db.php';
require '../utility/utility.php';
sec_session_start();
if(login_check($conn) != true) {
  header('Location: /default/needLoginUser.html');
  return;
}else {
  if($_SESSION['privileges'] != 0) header('Location: /default/goBackAdmin.html');
}

if(isset($_POST, $_POST['number'], $_SESSION['username'])) {

  if ($stmt = $conn->prepare("DELETE FROM card WHERE cardNumber = ? AND email = ? ")) {
    $stmt->bind_param('ss', $_POST['number'], $_SESSION['username']);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->affected_rows <= 0) echo 1;
    else echo 0;
  }
}

?>
