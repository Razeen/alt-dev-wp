<tfoot id="wccs_upload_main">
<tr><th scope="row"><?php _e('Upload Files','woocommerce-checkout-manager-pro'); ?>:<br /><span id="wccm_make_smaller">(<?php _e( 'Max Upload Size','woocommerce' ); ?>: <?php echo size_format( wp_max_upload_size() ); ?>)</span></th>
      <td scope="amount" id="files-listing-wccm">

<span id="wccm_uploader_select"><input type="file" name="files_wccm" id="files_wccm" multiple />
<button type="button" id="files_button_wccm">Upload Files!</button></span>
    
<span id="response_wccm"></span>

</td></tfoot>
  </div>
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>

<script type="text/javascript">
(function post_image_content() {
var input = document.getElementById("files_wccm"),
    formdata = false;  

if (window.FormData) {
    formdata = new FormData();
    document.getElementById("files_button_wccm").style.display = "none";
}


input.addEventListener("change", function (evt) {

$('table.shop_table.order_details').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6}});

$('table.shop_table.order_details').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', opacity: 0.6}});

    var i = 0, len = this.files.length, img, reader, file;

    for ( ; i < len; i++ ) {
        file = this.files[i];

             if ( window.FileReader ) {
                reader = new FileReader();
                reader.onloadend = function (e) {

function showUploadedItem (source) {
    var list = document.getElementById("files-listing-wccm"),
	li   = document.createElement("span"),
        img  = document.createElement("img");
    
if (file.type.match('image.*')) {
          img.src = source;
	}
if (file.type.match('application.*')) {
          img.src = '<?php echo site_url('wp-includes/images/crystal/document.png');?>';
	}
if (file.type.match('audio.*')) {
          img.src = '<?php echo site_url('wp-includes/images/crystal/audio.png');?>';
	}
if (file.type.match('text.*')) {
          img.src = '<?php echo site_url('wp-includes/images/crystal/text.png');?>';
	}
if (file.type.match('video.*')) {
          img.src = '<?php echo site_url('wp-includes/images/crystal/video.png');?>';
	}
    li.appendChild(img);
    list.appendChild(li);
}
                    showUploadedItem(e.target.result, file.fileName);

                };
               
       reader.readAsDataURL(file);

            if (formdata) {
                formdata.append("files_wccm[]",file); 
            }
         }
    }

    if (formdata) {
        $.ajax({
            url: "<?php echo admin_url('/admin-ajax.php?action=wccs_upload_file_func&order_id='.$order->id.''); ?>",
            type: "POST",
            data: formdata,
            processData: false,
            contentType: false,
            success: function (res) {
                $('#files_wccm').show();
                $('table.shop_table.order_details').unblock();

$.ajax({
   url: '<?php echo $order->get_view_order_url(); ?>',
   data: {},
   success: function (data) {
      $("div.woocommerce_order_items_wrapper.front_end").html($(data).find("div.woocommerce_order_items_wrapper.front_end"));
	jQuery('form.checkout').unblock();
   },
   dataType: 'html'
});
		
            }
        });
    }
}, false);
}());
</script>