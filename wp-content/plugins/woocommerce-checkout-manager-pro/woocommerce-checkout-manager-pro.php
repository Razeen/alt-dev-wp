<?php
/*

Plugin Name: WooCommerce Checkout Manager Pro
Plugin URI: http://www.trottyzone.com/product/woocommerce-checkout-manager
Description: Manages WooCommerce Checkout fields
Version: 1.1
Author: Ephrain Marchan
Author URI: http://www.trottyzone.com
License: GPLv2 or later
*/

/*
------------------------------------------------------------------------------
WooCommerce Checkout Manager Pro, Copyright (C) 2013 Ephrain Marchan, TrottyZone
-------------------------------------------------------------------------------

-------------------------------------------------------------------------------
--------------------------- NOTICE!!!!!!!!!!!!!!!!!! -------------------------
-------------------------------------------------------------------------------

Please do not edit woocommerce-checkout-manager-pro.php file or any other files of
WooCommerce Checkout Manager Pro. If you do, we are not responsible if your site shuts down,
or any other consequences of such actions.

This plugin may not be copy, distributed, transmitted, publicly display,
publicly perform, reproduce, edit, translate and reformat any way and
should ONLY be used on per domain of purchase.

Please respect our wishes granted that alot of effort has been
put into this plugin to help you have a better experience.

Should you have any questions or concerns, please do contact us at
http://www.trottyzone.com/contact-us/

-------------------------------------------------------------------------------
-------------------------------------------------------------------------------

- Text/ Html Swapper
-- By Ephrain Marchan http://www.trottyzone.com
-- Licensed under GPLv2

-------------------------------------------------------------------------------
*/

if ( ! defined( 'ABSPATH' ) ) die();

register_activation_hook( __FILE__, 'wccs_install' );
load_plugin_textdomain('woocommerce-checkout-manager-pro', false, dirname(plugin_basename(__FILE__)) . '/languages/');
include(plugin_dir_path( __FILE__ ).'update.php');
function wccs_install() {

$options = get_option( 'wccs_settings' );

update_option('wccmwrgp456vcb', ''.get_option( 'woocommerce_registration_generate_password').'');
update_option('wccmwrgu456vcb', ''.get_option( 'woocommerce_registration_generate_username').'');
update_option('wccmwesalfc456vcb', ''.get_option( 'woocommerce_enable_signup_and_login_from_checkout').'');

$options['checkness']['wooccm_notification_email'] = get_option('admin_email');
$options['checkness']['rq_company'] = '1';
$options['checkness']['wccs_rqo_12'] = '1';
$options['check']['rq_company1'] = '1';

if ( empty($options['checkness']['position3']) && empty($options['checkness']['position2']) && empty($options['checkness']['position1']) && empty($options['checkness']['beforebilling']) && empty($options['checkness']['beforeshipping']) ) {
$options['checkness']['position3'] = '1';
}

if ( empty($options['checkness']['payment_method_d']) ) {
$options['checkness']['payment_method_d'] = 'Payment Method';
}

if ( empty($options['checkness']['payment_method_t']) ) {
$options['checkness']['payment_method_t'] = '1';
}

if ( empty($options['checkness']['shipping_method_d']) ) {
$options['checkness']['shipping_method_d'] = 'Shipping Method';
}

if ( empty($options['checkness']['shipping_method_t']) ) {
$options['checkness']['shipping_method_t'] = '1';
}

update_option( 'wccs_settings', $options );

}
if ( is_admin() ){ 
add_action( 'admin_menu', 'wccs_admin_menu' );
add_action('admin_menu', 'wccm_admin_menu'); 
add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'wccs_admin_plugin_actions', -10);
add_action( 'admin_init', 'wccs_register_setting' );
} 
add_action( 'wp_enqueue_scripts', 'jquery_wccs_emit' );
function jquery_wccs_emit() {
global $woocommerce;
if ( is_checkout() ) {
// hook to get option values and dynamically render css to support the tab classes
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

// http://fgelinas.com/code/timepicker/
wp_enqueue_script('jquery-ui-timepicker', plugins_url('woocommerce-checkout-manager-pro/add-on/jquery.ui.timepicker.js') );
wp_enqueue_style('jquery-ui-timepicker', plugins_url('woocommerce-checkout-manager-pro/add-on/jquery.ui.timepicker.css') );
wp_enqueue_style('jquery-ui-timepicker-min', plugins_url('woocommerce-checkout-manager-pro/add-on/include/ui-1.10.0/ui-lightness/jquery-ui-1.10.0.custom.min.css') );
// load the style and script for farbtastic
wp_enqueue_style( 'farbtastic' );
wp_enqueue_script( 'farbtastic', site_url('/wp-admin/js/farbtastic.js') );
}
}

