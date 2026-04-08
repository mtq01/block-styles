<?php
/*
[Classic]: You register the style & enqueue the CSS manually
[Modern]: JSON registers the style, you enqueue the CSS
*/

// [front end] load 'style.css' for users (all pages)
function bs_theme_enqueues()
{
    wp_enqueue_style(
        'bs-theme-styles',
        get_theme_file_uri('style.css'),
        array(),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'bs_theme_enqueues');




// [editor] code written in style.css doesnt always show in the UI unless you specifically tell it to
function bs_theme_setup()
{
    // add support for editor-specific styles
    add_theme_support('editor-styles');

    // link style.css to gutenberg editor
    add_editor_style('style.css');
}
add_action('after_setup_theme', 'bs_theme_setup');




// --------------------------------- MODERN ---------------------------------



// Modern Method
// load block-specific CSS (JSON handles registration automatically, this handles the CSS)
function bs_enqueue_block_styles()
{
    // glow
    wp_enqueue_block_style('core/paragraph', array(
        'handle' => 'bs-style-glow',
        'src'    => get_theme_file_uri('assets/css/blocks/paragraph-glow-style.css'),
        'path'   => get_theme_file_path('assets/css/blocks/paragraph-glow-style.css'),
    ));

    // img masks
    wp_enqueue_block_style('core/image', array(
        'handle' => 'bs-shapes-style',
        'src'    => get_theme_file_uri('assets/css/blocks/image-shapes-style.css'),
        'path'   => get_theme_file_path('assets/css/blocks/image-shapes-style.css'),
    ));

    // arctic
    wp_enqueue_block_style('core/image', array(
        'handle' => 'bs-arctic-style',
        'src'    => get_theme_file_uri('assets/css/blocks/paragraph-arctic-style.css'),
        'path'   => get_theme_file_path('assets/css/blocks/paragraph-arctic-style.css'),
    ));

    // checkmark list
    wp_enqueue_block_style('core/list', array(
        'handle' => 'bs-list-checkmark',
        'src'    => get_theme_file_uri('assets/css/blocks/list-checkmark-style.css'),
        'path'   => get_theme_file_path('assets/css/blocks/list-checkmark-style.css'),
    ));

    // arrow list
    wp_enqueue_block_style('core/list', array(
        'handle' => 'bs-list-arrow',
        'src'    => get_theme_file_uri('assets/css/blocks/list-arrow-style.css'),
        'path'   => get_theme_file_path('assets/css/blocks/list-arrow-style.css'),
    ));

    // emoji list
    wp_enqueue_block_style('core/list', array(
        'handle' => 'bs-list-emoji',
        'src'    => get_theme_file_uri('assets/css/blocks/list-emoji-style.css'),
        'path'   => get_theme_file_path('assets/css/blocks/list-emoji-style.css'),
    ));

    // Glow Button
    wp_enqueue_block_style('core/button', array(
        'handle' => 'bs-button-glow',
        'src'    => get_theme_file_uri('assets/css/blocks/button-glow-style.css'),
        'path'   => get_theme_file_path('assets/css/blocks/button-glow-style.css'),
    ));

    // Shadow Button
    wp_enqueue_block_style('core/button', array(
        'handle' => 'bs-button-shadow',
        'src'    => get_theme_file_uri('assets/css/blocks/button-shadow-style.css'),
        'path'   => get_theme_file_path('assets/css/blocks/button-shadow-style.css'),
    ));

    // Underline Button
    wp_enqueue_block_style('core/button', array(
        'handle' => 'bs-button-underline',
        'src'    => get_theme_file_uri('assets/css/blocks/button-underline-style.css'),
        'path'   => get_theme_file_path('assets/css/blocks/button-underline-style.css'),
    ));

    // Highlight Table
    wp_enqueue_block_style('core/table', array(
        'handle' => 'bs-table-highlight',
        'src'    => get_theme_file_uri('assets/css/blocks/table-highlight-style.css'),
        'path'   => get_theme_file_path('assets/css/blocks/table-highlight-style.css'),
    ));

}
add_action('init', 'bs_enqueue_block_styles');



/*

// Modern / Automatic [Universal Loader - Loop]
// Enqueueing multiple CSS files this way gets ugly as you add more, but you can use a loop to enqueue the CSS
// [important]: this will only work if your CSS and JSON file names match.

function bs_enqueue_block_styles_loop() {
    $dir = get_theme_file_path('styles/blocks/');

    if ( ! is_dir( $dir ) ) return;

    foreach ( glob( $dir . '*.json' ) as $file ) {
        $data = json_decode( file_get_contents( $file ), true );

        // skip if no block type defined
        if ( ! isset( $data['blockTypes'][0] ) ) continue;

        $block_type = $data['blockTypes'][0];
        $slug       = basename( $file, '.json' );
        $css_path   = get_theme_file_path( "assets/css/blocks/$slug.css" );

        if ( file_exists( $css_path ) ) {
            wp_enqueue_block_style( $block_type, array(
                'handle' => "bs-style-$slug",
                'src'    => get_theme_file_uri( "assets/css/blocks/$slug.css" ),
                'path'   => $css_path,
            ));
        }
    }
}
add_action( 'init', 'bs_enqueue_block_styles_loop' );

*/








// --------------------------------- CLASSIC ---------------------------------

/*

// Classic Method

// [back end] register block styles

function bs_register_block_styles()
{
    // register paragraph glow
    register_block_style(
        'core/paragraph',
        array(
            'name' => 'glow', // matches name in paragraph-custom-style.json file
            'label' => __('Glow', 'block-style-theme'),
            'is_default' => false,
        )
    );

    // enqueue style
    wp_enqueue_block_style('core/paragraph', array(
        'handle' => 'bs-style-glow',
        'src'    => get_theme_file_uri('assets/css/blocks/paragraph-glow-style.css'),
        'path'   => get_theme_file_path('assets/css/blocks/paragraph-glow-style.css'),
    ));

}
add_action('init', 'bs_register_block_styles');

*/




/*

// [Classic / Manual Universal Loader - LOOP]

// loops through a hardcoded list of styles and registers + enqueues each one manually
// [note]: JSON files are not used — the $styles array in this function is the source of truth
// [naming convention]: the slug key in $styles must match the CSS filename in /assets/css/blocks/
    // - example: 'image-shapes' => 'core/image'  →  assets/css/blocks/image-shapes.css

function bs_register_custom_block_styles_classic() {

    // manually map style slugs to their block type
    // this replaces what JSON would have told us automatically
    $styles = array(
        'paragraph-glow'   => 'core/paragraph',
        'image-shapes'     => 'core/image',
        'image-arctic'     => 'core/image',
        'list-checkmark'   => 'core/list',
        'list-arrow'       => 'core/list',
        'list-emoji'       => 'core/list',
    );

    foreach ( $styles as $slug => $block_type ) {

        // register the style (classic requires this, JSON does it automatically)
        register_block_style( $block_type, array(
            'name'  => $slug,
            'label' => ucwords( str_replace( '-', ' ', $slug ) ),
        ));

        // enqueue the CSS if the file exists
        $css_path = get_theme_file_path( "assets/css/blocks/$slug.css" );

        if ( file_exists( $css_path ) ) {
            wp_enqueue_block_style( $block_type, array(
                'handle' => "bs-style-$slug",
                'src'    => get_theme_file_uri( "assets/css/blocks/$slug.css" ),
                'path'   => $css_path,
            ));
        }
    }
}
add_action( 'init', 'bs_register_custom_block_styles_classic' );

// ------------------------------------------------------------------------------------------------------------

*/



