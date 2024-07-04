<?php
    if(isset($data['results']) && is_array($data['results'])){
        foreach($data['results'] as $cliente){
            $id = htmlspecialchars($cliente['id']);
            $nombreCliente = htmlspecialchars($cliente['field_3220'] ?? 'N/A');
            $nombreFantasia = htmlspecialchars($cliente['field_3221'] ?? 'N/A');
            $grupoCliente = htmlspecialchars($cliente['field_3437'] ?? 'N/A');
            $razonSocial = htmlspecialchars($cliente['field_1622'] ?? 'N/A');
            $tipoCliente = "N/A";
            if(isset($cliente['field_3435']) && is_array($cliente['field_3435'])) {
                foreach($cliente['field_3435'] as $item) {
                if(isset($item['value'])) {
                    $tipoCliente = htmlspecialchars($item['value']);
                    break; // Salir del bucle una vez que se encuentra el valor
                }
                }
            }
            $rutEmpresa = htmlspecialchars($cliente['field_3438'] ?? 'N/A');
    ?>
    <tr>
        <td><?php echo $id;?></td>
        <td><?php echo $nombreCliente;?></td>
        <td><?php echo $nombreFantasia;?></td>
        <td><?php echo $grupoCliente;?></td>
        <td><?php echo $razonSocial;?></td>
        <td><?php echo $tipoCliente;?></td>
        <td><?php echo $rutEmpresa;?></td>
        <td>1</td>
        <td><a href="#" class="btn btn-primary">Detail</a></td>
    </tr>
    <?php
        }
    } else {
        echo "<tr><td colspan='9'>No se encontraron clientes</td></tr>";
    }
    ?>


