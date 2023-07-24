<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
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
 * @version 2.6.0
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$customer_id = get_current_user_id();
$woo_address_book_shipping_address = get_user_meta($customer_id, 'shipping_address_1', true );
$user_id = get_current_user_id();
$custom_addresses = get_user_meta($customer_id, THMAF_Utils::ADDRESS_KEY, true);    

// $custom_addresses_shipping = THMAF_Utils::get_addresses($customer_id, 'shipping');

 //$shipping_addresses = $this->get_account_addresses($customer_id, 'shipping', $custom_addresses_shipping);

//$additional_billing_addresses = apply_filters('additional_billing_address_label','Additional billing addresses');


if (! wc_ship_to_billing_address_only() && wc_shipping_enabled()) {
    $get_addresses = apply_filters('woocommerce_my_account_get_addresses', array(
        'billing' => esc_html__('Billing address', 'themehigh-multiple-addresses'),
        'shipping' => esc_html__('Shipping address', 'themehigh-multiple-addresses'),
    ), $customer_id);
} else {
    $get_addresses = apply_filters('woocommerce_my_account_get_addresses', array(
        'billing' => esc_html__('Billing address', 'themehigh-multiple-addresses'),
    ), $customer_id);
}

$oldcol = 1;
$col    = 1; ?>



<?php if (! wc_ship_to_billing_address_only() && wc_shipping_enabled()) : ?>
    <div class="u-columns woocommerce-Addresses col2-set addresses">
<?php endif; ?>


<?php 
//print_r($custom_addresses);
print_r($custom_addresses);
foreach ($custom_addresses as $name => $title) : ?>


<div class="u-column<?php echo (($col = $col * -1) < 0) ? 1 : 2; ?> col-<?php echo (($oldcol = $oldcol * -1) < 0) ? 1 : 2; ?> woocommerce-Address">
   <?php  ?>
   
    <header class="woocommerce-address-title title">
        <h3><?php  ?></h3>
        <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', $name)); ?>" class="edit"><?php _e('Edit', 'themehigh-multiple-addresses'); ?></a>
    </header>
    <address>
        <?php // print_r($name); 
              foreach($title as $item){
               // print_r($item);
                echo $item['shipping_first_name'].'</br>';
                echo $item['shipping_last_name'].'</br>';
                echo $item['shipping_company'].'</br>';
                echo $item['shipping_country'].'</br>';
                echo $item['shipping_address_1'].'</br>';
                echo $item['shipping_address_2'].'</br>';
                echo $item['shipping_city'].'</br>';
                echo $item['shipping_state'].'</br>';
                echo $item['shipping_postcode'].'</br>';
               
              }
        ?>
        <?php
        /*$address = wc_get_account_formatted_address($name);
        echo $address ? wp_kses_post($address) : esc_html_e('You have not set up this type of address yet.', 'themehigh-multiple-addresses'); */ ?> 
    </address>
</div>

<?php endforeach; ?>

<?php if (! wc_ship_to_billing_address_only() && wc_shipping_enabled()) : ?>
    </div>
<?php endif;
do_action('thmaf_after_address_display', $customer_id);