<?php
/**
 * OceanWP Child Theme Functions
 *
 * When running a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions will be used.
 *
 * Text Domain: oceanwp
 * @link http://codex.wordpress.org/Plugin_API
 *
 */

/**
 * Load the parent style.css file
 *
 * @link http://codex.wordpress.org/Child_Themes
 */
function oceanwp_child_enqueue_parent_style() {

	// Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update the theme).
	$theme   = wp_get_theme( 'OceanWP' );
	$version = $theme->get( 'Version' );

	// Load the stylesheet.
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'oceanwp-style' ), $version );
	
}

add_action( 'wp_enqueue_scripts', 'oceanwp_child_enqueue_parent_style' );




function edit_shipping_address_from_url()
{
    
        $url = parse_url($_SERVER['REQUEST_URI']);
        $type = explode('=', $url['query'])[1];		
        $address_new = [];
        if (!empty($type) && is_user_logged_in() && $_POST['save_address']) {
            $addresses_data = get_user_meta(get_current_user_id(), 'thwma_custom_address', true);
            if (empty($addresses_data['shipping'])) {	
				// blank array data store			
                $address_new['shipping'][$type] = array(
                    'shipping_first_name' => $_POST['shipping_first_name'],
                    'shipping_last_name' => $_POST['shipping_last_name'],
                    'shipping_company' => $_POST['shipping_company'],
                    'shipping_country' => $_POST['shipping_country'],
                    'shipping_address_1' => $_POST['shipping_address_1'],
                    'shipping_address_2' => $_POST['shipping_address_2'],
                    'shipping_city' => $_POST['shipping_city'],
                    'shipping_state' => $_POST['shipping_state'],
                    'shipping_postcode' => $_POST['shipping_postcode'],                   
                );
              //  $address_new['default_shipping'] = $type;
                update_user_meta(get_current_user_id(), 'check_user_login', 1);

            } else {
                foreach ($addresses_data as $address_type => $address_value) {
                    if ($address_type == 'shipping') {
                        if (in_array($type, array_keys($address_value))) {
                            foreach ($address_value as $shipping_key => $shipping_value) {
								// update data array								
                                if ($type != $shipping_key) {
                                    $address_new['shipping'][$shipping_key] = $shipping_value;

                                } else {
                                    $address_new['shipping'][$type] = array(
                                        'shipping_first_name' => $_POST['shipping_first_name'],
                                        'shipping_last_name' => $_POST['shipping_last_name'],
                                        'shipping_company' => $_POST['shipping_company'],
                                        'shipping_country' => $_POST['shipping_country'],
                                        'shipping_address_1' => $_POST['shipping_address_1'],
                                        'shipping_address_2' => $_POST['shipping_address_2'],
                                        'shipping_city' => $_POST['shipping_city'],
                                        'shipping_state' => $_POST['shipping_state'],
                                        'shipping_postcode' => $_POST['shipping_postcode'],                                      
                                    );
                                }
                            }
                        } else {
							// new data array
                            $address_new['shipping'] = $address_value;
                            $address_new['shipping'][$type] = array(
                                'shipping_first_name' => $_POST['shipping_first_name'],
                                'shipping_last_name' => $_POST['shipping_last_name'],
                                'shipping_company' => $_POST['shipping_company'],
                                'shipping_country' => $_POST['shipping_country'],
                                'shipping_address_1' => $_POST['shipping_address_1'],
                                'shipping_address_2' => $_POST['shipping_address_2'],
                                'shipping_city' => $_POST['shipping_city'],
                                'shipping_state' => $_POST['shipping_state'],
                                'shipping_postcode' => $_POST['shipping_postcode'],                                
                            );
                        }
                    }
                }
                $address_new['default_shipping'] = $addresses_data['default_shipping'];
            }				
            update_user_meta(get_current_user_id(), 'thwma_custom_address', $address_new);
        } 

}
add_action('wp_loaded', 'edit_shipping_address_from_url');