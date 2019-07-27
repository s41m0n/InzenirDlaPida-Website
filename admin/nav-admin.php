<?php
  echo '
        <div class="My-nav w-100">
          <nav class="navbar navbar-expand-md navbar-dark bg-dark navbar-fixed-top w-100 ">
            <div class="row container-fluid d-flex justify-content-between" id="NAV-ROW">
              <button class="navbar-toggler ml-lg-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">              <span class="navbar-toggler-icon "></span>
              </button>
              <div class="col-5" >
                <a class="navbar-brand" href="order-list-admin.php"><img src="../images/Logo.png" class="img-fluid" alt="Responsive image" height="30" width="30">  InzenirDlaPida</a></div>
              </div>
          </nav>
          <nav class="navbar navbar-expand-md navbar-light bg-dark navbar-fixed-top w-100">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                  <a class="nav-link text-white " id="ordini" href="order-list-admin.php">Lista ordini</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " id="prodotti" href="product-list-admin.php">Lista prodotti</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " id="logout" href="../login/logout.php">Logout</a>
                </li>
              </ul>
            </div>
          </nav>
        </div>';
?>
