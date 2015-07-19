/*
 * .wphacks-admin-notices-conditions
 * .wphacks-admin-notices-add-condition 
 * .wphacks-admin-notices-delete-condition
 * #wphacks-admin-notice-condition-template
 * 
 * 
 */
jQuery( document ).ready(function( $ ) {
	jQuery('.wphacks-admin-notices-add-condition').click(function(e){
		e.preventDefault();
		jQuery('.wphacks-admin-notices-conditions').append( jQuery('#wphacks-admin-notice-condition-template').html() );
	})
});

jQuery( document ).ready(function( $ ){
	jQuery( '.notice-dismiss' ).click(function(e){
		var notice_id = jQuery(this).parent().data('notice-id');
		var data = {
			'action': 'wphacks_hide_dismissed_by_user',
			'notice_id': notice_id,
		}
		jQuery.get(ajaxurl,data, function(response){
			console.log(response);
			if( (response.success) && (response.data == true) ){
				console.log('yay');
			} else {
				console.log('fail');
			}
		});
	})
});
