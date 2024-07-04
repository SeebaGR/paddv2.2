<?php
include 'querys/qcontratos.php';    
include 'componentes/header.php';
include 'componentes/sidebar.php';
?>
<!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Listado de Contratos</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped" id="table-1">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th>Nombre Contrato</th>
                            <th>Nombre Cliente</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Medio</th>
                            <th>Forma de Pago</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
                            <?php echo "$id";?>
                            </td>
                            <td><?php echo "$nombreContrato";?></td>
                            <td><?php echo "$nombreCliente";?></td>
                            <td><?php echo "$nombreProducto";?></td>
                            <td><?php echo "$nombreProveedor";?></td>
                            <td><?php echo "$medios";?></td>
                            <td><?php echo "$formaPago";?></td>
                         
                            <td><a href="#" class="btn btn-primary">Detail</a></td>
                          </tr>
         
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </section>
        
      </div>
      <?php include 'componentes/settings.php'; ?>
      <?php include 'componentes/footer.php'; ?>
      