// add dashboard icon
function add_menu_icons_styles(){
?>
<style>
#adminmenu #toplevel_page_woocommerce-checkout-manager-pro-woocommerce-checkout-manager-pro div.wp-menu-image:before {
content: '\f338';
}
</style>
<?php
}
add_action( 'admin_head', 'add_menu_icons_styles' );
include(plugin_dir_path( __FILE__ ).'main_upload.php');
function wccs_admin_menu() {
add_menu_page( __('WooCCM', 'woocommerce-checkout-manager-pro'), __('WooCCM', 'woocommerce-checkout-manager-pro'), 'manage_options', __FILE__ , 'wccs__options_page', '', 58);
}
function wccm_admin_menu() { 
add_submenu_page(__FILE__, 'License', 'License', 'manage_options', 'License_check_slug', 'pg_eptxml'); 
}
function wccs_register_setting() {
register_setting( 'wccs_options', 'wccs_settings', 'wccs_options_validate' );
} if(woocmmatl()){ function wccs__options_page() {
if (!current_user_can('manage_options') ) { wp_die( __('You do not have sufficient permissions to access this page.') ); }
$upload_dir = wp_upload_dir();
$hidden_field_name = 'mccs_submit_hidden';
$hidden_wccs_reset = "my_new_field_reset";

if( isset($_POST[ $hidden_wccs_reset ]) && $_POST[ $hidden_wccs_reset ] == 'Y' ) {
delete_option('wccs_settings');

$defaults = array(
'buttons' => array( array ( 'label' => 'My New Label', 'cow' => 'myfield1' ) ),
'check' => array ( 'rq_company1' => true ),
'checkness' => array(
'position2' => true ,
'wooccm_notification_email' => ''.get_option('admin_email').'',
'wccs_rqo_12' => true,
'payment_method_t' => true,
'shipping_method_t' => true,
'payment_method_d' => __('Payment Method','woocommerce-checkout-manager-pro'),
'shipping_method_d' => __('Shipping Method','woocommerce-checkout-manager-pro'),
'rq_company' => true ),

'change' => array(
'add_info' => __('Additional Information', 'woocommerce-checkout-manager-pro')

)
);

add_option( 'wccs_settings' , $defaults );
}


// read options values
$options = get_option( 'wccs_settings' );

settings_errors();


// Now display the settings editing screen

echo '<div class="wrap"></div>';

// icon for settings
echo '<div id="icon-themes" class="icon32"><br></div>';

// header
echo '<div style="clear:both;"><h2 class="nav-tab-wrapper add_tip_wrap">
<span class="wooccm_name_heading">' . __( 'WooCommerce Checkout Manager Pro', 'woocommerce-checkout-manager-pro' ) . '</span>
<a class="nav-tab nav-tab-active" href="#">Pro Version</a>
<a class="nav-tab" href="http://www.trottyzone.com/woocommerce-checkout-manager-pro-documentation/">Documentation</a>
<a class="nav-tab" href="http://www.trottyzone.com/store/">More Plugins</a>
</h2></div>';

// settings form

wp_enqueue_script( 'script_wccs', plugins_url( 'script_wccs.js', __FILE__ ), array( 'jquery' ), '1.2' );
if(!wp_script_is('jquery-ui-sortable', 'queue')){
wp_enqueue_script('jquery-ui-sortable');
}
?>

<div class="updated wccs_notice">
<p><?php _e('This is what PRO looks like! Should you have any questions or concerns find us on'); ?> <a href="http://www.trottyzone.com/forums/forum/wordpress-plugins/"><?php _e('Forum.'); ?></a></p>
</div>
<style type="text/css">
.updated.wccs_notice {
float: right;
}
th.hide_stuff_color.daoo, th.add_amount, th.apply_tick {
background: #38B3E4;
cursor: pointer;
}
.hide_stuff_color.daoo.current_opener, th.add_amount.current_opener, th.apply_tick.current_opener {
background: #FF2876;
color: #fff;
}
th.hide_stuff_days {
text-align:center;
}
.wccs_submit_button {
float: left;
margin-bottom: 20px !important;
margin-top: 10px !important;
margin-left: 15px !important;
}
#wccs_reset_submit {
float: left;
margin-bottom: 20px;
width: inherit;
margin-top: 10px;
}
.wooccm_name_heading {
margin-right: 20px;
}
.or_shower {
float: left;
position: absolute;
background: #fff;
padding: 2px;
margin-top: -3px;
margin-left: -33%;
}
</style>

<form name="reset_form" method="post" action="">
<input type="hidden" name="<?php echo $hidden_wccs_reset; ?>" value="Y">
<input type="submit" name="submit" id="wccs_reset_submit" class="button-secondary" value="Reset">
</form>

<form name="form" method="post" action="options.php" id="frm1">

<?php
settings_fields( 'wccs_options' );
$options = get_option( 'wccs_settings' );
?>


<input type="submit" name="Submit" class="wccs_submit_button button-primary" value="<?php _e( 'Save Changes', 'woocommerce-checkout-manager-pro' ); ?>" />

<?php do_action('run_color_innerpicker'); ?>
<!--
// -------------------------------------------------------------------------------------------------------------
// *************************************************************************************************************
// ************************** Cart Page ******************************
// *************************************************************************************************************
// -------------------------------------------------------------------------------------------------------------
-->

<?php require(plugin_dir_path( __FILE__ ).'/wccm_file_upload.php'); ?>

<!-- -------------------------------------------------------------------------------------------------------------
// *************************************************************************************************************
// ************************** CHECKOUT ******************************
// *************************************************************************************************************
// -------------------------------------------------------------------------------------------------------------
-->

<div class="show_hide"><a href="#">
<table class="widefat" border="1" style="margin-top:20px;">
<thead>
<tr>
<th>= <?php _e('Billing Section', 'woocommerce-checkout-manager-pro');  ?> =</th>
<th><div style="font-size: 30px;float:right;padding-right:3px;"><span class="toggle_shower">&equiv;</span></div></th>
</tr>
</thead>
</table>
</a></div>


<div class="slidingDiv" style="display:none;" id="floatright_set">
<table class="widefat" border="1" >

<thead>
<tr>
<th><?php _e('Field Name', 'woocommerce-checkout-manager-pro');  ?></th>
<th><?php _e('Remove Field Entirely', 'woocommerce-checkout-manager-pro');  ?></th>
<th><?php _e('Remove Required Attribute', 'woocommerce-checkout-manager-pro');  ?></th>
<th class="wccs_replace"><?php _e('Replace Label Name', 'woocommerce-checkout-manager-pro');  ?></th>
<th class="wccs_replace"><?php _e('Replace Placeholder Name', 'woocommerce-checkout-manager-pro');  ?></th>
</tr>
</thead>


<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><strong><?php _e('Select All Check boxes in this Column', 'woocommerce-checkout-manager-pro');  ?></strong></td>
<td><input name="wccs_settings[checkness][select_all_rm]" type="checkbox" id="select_all_rm" value="1" <?php if ( !empty($options['checkness']['select_all_rm'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[checkness][select_all_rq]" type="checkbox" id="select_all_rq" value="1" <?php if (  !empty($options['checkness']['select_all_rq'])) echo "checked='checked'"; ?> /></td>
<td></td>
<td></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('First Name', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[checkness][wccs_opt_1]" type="checkbox" class="rm" value="1"
<?php if (  !empty ($options['checkness']['wccs_opt_1']) ) echo 'checked="checked"'; ?>   /></td>

<td><input name="wccs_settings[checkness][wccs_rq_1]" type="checkbox" class="rq" value="1" <?php if (  !empty ($options['checkness']['wccs_rq_1'])) echo "checked='checked'"; ?> /></td>

<td><input type="text" name="wccs_settings[replace][label]"
value="<?php echo esc_attr( $options['replace']['label'] ); ?>" /></td>

<td><input type="text" name="wccs_settings[replace][placeholder]"
value="<?php echo esc_attr( $options['replace']['placeholder'] ); ?>" /></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Last Name', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[checkness][wccs_opt_2]" type="checkbox" class="rm" value="1" <?php if (  !empty ($options['checkness']['wccs_opt_2'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[checkness][wccs_rq_2]" type="checkbox" class="rq" value="1" <?php if (  !empty ($options['checkness']['wccs_rq_2'])) echo "checked='checked'"; ?> /></td>

<td><input type="text" name="wccs_settings[replace][label1]"
value="<?php echo esc_attr( $options['replace']['label1'] ); ?>" /></td>

<td><input type="text" name="wccs_settings[replace][placeholder1]"
value="<?php echo esc_attr( $options['replace']['placeholder1'] ); ?>" /></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Phone', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[checkness][wccs_opt_10]" type="checkbox" class="rm" value="1" <?php if (  !empty ($options['checkness']['wccs_opt_10'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[checkness][wccs_rq_10]" type="checkbox" class="rq" value="1" <?php if (  !empty ($options['checkness']['wccs_rq_10'])) echo "checked='checked'"; ?> /></td>

<td><input type="text" name="wccs_settings[replace][label3]"
value="<?php echo esc_attr( $options['replace']['label3'] ); ?>" /></td>

<td><input type="text" name="wccs_settings[replace][placeholder3]"
value="<?php echo esc_attr( $options['replace']['placeholder3'] ); ?>" /></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Email', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[checkness][wccs_opt_11]" type="checkbox" class="rm" value="1" <?php if (  !empty ($options['checkness']['wccs_opt_11'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[checkness][wccs_rq_11]" type="checkbox" class="rq" value="1" <?php if (  !empty ($options['checkness']['wccs_rq_11'])) echo "checked='checked'"; ?> /></td>

<td><input type="text" name="wccs_settings[replace][label4]"
value="<?php echo esc_attr( $options['replace']['label4'] ); ?>" /></td>

<td><input type="text" name="wccs_settings[replace][placeholder4]"
value="<?php echo esc_attr( $options['replace']['placeholder4'] ); ?>" /></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Company', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[checkness][wccs_opt_3]" type="checkbox" class="rm" value="1" <?php if (  !empty ($options['checkness']['wccs_opt_3'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[checkness][rq_company]" type="checkbox" class="rq" value="1" <?php if (  !empty ($options['checkness']['rq_company'])) echo "checked='checked'"; ?> /></td>

<td><input type="text" name="wccs_settings[replace][label5]"
value="<?php echo esc_attr( $options['replace']['label5'] ); ?>" /></td>

<td><input type="text" name="wccs_settings[replace][placeholder5]"
value="<?php echo esc_attr( $options['replace']['placeholder5'] ); ?>" /></td>
</tr>
</tbody>



<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Order Notes', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[checkness][wccs_opt_12]" type="checkbox" class="rm" value="1" <?php if (  !empty ($options['checkness']['wccs_opt_12'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[checkness][wccs_rqo_12]" type="checkbox" class="rq" value="1" <?php if (  !empty ($options['checkness']['wccs_rqo_12'])) echo "checked='checked'"; ?> /></td>

<td><input type="text" name="wccs_settings[replace][label11]"
value="<?php echo esc_attr( $options['replace']['label11'] ); ?>" /></td>

<td><input type="text" name="wccs_settings[replace][placeholder11]"
value="<?php echo esc_attr( $options['replace']['placeholder11'] ); ?>" /></td>
</tr>
</tbody>


<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Country', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[checkness][wccs_opt_8]" type="checkbox" class="rm" value="1" <?php if (  !empty ($options['checkness']['wccs_opt_8'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[checkness][wccs_rq_8]" type="checkbox" class="rq" value="1" <?php if (  !empty ($options['checkness']['wccs_rq_8'])) echo "checked='checked'"; ?> /></td>
<td></td>
<td></td>
</tr>
</tbody>


<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Address 1', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[checkness][wccs_opt_4]" type="checkbox" class="rm" value="1" <?php if (  !empty ($options['checkness']['wccs_opt_4'])) echo "checked='checked'"; ?></td>
<td><input name="wccs_settings[checkness][wccs_rq_4]" type="checkbox" class="rq" value="1" <?php if (  !empty ($options['checkness']['wccs_rq_4'])) echo "checked='checked'"; ?></td>
<td></td>
<td></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Address 2', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[checkness][wccs_opt_5]" type="checkbox" class="rm" value="1" <?php if (  !empty ($options['checkness']['wccs_opt_5'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[checkness][wccs_rq_5]" type="checkbox" class="rq" value="1" <?php if (  !empty ($options['checkness']['wccs_rq_5'])) echo "checked='checked'"; ?> /></td>
<td></td>
<td></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('City', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[checkness][wccs_opt_6]" type="checkbox" class="rm" value="1" <?php if (  !empty ($options['checkness']['wccs_opt_6'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[checkness][wccs_rq_6]" type="checkbox" class="rq" value="1" <?php if (  !empty ($options['checkness']['wccs_rq_6'])) echo "checked='checked'"; ?> /></td>
<td></td>
<td></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Postal Code', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[checkness][wccs_opt_7]" type="checkbox" class="rm" value="1" <?php if (  !empty ($options['checkness']['wccs_opt_7'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[checkness][wccs_rq_7]" type="checkbox" class="rq" value="1" <?php if (  !empty ($options['checkness']['wccs_rq_7'])) echo "checked='checked'"; ?> /></td>
<td></td>
<td></td>
</tr>
</tbody>



<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('State', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[checkness][wccs_opt_9]" type="checkbox" class="rm" value="1" <?php if (  !empty ($options['checkness']['wccs_opt_9'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[checkness][wccs_rq_9]" type="checkbox" class="rq" value="1" <?php if (  !empty ($options['checkness']['wccs_rq_9'])) echo "checked='checked'"; ?> /></td>
<td></td>
<td></td>
</tr>
</tbody>

</table>
</div>



<div class="show_hide"><a href="#">
<table class="widefat" border="1" style="margin-top:20px;">
<thead>
<tr>
<th>= <?php _e('Shipping Section', 'woocommerce-checkout-manager-pro');  ?> =</th>
<th><div style="font-size: 30px;float:right;padding-right:3px;"><span class="toggle_shower">&equiv;</span></div></th>
</tr>
</thead>
</table>
</a></div>



<div class="slidingDiv" style="display:none;" id="floatright_set">
<table class="widefat" border="1" >

<thead>
<tr>
<th><?php _e('Field Name', 'woocommerce-checkout-manager-pro');  ?></th>
<th><?php _e('Remove Field Entirely', 'woocommerce-checkout-manager-pro');  ?></th>
<th><?php _e('Remove Required Attribute', 'woocommerce-checkout-manager-pro');  ?></th>
<th class="wccs_replace"><?php _e('Replace Label Name', 'woocommerce-checkout-manager-pro');  ?></th>
<th class="wccs_replace"><?php _e('Replace Placeholder Name', 'woocommerce-checkout-manager-pro');  ?></th>
</tr>
</thead>


<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><strong><?php _e('Select All Check boxes in this Column', 'woocommerce-checkout-manager-pro');  ?></strong></td>
<td><input name="wccs_settings[check][select_all_rm_s]" type="checkbox" id="select_all_rm_s" value="1" <?php if (  !empty ($options['check']['select_all_rm_s'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[check][select_all_rq_s]" type="checkbox" id="select_all_rq_s" value="1" <?php if (  !empty ($options['check']['select_all_rq_s'])) echo "checked='checked'"; ?> /></td>
<td></td>
<td></td>
</tr>
</tbody>


<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('First Name', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[check][wccs_opt_1_s]" type="checkbox" class="rm_s" value="1"
<?php if (  !empty ($options['check']['wccs_opt_1_s']) ) echo 'checked="checked"'; ?>   /></td>

<td><input name="wccs_settings[check][wccs_rq_1_s]" type="checkbox" class="rq_s" value="1" <?php if (  !empty ($options['check']['wccs_rq_1_s'])) echo "checked='checked'"; ?> /></td>

<td><input type="text" name="wccs_settings[replace][label_s]"
value="<?php echo esc_attr( $options['replace']['label_s'] ); ?>" /></td>

<td><input type="text" name="wccs_settings[replace][placeholder_s]"
value="<?php echo esc_attr( $options['replace']['placeholder_s'] ); ?>" /></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Last Name', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[check][wccs_opt_2_s]" type="checkbox" class="rm_s" value="1" <?php if (  !empty ($options['check']['wccs_opt_2_s'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[check][wccs_rq_2_s]" type="checkbox" class="rq_s" value="1" <?php if (  !empty ($options['check']['wccs_rq_2_s'])) echo "checked='checked'"; ?> /></td>

<td><input type="text" name="wccs_settings[replace][label_s1]"
value="<?php echo esc_attr( $options['replace']['label_s1'] ); ?>" /></td>

<td><input type="text" name="wccs_settings[replace][placeholder_s1]"
value="<?php echo esc_attr( $options['replace']['placeholder_s1'] ); ?>" /></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Company', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[check][rm_company1]" type="checkbox" class="rm_s" value="1" <?php if (  !empty ($options['check']['rm_company1'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[check][rq_company1]" type="checkbox" class="rq_s" value="1" <?php if (  !empty ($options['check']['rq_company1'])) echo "checked='checked'"; ?> /></td>

<td><input type="text" name="wccs_settings[replace][label_s2]"  
          value="<?php echo esc_attr( $options['replace']['label_s2'] ); ?>" /></td>


<td><input type="text" name="wccs_settings[replace][placeholder_s2]"
value="<?php echo esc_attr( $options['replace']['placeholder_s2'] ); ?>" /></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Address 1', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[check][wccs_opt_4_s]" type="checkbox" class="rm_s" value="1" <?php if (  !empty ($options['check']['wccs_opt_4_s'])) echo "checked='checked'"; ?></td>
<td><input name="wccs_settings[check][wccs_rq_4_s]" type="checkbox" class="rq_s" value="1" <?php if (  !empty ($options['check']['wccs_rq_4_s'])) echo "checked='checked'"; ?> /></td>

<td></td>
<td></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Address 2', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[check][wccs_opt_5_s]" type="checkbox" class="rm_s" value="1" <?php if (  !empty ($options['check']['wccs_opt_5_s'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[check][wccs_rq_5_s]" type="checkbox" class="rq_s" value="1" <?php if (  !empty ($options['check']['wccs_rq_5_s'])) echo "checked='checked'"; ?> /></td>

<td></td>
<td></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('City', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[check][wccs_opt_6_s]" type="checkbox" class="rm_s" value="1" <?php if (  !empty ($options['check']['wccs_opt_6_s'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[check][wccs_rq_6_s]" type="checkbox" class="rq_s" value="1" <?php if (  !empty ($options['check']['wccs_rq_6_s'])) echo "checked='checked'"; ?> /></td>

<td></td>
<td></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Postal Code', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[check][wccs_opt_7_s]" type="checkbox" class="rm_s" value="1" <?php if (  !empty ($options['check']['wccs_opt_7_s'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[check][wccs_rq_7_s]" type="checkbox" class="rq_s" value="1" <?php if (  !empty ($options['check']['wccs_rq_7_s'])) echo "checked='checked'"; ?> /></td>

<td></td>
<td></td>
</tr>
</tbody>


<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('State', 'woocommerce-checkout-manager-pro');  ?></td>
<td><input name="wccs_settings[check][wccs_opt_9_s]" type="checkbox" class="rm_s" value="1" <?php if (  !empty ($options['check']['wccs_opt_9_s'])) echo "checked='checked'"; ?> /></td>
<td><input name="wccs_settings[check][wccs_rq_9_s]" type="checkbox" class="rq_s" value="1" <?php if (  !empty ($options['check']['wccs_rq_9_s'])) echo "checked='checked'"; ?> /></td>

<td></td>
<td></td>
</tr>
</tbody>

</table>
</div>


<div class="show_hide"><a href="#">
<table class="widefat" border="1" style="margin-top:20px;">
<thead>
<tr>
<th>= <?php _e('Address Fields and Custom CSS', 'woocommerce-checkout-manager-pro');  ?> =</th>
<th><div style="font-size: 30px;float:right;padding-right:3px;"><span class="toggle_shower">&equiv;</span></div></th>
</tr>
</thead>
</table>
</a></div>

<div class="slidingDiv" style="display:none;">
<table class="widefat" border="1">

<thead>
<tr>
<th><?php _e('Field Name', 'woocommerce-checkout-manager-pro');  ?></th>
<th><?php _e('Product ID - example 2222,1111,4444', 'woocommerce-checkout-manager-pro');  ?></th>
</tr>
</thead>


<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Disable Address fields: ', 'woocommerce-checkout-manager-pro');  ?></td>
<td style="width: 80%;"><input type="text" name="wccs_settings[option][productssave]" style="width: 100%;" value="<?php echo esc_attr($options['option']['productssave']); ?>" /></td>
</tr>
</tbody>

<tbody>
<tr>
<td></td>
<td style="width: 80%;"><?php _e('To get product number, goto the listing of WooCoommerce Products then hover over each product and you will see ID. Example', 'woocommerce-checkout-manager-pro'); ?> "ID: 3651"</td>
</tr>
</tbody>

<tbody>
<tr>
<td>-----------------</td>
<td style="width: 80%;">-----------------</td>
</tr>
</tbody>

<tbody>
<tr>
<td>
<strong><?php _e('Custom CSS','woocommerce-checkout-manager-pro'); ?></strong><br >
<?php _e('What is CSS?','woocommerce-checkout-manager-pro'); ?><br><br>
<?php _e('CSS language stands for Cascading Style Sheets which is used to style html content. You can change the fonts size, colours, margins of content, which lines to show or input, adjust height, width, background images etc.','woocommerce-checkout-manager-pro'); ?>
<br><br>
<?php _e('Get help in our Support', 'woocommerce-checkout-manager-pro');  ?>
 <a href="http://www.trottyzone.com/forums/forum/wordpress-plugins/"><?php _e('Forum', 'woocommerce-checkout-manager-pro');  ?></a>
</td>
<td style="width: 80%;"><textarea type="text" name="wccs_settings[option][custom_css_w]" style="height:200px;width: 100%;"><?php echo esc_attr($options['option']['custom_css_w']); ?></textarea></td>
</tr>
</tbody>

</table>
</div>


<div class="show_hide"><a href="#">
<table class="widefat" border="1" style="margin-top:20px;">
<thead>
<tr>
<th>= <?php _e('Add Checkout Notice', 'woocommerce-checkout-manager-pro');  ?> =</th>
<th><div style="font-size: 30px;float:right;padding-right:3px;"><span class="toggle_shower">&equiv;</span></div></th>
</tr>
</thead>
</table>
</a></div>


<div class="slidingDiv" style="display:none;">
<table class="widefat" border="1" >

<thead>
<tr>

<th><?php _e('Position for Notice', 'woocommerce-checkout-manager-pro');  ?></th>
<th>
<input style="float:left;" name="wccs_settings[notice][checkbox1]" type="checkbox" value="true" <?php if (  !empty ($options['notice']['checkbox1'])) echo "checked='checked'"; ?> /></th>

<th><?php _e('Before Customer Address Fields', 'woocommerce-checkout-manager-pro');  ?></th>

<th>
<input style="float:left;" name="wccs_settings[notice][checkbox2]" type="checkbox" value="true" <?php if (  !empty ($options['notice']['checkbox2'])) echo "checked='checked'"; ?> /></th>

<th><?php _e('Before Order Summary', 'woocommerce-checkout-manager-pro');  ?></th>

</tr>

</thead>

</table>

<table class="widefat" border="1" >

<thead>
<tr>

<th width="25%" ><?php _e('Notice Text 1 - ', 'woocommerce-checkout-manager-pro');  ?><br><?php _e('You can use class', 'woocommerce-checkout-manager-pro');  ?> "woocommerce-info" <?php _e('for the same design as WooCommerce Coupon.', 'woocommerce-checkout-manager-pro');  ?></th>


<th>
<textarea style="width:100%;height:150px;" name="wccs_settings[notice][text1]" type="textarea"><?php echo esc_attr( $options['notice']['text1'] ); ?></textarea></th>

</tr>
</thead>


</table>


<table class="widefat" border="1" style="margin-top:20px;" >

<thead>
<tr>

<th><?php _e('Position for Notice', 'woocommerce-checkout-manager-pro');  ?></th>
<th>
<input style="float:left;" name="wccs_settings[notice][checkbox3]" type="checkbox" value="true" <?php if (  !empty ($options['notice']['checkbox3'])) echo "checked='checked'"; ?> /></th>

<th><?php _e('Before Customer Address Fields', 'woocommerce-checkout-manager-pro');  ?></th>

<th>
<input style="float:left;" name="wccs_settings[notice][checkbox4]" type="checkbox" value="true" <?php if (  !empty ($options['notice']['checkbox4'])) echo "checked='checked'"; ?> /></th>

<th><?php _e('Before Order Summary', 'woocommerce-checkout-manager-pro');  ?></th>

</tr>

</thead>

</table>

<table class="widefat" border="1" >

<thead>
<tr>

<th width="25%"><?php _e('Notice Text 2 - ', 'woocommerce-checkout-manager-pro');  ?><br><?php _e('You can use class', 'woocommerce-checkout-manager-pro');  ?> "woocommerce-info" <?php _e('for the same design as WooCommerce Coupon.', 'woocommerce-checkout-manager-pro');  ?></th>


<th>
<textarea style="width:100%;height:150px;" name="wccs_settings[notice][text2]" type="textarea"><?php echo esc_attr( $options['notice']['text2'] ); ?></textarea></th>

</tr>
</thead>


</table>
</div>


<div class="show_hide"><a href="#">
<table class="widefat" border="1" style="margin-top:20px;">
<thead>
<tr>
<th>= <?php _e('New Fields Position & Switches', 'woocommerce-checkout-manager-pro');  ?> =</th>
<th><div style="font-size: 30px;float:right;padding-right:3px;"><span class="toggle_shower">&equiv;</span></div></th>
</tr>
</thead>
</table>
</a></div>


<div class="slidingDiv" style="display:none;">
<table class="widefat" border="1" >

<thead>
<tr>
<th style="width: 35%;"><?php _e('Name', 'woocommerce-checkout-manager-pro');  ?></th>
<th style="text-align: center;"><?php _e('Enable/ Disable', 'woocommerce-checkout-manager-pro');  ?></th>
</tr>
</thead>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Auto Create User account', 'woocommerce-checkout-manager-pro');  ?></td>
<td style="width: 70%;text-align:center;"><input name="wccs_settings[checkness][auto_create_wccm_account]" type="checkbox" value="true" <?php if (  !empty ($options['checkness']['auto_create_wccm_account'])) echo "checked='checked'"; ?> /></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Retain Fields Information', 'woocommerce-checkout-manager-pro');  ?></td>
<td style="width: 70%;text-align:center;"><input name="wccs_settings[checkness][retainval]" type="checkbox" value="true" <?php if (  !empty ($options['checkness']['retainval'])) echo "checked='checked'"; ?> /></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Before Shipping Form', 'woocommerce-checkout-manager-pro');  ?></td>
<td style="width: 70%;text-align:center;"><input name="wccs_settings[checkness][beforeshipping]" type="checkbox" value="true" <?php if (  !empty ($options['checkness']['beforeshipping'])) echo "checked='checked'"; ?> /></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('After Shipping Form', 'woocommerce-checkout-manager-pro');  ?></td>
<td style="width: 70%;text-align:center;"><input name="wccs_settings[checkness][position1]" type="checkbox" value="true" <?php if (  !empty ($options['checkness']['position1'])) echo "checked='checked'"; ?> /></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('Before Billing Form', 'woocommerce-checkout-manager-pro');  ?></td>
<td style="width: 70%;text-align:center;"><input name="wccs_settings[checkness][beforebilling]" type="checkbox" value="true" <?php if (  !empty ($options['checkness']['beforebilling'])) echo "checked='checked'"; ?> /></td>
</tr>
</tbody>


<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('After Billing Form', 'woocommerce-checkout-manager-pro');  ?></td>
<td style="width: 70%;text-align:center;"><input name="wccs_settings[checkness][position2]" type="checkbox" value="true" <?php if (  !empty ($options['checkness']['position2'])) echo "checked='checked'"; ?> /></td>
</tr>
</tbody>


<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><?php _e('After Order Notes', 'woocommerce-checkout-manager-pro');  ?></td>
<td style="width: 70%;text-align:center;"><input name="wccs_settings[checkness][position3]" type="checkbox" value="true" <?php if (  !empty ($options['checkness']['position3'])) echo "checked='checked'"; ?> /></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><input style="width: 50%;" name="wccs_settings[checkness][payment_method_d]" type="text" value="<?php echo $options['checkness']['payment_method_d']; ?>" /></br><?php _e('Change title for payment method used by customer.', 'woocommerce-checkout-manager-pro');  ?></td>
<td style="width: 70%;text-align:center;"><input name="wccs_settings[checkness][payment_method_t]" type="checkbox" value="true" <?php if (  !empty ($options['checkness']['payment_method_t'])) echo "checked='checked'"; ?> /></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><input style="width: 50%;" name="wccs_settings[checkness][shipping_method_d]" type="text" value="<?php echo $options['checkness']['shipping_method_d']; ?>" /></br><?php _e('Change title for shipping method used by customer.', 'woocommerce-checkout-manager-pro');  ?></td>
<td style="width: 70%;text-align:center;"><input name="wccs_settings[checkness][shipping_method_t]" type="checkbox" value="true" <?php if (  !empty ($options['checkness']['shipping_method_t'])) echo "checked='checked'"; ?> /></td>
</tr>
</tbody>

<tbody>
<tr>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<td><input style="width: 50%;" name="wccs_settings[checkness][per_state]" type="text" value="<?php echo $options['checkness']['per_state']; ?>" /></br><?php _e('Enter default sate code for checkout.', 'woocommerce-checkout-manager-pro');  ?></td>
<td style="width: 70%;text-align:center;"><input name="wccs_settings[checkness][per_state_check]" type="checkbox" value="true" <?php if (  !empty ($options['checkness']['per_state_check'])) echo "checked='checked'"; ?> /></td>
</tr>
</tbody>

</table>
</div>



<div class="show_hide2">
<a href="#">
<table class="widefat" border="1" style="margin-top:20px;">
<thead>
<tr>
<th>= <?php _e('Add New Field Section with Text/ Html Swapper', 'woocommerce-checkout-manager-pro');  ?> =</th>
<th><div style="font-size: 30px;float:right;padding-right:3px;"><span class="toggle_shower">&equiv;</span></div></th>
</tr>
</thead>
</table>
</a>
</div>



<div class="slidingDiv2" style="display:none;">
<table class="widefat" border="1" >

<thead>
<tr>

<th style="width:53%;"><?php echo ''.__('Enable Title', 'woocommerce-checkout-manager-pro').' - <input type="text" name="wccs_settings[change][add_info]" style="width: 80%;padding-left: 1%;" value="'.esc_attr($options['change']['add_info']).'" />';  ?></th>

<th style="width:5%;">
<input style="float:left;" name="wccs_settings[checkness][checkbox12]" type="checkbox" value="true" <?php if (  !empty ($options['checkness']['checkbox12'])) echo "checked='checked'"; ?> /></th>

<th style="width:12%;"><?php _e('Checkout Page', 'woocommerce-checkout-manager-pro');  ?></th>

<th style="width:5%;">
<input style="float:left;" name="wccs_settings[checkness][checkbox1]" type="checkbox" value="true" <?php if (  !empty ($options['checkness']['checkbox1'])) echo "checked='checked'"; ?> /></th>

<th><?php _e('Checkout Details and Email Receipt', 'woocommerce-checkout-manager-pro');  ?></th>

</tr>

</thead>

</table>

<style type="text/css">
.wccs-clone {
display:none;
}
#floatright_set .widefat input {
float:right;
}
.wccs_replace {
width: 20%;
}
.wccs-order {
cursor:move;
}
.wccs-table > tbody > tr > td {
background: #fff;
border-right: 1px solid #ededed;
border-bottom: 1px solid #ededed;
padding: 8px;
position: relative;
}

.wccs-table > tbody > tr:last-child td { border-bottom: 0 none; }
.wccs-table > tbody > tr td:last-child { border-right: 0 none; }
.wccs-table > thead > tr > th { border-right: 1px solid #e1e1e1; }
.wccs-table > thead > tr > th:last-child { border-right: 0 none; }

.wccs-table tr td.wccs-order,
.wccs-table tr th.wccs-order {
width: 16px;
text-align: center;
vertical-align: middle;
color: #aaa;
text-shadow: #fff 0 1px 0;
}

.wccs-table .wccs-remove {
width: 16px;
vertical-align: middle;
}
.wccs-table input[type="text"] {
width: 100%;
}

.wccs-table-footer {
position: relative;
overflow: hidden;
margin-top: 10px;
padding: 8px 0;
}
.toggle_shower {
color: #278ab7;
font-weight: 700 !important;
}
.current_opener {
color: red;
font-weight: 700 !important;
}
.spongagge {
float: left;
position: relative;
-moz-transform: rotate(270deg);  /* FF3.5+ */
-o-transform: rotate(270deg);  /* Opera 10.5 */
-webkit-transform: rotate(270deg);  /* Saf3.1+, Chrome */
filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=3);  /* IE6,IE7 */
-ms-filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3); /* IE8 */
margin-top: -25%;
}
</style>

<table class="widefat wccs-table" border="1">

<thead>
<tr>
<th style="width:1%;" class="wccs-order" title="<?php esc_attr_e( 'Change order' , 'woocommerce-checkout-manager-pro' ); ?>"></th>

<th class="more_toggler1c" style="display:none;width: 17%;"><?php _e('Hide Field from Product' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="more_toggler1c" style="display:none;width: 17%;"><?php _e('Show Field for Product' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="more_toggler1c" style="display:none;width: 20%;"><?php _e('Hide Field from Category' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="more_toggler1c" style="display:none;width: 20%;"><?php _e('Show Field for Category' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_color hide_stuff_days" style="display:none;width: 16%;"><?php _e('Date Format' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_color hide_stuff_days" style="display:none;width: 5%;"><?php _e('Days before' , 'woocommerce-checkout-manager-pro' ); ?></th> 

<th class="hide_stuff_color hide_stuff_days" style="display:none;width: 5%;"><?php _e('Days After' , 'woocommerce-checkout-manager-pro' ); ?></th> 

<th class="hide_stuff_color daoo" style="display:none;width: 7%;"><?php _e('Days Enabler' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_days" style="display:none;width: 7%;"><?php _e('Sundays' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_days" style="display:none;width: 7%;"><?php _e('Mondays' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_days" style="display:none;width: 7%;"><?php _e('Tuesdays' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_days" style="display:none;width: 7%;"><?php _e('Wednesdays' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_days" style="display:none;width: 7%;"><?php _e('Thursdays' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_days" style="display:none;width: 7%;"><?php _e('Fridays' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_days" style="display:none;width: 7%;"><?php _e('Satudays' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_color separator hide_stuff_days" style="display:none;" width="3%"><?php _e('Date' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_color hide_stuff_days" style="display:none;text-align:center;" width="5%"><?php _e('YY' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_color hide_stuff_days" style="display:none;" width="4%"><?php _e('MM' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_color hide_stuff_days" style="display:none;" width="4%"><?php _e('DD' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_color separator hide_stuff_days" style="display:none;" width="3%"><?php _e('Date' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_color hide_stuff_days" style="display:none;text-align:center;" width="5%"><?php _e('YY' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_color hide_stuff_days" style="display:none;" width="4%"><?php _e('MM' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_color hide_stuff_days" style="display:none;" width="4%"><?php _e('DD' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="more_toggler1 more_toggler1c condition_tick add_amount_field" style="display:none;" width="8%"><?php _e('Required Attribute' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="more_toggler1 more_toggler1c condition_tick add_amount_field" style="display:none;" width="5%"><?php _e('Float Right' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="more_toggler1 more_toggler1c condition_tick add_amount_field" style="display:none;" width="5%"><?php _e('Full Width' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="more_toggler1 more_toggler1c" style="display:none;" width="5%"><?php _e('Remove Tax' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="more_toggler1 more_toggler1c" style="display:none;" width="5%"><?php _e('Deny Receipt' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="more_toggler1 more_toggler1c add_amount" style="display:none;" width="5%"><?php _e('Add Amount' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="add_amount_field" style="display:none;" width="5%"><?php _e('Amount Name' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="add_amount_field" style="display:none;" width="5%"><?php _e('Enter Amount' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="more_toggler1 more_toggler1c apply_tick" style="display:none;" width="9%"><?php _e('Conditional Field On' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="condition_tick" style="display:none;" width="9%"><?php _e('Conditional Parent' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_color hide_stuff_change hide_stuff_op hide_stuff_opcheck more_toggler1"><?php _e('Label' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_color hide_stuff_change hide_stuff_op hide_stuff_opcheck more_toggler1"><?php _e('Placeholder' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th style="display:none; width: 10%;" class="condition_tick"><?php _e('Chosen Value' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th style="display:none;" class="condition_tick" width="16%"><?php _e('Conditional Tie' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th style="display:none;" class="more_toggler1 more_toggler1c condition_tick add_amount_field" width="12%"><?php _e('Default Color' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_change" style="display:none;"><?php _e('Input Name' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_change" style="display:none;"><?php _e('Change Name to' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_opcheck" style="display:none;" ><?php _e('Checked Value' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_opcheck" style="display:none;" ><?php _e('Not Checked Value' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op wccm1" style="display:none;" width="15%"><?php _e('Force Title' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op wccm1" style="display:none;" width="15%"><?php _e('Option 1' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op wccm1" style="display:none;" width="15%"><?php _e('Option 2' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op wccm1" style="display:none;" width="15%"><?php _e('Option 3' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op wccm1" style="display:none;" width="15%"><?php _e('Option 4' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op wccm1" style="display:none;" width="15%"><?php _e('Option 5' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op2" style="display:none;" width="15%"><?php _e('Option 6' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op2" style="display:none;" width="15%"><?php _e('Option 7' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op2" style="display:none;" width="15%"><?php _e('Option 8' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op2" style="display:none;" width="15%"><?php _e('Option 9' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op2" style="display:none;" width="15%"><?php _e('Option 10' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op3" style="display:none;" width="15%"><?php _e('Option 11' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op3" style="display:none;" width="15%"><?php _e('Option 12' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op3" style="display:none;" width="15%"><?php _e('Option 13' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op3" style="display:none;" width="15%"><?php _e('Option 14' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op3" style="display:none;" width="15%"><?php _e('Option 15' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op4" style="display:none;" width="15%"><?php _e('Option 16' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op4" style="display:none;" width="15%"><?php _e('Option 17' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op4" style="display:none;" width="15%"><?php _e('Option 18' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op4" style="display:none;" width="15%"><?php _e('Option 19' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op4" style="display:none;" width="15%"><?php _e('Option 20' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op5" style="display:none;" width="15%"><?php _e('Option 21' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op5" style="display:none;" width="15%"><?php _e('Option 22' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op5" style="display:none;" width="15%"><?php _e('Option 23' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op5" style="display:none;" width="15%"><?php _e('Option 24' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op5" style="display:none;" width="15%"><?php _e('Option 25' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op6" style="display:none;" width="15%"><?php _e('Option 26' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op6" style="display:none;" width="15%"><?php _e('Option 27' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op6" style="display:none;" width="15%"><?php _e('Option 28' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op6" style="display:none;" width="15%"><?php _e('Option 29' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op6" style="display:none;" width="15%"><?php _e('Option 30' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op7" style="display:none;" width="15%"><?php _e('Option 31' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op7" style="display:none;" width="15%"><?php _e('Option 32' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op7" style="display:none;" width="15%"><?php _e('Option 33' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op7" style="display:none;" width="15%"><?php _e('Option 34' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op7" style="display:none;" width="15%"><?php _e('Option 35' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op8" style="display:none;" width="15%"><?php _e('Option 36' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op8" style="display:none;" width="15%"><?php _e('Option 37' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op8" style="display:none;" width="15%"><?php _e('Option 38' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op8" style="display:none;" width="15%"><?php _e('Option 39' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op8" style="display:none;" width="15%"><?php _e('Option 40' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op9" style="display:none;" width="15%"><?php _e('Option 41' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op9" style="display:none;" width="15%"><?php _e('Option 42' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op9" style="display:none;" width="15%"><?php _e('Option 43' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op9" style="display:none;" width="15%"><?php _e('Option 44' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op9" style="display:none;" width="15%"><?php _e('Option 45' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op10" style="display:none;" width="15%"><?php _e('Option 46' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op10" style="display:none;" width="15%"><?php _e('Option 47' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op10" style="display:none;" width="15%"><?php _e('Option 48' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op10" style="display:none;" width="15%"><?php _e('Option 49' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th class="hide_stuff_op10" style="display:none;" width="15%"><?php _e('Option 50' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th style="display:none;cursor:pointer;text-align:center;font-size:30px;" class="toggler_adder hide_stuff_op" width="2%"><span class="toggle_shower">&#43;</span></th>

<th style="cursor:pointer;text-align:center;font-size:30px;" class="hide_stuff_tog hide_stuff_color more_toggler1" width="2%"><span class="toggle_shower">&equiv;</span></th>

<th style="cursor:pointer;text-align:center;font-size:30px;" class="hide_stuff_color hide_stuff_op hide_stuff_togcheck more_toggler1" width="2%"><span class="toggle_shower">&equiv;</span></th>

<th style="cursor:pointer;text-align:center;font-size:30px;" class="hide_stuff_op hide_stuff_change_tog hide_stuff_color more_toggler1" width="2%"><span class="toggle_shower">&equiv;</span></th>

<th style="cursor:pointer;text-align:center;font-size:30px;" class="hide_stuff_color_tog hide_stuff_op more_toggler1" width="2%"><span class="toggle_shower">&equiv;</span></th>

<th style="display:none;cursor:pointer;text-align:center;font-size:30px;" class="more_toggler1 more_toggler1a" width="9%"><span class="toggle_shower">&equiv;</span></th>

<th style="cursor:pointer;text-align:center;font-size:30px;" class="more_toggler hide_stuff_op" width="2%"><span class="toggle_shower">&equiv;</span></th>

<th class="hide_stuff_color hide_stuff_op more_toggler1" style="width:10%;"><?php _e('Choose Type' , 'woocommerce-checkout-manager-pro' ); ?></th>
<th class="hide_stuff_color hide_stuff_change hide_stuff_op more_toggler1c" style="width:5%"><?php _e('Abbreviation' , 'woocommerce-checkout-manager-pro' ); ?></th>

<th width="1%" scope="col" title="<?php esc_attr_e( 'Remove button', 'woocommerce-checkout-manager-pro' ); ?>"><!-- remove --></th>
</tr>
</thead>
<tbody>


<?php
if ( isset ( $options['buttons'] ) ) :

// Loop through all the buttons
for ( $i = 0; $i < count( $options['buttons'] ); $i++ ) :

if ( ! isset( $options['buttons'][$i] ) )
break;

if ( empty($options['buttons'][$i]['colorpickerd'])) {
$options['buttons'][$i]['colorpickerd'] = "#000";
}

?>

<tr valign="top" class="wccs-row">
<td class="wccs-order" title="<?php esc_attr_e( 'Change order', 'woocommerce-checkout-manager-pro' ); ?>"><?php echo $i + 1; ?></td>

<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<td style="display:none;" class="more_toggler1c"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_p]" placeholder="<?php _e('Product ID(s) e.g 1674','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['single_p'] ); ?>" /></td>

<td style="display:none;" class="more_toggler1c"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_px]" placeholder="<?php _e('Product ID(s) e.g 1674','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['single_px'] ); ?>" /></td>

<td style="display:none;" class="more_toggler1c"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_p_cat]" placeholder="<?php _e('Category Slug(s) e.g my-cat','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['single_p_cat'] ); ?>" /></td>

<td style="display:none;" class="more_toggler1c"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_px_cat]" placeholder="<?php _e('Category Slug(s) e.g my-cat','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['single_px_cat'] ); ?>" /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" placeholder="dd-mm-yy" name="wccs_settings[buttons][<?php echo $i; ?>][format_date]" value="<?php echo esc_attr( $options['buttons'][$i]['format_date'] ); ?>" /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" placeholder="3" name="wccs_settings[buttons][<?php echo $i; ?>][min_before]" value="<?php echo esc_attr( $options['buttons'][$i]['min_before'] ); ?>" /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" placeholder="3" name="wccs_settings[buttons][<?php echo $i; ?>][max_after]" value="<?php echo esc_attr( $options['buttons'][$i]['max_after'] ); ?>" /></td>

<td style="display:none;text-align:center;" class="hide_stuff_color daoo"><input name="wccs_settings[buttons][<?php echo $i; ?>][days_disabler]" type="checkbox" value="true" <?php if (  !empty ($options['buttons'][$i]['days_disabler'])) echo "checked='checked'"; ?> /></td>

<td style="display:none;text-align:center;" class="hide_stuff_days"><input name="wccs_settings[buttons][<?php echo $i; ?>][days_disabler0]" type="checkbox" value="10" <?php if (  10 == ($options['buttons'][$i]['days_disabler0'])) echo "checked='checked'"; ?> /></td>

<td style="display:none;text-align:center;" class="hide_stuff_days"><input name="wccs_settings[buttons][<?php echo $i; ?>][days_disabler1]" type="checkbox" value="1" <?php if (  !empty ($options['buttons'][$i]['days_disabler1'])) echo "checked='checked'"; ?> /></td>

<td style="display:none;text-align:center;" class="hide_stuff_days"><input name="wccs_settings[buttons][<?php echo $i; ?>][days_disabler2]" type="checkbox" value="2" <?php if (  2 == ($options['buttons'][$i]['days_disabler2'])) echo "checked='checked'"; ?> /></td>

<td style="display:none;text-align:center;" class="hide_stuff_days"><input name="wccs_settings[buttons][<?php echo $i; ?>][days_disabler3]" type="checkbox" value="3" <?php if (  3 == ($options['buttons'][$i]['days_disabler3'])) echo "checked='checked'"; ?> /></td>

<td style="display:none;text-align:center;" class="hide_stuff_days"><input name="wccs_settings[buttons][<?php echo $i; ?>][days_disabler4]" type="checkbox" value="4" <?php if (  4 == ($options['buttons'][$i]['days_disabler4'])) echo "checked='checked'"; ?> /></td>

<td style="display:none;text-align:center;" class="hide_stuff_days"><input name="wccs_settings[buttons][<?php echo $i; ?>][days_disabler5]" type="checkbox" value="5" <?php if (  5 == ($options['buttons'][$i]['days_disabler5'])) echo "checked='checked'"; ?> /></td>

<td style="display:none;text-align:center;" class="hide_stuff_days"><input name="wccs_settings[buttons][<?php echo $i; ?>][days_disabler6]" type="checkbox" value="6" <?php if (  6 == ($options['buttons'][$i]['days_disabler6'])) echo "checked='checked'"; ?> /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><span class="spongagge"><?php _e( 'Min Date', 'woocommerce-checkout-manager-pro' ); ?></span></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_yy]"
placeholder="<?php _e('2013','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'yy', 'woocommerce-checkout-manager-pro' ); ?>" value="<?php echo esc_attr( $options['buttons'][$i]['single_yy'] ); ?>" /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_mm]"
placeholder="<?php _e('10','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'mm', 'woocommerce-checkout-manager-pro' ); ?>" value="<?php echo esc_attr( $options['buttons'][$i]['single_mm'] ); ?>" /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_dd]"
placeholder="<?php _e('25','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'dd', 'woocommerce-checkout-manager-pro' ); ?>" value="<?php echo esc_attr( $options['buttons'][$i]['single_dd'] ); ?>" /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><span class="spongagge"><?php _e( 'Max Date', 'woocommerce-checkout-manager-pro' ); ?></span></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_max_yy]"
placeholder="<?php _e('2013','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'yy', 'woocommerce-checkout-manager-pro' ); ?>" value="<?php echo esc_attr( $options['buttons'][$i]['single_max_yy'] ); ?>" /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_max_mm]"
placeholder="<?php _e('10','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'mm', 'woocommerce-checkout-manager-pro' ); ?>" value="<?php echo esc_attr( $options['buttons'][$i]['single_max_mm'] ); ?>" /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_max_dd]"
placeholder="<?php _e('25','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'dd', 'woocommerce-checkout-manager-pro' ); ?>" value="<?php echo esc_attr( $options['buttons'][$i]['single_max_dd'] ); ?>" /></td>


<td class="more_toggler1 more_toggler1c condition_tick add_amount_field" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][checkbox]" type="checkbox" value="true" <?php if (  !empty ($options['buttons'][$i]['checkbox'])) echo "checked='checked'"; ?> /></td>

<td class="more_toggler1 more_toggler1c condition_tick add_amount_field" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][floatright]" type="checkbox" value="true" <?php if (  !empty ($options['buttons'][$i]['floatright'])) echo "checked='checked'"; ?> /></td>

<td class="more_toggler1 more_toggler1c condition_tick add_amount_field" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][center_align]" type="checkbox" value="true" <?php if (  !empty ($options['buttons'][$i]['center_align'])) echo "checked='checked'"; ?> /></td>

<td class="more_toggler1 more_toggler1c" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][tax_remove]" type="checkbox" value="true" <?php if (  !empty ($options['buttons'][$i]['tax_remove'])) echo "checked='checked'"; ?> /></td>

<td class="more_toggler1 more_toggler1c" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][deny_receipt]" type="checkbox" value="true" <?php if (  !empty ($options['buttons'][$i]['deny_receipt'])) echo "checked='checked'"; ?> /></td>

<td class="more_toggler1 more_toggler1c" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][add_amount]" type="checkbox" value="true" <?php if (  !empty ($options['buttons'][$i]['add_amount'])) echo "checked='checked'"; ?> /></td>

<td class="add_amount_field" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][fee_name]" type="text" value="<?php echo esc_attr($options['buttons'][$i]['fee_name']); ?>" /></td>

<td class="add_amount_field" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][add_amount_field]" type="text" value="<?php echo esc_attr($options['buttons'][$i]['add_amount_field']); ?>" /></td>

<td class="more_toggler1 more_toggler1c" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][conditional_parent_use]" type="checkbox" value="true" <?php if (  !empty ($options['buttons'][$i]['conditional_parent_use'])) echo "checked='checked'"; ?> /></td>

<td class="condition_tick" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][conditional_parent]" type="checkbox" value="true" <?php if (  !empty ($options['buttons'][$i]['conditional_parent'])) echo "checked='checked'"; ?> /></td>

<td class="hide_stuff_color hide_stuff_change hide_stuff_op hide_stuff_opcheck more_toggler1"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][label]" placeholder="<?php _e('My Field Name','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['label'] ); ?>" /></td>

<td class="hide_stuff_color hide_stuff_change hide_stuff_op hide_stuff_opcheck more_toggler1"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][placeholder]" placeholder="<?php _e('Example red','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['placeholder'] ); ?>" /></td>

<td style="display:none;" class="condition_tick"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][chosen_valt]" placeholder="<?php _e('Yes','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['chosen_valt'] ); ?>" /></td>

<td style="display:none;" class="condition_tick"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][conditional_tie]" placeholder="<?php _e('Parent Abbr. Name','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['conditional_tie'] ); ?>" /></td>

<td style="display:none;" class="more_toggler1 more_toggler1c condition_tick add_amount_field"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][colorpickerd]" id="colorpic<?php echo $i; ?>" placeholder="<?php _e('#000','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['colorpickerd'] ); ?>" /></td>

<td class="hide_stuff_change" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][changenamep]" placeholder="<?php _e('Billing Address','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['changenamep'] ); ?>" /></td>

<td class="hide_stuff_change" style="display:none;" ><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][changename]" placeholder="<?php _e('Bill Form','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['changename'] ); ?>" /></td>

<td class="hide_stuff_opcheck" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][check_1]"  placeholder="<?php _e('Yes apple','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['check_1'] ); ?>" /></td>

<td class="hide_stuff_opcheck" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][check_2]"  placeholder="<?php _e('No apple','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['check_2'] ); ?>" /></td>

<td class="hide_stuff_op wccm1" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][force_title2]" placeholder="<?php _e('Name Guide','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['force_title2'] ); ?>" /></td>

<td class="hide_stuff_op wccm1" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_1]" placeholder="<?php _e('Option Name 1','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_1'] ); ?>" /></td>

<td class="hide_stuff_op wccm1" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_2]"  placeholder="<?php _e('Option Name 2','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_2'] ); ?>" /></td>

<td class="hide_stuff_op wccm1" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_3]" placeholder="<?php _e('Option Name 3','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_3'] ); ?>" /></td>

<td class="hide_stuff_op wccm1" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_4]" placeholder="<?php _e('Option Name 4','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_4'] ); ?>" /></td>

<td class="hide_stuff_op wccm1" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_5]" placeholder="<?php _e('Option Name 5','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_5'] ); ?>" /></td>

<td class="hide_stuff_op2" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_6]" placeholder="<?php _e('Option Name 6','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_6'] ); ?>" /></td>

<td class="hide_stuff_op2" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_7]" placeholder="<?php _e('Option Name 7','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_7'] ); ?>" /></td>

<td class="hide_stuff_op2" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_8]" placeholder="<?php _e('Option Name 8','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_8'] ); ?>" /></td>

<td class="hide_stuff_op2" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_9]" placeholder="<?php _e('Option Name 9','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_9'] ); ?>" /></td>

<td class="hide_stuff_op2" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_10]" placeholder="<?php _e('Option Name 10','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_10'] ); ?>" /></td>

<td class="hide_stuff_op3" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_11]"
placeholder="<?php _e('Option Name 11','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_11'] ); ?>" /></td>

<td class="hide_stuff_op3" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_12]"  placeholder="<?php _e('Option Name 12','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_12'] ); ?>" /></td>

<td class="hide_stuff_op3" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_13]"  placeholder="<?php _e('Option Name 13','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_13'] ); ?>" /></td>

<td class="hide_stuff_op3" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_14]"  placeholder="<?php _e('Option Name 14','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_14'] ); ?>" /></td>

<td class="hide_stuff_op3" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_15]"  placeholder="<?php _e('Option Name 15','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_15'] ); ?>" /></td>

<td class="hide_stuff_op4" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_16]"  placeholder="<?php _e('Option Name 16','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_16'] ); ?>" /></td>

<td class="hide_stuff_op4" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_17]"  placeholder="<?php _e('Option Name 17','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_17'] ); ?>" /></td>

<td class="hide_stuff_op4" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_18]"  placeholder="<?php _e('Option Name 18','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_18'] ); ?>" /></td>

