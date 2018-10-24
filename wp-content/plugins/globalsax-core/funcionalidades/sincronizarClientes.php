<?php

$cantidadImportar = get_user_meta(1,"importar_clients",true);
$totalImportar = get_user_meta(1,"total_importar_clients",true);
//echo 'Cantidad total: '.$cantidadImportar;
/**LLAMADA AJAX**/
add_action('wp_ajax_get_sincronizar_cliente', 'ajax_get_sincronizar_cliente');
add_action('wp_ajax_nopriv_get_sincronizar_cliente', 'ajax_get_sincronizar_cliente');

function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}

function ajax_get_sincronizar_cliente(){
	//update_user_meta(1,'importar_clients',-99);
	//get_sincronizar_cliente();
  console_log('funciona');

}
function get_sincronizar_cliente(){


	$cantidadImportar = get_user_meta(1,"importar_clients",true);

	if($cantidadImportar == -99 || $cantidadImportar > 0){


		/**Obtener el archivo JSON**/

		$url = 'http://askipusax.dyndns.biz:65300/api/Client';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_URL,$url);

		$json = curl_exec($ch);

		if(curl_exec($ch) === false)

		{

			echo 'Curl error: ' . curl_error($ch);

		}

		else {

			echo 'Operación completada sin errores';

		}

		curl_close($ch);

		/**Convertir a JSON**/

		$clients = json_decode($json, true);

		$amount = (int) sizeof($clients);


		update_user_meta(1,'total_importar_clients',$amount);

		/**Crear los atributos si no existen**/

		create_att($clients);

		/**Insertar los productos**/

		insert_clients($clients);

		/**********************************************************************************************************************/

		//exit;

	} else {

		echo 'No se ha invocado ninguna llamada - get_sincronizar_producto';

	}

}

function insert_clients ($clients)

{

  if (!empty($clients)) // No point proceeding if there are no products

    {

        //array_map('insert_product', $products);

		$comienzoImportado = get_user_meta(1,"importar_clients",true);

		if($comienzoImportado == -99) $comienzoImportado = 0;


		$cantidadFija = sizeof($clients);

		$finImportado = $comienzoImportado + $cantidadFija;

		$i = 0;

		foreach ($clients as $client_data) // Go through each attribute

		{

			if($i >= $comienzoImportado and $i<= $finImportado){

				insert_product($client_data);

			}

			$i++;

			if($i == $finImportado){break;}

		}

		$totalImportar = get_user_meta(1,"total_importar_clients",true);

		if($finImportado >=  $totalImportar){

			update_user_meta(1,'importar_clients',0);

			update_user_meta(1,'total_importar_clients',0);

		}else{

			update_user_meta(1,'importar_clients',$finImportado);

		}

    }

}

/**/

function client($client_data)
{
	$IdWp = get_user_meta(1,'key_'.$client_data['Client_Id'], true);

	if(empty($IdWp)){
		/*$post = array(
			'post_author'  => 1,
			'post_content' => $product_data['Description'],
			'post_status'  => 'publish',
			'post_title'   => $product_data['Name'],
			'post_parent'  => '',
			'post_type'    => 'product'
		);
		$post_id = wp_insert_post($post);
		insert_product_category($post_id, $product_category);*/
		update_user_meta(1,'key_'.$product_data['Client_Id']);//,$post_id);
	} else {
		/*$post = array(
			'ID'  => $IdWp,
			'post_author'  => 1,
			'post_content' => $product_data['Description'],
			'post_status'  => 'publish',
			'post_title'   => $product_data['Name'],
			'post_parent'  => '',
			'post_type'    => 'product'
		);*/
		/*$post_id = wp_update_post($post);
		$product_category = $product_data['Category'];
		borrarVariacionesProductos($post_id);*/
	}
}


function insert_product_attributes ($post_id, $available_attributes, $variations)

