<?php

//require_once(ABSPATH . '/wp-content/plugins/woo-variations-table/woo-variations-table.php');

add_shortcode( 'gbs_catalog', 'gbs_catalog');
function gbs_get_categories(){
  $orderby = 'name';
  $order = 'asc';
  $hide_empty = false ;
  $cat_args = array(
      'orderby'    => $orderby,
      'order'      => $order,
      'hide_empty' => $hide_empty,
  );

  $product_categories = get_terms( 'product_cat', $cat_args );

  $categories = [];
  foreach ($product_categories as $key => $category) {
    $categories[$key]['name'] = $category->name;
    $categories[$key]['id'] = $category->term_id;
  }

  return $categories;
}


add_action( 'wp_ajax_nopriv_gbs_get_products_by_category', 'gbs_get_products_by_category' );
add_action( 'wp_ajax_gbs_get_products_by_category', 'gbs_get_products_by_category' );
function gbs_get_products_by_category(){
  $params = array();
  parse_str($_POST['data'], $params);

  $args = [
    'post_type' => 'product',
    'orderby'   => 'title',
    'status' => 'publish',
    'posts_per_page' => -1,
    'tax_query' => [
        [
          'taxonomy'  => 'product_cat',
    			'field'     => 'id',
    			'terms'     => $params['Category']
        ]
    ]
  ];

  $products = new WP_Query($args);
  echo gbs_products_list($products);
  wp_die();
}

add_action( 'wp_ajax_nopriv_gbs_load_variations', 'gbs_load_variations' );
add_action( 'wp_ajax_gbs_load_variations', 'gbs_load_variations' );
function gbs_load_variations(){

  $id = $_POST['product_id'];

  $factory = new WC_Product_Factory();
  $product = $factory->get_product($id);

  $args = array(
  	'post_type'     => 'product_variation',
  	'post_status'   => array( 'private', 'publish' ),
  	'numberposts'   => -1,
  	'orderby'       => 'menu_order',
  	'order'         => 'asc',
  	'post_parent'   => $id // get parent post-ID
  );
  $variations = get_posts( $args );

  $varMeta = gbsBuildVariationArray($variations);

  echo gbs_variation_table($varMeta, $id, $product->get_name());
  wp_die();
}

add_action( 'wp_ajax_nopriv_gbs_add_variations_to_cart', 'gbs_add_variations_to_cart' );
add_action( 'wp_ajax_gbs_add_variations_to_cart', 'gbs_add_variations_to_cart' );
function gbs_add_variations_to_cart(){
  $variations = array();
  parse_str($_POST['data'], $variations);

  $safe_parent = intval($variations['Product']);

  if ($safe_parent){
    $parent_product = $variations['Product'];

    $error = [];
    foreach ($variations['Variation'] as $id => $quantity) {
      if ($quantity != "" && $quantity>0){
        $result = WC()->cart->add_to_cart( $parent_product, $quantity, $id, wc_get_product_variation_attributes( $id ) );
        if (!$result)
          $error[$id] = $result;
      }
    }

    if (count($error)>0)
      echo json_encode([
          'msg' => 'Se produjo un error al agregar al carrito las variaciones con ID: ' . implode(',', $error),
          'type' => 'gbs-error',
        ]);
    else
    echo json_encode([
        'msg' => 'Se agregron las variaciones correctamente al carrito',
        'type' => 'gbs-success',
      ]);



  }else
    echo json_encode([
        'msg' => 'Error: El ID del producto es invalido.',
        'type' => 'gbs-error',
      ]);

  wp_die();
}


