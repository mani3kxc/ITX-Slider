jQuery(document).ready(function($){
		
	// stop our admin menus from collapsing
	if( $('body[class*=" itxsl_"]').length || $('body[class*=" post-type-itxsl_"]').length ) {

		$itxsl_menu_li = $('#toplevel_page_itxsl_dashboard_admin_page');
		
		$itxsl_menu_li
		.removeClass('wp-not-current-submenu')
		.addClass('wp-has-current-submenu')
		.addClass('wp-menu-open');
		
		$('a:first',$itxsl_menu_li)
		.removeClass('wp-not-current-submenu')
		.addClass('wp-has-submenu')
		.addClass('wp-has-current-submenu')
		.addClass('wp-menu-open');
		
	}

	/* Add Row */
$( 'body' ).on( 'click', '.itxsl-admin-add-row', function(e){
    e.preventDefault();
 
    /* Target the template. */
    var template = '.itxsl-admin-templates > .itxsl-admin-' + $( this ).attr( 'data-template' );
 
    /* Clone the template and add it. */
    $( template ).clone().appendTo( '.itxsl-admin-rows' );
 
    /* Hide Empty Row Message */
    $( '.itxsl-admin-rows-message' ).hide();
});

function fxPB_UpdateOrder(){
 
    /* In each of rows */
    $('.itxsl-admin-rows-active > .itxsl-admin-row').each( function(i){
 
        /* Increase index by 1 to avoid "0" as first number. */
        var num = i + 1;
    

        $( this ).find( '.itxsl-admin-row-input' ).each( function(i) {
 
            /* Get field id for this input */
            var slider_id = $( this ).attr( 'slider-id' );

            /* Update name attribute with order and field name.  */
            $( this ).attr( 'name', 'itxsl[' + slider_id + ']');
        });

    });

    $('.itxsl-admin-rows-all > .itxsl-admin-row').each( function(i){

    	$( this ).find( '.itxsl-admin-row-input' ).each( function(i) {
 
            /* Get field id for this input */
            var slider_id = $( this ).attr( 'slider-id' );

            /* Update name attribute with order and field name.  */
            $( this ).attr( 'name', '_itxsl[' + slider_id + ']');
        });

    });

}

/* Delete Row */
$( 'body' ).on( 'click', '.itxsl-admin-remove', function(e){
    e.preventDefault();
 
    /* Delete Row */
    $( this ).parents( '.itxsl-admin-row' ).remove();
    
    /* Show Empty Message When Applicable. */
    if( ! $( '.itxsl-admin-rows > .itxsl-admin-row' ).length ){
        $( '.itxsl-admin-rows-message' ).show();
    }
});

/* Make Row Sortable 
$( '.itxsl-admin-rows' ).sortable({
    handle: '.itxsl-admin-handle',
    cursor: 'grabbing',
    stop: function(e, ui) {
    	fxPB_UpdateOrder();
    }
});*/

$( '.itxsl-admin-rows-active, .itxsl-admin-rows-all' ).sortable({
    handle: '.itxsl-admin-handle',
    cursor: 'grabbing',
    connectWith: ".itxsl-admin-sliders-group", 
    stop: function(e, ui) {
    	fxPB_UpdateOrder();
    }
});

});