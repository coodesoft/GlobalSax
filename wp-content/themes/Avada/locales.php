<?php

require_once(__DIR__ . '/../db/Clients.php');

global $wpdb;

/*$user = wp_get_current_user();

$locales_db = apply_filters('locales_db', $wpdb);
$table_name = $locales_db->prefix .*/
$clientes = Clients::getAll();
$sucursales = Clients::getSucursales();
 //$sucursales = Clients::getSucursalesByClient($id);
 /*<ul>
   <?php foreach ($clientes as $key => $value) { ?>
     <li><?php echo $value['nombre_cliente']?></li>
   <?php } ?>
 </ul>*/
?>
<div id="body">
 <section class="section">
   <div class="interior">
   <div class="titular"><h2>Locales</h2></div>

   <div class="info_list">
     <p><span>Sitio Web </span></p>
     <p><span>Venta Mayorista </span></p>
     <p><span>Venta Minorista </span></p>
     <p><span>Venta Online </span></p>
     <p><span>Revendedoras </span></p>
   </div>
   <div class="buscar">
     <button>Buscar</button>
     <input type="search" placeholder="Buscar">
   </div>
   <div class="mapas">
     <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d242211.64174373038!2d-58.44772574163197!3d-34.64593114556312!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95edbcb7595281d9%3A0x4ad309fcdcf0a144!2sBuenos+Aires!5e0!3m2!1ses-419!2sar!4v1534285720746" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
   </div>

   <div class="sucursales_div">
     <ul>
       <?php foreach ($sucursales as $key => $value) { ?>
       <li> <?php echo $value['id'] ?> </li>
     <?php } ?>
     </ul>
   </div>

 </div>
 </section>
</div>
