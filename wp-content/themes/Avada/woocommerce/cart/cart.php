<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>
    <p class="woocommerce-store-notice demo_store"> Su pedido es un compromiso de compra </p>
<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

    <div class="datos_user_cart">
    	<?php
global $wpdb;
//si el usuario esta loggeado
if( is_user_logged_in()  ) {
	//obtengo el usuario
	$user = wp_get_current_user();
	echo "<label style='padding-left:10%;'> Pedido de: " . $user->display_name . "</label>";
  $table = $wpdb->prefix . ('usermeta');
	//si el rol del usuario es Cliente
	if ( in_array( 'customer', (array) $user->roles ) ) {
		//obtengo los datos de la tabla wd_usermeta, que contiene id_sucursal
    $queryStr = "SELECT * FROM ". $table ." WHERE user_id = " . $user->ID . " AND meta_key LIKE 'id_sucursal' ";
    $sucursales =  $wpdb->get_results($queryStr, ARRAY_A);
		//si tiene mas de 1 sucursal asignada carga el selector de sucursales

    if (sizeof($sucursales) > 0){

      ?>
      <div id="sucursalSelection">
          <div>Seleccione la sucursal:</div>

          <form id="sucursalesByClienForm">
            <div id="sucursalesList">
              <select name="sucursal" style="margin-left:5%;" required>
                  <option value="" disabled selected>Seleccione una sucursal</option>
                <?php
                foreach ($sucursales as $key => $sucursal) {
                    foreach ($sucursal as $k_suc => $v_suc) {
                        if ($k_suc == 'meta_value'){
                ?>
                  <option value="<?php echo $v_suc?>"><?php echo $v_suc ?></option>
                <?php }}} ?>
              </select>
            </div>
            </form>
      </div>
<?php
	} //cierre del sizeof($sucursales)
	}}
	?>
	</div>
	<div class="cart_style">
		<?php

		//echo json_encode(WC()->cart->get_cart());
			$product_list = [];
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ){
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				$product_cat = get_the_terms($product_id, 'product_cat');
				$product_cat = $product_cat[0]->name;

				if (array_key_exists($product_cat, $product_list)){
					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) )
						$product_list[$product_cat]['cant'] += $cart_item['quantity'];
				} else{
					$product_list[$product_cat] = [];
					$product_list[$product_cat]['name'] = $product_cat;
					$product_list[$product_cat]['cant'] = $cart_item['quantity'];
				}

			}
			$cant_total = 0;
			foreach ($product_list as $key => $category) {
			    $cant_total += $category['cant'];
			}
			?>
			<?php echo '<h2 data-fontsize="18" data-lineheight="27"> Tiene '. $cant_total .' elementos en su Carrito </h2>'; ?>
    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<thead>

			<tr>
				<?php // ThemeFusion edit for Avada theme: change table layout and columns. ?>
				<th class="product-name"><?php _e( 'Category', 'woocommerce' ); ?></th>
				<th class="product-quantity"><?php _e( 'Quantity', 'woocommerce' ); ?></th>

			</tr>
		</thead>

		<tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
			foreach ($product_list as $key => $category) { ?>
				<tr>
					<td class="product_name">
						<div class="product-info">
							<a href="#" class="product-title"><?php echo strtoupper($category['name']) ?> </span>
						</div>
					</td>
					<td class="product-quantity">
						<div class="quantity"><?php echo $category['cant'] ?></div>
					</td>
				</tr>


			<?php } ?>


			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<tr class="avada-cart-actions">
				<td colspan="6" class="actions">

					<?php if ( wc_coupons_enabled() ) { ?>
						<div class="coupon">
							<label for="coupon_code"><?php _e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>" />
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php } ?>

					<input type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>" />

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart' ); ?>
				</td>
			</tr>
			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</tbody>
	</table>
	<div><p></p></div>
	<div class="do_pedido" style="display:flex;font-size:1.2em;padding-left:34%;padding-top:2%;border-top:solid 0.2px;">
        <input type="radio" name="pedido" value="G" checked> Guardar pedido<br>
	    <input type="radio" name="pedido" value="A" style="margin-left:10%"> Anular pedido<br>
    </div>
    </div>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<div class="cart-collaterals">
	<?php
		/**
		 * woocommerce_cart_collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
	 //	do_action( 'woocommerce_cart_collaterals' );

	?>
</div>

<?php do_action( 'woocommerce_after_cart' );

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
