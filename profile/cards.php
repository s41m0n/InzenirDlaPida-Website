<?php

require_once('../utility/utility.php');
require_once('../utility/db.php');
if (session_status() == PHP_SESSION_NONE) {
    sec_session_start();
}

if(login_check($conn) != true) {
  header('Location: /login/login.php');
  return;
}else {
  if($_SESSION['privileges'] != 0) header('Location: /admin/order-list-admin.php');
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <title>Carrello</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
</head>
<body>


  <?php

  require_once('../default/nav.php');

  if(isset($_GET['ok'])) {
    echo '
    <div class="alert alert-success">
    <strong>Complimenti!</strong> Carta aggiunta con successo.
    </div>
    ';
  }

  if(isset($_GET['duplicate'])) {
    echo '
    <div class="alert alert-danger">
    <strong>Errore!</strong> Carta esistente
    </div>
    ';
  }

  if(isset($_GET['deleted']))
    echo '
      <div class="alert alert-success">
        <strong>Complimenti!</strong> Carta rimossa con successo.
      </div>';

  if(isset($_GET['error'])) {
    if($_GET['error'] == 1)
      echo '
        <div class="alert alert-danger">
          <strong>Errore!</strong> Carta non inserita
        </div>';
    else
      echo '
        <div class="alert alert-danger">
          <strong>Errore!</strong> Carta non rimossa
        </div>';
  }

  ?>
  <div class="w-100 pt-4" id="PAGE-BODY" style="padding-bottom:13%">

    <div class="container-fluid w-100" id="MIE-CARTE" >
      <div class="row justify-content-center ml-0 mr-0 rounded-top bg-dark">
        <div class="col-auto text-white">
          Le mie carte
        </div>
      </div>
      <div class="container-fluid w-100 pl-0 pr-0">
        <div class="row justify-content-center w-100 mr-0 ml-0">
          <ul class="list-group w-100">

              <?php
              if(isset($_SESSION['username'])) {

                if ($stmt = $conn->prepare("SELECT type, cardNumber, expiryDate, owner FROM card WHERE email = ?")) {
                  $stmt->bind_param('s', $_SESSION['username']);
                  $stmt->execute();
                  $stmt->bind_result($type, $cardNumber, $expiryDate, $owner);
                  $stmt->store_result();

                  while($stmt->fetch()) {
                    echo '
                    <li class="list-group-item">
                    <div class="row">
                      <div class="col-md-3">
                        <label ><strong>Carta:</strong>
                        <span>'.$cardNumber.'</span>
                        </label>
                      </div>
                      <div class="col-md-2">
                        <label ><strong>Tipo:</strong>
                        <span>'.$type.'</span>
                        </label>
                      </div>
                      <div class="col-md-2">
                        <label ><strong>Scadenza:</strong>
                        <span>'.$expiryDate.'</span>
                        </label>
                      </div>
                      <div class="col-md-3">
                        <label><strong>Intestatario:</strong>
                        <span>'.$owner.'</span>
                        </label>
                      </div>
                      <div class="col-md-2">
                        <button type="button" name="removeCard" value="'.$cardNumber.'" id="'.$cardNumber.'" class="btn btn-primary remove" >Elimina carta</button>
                      </div>
                    </div>
                    </li>
                    ';
                  }
                }
              }
              ?>

        </ul>
      </div>
    </div>
  </div>

  <div class="container-fluid w-100 pt-4" id="registerCard" >
    <div class="row justify-content-center ml-0 mr-0 rounded-top bg-dark">
      <div class="col-auto text-white">
        Registra una nuova carta
      </div>
    </div>

    <div class="row ml-0 mr-0 p-3 ">
      <form class="container-fluid" method="post" id="formAdd" action="addCard.php">
        <div class="form-inline justify-content-between">
          <div class="form-group col-md-2 ">
            <label for="owner">Intestatario:</label>
            <input type="text" class="form-control" name="owner" id="owner" placeholder="Mario Rossi" required>
          </div>
          <div class="form-group col-md-2 ">
            <label for="number">Numero Carta:</label>
            <input type="text" pattern=".{16,16}" maxlength="16" class="form-control" name="number" id="number" placeholder="0101010101010101" required>
          </div>
          <div class="form-group col-md-2 ">
            <label for="sel1">Tipo carta:</label>
            <select class="form-control form-group" name="type" id="sel1">
              <option>MasterCard</option>
              <option>AmericanExpress</option>
              <option>Visa</option>
            </select>
          </div>
          <div class="form-group col-md-2 ">
            <label for="expiryDate">Scadenza:</label>
            <input type="date" class="form-control w-70" name="expiryDate" id="expiryDate" required>
          </div>
          <div class="form-group col-md-2 ">
            <label for="ccv">CCV:</label>
            <input type="text" class="form-control " name="ccv" id="ccv" placeholder="111" required>
          </div>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Registra</button>
      </form>
    </div>

  </div>


</div>

<?php require_once('../default/footer.php');?>

<script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
<script>
$(".remove").click(function(e) {
  let nmb = $(this).val();
  $.confirm({
    title: 'Informazione!',
    content: 'Sicuro di voler eliminare la carta?',
    buttons: {
      conferma: function () {
        $.post("removeCard.php",{
          number: nmb
        }, function(e) {
          if(e == 1) location.href = 'cards.php?error=2';
          else if(e == 0) location.href = 'cards.php?deleted=True';
        });
      },
      annulla: function () {
      },
    }
  });
});

$("#formAdd").submit(function(e) {
  e.preventDefault();
  $.confirm({
    title: 'Informazione!',
    content: 'Sicuro di voler aggiungere la carta?',
    buttons: {
      conferma: function () {
        e.currentTarget.submit();
      },
      annulla: function () {
      },
    }
  });
});

</script>

</body>
</html>
