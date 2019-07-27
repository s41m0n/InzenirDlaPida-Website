<?php
require_once('../utility/utility.php');
require_once('../utility/db.php');
require_once('../utility/html-components.php');
define('MIN_NO_SPEDIZIONE','10,00');

if (session_status() == PHP_SESSION_NONE) {
  sec_session_start();
}

if(login_check($conn) != true) {
  header('Location: /index.php');
  return;
}else {
  if($_SESSION['privileges'] != 0) header('Location: /admin/order-list-admin.php');
}


if(!isset($_SESSION,$_SESSION["username"],$_SESSION["cart"],$_SESSION["totCarrello"]) || $_SESSION["nProd"] === 0){
  $_SESSION["cartEmpty"]='1';
  header('Location: cart.php');
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
  <title>InzenirDlaPida_CompletaOrdine</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
  <style>
  #PRODUCT-ROW{
    border-bottom: 1px solid grey;
  }
  </style>
</head>
<body>
  <?php
  require_once('../default/nav.php');
  if(isset($_GET['errordata'])) {
    echo '
    <div class="alert alert-danger">
    <strong>Spiacenti,</strong> Il suo ordine non può essere preso in carica per <strong>l\'ora</strong> e la <strong>data</strong> da lei richiesti.
    </div>
    ';
  }

  if(isset($_GET['error_order'])) {
    echo '
    <div class="alert alert-danger">
    <strong>Errore!</strong> L\'ordine non è stato caricato correttamente.
    </div>
    ';
  }

  ?>
  <div class="w-100" id="PAGE-BODY" style="padding-bottom:5%">
    <form method="post" action="completeOrder.php" id="formOrder">
      <fieldset>
        <div class="row container-fluid justify-content-center mt-3 w-100 mr-0 ml-0 pl-3 pr-3">
          <div class="col-md-8 p-0 pb-4" style="background-color:	#F8F8FF" id="DATI-CONSEGNA">
            <div class="row justify-content-center rounded-top w-100 mr-0 ml-0" style="background-color:grey">
              <legend class="col-auto text-white">Pagamento e consegna</legend>
            </div>
            <div class="form container-fluid pr-0 w-100">
              <div class="form-group" id="DATE_TIME">
                <div class="row w-100  mr-0 ml-0">
                  <div class="col-auto"> <p style="font-size:150%;color:green;text-decoration:underline">Ora e giono:</p> </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="container-fluid p-3">
                      <label class="form-check-label" for="time">
                        Scegli il giorno
                      </label>
                      <input type="datetime-local" id="time" name="deliveryTime" value="" class="DATE_TIME border border-secondary rounded" required>
                    </div>
                  </div>
                  <div class="col-md-6">

                    <?php
                    if(isset($_GET['errordata'])) {
                      echo '
                      <em>Informiamo la gentile clientela che il nostro servizio d\'asporto è disponibile a orario di pranzo dalle <span style="color:red">12:00</span> alle <span style="color:red">15:00</span> e a cena dalle <span style="color:red">18:00</span> alle <span style="color:red">22:00</span>. Inoltre saranno prese in considerazione solo gli ordini richiesti con almeno un\'<span style="color:red" >ora di anticipo</span>. </em>
                      ';
                    }else{
                      echo '
                      <em>Informiamo la gentile clientela che il nostro servizio d\'asporto è disponibile a orario di pranzo dalle 12:00 alle 15:00 e a cena dalle 18:00 alle 22:00. Inoltre saranno prese in considerazione solo gli ordini richiesti con almeno un\'ora di anticipo. </em>
                      ';
                    }

                    ?>
                  </div>
                </div>
              </div>
              <div class="form-group" id="PAYMENT">
                <div class="row w-100  mr-0 ml-0">
                  <div class="col-auto " style="text-decoration:underline"><p style="font-size:150%;color:green;text-decoration:underline">Modalità pagamento:</p></div>
                </div>
                <div class="container-fluid pt-3">
                  <div class="form-inline ">
                    <label class="form-check-label" for="allaConsegna"><input class="ON_DELIVERY form-check-input" type="radio" name="paymentMethod" value="Alla consegna" id="allaConsegna" checked>Pagamento alla consegna</label>
                  </div>
                  <div class="form-inline pt-3" >
                    <label class="form-check-label" for="conCarta"><input class="WITH_CARD form-check-input" type="radio" name="paymentMethod" id="conCarta" value="Con carta">Carta</label>
                    <label for="carte">
                      <select class="SELECT_CARD form-control form-control-sm pl-0 ml-3" id="carte" name="cardNumber">
                        <option>-nessuna selezione-</option>
                        <?php
                        if(isset($_SESSION['username'])) {
                          if ($stmt = $conn->prepare("SELECT cardNumber FROM card WHERE email = ?")) {
                            $stmt->bind_param('s', $_SESSION['username']);
                            $stmt->execute();
                            $stmt->bind_result($cardNumber);
                            $stmt->store_result();

                            while($stmt->fetch()) {
                              echo '<option>'.$cardNumber.'</option>';
                            }
                          }
                        }
                        ?>
                      </select>
                    </label>
                  </div>
                  <div class="form-inline pt-3">
                    <button type="button" class="btn btn-info" name="RegistrCarta" onclick="location.href = '/profile/cards.php'" id="REGISTRA-CARTA">Registra nuova carta</button>
                  </div>
                </div>
              </div>
              <div class="form-group" id="ADDRESS" >
                <div class="row w-100 mr-0 ml-0 pt-2">
                  <div class="col-auto" style="text-decoration:underline"><p style="font-size:150%;color:green;text-decoration:underline">Indirizzo consegna:</p></div>
                </div>
                <div class="container-fluid">
                  <div class="form-inline pt-3">
                    <label class="form-check-label" for="mioIndirizz">
                      <input class="HIDE_OTHER form-check-input" type="radio" name="deliveryMode"  value="myaddress" id="mioIndirizz" checked>
                      I miei indirizzi
                    </label>
                    <label for="indirizzi">
                      <select class="SELECT_ADDRESS form-control form-control-sm ml-3 " id="indirizzi" name="deliveryAddress" required>
                        <option value="">-nessuna selezione-</option>
                        <?php
                        if(isset($_SESSION['username'])) {
                          if ($stmt = $conn->prepare("SELECT nameAddress FROM address WHERE email = ?")) {
                            $stmt->bind_param('s', $_SESSION['username']);
                            $stmt->execute();
                            $stmt->bind_result($address);
                            $stmt->store_result();

                            while($stmt->fetch()) {
                              $addrCopy=$address;
                              list($way,$num,$city,$prov,$country,$cap) = explode(":",$address);
                              echo '<option>'.$way.'-'.$num.'-'.$city.'-'.$prov.'-'.$country.'-'.$cap.'</option>';
                            }
                          }
                        }
                        ?>
                      </select>
                    </label>
                  </div>
                  <div class="form-inline pt-3">
                    <label class="form-check-label" for="altroIndirizzo">
                      <input class="SHOW_OTHER form-check-input" type="radio" name="deliveryMode" id="altroIndirizzo"  value="other">
                      Altro indirizzo
                    </label>
                  </div>
                  <div class="OTHER_ADDRESS form-row w-100 pt-2">
                    <div class="form-group w-100 pl-0 pt-0">
                      <div class="row pt-1 w-100">
                        <div class="col-7 ">
                          <label class=" form-check-label pl-0" for="VIA">Via</label>
                          <input type="text" class="ADDR form-control" placeholder="Via" name="via" id="VIA">
                        </div>
                        <div class="col-4">
                          <label class="form-check-label pl-0" for="NUMERO">Civico</label>
                          <input type="number" class="ADDR form-control" placeholder="Nr." min="1" name="numero" id="NUMERO">
                        </div>
                      </div>
                      <div class="row pt-1 w-100">
                        <div class="col-7 ">
                          <label class="form-check-label pl-0" for="CITTA">Città</label>
                          <select class="ADDR form-control form-control-sm" name="citta" id="CITTA">
                            <option>-nessuna selezione-</option>
                            <option>Cesena</option>
                            <option>Gambettola</option>
                          </select>
                        </div>
                        <div class="col-4">
                          <label class="form-check-label pl-0" for="PROVINCIA">Provincia</label>
                          <input type="text" class="ADDR form-control" value="FC" placeholder="Proincia" readonly name="provincia" id="PROVINCIA">
                        </div>
                      </div>
                      <div class="row pt-1 w-100">
                        <div class="col-7 ">
                          <label class="form-check-label pl-0" for="PAESE">Paese</label>
                          <input type="text" class="ADDR form-control" value="Italia" placeholder="Paese" readonly name="paese" id="PAESE">
                        </div>
                        <div class="col-4">
                          <label class="form-check-label pl-0" for="CAP">CAP</label>
                          <select class="ADDR form-control form-control-sm" name="cap" id="CAP">
                            <option>-nessuna selezione-</option>
                            <option>47521</option>
                            <option>47522</option>
                            <option>47035</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-inline pt-3">
                    <button type="button" class="btn btn-info" name="registra indirizzo" onclick="location.href = '/profile/addresses.php'" id="REGISTRA-INDIRIZZO">Registra nuovo indirizzo</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-1"></div>
          <div class="col-md-3 pr-0 pl-0 ">
            <div class="container-fluid" style="background-color:	#F8F8FF">
              <div class="row align-items-center justify-content-between mr-0 ml-0 pt-2 w-100 ">
                <div class="col-auto" style="font-size:80%"> <label>Articoli</label></div>
                <div class="col-3" id="COSTO-ARTICOLI" ><?php
                echo number_format($_SESSION["totCarrello"],2,'.','')."€" ?></div>
              </div>
              <div class="row align-items-center justify-content-between mr-0 ml-0 pt-2 w-100 ">
                <div class="col-auto" style="font-size:80%"><label>Spese di spedizione</label></div>
                <div class="col-3" id="COSTO-SPEDIZIONE" >
                  <?php
                  if($_SESSION["totCarrello"]>MIN_NO_SPEDIZIONE){
                    $_SESSION["spedizione"]=0.00;
                  }else{
                    $_SESSION["spedizione"]=2.00;
                  }
                  echo number_format($_SESSION["spedizione"],2,'.','');
                  ?>
                  €
                </div>
              </div>
              <div class="row align-items-center justify-content-between mr-0 ml-0 pt-4 w-100 ">
                <div class="col-auto" style="font-size:150%"> <label>Totale</label> <span class="badge badge-light pl-1 text-white" style="background-color:red"><?php echo $_SESSION["nProd"]; ?></span></div>
                <div class="col-3" id="TOTALE" style="font-size:120%" ><span class="TOTALE"><?php  echo number_format(($_SESSION["totCarrello"]+$_SESSION["spedizione"]),2,'.','') ?></span>€ </div>
              </div>
              <div class="row align-items-center justify-content-center mr-0 ml-0 pt-3 w-100 ">
                <button type="submit" class="PROCEED btn btn-warning h-100 btn-sm pr-2 pl-4 w-100">Procedi</button>
              </div>
            </div>
          </div>
        </div>
      </fieldset>
    </form>
  </div>

  <?php
  require_once('../default/footer.php');
  ?>


  <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
  <script src="checkOrder.js"></script>
</body>
</html>
