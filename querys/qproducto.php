<?php
    if(isset($data['results']) && is_array($data['results'])){
        foreach($data['results'] as $producto){
          $id = htmlspecialchars($producto['id']);
          $nombreproducto = htmlspecialchars($producto['field_5181'] ?? 'N/A');
          
          $nombreCliente = "N/A";

        if(isset($producto['field_5184']) && is_array($producto['field_5184'])) {
        foreach($producto['field_5184'] as $item) {
          if(isset($item['value'])) {
            $nombreCliente = htmlspecialchars($item['value']);
            break; // Salir del bucle una vez que se encuentra el valor
          }
        }
      }

      $tipocliente = "N/A";
      if(isset($producto['field_5186']) && is_array($producto['field_5186'])) {
        foreach($producto['field_5186'] as $item) {
          if(isset($item['value'])) {
            $tipocliente = htmlspecialchars($item['value']);
            break; // Salir del bucle una vez que se encuentra el valor
          }
        }
      }
      $numcontrato = "N/A";
      if(isset($producto['field_5223']) && is_array($producto['field_5223'])) {
        foreach($producto['field_5223'] as $item) {
          if(isset($item['value'])) {
            $numcontrato = htmlspecialchars($item['value']);
            break; // Salir del bucle una vez que se encuentra el valor
          }
        }
      }
      $numcampaña = "N/A";
      if(isset($producto['field_5277']) && is_array($producto['field_5277'])) {
        foreach($producto['field_5277'] as $item) {
          if(isset($item['value'])) {
            $numcampaña = htmlspecialchars($item['value']);
            break; // Salir del bucle una vez que se encuentra el valor
          }
        }
      }

          
    ?>
   <tr>
    <td>
    <?php echo "$id";?>
    </td>
    <td><?php echo "$nombreCliente";?></td>
    <td><?php echo "$nombreproducto";?></td>
      <td><?php echo "$tipocliente";?></td>
    <td><?php echo "$numcontrato";?></td>
    <td><?php echo "$numcampaña";?></td>
    <td>
            <a href='#' class='color1'><i class='material-icons dos'>create</i></a>
            <a href='#' class='color2'><i class='material-icons dos'>remove_red_eye</i></a>
            <a href='#' class='color3'><i class='material-icons dos'>delete_forever</i></a>
    </td>
</tr>
    <?php
        }
    } else {
        echo "<tr><td colspan='9'>No se encontraron clientes</td></tr>";
    }
    ?>



