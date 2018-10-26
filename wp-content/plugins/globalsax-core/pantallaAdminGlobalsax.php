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


function get_GS_user(){
  global $wpdb;
  $gs_client_table = $wpdb->prefix . ('gs_clients');
  $query = 'SELECT * FROM ' . $gs_client_table . ' WHERE NOT ISNULL(user_id) ';
  return $wpdb->get_results($query);
}

function assignClient(){
?>
  <table>
      <tr>
        <th>Cliente</th>
        <th>Usuario Wordpress</th>
        <th>Borrar relación</th>
      </tr>
      <tr>
        <?php
        $clientes = get_GS_user();
        foreach ($clientes as $key => $cliente) {
          $userByID = get_user_by('ID', $cliente->user_id);
          ?>
        <td><?php echo $cliente->Name; ?></td>
        <td><?php echo $userByID->display_name; ?></td>
        <td><?php echo '$userByID'; ?></td>
      </tr>
    <?php } ?>
  </table>
    <div class="cu-submit-button cu-text-center">
      <button type="submit">Editar características</button>
      <div class="cu-loader"></div>
    </div>
<?php
}

?>
