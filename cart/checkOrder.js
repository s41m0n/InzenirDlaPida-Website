$(function(){

  $(".alert").fadeTo(2000, 500).slideUp(500, function(){
    $(".alert").slideUp(500);
  });

  if(!$(".WITH_CARD").is(":checked")){
    $(".SELECT_CARD").hide();
  }

  if(!$(".SHOW_OTHER").is(":checked")){
    $(".OTHER_ADDRESS").hide();
  }

  $('input[type=radio][name=deliveryMode]').change(function() {
        if (this.value === 'myaddress') {
          $(".OTHER_ADDRESS").hide();
          $(".ADDR").prop("required",false);
          $(".SELECT_ADDRESS").prop("required",true);
          $(".SELECT_ADDRESS").show();
        }
        else if (this.value === 'other') {
          $(".OTHER_ADDRESS").show();
          $(".ADDR").prop("required",true);
          $(".SELECT_ADDRESS").prop("required",false);
          $(".SELECT_ADDRESS").hide();
        }
    });

    $('input[type=radio][name=paymentMethod]').change(function() {
          if (this.value === 'Alla consegna') {
            $(".SELECT_CARD").prop("required",false);
            $(".SELECT_CARD").hide();
          }
          else if (this.value === 'Con carta') {
            $(".SELECT_CARD").prop("required",true);
            $(".SELECT_CARD").show();
          }
      });


  $("#formOrder").submit(function(e) {
    e.preventDefault();
    $.confirm({
      title: 'Attenzione!',
      content: 'Sicuro di voler procedere con l\'ordine',
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
