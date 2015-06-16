<?php
/**
 * Add the field to the checkout
 **/

function wccm_set_html_content_type() {
	return 'text/html';
}

function wccm_validate_upload_process_customer() {
$options = get_option( 'wccs_settings' );

if ( !empty($options['checkness']['enable_file_upload'])) {return true;} else {return false;}
}

if ( wccm_validate_upload_process_customer() ) {
add_action('woocommerce_order_items_table', 'wccs_image_uploader_checkout_field');
add_action('woocommerce_view_order','wccs_file_uploader_front_end');
add_action('woocommerce_product_write_panel_tabs', 'wccs_file_uploader_panel');
add_action( 'add_meta_boxes', 'wccs_initialize_metabox');
}

function wccs_image_uploader_checkout_field( $order ) {
global $wpdb, $woocommerce, $post, $product;
$options = get_option( 'wccs_settings' );

$allow_file_upload = explode(',',$options['change']['allow_file_upload']);
$deny_file_upload = explode(',',$options['change']['deny_file_upload']);
$allow_file_upload_cat = explode(',',$options['change']['allow_file_upload_cat']);
$deny_file_upload_cat = explode(',',$options['change']['deny_file_upload_cat']);

foreach ( $order->get_items() as $order_item ) {

// begin category upload
$terms = get_the_terms( $order_item['product_id'], 'product_cat' );
foreach ( $terms as $term ) {

if ( !empty($options['change']['allow_file_upload_cat']) && in_array($term->slug, $allow_file_upload_cat) ) {
wp_enqueue_style('style', plugins_url( '/woocommerce-checkout-manager-pro/upload.css'), false, '1.0', 'all'); 
require_once( ABSPATH . 'wp-content/plugins/woocommerce-checkout-manager-pro/upload.php' );
}

if ( !empty($options['change']['deny_file_upload_cat']) && !in_array($term->slug, $deny_file_upload_cat) ) {
wp_enqueue_style('style', plugins_url( '/woocommerce-checkout-manager-pro/upload.css'), false, '1.0', 'all');
require_once( ABSPATH . 'wp-content/plugins/woocommerce-checkout-manager-pro/upload.php' );
}
	}


// begin product upload
if ( !empty($options['change']['allow_file_upload']) && in_array($order_item['product_id'], $allow_file_upload) ) {
wp_enqueue_style('style', plugins_url( '/woocommerce-checkout-manager-pro/upload.css'), false, '1.0', 'all'); 
require_once( ABSPATH . 'wp-content/plugins/woocommerce-checkout-manager-pro/upload.php' );
}

if ( !empty($options['change']['deny_file_upload']) && !in_array($order_item['product_id'], $deny_file_upload) ) {
wp_enqueue_style('style', plugins_url( '/woocommerce-checkout-manager-pro/upload.css'), false, '1.0', 'all');
require_once( ABSPATH . 'wp-content/plugins/woocommerce-checkout-manager-pro/upload.php' );
}


// default behaviour
if ( empty($options['change']['deny_file_upload']) && empty($options['change']['allow_file_upload']) &&
empty($options['change']['deny_file_upload_cat']) && empty($options['change']['allow_file_upload_cat']) ) {
wp_enqueue_style('style', plugins_url( '/woocommerce-checkout-manager-pro/upload.css'), false, '1.0', 'all');
require_once( ABSPATH . 'wp-content/plugins/woocommerce-checkout-manager-pro/upload.php' );
}

}}


//////////////////////////////
add_action("wp_ajax_wccs_upload_file_func", "wccs_upload_file_func_callback");
add_action("wp_ajax_nopriv_wccs_upload_file_func", "wccs_upload_file_func_callback");