<td class="hide_stuff_op4" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_19]"  placeholder="<?php _e('Option Name 19','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_19'] ); ?>" /></td>

<td class="hide_stuff_op4" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_20]" placeholder="<?php _e('Option Name 20','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_20'] ); ?>" /></td>

<td class="hide_stuff_op5" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_21]"
placeholder="<?php _e('Option Name 21','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_21'] ); ?>" /></td>

<td class="hide_stuff_op5" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_22]"  placeholder="<?php _e('Option Name 22','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_22'] ); ?>" /></td>

<td class="hide_stuff_op5" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_23]"  placeholder="<?php _e('Option Name 23','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_23'] ); ?>" /></td>

<td class="hide_stuff_op5" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_24]"  placeholder="<?php _e('Option Name 24','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_24'] ); ?>" /></td>

<td class="hide_stuff_op5" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_25]"  placeholder="<?php _e('Option Name 25','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_25'] ); ?>" /></td>

<td class="hide_stuff_op6" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_26]"  placeholder="<?php _e('Option Name 26','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_26'] ); ?>" /></td>

<td class="hide_stuff_op6" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_27]"  placeholder="<?php _e('Option Name 27','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_27'] ); ?>" /></td>

<td class="hide_stuff_op6" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_28]"  placeholder="<?php _e('Option Name 28','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_28'] ); ?>" /></td>

<td class="hide_stuff_op6" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_29]"  placeholder="<?php _e('Option Name 29','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_29'] ); ?>" /></td>

<td class="hide_stuff_op6" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_30]"  placeholder="<?php _e('Option Name 30','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_30'] ); ?>" /></td>

<td class="hide_stuff_op7" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_31]"
placeholder="<?php _e('Option Name 31','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_31'] ); ?>" /></td>

<td class="hide_stuff_op7" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_32]"  placeholder="<?php _e('Option Name 32','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_32'] ); ?>" /></td>

<td class="hide_stuff_op7" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_33]"  placeholder="<?php _e('Option Name 33','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_33'] ); ?>" /></td>

<td class="hide_stuff_op7" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_34]"  placeholder="<?php _e('Option Name 34','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_34'] ); ?>" /></td>

<td class="hide_stuff_op7" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_35]"  placeholder="<?php _e('Option Name 35','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_35'] ); ?>" /></td>

<td class="hide_stuff_op8" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_36]"  placeholder="<?php _e('Option Name 36','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_36'] ); ?>" /></td>

<td class="hide_stuff_op8" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_37]"  placeholder="<?php _e('Option Name 37','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_37'] ); ?>" /></td>

<td class="hide_stuff_op8" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_38]"  placeholder="<?php _e('Option Name 38','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_38'] ); ?>" /></td>

<td class="hide_stuff_op8" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_39]"  placeholder="<?php _e('Option Name 39','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_39'] ); ?>" /></td>

<td class="hide_stuff_op8" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_40]"  placeholder="<?php _e('Option Name 40','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_40'] ); ?>" /></td>

<td class="hide_stuff_op9" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_41]"
placeholder="<?php _e('Option Name 41','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_41'] ); ?>" /></td>

<td class="hide_stuff_op9" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_42]"  placeholder="<?php _e('Option Name 42','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_42'] ); ?>" /></td>

<td class="hide_stuff_op9" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_43]"  placeholder="<?php _e('Option Name 43','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_43'] ); ?>" /></td>

<td class="hide_stuff_op9" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_44]"  placeholder="<?php _e('Option Name 44','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_44'] ); ?>" /></td>

<td class="hide_stuff_op9" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_45]"  placeholder="<?php _e('Option Name 45','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_45'] ); ?>" /></td>

<td class="hide_stuff_op10" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_46]"  placeholder="<?php _e('Option Name 46','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_46'] ); ?>" /></td>

<td class="hide_stuff_op10" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_47]"  placeholder="<?php _e('Option Name 47','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_47'] ); ?>" /></td>

<td class="hide_stuff_op10" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_48]"  placeholder="<?php _e('Option Name 48','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_48'] ); ?>" /></td>

<td class="hide_stuff_op10" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_49]"  placeholder="<?php _e('Option Name 49','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_49'] ); ?>" /></td>

<td class="hide_stuff_op10" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_50]"  placeholder="<?php _e('Option Name 50','woocommerce-checkout-manager-pro'); ?>"
value="<?php echo esc_attr( $options['buttons'][$i]['option_50'] ); ?>" /></td>

<td style="display:none;" class="hide_stuff_op hide_stuff_adder"><?php _e('Toggler Adder', 'woocommerce-checkout-manager-pro' ); ?></td>
<td class="hide_stuff_color more_toggler1"><?php _e('Options Toggler', 'woocommerce-checkout-manager-pro' ); ?></td>
<td class="hide_stuff_op hide_stuff_color more_toggler1"><?php _e('Checkbox Toggler', 'woocommerce-checkout-manager-pro' ); ?></td>
<td class="hide_stuff_op hide_stuff_color more_toggler1"><?php _e('Swapper Toggler', 'woocommerce-checkout-manager-pro' ); ?></td>
<td class="hide_stuff_op more_toggler1"><?php _e('Date Toggler', 'woocommerce-checkout-manager-pro' ); ?></td>
<td style="display:none;" class="more_toggler1 more_toggler1a"><?php _e('Show & Hide Toggler', 'woocommerce-checkout-manager-pro' ); ?></td>
<td class="hide_stuff_op more_toggler"><?php _e('More Toggler', 'woocommerce-checkout-manager-pro' ); ?></td>



