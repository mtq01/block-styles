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
}
add_action('init', 'bs_register_block_styles');





// ------------------------------------------------------------------------------------------------------------
// [universal loader]
// 'bs_register_custom_block_styles_universal()' replaces 'bs_register_block_styles' / 'register_block_style()' 
// function. used if loading more than a couple different block styles. 
// [note]: scans '/styles/blocks/ for .json files and registers them automatically.

/*
function bs_register_custom_block_styles_universal() {
    $directory = get_theme_file_path('/styles/blocks/');

    if ( is_dir( $directory ) ) {
        $files = glob( $directory . '*.json' );

        foreach ( $files as $file ) {
            $slug = basename( $file, '.json' );
            
            // dismantle file name. ex: change "paragraph-glow" to get "paragraph" as the block_name
            // for this to work, your file name needs to start with its block type.
            // otherwise "core/$block_name" below won't register properly.
            $parts = explode('-', $slug);
            $block_name = $parts[0]; 
            $style_name = $parts[1];

            // register style in UI
            register_block_style(
                "core/$block_name",
                array(
                    'name'  => $style_name,
                    'label' => ucwords($style_name),
                    'is_default' => false,
                )
            );

            // enqueue the specific CSS file for this style (only if its used on a page)
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