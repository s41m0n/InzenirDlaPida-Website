<?php

  require('../utility/db.php');
  require('../utility/utility.php');

  sec_session_start(); // usiamo la nostra funzione per avviare una sessione php sicura

  if(isset($_POST['loginEmail'], $_POST['p'])) {
    $email = $_POST['loginEmail'];
    $password = $_POST['p']; // Recupero la password criptata.
    if(login($email, $password, $conn)) {
      if(!isset($_COOKIE['email'],$_POST['remember'])) setcookie("email", $email, time()+ 7 * 24 * 60 *60);
      print_r($_SESSION);
      $target = $_SESSION['targetPage'];
      unset($_SESSION['targetPage']);
      header('Location: '.$target);
    } else {
      $error = $_SESSION['error'];
      unset($_SESSION['error']);
      header('Location: ./login.php?error='.$error);
    }
  } else {
   // Le variabili corrette non sono state inviate a questa pagina dal metodo POST.
    echo '<DOCTYPE html>
          <head>
            <title>InzenirDlaPida_WTF</title>
          </head>
          <body>
            Invalid Request
          </body>
    ';
  }

 ?>
