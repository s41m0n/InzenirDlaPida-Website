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

if(isset($_POST,$_POST['idRequest'], $_SESSION['username'])) {

//decline, wait
  if ($insert_stmt = $conn->prepare("UPDATE request SET orderState='Annullato' WHERE email = ? AND idRequest= ? AND orderState = 'Attesa' ")) {

    $insert_stmt->bind_param('si', $_SESSION['username'], $_POST['idRequest']);
    $insert_stmt->execute();
    $insert_stmt->store_result();

    if($insert_stmt->affected_rows <= 0) {
      echo 1;
    }else {
      echo 0;
    }
  }
}

?>