{

    foreach ($available_attributes as $attribute) // Go through each attribute

    {

	    $values = array(); // Set up an array to store the current attributes values.

        foreach ($variations as $variation) // Loop each variation in the file

        {

            $attribute_keys = array_keys($variation['attributes']); // Get the keys for the current variations attributes

            foreach ($attribute_keys as $key) // Loop through each key

            {

                if ($key === $attribute) // If this attributes key is the top level attribute add the value to the $values array

                {

                    $values[] = $variation['attributes'][$key];

                }

            }

        }

        // Essentially we want to end up with something like this for each attribute:

        // $values would contain: array('small', 'medium', 'medium', 'large');

        $values = array_unique($values); // Filter out duplicate values

        // Store the values to the attribute on the new post, for example without variables:

        // wp_set_object_terms(23, array('small', 'medium', 'large'), 'pa_size');

        wp_set_object_terms($post_id, $values, 'pa_' . $attribute);

    }

    $product_attributes_data = array(); // Setup array to hold our product attributes data

    foreach ($available_attributes as $attribute) // Loop round each attribute

    {

        $product_attributes_data['pa_'.$attribute] = array( // Set this attributes array to a key to using the prefix 'pa'



            'name'         => 'pa_'.$attribute,

            'value'        => '',

            'is_visible'   => '1',

            'is_variation' => '1',

            'is_taxonomy'  => '1'



        );

    }

	//print_r($product_attributes_data);

    update_post_meta($post_id, '_product_attributes', $product_attributes_data); // Attach the above array to the new posts meta data key '_product_attributes'

}

//Insert product category
function insert_product_category($post_id, $product_category){
	global $wpdb;
	$term_tax_table = $wpdb->prefix . ('term_taxonomy');
	$terms_table = $wpdb->prefix . ('terms');
	$cat_id = $wpdb->get_var('SELECT term_taxonomy_id FROM '. $term_tax_table . '  AS tt, '. $terms_table .' AS t WHERE tt.term_id = t.term_id AND UPPER(t.name) = "' . $product_category .'"');

  //if (empty( $cat_id))  {$cat_id = 149;}
  console_log($cat_id);
		$values = array( 'object_id' => $post_id,
									 'term_taxonomy_id' => $cat_id
									 );

	$types = array( '%d', '%d');

	$wpdb->insert($wpdb->prefix . ('term_relationships'), $values, $types);
}
// end - Insert product category
/**/

function insert_product_variations ($post_id, $variations)

{

	foreach ($variations as $index => $variation)

    {

        $variation_post = array( // Setup the post data for the variation



            'post_title'  => 'Variation #'.$index.' of '.count($variations).' for product#'. $post_id,

            'post_name'   => 'product-'.$post_id.'-variation-'.$index,

            'post_status' => 'publish',

            'post_parent' => $post_id,

            'post_type'   => 'product_variation',

            'guid'        => home_url() . '/?product_variation=product-' . $post_id . '-variation-' . $index

        );



        $variation_post_id = wp_insert_post($variation_post); // Insert the variation

        foreach ($variation['attributes'] as $attribute => $value) // Loop through the variations attributes

        {

            $attribute_term = get_term_by('name', $value, 'pa_'.$attribute); // We need to insert the slug not the name into the variation post meta



            update_post_meta($variation_post_id, 'attribute_pa_'.$attribute, $attribute_term->slug);

          // Again without variables: update_post_meta(25, 'attribute_pa_size', 'small')

        }



        update_post_meta($variation_post_id, '_price', $variation['price']);

        update_post_meta($variation_post_id, '_regular_price', $variation['price']);

    }

}

/**/

function create_att($products)

