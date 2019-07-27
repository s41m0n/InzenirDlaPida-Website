<?php

  require('../utility/utility.php');
  require('../utility/db.php');
  sec_session_start();

  if(login_check($conn) != true) {
    header('Location: /index.php');
    return;
  }else {
    // Recupera i parametri di sessione.
    $params = session_get_cookie_params();
    // Cancella i cookie attuali.
    if(isset($_SESSION['cart']) && $_SESSION['cart'].length != 0) {
      if ($insert_stmt = $conn->prepare("UPDATE user SET cart = ? WHERE email = ?")) {
        $cart = cartAsString($_SESSION['cart']);
        $email = $_SESSION['username'];
        print_r($cart);
        echo '<br>';
        $insert_stmt->bind_param('ss', $cart, $email);
        $insert_stmt->execute();
        $insert_stmt->store_result();

        if($insert_stmt->affected_rows > 0) {
          echo 'ok';
        }
      }
    }
    // Elimina tutti i valori della sessione.
    $_SESSION = array();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    // Cancella la sessione.
    session_destroy();
    header('Location: /index.php');
  }

?>
