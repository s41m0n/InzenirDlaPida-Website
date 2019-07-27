<?php

require_once('../utility/utility.php');
require_once('../utility/db.php');
if (session_status() == PHP_SESSION_NONE) {
  sec_session_start();
}

if(login_check($conn) != true) {
  header('Location: ../login/login.php');
  return;
}else {
  if($_SESSION['privileges'] != 0) header('Location: /admin/order-list-admin.php');
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <title>InzenirDlaPida_Indirizzi</title>
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
    <strong>Complimenti!</strong> Indirizzo aggiunto con successo.
    </div>
    ';
  }

  if(isset($_GET['duplicate'])) {
    echo '
    <div class="alert alert-danger">
    <strong>Errore!</strong> Indirizzo esistente
    </div>
    ';
  }

  if(isset($_GET['deleted']))
  echo '
  <div class="alert alert-success">
  <strong>Complimenti!</strong> Indirizzo rimosso con successo.
  </div>';


  if(isset($_GET['error'])) {
    if($_GET['error'] == 1)
    echo '
    <div class="alert alert-danger">
    <strong>Errore!</strong> Indirizzo non inserito
    </div>';
    else
    echo '
    <div class="alert alert-danger">
    <strong>Errore!</strong> Indirizzo non rimosso
    </div>';
  }

  ?>
  <div class="w-100 pt-4" id="pageBody">

    <div class="container-fluid w-100" id="addresses" >
      <div class="row justify-content-center ml-0 mr-0 rounded-top bg-dark">
        <div class="col-auto text-white">
          I miei indirizzi
        </div>
      </div>
      <div class="row  d-flex justify-content-center flex-wrap  ml-0 mr-0 w-100" >
        <div class="container d-flex justify-content-center flex-wrap pt-2">

          <?php

          if ($stmt = $conn->prepare("SELECT nameAddress FROM address WHERE email = ?")) {
            $stmt->bind_param('s', $_SESSION['username']);
            // Eseguo la query creata.
            $stmt->execute();
            $stmt->bind_result($iperAddress);
            $stmt->store_result();

            while($stmt->fetch()) {

              list($address, $number, $city, $cap, $province, $country ) = explode(":", $iperAddress);

              echo '
              <div class="col-md-3 m-2 border rounded pt-2 pb-2">
              <p>
              <span><strong>Via:</strong> '.$address.'</span> <br>
              <span><strong>N.:</strong> '.$number.'</span> <br>
              <span><strong>Città:</strong> '.$city.'  '.$cap.'</span> <br>
              <span><strong>Provincia:</strong> '.$province.'</span> <br>
              <span><strong>Paese:</strong> '.$country.'</span> <br>
              </p>
              <button class="btn btn-primary remove" value="'.$iperAddress.'" type="button" name="deleteAddressG">Rimuovi</button>
              </div>';

            }
          }

          ?>

        </div>
      </div>

      <div class="container-fluid w-100 pt-4" id="FORM-REGISTRAZIONE" >
        <div class="row justify-content-center ml-0 mr-0 rounded-top bg-dark">
          <div class="col-auto text-white">
            Registra nuovo indirizzo
          </div>
        </div>
          <form id='formAdd' class="container-fluid" method='post' action='registerAddress.php'>
            <div class="form-row w-100 pt-2 ml-0 mr-0 pl-4">
              <div class="form-group w-100 pl-0 pt-0">
                <div class="row pt-1 w-100">
                  <div class="col-7 ">
                    <label for="via" class="form-check-label pl-0">Via</label>
                    <input type="text" name='address' class="form-control" placeholder="Via" id="via" required>
                  </div>
                  <div class="col-4">
                    <label for="civico" class="form-check-label pl-0">Civico</label>
                    <input type="number" id="civico" name='number' class="form-control" placeholder="Nr." min="0"  required>
                  </div>
                </div>
                <div class="row pt-1 w-100">
                  <div class="col-7 ">
                    <label for="citta" class="form-check-label pl-0">Città</label>
                    <select class="form-control form-control-sm" id="citta" name='city'>
                      <option value="Cesena">Cesena</option>
                      <option value="Gambettola">Gambettola</option>
                    </select>
                  </div>
                  <div class="col-4">
                    <label for="provincia" class="form-check-label pl-0">Provincia</label>
                    <input type="text" id="provincia" class="form-control" name='province' value="FC" placeholder="Provincia" readonly>
                  </div>
                </div>
                <div class="row pt-1 w-100">
                  <div class="col-7 ">
                    <label for="paese" class="form-check-label pl-0">Paese</label>
                    <input type="text" id="paese" class="form-control" name='country' value="Italia" placeholder="Paese" readonly>
                  </div>
                  <div class="col-4">
                    <label for="cap" class="form-check-label pl-0">CAP</label>
                    <select class="form-control form-control-sm" id="cap" name='cap'>
                      <option value="47521">47521</option>
                      <option value="47522">47522</option>
                      <option value="47035">47035</option>
                    </select>
                  </div>
                </div>
                <div class="row pt-3 w-100">
                  <div class="col-auto">
                    <button type="submit" class="btn btn-primary  " id="register" value="Registra">Registra indirizzo</button>
                  </div>
                </div>
              </div>
            </div>
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
    let addr = $(this).val();
    $.confirm({
      title: 'Informazione!',
      content: 'Sicuro di voler eliminare l\'indirizzo?',
      buttons: {
        conferma: function () {
          $.post("removeAddress.php",{
            address: addr
          }, function(e) {
            if(e == 1) location.href = 'addresses.php?error=2';
            else if(e == 0) location.href = 'addresses.php?deleted=True';
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
      content: 'Sicuro di voler aggiungere l\'indirizzo?',
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
