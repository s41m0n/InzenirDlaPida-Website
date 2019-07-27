<?php
require('../utility/db.php');
require('../utility/utility.php');
sec_session_start();
if(login_check($conn) != true) {
  header('Location: needLoginAdmin.html');
  return;
}else {
  if($_SESSION['privileges'] != 1) header('Location: "../default/goBackUser.html"');
}

if(isset($_POST, $_POST['name'], $_POST['categories'], $_POST['price'], $_POST['nutritionalValue'], $_POST['description'], $_FILES['myImage'])) {
  if($insert_stmt = $conn->prepare("SELECT idCategory FROM category WHERE nameCategory = ?")) {
    $name = $_POST['categories'];
    $insert_stmt->bind_param('s', $name);
    $insert_stmt->execute();
    $insert_stmt->store_result();
    $insert_stmt->bind_result($idCategory);
    $insert_stmt->fetch();
    if($insert_stmt->affected_rows > 0) {
      if ($insert_stmt = $conn->prepare("INSERT INTO product VALUES(?, ?, ?, ?, ?, ?, ?, ?)")) {
        $discount = 0;
        $path = '/images/products/'.$_FILES['myImage']['name'];
        $code = randomString();
        $insert_stmt->bind_param('ssdsssss', $code, $_POST['name'], $_POST['price'], $_POST['description'], $_POST['nutritionalValue'], $path, $discount, $idCategory);
        $insert_stmt->execute();
        $insert_stmt->store_result();

        if($insert_stmt->affected_rows <= 0) {
          if(mysqli_errno($conn) == 1062) header('Location: product-list-admin.php?duplicate=True');
          else header('Location: product-list-admin.php?error=1');
        }else {
          move_uploaded_file($_FILES['myImage']['tmp_name'], '..'.$path);
          unset($_SESSION['products-admin']);
          header('Location: product-list-admin.php?ok=True');
        }
      }
    }
  }
}
?>
