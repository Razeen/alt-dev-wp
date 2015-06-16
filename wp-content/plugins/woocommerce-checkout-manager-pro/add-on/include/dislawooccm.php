<?php

   function esip($ip_addr) 
{ 
  //first of all the format of the ip address is matched 
  if(preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/",$ip_addr)) 
  { 
    //now all the intger values are separated 
    $parts=explode(".",$ip_addr); 
    //now we need to check each part can range from 0-255 
    foreach($parts as $ip_parts) 
    { 
      if(intval($ip_parts)>255 || intval($ip_parts)<0) 
      return FALSE; //if number is not within range of 0-255 
    } 
    return TRUE; 
  } 
  else 
    return FALSE; //if format of ip address doesn't matches 
} 

    
    
    function domain($domainb) 
    { 
    $bits = explode('/', $domainb); 
    if ($bits[0]=='http:' || $bits[0]=='https:') 
    { 
    $domainb= $bits[2]; 
    } else { 
    $domainb= $bits[0]; 
    } 
    unset($bits); 
    $bits = explode('.', $domainb); 
    $idz=count($bits); 
    $idz-=3; 
    if (strlen($bits[($idz+2)])==2) { 
    $url=$bits[$idz].'.'.$bits[($idz+1)].'.'.$bits[($idz+2)]; 
    } else if (strlen($bits[($idz+2)])==0) { 
    $url=$bits[($idz)].'.'.$bits[($idz+1)]; 
    } else { 
    $url=$bits[($idz+1)].'.'.$bits[($idz+2)]; 
    } 
    return $url; 
    } 

function check_domainIP( $domain ) {
$bits = explode('.', $domain);

if ( count( $bits ) == 4 ) {
if ( is_numeric($bits[0]) && is_numeric($bits[1]) && is_numeric($bits[2]) && is_numeric($bits[3]) ) {
return true;
} 
else {
return false;
}
} else {
return false;
}

}


add_option( 'wccmkelizn32aunique', '0' ); 
function pg_eptxml() {
if( isset($_POST['lw_eptxml']) ) { 
update_option( 'wccmkelizn32aunique', $_POST['lw_eptxml'] ); 
echo '<div class="updated"><p><strong>'.__('License Key Successfully Installed.').'</strong></p></div>';
} 
?>

<div class="wrap"></div>
<h2><?php _e( 'WooCommerce Checkout Manager Pro License', 'woocommerce-checkout-manager-pro' ); ?></h2> 

<style type="text/css">
#lw_eptxml {
width:55%;
}
.no {
background:red;
padding:25.5%;
color:#fff;
width:55%;
}
.yes{
background:green;
padding:25.9%;
color:#fff;
width:55%;
}
</style>

<form action="admin.php?page=License_check_slug" method="post">
<table style="margin-top:40px;" class="wp-list-table widefat tags ui-sortable">
<thead>
	<tr>
		<th><?php _e('Licensing Validator','woocommerce-checkout-manager-pro'); ?></th>
		<th></th>
	</tr>
</thead>

<tbody>
	<tr>
		<td><?php _e('Status','woocommerce-checkout-manager-pro'); ?></td>
		<td>

<?php if( woocmmatl() ) { ?>
<span class="yes"><?php _e('Valid','woocommerce-checkout-manager-pro'); ?></span>
<?php } ?>

<?php if( !woocmmatl() ) { ?>
<span class="no"><?php _e('Invalid','woocommerce-checkout-manager-pro'); ?></span>
<?php } ?>

		</td>
	</tr>
</tbody>


<tbody>
	<tr>
		<td><?php _e('Installed Key','woocommerce-checkout-manager-pro'); ?></td>
		<td><input id="lw_eptxml" name="lw_eptxml" size="70" type="text" value="<?php echo get_option('wccmkelizn32aunique'); ?>" /></td>
	</tr>

	<tr>
		<td><a href="http://www.trottyzone.com/contact/"><input type="button" class="button-secondary" value="<?php _e('Help ?','woocommerce-checkout-manager-pro'); ?>" /></a></td>
		<td><input type="submit" class="button-primary" value="<?php _e('Validate','woocommerce-checkout-manager-pro'); ?>" /></td>
</tr>
</tbody>

</table>
</form>
<?php 
}

function woocmmatl() { $address= $_SERVER['HTTP_HOST']; if ( check_domainIP( $address ) == false ) { $parsed_url = parse_url($address); $check = esip($parsed_url['host']); $host = $parsed_url['host']; if ($check == FALSE){ if ($host != ""){ if ( substr(domain($host), 0, 1) == '.' ) { $host = str_replace('www.','',substr(domain($host), 1)); } else { $host = str_replace('www.','',domain($host)); } }else{ if ( substr(domain($address), 0, 1) == '.' ) { $host = str_replace('www.','',substr(domain($address), 1)); } else { $host = str_replace('www.','',domain($address)); }  } } } else { $host = $address; } $sewccm = "http://www.trottyzone.com"; $valuexg = get_option('wccmkelizn32aunique'); if ( $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] == str_replace(array('http://','https://'),'', site_url('/wp-admin/admin.php?page=License_check_slug')) || $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] == str_replace(array('http://','https://'),'', site_url('/wp-admin/admin.php?page=woocommerce-checkout-manager-pro/woocommerce-checkout-manager-pro.php')) || $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] == str_replace(array('http://','https://'),'', site_url('/wp-admin/admin.php?page=woocommerce-checkout-manager-pro%2Fwoocommerce-checkout-manager-pro.php&settings-updated=true')) ) {  if ( substr( $_SERVER['REMOTE_ADDR'] , 0, 3) == "127" || $_SERVER['REMOTE_ADDR'] == "1" || $_SERVER['REMOTE_ADDR'] == "::1" ) { return true; } else { if ( !empty( $valuexg ) ) { $xkewccm['key'] = get_option('wccmkelizn32aunique'); $xkewccm['domain'] = $host; $xkewccm['product'] = "woocommerce-checkout-manager-pro";  $xsnwccm = curl_init();  curl_setopt($xsnwccm, CURLOPT_URL, $sewccm."/wp-content/plugins/wp-licensing/auth/verify.php"); curl_setopt($xsnwccm, CURLOPT_POST, 1); curl_setopt($xsnwccm, CURLOPT_POSTFIELDS, $xkewccm); curl_setopt($xsnwccm, CURLOPT_TIMEOUT, 30); curl_setopt($xsnwccm, CURLOPT_RETURNTRANSFER, 1);  $result = curl_exec($xsnwccm);  curl_close($xsnwccm);  $results = array(); $result = json_decode($result, true); if($result['valid'] == "true"){ return true; } }}} return false; }