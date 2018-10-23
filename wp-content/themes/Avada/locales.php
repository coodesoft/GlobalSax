<?php

require_once(__DIR__ . '/../../plugins/custom-upload/db/Clients.php');

global $wpdb;
/* ?>

<script>
function initMap() {
var map = new google.maps.Map(document.getElementById('map'), {
  center: new google.maps.LatLng(-33.863276, 151.207977),
  zoom: 12
});
var infoWindow = new google.maps.InfoWindow;


  downloadUrl('https://storage.googleapis.com/mapsdevsite/json/mapmarkers2.xml', function(data) {
    var xml = data.responseXML;
    var markers = xml.documentElement.getElementsByTagName('marker');
    Array.prototype.forEach.call(markers, function(markerElem) {
      var id = markerElem.getAttribute('id');
      var name = markerElem.getAttribute('name');
      var address = markerElem.getAttribute('address');
      var type = markerElem.getAttribute('type');
      var point = new google.maps.LatLng(
          parseFloat(markerElem.getAttribute('lat')),
          parseFloat(markerElem.getAttribute('lng')));

      var infowincontent = document.createElement('div');
      var strong = document.createElement('strong');
      strong.textContent = name
      infowincontent.appendChild(strong);
      infowincontent.appendChild(document.createElement('br'));

      var text = document.createElement('text');
      text.textContent = address
      infowincontent.appendChild(text);
      var icon = customLabel[type] || {};
      var marker = new google.maps.Marker({
        map: map,
        position: point,
        label: icon.label
      });
      marker.addListener('click', function() {
        infoWindow.setContent(infowincontent);
        infoWindow.open(map, marker);
      });
    });
  });
}



function downloadUrl(url, callback) {
var request = window.ActiveXObject ?
    new ActiveXObject('Microsoft.XMLHTTP') :
    new XMLHttpRequest;

request.onreadystatechange = function() {
  if (request.readyState == 4) {
    request.onreadystatechange = doNothing;
    callback(request, request.status);
  }
};

request.open('GET', url, true);
request.send(null);
}

function doNothing() {}

</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-cK8Eml2T7tgYd0JjWQnXH7hJHzupqe8&callback=initMap"></script>
<?php */
/*$user = wp_get_current_user();

$locales_db = apply_filters('locales_db', $wpdb);
$table_name = $locales_db->prefix .*/

