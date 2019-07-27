$(function(){
  $(".alert").fadeTo(2000, 500).slideUp(500, function(){
    $(".alert").slideUp(500);
  });
  
  $(".DELETE").click(function(e){
    let code = $(this).val();
    $.post("deleteFromCart.php",
            {
              code: ""+code
            },
           function(data, status){
             if(data === "0"){
                location.href="cart.php";
             }else{
                alert("Data: " + data + "\nStatus: " + status);
             }
           }
    );

  });


  $(".SAVE").click(function(e){
    let newCartValues = "";
    $.each($(".PRODUCT"),function(){
      let id=this.id;
      let qt=$(this).find(".QUANTITA").val();
      if(newCartValues === ""){
        newCartValues+=""+id+" "+qt;
      }else{
        newCartValues+=","+id+" "+qt;
      }
    });
    $.post("saveCart.php",
            {
              cartString: ""+newCartValues
            },
           function(data, status){
             if(data === "0"){
                location.href="cart.php";
             }
           }
    );

  });
  $(".COMPLETE").click(function(e){
    let newCartValues = "";
    $.each($(".PRODUCT"),function(){
      let id=this.id;
      let qt=$(this).find(".QUANTITA").val();
      if(newCartValues === ""){
        newCartValues+=""+id+" "+qt;
      }else{
        newCartValues+=","+id+" "+qt;
      }
    });
    $.post("saveCart.php",
            {
              cartString: ""+newCartValues
            },
           function(data, status){
             if(data === "0"){
              location.href="checkOrder.php";
             }
           }
    );
  });


});
