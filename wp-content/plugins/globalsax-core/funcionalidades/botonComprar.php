<?

add_action( 'woocommerce_after_cart_table', 'gbs_cartFunctions' );


function gbs_cartFunctions(){ ?>
  <div style="text-align: center; margin-top:20px">
    <a onclick="return VaciarCarrito()" class="fusion-button button-default fusion-button-default-size button">Vaciar Carrito</a>
    <a onclick="return EnviarPedido()" class="fusion-button button-default fusion-button-default-size button">Realizar Pedido</a>
    <script>
      function VaciarCarrito(){
         var data = {
           'action' : 'gbs_vaciar_carrito',
         };
         jQuery.post(ajaxurl, data, function(response){
           if (response)
             window.location = '<?php echo esc_url(get_permalink(1171)) ?>';
         });
      }
      function EnviarPedido() {
       	var data = {
       		'action': 'get_enviar_pedido',
       		'whatever': 'casa'      // We pass php values differently!
       	};

       	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
    //   	jQuery.post('".admin_url( 'admin-ajax.php' )."', data, function(response) {
       		//alert('El pedido se ha realizado con exito: ' + response);
       		//alert('El pedido se ha realizado con exito');
       	//	if(alert('El pedido se ha realizado con exito!')){}
        //       else    window.location = '".esc_url( get_permalink(11963) )."';
    //   	  })
        }
    </script>
  </div>

<?php }




/**LLAMADA AJAX**/
add_action('wp_ajax_get_enviar_pedido', 'ajax_get_enviar_pedido');
add_action('wp_ajax_nopriv_get_enviar_pedido', 'ajax_get_enviar_pedido');

add_action('wp_ajax_gbs_vaciar_carrito', 'ajax_gbs_vaciar_carrito');
add_action('wp_ajax_nopriv_gbs_vaciar_carrito', 'ajax_gbs_vaciar_carrito');


function ajax_get_enviar_pedido(){
	global $woocommerce;
	$woocommerce->cart->empty_cart();
}

function ajax_gbs_vaciar_carrito(){
  global $woocommerce;
  if ( WC()->cart->get_cart_contents_count() != 0 )
	   $woocommerce->cart->empty_cart();

  return true;
}


/**Eliminar boton de finalizar compra***/
remove_action( 'woocommerce_proceed_to_checkout','woocommerce_button_proceed_to_checkout', 20);
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );

?>
