/**
 * The Javascript file for WP Avatar Plugin
 */

jQuery(document).ready(function(){
    jQuery('input[type="checkbox"][name="wp-avatar-profile"]').on('change',function(){
       var th = jQuery(this);
       var name = th.prop('name'); 
       if(th.is(':checked')){
           jQuery(':checkbox[name="'  + name + '"]').not(jQuery(this)).prop('checked',false);   
       }
    });
});