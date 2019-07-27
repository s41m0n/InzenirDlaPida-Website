<?php
if (session_status() == PHP_SESSION_NONE) {
    sec_session_start();
}

$tmp = '
<header>
<div class="w-100" id="MY_NAV">
  <nav class="navbar navbar-expand-md bg-dark navbar-dark navbar-fixed-top w-100 pl-0 pr-0 pt-4" id="UPPER-NAV">
    <div class="w-100" id="NAV-ROW">
    <div class="row justify-content-between w-100 mr-0 ml-0">
      <div class="col-auto pr-0 pl-1" >
      <button class="navbar-toggler ml-lg-0 ml-1" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon "></span>
      </button>
        <a class="navbar-brand pl-1 mr-0" href="/index.php"><img src="/images/Logo.png" class="img-fluid" alt="Responsive image" height="30" width="30">  InzenirDlaPida</a>
      </div>

      <div class="col-12 col-md-5  pl-0 pr-0">
        <div class="row justify-content-end w-100 mr-0 ml-0 ">
        <div class="col-auto  pr-0 pl-0">
        <div class="dropdown show">';

        $not;
        if(isset($_SESSION, $_SESSION['username'])) {
          if($insert_stmt = $conn->prepare("SELECT notifications FROM user WHERE email = ? AND notifications IS NOT NULL")) {
            $insert_stmt->bind_param('s', $_SESSION['username']);
            $insert_stmt->execute();
            $insert_stmt->store_result();
            $insert_stmt->bind_result($notifications);
            $insert_stmt->fetch();
            if($insert_stmt->num_rows > 0) {
              $not = notifyAsArray($notifications);
              if(count($not) > 0) $tmp.= '
              <span class="badge badge-notify notifications">'.count($not).'</span>';
            }
          }
        }
        if(isset($_SESSION, $_SESSION['cart'])) {
          if(count($_SESSION['cart']) > 0) $tmp.= '
          <span class="badge badge-notify2">'.count($_SESSION['cart']).'</span>';
        }

        $tmp.= '<a class="pl-2 dropdown-toggle" id="menu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="checkedNotify()" href="#"><img src="/images/Notification.png" alt="Responsive image" height="30" width="30" /></a>';
        if(isset($not) && count($not) > 0) {
          $tmp.= '
          <div class="dropdown-menu" aria-labelledby="menu1" style="left: -280px !important;">
          <ul style="font-size: 15px; list-style-type: none; margin: 0; padding: 0;">';
          foreach($not as $notify) {
            $tmp.= '
            <li class="dropdown-item">L\'ordine '.$notify['code'].' è passato in '.$notify['state'].' alle ore '.$notify['date'].'</li>
            <div class="dropdown-divider"></div>';
          }
          $tmp.= '</ul>
          </div>';
        }


        $tmp.= '</div>
        </div>
        <div class="col-auto pl-0 pr-0">
        <div class="dropdown show">
        <a class="dropdown-toggle pl-2" id="menu2" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false" ><img src="/images/User.png" alt="Responsive image" height="30" width="30">  </a>
        <div class="dropdown-menu" aria-labelledby="menu2" style="left: -70px !important;">
        <ul style="list-style-type: none; margin: 0; padding: 0;">
        <li class="dropdown-item" ><a href="/profile/profile.php">Profilo</a></li>
        <li class="dropdown-item" ><a href="/profile/cards.php">Carte</a></li>
        <li class="dropdown-item" ><a href="/profile/addresses.php">Indirizzi</a></li>
        <li class="dropdown-item" ><a href="/profile/profileSettings.php">Dati</a></li>
        <li class="dropdown-item" ><a href="/profile/orders.php">Ordini</a></li>
        <li class="dropdown-item" ><a href="/login/logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
  </div>
  <div class="col-auto pl-0 pr-0 mr-2">
    <a class="pl-2" href="/cart/cart.php"><img src="/images/CartWhite.png" alt="Responsive image" height="30" width="30">  </a>
  </div>
        </div>
      </div>
</div>
    <div class="row w-100 pt-0 pb-0  justify-content-end">';
    if(isset($_SESSION, $_SESSION['username'])) {
      $tmp.= '<p class="text-white mb-0" style="font-size:90%">Bentornato <strong>'.$_SESSION['username'].'</strong></p>';
    } else {
      $tmp.= '<p class="text-white mb-0" style="font-size:90%">Non hai effettuato il login? <a class="text-blue" href="/login/login.php">Entra</a></p>';
    }

$tmp.='
    </div>
    </div>
  </nav>
  <nav class="navbar navbar-expand-md bg-dark navbar-light navbar-fixed-top w-100" >
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link text-white " href="/index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="/primaryPages/ourProducts.php?CATEGORY=Tutti">I nostri prodotti</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="/profile/orders.php">I miei ordini</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="/primaryPages/contact.php">Contatti</a>
        </li>
      </ul>
    </div>
  </nav>
  </div>
</header>';

echo '
<style>
.badge-notify2{
  background:red;
  position:absolute;
  top: -20px;
  left: 129px;
}

.badge-notify{
   background:red;
   position:absolute;
   top: -20px;
   left: 30px;
}
</style>';

echo '
<script>
  function checkedNotify() {
    $(".notifications").hide();
    $.post("/default/deleteNotify.php", {}, function(e) {});
  }
</script>';

echo $tmp;
?>
