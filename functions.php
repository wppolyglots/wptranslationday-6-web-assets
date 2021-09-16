<?php

// enqueue parent styles
add_action( 'wp_enqueue_scripts', 'wptd_enqueue_styles' );
function wptd_enqueue_styles() {
    $parenthandle = 'twenty-twenty-one-style'; // This is 'twenty-twenty-one-style' for the Twenty Twenty-one theme.
    $theme = wp_get_theme();
	wp_enqueue_style( 'add_google_fonts', 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,700;0,800;1,400&display=swap', false );
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css',
        array(),  // if the parent theme code has a dependency, copy it to here
        $theme->parent()->get('Version')
    );
    wp_enqueue_style( 'custom-style', get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get('Version') // this only works if you have Version in the style header
    );
}

/**
 * Fix 404 pagination on category archive when category base is set to . under Permalink Settings
 * Ref: https://wordpress.stackexchange.com/questions/311858/how-to-remove-the-term-category-from-category-pagination/311872#311872
 */
function wptd_fix_category_pagination( $query_string = array() ) {
    if( isset( $query_string['category_name'] ) && isset( $query_string['name'] ) && $query_string['name'] === 'page' && isset( $query_string['page'] ) ) {
      $paged = trim( $query_string['page'], '/' );
        if( is_numeric( $paged ) ) {
          // we are not allowing 'page' as a page or post slug 
          unset( $query_string['name'] );
          unset( $query_string['page'] )  ;

          // for a category archive, proper pagination query string  is 'paged'
          $query_string['paged'] = ( int ) $paged;
        }
    }   
    return $query_string;
}
add_filter( 'request', 'wptd_fix_category_pagination' );