/* FUNCIONES INTERNAS DE RETORNO */
function gbs_catalog(){
  $categories = gbs_get_categories();
  ?>
  <div id="gbsCatalog">

    <div id="selectCategoryForm">
      <label for="product_cat_selection">Seleccione una categoría</label>
      <select name="Category" id="product_cat_selection">
          <option value="">Categoría</option>
        <?php foreach ($categories as $key => $cat) { ?>
          <option value="<?php echo $cat['id']?>"><?php echo $cat['name']?></option>
        <?php } ?>
      </select>
      <a href= " <?php home_url('/carrito') ?> " class="fusion-button button-flat fusion-button-pill button-large button-custom button-2" >Revisar pedido</a>
    </div>

    <div id="gbs_action_result" class="gbs-dialog">
      <p class="body"></p>
      <span class="gbs-close">x</span>
    </div>
    <div id="gbs_variation_popup" class="gbs-dialog">
      <div class="header"><span class="gbs-close">x</span></div>
      <div class="body"></div>
    </div>

    <div id="gbs_productos_list"></div>
  </div>
<?php }

function gbs_products_list($products){ ?>

  <ul class="products products-3">
    <?php while ($products->have_posts()) : ?>
      <?php $products->the_post();
            $product = get_product( $products->post->ID );
      ?>
      <li class="product-<?php echo $product->get_id() ?> product product-type" data-product="<?php echo $product->get_id() ?>">

        <?php /*<div class="featured-image">
        		<label alt="Marcador" width="500" class="woocommerce-placeholder wp-post-image" height=""><?php echo $product->get_id()  ?> </label>
              <div class="cart-loading"><i class="fusion-icon-spinner"></i></div>
      	</div>*/

      	?>
        <div class="product-details">
        	<div class="product-details-container">
        	    <?php $cantidad = 0;
           foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ){
              if ($cart_item['product_id'] == $product->get_id()){
              $cantidad += $cart_item['quantity'];
              }
            } ?>
            <label class="product-title" data-fontsize="16" data-lineheight="24" style="text-align: left;"><?php echo $product->get_name() ." Cantidad pedido: ". $cantidad  ?></label>

             </div>
        </div>
        <div id="variation-<?php echo $product->get_id() ?>" class="product-variations"> </div>
      </li>
    <?php endwhile;
        wp_reset_query();
    ?>
  </ul>

<?php }

function gbs_variation_table($varMeta, $parentId, $parentName){
  $talles = $varMeta['talles'];
  $variations = $varMeta['variations'];
  ?>
  <div id="gbs-product-name"><h3>Producto: <?php echo $parentName ?></h3></div>
  <form id="gbsAddVariationToCartForm">
    <input type="hidden" name="Product" value="<?php echo $parentId ?>">
    <table>
      <tr>
        <th></th>
        <?php foreach ($talles as $index => $talle): ?>
        <th><?php echo $index ?></th>
        <?php endforeach; ?>
      </tr>

      <?php foreach ($variations as $color => $meta): ?>
      <tr>
        <td class="gbs_tag"><?php echo strtoupper($color)?></td>
        <?php foreach ($meta as $talle => $IdVariation): ?>
        <td class="gbs_data" data-color="<?php echo $color ?>" data-talle="<?php echo $talle ?>">
          <input type="number" name="Variation[<?php echo $IdVariation ?>]">
        </td>
        <?php endforeach; ?>
      </tr>
      <?php endforeach; ?>
    </table>
    <button id="gbsAddVariationToCartButton">Agregar al carrito</button>
  </form>
<?php }

/* FUNCIONES ESTRUCTURALES */
function gbsBuildVariationArray($variations){
  $arrVariations = [];
  $talles = [];
  foreach ($variations as $key => $variation) {
    $metadata = get_post_meta($variation->ID);
    $talle = $metadata['attribute_pa_talle'][0];
    $color = $metadata['attribute_pa_color'][0];
    $talles[$talle] = 1;
    $arrVariations[$color][$talle] = $variation->ID;
  }

  ksort($talles);
  $varMeta['talles'] = $talles;
  $varMeta['variations'] = gbsOrderVariationArrByTalle($arrVariations);
  return $varMeta;
}

function gbsOrderVariationArrByTalle($arrVariations){
  $tmp = [];
  foreach ($arrVariations as $key => $vari) {
    ksort($vari);
    $tmp[$key] = $vari;
  }

  return $tmp;
}