function wccs_upload_file_func_callback($order_id) {
global $wpdb, $woocommerce, $post; // this is how you get access to the database

$options = get_option( 'wccs_settings' );
$order_id = $_REQUEST["order_id"];
$order = new WC_Order( $order_id );

// load files
require_once( ABSPATH . 'wp-admin/includes/file.php' ); 
require_once( ABSPATH . 'wp-admin/includes/media.php' );

$upload_dir = wp_upload_dir();
$files = $_FILES['files_wccm'];
$upload_overrides = array( 'test_form' => false );


foreach ($files['name'] as $key => $value) {
  if ($files['name'][$key]) {


// using the wp_handle_upload
if ( empty($options['checkness']['cat_file_upload']) ) {  
  $file = array(
      'name'     => $files['name'][$key],
      'type'     => $files['type'][$key],
      'tmp_name' => $files['tmp_name'][$key],
      'error'    => $files['error'][$key],
      'size'     => $files['size'][$key]
    );
$movefile = wp_handle_upload($file, $upload_overrides);

          $attachment = array(
                'guid' => $movefile['url'], 
                'post_mime_type' => $movefile['type'],
                'post_title' => preg_replace( '/\.[^.]+$/', '', basename($movefile['file'])),
                'post_content' => '',
                'post_status' => 'inherit'
            );

            $attach_id = wp_insert_attachment( $attachment, $movefile['url'], $order_id);

            // you must first include the image.php file
            // for the function wp_generate_attachment_metadata() to work

            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata( $attach_id, $movefile['url'] );
            wp_update_attachment_metadata( $attach_id, $attach_data );

// send email
$email_recipients = $options['checkness']['wooccm_notification_email'];
$message_content = '
This is an automatic message from WooCommerce Checkout Manager, reporting that files has been uploaded by '.$order->billing_first_name.' '.$order->billing_last_name.'.<br />
<h3>Customer Details</h3>
Name: '.$order->billing_first_name.' '.$order->billing_last_name.'<br />
E-mail: '.$order->billing_email.'<br />
Order Number: '.$order_id.' <br /> 
You can view the files and order details via back-end by following this <a href="'.admin_url('/post.php?post='.$order_id.'&action=edit').'">link</a>.
';

add_filter( 'wp_mail_content_type', 'wccm_set_html_content_type' );
wp_mail( $email_recipients, 'WooCCM - Files Uploaded by Customer ['.$order->billing_first_name.' '.$order->billing_last_name.']', $message_content );

// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
remove_filter( 'wp_mail_content_type', 'wccm_set_html_content_type' );

} else {

// using move_uploaded_file to categorized uploaded images
if (!file_exists($upload_dir['basedir']. '/wooccm_uploads/'.$order_id.'/')) {
wp_mkdir_p($upload_dir['basedir']. '/wooccm_uploads/'.$order_id.'/');
}

$filename = $files['name'][$key];
$wp_filetype = wp_check_filetype($filename);
$URLpath = $upload_dir['baseurl']. '/wooccm_uploads/'.$order_id.'/'.$filename;

move_uploaded_file( $files["tmp_name"][$key], $upload_dir['basedir']. '/wooccm_uploads/'.$order_id.'/'.$filename);

          $attachment = array(
                'guid' => $URLpath, 
                'post_mime_type' => $wp_filetype['ext'],
                'post_title' => preg_replace( '/\.[^.]+$/', '', $filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );

            $attach_id = wp_insert_attachment( $attachment, $URLpath, $order_id);

            // you must first include the image.php file
            // for the function wp_generate_attachment_metadata() to work

            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata( $attach_id, $URLpath );
            wp_update_attachment_metadata( $attach_id, $attach_data );
// send email
$email_recipients = get_option('admin_email');
$message_content = '
This is an automatic message from WooCommerce Checkout Manager, reporting that files has been uploaded by '.$order->billing_first_name.' '.$order->billing_last_name.'.<br />
<h3>Customer Details</h3>
Name: '.$order->billing_first_name.' '.$order->billing_last_name.'<br />
E-mail: '.$order->billing_email.'<br />
Order Number: '.$order_id.' <br /> 
You can view the files and order details via back-end by following this <a href="'.admin_url('/post.php?post='.$order_id.'&action=edit').'">link</a>.
';

add_filter( 'wp_mail_content_type', 'wccm_set_html_content_type' );
wp_mail( $email_recipients, 'WooCCM - Files Uploaded by Customer', $message_content );

// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
remove_filter( 'wp_mail_content_type', 'wccm_set_html_content_type' );

    }
}}
echo ' '.__('Files was uploaded successfully.','woocommerce-checkout-manager-pro').'';
die();
}


function wccs_initialize_metabox() {
     global $post;
add_meta_box( 'woocommerce-order-files', __( 'Order Uploaded files', 'woocommerce-checkout-manager-pro' ), 'wccs_file_uploader_data_meta_box', 'shop_order', 'normal', 'default' );
}

function wccs_file_uploader_data_meta_box($post) {
 global $wpdb, $thepostid, $theorder, $woocommerce, $post;

$options = get_option( 'wccs_settings' );
$upload_dir = wp_upload_dir();
        $args = array(
            'post_type' => 'attachment',
            'numberposts' => -1,
            'post_status' => null,
            'post_parent' => $post->ID
        );
?>   

<script type="text/javascript" >
jQuery(document).ready(function($) {

$('#wccm_save_order_submit').click(function() {

$('div.woocommerce_order_items_wrapper').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6}});

$('div.woocommerce_order_items_wrapper').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', opacity: 0.6}});

	var data = {
		action: 'save_meta_box_wccm',
		post_id : '<?php echo $post->ID; ?>',
		product_image_gallery : $('#product_image_gallery').val(),
		wccm_default_keys_load : $('#wccm_default_keys_load').val()
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	$.post(ajaxurl, data, function(response) {
	$('div.woocommerce_order_items_wrapper').unblock();
            $(".wccm_results").html(response);
	});
}); }); 
</script>

