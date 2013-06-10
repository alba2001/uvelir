// <div class="expand-source"></div>
jQuery(document).ready(function(){
	// jQuery('.expand-source:not(:first-child)').hide();
	jQuery(".expand-source").each(function(){
		jQuery(":first", this).siblings().hide();
	});
    jQuery('.expand-source').addClass('up');
    jQuery('.expand-source p:first-child').addClass('title');
    jQuery('.expand-source p:first-child').toggle(
      function(){
        jQuery(this).siblings().stop(false, true).slideDown(500);
    	jQuery(this).parent().removeClass('up').addClass('down');
      },
     function(){
        jQuery(this).siblings().stop(false, true).slideUp(500);
    	jQuery(this).parent().removeClass('down').addClass('up');
     }
   );
});