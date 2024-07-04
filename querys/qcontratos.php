<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://baserow-production-9ab6.up.railway.app/api/database/rows/table/549/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Token nHPciD53K9SI883sLftNOUPQuaSWKNB0'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

// Decodificar la respuesta JSON
$data = json_decode($response, true);
 // Mostrar los resultados
 if(isset($data['results']) && is_array($data['results'])){
    foreach($data['results'] as $contrato){
      $id = htmlspecialchars($contrato['id']);
      $nombreContrato = htmlspecialchars($contrato['field_5217'] ?? 'N/A');
      $nombreCliente = "N/A";
      if(isset($contrato['field_5220']) && is_array($contrato['field_5220'])) {
        foreach($contrato['field_5220'] as $item) {
          if(isset($item['value'])) {
            $nombreCliente = htmlspecialchars($item['value']);
            break; // Salir del bucle una vez que se encuentra el valor
          }
        }
      }
      $nombreProducto = "N/A";
      if(isset($contrato['field_5222']) && is_array($contrato['field_5222'])) {
        foreach($contrato['field_5222'] as $item) {
          if(isset($item['value'])) {
            $nombreProducto = htmlspecialchars($item['value']);
            break; // Salir del bucle una vez que se encuentra el valor
          }
        }
      }
      $nombreProveedor = "N/A";
      if(isset($contrato['field_5224']) && is_array($contrato['field_5224'])) {
        foreach($contrato['field_5224'] as $item) {
          if(isset($item['value'])) {
            $nombreProveedor = htmlspecialchars($item['value']);
            break; // Salir del bucle una vez que se encuentra el valor
          }
        }
      }
      $medios = "N/A";
      if(isset($contrato['field_5226']) && is_array($contrato['field_5226'])) {
        foreach($contrato['field_5226'] as $item) {
          if(isset($item['value'])) {
            $medios = htmlspecialchars($item['value']);
            break; // Salir del bucle una vez que se encuentra el valor
          }
        }
      }
      $formaPago = "N/A";
      if(isset($contrato['field_5246']) && is_array($contrato['field_5246'])) {
        foreach($contrato['field_5246'] as $item) {
          if(isset($item['value'])) {
            $formaPago = htmlspecialchars($item['value']);
            break; // Salir del bucle una vez que se encuentra el valor
          }
        }
      }
    }
}
?>