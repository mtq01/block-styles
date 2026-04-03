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
        'handle' => 'bs-glow-style',
        'src' => get_theme_file_uri('assets/css/blocks/paragraph-glow.css'),
        'path' => get_theme_file_path('assets/css/blocks/paragraph-glow.css')
    ));
}
add_action('init', 'bs_register_block_styles');





// [editor] code written in style.css doesnt always show in the UI unless you specifically tell it to
function bs_theme_setup()
{
    // add support for editor-specific styles
    add_theme_support('editor-styles');

    // link style.css to gutenberg editor
    add_editor_style('style.css');
}
add_action('after_setup_theme', 'bs_theme_setup');