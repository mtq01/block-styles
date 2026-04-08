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

    // Custom List Style: Checkmark
    register_block_style(
        'core/list',
        array(
            'name' => 'checkmark',
            'label' => __('Checkmark', 'block-style-theme'),
            'is_default' => false,
        )
    );

    wp_enqueue_block_style('core/list', array(
        'handle' => 'bs-list-checkmark',
        'src' => get_theme_file_uri('assets/css/blocks/list-checkmark.css'),
        'path' => get_theme_file_path('assets/css/blocks/list-checkmark.css')
    ));

    // Custom List Style: Arrow
    register_block_style(
        'core/list',
        array(
            'name' => 'arrow',
            'label' => __('Arrow', 'block-style-theme'),
            'is_default' => false,
        )
    );

    wp_enqueue_block_style('core/list', array(
        'handle' => 'bs-list-arrow',
        'src' => get_theme_file_uri('assets/css/blocks/list-arrow.css'),
        'path' => get_theme_file_path('assets/css/blocks/list-arrow.css')
    ));

    // Custom List Style: Emoji
    register_block_style(
        'core/list',
        array(
            'name' => 'emoji',
            'label' => __('Emoji', 'block-style-theme'),
            'is_default' => false,
        )
    );

    wp_enqueue_block_style('core/list', array(
        'handle' => 'bs-list-emoji',
        'src' => get_theme_file_uri('assets/css/blocks/list-emoji.css'),
        'path' => get_theme_file_path('assets/css/blocks/list-emoji.css')
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
    // path to json folder
    $dir = get_theme_file_path('/styles/blocks/');

    // safety: skip if directory does not exist
    if ( is_dir( $dir ) ) {
        
        // find every .json file in the folder
        $files = glob( $dir . '*.json' );

        foreach ( $files as $file ) {
            // convert JSON into an associative array
            $data = json_decode( file_get_contents( $file ), true );
            
            // grab filename (ex: 'image-shapes-style' or 'paragraph-glow-style')
            $slug = basename( $file, '.json' ); 
            
            // safety: skip file if 'blockTypes' is missing
            if ( ! isset( $data['blockTypes'][0] ) ) {
                continue;
            }

            $block_type = $data['blockTypes'][0];

            // registartion
            // if the JSON has a 'styles' array (like my image-shapes-style.json file)
            if ( isset( $data['styles'] ) && is_array( $data['styles'] ) ) {
                foreach ( $data['styles'] as $style ) {

                    // [prevent warnings] check if keys exist before registering
                    if ( isset( $style['name'] ) && isset( $style['label'] ) ) {
                        register_block_style( $block_type, array(
                            'name'  => $style['name'],
                            'label' => $style['label'],
                        ) );
                    }
                }
            } 
            
            // if the JSON is a single style (like the paragraph-glow-style.json file)
            // use 'title' from the JSON and the second half of the filename as the 'name'
            elseif ( isset( $data['title'] ) ) {
                // extracts 'glow' from 'paragraph-glow'
                $name_parts = explode( '-', $slug );
                $style_name = ( count( $name_parts ) > 1 ) ? $name_parts[1] : $slug;

                register_block_style( $block_type, array(
                    'name'  => $style_name,
                    'label' => $data['title'],
                ) );
            }

            // style loading: links the json filename to css filename in /assets/css/blocks/
             
            $css_uri  = "assets/css/blocks/$slug.css";
            $css_path = get_theme_file_path( $css_uri );

            // enqueue if the physical CSS file exists
            if ( file_exists( $css_path ) ) {
                wp_enqueue_block_style( $block_type, array(
                    'handle' => "bs-style-$slug",
                    'src'    => get_theme_file_uri( $css_uri ),
                    'path'   => $css_path
                ) );
            }
        }
    }
}
// hook into 'init' & register styles when wp loads
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