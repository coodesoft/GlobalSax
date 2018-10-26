<?php
define('GLOBALSAX_CORE','globalsax-core');

function theme_settings_page()
{
  $screen =  get_current_screen();
	$pluginPageUID = $screen->parent_file;
    ?>
    <div class="wrap">
        <h1 class="panel-title">GLOBALSAX - CORE</h1>
          <h2 class="nav-tab-wrapper">
          <a href="<?= admin_url('admin.php?page='.$pluginPageUID.'&tab=sincronizar')?>" class="nav-tab">Sincronizar</a>
          <a href="<?= admin_url('admin.php?page='.$pluginPageUID.'&tab=assignClient')?>" class="nav-tab">Asignar clientes</a>
        </h2>

      <div class="panel-body">
  			<?php $activeTab = $_GET['tab']; ?>

  			<?php if (!isset($activeTab)){ ?>
        	<div id="gs-tab"><?php settings(); ?></div>
  			<?php } ?>

  			<?php if ($activeTab == 'sincronizar'){ ?>
  				<div class="gs-tab"><?php	settings(); ?></div>
  			<?php } ?>

        <?php if ($activeTab == 'assignClient'){ ?>
  				<div class="gs-tab"><?php	assignClient(); ?></div>
  			<?php } ?>
		</div>
	<?php
}
function settings(){
  ?>
  <form method="post" action="options.php">
   <?php
       settings_fields("section");
       do_settings_sections("theme-options");
       submit_button();
   ?>
</form>
<?php
}
function display_opcion_sincronizar_productos()
{

	?>

		<input type="button" name="sincronizar_productos" value="Sincronizar productos" onclick="sincronizarProductos()"/>
    <script>

      function sincronizarProductos(){
        jQuery.ajax({
          type : "post",
          url : "<?php echo home_url('/wp-admin/admin-ajax.php'); ?>",
          data : 'action=get_sincronizar_producto&security=<?php echo wp_create_nonce('globalsax'); ?>',
          success: function( response ) {
            console.log(response);
            //location.reload();
        },
        error: function() {
          console.log('error');
        }
        });
      }
    </script>


	<?php
}

function display_opcion_sincronizar_clientes() {
  ?>

		<input type="button" name="sincronizar_clientes" value="Sincronizar clientes" onclick="sincronizarClientes()"/>
    <script>

      function sincronizarClientes(){
        jQuery.ajax({
          type : "post",
          url : "<?php echo home_url('/wp-admin/admin-ajax.php'); ?>",
          data : 'action=get_sincronizar_cliente&security=<?php echo wp_create_nonce('globalsax'); ?>',
          success: function( response ) {
            console.log(response);
            //location.reload();
        },
        error: function() {
          console.log('error');
        }
        });
      }
    </script>


	<?php

}
function display_theme_panel_fields()
{
	add_settings_section("section", "Configuracion de opciones de sistema", null, "theme-options");
	/**/
  add_settings_field("productos", "Sincronizar lista de productos de todos los productos! - Es un proceso lento y que genera mucho estress a la base de datos, por favor sincronizar cuando este seguro que se debe hacer.", "display_opcion_sincronizar_productos","theme-options", "section");
	register_setting("section", "productos");
	add_settings_field("clientes", "1) Sincronizar lista de clientes", "display_opcion_sincronizar_clientes","theme-options", "section");
	register_setting("section", "clientes");
	//add_settings_field("opcion_3", "1) Opcion 3", "display_opcion_generar_cotizacion","theme-options", "section");
	//register_setting("section", "opcion_3");
	//add_settings_field("opcion_4", "1) Opcion 4", "display_opcion_generar_cotizacion","theme-options", "section");
    //register_setting("section", "opcion_4");
	/**/
}

add_action("admin_init", "display_theme_panel_fields");


function get_GS_clients_from_WP_users($condition){

  global $wpdb;

  $gs_client_table = $wpdb->prefix . ('gs_clients');

  $query = 'SELECT * FROM ' . $gs_client_table . ' WHERE ' . $condition . ' ISNULL(user_id) ';

  return $wpdb->get_results($query);

}

function update_GS_user($GS_client_id, $WP_user_id){

  global $wpdb;

  $gs_client_table = $wpdb->prefix . ('gs_clients');

  return $wpdb->update($gs_client_table, ['user_id' => $WP_user_id], ['Client_ID'=> $GS_client_id], ['%d'], ['%d']);

}

function assignClient(){
  if (!empty($_POST['client'])){
    if (!empty($_POST['user'])) {
      update_GS_user($_POST['client'], $_POST['user']);
    }
    else update_GS_user($_POST['client'], NULL);

  }

?>

  <table>
      <tr>
        <th>Cliente</th>
        <th>Usuario Wordpress</th>
        <th>Borrar relación</th>
      </tr>
      <tr>
        <?php
        $users_assigned = array();
        $clientes = get_GS_clients_from_WP_users('NOT');
        foreach ($clientes as $key => $cliente) {
          $userByID = get_user_by('ID', $cliente->user_id);
          if (!in_array($users_assigned, $userByID->ID)){
          array_push($users_assigned, $userByID->ID);
          }
          ?>
          <form class="" method="post">
            <td><?php echo $cliente->Name; ?></td>
            <td><?php echo $userByID->display_name; ?></td>
            <td><button type="submit" name="client" value="<?php echo $cliente->Client_ID?>"=>Borrar</button></td>
        </form>
      </tr>
    <?php } ?>
  </table>
  <form class="" method="post">
    <div id="clientsList">
    <select name="client" id="client" required>
      <option value="" disabled selected>Seleccione un cliente</option>
      <?php
      $clientes = get_GS_clients_from_WP_users('');
      foreach ($clientes as $key => $cliente) {
        ?>
                <option value="<?php echo $cliente->Client_ID?>"><?php echo $cliente->Name ?></option>
      <?php } ?>
  </select>
  <select name="user" id="user" required>
    <option value="" disabled selected>Seleccione un usuario de Wordpress</option>
    <?php
    $users_not_assigned = get_users( array ('exclude' => $users_assigned));
    foreach ($users_not_assigned as $key => $user_not_assigned) {
      ?>
              <option value="<?php echo $user_not_assigned->ID?>"><?php echo $user_not_assigned->display_name ?></option>
    <?php } ?>
  </select>
        <div>
          <button type="submit" name="Sincronizar" value="Sincronizar">Sincronizar</button>
        </div>
    </div>
  </form>

<?php
}

?>
