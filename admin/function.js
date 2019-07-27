$(function(){
  $(".alert").fadeTo(2000, 500).slideUp(500, function(){
    $(".alert").slideUp(500);
  });

  let state;

  $.each($(".ITEM"), function(){
    let id = $(this).attr('id');
    state = $(this).find(".STATE").html();
    switch(state){
      case "Attesa":
      $("#"+id+"").find(".DELIVER").prop("disabled",true);
      $("#"+id+"").find(".COMPLETE").prop("disabled",true);
      break;
      case "Preparazione":
      $("#"+id+"").find(".DECLINE").prop("disabled",true);
      $("#"+id+"").find(".MANAGE").prop("disabled",true);
      $("#"+id+"").find(".COMPLETE").prop("disabled",true);
      break;
      case "Consegna":
      $("#"+id+"").find(".DECLINE").prop("disabled",true);
      $("#"+id+"").find(".MANAGE").prop("disabled",true);
      $("#"+id+"").find(".DELIVER").prop("disabled",true);
      break;
    }
  });

  $(".SAVE").click(function(e){
    let prod = $(this).val();
    let p = $("#"+prod).val();
    $.confirm({
      title: 'Informazione!',
      content: 'Sicuro di voler modificare il prodotto?',
      buttons: {
        conferma: function () {
          $.post("save-product.php",{
            product: prod,
            price: p
          }, function(e) {
            if(e == 1) location.href = 'product-list-admin.php?updated=True';
            else if(e==0 || e==2) location.href = 'product-list-admin.php?error=2';
          });
        },
        annulla: function () {
        },
      }
    });
  });

  $(".DELETE").click(function(e){
    var prod = $(this).val();
    $.confirm({
      title: 'Informazione',
      content: 'Sei sicuro di voler eliminare il prodotto?!',
      buttons: {
        conferma: function () {
          $.post("remove-product.php",{
            product: prod
          }, function(e) {
            if(e == 0 || e==2 || e==3) location.href = 'product-list-admin.php?error=0';
            else if(e==1) location.href = 'product-list-admin.php?deleted=True';
          });
        },
        annulla: function () {
        },
      }
    });
  });

  $(".DECLINE").click(function(e){
    let ord = $(this).val();
    let state = "Annullato";
    $.confirm({
      title: 'Informazione',
      content: 'Sei sicuro di voler annullare l\'ordine?!',
      buttons: {
        conferma: function () {
          $.post("change-state.php",{
            order: ord,
            s: state
          }, function(e) {
            if(e == 1) location.href = './order-list-admin.php?declinedOk=True';
            else location.href = './order-list-admin.php?declinedKo=True';
          });
        },
        annulla: function () {
        },
      }
    });
  });

  $(".MANAGE").click(function(e){
    let ord = $(this).val();
    let state = "Preparazione";
    $.confirm({
      title: 'Informazione',
      content: 'Sei sicuro di voler preparare l\'ordine?',
      buttons: {
        conferma: function () {
          $.post("change-state.php",{
            order: ord,
            s: state
          }, function(e) {
            if(e == 1) location.href = 'order-list-admin.php?manageOk=True';
            else location.href = 'order-list-admin.php?manageKo=True';
          });
        },
        annulla: function () {
        },
      }
    });
  });

  $(".DELIVER").click(function(e){
    let ord = $(this).val();
    let state = "Consegna";
    $.confirm({
      title: 'Informazione',
      content: 'Sei sicuro di voler consegnare l\'ordine?',
      buttons: {
        conferma: function () {
          $.post("change-state.php",{
            order: ord,
            s: state
          }, function(e) {
            if(e == 1) location.href = 'order-list-admin.php?deliverOk=True';
            else location.href = 'order-list-admin.php?deliverKo=True';
          });
        },
        annulla: function () {
        },
      }
    });
  });

  $(".COMPLETE").click(function(e){
    let ord = $(this).val();
    let state = "Completato";
    $.confirm({
      title: 'Informazione',
      content: 'Sei sicuro di voler concludere l\'ordine?',
      buttons: {
        conferma: function () {
          $.post("change-state.php",{
            order: ord,
            s: state
          }, function(e) {
            if(e == 1) location.href = 'order-list-admin.php?completeOk=True';
            else location.href = 'order-list-admin.php?completeKo=True';
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
      content: 'Sicuro di voler aggiungere il prodotto?',
      buttons: {
        conferma: function () {
          e.currentTarget.submit();
        },
        annulla: function () {
        },
      }
    });
  });
});