<?php wp_enqueue_style( 'wccm_upload_file_style', plugins_url('/woocommerce-checkout-manager-pro/order_css.css') ); ?>

<div class="woocommerce_order_items_wrapper">
<table class="woocommerce_order_items">
	<thead>
		<tr>
			<th style="width:12%;text-align: center;"><?php _e('File Image','woocommerce-checkout-manager-pro'); ?></th>
			<th style="width:10%;text-align: center;"><?php _e('Action','woocommerce-checkout-manager-pro'); ?></th>
			<th style="width:12%;text-align: center;"><?php _e('Width x Height','woocommerce-checkout-manager-pro'); ?></th>
			<th style="width:8%;text-align: center;"><?php _e('Extension','woocommerce-checkout-manager-pro'); ?></th>
			<th style="width:15%;text-align: center;"><?php _e('ID #','woocommerce-checkout-manager-pro'); ?></th>
			<th style="width:4%"><?php _e('Link','woocommerce-checkout-manager-pro'); ?></th>
			<th style="width:30%;text-align: center;"><?php _e('Name','woocommerce-checkout-manager-pro'); ?></th>
		</tr>
	</thead>

</table>
<div id="product_images_container">
	<ul class="product_images">
		<?php


$attachment_args = get_posts( $args );	
	
if ( get_post_meta( $post->ID, '_product_image_gallery', true ) == '' ) {

if ($attachment_args) {
foreach($attachment_args as $attachment) {
$array[] = $attachment->ID;
}

$default_wccm_values = implode(',',$array);
$product_image_gallery = implode(',',$array);
}} else {

if ($attachment_args) {
$attachment_args = get_posts( $args );
foreach($attachment_args as $attachment) {
$array[] = $attachment->ID;
}
$default_wccm_values = implode(',',$array);
}

$product_image_gallery = get_post_meta( $post->ID, '_product_image_gallery', true );
}
			

$attachments = array_filter( explode( ',', $product_image_gallery ) );

if ( $attachments ) {
		foreach ( $attachments as $attachment_id ) {

$image_attributes = wp_get_attachment_url( $attachment_id );
$image_attributes2 = wp_get_attachment_image_src( $attachment_id );
$filename = basename($image_attributes);
$wp_filetype = wp_check_filetype($filename);

	echo '<li class="image wccm_filesli" data-attachment_id="' . esc_attr( $attachment_id ) . '">

<span class="file_image_wccm">
'.wp_get_attachment_image( $attachment_id, array(75,75), true ).'
</span>

<span class="delete_action_wccm">
<ul class="actions">
<li>
<a href="#" class="delete tips wccm_delete" data-tip="' . __( 'Delete image', 'woocommerce' ) . '">' . __( 'Delete', 'woocommerce' ) . '</a>
</li>
</ul>
</span>

<span class="file_width_height_wccm">';
if($image_attributes2[1] == '') { echo '-';}else{ echo $image_attributes2[1].' x '.$image_attributes2[2];} 
echo '</span>

<span class="file_extension_wccm">
'.$wp_filetype['ext'].'
</span>

<span class="file_id_wccm">
'.$attachment_id.'
</span>

<span class="file_link_wccm">
'.wp_get_attachment_link( $attachment_id, '' , false, false, 'Link' ).'
</span>

<span class="file_name_wccm">
'.preg_replace( '/\.[^.]+$/', '', $filename).'
</span>
							</li>';
						}}
				?>
			</ul>

