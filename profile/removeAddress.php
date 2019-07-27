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
if(isset($_POST, $_POST['address'], $_SESSION['username'])) {

  if ($stmt = $conn->prepare("DELETE FROM address WHERE email = ? AND nameAddress= ? ")) {
    $stmt->bind_param('ss', $_SESSION['username'], $_POST['address']);
    // Eseguo la query creata.
    $stmt->execute();
    $stmt->store_result();

    if($stmt->affected_rows == 0) echo 1;
    else echo 0;
  }
}

?>