<td class="hide_stuff_color hide_stuff_op more_toggler1">
<select name="wccs_settings[buttons][<?php echo $i; ?>][type]" >  <!--Call run() function-->
<option value="text" <?php selected( $options['buttons'][$i]['type'], 'text' ); ?>><?php _e('Text Input','woocommerce-checkout-manager-pro'); ?></option>
<option value="textarea" <?php selected( $options['buttons'][$i]['type'], 'textarea' ); ?>><?php _e('Text Area','woocommerce-checkout-manager-pro'); ?></option>
<option value="password" <?php selected( $options['buttons'][$i]['type'], 'password' ); ?>><?php _e('Password','woocommerce-checkout-manager-pro'); ?></option>
<option value="radio" <?php selected( $options['buttons'][$i]['type'], 'radio' ); ?>><?php _e('Radio Button','woocommerce-checkout-manager-pro'); ?></option>
<option value="checkbox" <?php selected( $options['buttons'][$i]['type'], 'checkbox' ); ?>><?php _e('Check Box','woocommerce-checkout-manager-pro'); ?></option>
<option value="select" <?php selected( $options['buttons'][$i]['type'], 'select' ); ?>><?php _e('Select Options','woocommerce-checkout-manager-pro'); ?></option>
<option value="date" <?php selected( $options['buttons'][$i]['type'], 'date' ); ?>><?php _e('Date Picker','woocommerce-checkout-manager-pro'); ?></option>
<option value="time" <?php selected( $options['buttons'][$i]['type'], 'time' ); ?>><?php _e('Time Picker','woocommerce-checkout-manager-pro'); ?></option>
<option value="changename" <?php selected( $options['buttons'][$i]['type'], 'changename' ); ?>><?php _e('Text/ Html Swapper','woocommerce-checkout-manager-pro'); ?></option>
<option value="colorpicker" <?php selected( $options['buttons'][$i]['type'], 'colorpicker' ); ?>><?php _e('Color Picker','woocommerce-checkout-manager-pro'); ?></option>
</select>
</td>




<td class="hide_stuff_color hide_stuff_change hide_stuff_op more_toggler1c"><input type="text" maxlength="10" name="wccs_settings[buttons][<?php echo $i; ?>][cow]"
placeholder="MyField" value="<?php echo esc_attr( $options['buttons'][$i]['cow'] ); ?>" /></td>


<td class="wccs-remove"><a class="wccs-remove-button" href="javascript:;" title="<?php esc_attr_e( 'Remove Field' , 'woocommerce-checkout-manager-pro' ); ?>">&times;</a></td>
</tr>

<?php endfor; endif; ?>
<!-- Empty -->

<?php $i = 999; ?>

<tr valign="top" class="wccs-clone" >

<td class="wccs-order" title="<?php esc_attr_e( 'Change order' , 'woocommerce-checkout-manager-pro' ); ?>"><?php echo $i; ?></td>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<td style="display:none;" class="more_toggler1c"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_p]"
placeholder="<?php _e('Product ID(s) e.g 1674','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'Hide field from this Products Only', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td style="display:none;" class="more_toggler1c"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_px]"
placeholder="<?php _e('Product ID(s) e.g 1674','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'Display Field for these Products Only', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td style="display:none;" class="more_toggler1c"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_p_cat]"
placeholder="<?php _e('Category Slug(s) e.g my-cat','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'Hide field from Category', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td style="display:none;" class="more_toggler1c"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_px_cat]"
placeholder="<?php _e('Category Slug(s) e.g my-cat','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'Show Field for Category', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][format_date]" placeholder="dd-mm-yy" title="dd-mm-yy" value="" /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][min_before]" placeholder="3" title="Days Before" value="" /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][max_after]" placeholder="3" title="Days After" value="" /></td>

<td style="display:none;text-align:center;" class="hide_stuff_color daoo"><input name="wccs_settings[buttons][<?php echo $i; ?>][days_disabler]" type="checkbox" value="" /></td>

<td style="display:none;text-align:center;" class="hide_stuff_days"><input name="wccs_settings[buttons][<?php echo $i; ?>][days_disabler0]" type="checkbox" value="" /></td>

<td style="display:none;text-align:center;" class="hide_stuff_days"><input name="wccs_settings[buttons][<?php echo $i; ?>][days_disabler1]" type="checkbox" value="" /></td>

<td style="display:none;text-align:center;" class="hide_stuff_days"><input name="wccs_settings[buttons][<?php echo $i; ?>][days_disabler2]" type="checkbox" value="" /></td>

<td style="display:none;text-align:center;" class="hide_stuff_days"><input name="wccs_settings[buttons][<?php echo $i; ?>][days_disabler3]" type="checkbox" value="" /></td>

<td style="display:none;text-align:center;" class="hide_stuff_days"><input name="wccs_settings[buttons][<?php echo $i; ?>][days_disabler4]" type="checkbox" value="" /></td>

<td style="display:none;text-align:center;" class="hide_stuff_days"><input name="wccs_settings[buttons][<?php echo $i; ?>][days_disabler5]" type="checkbox" value="" /></td>

<td style="display:none;text-align:center;" class="hide_stuff_days"><input name="wccs_settings[buttons][<?php echo $i; ?>][days_disabler6]" type="checkbox" value="" /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days" title="<?php esc_attr_e( 'Min Date', 'woocommerce-checkout-manager-pro' ); ?>"><span class="spongagge"><?php _e( 'Min Date', 'woocommerce-checkout-manager-pro' ); ?></span></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_yy]"
placeholder="<?php _e('2013','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'yy', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_mm]"
placeholder="<?php _e('10','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'mm', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_dd]"
placeholder="<?php _e('25','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'dd', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days" title="<?php esc_attr_e( 'Max Date', 'woocommerce-checkout-manager-pro' ); ?>"><span class="spongagge"><?php _e( 'Max Date', 'woocommerce-checkout-manager-pro' ); ?></span></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_max_yy]"
placeholder="<?php _e('2013','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'yy', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_max_mm]"
placeholder="<?php _e('10','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'mm', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td style="display:none;" class="hide_stuff_color hide_stuff_days"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][single_max_dd]"
placeholder="<?php _e('25','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'dd', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td class="more_toggler1 more_toggler1c condition_tick add_amount_field" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][checkbox]" type="checkbox"
title="<?php esc_attr_e( 'Add/Remove Required Attribute', 'woocommerce-checkout-manager-pro' ); ?>" value=" " /></td>

<td class="more_toggler1 more_toggler1c condition_tick add_amount_field" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][floatright]" type="checkbox"
title="<?php esc_attr_e( 'Float Right', 'woocommerce-checkout-manager-pro' ); ?>" value=" " /></td>

<td class="more_toggler1 more_toggler1c condition_tick add_amount_field" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][center_align]" type="checkbox"
title="<?php esc_attr_e( 'Full Width', 'woocommerce-checkout-manager-pro' ); ?>" value=" " /></td>

<td class="more_toggler1 more_toggler1c" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][tax_remove]" type="checkbox"
title="<?php esc_attr_e( 'Remove tax', 'woocommerce-checkout-manager-pro' ); ?>" value=" " /></td>

<td class="more_toggler1 more_toggler1c" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][deny_receipt]" type="checkbox"
title="<?php esc_attr_e( 'Deny Receipt', 'woocommerce-checkout-manager-pro' ); ?>" value=" " /></td>

<td class="more_toggler1 more_toggler1c" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][add_amount]" type="checkbox"
title="<?php esc_attr_e( 'Add Amount', 'woocommerce-checkout-manager-pro' ); ?>" value=" " /></td>

<td class="add_amount_field" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][fee_name]" type="text"
title="<?php esc_attr_e( 'Amount Name', 'woocommerce-checkout-manager-pro' ); ?>" value=" " /></td>

<td class="add_amount_field" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][add_amount_field]" type="text"
title="<?php esc_attr_e( 'Add Amount Field', 'woocommerce-checkout-manager-pro' ); ?>" value=" " /></td>

<td class="more_toggler1 more_toggler1c" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][conditional_parent_use]" type="checkbox"
title="<?php esc_attr_e( 'Conditional Field On', 'woocommerce-checkout-manager-pro' ); ?>" value=" " /></td>

<td class="condition_tick" style="display:none;text-align:center;"><input name="wccs_settings[buttons][<?php echo $i; ?>][conditional_parent]" type="checkbox"
title="<?php esc_attr_e( 'Conditional Parent', 'woocommerce-checkout-manager-pro' ); ?>" value=" " /></td>

<td class="hide_stuff_color hide_stuff_change hide_stuff_op hide_stuff_opcheck more_toggler1"><input placeholder="<?php _e('My Field Name','woocommerce-checkout-manager-pro'); ?>" type="text" name="wccs_settings[buttons][<?php echo $i; ?>][label]"
title="<?php esc_attr_e( 'Label of the New Field', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td class="hide_stuff_color hide_stuff_change hide_stuff_op hide_stuff_opcheck more_toggler1"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][placeholder]" placeholder="<?php _e('Example red','woocommerce-checkout-manager-pro'); ?>"
title="<?php esc_attr_e( 'Placeholder - Preview of Data to Input', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td style="display:none;" class="condition_tick"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][chosen_valt]" placeholder="<?php _e('Yes','woocommerce-checkout-manager-pro'); ?>"
title="<?php esc_attr_e( 'Chosen value for conditional', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td style="display:none;" class="condition_tick"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][conditional_tie]"
placeholder="<?php _e('Parent Abbr. Name','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'Parent Abbr. Name', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td style="display:none;" class="more_toggler1 more_toggler1c condition_tick add_amount_field"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][colorpickerd]" id="colorpic<?php echo $i; ?>"
placeholder="<?php _e('#000','woocommerce-checkout-manager-pro'); ?>" title="<?php esc_attr_e( 'Default Color', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td class="hide_stuff_change" style="display:none;" ><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][changenamep]" placeholder="<?php _e('Billing Address', 'woocommerce-checkout-manager-pro' ); ?>"
title="<?php esc_attr_e( 'Input Name', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td class="hide_stuff_change" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][changename]" placeholder="<?php _e('Bill Form','woocommerce-checkout-manager-pro'); ?>"
title="<?php esc_attr_e( 'Change Name to', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td class="hide_stuff_opcheck" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][check_1]"
value="" placeholder="<?php _e('Yes apple','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_opcheck" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][check_2]"
value="" placeholder="<?php _e('No apple','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op wccm1" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][force_title2]"
value="" placeholder="<?php _e('Name Guide','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op wccm1" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_1]"
value="" placeholder="<?php _e('Option Name 1','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op wccm1" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_2]"
value="" placeholder="<?php _e('Option Name 2','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op wccm1" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_3]"
value="" placeholder="<?php _e('Option Name 3','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op wccm1" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_4]"
value="" placeholder="<?php _e('Option Name 4','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op wccm1" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_5]"
value="" placeholder="<?php _e('Option Name 5','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op2" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_6]"
value="" placeholder="<?php _e('Option Name 6','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op2" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_7]"
value="" placeholder="<?php _e('Option Name 7','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op2" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_8]"
value="" placeholder="<?php _e('Option Name 8','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op2" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_9]"
value="" placeholder="<?php _e('Option Name 9','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op2" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_10]"
value="" placeholder="<?php _e('Option Name 10','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op3" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_11]"
value="" placeholder="<?php _e('Option Name 11','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op3" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_12]"
value="" placeholder="<?php _e('Option Name 12','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op3" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_13]"
value="" placeholder="<?php _e('Option Name 13','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op3" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_14]"
value="" placeholder="<?php _e('Option Name 14','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op3" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_15]"
value="" placeholder="<?php _e('Option Name 15','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op4" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_16]"
value="" placeholder="<?php _e('Option Name 16','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op4" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_17]"
value="" placeholder="<?php _e('Option Name 17','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op4" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_18]"
value="" placeholder="<?php _e('Option Name 18','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op4" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_19]"
value="" placeholder="<?php _e('Option Name 19','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op4" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_20]"
value="" placeholder="<?php _e('Option Name 20','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op5" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_21]"
value="" placeholder="<?php _e('Option Name 21','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op5" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_22]"
value="" placeholder="<?php _e('Option Name 22','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op5" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_23]"
value="" placeholder="<?php _e('Option Name 23','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op5" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_24]"
value="" placeholder="<?php _e('Option Name 24','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op5" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_25]"
value="" placeholder="<?php _e('Option Name 25','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op6" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_26]"
value="" placeholder="<?php _e('Option Name 26','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op6" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_27]"
value="" placeholder="<?php _e('Option Name 27','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op6" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_28]"
value="" placeholder="<?php _e('Option Name 28','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op6" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_29]"
value="" placeholder="<?php _e('Option Name 29','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op6" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_30]"
value="" placeholder="<?php _e('Option Name 30','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op7" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_31]"
value="" placeholder="<?php _e('Option Name 31','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op7" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_32]"
value="" placeholder="<?php _e('Option Name 32','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op7" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_33]"
value="" placeholder="<?php _e('Option Name 33','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op7" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_34]"
value="" placeholder="<?php _e('Option Name 34','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op7" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_35]"
value="" placeholder="<?php _e('Option Name 35','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op8" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_36]"
value="" placeholder="<?php _e('Option Name 36','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op8" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_37]"
value="" placeholder="<?php _e('Option Name 37','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op8" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_38]"
value="" placeholder="<?php _e('Option Name 38','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op8" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_39]"
value="" placeholder="<?php _e('Option Name 39','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op8" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_40]"
value="" placeholder="<?php _e('Option Name 40','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op9" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_41]"
value="" placeholder="<?php _e('Option Name 41','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op9" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_42]"
value="" placeholder="<?php _e('Option Name 42','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op9" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_43]"
value="" placeholder="<?php _e('Option Name 43','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op9" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_44]"
value="" placeholder="<?php _e('Option Name 44','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op9" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_45]"
value="" placeholder="<?php _e('Option Name 45','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op10" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_46]"
value="" placeholder="<?php _e('Option Name 46','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op10" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_47]"
value="" placeholder="<?php _e('Option Name 47','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op10" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_48]"
value="" placeholder="<?php _e('Option Name 48','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op10" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_49]"
value="" placeholder="<?php _e('Option Name 49','woocommerce-checkout-manager-pro'); ?>" /></td>

<td class="hide_stuff_op10" style="display:none;"><input type="text" name="wccs_settings[buttons][<?php echo $i; ?>][option_50]"
value="" placeholder="<?php _e('Option Name 50','woocommerce-checkout-manager-pro'); ?>" /></td>


<td style="display:none;" class="hide_stuff_op hide_stuff_adder"><?php _e('Toggler Adder', 'woocommerce-checkout-manager-pro' ); ?></td>
<td class="hide_stuff_color more_toggler1"><?php _e('Options Toggler', 'woocommerce-checkout-manager-pro' ); ?></td>
<td class="hide_stuff_op hide_stuff_color more_toggler1"><?php _e('Checkbox Toggler', 'woocommerce-checkout-manager-pro' ); ?></td>
<td class="hide_stuff_op hide_stuff_color more_toggler1"><?php _e('Swapper Toggler', 'woocommerce-checkout-manager-pro' ); ?></td>
<td class="hide_stuff_op more_toggler1"><?php _e('Date Toggler', 'woocommerce-checkout-manager-pro' ); ?></td>
<td style="display:none;" class="more_toggler1 more_toggler1a"><?php _e('Show & Hide Toggler', 'woocommerce-checkout-manager-pro' ); ?></td>
<td class="hide_stuff_op more_toggler"><?php _e('More Toggler', 'woocommerce-checkout-manager-pro' ); ?></td>

<td class="hide_stuff_color hide_stuff_op more_toggler1">
<select name="wccs_settings[buttons][<?php echo $i; ?>][type]" >  <!--Call run() function-->
<option value="text" ><?php _e('Text Input','woocommerce-checkout-manager-pro'); ?></option>
<option value="textarea" ><?php _e('Text Area','woocommerce-checkout-manager-pro'); ?></option>
<option value="password" ><?php _e('Password','woocommerce-checkout-manager-pro'); ?></option>
<option value="radio" ><?php _e('Radio Button','woocommerce-checkout-manager-pro'); ?></option>
<option value="checkbox" ><?php _e('Check Box','woocommerce-checkout-manager-pro'); ?></option>
<option value="select" ><?php _e('Select Options','woocommerce-checkout-manager-pro'); ?></option>
<option value="date" ><?php _e('Date Picker','woocommerce-checkout-manager-pro'); ?></option>
<option value="time" ><?php _e('Time Picker','woocommerce-checkout-manager-pro'); ?></option>
<option value="changename" ><?php _e('Text/ Html Swapper','woocommerce-checkout-manager-pro'); ?></option>
<option value="colorpicker" ><?php _e('Color Picker','woocommerce-checkout-manager-pro'); ?></option>
</select>
</td>


<td class="hide_stuff_color hide_stuff_change hide_stuff_op more_toggler1c"><input type="text" maxlength="10" name="wccs_settings[buttons][<?php echo $i; ?>][cow]"
placeholder="MyField" title="<?php esc_attr_e( 'Abbreviation (No spaces)', 'woocommerce-checkout-manager-pro' ); ?>" value="" /></td>

<td class="wccs-remove"><a class="wccs-remove-button" href="javascript:;" title="<?php esc_attr_e( 'Remove Field' , 'woocommerce-checkout-manager-pro' ); ?>">&times;</a></td>

</tr>
</tbody>

</table>

<div class="wccs-table-footer">
<a href="javascript:;" id="wccs-add-button" class="button-secondary"><?php _e( '+ Add New Field' , 'woocommerce-checkout-manager-pro' ); ?></a>
</div>
</div>


</form>
<?php
}} else {
function wccs__options_page() {
?>
<div style="margin-top:3%;" class="error">
<p><h2 style="line-height:1.5em;">
<?php _e('Your License Key is invalid for WooCommerce Checkout Manager Pro.','woocommerce-checkout-manager-pro'); ?><br />Please <a href="admin.php?page=License_check_slug"><?php _e('update','woocommerce-checkout-manager-pro'); ?></a> <?php _e('your license key.','woocommerce-checkout-manager-pro'); ?>
</h2></p>
</div>
<?php
}}


function wccs_admin_plugin_actions($links) {
$wccs_plugin_links = array(
'<a href="admin.php?page=woocommerce-checkout-manager-pro/woocommerce-checkout-manager-pro.php">'.__('Settings').'</a>',
'<a href="http://www.trottyzone.com/forums/forum/website-support/">'.__('Support').'</a>',
);
return array_merge( $wccs_plugin_links, $links );
}

function wccs_override_checkout_fields1($fields1) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['checkness']['wccs_opt_1'] ) )
unset($fields1['billing']['billing_first_name']);
return $fields1;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields1' );


function wccs_override_checkout_fieldscom($fields1) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['checkness']['wccs_opt_3'] ) )
unset($fields1['billing']['billing_company']);
return $fields1;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fieldscom' );

function wccs_override_required_fields1( $address_fields1 ) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['checkness']['wccs_rq_1'] ) )
$address_fields1['billing_first_name']['required'] = false;
return $address_fields1;
} 
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields1', 10, 1 );  

function custom_replace_checkout_fields( $fields ) { 
$options = get_option( 'wccs_settings' ); 
if ( ! empty( $options['replace']['placeholder'] ) ) 
$fields['billing']['billing_first_name']['placeholder'] = ''.$options['replace']['placeholder'].''; 
return $fields; 
} 
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fields' );

function custom_replace_checkout_fieldsa( $fields ) { 
$options = get_option( 'wccs_settings' ); 
if ( ! empty( $options['replace']['label'] ) ) 
$fields['billing']['billing_first_name']['label'] = ''.$options['replace']['label'].''; 
return $fields;
} 
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fieldsa' ); 

function wccs_override_checkout_fields2($fields2) { 
$options = get_option( 'wccs_settings' ); 
if (  !empty ($options['checkness']['wccs_opt_2'] ) ) 
unset($fields2['billing']['billing_last_name']); 
return $fields2; 
} 
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields2' );

function wccs_override_required_fields2( $address_fields2 ) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['checkness']['wccs_rq_2'] ) )
$address_fields2['billing_last_name']['required'] = false;
return $address_fields2;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields2', 10, 1 );

function custom_replace_checkout_fields1( $fields ) {
$options = get_option( 'wccs_settings' );
if ( ! empty( $options['replace']['placeholder1'] ) )
$fields['billing']['billing_last_name']['placeholder'] = ''.$options['replace']['placeholder1'].'';
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fields1' );

function custom_replace_checkout_fieldsb( $fields ) {
$options = get_option( 'wccs_settings' );
if ( ! empty( $options['replace']['label1'] ) )
$fields['billing']['billing_last_name']['label'] = ''.$options['replace']['label1'].'';
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fieldsb' );

function custom_replace_checkout_fields2( $fields ) {
$options = get_option( 'wccs_settings' );
if ( ! empty( $options['replace']['placeholder2'] ) )
$fields['billing']['billing_company']['placeholder'] = ''.$options['replace']['placeholder2'].'';
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fields2' );

function custom_replace_checkout_fieldsc( $fields ) { $options = get_option( 'wccs_settings' );
if ( !empty( $options['replace']['label5'] ) )
$fields['billing']['billing_company']['label'] = ''.$options['replace']['label5'].'';
return $fields; 
}
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fieldsc' );

function wccs_override_checkout_fields4($fields4) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['checkness']['wccs_opt_4'] ) )
unset($fields4['billing']['billing_address_1']); return $fields4;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields4' );

function wccs_override_checkout_fields5($fields5) { 
$options = get_option( 'wccs_settings' ); 
if (  !empty ($options['checkness']['wccs_opt_5'] ) ) 
unset($fields5['billing']['billing_address_2']); 
return $fields5;
} 
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields5' );
// ============= City ================
function wccs_override_checkout_fields6($fields6) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['checkness']['wccs_opt_6'] )  )
unset($fields6['billing']['billing_city']);
return $fields6;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields6' );
// ============= Postal Code ================
function wccs_override_checkout_fields7($fields7) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['checkness']['wccs_opt_7'] )  )
unset($fields7['billing']['billing_postcode']);
return $fields7;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields7' );

