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







// [back end] register glow option in editor
function bs_register_block_styles()
{
    register_block_style(
        'core/paragraph',
        array(
            'name' => 'glow', // matches name in paragraph-custom-style.json file
            'label' => __('Glow', 'block-style-theme'),
            'is_default' => false,
        )
    );

    /* +++++++++ overkill for this assignment, but great for performnace. 

        - only load the CSS if a 'glow' paragraph block is used on the page  */
    wp_enqueue_block_style('core/paragraph', array(
        'handle' => 'bs-style-glow',
        'src' => get_theme_file_uri('assets/css/blocks/paragraph-glow.css'),
        'path' => get_theme_file_path('assets/css/blocks/paragraph-glow.css')
    ));

     register_block_style(
        'core/paragraph',
        array(
            'name' => 'arctic', 
            'label' => __('Arctic', 'block-style-theme'),
            'is_default' => false,
        )
    );

    wp_enqueue_block_style('core/paragraph', array(
        'handle' => 'bs-arctic-style',
        'src' => get_theme_file_uri('assets/css/blocks/paragraph-arctic.css'),
        'path' => get_theme_file_path('assets/css/blocks/paragraph-arctic.css')
    ));




}
add_action('init', 'bs_register_block_styles');





// ------------------------------------------------------------------------------------------------------------
// [universal loader]
// 'bs_register_custom_block_styles_universal()' replaces 'bs_register_block_styles' / 'register_block_style()' 
// function. used if loading more than a couple different block styles. 
// [note]: scans '/styles/blocks/ for .json files and registers them automatically.

/*
function bs_register_custom_block_styles_universal() {
    $dir = get_theme_file_path('/styles/blocks/');

    if ( is_dir( $dir ) ) {
        $files = glob( $dir . '*.json' );

        foreach ( $files as $file ) {
            $slug = basename( $file, '.json' );
            $parts = explode('-', $slug);

            // shift the first word out as the block type
            $block_name = array_shift($parts); 
            
            // join everything else as the style name
            $style_name = implode('-', $parts); 

            // register the block style variant
            register_block_style(
                "core/$block_name",
                array(
                    'name'  => $style_name,
                    'label' => ucwords(str_replace('-', ' ', $style_name)),
                    'is_default' => false,
                )
            );

            // load the matching CSS file (only if its on page)
            wp_enqueue_block_style("core/$block_name", array(
                'handle' => "bs-style-$slug",
                'src'    => get_theme_file_uri("/assets/css/blocks/$slug.css"),
                'path'   => get_theme_file_path("/assets/css/blocks/$slug.css"),
            ));
        }
    }
}
add_action('init', 'bs_register_custom_block_styles_universal');

*/

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