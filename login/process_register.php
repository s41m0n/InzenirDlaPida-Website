<?php

require('../utility/db.php');
require('../utility/utility.php');

// Recupero la password criptata dal form di inserimento.
$password = $_POST['p'];
// Crea una chiave casuale
$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
// Crea una password usando la chiave appena creata.
$password = hash('sha512', $password.$random_salt);
// Inserisci a questo punto il codice SQL per eseguire la INSERT nel tuo database
// Assicurati di usare statement SQL 'prepared'.

$url =  'https://www.google.com/recaptcha/api/siteverify';
$private_key = '6Ld-xUIUAAAAADpbMiPaq21QDRZZSYYwD1TqtuoC';
$response = file_get_contents($url."?secret=".$private_key."&response=".$_POST['g-recaptcha-response']);
$data = json_decode($response);

if(!isset($data->success) || $data->success == false) header('Location: login.php?captcha=True');
else if ($insert_stmt = $conn->prepare("INSERT INTO user (email, password,
  privileges, shoppingCart, name, surname, birthdate,
  phoneNumber, city, employment, salt)
  VALUES (?, ?, NULL, NULL, ?, ?, ?, ?, ?, ?, ?)")) {

    $insert_stmt->bind_param('sssssssss',$_POST['email'], $password,
    $_POST['name'], $_POST['surname'],
    $_POST['birthdate'], $_POST['phone'],
    $_POST['city'], $_POST['employment'],
    $random_salt);
    // Esegui la query ottenuta.
    $insert_stmt->execute();
    $insert_stmt->store_result();

    if($insert_stmt->affected_rows <= 0) {
      if(mysqli_errno($conn) == 1062) header('Location: ./login.php?duplicate=True');
      else header('Location: ./login.php?error=1');
    }else {
      $msg = '
      <strong>
        <p>Per concludere la tua registrazione, devi cliccare sul link sottostante</p>
        <a href="inzenirdlapida.altervista.org/login/confirmRegistration.php?id=convalida&email='.$_POST['email'].'">CLICCA QUI PER CONVALIDARE LA TUA REGISTRAZIONE!</a>
      </strong>';
      sendMail("InzenirDlaPida", "inzenirdlapida@altervista.org", $_POST['email'], 'Conferma la registrazione', $msg);
      header('Location: ./login.php?registered=True');
    }
  }

  ?>
