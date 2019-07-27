$(function(){

  $(".alert").fadeTo(2000, 500).slideUp(500, function(){
    $(".alert").slideUp(500);
  });
  
  $(".COMPLETE").click(function(){
    var idRequest;
    var totalPrice;
    var deliveryTime;
    var OrderState;
    var paymentMethod;
    var deliveryAddres;
    var email;

    var paymentRadio = $('input[name=payment]:checked', '#PAYMENT').val();
    var addressRadio = $('input[name=address]:checked', '#ADDRESS').val();

    console.log(paymentRadio);// consegna o carta
    console.log(addressRadio);// miearte o altra

    console.log($(".DATE_TIME").val());

  });

});