function wccs_override_checkout_fields9($fields9) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['checkness']['wccs_opt_9'] ) )
unset($fields9['billing']['billing_state']);
return $fields9;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields9' );
function wccs_override_checkout_fields10($fields10) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['checkness']['wccs_opt_10'] ) )
unset($fields10['billing']['billing_phone']);
return $fields10;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields10' );
function wccs_override_required_fields10( $address_fields10 ) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['checkness']['wccs_rq_10'] ) )
$address_fields10['billing_phone']['required'] = false;
return $address_fields10;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields10', 10, 1 );
// replace label and placeholder
function custom_replace_checkout_fields9( $fields ) {
$options = get_option( 'wccs_settings' );
if ( ! empty( $options['replace']['placeholder3'] ) )
$fields['billing']['billing_phone']['placeholder'] = ''.$options['replace']['placeholder3'].'';
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fields9' );
function custom_replace_checkout_fieldsj( $fields ) {
$options = get_option( 'wccs_settings' );
if ( ! empty( $options['replace']['label3'] ) )
$fields['billing']['billing_phone']['label'] = ''.$options['replace']['label3'].'';
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fieldsj' );
function wccs_override_checkout_fields11($fields11) {
$options = get_option( 'wccs_settings' );
if (  !empty($options['checkness']['wccs_opt_11'] ) )
unset($fields11['billing']['billing_email']);
return $fields11;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields11' );
function wccs_override_checkout_fieldsCountry1($fields) {
$options = get_option( 'wccs_settings' );
if (  !empty($options['checkness']['wccs_opt_8'] ) )
unset($fields['billing']['billing_country']);
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fieldsCountry1' );
function wccs_override_required_fields11( $address_fields11 ) {
$options = get_option( 'wccs_settings' );
if (  !empty($options['checkness']['wccs_rq_11'] ) )
$address_fields11['billing_email']['required'] = false;
return $address_fields11;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fields11', 10, 1 );
function wccs_override_required_fieldsCountry( $fields ) {
$options = get_option( 'wccs_settings' );
if (  !empty($options['checkness']['wccs_rq_8'] ) )
$fields['billing_country']['required'] = false;
return $fields;
}
add_filter( 'woocommerce_billing_fields', 'wccs_override_required_fieldsCountry', 10, 1 );
// replace label and placeholder
function custom_replace_checkout_fields10( $fields ) {
$options = get_option( 'wccs_settings' );
if ( ! empty( $options['replace']['placeholder4'] ) )
$fields['billing']['billing_email']['placeholder'] = ''.$options['replace']['placeholder4'].'';
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fields10' );
function custom_replace_checkout_fieldsk( $fields ) {
$options = get_option( 'wccs_settings' );
if ( ! empty( $options['replace']['label4'] ) )
$fields['billing']['billing_email']['label'] = ''.$options['replace']['label4'].'';
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fieldsk' );
// ===================== Order Comments ==========================
function wccs_override_checkout_fields12($fields12) {
$options = get_option( 'wccs_settings' );
if (  !empty($options['checkness']['wccs_opt_12'] ) )
unset($fields12['order']['order_comments']);
return $fields12;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_override_checkout_fields12' );

function wccm_order_notes_additional_remove($fields) {
$options = get_option( 'wccs_settings' );
if (  !empty($options['checkness']['wccs_opt_12'] ) ){

unset($fields);
}
return $fields;
}
add_filter( 'woocommerce_enable_order_notes_field' , 'wccm_order_notes_additional_remove' );

function custom_replace_checkout_fields11( $fields ) {
$options = get_option( 'wccs_settings' );
if ( ! empty( $options['replace']['placeholder11'] ) )
$fields['order']['order_comments']['placeholder'] = ''.$options['replace']['placeholder11'].'';
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fields11' );
function custom_replace_checkout_fieldsl( $fields ) {
$options = get_option( 'wccs_settings' );
if ( ! empty( $options['replace']['label11'] ) )
$fields['order']['order_comments']['label'] = ''.$options['replace']['label11'].'';
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fieldsl' );

add_action( 'woocommerce_email_after_order_table', 'add_payment_method_to_new_order', 15 );
function add_payment_method_to_new_order( $order ) {
$options = get_option( 'wccs_settings' );
if ( $order->payment_method_title && $options['checkness']['payment_method_t'] == true ) {
echo '<p><strong>'.$options['checkness']['payment_method_d'].':</strong> ' . $order->payment_method_title . '</p>';
}
if ( $order->shipping_method_title && ($options['checkness']['shipping_method_t'] == true)) {
echo '<p><strong>'.$options['checkness']['shipping_method_d'].':</strong> ' . $order->shipping_method_title . '</p>';
}
if ( count( $options['buttons'] ) > 0 ) :
$i = 0;
// Loop through each button
foreach ( $options['buttons'] as $btn ) :

$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';

if ( (''.get_post_meta( $order->id , ''.$btn['label'].'', true).'' !== '') && !empty( $btn['label'] ) && empty($btn['deny_receipt'])) {
echo '<p><strong>'.$btn['label'].':</strong> '.nl2br(get_post_meta( $order->id , ''.$btn['label'].'', true)).'</p>';

}
$i++;
endforeach;
endif;
}
function wccs_add_title() {
$options = get_option( 'wccs_settings' );
if (!empty ($options['checkness']['checkbox12']) )
echo '<div class="add_info_wccs"><br><h3>' .$options['change']['add_info']. '</h3></div>';
}
function wccs_custom_checkout_field( $checkout ) {
$options = get_option( 'wccs_settings' );
if ( count( $options['buttons'] ) > 0 ) : ?>
<?php
$i = 0;

// Loop through each button
foreach ( $options['buttons'] as $btn ) :

$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';

?>
<?php

if ( ! empty( $btn['label'] ) &&  ($btn['type'] == 'text') ) {
woocommerce_form_field( ''.$btn['cow'].'' , array(
'type'          => 'text',
'class'         => array('wccs-field-class wccs-form-row-wide text '.$btn['conditional_tie'].''),
'label'         =>  __(''.$btn['label'].'', 'woocommerce-checkout-manager-pro' ),
'required'  => ''.$btn['checkbox'].'',
'placeholder'       => ''.$btn['placeholder'].'',

), $checkout->get_value( ''.$btn['cow'].'' ));
}

if ( ! empty( $btn['label'] ) &&  ($btn['type'] == 'textarea') ) {
woocommerce_form_field( ''.$btn['cow'].'' , array(
'type'          => 'textarea',
'class'         => array('wccs-field-class-textarea wccs-form-row-wide '.$btn['conditional_tie'].''),
'label'         =>  __(''.$btn['label'].'', 'woocommerce-checkout-manager-pro' ),
'required'  => ''.$btn['checkbox'].'',
'placeholder'       => ''.$btn['placeholder'].'',

), $checkout->get_value( ''.$btn['cow'].'' ));
}
if ( ! empty( $btn['label'] ) &&  ($btn['type'] == 'select') ) {
woocommerce_form_field( ''.$btn['cow'].'' , array(
'type'          => 'select',
'class'         => array('wccs-field-class wccs-form-row-wide select-options '.$btn['conditional_tie'].''),
'label'         =>  __(''.$btn['label'].'', 'woocommerce-checkout-manager-pro' ),
'options'     => array(
''.$btn['option_1'].'' => __(''.$btn['option_1'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_2'].'' => __(''.$btn['option_2'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_3'].'' => __(''.$btn['option_3'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_4'].'' => __(''.$btn['option_4'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_5'].'' => __(''.$btn['option_5'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_6'].'' => __(''.$btn['option_6'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_7'].'' => __(''.$btn['option_7'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_8'].'' => __(''.$btn['option_8'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_9'].'' => __(''.$btn['option_9'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_10'].'' => __(''.$btn['option_10'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_11'].'' => __(''.$btn['option_11'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_12'].'' => __(''.$btn['option_12'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_13'].'' => __(''.$btn['option_13'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_14'].'' => __(''.$btn['option_14'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_15'].'' => __(''.$btn['option_15'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_16'].'' => __(''.$btn['option_16'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_17'].'' => __(''.$btn['option_17'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_18'].'' => __(''.$btn['option_18'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_19'].'' => __(''.$btn['option_19'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_20'].'' => __(''.$btn['option_20'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_21'].'' => __(''.$btn['option_21'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_22'].'' => __(''.$btn['option_22'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_23'].'' => __(''.$btn['option_23'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_24'].'' => __(''.$btn['option_24'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_25'].'' => __(''.$btn['option_25'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_26'].'' => __(''.$btn['option_26'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_27'].'' => __(''.$btn['option_27'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_28'].'' => __(''.$btn['option_28'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_29'].'' => __(''.$btn['option_29'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_30'].'' => __(''.$btn['option_30'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_31'].'' => __(''.$btn['option_31'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_32'].'' => __(''.$btn['option_32'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_33'].'' => __(''.$btn['option_33'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_34'].'' => __(''.$btn['option_34'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_35'].'' => __(''.$btn['option_35'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_36'].'' => __(''.$btn['option_36'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_37'].'' => __(''.$btn['option_37'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_38'].'' => __(''.$btn['option_38'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_39'].'' => __(''.$btn['option_39'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_40'].'' => __(''.$btn['option_40'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_41'].'' => __(''.$btn['option_41'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_42'].'' => __(''.$btn['option_42'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_43'].'' => __(''.$btn['option_43'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_44'].'' => __(''.$btn['option_44'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_45'].'' => __(''.$btn['option_45'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_46'].'' => __(''.$btn['option_46'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_47'].'' => __(''.$btn['option_47'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_48'].'' => __(''.$btn['option_48'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_49'].'' => __(''.$btn['option_49'].'', 'woocommerce-checkout-manager-pro' ),
''.$btn['option_50'].'' => __(''.$btn['option_50'].'', 'woocommerce-checkout-manager-pro' )
),
'required'  => ''.$btn['checkbox'].'',
'placeholder'       => ''.$btn['placeholder'].'',

), $checkout->get_value( ''.$btn['cow'].'' ));
}
if ( ! empty( $btn['label'] ) &&  ($btn['type'] == 'colorpicker') ) {
?>
<p class="form-row wccs-field-class wccs-form-row-wide wccs_colorpicker <?php echo $btn['conditional_tie']; ?> <?php if ($btn['checkbox'] == 'true') { echo 'validate-required'; } ?>" id="<?php echo $btn['cow']; ?>_field">
<label for="<?php echo $btn['cow']; ?>" ><?php echo ''.__(''.$btn['label'].'','woocommerce-checkout-manager-pro').''; ?> <?php if ($btn['checkbox'] == 'true') { echo '<abbr class="required" title="required">*</abbr>';} ?></label>
<input class="input-text" type="text" maxlength="7" size="6" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_colorpicker" value="<?php echo $options['buttons'][$i]['colorpickerd']; ?>" />
</p>
<?php
}
if ( ! empty( $btn['label'] ) &&  ($btn['type'] == 'date') ) {
woocommerce_form_field( ''.$btn['cow'].'' , array(
'type'          => 'text',
'class'         => array('wccs-field-class MyDate'.$btn['cow'].' wccs-form-row-wide '.$btn['conditional_tie'].''),
'label'         =>  __(''.$btn['label'].'', 'woocommerce-checkout-manager-pro' ),
'required'  => ''.$btn['checkbox'].'',
'placeholder'       => ''.$btn['placeholder'].'',

), $checkout->get_value( ''.$btn['cow'].'' ));
}
if ( ! empty( $btn['label'] ) &&  ($btn['type'] == 'time') ) {
woocommerce_form_field( ''.$btn['cow'].'' , array(
'type'          => 'text',
'class'         => array('wccs-field-class MyTime'.$btn['cow'].' wccs-form-row-wide '.$btn['conditional_tie'].''),
'label'         =>  __(''.$btn['label'].'', 'woocommerce-checkout-manager-pro' ),
'required'  => ''.$btn['checkbox'].'',
'placeholder'       => ''.$btn['placeholder'].'',

), $checkout->get_value( ''.$btn['cow'].'' ));
}
if ( ! empty( $btn['label'] ) &&  ($btn['type'] == 'checkbox') ) {
?>
<p class="form-row wccs-field-class wccs-form-row-wide wccs_checkbox <?php echo $btn['conditional_tie']; ?> <?php if ($btn['checkbox'] == 'true') { echo 'validate-required'; } ?>" id="<?php echo $btn['cow']; ?>_field">
<input type="checkbox" class="input-checkbox" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_checkbox" value="<?php echo ''.__(''.$btn['check_2'].'','woocommerce-checkout-manager-pro').''; ?>" />

<input id="<?php echo $btn['cow']; ?>_checkboxhiddenfield" type='hidden' value="<?php echo ''.__(''.$btn['check_2'].'','woocommerce-checkout-manager-pro').''; ?>" name="<?php echo $btn['cow']; ?>">
<label for="<?php echo $btn['cow']; ?>" class="checkbox "><?php echo ''.__(''.$btn['label'].'','woocommerce-checkout-manager-pro').''; ?> <?php if ($btn['checkbox'] == 'true') { echo '<abbr class="required" title="required">*</abbr>';} ?></label>
</p>
<?php
}
if ( ! empty( $btn['label'] ) &&  ($btn['type'] == 'password') ) {
?>
<p class="form-row wccs-field-class wccs-form-row-wide wccs_password <?php echo $btn['conditional_tie']; ?> <?php if ($btn['checkbox'] == 'true') { echo 'validate-required'; } ?>" id="<?php echo $btn['cow']; ?>_field">
<label for="<?php echo $btn['cow']; ?>" ><?php echo ''.__(''.$btn['label'].'','woocommerce-checkout-manager-pro').''; ?> <?php if ($btn['checkbox'] == 'true') { echo '<abbr class="required" title="required">*</abbr>';} ?></label>
<input type="password" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="" />
</p>
<?php
}
if ( ! empty( $btn['label'] ) &&  ($btn['type'] == 'radio') ) {
?>
<p class="form-row wccs-field-class wccs-form-row-wide wccs_password <?php echo $btn['conditional_tie']; ?> <?php if ($btn['checkbox'] == 'true') { echo 'validate-required'; } ?>" id="<?php echo $btn['cow']; ?>_field">
<label for="<?php echo $btn['cow']; ?>" ><?php echo ''.__(''.$btn['label'].'','woocommerce-checkout-manager-pro').''; ?> <?php if ($btn['checkbox'] == 'true') { echo '<abbr class="required" title="required">*</abbr>';} ?></label>
<?php if ( !empty($btn['option_1']) ) { ?>
<span>
<input  style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_1']; ?>" /><?php echo ''.__(''.$btn['option_1'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_2']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_2']; ?>" /><?php echo ''.__(''.$btn['option_2'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_3']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_3']; ?>" /><?php echo ''.__(''.$btn['option_3'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_4']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_4']; ?>" /><?php echo ''.__(''.$btn['option_4'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_5']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_5']; ?>" /><?php echo ''.__(''.$btn['option_5'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_6']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_6']; ?>" /><?php echo ''.__(''.$btn['option_6'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_7']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_7']; ?>" /><?php echo ''.__(''.$btn['option_7'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_8']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_8']; ?>" /><?php echo ''.__(''.$btn['option_8'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_9']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_9']; ?>" /><?php echo ''.__(''.$btn['option_9'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_10']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_10']; ?>" /><?php echo ''.__(''.$btn['option_10'].'','woocommerce-checkout-manager-pro').''; ?></span>
<?php } ?>
<?php if ( !empty($btn['option_11']) ) { ?>
<span>
<input  style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_11']; ?>" /><?php echo ''.__(''.$btn['option_11'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_12']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_12']; ?>" /><?php echo ''.__(''.$btn['option_12'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_13']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_13']; ?>" /><?php echo ''.__(''.$btn['option_13'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_14']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_14']; ?>" /><?php echo ''.__(''.$btn['option_14'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_15']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_15']; ?>" /><?php echo ''.__(''.$btn['option_15'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_16']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_16']; ?>" /><?php echo ''.__(''.$btn['option_16'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_17']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_17']; ?>" /><?php echo ''.__(''.$btn['option_17'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_18']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_18']; ?>" /><?php echo ''.__(''.$btn['option_18'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_19']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_19']; ?>" /><?php echo ''.__(''.$btn['option_19'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_20']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_20']; ?>" /><?php echo ''.__(''.$btn['option_20'].'','woocommerce-checkout-manager-pro').''; ?></span>
<?php } ?>
<?php if ( !empty($btn['option_21']) ) { ?>
<span>
<input  style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_21']; ?>" /><?php echo ''.__(''.$btn['option_21'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_22']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_22']; ?>" /><?php echo ''.__(''.$btn['option_22'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_23']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_23']; ?>" /><?php echo ''.__(''.$btn['option_23'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_24']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_24']; ?>" /><?php echo ''.__(''.$btn['option_24'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_25']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_25']; ?>" /><?php echo ''.__(''.$btn['option_25'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_26']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_26']; ?>" /><?php echo ''.__(''.$btn['option_26'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_27']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_27']; ?>" /><?php echo ''.__(''.$btn['option_27'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_28']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_28']; ?>" /><?php echo ''.__(''.$btn['option_28'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_29']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_29']; ?>" /><?php echo ''.__(''.$btn['option_29'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_30']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_30']; ?>" /><?php echo ''.__(''.$btn['option_30'].'','woocommerce-checkout-manager-pro').''; ?></span>
<?php } ?>
<?php if ( !empty($btn['option_31']) ) { ?>
<span>
<input  style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_31']; ?>" /><?php echo ''.__(''.$btn['option_31'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_32']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_32']; ?>" /><?php echo ''.__(''.$btn['option_32'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_33']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_33']; ?>" /><?php echo ''.__(''.$btn['option_33'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_34']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_34']; ?>" /><?php echo ''.__(''.$btn['option_34'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_35']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_35']; ?>" /><?php echo ''.__(''.$btn['option_35'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_36']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_36']; ?>" /><?php echo ''.__(''.$btn['option_36'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_37']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_37']; ?>" /><?php echo ''.__(''.$btn['option_37'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_38']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_38']; ?>" /><?php echo ''.__(''.$btn['option_38'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_39']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_39']; ?>" /><?php echo ''.__(''.$btn['option_39'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_40']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_40']; ?>" /><?php echo ''.__(''.$btn['option_40'].'','woocommerce-checkout-manager-pro').''; ?></span>
<?php } ?>
<?php if ( !empty($btn['option_41']) ) { ?>
<span>
<input  style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_41']; ?>" /><?php echo ''.__(''.$btn['option_41'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_42']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_42']; ?>" /><?php echo ''.__(''.$btn['option_42'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_43']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_43']; ?>" /><?php echo ''.__(''.$btn['option_43'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_44']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_44']; ?>" /><?php echo ''.__(''.$btn['option_44'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_45']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_45']; ?>" /><?php echo ''.__(''.$btn['option_45'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_46']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_46']; ?>" /><?php echo ''.__(''.$btn['option_46'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_47']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_47']; ?>" /><?php echo ''.__(''.$btn['option_47'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_48']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_48']; ?>" /><?php echo ''.__(''.$btn['option_48'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_49']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_49']; ?>" /><?php echo ''.__(''.$btn['option_49'].'','woocommerce-checkout-manager-pro').''; ?></span><br />
<?php } ?>
<?php if ( !empty($btn['option_50']) ) { ?>
<span>
<input style="width: 20%;" type="radio" class="input-text" name="<?php echo $btn['cow']; ?>" id="<?php echo $btn['cow']; ?>_password" value="<?php echo $btn['option_50']; ?>" /><?php echo ''.__(''.$btn['option_50'].'','woocommerce-checkout-manager-pro').''; ?></span>
<?php } ?>
</p>
<?php
}
?>
<?php
$i++;
endforeach;
?>
<?php
endif;
}
if ( wccs_positioning_wrkb1() ) {
add_action('woocommerce_before_checkout_shipping_form', 'wccs_add_title');
add_action('woocommerce_before_checkout_shipping_form', 'wccs_custom_checkout_field');
add_action('woocommerce_before_checkout_shipping_form', 'scripts_enhanced');
}
if ( wccs_positioning_wrk1() ) {
add_action('woocommerce_after_checkout_shipping_form', 'wccs_add_title');
add_action('woocommerce_after_checkout_shipping_form', 'wccs_custom_checkout_field');
add_action('woocommerce_after_checkout_shipping_form', 'scripts_enhanced');
}
if ( wccs_positioning_wrkb2() ) {
add_action('woocommerce_before_checkout_billing_form', 'wccs_add_title');
add_action('woocommerce_before_checkout_billing_form', 'wccs_custom_checkout_field');
add_action('woocommerce_before_checkout_billing_form', 'scripts_enhanced');
}
if ( wccs_positioning_wrk2() ) {
add_action('woocommerce_after_checkout_billing_form', 'wccs_add_title');
add_action('woocommerce_after_checkout_billing_form', 'wccs_custom_checkout_field');
add_action('woocommerce_after_checkout_billing_form', 'scripts_enhanced');
}
if ( wccs_positioning_wrk3() ) {
add_action('woocommerce_after_order_notes', 'wccs_add_title');
add_action('woocommerce_after_order_notes', 'wccs_custom_checkout_field');
add_action('woocommerce_after_order_notes', 'scripts_enhanced');
}
function wccs_positioning_wrkb1() {
$options = get_option( 'wccs_settings' );
if ( !empty($options['checkness']['beforeshipping']) ) {
return true;
} else {
return false;
}}
function wccs_positioning_wrk1() {
$options = get_option( 'wccs_settings' );
if ( !empty($options['checkness']['position1']) ) {
return true;
} else {
return false;
}}
function wccs_positioning_wrkb2() {
$options = get_option( 'wccs_settings' );
if ( !empty($options['checkness']['beforebilling']) ) {
return true;
} else {
return false;
}}
function wccs_positioning_wrk2() {
$options = get_option( 'wccs_settings' );
if ( !empty($options['checkness']['position2']) ) {
return true;
} else {
return false;
}}
function wccs_positioning_wrk3() {
$options = get_option( 'wccs_settings' );
if ( !empty($options['checkness']['position3']) ) {
return true;
} else {
return false;
}}
function wccs_custom_checkout_field_update_order_meta( $order ) {
$options = get_option( 'wccs_settings' );
if ( count( $options['buttons'] ) > 0 ) :
$i = 0;
// Loop through each button
foreach ( $options['buttons'] as $btn ) :

$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';
if ( ! empty( $btn['label'] ) )
if ( $_POST[ ''.$btn['cow'].'' ])
update_post_meta( $order, ''.$btn['label'].'' , esc_attr( $_POST[ ''.$btn['cow'].'' ] ));
$i++;
endforeach;
endif;
}
add_action('woocommerce_checkout_update_order_meta', 'wccs_custom_checkout_field_update_order_meta');
add_action('woocommerce_email_after_order_table', 'wccs_custom_style_checkout_email');
function wccs_custom_style_checkout_email() {
$options = get_option( 'wccs_settings' );
if (!empty ($options['checkness']['checkbox1']) )
echo '<h2>' .$options['change']['add_info']. '</h2>';
}
function wccs_custom_checkout_field_process() {
$options = get_option( 'wccs_settings' );
if ( count( $options['buttons'] ) > 0 ) :
$i = 0;
// Loop through each button
foreach ( $options['buttons'] as $btn ) :

$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';
global $woocommerce;

if ( empty($btn['single_px_cat']) && empty($btn['single_p_cat']) && empty($btn['single_px']) && empty($btn['single_p']) && !empty ($btn['checkbox']) && !empty( $btn['label'] ) && $btn['type'] !== 'changename'  ) {
if (!$_POST[''.$btn['cow'].''] ) {
$woocommerce->add_error( '<strong>'.$btn['label'].'</strong> '. __('is a required field.', 'woocommerce-checkout-manager-pro' ) . ' ');
}}
if ( empty($btn['single_px_cat']) && empty($btn['single_p_cat']) && empty($btn['single_px']) && empty($btn['single_p']) && $btn['type'] == 'checkbox' && !empty( $btn['label'] ) && $btn['type'] !== 'changename'  ) {
if ( ($_POST[ ''.$btn['cow'].'' ] == ''.$btn['check_2'].'')  && (!empty ($btn['checkbox']) ) ) {
$woocommerce->add_error( '<strong>'.$btn['label'].'</strong> '. __('is a required field.', 'woocommerce-checkout-manager-pro' ) . ' ');
}}
$i++;
endforeach;


endif;
}
add_action('woocommerce_checkout_process', 'wccs_custom_checkout_field_process');
function wccs_options_validatew() {
$options = get_option( 'wccs_settings' );

if ( empty( $options['replace']['label'] ) )
unset( $options['replace']['label'] );
}



