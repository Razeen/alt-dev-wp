<div class="show_hide2">
<a href="#">
<table class="widefat" border="1" style="margin-top:20px;">
<thead>
<tr>
<th>= <?php _e('Upload Files', 'woocommerce-checkout-manager-pro');  ?> =</th>
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

        <th style="width: 35%;"><?php _e('Name', 'woocommerce-checkout-manager-pro'); ?></th>
	<th style="text-align: center"><?php _e('Enable/ Disable', 'woocommerce-checkout-manager-pro'); ?></th>
	
    </tr>
</thead>

<tbody>
  <tr>
<td><?php _e('Allow Customers to Upload Files', 'woocommerce-checkout-manager-pro');  ?></th>

<td style="width: 70%;text-align:center;">
<input name="wccs_settings[checkness][enable_file_upload]" type="checkbox" value="true" <?php if ( !empty($options['checkness']['enable_file_upload'])) echo "checked='checked'"; ?> />
	</td>		
  </tr>
</tbody>

<tbody>
  <tr>
<td><?php _e('Notification E-mail', 'woocommerce-checkout-manager-pro');  ?></th>

<td style="width: 70%;">
<input style="width:100%;" name="wccs_settings[checkness][wooccm_notification_email]" type="text" value="<?php echo $options['checkness']['wooccm_notification_email']; ?>" />
	</td>		
  </tr>
</tbody>

<tbody>
  <tr>
<td><?php _e('Categorize Uploaded Files', 'woocommerce-checkout-manager-pro');  ?> : <span style="cursor: pointer;" class="show_hide2"><a>read more</a></span>

<span style="display:none;" class="slidingDiv2">
<br /><br />
<?php _e('Changes uploaded files location folder from', 'woocommerce-checkout-manager-pro');  ?> <br />
<strong><?php echo $upload_dir['url']; ?>/</strong> <br />
<?php _e('to', 'woocommerce-checkout-manager-pro');  ?><br />
<strong><?php echo $upload_dir['baseurl']; ?>/wooccm_uploads/{order number}/</strong>
</span>

</td>

<td style="width: 70%;text-align:center;">
<input name="wccs_settings[checkness][cat_file_upload]" type="checkbox" value="true" <?php if ( !empty($options['checkness']['cat_file_upload'])) echo "checked='checked'"; ?> />
	</td>		
  </tr>
</tbody>

<tbody>
  <tr>
<td><?php _e('Allow File Upload for Products Only', 'woocommerce-checkout-manager-pro');  ?></th>

<td style="width: 100%;text-align:center;">
<input style="width: 100%;" name="wccs_settings[change][allow_file_upload]" placeholder="Enter Product ID(s); Example: 1674,1423,1234" type="text" value="<?php echo $options['change']['allow_file_upload']; ?>" />
	<span class="or_shower"><?php _e('OR', 'woocommerce-checkout-manager-pro');  ?></span></td>		
  </tr>
</tbody>

<tbody>
  <tr>
<td><?php _e('Deny File Upload for Products Only', 'woocommerce-checkout-manager-pro');  ?></th>

<td style="width: 100%;text-align:center;">
<input style="width: 100%;" name="wccs_settings[change][deny_file_upload]" placeholder="Enter Product ID(s); Example: 1674,1423,1234" type="text" value="<?php echo $options['change']['deny_file_upload']; ?>" />
	</td>		
  </tr>
</tbody>


<tbody>
  <tr>
<td><?php _e('Allow File Upload for Categories of', 'woocommerce-checkout-manager-pro');  ?></th>

<td style="width: 100%;text-align:center;">
<input style="width: 100%;" name="wccs_settings[change][allow_file_upload_cat]" placeholder="Enter Category Slug(s); Example: my-cat, flowers_in, double_in" type="text" value="<?php echo $options['change']['allow_file_upload_cat']; ?>" />
	<span class="or_shower"><?php _e('OR', 'woocommerce-checkout-manager-pro');  ?></span></td>		
  </tr>
</tbody>

<tbody>
  <tr>
<td><?php _e('Deny File Upload for Categories of', 'woocommerce-checkout-manager-pro');  ?></th>

<td style="width: 100%;text-align:center;">
<input style="width: 100%;" name="wccs_settings[change][deny_file_upload_cat]" placeholder="Enter Category Slug(s); Example: my-cat, flowers_in, double_in" type="text" value="<?php echo $options['change']['deny_file_upload_cat']; ?>" />
	</td>		
  </tr>
</tbody>


</table>
</div>