$clientes = Clients::getAll();
$sucursales = Clients::getSucursales();
/*foreach ($sucursales as $key => $value) {
    $address = $value['direccion_publica'] . ", " . $value['ciudad'] . ", " . $value['provincia'];
}

$address   = urlencode($address);

$url       = "https://maps.google.com/maps/api/geocode/json?sensor=false&address={$address}";
$resp_json = file_get_contents($url);
$resp      = json_decode($resp_json, true);

    if ($resp['status'] == 'OK') {
        // get the important data
        $lati  = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        /*echo $lati;
        echo $longi;*/


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

   <div class="buscar trescol input">
     <img class="adj file-archivo" src="/demo/img/lupa.svg">
     <input id="buscar" type="search" placeholder="Buscar">
   </div>

   <div class="other_section">
   <div class="mapas">
     <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d242211.64174373038!2d-58.44772574163197!3d-34.64593114556312!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95edbcb7595281d9%3A0x4ad309fcdcf0a144!2sBuenos+Aires!5e0!3m2!1ses-419!2sar!4v1534285720746" width="600" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>
   </div>


  <div class="info_list">
      <select name="menu-prov" class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required" aria-required="true" aria-invalid="false">
        <option value="">---</option>
        <option value="Buenos Aires">Buenos Aires</option>
        <option value="Buenos Aires-GBA">Buenos Aires-GBA</option>
        <option value="Capital Federal">Capital Federal</option>
        <option value="Catamarca">Catamarca</option>
        <option value="Chaco">Chaco</option>
        <option value="Chubut">Chubut</option>
        <option value="Cordoba">Cordoba</option>
        <option value="Corrientes">Corrientes</option>
        <option value="Entre Rios">Entre Rios</option>
        <option value="Formosa">Formosa</option>
        <option value="Jujuy">Jujuy</option>
        <option value="La Pampa">La Pampa</option>
        <option value="La Rioja">La Rioja</option>
        <option value="Mendoza">Mendoza</option>
        <option value="Misiones">Misiones</option>
        <option value="Neuquen">Neuquen</option>
        <option value="Rio Negro">Rio Negro</option>
        <option value="Salta">Salta</option>
        <option value="San Juan">San Juan</option>
        <option value="San Luis">San Luis</option>
        <option value="Santa Cruz">Santa Cruz</option>
        <option value="Santa Fé">Santa Fé</option>
        <option value="Santiago del Estero">Santiago del Estero</option>
        <option value="Tierra del Fuego">Tierra del Fuego</option>
        <option value="Tucuman">Tucuman</option></select>

     <ul>
      <div class="item_list"><img class="locales_img" src="/demo/img/locales_sitio_web.svg"><span>Sitio Web </span></div>
      <div class="item_list"><img class="locales_img" src="/demo/img/locales_venta_mayorista.svg"><span>Venta Mayorista </span></div>
      <div class="item_list"><img class="locales_img" src="/demo/img/locales_venta_minorista.svg"><span>Venta Minorista </span></div>
      <div class="item_list"><img class="locales_img" src="/demo/img/locales_venta_online.svg"><span>Venta Online </span></div>
      <div class="item_list"><img class="locales_img" src="/demo/img/locales_revendedoras.svg"><span>Revendedoras </span></div>
    </ul>
   </div>
 </div>

   <?php $ciudadescargadas = array();
   $provinciascargadas = array();
   ?>
     <div class="sucursales_div">
        <?php foreach ($sucursales as $key => $value) {
         if (!(in_array($value['provincia'], $provinciascargadas))){ ?>
          <h2><?php echo $value['provincia'] ?></h2>
          <?php array_push($provinciascargadas, $value['provincia']);
          } ?>
          <div class="provincia">
              <?php foreach ($sucursales as $k => $v) { ?>
                <?php if (($v['provincia'] == $value['provincia'])){ ?>
                <?php if (!(in_array($v['id'], $ciudadescargadas))){ ?>
                  <div class="container-prov">
                  <div class="ciudad"> <?php echo $v['ciudad'] ?>   </div>
                  <div id="hidden-info" class="sucursal">
                    <div class="nombre_cliente"> <span><?php echo $v['nombre_cliente'] ?></span></div>
                    <div class="info">
                      <ul>
                      <?php if (($v['sitio_web'])== true) { ?>
                        <li><img class="locales_img items" src="/demo/img/locales_sitio_web.svg" href="#"></li>
                      <?php } ?>

                      <?php if (($v['venta_mayorista'])== true) { ?>
                        <li><img class="locales_img items" src="/demo/img/locales_venta_mayorista.svg"></li>
                      <?php } ?>

                      <?php if (($v['venta_minorista'])== true) {  ?>
                        <li><img class="locales_img items" src="/demo/img/locales_venta_minorista.svg"></li>
                      <?php } ?>

                      <?php if (($v['venta_online'])== true) { ?>
                        <li><img class="locales_img items" src="/demo/img/locales_venta_online.svg"></li>
                      <?php } ?>

                      <?php if (($v['revendedoras'])== true) { ?>
                        <li><img class="locales_img items" src="/demo/img/locales_revendedoras.svg"></li>
                      <?php } ?>
                    </ul>
                    </div>
                    <div class="direccion_publica"> <?php echo $v['direccion_publica'] ?> </div>
                  </div>
                </div>
                    <?php array_push($ciudadescargadas, $v['id']);
                  }
                  }
                } ?>
          </div>
        <?php } ?>
      </div>

 </div>
 </section>
 </div>