function wccs_options_validate( $input ) {
$options = get_option( 'wccs_settings' );

if( empty($options) ) {

            $type = 'updated';
            $message = __( 'Settings Successfully Reset.', 'woocommerce-checkout-manager-pro' );

        } else {

            $type = 'updated';
            $message = __( 'Settings Successfully Updated.', 'woocommerce-checkout-manager-pro' );

        }

    add_settings_error(
        'wccs_settings_error',
        esc_attr( 'settings_updated' ),
        $message,
        $type
    );

foreach( $input['buttons'] as $i => $btn ) :
if ( empty( $btn['label'] ) )
unset( $input['buttons'][$i], $btn );
endforeach;

$input['buttons'] = array_values( $input['buttons'] );
return $input;
}




function wccs_custom_checkout_details( $order) {
$options = get_option( 'wccs_settings' );
if (!empty ($options['checkness']['checkbox1']) )  {
echo '<h2>' .$options['change']['add_info']. '</h2>';
}
if ( count( $options['buttons'] ) > 0 ) :
$i = 0;
// Loop through each button
foreach ( $options['buttons'] as $btn ) :
$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';

if (  (''.get_post_meta( $order->id , ''.$btn['label'].'', true).'' !== '') && !empty( $btn['label'] ) && empty( $btn['deny_receipt'] )) {
echo '<dl><dt>'.$btn['label'].':</dt> <dd>'.nl2br(get_post_meta( $order->id , ''.$btn['label'].'', true)).'</dd></dl>';
}
$i++;
endforeach;
endif;
}
add_action('woocommerce_order_details_after_order_table', 'wccs_custom_checkout_details');
function wccs_shipping_checkout_fields1( $fields1 ) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['check']['wccs_opt_1_s'] ) )
unset($fields1['shipping']['shipping_first_name']);
return $fields1;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_shipping_checkout_fields1' );
function wccs_shipping_required_fields1( $address_fields1 ) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['check']['wccs_rq_1_s'] ) )
$address_fields1['shipping_first_name']['required'] = false;
return $address_fields1;
}
add_filter( 'woocommerce_shipping_fields', 'wccs_shipping_required_fields1', 10, 1 );
add_action('woocommerce_checkout_after_customer_details','wccm_checkout_text_after');
function wccm_checkout_text_after(){
$options = get_option( 'wccs_settings' );
if ( !empty($options['notice']['text2']) ) {
if ( $options['notice']['checkbox3'] == true || $options['notice']['checkbox4'] == true ) {
if ( $options['notice']['checkbox4'] == true ) {
echo ''.$options['notice']['text2'].'';
}}}
if ( !empty($options['notice']['text1']) ) {
if ( $options['notice']['checkbox1'] == true || $options['notice']['checkbox2'] == true ) {
if ( $options['notice']['checkbox2'] == true ) {
echo ''.$options['notice']['text1'].'';
}}}
}
add_action('woocommerce_checkout_before_customer_details','wccm_checkout_text_before');
function wccm_checkout_text_before(){
$options = get_option( 'wccs_settings' );
if ( !empty($options['notice']['text2']) ) {
if ( $options['notice']['checkbox3'] == true || $options['notice']['checkbox4'] == true ) {
if ( $options['notice']['checkbox3'] == true ) {
echo ''.$options['notice']['text2'].'';
}}}
if ( !empty($options['notice']['text1']) ) {
if ( $options['notice']['checkbox1'] == true || $options['notice']['checkbox2'] == true ) {
if ( $options['notice']['checkbox1'] == true ) {
echo ''.$options['notice']['text1'].'';
}}}
}
// replace label and placeholder
function custom_replace_checkout_fieldss( $fields ) {
$options = get_option( 'wccs_settings' );
if ( ! empty( $options['replace']['placeholder_s'] ) )
$fields['shipping']['shipping_first_name']['placeholder'] = ''.$options['replace']['placeholder_s'].'';
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fieldss' );
function custom_replace_checkout_fieldssa( $fields ) {
$options = get_option( 'wccs_settings' );
if ( ! empty( $options['replace']['label_s'] ) )
$fields['shipping']['shipping_first_name']['label'] = ''.$options['replace']['label_s'].'';
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fieldssa' );
function wccs_shipping_checkout_fields2( $fields2 ) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['check']['wccs_opt_2_s'] ) )
unset($fields2['shipping']['shipping_last_name']);
return $fields2;
}
add_filter( 'woocommerce_checkout_fields' , 'wccs_shipping_checkout_fields2' );
function wccs_shipping_required_fields2( $address_fields2 ) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['check']['wccs_rq_2_s'] ) )
$address_fields2['shipping_last_name']['required'] = false;
return $address_fields2;
}
add_filter( 'woocommerce_shipping_fields', 'wccs_shipping_required_fields2', 10, 1 );
function delta_wccs_custom_checkout_details( $order ) {
$options = get_option( 'wccs_settings' );
global $post;
if ( count( $options['buttons'] ) > 0 ) :
$i = 0;
// Loop through each button
foreach ( $options['buttons'] as $btn ) :
$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';

if ( (''.get_post_meta( $order->id , ''.$btn['label'].'', true).'' !== '') && !empty( $btn['label'] ) ) {
echo '<p><strong>'.$btn['label'].':</strong> '.nl2br(get_post_meta( $order->id , ''.$btn['label'].'', true)).'</p>';
}

$i++;
endforeach;
endif;
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'delta_wccs_custom_checkout_details', 10, 1 );
// replace label and placeholder
function custom_replace_checkout_fieldss1( $fields ) {
$options = get_option( 'wccs_settings' );
if ( ! empty( $options['replace']['placeholder_s1'] ) )
$fields['shipping']['shipping_last_name']['placeholder'] = ''.$options['replace']['placeholder_s1'].'';
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fieldss1' );
function custom_replace_checkout_fieldssb( $fields ) {
$options = get_option( 'wccs_settings' );
if ( ! empty( $options['replace']['label_s1'] ) )
$fields['shipping']['shipping_last_name']['label'] = ''.$options['replace']['label_s1'].'';
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fieldssb' );
function custom_replace_checkout_fieldss2( $fields ) {
$options = get_option( 'wccs_settings' );
if ( ! empty( $options['replace']['placeholder_s2'] ) )
$fields['shipping']['shipping_company']['placeholder'] = ''.$options['replace']['placeholder_s2'].'';
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fieldss2' );

add_filter('woocommerce_checkout_fields','remove_fields_filter',15);
function remove_fields_filter($fields){
global $woocommerce;
foreach ($woocommerce->cart->cart_contents as $key => $values ) {
$options = get_option( 'wccs_settings' );
$multiCategoriesx= $options['option']['productssave'];
// move into usable array
$multiCategoriesArrayx=explode(',',$multiCategoriesx);
if(in_array($values['product_id'],$multiCategoriesArrayx) && ($woocommerce->cart->cart_contents_count < 2) ){
unset($fields['billing']['billing_address_1']);
unset($fields['billing']['billing_address_2']);
unset($fields['billing']['billing_phone']);
unset($fields['billing']['billing_country']);
unset($fields['billing']['billing_city']);
unset($fields['billing']['billing_postcode']);
unset($fields['billing']['billing_state']);
break;
}}
return $fields;
}

add_filter('woocommerce_checkout_fields','remove_fields_filter3',1);
function remove_fields_filter3($fields){
global $woocommerce;
foreach ($woocommerce->cart->cart_contents as $key => $values ) {

$options = get_option( 'wccs_settings' );
$multiCategoriesx= $options['option']['productssave'];
// move into usable array
$multiCategoriesArrayx=explode(',',$multiCategoriesx);
$_product = $values['data'];
if( ($woocommerce->cart->cart_contents_count > 1) && ($_product->needs_shipping()) ){
remove_filter('woocommerce_checkout_fields','remove_fields_filter',15);
break;
}}
return $fields;
}
function custom_replace_checkout_fieldssc( $fields ) {
$options = get_option( 'wccs_settings' );
if ( ! empty( $options['replace']['label_s2'] ) ) 
     $fields['shipping']['shipping_company']['label'] = ''.$options['replace']['label_s2'].'';
     return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_replace_checkout_fieldssc' );
add_filter( 'woocommerce_checkout_fields' , 'b1_custom_override_checkout_fields' );
function b1_custom_override_checkout_fields( $fields ) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['check']['wccs_opt_4_s'] ) )
unset($fields['shipping']['shipping_address_1']);

return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'b2_custom_override_checkout_fields' );
function b2_custom_override_checkout_fields( $fields ) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['check']['wccs_opt_5_s'] ) )
unset($fields['shipping']['shipping_address_2']);
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'c_custom_override_checkout_fields' );
function c_custom_override_checkout_fields( $fields ) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['check']['wccs_opt_6_s'] ) )
unset($fields['shipping']['shipping_city']);
return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'sp_custom_override_checkout_fields' );
function sp_custom_override_checkout_fields( $fields ) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['check']['wccs_opt_7_s'] ) )
unset($fields['shipping']['shipping_postcode']);
return $fields;
}
if ( validator_changename() ) {
add_action('woocommerce_before_cart', 'wccm_before_checkout');
add_action('woocommerce_admin_order_data_after_order_details', 'wccm_before_checkout');
add_action('woocommerce_before_my_account', 'wccm_before_checkout');
add_action('woocommerce_email_header', 'wccm_before_checkout');
add_action('woocommerce_before_checkout_form', 'wccm_before_checkout');
function wccm_before_checkout() {
$options = get_option( 'wccs_settings' );
if ( count( $options['buttons'] ) > 0 ) :
$i = 0;
// Loop through each button
foreach ( $options['buttons'] as $btn ) :
$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';
ob_start();
$i++;
endforeach;
endif;
}
add_action('woocommerce_after_cart', 'wccm_after_checkout');
add_action('woocommerce_admin_order_data_after_shipping_address', 'wccm_after_checkout');
add_action('woocommerce_after_my_account', 'wccm_after_checkout');
add_action('woocommerce_email_footer', 'wccm_after_checkout');
add_action('woocommerce_after_checkout_form', 'wccm_after_checkout');
function wccm_after_checkout() {
$options = get_option( 'wccs_settings' );
if ( count( $options['buttons'] ) > 0 ) :
$i = 0;
// Loop through each button
foreach ( $options['buttons'] as $btn ) :
$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';

if ( $btn['type'] == 'changename' ) {
$content = ob_get_clean();
echo str_replace( ''.$btn['changenamep'].'', ''.$btn['changename'].'', $content);

}
$i++;
endforeach;
endif;
}}

add_filter( 'woocommerce_checkout_fields' , 'ss_custom_override_checkout_fields' );
function ss_custom_override_checkout_fields( $fields ) {
$options = get_option( 'wccs_settings' );
if (  !empty ($options['check']['wccs_opt_9_s'] ) )
unset($fields['shipping']['shipping_state']);

return $fields;
}
function display_front_wccs() {
$options = get_option( 'wccs_settings' );
echo '<style type="text/css">
'.$options['option']['custom_css_w'].'
.wccs-field-class-textarea {
float: none;
width: 100%;
clear: both;
}
.wccs_colorpicker input {
border-radius: 12px;
max-width: 35%;
}
.spec_shootd {
margin-left: 17%;
}
.wccs-field-class {
float:left;
width: 47%;
}
.add_info_wccs {
clear: both;
width: 100%;
}
</style>';
}
add_action('wp_head','display_front_wccs');



function scripts_enhanced() {
$options = get_option( 'wccs_settings' );
global $woocommerce;
if ( count( $options['buttons'] ) > 0 ) :
$i = 0;
// Loop through each button
foreach ( $options['buttons'] as $btn ) :
$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';


if ( $btn['type'] == 'date' ) {
echo '<script type="text/javascript">
jQuery(document).ready(function() {
var today = new Date();
jQuery("input#'.$btn['cow'].'").datepicker({';
if ( empty($btn['format_date']) ) {
echo 'dateFormat : "dd-mm-yy",'; }
if ( !empty($btn['format_date']) ) {
echo 'dateFormat : "'.str_replace( ' ', '', $btn['format_date'] ).'",'; }
if ( !empty($btn['single_yy']) ) {
echo 'minDate: new Date( '.$btn['single_yy'].', '.$btn['single_mm'].' - 1, '.$btn['single_dd'].'),';
}
if ( !empty($btn['min_before']) ) {
echo 'minDate: -'.$btn['min_before'].',';
}
if ( !empty($btn['single_max_yy']) ) {
echo 'maxDate: new Date( '.$btn['single_max_yy'].', '.$btn['single_max_mm'].' - 1, '.$btn['single_max_dd'].'),';
}
if ( !empty($btn['max_after']) ) {
echo 'maxDate: '.$btn['max_after'].',';
}
if ( !empty($btn['days_disabler']) ) {
echo 'beforeShowDay: function(date) { var day = date.getDay(); return [(';

if ( !empty($btn['days_disabler0']) ) {
echo 'day == '.str_replace('1','',$btn['days_disabler0']).'';
}else { echo 'day == "x"'; }
if ( !empty($btn['days_disabler1']) ) {
echo ' || day == '.$btn['days_disabler1'].'';
}
if ( !empty($btn['days_disabler2']) ) {
echo ' || day == '.$btn['days_disabler2'].'';
}
if ( !empty($btn['days_disabler3']) ) {
echo ' || day == '.$btn['days_disabler3'].'';
}
if ( !empty($btn['days_disabler4']) ) {
echo ' || day == '.$btn['days_disabler4'].'';
}
if ( !empty($btn['days_disabler5']) ) {
echo ' || day == '.$btn['days_disabler5'].'';
}
if ( !empty($btn['days_disabler6']) ) {
echo '|| day == '.$btn['days_disabler6'].'';
}

echo ')]; }';
}
echo '});
});
</script>';
}

// http://fgelinas.com/code/timepicker/
if ( $btn['type'] == 'time' ) {
echo '<script type="text/javascript">
jQuery(document).ready(function() {
jQuery("#'.$btn['cow'].'_field input#'.$btn['cow'].'").timepicker({
showPeriod: true,
showLeadingZero: true
});
});
</script>';
}

if ( $btn['type'] == 'password' ) {
echo '<script type="text/javascript">
jQuery(document).ready(function() {
jQuery("p#'.$btn['cow'].'_field").css("display");
});
</script>';
}


if ( $btn['type'] == 'select' ) {
?>
<script type="text/javascript">
jQuery(document).ready(function() {
jQuery('select#<?php echo $btn['cow']; ?> option[value=""]').remove();
jQuery('select#<?php echo $btn['cow']; ?>').append('<option value="" selected="selected" ><?php echo $btn['force_title2']; ?></option>');
});
</script>
<?php
}

if ( $btn['type'] == 'radio' ) {
?>
<script type="text/javascript">
jQuery(document).ready(function() {
var a = '';
jQuery("input[type=radio][value=" + a + "]").remove();
});
</script>
<?php
}

if ( ! empty( $btn['label'] ) && $btn['type'] == 'colorpicker' ) {
?>
<div id="<?php echo $btn['cow']; ?>_colorpickerdiv" class="spec_shootd"></div>
<script type="text/javascript">
jQuery(document).ready(function($) {
jQuery('#<?php echo $btn['cow']; ?>_colorpickerdiv').hide();
jQuery('#<?php echo $btn['cow']; ?>_colorpickerdiv').farbtastic("#<?php echo $btn['cow']; ?>_colorpicker");
jQuery("#<?php echo $btn['cow']; ?>_colorpicker").click(function(){jQuery('#<?php echo $btn['cow']; ?>_colorpickerdiv').slideToggle()});

});
</script>
<?php
}


// ============================== radio button & checkbox ===========================================
if ( ($btn['type'] == 'radio' || $btn['type'] == 'checkbox') && !empty( $btn['tax_remove'] ) ) { 
?>
<script type="text/javascript">
jQuery(document).ready(function($) {

jQuery('#<?php echo $btn['cow']; ?>_field input').click(function() {

jQuery('form.checkout').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6}});

jQuery('form.checkout').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', opacity: 0.6}});

	var ajaxurl = '<?php echo admin_url('/admin-ajax.php'); ?>';
		data = {
		action: 'remove_tax_wccm',
		tax_remove_aj: jQuery('#<?php echo $btn['cow']; ?>_field input[name=<?php echo $btn['cow']; ?>]:checked').val()     // We pass php values differently!
	};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.post(ajaxurl, data, function(response) {

$.ajax({
   url: '<?php echo $woocommerce->cart->get_checkout_url(); ?>',
   data: {},
   success: function (data) {
      $("#order_review").html($(data).find("#order_review"));
	jQuery('form.checkout').unblock();
   },
   dataType: 'html'
});
<!--        $("#order_review").load("<?php echo $woocommerce->cart->get_checkout_url(); ?> #order_review"); -->
	
		
	});

}); });
</script>
<?php
}

if ( ($btn['type'] == 'radio' || $btn['type'] == 'checkbox') && !empty( $btn['add_amount'] ) && !empty( $btn['fee_name'] ) && !empty( $btn['add_amount_field'] ) ) { 
?>
<script type="text/javascript">
jQuery(document).ready(function($) {

jQuery('#<?php echo $btn['cow']; ?>_field input').click(function() {

jQuery('form.checkout').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6}});

jQuery('form.checkout').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', opacity: 0.6}});

	var ajaxurl = '<?php echo admin_url('/admin-ajax.php'); ?>';
		data = {
		action: 'remove_tax_wccm',
		add_amount_aj: jQuery('#<?php echo $btn['cow']; ?>_field input[name=<?php echo $btn['cow']; ?>]:checked').val()     // We pass php values differently!
	};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.post(ajaxurl, data, function(response) {

$.ajax({
   url: '<?php echo $woocommerce->cart->get_checkout_url(); ?>',
   data: {},
   success: function (data) {
      $("#order_review").html($(data).find("#order_review"));
	jQuery('form.checkout').unblock();
   },
   dataType: 'html'
});
<!--        $("#order_review").load("<?php echo $woocommerce->cart->get_checkout_url(); ?> #order_review"); -->
	
		
	});

}); });
</script>
<?php
}


