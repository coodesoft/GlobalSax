<?php

require_once 'sucursales.php';


function sucursales(){
  $screen =  get_current_screen();
	$pluginPageUID = $screen->parent_file;

?>
  <h2 class="nav-tab-wrapper">
    <a href="<?= admin_url('admin.php?page='.$pluginPageUID.'&tab=sucursales&action=user')?>" class="nav-tab">Cargar clientes</a>
    <a href="<?= admin_url('admin.php?page='.$pluginPageUID.'&tab=sucursales&action=upload')?>" class="nav-tab">Cargar sucursal</a>
    <a href="<?= admin_url('admin.php?page='.$pluginPageUID.'&tab=sucursales&action=edit')?>" class="nav-tab">Editar características</a>
  </h2>

  <div class="panel-body">
    <?php
    $activeTab = $_GET['tab'];
    $action = $_GET['action'];

    if ($activeTab == 'sucursales' && !isset($action))
      createCliente();

    if ($activeTab == 'sucursales' && $action == 'user')
      createCliente();

    if ($activeTab == 'sucursales' && $action == 'upload')
      uploadSucursal();

    if ($activeTab == 'sucursales' && $action == 'edit')
      editFeatures();
    ?>
  </div>
<?
}

function createCliente(){
?>
  <div id="ucInstructions">
    <p>Ingrese el nombre del cliente que desea agregar.
       Luego diríjase a la pestaña "Cargar sucursal" para cargar las sucursales asociadas
    </p>
  </div>

  <div id="actionResult"  class="hidden"></div>

  <div id="uploadSucursal">
    <div class="left-panel">
      <form id="newClientForm" class="form" method="post">

        <div id="newClientInput" class="form-group cu-form-group">
           <label for="exampleInputEmail1">Nombre del cliente:  </label>
           <input type="text" class="form-control" name="Cliente" placeholder="ej. Ipanema">
         </div>
         <div class="form-group cu-form-group">
           <button type="submit">Agregar cliente</button>
           <div class="cu-loader"></div>
         </div>
      </form>
    </div>
    <div class="right-panel">
      <?php
       $clientes = Clients::getAll();
      ?>
      <h3>Clientes cargados</h3>
      <div class="uc-list">
        <ul>
          <?php foreach ($clientes as $key => $value) { ?>
            <li><?php echo $value['nombre_cliente']?></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
<?php
}


function uploadSucursal(){
?>
  <div id="uploadSucursal">

    <div id="ucInstructions">
      <p>
        <ul>
          <li>Seleccione un cliente para ver las sucursales cargadas</li>
          <li>Ingrese una dirección para cargar una nueva sucursal del cliente seleccionado</li>
        </ul>
     </p>
    </div>
    <div id="actionResult" class="hidden"></div>

    <div class="left-panel">

      <form id="uploadSucursalForm" class="form" method="post">

          <div id="sucursalClientSelection" class="cu-form-group form-group">
              <div>Seleccione el cliente:</div>
              <select name="Sucursal[cliente_actual]" required>
                  <option id="noClient" value="" disabled selected>Cliente</option>
                  <?php $clientes = Clients::getAll(); ?>
                  <?php foreach ($clientes as $key => $value) { ?>
                  <option value="<?php echo $value['cliente_id'] ?>"> <?php echo $value['nombre_cliente'] ?></option>
                  <?php } ?>
              </select>
          </div>
          <div class="form-group cu-form-group">
              <label for="sucursal">Dirección:</label>
              <input id="sucursalInput" type="text" class="form-control" name="Sucursal[location]" placeholder="ej. Calle Falsa 123" required>
          </div>
          <div id="uploadBtn" class="cu-form-group form-group">
              <button type="submit">Cargar sucursal</button>
              <div class="cu-loader"></div>
          </div>

      </form>
    </div>

    <div class="right-panel">
      <h3>Sucursales Cargadas</h3>
      <div class="cu-loadIndicator"></div>
      <div class="uc-list"><ul></ul></div>
    </div>
  </div>
<?php
}

function editFeatures(){
?>
<div id="editFeatures">
  <div id="ucInstructions">
    <p>Edite las características de las sucursales checkeando las casillas</p>
  </div>
  <div id="actionResult" class="hidden"></div>

  <form id="editFeaturesForm">
    <table>
      <tr>
        <th>Cliente</th>
        <th>Dirección</th>
        <th>Visibilidad</th>
        <th>Venta Mayorista</th>
        <th>Venta Minorista</th>
        <th>Venta online</th>
        <th>Sitio Web</th>
        <th>Revendedoras</th>
      </tr>
      <?php $sucursales = Clients::getSucursales(); ?>
      <?php foreach ($sucursales as $key => $sucursal) { ?>
      <tr>
        <td><?php echo $sucursal['nombre_cliente'] ?></td>
        <td><input name="Cliente[<?php echo $sucursal['cliente_id'] ?>][<?php echo $sucursal['id'] ?>][direccion_publica]" "type="text" value="<?php echo $sucursal['direccion_publica'] ?>"></td>
        <td><input name="Cliente[<?php echo $sucursal['cliente_id'] ?>][<?php echo $sucursal['id'] ?>][visibilidad]" type="checkbox" <?php echo $sucursal['visibilidad'] ? 'checked' : '' ?> ></td>
        <td><input name="Cliente[<?php echo $sucursal['cliente_id'] ?>][<?php echo $sucursal['id'] ?>][venta_mayorista]" type="checkbox" <?php echo $sucursal['venta_mayorista'] ? 'checked' : '' ?> ></td>
        <td><input name="Cliente[<?php echo $sucursal['cliente_id'] ?>][<?php echo $sucursal['id'] ?>][venta_minorista]" type="checkbox" <?php echo $sucursal['venta_minorista'] ? 'checked' : '' ?> ></td>
        <td><input name="Cliente[<?php echo $sucursal['cliente_id'] ?>][<?php echo $sucursal['id'] ?>][venta_online]" type="checkbox" <?php echo $sucursal['venta_online'] ? 'checked' : '' ?> ></td>
        <td><input name="Cliente[<?php echo $sucursal['cliente_id'] ?>][<?php echo $sucursal['id'] ?>][sitio_web]" type="checkbox" <?php echo $sucursal['sitio_web'] ? 'checked' : '' ?> ></td>
        <td><input name="Cliente[<?php echo $sucursal['cliente_id'] ?>][<?php echo $sucursal['id'] ?>][revendedoras]" type="checkbox" <?php echo $sucursal['revendedoras'] ? 'checked' : '' ?> ></td>
      </tr>
      <?php } ?>
    </table>
    <div class="cu-submit-button cu-text-center">
      <button type="submit">Editar características</button>
      <div class="cu-loader"></div>
    </div>
  </form>
</div>
<?php
}
