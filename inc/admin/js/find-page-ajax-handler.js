jQuery( document ).ready( function( $ ) {

        "use strict";
	    /**
         * The file is enqueued from inc/admin/class-admin.php.
	    */        
        $( '#fp_add_user_meta_ajax_form' ).submit( function( event ) {
            
            event.preventDefault(); // Prevent the default form submit.            
            
            // serialize the form data
            var ajax_form_data = $("#fp_add_user_meta_ajax_form").serialize();
            
            //add our own ajax check as X-Requested-With is not always reliable
            ajax_form_data = ajax_form_data+'&ajaxrequest=true&submit=Submit+Form';
            
            $.ajax({
                url:    params.ajaxurl, 
                type:   'post',                
                data:   ajax_form_data
            })
            
            .done( function( response ) { 
                $(".fp__result ").html( response );
            })
            
            // something went wrong  
            .fail( function() {
                $(" #fp_form_feedback ").html( "<h2>Something went wrong.</h2><br>" );                  
            })
        
            // after all this time?
            .always( function() {
                event.target.reset();
            });
        
       });
        
});