{

  	$atributos = array();

    if (!empty($products))

    {


		$comienzoImportado = get_user_meta(1,"importar_clients",true);

		if($comienzoImportado == -99) $comienzoImportado = 0;


		$cantidadFija = sizeof($products);

		$finImportado = $comienzoImportado + $cantidadFija;

		$i = 0;

		foreach ($products as $product){

			if($i >= $comienzoImportado and $i<= $finImportado){

				foreach ($product['Variations'] as $attribute1)

				{

					$atributos[$attribute1['AttributeName']][$attribute1['AttributeValue']] = $attribute1['AttributeValue'];

					foreach ($attribute1['Variations'] as $attribute2)

					{

						$atributos[$attribute2['AttributeName']][$attribute2['AttributeValue']] = $attribute2['AttributeValue'];

					}

				}

			}

			$i++;if($i == $finImportado)break;

		}

		foreach ($atributos as $taxonomy => $value )

		{

			save_product_attribute_from_name(strtolower($taxonomy),$taxonomy);

			foreach ($value as $key2 => $term_name) {

				if( ! term_exists( $term_name, 'pa_' . strtolower($taxonomy) ) ){

					wp_insert_term( $term_name, 'pa_' . strtolower($taxonomy) );

				}

			}

		}

    }

}



/**

 * Save a new product attribute from his name (slug).

 */

function save_product_attribute_from_name( $name, $label='', $set=true ){

    if( ! function_exists ('get_attribute_id_from_name') ) return;

    global $wpdb;

    $label = $label == '' ? ucfirst($name) : $label;

    $attribute_id = get_attribute_id_from_name( $name );



    if( empty($attribute_id) ){

        $attribute_id = NULL;

    } else {

        $set = false;

    }

    $args = array(

        'attribute_id'      => $attribute_id,

        'attribute_name'    => $name,

        'attribute_label'   => $label,

        'attribute_type'    => 'select',

        'attribute_orderby' => 'menu_order',

        'attribute_public'  => 0,

    );



    if( empty($attribute_id) )

        $wpdb->insert(  "{$wpdb->prefix}woocommerce_attribute_taxonomies", $args );



    if( $set ){

        $attributes = wc_get_attribute_taxonomies();

        $ars['attribute_id'] = get_attribute_id_from_name( $name );

        $attributes[] = (object) $args;

        set_transient( 'wc_attribute_taxonomies', $attributes );

    } else {

        return;

    }

}



/**

 * Get the product attribute ID from the name.

 */

function get_attribute_id_from_name( $name ){

    global $wpdb;

    $attribute_id = $wpdb->get_col("SELECT attribute_id

    FROM {$wpdb->prefix}woocommerce_attribute_taxonomies

    WHERE attribute_name LIKE '$name'");

    return reset($attribute_id);

}

/**/

function get_available_attributes($product_data){

	$available_attributes = array();

	foreach ($product_data['Variations'] as $attribute1)

	{

		$available_attributes[$attribute1['AttributeName']][$attribute1['AttributeValue']] = $attribute1['AttributeValue'];

		foreach ($attribute1['Variations'] as $attribute2)

		{

			$available_attributes[$attribute2['AttributeName']][$attribute2['AttributeValue']] = $attribute2['AttributeValue'];

		}



	}

	foreach ($available_attributes as $attribute => $variations)

    {

		$values[] = strtolower($attribute);

    }

	return $values;

}

/**/

function get_variations($product_data){

	$string = '[ { "attributes": { "size" : "Small", "color" : "Red" }, "price" : "8.00" }, ]';

	$available_attributes = array();

	$string = '[';

	foreach ($product_data['Variations'] as $attribute1)

	{

		if(count($attribute1['Variations']) > 0){

			foreach ($attribute1['Variations'] as $attribute2)

			{

				$string = $string.'{ "attributes": {';

				$string = $string.'"'.strtolower($attribute1['AttributeName']).'"  : "'.$attribute1['AttributeValue'].'",';

				$string = $string.'"'.strtolower($attribute2['AttributeName']).'"  : "'.$attribute2['AttributeValue'].'"';

				$string = $string.'},"price" : "99.00"},';

			}

		}

		else{

			$string = $string.'{ "attributes": {';

			$string = $string.'"'.strtolower($attribute1['AttributeName']).'"  : "'.$attribute1['AttributeValue'].'"';

			$string = $string.'},"price" : "99.00"},';

		}

	}

	$string = substr($string, 0, -1);

	$string = $string.']';

	return json_decode($string,true);

}


?>