<input type="hidden" class="wccm_add_to_list" id="product_image_gallery" name="product_image_gallery" value="<?php echo esc_attr( $product_image_gallery ); ?>" />

<input type="hidden" id="wccm_default_keys_load" name="wccm_default_keys_load" value="<?php echo esc_attr($default_wccm_values); ?>" />

		</div>
		<p class="add_product_images hide-if-no-js">
			<a href="#" class="button-primary wccm_add_order_link" data-choose="<?php _e( 'Add Files to Order Gallery', 'woocommerce' ); ?>" data-update="<?php _e( 'Add to gallery', 'woocommerce' ); ?>" data-delete="<?php _e( 'Delete image', 'woocommerce' ); ?>" data-text="<?php _e( 'Delete', 'woocommerce' ); ?>"><?php _e( 'Add Order Files', 'woocommerce' ); ?></a>
		</p>
		

<input type="button" id="wccm_save_order_submit" class="button button-primary" value="Save Files">
 <div class="wccm_results"></div>	

    <div class="clear"></div></div>
    <?php

}



add_action("wp_ajax_save_meta_box_wccm", "save_meta_box_wccm");
add_action("wp_ajax_nopriv_save_meta_box_wccm", "save_meta_box_wccm");

function save_meta_box_wccm() {
global $post, $wpdb, $woocommerce;

if (isset($_POST['product_image_gallery'])) {		
$attachment_ids = array_filter( explode( ',', wc_clean( $_POST['product_image_gallery'] ) ) );
update_post_meta( $_POST['post_id'], '_product_image_gallery', implode( ',', $attachment_ids ) );


$array1 = explode( ',',$_POST['wccm_default_keys_load']);
$array2 = explode( ',',$_POST['product_image_gallery']);
$attachment_id_each = array_diff($array1, $array2);
}

if (isset($_POST['wccm_default_keys_load'])) {
foreach($attachment_id_each as $key => $values) {
wp_delete_attachment( $attachment_id_each[$key] );
}
echo ''.__('Saved Successfully.','woocommerce-checkout-manager-pro').'';
}
die();
}


