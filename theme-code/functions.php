<?php

function create_meta(){
    $user_id = get_current_user_id();
    $meta_key = 'shipping_custom_data';
    $meta_value = wp_json_encode(array("Peter"=>35, "Ben"=>37, "Joe"=>43)); 
    $prev_value = false;
	// update_user_meta( $user_id, $meta_key, $meta_value, $prev_value );
}
// add_action('init','create_meta');

function display_shipping_data()
{
	ob_start();
    ?>
    <a href="#" >Add Shipping</a>
    <?php
    $output = ob_get_contents();
   echo  $output;
    ob_end_clean();
}
add_action('shipping_after_address_display','display_shipping_data');
add_action('woocommerce_after_edit_account_address_form','display_shipping_data');

function save_form_ajax(){
	$user_id = get_current_user_id();
	$shipping = array();
	$data = array(
		'shipping_first_name'=>$_POST['shipping_first_name'],
		'shipping_last_name'=>$_POST['shipping_last_name'],
		'shipping_company'=>$_POST['shipping_company'],
		'shipping_address_1'=>$_POST['shipping_address_1'],
		'shipping_address_2'=>$_POST['shipping_address_2'],
		'shipping_city'=>$_POST['shipping_city'],
		'shipping_state'=>$_POST['shipping_state'],
		'shipping_postcode'=>$_POST['shipping_postcode'],
		'shipping_country'=>$_POST['shipping_country'],
	);	
	$get_data = get_user_meta($user_id,'shipping_custom_data');	
	$count = 1;
	
	if(is_array($get_data) && count($get_data) != 0){
		$shipping = json_decode($get_data[0]);		
		array_push($shipping,$data); 
	}else{
		$shipping[] = $data;
	}	
    $meta_key = 'shipping_custom_data';
    $meta_value = wp_json_encode($shipping); 
    $prev_value = false;	
	update_user_meta( $user_id, $meta_key, $meta_value, $prev_value );
  die;
}
add_action('wp_ajax_save_form_ajax', 'save_form_ajax');
add_action('wp_ajax_nopriv_save_form_ajax', 'save_form_ajax');

function footer_script(){
 ?>
 <script>
	jQuery('form button[name="save_address"]').click(function(){
		var url = window.location.href;
		var arr = url.split('/custom-address=');
		if(arr.length == 2){
			var num = arr[1].replace('/','');
			var shipping_first_name = jQuery('input[name="shipping-custom-address'+num+'_first_name"]').val();
			var shipping_last_name = jQuery('input[name="shipping-custom-address'+num+'_last_name"]').val();
			var shipping_company = jQuery('input[name="shipping-custom-address'+num+'_company"]').val();
			var shipping_address_1 = jQuery('input[name="shipping-custom-address'+num+'_address_1"]').val();
			var shipping_address_2 = jQuery('input[name="shipping-custom-address'+num+'_address_2"]').val();
			var shipping_city = jQuery('input[name="shipping-custom-address'+num+'_city"]').val();
			var shipping_state = jQuery('select[name="shipping-custom-address'+num+'_state"]').val();
			var shipping_postcode = jQuery('input[name="shipping-custom-address'+num+'_postcode"]').val();
			var shipping_country = jQuery('select[name="shipping-custom-address'+num+'_country"]').val();
			jQuery.ajax({
				type: 'POST',
				url: 'http://localhost/shop-demo/wp-admin/admin-ajax.php',
				data: {
					'action': 'save_form_ajax',
					'shipping_first_name':shipping_first_name,
					'shipping_last_name': shipping_last_name,
					'shipping_company': shipping_company,
					'shipping_address_1': shipping_address_1,
					'shipping_address_2': shipping_address_2,
					'shipping_city': shipping_city,
					'shipping_state':shipping_state,
					'shipping_postcode':shipping_postcode,
					'shipping_country':shipping_country,
				},
				success: function (serverResponse) {
					console.log(serverResponse);
					window.location.href = "http://localhost/shop-demo/my-account/edit-address/";
				}
			});
		}    
		return false;
	});
 </script>
 <?php 
}
add_action('wp_footer','footer_script');