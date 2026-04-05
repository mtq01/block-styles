<?php


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





/* This is the OLD way.... 

// [back end] register block styles
function bs_register_block_styles()
{
    // paragraph glow
    register_block_style(
        'core/paragraph',
        array(
            'name' => 'glow', // matches name in paragraph-custom-style.json file
            'label' => __('Glow', 'block-style-theme'),
            'is_default' => false,
        )
    );

    // image shapes
    register_block_style(
        'core/image',
        array(
            'name' => 'diamond',
            'label' => 'Diamond'
        )
    );
    register_block_style(
        'core/image',
        array(
            'name' => 'star',
            'label' => 'Star'
        )
    );
    register_block_style(
        'core/image',
        array(
            'name' => 'hexagon',
            'label' => 'Hexagon'
        )
    );

    // - only load the CSS if a 'glow' paragraph block is used on the page  
    wp_enqueue_block_style('core/paragraph', array(
        'handle' => 'bs-style-glow',
        'src' => get_theme_file_uri('assets/css/blocks/paragraph-glow.css'),
        'path' => get_theme_file_path('assets/css/blocks/paragraph-glow.css')
    ));


    wp_enqueue_block_style('core/image', array(
        'handle' => 'bs-shapes-style',
        'src' => get_theme_file_uri('assets/css/blocks/image-shapes.css'),
        'path' => get_theme_file_path('assets/css/blocks/image-shapes.css')
    ));
}
add_action('init', 'bs_register_block_styles');

*/







// New Way via JSOn ------------------------------------------------------------------------------------------------------------
// [universal loader]
// 'bs_register_custom_block_styles_universal()' replaces 'bs_register_block_styles' / 'register_block_style()' 
// function. used if loading more than a couple different block styles. 
// [note]: scans '/styles/blocks/ for .json files and registers them automatically.
// [naming convention]: annoying, but the json and css file need to match for this to work & it MUST start with the blocktype (blocktype-name-name.css and blocktype-name-name.css)
    // - example: image-shapes-style.css and image-shapes-style.json
function bs_register_custom_block_styles_universal() {
    $dir = get_theme_file_path('/styles/blocks/');

    if ( is_dir( $dir ) ) {
        $files = glob( $dir . '*.json' );

        foreach ( $files as $file ) {
            $data = json_decode( file_get_contents( $file ), true );
            $slug = basename( $file, '.json' ); // e.g., 'image-shapes-style'
            
            // get block type
            $block_type = $data['blockTypes'][0];

            // register every style inside the JSON
            if ( isset( $data['styles'] ) ) {
                foreach ( $data['styles'] as $style ) {
                    register_block_style( $block_type, array(
                        'name'  => $style['name'],
                        'label' => $style['label'],
                    ) );
                }
            }

            // link the css
            wp_enqueue_block_style( $block_type, array(
                'handle' => "bs-style-$slug",
                'src'    => get_theme_file_uri( "assets/css/blocks/$slug.css" ),
                'path'   => get_theme_file_path( "assets/css/blocks/$slug.css" )
            ) );
        }
    }
}
add_action( 'init', 'bs_register_custom_block_styles_universal' );

// ------------------------------------------------------------------------------------------------------------





// [editor] code written in style.css doesnt always show in the UI unless you specifically tell it to
function bs_theme_setup()
{
    // add support for editor-specific styles
    add_theme_support('editor-styles');

    // link style.css to gutenberg editor
    add_editor_style('style.css');
}
add_action('after_setup_theme', 'bs_theme_setup');