// =========================================== select options =========================================
if ( ($btn['type'] == 'select') && !empty( $btn['tax_remove'] ) ) { 
?>
<script type="text/javascript">
jQuery(document).ready(function($) {

jQuery('#<?php echo $btn['cow']; ?>_field select').change(function() {

jQuery('form.checkout').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6}});

jQuery('form.checkout').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', opacity: 0.6}});

	var ajaxurl = '<?php echo admin_url('/admin-ajax.php'); ?>';
		data = {
		action: 'remove_tax_wccm',
		tax_remove_aj: jQuery('#<?php echo $btn['cow']; ?> option:selected').val()     

	};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.post(ajaxurl, data, function(response) {

$.ajax({
   url: '<?php echo $woocommerce->cart->get_checkout_url(); ?>',
   data: {},
   success: function (data) {
      $("#order_review").html($(data).find("#order_review"));
	jQuery('form.checkout').unblock();
   },
   dataType: 'html'
});
<!--        $("#order_review").load("<?php echo $woocommerce->cart->get_checkout_url(); ?> #order_review"); -->
	
		
	});

}); });
</script>
<?php
}

if ( ($btn['type'] == 'select') && !empty( $btn['add_amount'] ) && !empty( $btn['fee_name'] ) && !empty( $btn['add_amount_field'] ) ) { 
?>
<script type="text/javascript">
jQuery(document).ready(function($) {

jQuery('#<?php echo $btn['cow']; ?>_field select').change(function() {

jQuery('form.checkout').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6}});

jQuery('form.checkout').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', opacity: 0.6}});

	var ajaxurl = '<?php echo admin_url('/admin-ajax.php'); ?>';
		data = {
		action: 'remove_tax_wccm',
		add_amount_aj: jQuery('#<?php echo $btn['cow']; ?> option:selected').val()     
	};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.post(ajaxurl, data, function(response) {

$.ajax({
   url: '<?php echo $woocommerce->cart->get_checkout_url(); ?>',
   data: {},
   success: function (data) {
      $("#order_review").html($(data).find("#order_review"));
	jQuery('form.checkout').unblock();
   },
   dataType: 'html'
});
<!--        $("#order_review").load("<?php echo $woocommerce->cart->get_checkout_url(); ?> #order_review"); -->
	
		
	});

}); });
</script>
<?php
}

// =========================================== add apply button ==========================================

if ( ($btn['type'] == 'text') && !empty( $btn['add_amount'] ) && !empty( $btn['fee_name'] ) && empty( $btn['add_amount_field'] ) ) { ?>
<script type="text/javascript">
jQuery(document).ready(function() {
jQuery( "#<?php echo $btn['cow']; ?>_field" ).append( '<span id="<?php echo $btn['cow']; ?>_applynow"><?php _e('Apply','woocommerce-checkout-manager-pro'); ?></span>' );
});

jQuery(document).ready(function($) {

jQuery('#<?php echo $btn['cow']; ?>_field #<?php echo $btn['cow']; ?>_applynow').click(function() {

jQuery('form.checkout').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6}});

jQuery('form.checkout').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', opacity: 0.6}});

	var ajaxurl = '<?php echo admin_url('/admin-ajax.php'); ?>';
		data = {
		action: 'remove_tax_wccm',
		add_amount_faj: jQuery('input#<?php echo $btn['cow']; ?>').val()     
	};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.post(ajaxurl, data, function(response) {

$.ajax({
   url: '<?php echo $woocommerce->cart->get_checkout_url(); ?>',
   data: {},
   success: function (data) {
      $("#order_review").html($(data).find("#order_review"));
	jQuery('form.checkout').unblock();
   },
   dataType: 'html'
});
<!--        $("#order_review").load("<?php echo $woocommerce->cart->get_checkout_url(); ?> #order_review"); -->
			
	});

}); });
</script>
<?php
}

// =====================================================

if ( $btn['type'] == 'checkbox' ) {
?>
<script type="text/javascript">
jQuery(document).ready(function() {
jQuery('#<?php echo $btn['cow']; ?>_checkbox').change(function(){
if(jQuery(this).attr('checked')){
jQuery(this).val('<?php echo ''.__(''.$btn['check_1'].'','woocommerce-checkout-manager-pro').''; ?>');
jQuery("#<?php echo $btn['cow']; ?>_checkboxhiddenfield").prop("disabled", true);
}else{
jQuery(this).val('<?php echo ''.__(''.$btn['check_2'].'','woocommerce-checkout-manager-pro').''; ?>');
jQuery("#<?php echo $btn['cow']; ?>_checkboxhiddenfield").prop("disabled", false);
}
});
});
</script>
<?php
}

$i++;
endforeach;
endif;
}
function required_fields_override1( $fields ) {
$options = get_option( 'wccs_settings' );
if ( !empty($options['checkness']['wccs_rq_4']) )
$fields['billing_address_1']['required'] = false;
return $fields;
}
add_filter( 'woocommerce_billing_fields', 'required_fields_override1' );

function required_fields_override2( $fields ) {
$options = get_option( 'wccs_settings' );
if ( !empty($options['checkness']['wccs_rq_5']) )
$fields['billing_address_2']['required'] = false;
return $fields;
}
add_filter( 'woocommerce_billing_fields', 'required_fields_override2' );

function required_fields_override3( $fields ) {
$options = get_option( 'wccs_settings' );
if ( !empty($options['checkness']['wccs_rq_6']) )
$fields['billing_city']['required'] = false;
return $fields;
}
add_filter( 'woocommerce_billing_fields', 'required_fields_override3' );

function required_fields_override4( $fields ) {
$options = get_option( 'wccs_settings' );
if ( !empty($options['checkness']['wccs_rq_7']) )
$fields['billing_postcode']['required'] = false;
return $fields;
}
add_filter( 'woocommerce_billing_fields', 'required_fields_override4' );

function required_fields_override5( $fields ) {
$options = get_option( 'wccs_settings' );
if ( !empty($options['checkness']['wccs_rq_9']) )
$fields['billing_state']['required'] = false;
return $fields;
}
add_filter( 'woocommerce_billing_fields', 'required_fields_override5' );

// clear -----------------------------------------
function required_fields_override1_s( $fields ) {
$options = get_option( 'wccs_settings' );
if ( !empty($options['check']['wccs_rq_4_s']) )
$fields['shipping_address_1']['required'] = false;
return $fields;
}
add_filter( 'woocommerce_shipping_fields', 'required_fields_override1_s' );

function required_fields_override2_s( $fields ) {
$options = get_option( 'wccs_settings' );
if ( !empty($options['check']['wccs_rq_5_s']) )
$fields['shipping_address_2']['required'] = false;
return $fields;
}
add_filter( 'woocommerce_shipping_fields', 'required_fields_override2_s' );


// --------------------------------------------------------
add_action('run_color_innerpicker','run_color_inner');
function run_color_inner() {
global $wpdb, $post;
$options = get_option( 'wccs_settings' );
wp_enqueue_style( 'farbtastic' );
wp_enqueue_script( 'farbtastic', site_url('/wp-admin/js/farbtastic.js') );
?>
<?php
if ( count( $options['buttons'] ) > 0 ) {
$i = 0;
// Loop through each button
foreach ( $options['buttons'] as $btn ) {
?>
<div id="colorpickerdiv<?php echo $i; ?>" style="position: absolute;top: 41%;left: 46%;"></div>
<script type="text/javascript">
jQuery(document).ready(function($) {
jQuery('#colorpickerdiv<?php echo $i; ?>').hide();
jQuery('#colorpickerdiv<?php echo $i; ?>').farbtastic("#colorpic<?php echo $i; ?>");
jQuery("#colorpic<?php echo $i; ?>").click(function(){jQuery('#colorpickerdiv<?php echo $i; ?>').slideToggle()});
});
</script>
<?php
$i++;
}}
?>
<?php
}
// -----------------------------------------------------------

function required_fields_override3_s( $fields ) {
$options = get_option( 'wccs_settings' );
if ( !empty($options['check']['wccs_rq_6_s']) )
$fields['shipping_city']['required'] = false;
return $fields;
}
add_filter( 'woocommerce_shipping_fields', 'required_fields_override3_s' );

function required_fields_override4_s( $fields ) {
$options = get_option( 'wccs_settings' );
if ( !empty($options['check']['wccs_rq_7_s']) )
$fields['shipping_postcode']['required'] = false;
return $fields;
}
add_filter( 'woocommerce_shipping_fields', 'required_fields_override4_s' );

function required_fields_override5_s( $fields ) {
$options = get_option( 'wccs_settings' );
if ( !empty($options['check']['wccs_rq_9_s']) )
$fields['shipping_state']['required'] = false;
return $fields;
}
add_filter( 'woocommerce_shipping_fields', 'required_fields_override5_s' );
// ---------------------------------------------------------------------------------
add_action('woocommerce_before_checkout_billing_form', 'echo_override_style');
function echo_override_style() {
$options = get_option( 'wccs_settings' );
if ( !empty($options['checkness']['wccs_rq_4']) || !empty($options['checkness']['wccs_rq_5']) || !empty($options['checkness']['wccs_rq_6']) || !empty($options['checkness']['wccs_rq_7']) || !empty($options['checkness']['wccs_rq_9']) ) {
?>
<style type="text/css">
<?php
if ( $options['checkness']['wccs_rq_4'] == 1 ) {
?>
.form-row.address_1 abbr.required {
display: none;
}
<?php }
if ( $options['checkness']['wccs_rq_6'] == 1 ) {
?>
.form-row.city abbr.required {
display: none;
}
<?php }
if ( $options['checkness']['wccs_rq_7'] == 1 ) {
?>
.form-row.postcode abbr.required {
display: none;
}
<?php }
if ( $options['checkness']['wccs_rq_9'] == 1 ) {
?>
.form-row.state abbr.required {
display: none;
}}
if ( $options['check']['wccs_rq_4_s'] || $options['check']['wccs_rq_5_s'] || $options['check']['wccs_rq_6_s'] || $options['check']['wccs_rq_7_s'] || $options['check']['wccs_rq_9_s'] ) {
if ( $options['check']['wccs_rq_4_s'] == 1 ) {
?>
.form-row.address_1_s abbr.required {
display: none;
}
<?php }
if ( $options['check']['wccs_rq_6_s'] == 1 ) {
?>
.form-row.city_s abbr.required {
display: none;
}
<?php }
if ( $options['check']['wccs_rq_7_s'] == 1 ) {
?>
.form-row.postcode_s abbr.required {
display: none;
}
<?php }
if ( $options['check']['wccs_rq_9_s'] == 1 ) {
?>
.form-row.state_s abbr.required {
display: none;
}
<?php } ?>
</style>
<?php
}
}

// validator
function validator_changename() {
$options = get_option( 'wccs_settings' );

if ( count( $options['buttons'] ) > 0 )  :
$i = 0;
// Loop through each button
foreach ( $options['buttons'] as $btn ) :
$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';

if ( $btn['type'] == 'changename' && !empty($btn['label']) ){
return true;
}

$i++;
endforeach;
endif;
}

if ( validator_changename() ) {
add_action( 'woocommerce_order_details_after_order_table', 'string_replacer_wccs');
function string_replacer_wccs( $order ) {
$options = get_option( 'wccs_settings' );

?>
<header>
<h2><?php _e( 'Customer details', 'woocommerce' ); ?></h2>
</header>
<dl class="customer_details">
<?php
if ($order->billing_email) echo '<dt>'.__( 'Email:', 'woocommerce' ).'</dt><dd>'.$order->billing_email.'</dd>';
if ($order->billing_phone) echo '<dt>'.__( 'Telephone:', 'woocommerce' ).'</dt><dd>'.$order->billing_phone.'</dd>';
?>
</dl>

<?php if (get_option('woocommerce_ship_to_billing_address_only')=='no') : ?>

<div class="col2-set addresses">

<div class="col-1">

<?php endif; ?>

<header class="title">
<h3><?php _e( 'Billing Address', 'woocommerce' ); ?></h3>
</header>
<address><p>
<?php
if (!$order->get_formatted_billing_address()) _e( 'N/A', 'woocommerce' ); else echo $order->get_formatted_billing_address();
?>
</p></address>

<?php if (get_option('woocommerce_ship_to_billing_address_only')=='no') : ?>

</div><!-- /.col-1 -->

<div class="col-2">

<header class="title">
<h3><?php _e( 'Shipping Address', 'woocommerce' ); ?></h3>
</header>
<address><p>
<?php
if (!$order->get_formatted_shipping_address()) _e( 'N/A', 'woocommerce' ); else echo $order->get_formatted_shipping_address();
?>
</p></address>

</div><!-- /.col-2 -->

</div><!-- /.col2-set -->

<?php endif; ?>

<div class="clear"></div>

<script type="text/javascript">
var array = [];
<?php
$options = get_option( 'wccs_settings' );
if ( count( $options['buttons'] ) > 0 ) :
$i = 0;
// Loop through each button
foreach ( $options['buttons'] as $btn ) :
$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';

?>
array.push("<?php echo $btn['changenamep']; ?>" , "<?php echo $btn['changename']; ?>")
<?php
$i++;
endforeach;
endif;
?>

b(array);

function b(array)
{
for(var i = 0; i<(array.length-1); i=i+2)
{
document.body.innerHTML= document.body.innerHTML.replace(array[i],array[i+1])
}
}
</script>

<?php
}}

add_action('woocommerce_after_checkout_form', 'add_custom_css_wccs');
function add_custom_css_wccs() {
global $woocommerce;
$options = get_option( 'wccs_settings' );
if ( count( $options['buttons'] ) > 0 ) :
$i = 0;
// Loop through each button
foreach ( $options['buttons'] as $btn ) :
$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';
?>

<?php if ( !empty( $btn['label'] ) && !empty($btn['floatright']) ) { ?>

<style type="text/css">
#<?php echo $btn['cow']; ?>_field { float: right; }
</style>

<?php }

if ( !empty( $btn['label'] ) && !empty($btn['center_align']) ) { ?>

<style type="text/css">
#<?php echo $btn['cow']; ?>_field { width: 100%; }
</style>

<?php }
$i++;
endforeach;
endif;
}

add_action('woocommerce_before_checkout_form', 'override_this_wccs');
function override_this_wccs() {
global $woocommerce;
$options = get_option( 'wccs_settings' );
if ( count( $options['buttons'] ) > 0 ) {
$i = 0;

// css sub-parent hide
foreach( $options['buttons'] as $btn ) {
if ( ($btn['type'] == 'text') && !empty( $btn['add_amount'] ) && !empty( $btn['fee_name'] ) && empty( $btn['add_amount_field'] ) ) { 
echo '<style type="text/css">#'.$btn['cow'].'_applynow {
background: -webkit-gradient(linear,left top,left bottom,from(#ad74a2),to(#96588a));
background: -webkit-linear-gradient(#ad74a2,#96588a);
background: -moz-linear-gradient(center top,#ad74a2 0,#96588a 100%);
background: -moz-gradient(center top,#ad74a2 0,#96588a 100%);
border-color: #76456c;
color: #fff;
text-shadow: 0 -1px 0 rgba(0,0,0,.6);
width: 100%;
text-align: center;
float: right;
cursor: pointer;
position: relative;
}
#'.$btn['cow'].'_applynow:active {
top: 1px;
}</style>';
}
if ( !empty($btn['conditional_tie']) && empty($btn['conditional_parent']) && !empty($btn['conditional_parent_use'])) {
echo '<style type="text/css">#'.$btn['cow'].'_field.'.$btn['conditional_tie'].' { display: none; }</style>';
}
}

// script when clicked show
?>
<script type="text/javascript">
jQuery(document).ready(function($){

<?php
foreach( $options['buttons'] as $btn ) {
if ( !empty($btn['conditional_parent']) && !empty($btn['conditional_parent_use']) && !empty($btn['chosen_valt'])) { ?>

jQuery("#<?php echo ''.$btn['cow'].'_field.'.$btn['conditional_tie']; ?> input[name=<?php echo $btn['cow']; ?>]").click(function(){

<?php foreach( $options['buttons'] as $btn3 ) {
if ( empty($btn3['conditional_parent']) && !empty($btn3['conditional_parent_use']) && !empty($btn3['conditional_tie'])) { ?>

if(jQuery('#<?php echo ''.$btn['cow'].'_field.'.$btn['conditional_tie']; ?> input[name=<?php echo $btn['cow']; ?>]:checked').val() === '<?php echo $btn3['chosen_valt']; ?>' ) {
jQuery("#<?php echo ''.$btn3['cow'].'_field.'.$btn['conditional_tie']; ?>").show( "slow" );
}

if(jQuery('#<?php echo ''.$btn['cow'].'_field.'.$btn['conditional_tie']; ?> input[name=<?php echo $btn['cow']; ?>]:checked').val() !== '<?php echo $btn3['chosen_valt']; ?>' ) {
jQuery("#<?php echo ''.$btn3['cow'].'_field.'.$btn['conditional_tie']; ?>").hide( "slow" );

<?php
if ( !empty($btn2['fee_name']) && !empty($btn2['add_amount']) ) {
?>
jQuery('form.checkout').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6}});

jQuery('form.checkout').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', opacity: 0.6}});


var ajaxurl = '<?php echo admin_url('/admin-ajax.php'); ?>';
		data = {
		action: 'remove_tax_wccm',
		empty_check_add: 'none'
	};
	
	jQuery.post(ajaxurl, data, function(response) {

$.ajax({
   url: '<?php echo $woocommerce->cart->get_checkout_url(); ?>',
   data: {},
   success: function (data) {
      $("#order_review").html($(data).find("#order_review"));
	jQuery('form.checkout').unblock();
   },
   dataType: 'html'
});
});

<?php } ?> 

}

<?php }} ?>

});
<?php }} ?>
});
</script>

<?php

// ----------------------------- CLEAR ---------------------------------
// ---------------------------------------------------------------------
// ---------------------------------------------------------------------

foreach( $options['buttons'] as $btn ) {
foreach ($woocommerce->cart->cart_contents as $key => $values ) {

$multiproductsx[$i] = $btn['single_p'];
$show_field_single[$i] = $btn['single_px'];
$multiproductsx_cat[$i] = $btn['single_p_cat'];
$show_field_single_cat[$i] = $btn['single_px_cat'];

// Products
// hide field
if ( !empty($btn['single_p']) ) {
$multiarrayproductsx[$i] = explode(',',$multiproductsx[$i]);
if(in_array($values['product_id'],$multiarrayproductsx[$i]) && ( count($woocommerce->cart->cart_contents) < 2) ){
echo '<style type="text/css">
.woocommerce form #customer_details #'.$btn['cow'].'_field,
.woocommerce-page form #customer_details #'.$btn['cow'].'_field { 
display: none; 
}
</style>';
}}
// show field
if ( !empty($btn['single_px']) ) {
$show_field_array[$i] = explode(',',$show_field_single[$i]);
if(in_array($values['product_id'], $show_field_array[$i]) && ( count($woocommerce->cart->cart_contents) < 2) ){
echo '<style type="text/css">
.woocommerce form #customer_details #'.$btn['cow'].'_field,
.woocommerce-page form #customer_details #'.$btn['cow'].'_field { 
display: inline; 
}
</style>';
}
if(!in_array($values['product_id'], $show_field_array[$i]) && ( count($woocommerce->cart->cart_contents) < 2) ){
echo '<style type="text/css">
.woocommerce form #customer_details #'.$btn['cow'].'_field,
.woocommerce-page form #customer_details #'.$btn['cow'].'_field { 
display: none; 
}
</style>';
}}


// Category
// hide field
$terms = get_the_terms( $values['product_id'], 'product_cat' );
if ( !empty($terms) ) {
foreach ( $terms as $term ) {
if ( !empty($btn['single_p_cat']) ) {
$multiarrayproductsx_cat[$i] = explode(',',$multiproductsx_cat[$i]);
if(in_array($term->slug,$multiarrayproductsx_cat[$i]) && ( count($woocommerce->cart->cart_contents) < 2) ){
echo '<style type="text/css">
.woocommerce form #customer_details #'.$btn['cow'].'_field,
.woocommerce-page form #customer_details #'.$btn['cow'].'_field { 
display: none; 
}
</style>';
}}
// show field
if ( !empty($btn['single_px_cat']) ) {
$show_field_array_cat[$i] = explode(',',$show_field_single_cat[$i]);
if(in_array($term->slug, $show_field_array_cat[$i]) && ( count($woocommerce->cart->cart_contents) < 2) ){
echo '<style type="text/css">
.woocommerce form #customer_details #'.$btn['cow'].'_field,
.woocommerce-page form #customer_details #'.$btn['cow'].'_field { 
display: inline; 
}
</style>';
}
if(!in_array($term->slug, $show_field_array_cat[$i]) && ( count($woocommerce->cart->cart_contents) < 2) ){
echo '<style type="text/css">
.woocommerce form #customer_details #'.$btn['cow'].'_field,
.woocommerce-page form #customer_details #'.$btn['cow'].'_field { 
display: none; 
}
</style>';
}}}

}}}


}}






