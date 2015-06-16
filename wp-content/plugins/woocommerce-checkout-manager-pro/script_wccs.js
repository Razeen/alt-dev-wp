jQuery(document).ready(function() {

jQuery(function () {
    jQuery(".show_hide").click(function() {
        
jQuery('.widefat th div span',this).toggleClass('current_opener');

  jQuery(this).next().toggle();
      if( jQuery('.slidingDiv').length > 1) {
            jQuery('.slidingDiv table:vissible').hide();

            jQuery(this).next().show();
       }
    }); 
}); 
});


jQuery(document).ready(function() {

jQuery(function () {
    jQuery(".show_hide2").click(function() {

jQuery('.widefat th div span',this).toggleClass('current_opener');

  jQuery(this).next().toggle();
      if( jQuery('.slidingDiv2').length > 1) {
            jQuery('.slidingDiv2 :vissible').hide();

            jQuery(this).next().show();
       }
    }); 
}); 
});


jQuery(document).ready(function(){

    jQuery(".hide_stuff_change_tog").click(function(){
  jQuery(".hide_stuff_change_tog span").toggleClass('current_opener');
     jQuery(".hide_stuff_change").slideToggle(0);
     
  });
});

jQuery(document).ready(function(){

    jQuery("th.daoo").click(function(){
	jQuery("th.daoo").toggleClass('current_opener');
     jQuery(".hide_stuff_days").slideToggle(0);
     
  });
});

jQuery(document).ready(function(){

    jQuery("th.add_amount").click(function(){
	jQuery("th.add_amount").toggleClass('current_opener');
     jQuery(".add_amount_field").slideToggle(0);
     
  });
});

jQuery(document).ready(function(){

    jQuery("th.apply_tick").click(function(){
	jQuery("th.apply_tick").toggleClass('current_opener');
     jQuery(".condition_tick").slideToggle(0);
     
  });
});


jQuery(document).ready(function(){

    jQuery(".more_toggler").click(function(){
  jQuery(".more_toggler span").toggleClass('current_opener');
     jQuery(".more_toggler1").slideToggle(0);
     
  });
});

jQuery(document).ready(function(){

    jQuery(".more_toggler1a").click(function(){
  jQuery(".more_toggler1a span").toggleClass('current_opener');
     jQuery(".more_toggler1c").slideToggle(0);
     
  });
});


jQuery(document).ready(function(){

var counter = 0;
jQuery(".toggler_adder").bind("click",function(){
    counter++;
    switch(counter){
        case 1:
            jQuery(".toggler_adder span").addClass('current_opener');
            jQuery(".wccm1").hide();
            jQuery(".hide_stuff_op2").show();
            break;
        case 2:
            jQuery(".hide_stuff_op2").hide();
            jQuery(".hide_stuff_op3").show();
            break;
        case 3:
            jQuery(".hide_stuff_op3").hide();
            jQuery(".hide_stuff_op4").show();
            break;
        case 4:
            jQuery(".hide_stuff_op4").hide();
            jQuery(".hide_stuff_op5").show();
            break;
        case 5:
            jQuery(".hide_stuff_op5").hide();
            jQuery(".hide_stuff_op6").show();
            break;
        case 6:
            jQuery(".hide_stuff_op5").hide();
            jQuery(".hide_stuff_op6").show();
            break;
        case 7:
            jQuery(".hide_stuff_op6").hide();
            jQuery(".hide_stuff_op7").show();
            break;
        case 8:
            jQuery(".hide_stuff_op7").hide();
            jQuery(".hide_stuff_op8").show();
            break;
        case 9:
            jQuery(".hide_stuff_op8").hide();
            jQuery(".hide_stuff_op9").show();
            break;
        case 10:
            jQuery(".hide_stuff_op9").hide();
            jQuery(".hide_stuff_op10").show();
            break;
        case 11:
            jQuery(".toggler_adder span").removeClass('current_opener');
	    jQuery(".hide_stuff_op10").hide();
	    jQuery(".wccm1").show();
	    counter=0;
            break;
    }
});
});

jQuery(document).ready(function(){

    jQuery(".hide_stuff_color_tog").click(function(){
    jQuery(".hide_stuff_color_tog span").toggleClass('current_opener');
     jQuery(".hide_stuff_color").slideToggle(0);
     
  });
});


jQuery(document).ready(function(){

    jQuery(".hide_stuff_tog").click(function(){
    jQuery(".hide_stuff_tog span").toggleClass('current_opener');
     jQuery(".hide_stuff_op").slideToggle(0);
     
  });
});

jQuery(document).ready(function(){

    jQuery(".hide_stuff_togcheck").click(function(){
     jQuery(".hide_stuff_togcheck span").toggleClass('current_opener');
     jQuery(".hide_stuff_opcheck").slideToggle(0);
     
  });
});

jQuery(document).ready(function() {
jQuery(function() {
jQuery('#select_all_rm').click(function() {
    var c = this.checked;
    jQuery('.rm').prop('checked',c);
});
});
});

jQuery(document).ready(function() {
jQuery(function() {
jQuery('#select_all_rm_s').click(function() {
    var c = this.checked;
    jQuery('.rm_s').prop('checked',c);
});
});
});

jQuery(document).ready(function() {
jQuery(function() {
jQuery('#select_all_rq').click(function() {
    var c = this.checked;
    jQuery('.rq').prop('checked',c);
});
});
});

jQuery(document).ready(function() {
jQuery(function() {
jQuery('#select_all_rq_s').click(function() {
    var c = this.checked;
    jQuery('.rq_s').prop('checked',c);
});
});
});

// Javascript for adding new field
jQuery(document).ready( function() {

	/**
	 * Credits to the Advanced Custom Fields plugin for this code
	 */

	// Update Order Numbers
	function update_order_numbers(div) {
		div.children('tbody').children('tr.wccs-row').each(function(i) {
			jQuery(this).children('td.wccs-order').html(i+1);
		});
	}
	
	// Make Sortable
	function make_sortable(div){
		var fixHelper = function(e, ui) {
			ui.children().each(function() {
				jQuery(this).width(jQuery(this).width());
			});
			return ui;
		};

		div.children('tbody').unbind('sortable').sortable({
			update: function(event, ui){
				update_order_numbers(div);
			},
			handle: 'td.wccs-order',
			helper: fixHelper
		});
	}

	var div = jQuery('.wccs-table'),
		row_count = div.children('tbody').children('tr.wccs-row').length;

	// Make the table sortable
	make_sortable(div);
	
	// Add button
	jQuery('#wccs-add-button').live('click', function(){

		var div = jQuery('.wccs-table'),			
			row_count = div.children('tbody').children('tr.wccs-row').length,
			new_field = div.children('tbody').children('tr.wccs-clone').clone(false); // Create and add the new field

		new_field.attr( 'class', 'wccs-row' );

		// Update names
		new_field.find('[name]').each(function(){
			var count = parseInt(row_count);
			var name = jQuery(this).attr('name').replace('[999]','[' + count + ']');
			jQuery(this).attr('name', name);
		});

		// Add row
		div.children('tbody').append(new_field); 
		update_order_numbers(div);

		// There is now 1 more row
		row_count ++;

		return false;	
	});

	// Remove button
	jQuery('.wccs-table .wccs-remove-button').live('click', function(){
		var div = jQuery('.wccs-table'),
			tr = jQuery(this).closest('tr');

		tr.animate({'left' : '50px', 'opacity' : 0}, 250, function(){
			tr.remove();
			update_order_numbers(div);
		});

		return false;
	});
});