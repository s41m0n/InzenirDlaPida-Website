<?php

function getOrderUser($idRequest,$address,$number,$city,$deliveryTime,$orderState,$details){
  $text = '
  <li class="ITEM list-group-item" id="'.$idRequest.'">
    <div class="row">
      <div class="col-md-3">
        <label >N° Ordine:
          <span>'.$idRequest.'</span>
        </label>
      </div>
      <div class="col-md-3">
        <label >Via/Piazza:
          <span>'.$address.' '.$number.' '.$city.'</span>
        </label>
      </div>
      <div class="col-md-3">
        <label>Data/Ora:
          <span>'.$deliveryTime.'</span>
        </label>
      </div>
      <div class="col-md-3">
        <label>Stato:
          <span class="STATE">'.$orderState.'</span>
        </label>
      </div>
    </div>
    <div class="row">';
    if(new DateTime("now") <= new DateTime($deliveryTime) && strcmp($orderState, 'Annullato') !== 0 && strcmp($orderState, 'Completato') !==0){
      $text.= '
      <div class="col">
        <div class="btn-group ml-0" role="group" aria-label="Basic example">
          <button type="button" name="annulla" class="DECLINE btn btn-primary" value="'.$idRequest.'" onclick="undo('.$idRequest.')">Annulla</button>
        </div>
      </div>';
    }else $text.= '<div class="col"></div>';
  $text.= '
      <div class="col-md-6">
        <a class="btn" data-toggle="collapse" href="#collapse'.$idRequest.'" role="button" aria-expanded="false" aria-controls="collapse'.$idRequest.'">
          Vedi Dettaglio
        </a>
      </div>
    </div>
    <div class="collapse mt-2 ml-0" id="collapse'.$idRequest.'">
      <div class="card card-body">';

  foreach($details as $detail){
    $text .= '
      <div class="row">
        <label>Prodotto:
          <span>'.$detail['nameProduct'].'</span>
        </label>
        <label class="ml-3">Quantità:
          <span>'.$detail['quantity'].'</span>
        </label>
      </div>';
  }

  $text .= '</div>
          </div>
        </li>
      ';
  return $text;
}

function getProduct($code,$name,$price,$imagePath,$desc){
  $title = "'".$name."'";
  $realPrice ="".number_format($price,2,',','')."€";
  $text = "'".$desc."'";
  return '
  <div class="PRODUCT col-md-6 w-100  pl-0 pr-3 border border-secondary border-top-0 border-left-0 border-right-0 pt-2 mb-4">
      <div class="row w-100 container-fluid">'.$name.'</div>
      <div class=" row w-100 mt-1 mb-2 align-items-end">
              <div class="col-4 pr-2">
                <img class="img-responsive rounded border border-secondary" width="100%" height="120" src="'.$imagePath.'" alt="Immagine prodotto" >
              </div>
              <div class="col-1 pl-0 pr-0  align-self-end ">
                  <img class="img-responsive pb-1 w-75" src="/images/Info.png" alt="Info" onclick="
                  $.confirm({
                    title: '.$title.',
                    content: '.$text.',
                    buttons: {
                      ok: function () {}
                    }
                  })" >
              </div>
              <div class="col-7 pl-0 pr-0 ">
                <div class="form-inline row">
                  <div class="col-3 pr-0 text-right" style="font-size:70%">Prezzo:</div>
                  <div class="col-6 mt-1"> <span>'.$realPrice.'</span> </div>
                </div>
                <div class="form-inline row">
                  <div class="col-3 pr-0 text-right" style="font-size:70%">
                    <label for="'.$code.'">
                    Quantità:
                    </label>
                  </div>
                  <div class="col-6 mt-1 ">
                      <input type="number" name="quantità" value="1" min="1" class="rounded w-75 pl-1 border border-secondary" id="'.$code.'">
                  </div>
                  <div class="col-3 pr-0 pl-0">
                    <button type="button" class="ADD btn btn-warning w-100 btn-sm" name="add to cart" value="'.$code.'" >Add</button>
                  </div>
                </div>
              </div>
        </div>
  </div>
  ';
}

