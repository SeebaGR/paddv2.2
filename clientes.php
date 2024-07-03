<?php
// URL del endpoint
$url = "https://baserow-production-9ab6.up.railway.app/api/database/rows/table/164/";

// Token de autorización
$token = "nHPciD53K9SI883sLftNOUPQuaSWKNB0";

// Función para eliminar cliente
function deleteCliente($id, $token, $baseUrl) {
    $url = $baseUrl . $id . '/';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Token $token",
        "Content-Type: application/json"
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $http_code;
}

// Manejar la solicitud de eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_result = deleteCliente($delete_id, $token, $url);
    if ($delete_result == 204) {
        $delete_message = "Cliente eliminado con éxito.";
    } else {
        $delete_message = "Error al eliminar el cliente. Código: $delete_result";
    }
}

// Inicializar cURL
$ch = curl_init($url);

// Configurar las opciones de cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Token $token",
    "Content-Type: application/json"
]);

// Ejecutar la solicitud
$response = curl_exec($ch);

// Verificar si hubo algún error
if(curl_errno($ch)){
    echo "Error cURL: " . curl_error($ch);
    exit;
}

// Obtener el código de respuesta HTTP
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Cerrar la sesión cURL
curl_close($ch);

// Verificar el código de respuesta HTTP
if($http_code == 200){
    // La solicitud fue exitosa
    $data = json_decode($response, true);
    
    // Verificar si se pudo decodificar el JSON
    if($data === null && json_last_error() !== JSON_ERROR_NONE){
        echo "Error al decodificar la respuesta JSON: " . json_last_error_msg();
        exit;
    }
    
    // Incluir el header y la barra lateral
    include 'componentes/header.php';
    include 'componentes/sidebar.php';

    // Incluir el contenido principal
    echo '
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <div class="col-10">
                      <h4>Listado de clientes</h4>
                    </div>
                    <div class="text-end col-2">
                      <a href="addCliente.php" class="btn btn-primary btn1"><i class="material-icons tres">note_add</i> Agregar Cliente</a>
                    </div>
                  </div>
                  <div class="card-body">
                    ' . (isset($delete_message) ? "<div class='alert alert-info'>$delete_message</div>" : '') . '
                    <div class="table-responsive">
                      <table class="table table-striped" id="table-1">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Nombre Cliente</th>
                            <th>Nombre de fantasía</th>
                            <th>Grupo</th>
                            <th>Razón social</th>
                            <th>Tipo de cliente</th>
                            <th>Rut de empresa</th>
                            <th>N° de campañas</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
    ';

    // Mostrar los resultados
    if(isset($data['results']) && is_array($data['results'])){
        foreach($data['results'] as $cliente){
            // Escapar los campos necesarios
            $id = htmlspecialchars($cliente['id']);
            $nombreCliente = htmlspecialchars($cliente['field_3220'] ?? 'N/A');
            $nombreFantasia = htmlspecialchars($cliente['field_3221'] ?? 'N/A');
            $grupo = htmlspecialchars($cliente['field_3437'] ?? 'N/A');
            $razonSocial = htmlspecialchars($cliente['field_1622'] ?? 'N/A');
            $rutEmpresa = htmlspecialchars($cliente['field_3438'] ?? 'N/A');
            
            // Manejar el campo 'field_3435' que es un array
            $campo3435 = "N/A";
            if(isset($cliente['field_3435']) && is_array($cliente['field_3435'])) {
                foreach($cliente['field_3435'] as $item) {
                    if(isset($item['value'])) {
                        $campo3435 = htmlspecialchars($item['value']);
                        break; // Salir del bucle una vez que se encuentra el valor
                    }
                }
            }

            // Generar la fila de la tabla con los datos obtenidos
            echo "
              <tr>
                <td>$id</td>
                <td>$nombreCliente</td>
                <td>$nombreFantasia</td>
                <td>$grupo</td>
                <td>$razonSocial</td>
                <td>$campo3435</td>
                <td>$rutEmpresa</td>
                <td>1</td>
                <td>
                  <a href='#' class='color1'><i class='material-icons dos'>create</i></a>
                  <a href='#' class='color2'><i class='material-icons dos'>remove_red_eye</i></a>
                  <form method='POST' style='display:inline;' onsubmit='return confirm(\"¿Está seguro de que desea eliminar este cliente?\");'>
                    <input type='hidden' name='delete_id' value='$id'>
                    <button type='submit' class='btn btn-link p-0 color3'><i class='material-icons dos'>delete_forever</i></button>
                  </form>
                </td>
              </tr>
            ";
        }
    } else {
        echo "<tr><td colspan='9'>No se encontraron resultados.</td></tr>";
    }

    // Cerrar la tabla y el contenido principal
    echo '
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    ';

} else {
    // La solicitud falló
    echo "La solicitud falló con el código de estado HTTP: $http_code<br>";
    echo "Respuesta: $response";
}

// Incluir el footer
include 'componentes/footer.php';
?>