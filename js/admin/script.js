jQuery(document).ready(function($){
		
	// stop our admin menus from collapsing
	if( $('body[class*=" sfs_"]').length || $('body[class*=" post-type-sfs_"]').length ) {

		$sfs_menu_li = $('#toplevel_page_sfs_dashboard_admin_page');
		
		$sfs_menu_li
		.removeClass('wp-not-current-submenu')
		.addClass('wp-has-current-submenu')
		.addClass('wp-menu-open');
		
		$('a:first',$sfs_menu_li)
		.removeClass('wp-not-current-submenu')
		.addClass('wp-has-submenu')
		.addClass('wp-has-current-submenu')
		.addClass('wp-menu-open');
		
	}

	/* Add Row */
$( 'body' ).on( 'click', '.sfs-admin-add-row', function(e){
    e.preventDefault();
 
    /* Target the template. */
    var template = '.sfs-admin-templates > .sfs-admin-' + $( this ).attr( 'data-template' );
 
    /* Clone the template and add it. */
    $( template ).clone().appendTo( '.sfs-admin-rows' );
 
    /* Hide Empty Row Message */
    $( '.sfs-admin-rows-message' ).hide();
});

function fxPB_UpdateOrder(){
 
    /* In each of rows */
    $('.sfs-admin-rows-active > .sfs-admin-row').each( function(i){
 
        /* Increase index by 1 to avoid "0" as first number. */
        var num = i + 1;
    

        $( this ).find( '.sfs-admin-row-input' ).each( function(i) {
 
            /* Get field id for this input */
            var slider_id = $( this ).attr( 'slider-id' );

            /* Update name attribute with order and field name.  */
            $( this ).attr( 'name', 'sfs[' + slider_id + ']');
        });

    });

    $('.sfs-admin-rows-all > .sfs-admin-row').each( function(i){

    	$( this ).find( '.sfs-admin-row-input' ).each( function(i) {
 
            /* Get field id for this input */
            var slider_id = $( this ).attr( 'slider-id' );

            /* Update name attribute with order and field name.  */
            $( this ).attr( 'name', '_sfs[' + slider_id + ']');
        });

    });

}

/* Delete Row */
$( 'body' ).on( 'click', '.sfs-admin-remove', function(e){
    e.preventDefault();
 
    /* Delete Row */
    $( this ).parents( '.sfs-admin-row' ).remove();
    
    /* Show Empty Message When Applicable. */
    if( ! $( '.sfs-admin-rows > .sfs-admin-row' ).length ){
        $( '.sfs-admin-rows-message' ).show();
    }
});

/* Make Row Sortable 
$( '.sfs-admin-rows' ).sortable({
    handle: '.sfs-admin-handle',
    cursor: 'grabbing',
    stop: function(e, ui) {
    	fxPB_UpdateOrder();
    }
});*/

$( '.sfs-admin-rows-active, .sfs-admin-rows-all' ).sortable({
    handle: '.sfs-admin-handle',
    cursor: 'grabbing',
    connectWith: ".sfs-admin-sliders-group", 
    stop: function(e, ui) {
    	fxPB_UpdateOrder();
    }
});

});