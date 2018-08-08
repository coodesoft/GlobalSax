<?php

require_once 'add_clientes.php';


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

  <div id="uploadSucursal">
    <div class="left-panel">
      <form class="form" method="post">

        <div id="newClientInput" class="form-group cu-form-group">
           <label for="exampleInputEmail1">Nombre del cliente:  </label>
           <input type="text" class="form-control" name="Cliente" placeholder="ej. Ipanema">
         </div>
         <div class="form-group cu-form-group">
           <button type="submit">Agregar cliente</button>
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
      <p>Seleccione un cliente para ver las sucursales cargadas</p>
    </div>

    <div class="left-panel">

      <form class="form" method="post">

          <div id="clientSelection" class="cu-form-group form-group">
              <div>Seleccione el cliente:</div>
              <select name="Sucursal[actual_cliente]">
                  <option id="noClient" value="" disabled selected>Cliente</option>
                  <?php $clientes = Clients::getAll(); ?>
                  <?php foreach ($clientes as $key => $value) { ?>
                  <option value="<?php echo $value['cliente_id'] ?>"> <?php echo $value['nombre_cliente'] ?></option>
                  <?php } ?>
              </select>
          </div>
          <div class="form-group cu-form-group">
              <label for="exampleInputEmail1">Dirección:</label>
              <input type="text" class="form-control" name="Sucursal[location]" placeholder="ej. Calle Falsa 123">
          </div>
          <div id="uploadBtn" class="cu-form-group form-group">
              <button type="submit">Cargar sucursal</button>
          </div>

      </form>
    </div>

    <div class="right-panel">
      <h3>Sucursales Cargadas</h3>
      <div class="uc-list">
      </div>
    </div>
  </div>
<?php
}

function editFeatures(){

}
