<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// URLs de los endpoints
$clienteUrl = "https://baserow-production-9ab6.up.railway.app/api/database/rows/table/164/";
$tipoClienteUrl = "https://baserow-production-9ab6.up.railway.app/api/database/rows/table/342/";
$tipoFacturacionUrl = "https://baserow-production-9ab6.up.railway.app/api/database/rows/table/265/";

// Token de autorización
$token = "nHPciD53K9SI883sLftNOUPQuaSWKNB0";

$message = '';
$tiposCliente = [];
$tiposFacturacion = [];
$debug_info = '';

// Función para hacer solicitudes GET
function makeGetRequest($url, $token) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Token $token",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if(curl_errno($ch)){
        $error = 'Error cURL: ' . curl_error($ch);
        curl_close($ch);
        return [0, $error];
    }
    curl_close($ch);
    return [$http_code, $response];
}

// Obtener tipos de cliente
list($http_code, $response) = makeGetRequest($tipoClienteUrl, $token);
if ($http_code == 200) {
    $data = json_decode($response, true);
    if (isset($data['results']) && is_array($data['results'])) {
        foreach ($data['results'] as $tipo) {
            if (isset($tipo['id']) && isset($tipo['field_3222'])) {
                $tiposCliente[$tipo['id']] = $tipo['field_3222'];
            }
        }
    }
} else {
    $debug_info .= "Error al obtener tipos de cliente. Código: $http_code\n";
    $debug_info .= "Respuesta: $response\n";
}

// Obtener tipos de facturación
list($http_code, $response) = makeGetRequest($tipoFacturacionUrl, $token);
$debug_info .= "Respuesta del endpoint de tipos de facturación:\n";
$debug_info .= "Código HTTP: $http_code\n";
$debug_info .= "Respuesta: " . print_r($response, true) . "\n";

if ($http_code == 200) {
    $data = json_decode($response, true);
    if (isset($data['results']) && is_array($data['results'])) {
        foreach ($data['results'] as $tipo) {
            if (isset($tipo['id']) && isset($tipo['field_2453'])) {
                $tiposFacturacion[$tipo['id']] = $tipo['field_2453'];
            }
        }
    }
} else {
    $debug_info .= "Error al obtener tipos de facturación. Código: $http_code\n";
    $debug_info .= "Respuesta: $response\n";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombreCliente = $_POST['nombreCliente'] ?? '';
    $nombreFantasia = $_POST['nombreFantasia'] ?? '';
    $grupo = $_POST['grupo'] ?? '';
    $razonSocial = $_POST['razonSocial'] ?? '';
    $tipoCliente = $_POST['tipoCliente'] ?? '';
    $tipoFacturacion = $_POST['tipoFacturacion'] ?? '';
    $rutEmpresa = $_POST['rutEmpresa'] ?? '';

    // Preparar los datos para enviar
    $data = [
        'field_3220' => $nombreCliente,
        'field_3221' => $nombreFantasia,
        'field_3437' => $grupo,
        'field_1622' => $razonSocial,
        'field_3435' => [intval($tipoCliente)],
        'field_1623' => [intval($tipoFacturacion)],
        'field_3438' => $rutEmpresa
    ];

    $debug_info .= "Datos enviados: " . print_r($data, true) . "\n";
    $debug_info .= "Datos enviados (JSON): " . json_encode($data, JSON_PRETTY_PRINT) . "\n";

    // Inicializar cURL
    $ch = curl_init($clienteUrl);

    // Configurar las opciones de cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Token $token",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    // Ejecutar la solicitud
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if(curl_errno($ch)){
        $error = 'Error cURL: ' . curl_error($ch);
        $debug_info .= "Error en la solicitud: $error\n";
        $message = "Error al conectar con el servidor.";
    } else {
        if ($http_code == 200 || $http_code == 201) {
            $message = "Cliente agregado exitosamente.";
        } else {
            $responseData = json_decode($response, true);
            $message = "Error al agregar el cliente. Código: $http_code";
            $debug_info .= "Respuesta del servidor: " . print_r($responseData, true) . "\n";
            
            if (isset($responseData['error']) && isset($responseData['detail'])) {
                $debug_info .= "Error específico: " . $responseData['error'] . "\n";
                $debug_info .= "Detalles del error: " . print_r($responseData['detail'], true) . "\n";
            }
        }
    }

    // Cerrar la sesión cURL
    curl_close($ch);
}

// Incluir el header y la barra lateral
include 'componentes/header.php';
include 'componentes/sidebar.php';
?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Agregar Nuevo Cliente</h4>
                        </div>
                        <div class="card-body">
                            <?php if ($message): ?>
                                <div class="alert alert-info"><?php echo $message; ?></div>
                            <?php endif; ?>
                            <?php if ($debug_info): ?>
                                <pre><?php echo htmlspecialchars($debug_info); ?></pre>
                            <?php endif; ?>
                            <form method="POST">
                                <div class="form-group">
                                    <label>Nombre Cliente</label>
                                    <input type="text" class="form-control" name="nombreCliente" required>
                                </div>
                                <div class="form-group">
                                    <label>Nombre de Fantasía</label>
                                    <input type="text" class="form-control" name="nombreFantasia">
                                </div>
                                <div class="form-group">
                                    <label>Grupo</label>
                                    <input type="text" class="form-control" name="grupo">
                                </div>
                                <div class="form-group">
                                    <label>Razón Social</label>
                                    <input type="text" class="form-control" name="razonSocial" required>
                                </div>
                                <div class="form-group">
                                    <label>Tipo de Cliente</label>
                                    <select class="form-control" name="tipoCliente">
                                        <?php foreach ($tiposCliente as $id => $nombre): ?>
                                            <option value="<?php echo htmlspecialchars($id); ?>"><?php echo htmlspecialchars($nombre); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Tipo de Facturación</label>
                                    <select class="form-control" name="tipoFacturacion">
                                        <?php if (empty($tiposFacturacion)): ?>
                                            <option value="">No se encontraron tipos de facturación</option>
                                        <?php else: ?>
                                            <?php foreach ($tiposFacturacion as $id => $tipo): ?>
                                                <option value="<?php echo htmlspecialchars($id); ?>"><?php echo htmlspecialchars($tipo); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>RUT Empresa</label>
                                    <input type="text" class="form-control" name="rutEmpresa" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Agregar Cliente</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'componentes/footer.php'; ?>