function getCartProduct($code,$nameProduct,$price,$qt, $desc,$imagePath){
  $title = "'".$nameProduct."'";
  $text = "'".$desc."'";
  $realPrice ="".number_format($price,2,',','')."€";
  return '
      <div class="PRODUCT row mr-0 ml-0" id="'.$code.'">
        <div class="row align-items-center w-100 mr-0 ml-0">
          <div class="col-auto" style="font-size:120%" >'.$nameProduct.'</div>
          <div class="col-1">
            <img class="img-responsive" src="/images/Info.png" alt="info" width="20" onclick="
            $.confirm({
              title: '.$title.',
              content: '.$text.',
              buttons: {
                letto: function () {}
              }
            })">
          </div>
        </div>
        <div class="row align-items-center justify-content-end mr-0 ml-0 pt-3 pb-3 w-100 border border-secondary border-top-0 border-right-0 border-left-0 ">
          <div class="col-4">
              <img class="img-responsive rounded border border-secondary" width="75%" height="110" src="'.$imagePath.'" alt="Immagine prodotto" >
          </div>
          <div class="col-3 pl-0">
            <label class="PRICE text-danger ">Prezzo:<span>'.$realPrice.'</span></label>
          </div>
          <div class="col-5">
            <label for="quantità '.$code.'">Qt.<input type="number" id="quantità '.$code.' name="quantità" value="'.$qt.'" min="1" max="50" class="QUANTITA rounded  ml-3 pl-3 border border-secondary"></label>
          </div>
          <div class="col-auto">
            <button type="button" class="DELETE btn btn-warning w-100 h-100 btn-sm" name="remove-from-cart" value="'.$code.'" >Remove</button>
          </div>
        </div>
      </div>
  ';
}

function getOrder($idRequest,$address,$number,$city,$deliveryTime,$orderState,$details){
  $text = '
  <li class="ITEM list-group-item" id="'.$idRequest.'">
    <div class="row">
      <div class="col-md-3">
        <label >N° Ordine:
          <span>'.$idRequest.'</span>
        </label>
      </div>
      <div class="col-md-3">
        <label >Via/Piazza:
          <span>'.$address.' '.$number.' '.$city.'</span>
        </label>
      </div>
      <div class="col-md-3">
        <label>Data/Ora:
          <span>'.$deliveryTime.'</span>
        </label>
      </div>
      <div class="col-md-3">
        <label>Stato:
          <span class="STATE">'.$orderState.'</span>
        </label>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="btn-group" role="group" aria-label="Basic example">
          <button type="button" name="annulla" class="DECLINE btn btn-primary" value="'.$idRequest.'">Annulla</button>
          <button type="button" name="gestisci" class="MANAGE btn btn-primary" value="'.$idRequest.'">Gestisci</button>
          <button type="button" name="consegna" class="DELIVER btn btn-primary" value="'.$idRequest.'">Consegna</button>
          <button type="button" name="completato" class="COMPLETE btn btn-primary" value="'.$idRequest.'">Completato</button>
        </div>
      </div>
      <div class="col-md-6">
        <a class="btn" data-toggle="collapse" href="#collapse'.$idRequest.'" role="button" aria-expanded="false" aria-controls="collapse'.$idRequest.'">
          Vedi Dettaglio
        </a>
      </div>
    </div>
    <div class="collapse mt-2 ml-0" id="collapse'.$idRequest.'">
      <div class="card card-body">';

  foreach($details as $detail){
    $text .= '
      <div class="row">
        <label>Prodotto:
          <span>'.$detail['nameProduct'].'</span>
        </label>
        <label class="ml-3">Quantità:
          <span>'.$detail['quantity'].'</span>
        </label>
      </div>';
  }

  $text .= '</div>
          </div>
        </li>
      ';
  return $text;
}

function getProductAdmin($code,$name,$price,$imagePath){
  $realPrice ="".number_format($price,2,'.','');
  return '
  <li class="list-group-item">
    <div class="row align-items-center justify-content-md-center">
      <div class="col-md-2">
        <label><strong>Codice prodotto:</strong>
          <span>'.$code.'</span>
        </label>
      </div>
      <div class="col-md-2">
        <label><strong>Nome:</strong>
          <span>'.$name.'</span>
        </label>
      </div>
      <div class="col-md-2">
        <label><strong>Foto:</strong></label>
        <img class="w-100 img-responsive rounded border border-secondary" src="'.$imagePath.'" alt="Immagine prodotto" >
      </div>
      <div class="col-md-3 mt-2">
        <label for="'.$code.'"><strong>Prezzo:</strong>
        <input type="number" id="'.$code.'" name="prezzo" step="0.10" value="'.$realPrice.'" min="0.10" class="rounded w-50 pl-1 border border-secondary">
        €</label>
      </div>
      <div class="col-md-2">
        <div class="btn-group-vertical" role="group" aria-label="Basic example">
          <button type="button" name="salva" class="SAVE btn btn-primary" value="'.$code.'">Salva</button>
          <button type="button" name="elimina" class="DELETE btn btn-warning" value="'.$code.'">Elimina</button>
        </div>
      </div>
    </div>
  </li>
  ';
}

?>
