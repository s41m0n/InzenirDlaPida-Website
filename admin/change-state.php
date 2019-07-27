<?php
require('../utility/db.php');
require('../utility/utility.php');
sec_session_start();
if(login_check($conn) != true) {
  header('Location: /index.php');
  return;
}else {
  if($_SESSION['privileges'] != 1) header('Location: /index.php');
}

if(isset($_POST, $_POST['order'], $_POST['s'])) {
  $order=$_POST["order"];
  $state=$_POST["s"];

  if($sql = $conn->prepare("UPDATE request set orderState = ? WHERE idRequest = ?")){
    $sql->bind_param('si', $state, $order);
    $sql->execute();
    $sql->store_result();

    if($sql->affected_rows > 0) {
      $not = ''.$order.' '.date("H:i:s").' '.$state;
      if($sql = $conn->prepare("UPDATE user AS u
        INNER JOIN request AS r
        ON r.email = u.email
        SET u.notifications = CONCAT_WS(',', u.notifications, ?)
        WHERE r.idRequest = ?")) {

          $sql->bind_param('si', $not, $order);
          $sql->execute();
          if($sql = $conn->prepare("SELECT email FROM request WHERE idRequest = ?")) {

              $sql->bind_param('i', $order);
              $sql->execute();
              $sql->store_result();
              $sql->bind_result($username);
              $sql->fetch();
              $msg = '
              <strong>
              <p>Il tuo ordine '.$order.' è passato in stato '.$state.'</p>
              </strong>';
              sendMail("InzenirDlaPida", "inzenirdlapida@altervista.org", $username, 'Aggiornamento ordine', $msg);
            }
          }
          echo 1;
        } else echo 0;
      }
    } else echo 2;
    ?>
