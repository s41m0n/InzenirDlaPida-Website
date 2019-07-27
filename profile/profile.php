<!DOCTYPE html>
<html lang="it">
<head>
  <title>InzenirDlaPida_Profile</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>


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

  require_once('../default/nav.php');

  ?>
  <div class="w-100  pt-4" id="PAGE-BODY" style="padding-bottom:13%">

    <div class="container-fluid w-100 " >
      <div class="row justify-content-center ml-0 mr-0 rounded-top bg-dark">
        <div class="col-auto text-white">
          Il mio profilo
        </div>
      </div>

      <div class="container-fluid w-100" id="FUNZIONALITA-PROFILO">
        <div class="row justify-content-center w-100 pt-4 mr-0 ml-0">
          <div class="col-md-4 border border-dark rounded pt-2 pb-2">
            <a href="orders.php">
              <div class="media">
                <img class="mr-3" src="../images/OrderBox.png" alt="Generic placeholder image">
                <div class="media-body">
                  <h5 class="mt-0">I miei ordini</h5>
                  Gestisci e monitora lo stato dei tuoi ordini.
                </div>
              </div>

            </a>
          </div>
          <div class="col-md-1 mt-2"></div>
          <div class="col-md-4 border border-dark rounded pb-2">
            <a href="profileSettings.php">
              <div class="media">
                <img class="mr-3" src="../images/Settings.png" alt="Generic placeholder image">
                <div class="media-body">
                  <h5 class="mt-0">Impostazioni profilo</h5>
                  Modifica email, numero di telefono e password.
                </div>
              </div>
            </a>
          </div>
        </div>
        <div class="row justify-content-center w-100 pt-2 mr-0 ml-0">
          <div class="col-md-4 border border-dark rounded pt-2 pb-2" >
            <a href="cards.php">
              <div class="media">
                <img class="mr-3" src="../images/CreditCard.png" alt="Generic placeholder image">
                <div class="media-body">
                  <h5 class="mt-0">Le mie carte</h5>
                  Controlla tutte le tue carte e registrane di nuove.
                </div>
              </div>
            </a>
          </div>
          <div class="col-md-1 mt-2"></div>
          <div class="col-md-4 border border-dark rounded pt-2 pb-2">
            <a href="addresses.php">
              <div class="media">
                <img class="mr-3" src="../images/FindUs.png" alt="Generic placeholder image">
                <div class="media-body">
                  <h5 class="mt-0">I miei indirizzi</h5>
                  Dove vuoi ricevere la tua merce? Registra nuovi indirizzi oppure eliminane di già esistenti
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>

    </div>

  </div>
  <?php require_once('../default/footer.php');?>

  <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>
