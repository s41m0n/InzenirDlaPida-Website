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

if(isset($_POST['owner'], $_POST['type'], $_POST['expiryDate'], $_POST['ccv'], $_POST['number'], $_SESSION['username'])){

  if ($insert_stmt = $conn->prepare("INSERT INTO card (type, cardNumber,
    expiryDate, ccv, owner, email)
    VALUES (?, ?, ?, ?, ?, ?)")) {

      $owner = $_POST['owner'];
      $type = $_POST['type'];
      $expiryDate = $_POST['expiryDate'];
      $ccv = $_POST['ccv'];
      $number = $_POST['number'];
      $username = $_SESSION['username'];

      $insert_stmt->bind_param('ssssss', $type, $number, $expiryDate,
                              $ccv, $owner, $username);

      $insert_stmt->execute();
      $insert_stmt->store_result();

      if($insert_stmt->affected_rows == 0) {
        if(mysqli_errno($conn) == 1062) header('Location: ./cards.php?duplicate=True');
        else header('Location: ./cards.php?error=1');
      }else {
        header('Location: ./cards.php?ok=True');
      }
    }
  }
?>