// front end for user
function wccs_file_uploader_front_end($order_id) {
 global $wpdb, $thepostid, $theorder, $woocommerce, $post;

$options = get_option( 'wccs_settings' );
$upload_dir = wp_upload_dir();
        $args = array(
            'post_type' => 'attachment',
            'numberposts' => -1,
            'post_status' => null,
            'post_parent' => $order_id
        );
?>   

<script type="text/javascript" >
jQuery(document).ready(function($) {

$('#wccm_save_order_submit').click(function() {
$(".wccm_results").html("Saving, please wait....");
	var ajaxurl = '<?php echo admin_url('/admin-ajax.php'); ?>';
		data = {
		action: 'update_attachment_wccm',
		product_image_gallery : $('#product_image_gallery').val(),
		wccm_default_keys_load : $('#wccm_default_keys_load').val()
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	$.post(ajaxurl, data, function(response) {
            $(".wccm_results").html(response);
	});
}); }); 
</script>

<?php wp_enqueue_style( 'wccm_upload_file_style', plugins_url('/woocommerce-checkout-manager-pro/order_css.css') ); ?>

<h2><?php _e('Order Uploaded Files','woocommerce-checkout-manager-pro'); ?></h2>
<div class="woocommerce_order_items_wrapper front_end">
<table class="woocommerce_order_items front_end">
	<thead>
		<tr>
			<th style="width:12%"><?php _e('File Image','woocommerce-checkout-manager-pro'); ?></th>
			<th style="width:10%"><?php _e('Action','woocommerce-checkout-manager-pro'); ?></th>
			<th style="width:12%"><?php _e('Width x Height','woocommerce-checkout-manager-pro'); ?></th>
			<th style="width:8%"><?php _e('Extension','woocommerce-checkout-manager-pro'); ?></th>
			<th style="width:15%;text-align: center;"><?php _e('ID #','woocommerce-checkout-manager-pro'); ?></th>
			<th style="width:4%"><?php _e('Link','woocommerce-checkout-manager-pro'); ?></th>
			<th style="width:30%;text-align: center;"><?php _e('Name','woocommerce-checkout-manager-pro'); ?></th>
		</tr>
	</thead>

</table>
<div id="product_images_container front_end">
	<ul class="product_images front_end">
		<?php


$attachment_args = get_posts( $args );	
	
if ($attachment_args) {
foreach($attachment_args as $attachment) {
$array[] = $attachment->ID;
}

$default_wccm_values = implode(',',$array);
$product_image_gallery = implode(',',$array);
}
		
$attachments = array_filter( explode( ',', $product_image_gallery ) );

if ( $attachments ) {
		foreach ( $attachments as $attachment_id ) {


$image_attributes = wp_get_attachment_url( $attachment_id );
$image_attributes2 = wp_get_attachment_image_src( $attachment_id );
$filename = basename($image_attributes);
$wp_filetype = wp_check_filetype($filename);

$value_declear = array_diff(explode( ',',$default_wccm_values), explode( ',',$attachment_id));

echo '<li class="image wccm_filesli wccmv_' . esc_attr( $attachment_id ) . '">

<span style="display:none;"><script type="text/javascript">
jQuery(document).ready(function(){

    jQuery(".wccmx_' . esc_attr( $attachment_id ) . '").click(function(){
     jQuery(".wccmv_' . esc_attr( $attachment_id ) . '").hide();
jQuery("#product_image_gallery").val(jQuery("#product_image_gallery").val().replace("'.esc_attr( $attachment_id ).'", ""));
     
  });
});
</script></span>

<span class="file_image_wccm front_end">
'.wp_get_attachment_image( $attachment_id, array(75,75), true ).'
</span>

<span class="delete_action_wccm">
<ul class="actions">
<li>
<a class="delete tips wccm_delete wccmx_' . esc_attr( $attachment_id ) . '" data-tip="' . __( 'Delete image', 'woocommerce' ) . '">' . __( 'Delete', 'woocommerce' ) . '</a>
</li>
</ul>
</span>

<span class="file_width_height_wccm">';
if($image_attributes2[1] == '') { echo '-';}else{ echo $image_attributes2[1].' x '.$image_attributes2[2];} 
echo '</span>

<span class="file_extension_wccm">
'.$wp_filetype['ext'].'
</span>

<span class="file_id_wccm">
'.$attachment_id.'
</span>

<span class="file_link_wccm">
'.wp_get_attachment_link( $attachment_id, '' , false, false, 'Link' ).'
</span>

<span class="file_name_wccm">
'.preg_replace( '/\.[^.]+$/', '', $filename).'
</span>
							</li>';
						}}
				?>
			</ul>

<input type="hidden" class="wccm_add_to_list" id="product_image_gallery" name="product_image_gallery" value="<?php echo esc_attr( $product_image_gallery ); ?>" />

<input type="hidden" id="wccm_default_keys_load" name="wccm_default_keys_load" value="<?php echo esc_attr($default_wccm_values); ?>" />

		</div>

<input type="button" id="wccm_save_order_submit" class="button button-primary front_end" value="Save Files">
 <div class="wccm_results front_end"></div>	

    <div class="clear"></div></div>
    <?php

}

add_action("wp_ajax_update_attachment_wccm", "update_attachment_wccm_callback");
add_action("wp_ajax_nopriv_update_attachment_wccm", "update_attachment_wccm_callback");

function update_attachment_wccm_callback() {
global $post, $wpdb, $woocommerce;

$array1 = explode( ',',$_POST['wccm_default_keys_load']);
$array2 = explode( ',',$_POST['product_image_gallery']);
$attachment_id_each = array_diff($array1, $array2);

if (isset($_POST['wccm_default_keys_load'])) {
foreach($attachment_id_each as $key => $values) {
wp_delete_attachment( $attachment_id_each[$key] );
}
echo ''.__('Saved Successfully','woocommerce-checkout-manager-pro').'';
}
die();
}
include(plugin_dir_path( __FILE__ ).'add-on/include/dislawooccm.php');