if ( enable_auto_complete_wccs()) {
add_action( 'woocommerce_before_checkout_form', 'retain_field_values_wccm' );
function retain_field_values_wccm() {
?>
<script type="text/javascript">

jQuery(document).ready(function() {

window.onload = function() {

<?php if(!is_user_logged_in()){ ?>
document.forms['checkout'].elements['billing_first_name'].value = "<?php echo get_option('billing_first_name-'.$_SERVER['REMOTE_ADDR']); ?>";
document.forms['checkout'].elements['billing_last_name'].value = "<?php echo get_option('billing_last_name-'.$_SERVER['REMOTE_ADDR']); ?>";
document.forms['checkout'].elements['billing_company'].value = "<?php echo get_option('billing_company-'.$_SERVER['REMOTE_ADDR']); ?>";
document.forms['checkout'].elements['billing_address_1'].value = "<?php echo get_option('billing_address_1-'.$_SERVER['REMOTE_ADDR']); ?>";
document.forms['checkout'].elements['billing_address_2'].value = "<?php echo get_option('billing_address_2-'.$_SERVER['REMOTE_ADDR']); ?>";
document.forms['checkout'].elements['billing_city'].value = "<?php echo get_option('billing_city-'.$_SERVER['REMOTE_ADDR']); ?>";
document.forms['checkout'].elements['billing_postcode'].value = "<?php echo get_option('billing_postcode-'.$_SERVER['REMOTE_ADDR']); ?>";
document.forms['checkout'].elements['billing_state'].value = "<?php echo get_option('billing_state-'.$_SERVER['REMOTE_ADDR']); ?>";
document.forms['checkout'].elements['billing_email'].value = "<?php echo get_option('billing_email-'.$_SERVER['REMOTE_ADDR']); ?>";
document.forms['checkout'].elements['billing_phone'].value = "<?php echo get_option('billing_phone-'.$_SERVER['REMOTE_ADDR']); ?>";
document.forms['checkout'].elements['shipping_first_name'].value = "<?php echo get_option('shipping_first_name-'.$_SERVER['REMOTE_ADDR']); ?>";
document.forms['checkout'].elements['shipping_last_name'].value = "<?php echo get_option('shipping_last_name-'.$_SERVER['REMOTE_ADDR']); ?>";
document.forms['checkout'].elements['shipping_company'].value = "<?php echo get_option('shipping_company-'.$_SERVER['REMOTE_ADDR']); ?>";
document.forms['checkout'].elements['shipping_address_1'].value = "<?php echo get_option('shipping_address_1-'.$_SERVER['REMOTE_ADDR']); ?>";
document.forms['checkout'].elements['shipping_address_2'].value = "<?php echo get_option('shipping_address_2-'.$_SERVER['REMOTE_ADDR']); ?>";
document.forms['checkout'].elements['shipping_city'].value = "<?php echo get_option('shipping_city-'.$_SERVER['REMOTE_ADDR']); ?>";
document.forms['checkout'].elements['shipping_postcode'].value = "<?php echo get_option('shipping_postcode-'.$_SERVER['REMOTE_ADDR']); ?>";
document.forms['checkout'].elements['shipping_state'].value = "<?php echo get_option('shipping_state-'.$_SERVER['REMOTE_ADDR']); ?>";
<?php } ?>
}
<?php
$options = get_option( 'wccs_settings' );
if ( count( $options['buttons'] ) > 0 ) :
$i = 0;
foreach ( $options['buttons'] as $btn ) :
$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';
if ( $btn['type'] !== 'changename' )  { ?>
document.forms['checkout'].elements['<?php echo $btn['cow']; ?>'].value = "<?php echo get_option(''.$btn['cow'].'-'.$_SERVER['REMOTE_ADDR']); ?>";

<?php }
$i++;
endforeach;
endif;
?>
});
</script>

<script type="text/javascript">
jQuery(document).ready(function() {

jQuery('body').change(function() {

var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
data = { action: 'retain_val_wccs',

<?php if(!is_user_logged_in()){ ?>
billing_first_name: jQuery("#billing_first_name").val(),

billing_last_name: jQuery("#billing_last_name").val(),

billing_company: jQuery("#billing_company").val(),

billing_address_1: jQuery("#billing_address_1").val(),

billing_address_2: jQuery("#billing_address_2").val(),

billing_city: jQuery("#billing_city").val(),

billing_postcode: jQuery("#billing_postcode").val(),

billing_state: jQuery("#billing_state").val(),

billing_email: jQuery("#billing_email").val(),

billing_phone: jQuery("#billing_phone").val(),

shipping_first_name: jQuery("#shipping_first_name").val(),

shipping_last_name: jQuery("#shipping_last_name").val(),

shipping_company: jQuery("#shipping_company").val(),

shipping_address_1: jQuery("#shipping_address_1").val(),

shipping_address_2: jQuery("#shipping_address_2").val(),

shipping_city: jQuery("#shipping_city").val(),

shipping_postcode: jQuery("#shipping_postcode").val(),

shipping_state: jQuery("#shipping_state").val(),
<?php } ?>
<?php if ( count( $options['buttons'] ) > 0 ) :
$i = 0;
foreach ( $options['buttons'] as $btn ) :
$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';
if ( $btn['type'] !== 'changename' ) { ?>
<?php echo $btn['cow']; ?>: jQuery("#<?php echo $btn['cow']; ?>").val(),

<?php }
$i++;
endforeach;
endif;
?>
};
jQuery.post(ajaxurl, data, function(response) {
});
return false;
});
});

</script>
<?php
}

add_action( 'wp_ajax_retain_val_wccs', 'retain_val_wccs_callback' );
add_action('wp_ajax_nopriv_retain_val_wccs', 'retain_val_wccs_callback');


function retain_val_wccs_callback() {
global $wpdb; // this is how you get access to the database

$options = get_option( 'wccs_settings' );

update_option('billing_first_name-'.$_SERVER['REMOTE_ADDR'] ,$_POST['billing_first_name']);
update_option('billing_last_name-'.$_SERVER['REMOTE_ADDR'] ,$_POST['billing_last_name']);
update_option('billing_company-'.$_SERVER['REMOTE_ADDR'] ,$_POST['billing_company']);
update_option('billing_address_1-'.$_SERVER['REMOTE_ADDR'] ,$_POST['billing_address_1']);
update_option('billing_address_2-'.$_SERVER['REMOTE_ADDR'] ,$_POST['billing_address_2']);
update_option('billing_city-'.$_SERVER['REMOTE_ADDR'] ,$_POST['billing_city']);
update_option('billing_postcode-'.$_SERVER['REMOTE_ADDR'] ,$_POST['billing_postcode']);
update_option('billing_state-'.$_SERVER['REMOTE_ADDR'] ,$_POST['billing_state']);
update_option('billing_email-'.$_SERVER['REMOTE_ADDR'] ,$_POST['billing_email']);
update_option('billing_phone-'.$_SERVER['REMOTE_ADDR'] ,$_POST['billing_phone']);
update_option('shipping_first_name-'.$_SERVER['REMOTE_ADDR'] ,$_POST['shipping_first_name']);
update_option('shipping_last_name-'.$_SERVER['REMOTE_ADDR'] ,$_POST['shipping_last_name']);
update_option('shipping_company-'.$_SERVER['REMOTE_ADDR'] ,$_POST['shipping_company']);
update_option('shipping_address_1-'.$_SERVER['REMOTE_ADDR'] ,$_POST['shipping_address_1']);
update_option('shipping_address_2-'.$_SERVER['REMOTE_ADDR'] ,$_POST['shipping_address_2']);
update_option('shipping_city-'.$_SERVER['REMOTE_ADDR'] ,$_POST['shipping_city']);
update_option('shipping_postcode-'.$_SERVER['REMOTE_ADDR'] ,$_POST['shipping_postcode']);
update_option('shipping_state-'.$_SERVER['REMOTE_ADDR'] ,$_POST['shipping_state']);
if ( count( $options['buttons'] ) > 0 ) :
$i = 0;
foreach ( $options['buttons'] as $btn ) :
$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';
if ( $btn['type'] !== 'changename' ) {
update_option(''.$btn['cow'].'-'.$_SERVER['REMOTE_ADDR'] ,$_POST[''.$btn['cow'].'']);
}
$i++;
endforeach;
endif;

die(); // this is required to return a proper result

}}

add_action('woocommerce_settings_start','update_auto_gen_account_wccm');

function update_auto_gen_account_wccm() {
$options = get_option( 'wccs_settings' );

// =================== apply new state ==========================
if ( !empty($options['checkness']['auto_create_wccm_account']) &&  get_option( 'woocommerce_registration_generate_password') == 'no' ) {
update_option( 'woocommerce_registration_generate_password', 'yes' );
}

if ( !empty($options['checkness']['auto_create_wccm_account']) && get_option( 'woocommerce_registration_generate_username') == 'no' ) {
update_option( 'woocommerce_registration_generate_username', 'yes' );
}

if ( !empty($options['checkness']['auto_create_wccm_account']) && get_option( 'woocommerce_enable_signup_and_login_from_checkout') == 'no' ) {
update_option( 'woocommerce_enable_signup_and_login_from_checkout', 'yes' );
}

// ====================== reset state =============================
if ( empty($options['checkness']['auto_create_wccm_account']) ) {
update_option( 'woocommerce_registration_generate_password', ''.get_option('wccmwrgp456vcb').'' );
}

if ( empty($options['checkness']['auto_create_wccm_account']) ) {
update_option( 'woocommerce_registration_generate_username', ''.get_option('wccmwrgu456vcb').'' );
}

if ( empty($options['checkness']['auto_create_wccm_account']) ) {
update_option( 'woocommerce_enable_signup_and_login_from_checkout', ''.get_option('wccmwesalfc456vcb').'' );
}

}

function enable_auto_complete_wccs() {
$options = get_option( 'wccs_settings' );

if ( !empty($options['checkness']['retainval']) ) {
return true;
} else {
return false;
}
}

add_action('woocommerce_checkout_process', 'company_custom_checkout_field_process_wccs');
function company_custom_checkout_field_process_wccs() {
global $woocommerce;
$options = get_option( 'wccs_settings' );

if ( empty($options['checkness']['wccs_opt_3']) && empty($options['checkness']['rq_company']) && !$_POST['billing_company']) {
$woocommerce->add_error( __('<strong>'.__('Company Name','woocommerce').'</strong> is a required field.') );
}}

add_action('woocommerce_checkout_process', 'company1_custom_checkout_field_process_wccs');
function company1_custom_checkout_field_process_wccs() {
global $woocommerce;
$options = get_option( 'wccs_settings' );

if ( empty($options['check']['rq_company1']) && !$_POST['shipping_company']) {
$woocommerce->add_error( __('<strong>'.__('Company Name','woocommerce').'</strong> is a required field.') );
}}

add_action('woocommerce_checkout_process', 'comments_custom_checkout_field_process_wccs');
function comments_custom_checkout_field_process_wccs() {
global $woocommerce;
$options = get_option( 'wccs_settings' );

if ( empty($options['checkness']['wccs_rqo_12']) && !$_POST['order_comments']) {
$woocommerce->add_error( __('<strong>'.__('Order Notes','woocommerce').'</strong> is a required field.') );
}}

add_action( 'woocommerce_before_checkout_form' , 'asterisk_company_wccs' );
function asterisk_company_wccs( $fields ) {
$options = get_option( 'wccs_settings' );
if ( !empty($options['checkness']['auto_create_wccm_account']) ) {
?>
<script type="text/javascript">
jQuery(document).ready(function() {
jQuery( "input#createaccount" ).prop("checked","checked");
});
</script>
<style type="text/css">
.create-account {
display:none;
}
</style>
<?php
}

if ( empty($options['checkness']['wccs_opt_3']) && empty($options['checkness']['rq_company']) ) {
?>
<script type="text/javascript">
jQuery(document).ready(function() {
jQuery( "#billing_company_field" ).addClass( "validate-required wccs_custom_val" );
});
</script>
<style type="text/css">
#billing_company_field label:after {
content: ' *';
color: red;
}
</style>
<?php
}

if ( empty($options['check']['rq_company1']) ) {
?>
<script type="text/javascript">
jQuery(document).ready(function() {
jQuery( "#shipping_company_field" ).addClass( "validate-required wccs_custom_val" );
});
</script>
<style type="text/css">
#shipping_company_field label:after {
content: ' *';
color: red;
}
</style>
<?php } 

if ( empty($options['checkness']['wccs_rqo_12']) ) {
?>
<script type="text/javascript">
jQuery(document).ready(function() {
jQuery( "p#order_comments_field" ).addClass( "validate-required wccs_custom_val" );
});
</script>
<style type="text/css">
p#order_comments_field label:after {
content: ' *';
color: red;
}
.woocommerce-page form .form-row.validate-required.woocommerce-invalid textarea.input-text,
.woocommerce form .form-row.validate-required.woocommerce-invalid textarea.input-text {
border-color: #fb7f88;
}
</style>
<?php
}
}

add_filter( 'woocommerce_checkout_fields' , 'custom_override_company1' );
function custom_override_company1( $fields ) {
$options = get_option('wccs_settings');
if ( !empty($options['check']['rm_company1']) ) 
     unset($fields['shipping']['shipping_company']);
     return $fields;
}

add_action( 'wp_ajax_remove_tax_wccm', 'remove_tax_wccm' );
add_action( 'wp_ajax_nopriv_remove_tax_wccm', 'remove_tax_wccm' );

function remove_tax_wccm() {
global $wpdb, $post, $woocommerce;
$options = get_option( 'wccs_settings' );

if ( count( $options['buttons'] ) > 0 ) {
$i = 0;
// Loop through each button
foreach ( $options['buttons'] as $btn ) {

if ( !empty( $btn['add_amount'] ) && !empty( $btn['fee_name'] ) ) {
if ( isset($_POST['add_amount_faj']) && !empty($_POST['add_amount_faj']) ) {
update_option('wooccm_addamount453userf', '0');
update_option('wooccm_addamount453userfa', $_POST['add_amount_faj'] );
} else {
update_option('wooccm_addamount453userf', '1');
update_option('wooccm_addamount453userfa', '' );
}
}


if ( ! empty( $btn['chosen_valt'] ) ) {

if ( ! empty( $btn['tax_remove'] ) ) {
if ( isset($_POST['tax_remove_aj']) && !empty($_POST['tax_remove_aj']) && $_POST['tax_remove_aj'] == $btn['chosen_valt'] ) {
update_option('wooccm_tax_save_method', '0');
} else {
update_option('wooccm_tax_save_method', '1');
}
}

if ( ! empty( $btn['add_amount'] ) && !empty( $btn['add_amount_field'] ) ) {
if ( isset($_POST['add_amount_aj']) && !empty($_POST['add_amount_aj']) && $_POST['add_amount_aj'] == $btn['chosen_valt'] ) {
update_option('wooccm_addamount453user', '0');
} else {
update_option('wooccm_addamount453user', '1');
}}

}}}
die();
}

add_action('woocommerce_checkout_order_processed','cart_update_wccm');
function cart_update_wccm( $items) {
update_option('wooccm_tax_save_method', '1');
update_option('wooccm_addamount453user', '1');
update_option('wooccm_addamount453userf', '1');
}

add_action('woocommerce_after_checkout_validation','reupdate_upon_checkout_wccm');
function reupdate_upon_checkout_wccm( $order_id ) {
global $woocommerce, $wpdb;
$options = get_option( 'wccs_settings' );

if ( count( $options['buttons'] ) > 0 ) {
$i = 0;
// Loop through each button
foreach ( $options['buttons'] as $btn ) {

if ( $_POST[ ''.$btn['cow'].'' ]) {
if ( ! empty( $btn['tax_remove'] ) ) {
update_option('wooccm_tax_save_method', '0');
}
if ( ! empty( $btn['add_amount'] ) && !empty( $btn['add_amount_field'] ) ) {
update_option('wooccm_addamount453user', '0');
}
if ( ! empty( $btn['add_amount'] ) && !empty( $btn['fee_name'] ) ) {
update_option('wooccm_addamount453userf', '0');
}
$i++;
}}}
}

add_action( 'woocommerce_calculate_totals', 'remove_tax_for_exempt' );
function remove_tax_for_exempt( $cart ) {
global $woocommerce, $wpdb;

if ( get_option('wooccm_tax_save_method') == '0' ) {
if ( !is_checkout() ) {
update_option('wooccm_tax_save_method', '1');
}
$cart->remove_taxes();
}
return $cart;
}

add_action( 'woocommerce_before_calculate_totals','wooccm_custom_user_charge_man' );
function wooccm_custom_user_charge_man() {
global $woocommerce, $wpdb;
$options = get_option( 'wccs_settings' );

if ( get_option('wooccm_addamount453user') == '0' ) {
if ( !is_checkout() ) {
update_option('wooccm_addamount453user', '1');
}

if ( count( $options['buttons'] ) > 0 ) {
$i = 0;
// Loop through each button
foreach ( $options['buttons'] as $btn ) {

if ( !empty( $btn['add_amount'] ) && !empty( $btn['add_amount_field'] ) && !empty( $btn['label'] ) && !empty( $btn['fee_name'] ) ) {	

	$woocommerce->cart->add_fee( $btn['fee_name'], $btn['add_amount_field'], false, '' );
	
$i++;
}}}}}

add_action( 'woocommerce_before_calculate_totals','wooccm_custom_user_charge_manf' );
function wooccm_custom_user_charge_manf() {
global $woocommerce, $wpdb;
$options = get_option( 'wccs_settings' );

if ( get_option('wooccm_addamount453userf') == '0' ) {
if ( !is_checkout() ) {
update_option('wooccm_addamount453userf', '1');
}

if ( count( $options['buttons'] ) > 0 ) {
$i = 0;
// Loop through each button
foreach ( $options['buttons'] as $btn ) {

if ( !empty( $btn['add_amount'] ) && empty( $btn['add_amount_field'] ) && !empty( $btn['label'] ) && !empty( $btn['fee_name'] ) ) {	

	$woocommerce->cart->add_fee( $btn['fee_name'], get_option('wooccm_addamount453userfa'), false, '' );
	
$i++;
}}}}}

add_action('woocommerce_after_cart_totals','update_cart_totalajaxwccm');
function update_cart_totalajaxwccm() {
global $woocommerce, $wpdb;
?>
<script type="text/javascript">
jQuery(document).ready(function($) {

window.onload = function() {
jQuery('div.cart-collaterals').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6}});

jQuery('div.cart-collaterals').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', opacity: 0.6}});

$.ajax({
   url: '<?php echo $woocommerce->cart->get_cart_url(); ?>',
   data: {},
   success: function (data) {
      $(".cart-collaterals").html($(data).find(".cart-collaterals"));
	jQuery('div.cart-collaterals').unblock();
   },
   dataType: 'html'
});

}; });
</script>
<?php
}

// -------------- required clear-----------------------------------
add_action('woocommerce_checkout_process', 'wccm_ccfcustomcheckoutprocessnow');
function wccm_ccfcustomcheckoutprocessnow() {
global $woocommerce;
$options = get_option( 'wccs_settings' );
if ( count( $options['buttons'] ) > 0 ) {
$i = 0;

foreach( $options['buttons'] as $btn ) {
foreach ($woocommerce->cart->cart_contents as $key => $values ) {

$multiproductsx[$i] = $btn['single_p'];
$show_field_single[$i] = $btn['single_px'];
$multiproductsx_cat[$i] = $btn['single_p_cat'];
$show_field_single_cat[$i] = $btn['single_px_cat'];

// show field
if ( !empty($btn['single_px']) ) {
$show_field_array[$i] = explode(',',$show_field_single[$i]);
if(in_array($values['product_id'], $show_field_array[$i]) && ($woocommerce->cart->cart_contents_count < 2) ){
if ( !empty ($btn['checkbox']) && !empty( $btn['label'] ) && ($btn['type'] !== 'changename')  ) {
if (!$_POST[''.$btn['cow'].''] ) {
$woocommerce->add_error( '<strong>'.$btn['label'].'</strong> '. __('is a required field.', 'woocommerce-checkout-manager-pro' ) . ' ');
}}}}

// hide field
if ( !empty($btn['single_p']) ) {
$hide_field_array[$i] = explode(',',$multiproductsx[$i]);
if(!in_array($values['product_id'], $hide_field_array[$i]) && ($woocommerce->cart->cart_contents_count < 2) ){
if ( !empty ($btn['checkbox']) && !empty( $btn['label'] ) && ($btn['type'] !== 'changename')  ) {
if (!$_POST[''.$btn['cow'].''] ) {
$woocommerce->add_error( '<strong>'.$btn['label'].'</strong> '. __('is a required field.', 'woocommerce-checkout-manager-pro' ) . ' ');
}}}}


// category
$terms = get_the_terms( $values['product_id'], 'product_cat' );
if ( !empty($terms) ) {
foreach ( $terms as $term ) {

// show field
if ( !empty($btn['single_px_cat']) ) {
$show_field_array_cat[$i] = explode(',',$show_field_single_cat[$i]);
if(in_array($term->slug, $show_field_array_cat[$i]) && ($woocommerce->cart->cart_contents_count < 2) ){

if ( !empty ($btn['checkbox']) && !empty( $btn['label'] ) && ($btn['type'] !== 'changename')  ) {
if (!$_POST[''.$btn['cow'].''] ) {
$woocommerce->add_error( '<strong>'.$btn['label'].'</strong> '. __('is a required field.', 'woocommerce-checkout-manager-pro' ) . ' ');
}}}}

// hide field
if ( !empty($btn['single_p_cat']) ) {
$hide_field_array_cat[$i] = explode(',',$multiproductsx_cat[$i]);
if(!in_array($term->slug, $hide_field_array_cat[$i]) && ($woocommerce->cart->cart_contents_count < 2) ){

if ( !empty ($btn['checkbox']) && !empty( $btn['label'] ) && ($btn['type'] !== 'changename')  ) {
if (!$_POST[''.$btn['cow'].''] ) {
$woocommerce->add_error( '<strong>'.$btn['label'].'</strong> '. __('is a required field.', 'woocommerce-checkout-manager-pro' ) . ' ');
}}}}


}}

$i++;
}}}}

function state_defaultSwitchWooccm() {
$options = get_option( 'wccs_settings' );
if( !empty($options['checkness']['per_state']) && !empty($options['checkness']['per_state_check']) ) 
return ''.$options['checkness']['per_state'].''; 
}
add_filter( 'default_checkout_state', 'state_defaultSwitchWooccm' );