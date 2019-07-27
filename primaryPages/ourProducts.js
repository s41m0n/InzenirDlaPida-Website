$(function(){
  $(".alert").fadeTo(2000, 500).slideUp(500, function(){
    $(".alert").slideUp(500);
  });

  $(".ADD").click(function(e){
    let code = $(this).val();
    let qt = $("#"+code).val();
    $.confirm({
      title: 'Informazione!',
      content: 'Sicuro di voler aggiungere il prodotto al carrello?',
      buttons: {
        conferma: function () {
          $.post("addToCart.php",{
            cod: ""+code,
            qt: ""+qt
          }, function(data){
            var category = $(".CATEGORY").text();
            switch (data) {
              case 'ok':
              location.href = 'ourProducts.php?CATEGORY='+category+'&ok=1';
              break;
              case 'error':
              location.href = 'ourProducts.php?CATEGORY='+category+'&error=1';
              break;
              case 'login':
              location.href = '/login/login.php'
              default:
            }
          }
        );
      },
      annulla: function () {
      },
    }
  });
});
});
