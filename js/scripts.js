/* Author:

*/


jQuery(document).ready(function ($) {
   
   /**
    * 
    * 
    * Loads the relevant sub-selector based on the primary selector.
    */
   $(document).on("change", 'select.type-select', function() { 
        var input_type = $(this).val();
        $(this).siblings().hide();
        $(this).siblings('.'+input_type).show();
   });
   
   /**
    * 
    * 
    * When a select box is changed, send that new value to our input.
    */
   $(document).on("change", 'select.url-selector', function() { 
        $(this).siblings('.eps-redirect-url').val( $(this).val() );
        console.log( $(this).closest('tr.redirect-entry').find('.eps-text-link.test') );
        $(this).closest('tr.redirect-entry').find('.eps-text-link.test').addClass('inactive');
   });
   
   /**
    * 
    * 
    * Grey out the test button if it's been changed.
    */
   $('tr.redirect-entry .eps-text-link.test').on('click', function(e) {
       if( $(this).hasClass('inactive') ) e.preventDefault();
   });
   
   
   /**
    * 
    * 
    * Get a new blank input.
    */
   $('#eps-redirect-add').click( function(e){
       e.preventDefault();
       var request = $.post( eps_redirect_ajax_url, { 
            'action' : 'eps_redirect_get_new_entry'
       });
       request.done(function( data ) {
            $('#eps-redirect-entries tr:last-child').before( data );
       });
   });
   
   
   
   
   /**
    * 
    * 
    * Delete an entry.
    */
   $('.eps-text-link.remove').click( function(e){
       e.preventDefault();
       var request = $.post( eps_redirect_ajax_url, { 
            'action' : 'eps_redirect_delete_entry',
            'id'     : $(this).siblings('.redirect-id').val()
       });
       request.done(function( data ) {
           var response = JSON.parse(data);
           $('tr.redirect-entry.id-'+response.id).remove();
       });
       
       
   });
   
   
   


   
   /**
    * 
    * 
    * Tabs
    */
   $('#eps-tab-nav .eps-tab-nav-item').click(function(e){
        //e.preventDefault();
        var target = $(this).attr('href');
        
        
        $('#eps-tabs .eps-tab').hide();
         
        $(target + '-pane').show().height( 'auto' );
        
        $('#eps-tab-nav .eps-tab-nav-item').removeClass('active');
        $(this).addClass('active');
        //return false;
     });
     
    
     
     /**
      * 
      * 
      * Open up the tab as per the current location
      */
     var hash = window.location.hash;
     
     if( hash ) {
        $('#eps-tabs .eps-tab').hide();
        $(hash+'-pane').show();
        $('#eps-tab-nav .eps-tab-nav-item').removeClass('active');
        $('#eps-tab-nav .eps-tab-nav-item').eq( $(hash +'-pane').index() ).addClass('active');
     } else {
        $('#eps-tab-nav .eps-tab-nav-item').show();  
     }
   
});



