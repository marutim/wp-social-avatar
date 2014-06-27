/**
 * The Javascript file for WP Avatar Plugin
 */

jQuery(document).ready(function(){
    // Allowing checking of only one checkbox
    jQuery('input[type="checkbox"][name="wp-avatar-profile"]').on('change',function(){
       var th = jQuery(this);
       var name = th.prop('name'); 
       if(th.is(':checked')){
           jQuery(':checkbox[name="'  + name + '"]').not(jQuery(this)).prop('checked',false);   
       }
    });
    
    // Ajax call to clear the transient
    jQuery('input[type="button"][name="wp-gplus-clear"]').on('click',function(){
        jQuery('span#msg').html('');
        var userId = jQuery(this).attr('user');
        jQuery.ajax({
            type : "post",
            url : ajaxurl,
            data : {action: "wp_social_avatar_gplus_clear_cache", user_id : userId},
            success: function(response) {
                if(response) {
                    jQuery('span#msg').html('<strong> Cache Cleared</strong>');
                } else {
                    jQuery('span#msg').html('<strong> Try Again</strong>');
                }
            }
        });
    });
});