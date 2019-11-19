<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kenzap_gallery_list_init() {
    $locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
    $locale = apply_filters( 'plugin_locale', $locale, 'kenzap-gallery' );

    unload_textdomain( 'kenzap-gallery' );
    load_textdomain( 'kenzap-gallery', __DIR__ . '/languages/kenzap-gallery-' . $locale . '.mo' );
    load_plugin_textdomain( 'kenzap-gallery', false, __DIR__ . '/languages' );
}
add_action( 'init', 'kenzap_gallery_list_init' );

//Load body class
function kenzap_gallery_list_body_class( $classes ) {

	if ( is_array($classes) ){ $classes[] = 'kenzap'; }else{ $classes.=' kenzap'; }
	return $classes;
}
add_filter( 'body_class', 'kenzap_gallery_list_body_class' );
add_filter( 'admin_body_class', 'kenzap_gallery_list_body_class' );

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * `wp-blocks`: includes block type registration and related functions.
 *
 * @since 1.0.0
 */
function kenzap_gallery_list_block_assets() {
	// Styles.
	wp_enqueue_style(
		'kenzap_gallery_list_style-css',
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ),
		array()
	);

}

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'kenzap_gallery_list_block_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * `wp-blocks`: includes block type registration and related functions.
 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
 * `wp-i18n`: To internationalize the block's text.
 *
 * @since 1.0.0
 */
function kenzap_gallery_list_editor_assets() {
	// Scripts.
	wp_enqueue_script(
		'kenzap-gallery', // Handle.
		plugins_url( 'dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
        array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ), // Dependencies, defined above.
        // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	// Styles.
	wp_enqueue_style(
		'kenzap-gallery', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: filemtime — Gets file modification time.
    );
    
    // This is only available in WP5.
	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'kenzap-gallery', 'kenzap-gallery', KENZAP_GALLERY . '/languages/' );
	}

	$pathToPlugin = plugins_url( 'dist/', dirname( __FILE__ ) );
    wp_add_inline_script( 'wp-blocks', 'var kenzap_gallery_gutenberg_path = "' .wp_parse_url($pathToPlugin)['path'].'"', 'before');
} // End function kenzap_feature_list_cgb_editor_assets().

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'kenzap_gallery_list_editor_assets' );

function kenzap_gallery_add_specific_features( $post_object ) {
    if(!function_exists('has_blocks') || !function_exists('parse_blocks'))
        return;

    if ( has_blocks( $post_object ) ) {
        $pathToPlugin = plugins_url( 'dist/', dirname( __FILE__ ) );
        $blocks = parse_blocks( $post_object ->post_content );
        foreach ($blocks as $block) {
            if (
                $block['blockName'] == 'kenzap/gallery-1' ||
                $block['blockName'] == 'kenzap/gallery-2-1' ||
                $block['blockName'] == 'kenzap/gallery-3' ||
                $block['blockName'] == 'kenzap/gallery-5' ||
                $block['blockName'] == 'kenzap/gallery-8'
            ) {
                wp_register_script( 'fancybox', $pathToPlugin . 'fancy-box/fancybox.min.js');
                wp_enqueue_script( 'fancybox' );
                wp_register_style('fancybox', $pathToPlugin.'fancy-box/fancybox.min.css');
                wp_enqueue_style( 'fancybox');
            }
            if (
                $block['blockName'] == 'kenzap/gallery-1' ||
                $block['blockName'] == 'kenzap/gallery-2-1' ||
                $block['blockName'] == 'kenzap/gallery-5' ||
                $block['blockName'] == 'kenzap/gallery-8'
            ) {
                wp_enqueue_script( 'imagesloaded', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.1.8/imagesloaded.pkgd.min.js', array('jquery') );
                wp_register_script( 'isotope', $pathToPlugin . 'isotope.pkgd.min.js');
                wp_enqueue_script( 'isotope' );
                wp_register_script( 'packery-mode', $pathToPlugin . 'packery-mode.pkgd.min.js');
                wp_enqueue_script( 'packery-mode' );
            }

            if ( $block['blockName'] == 'kenzap/gallery-3' ) {
                wp_register_script( 'owl-carousel', $pathToPlugin . 'owl-carousel/owl-carousel.js');
                wp_enqueue_script( 'owl-carousel' );
                wp_register_style('owl-carousel', $pathToPlugin.'owl-carousel/owl-carousel.css');
                wp_enqueue_style( 'owl-carousel');
            }

            if ($block['blockName'] == 'kenzap/gallery-1') {
                wp_enqueue_script( 'kenzap/gallery-1', plugins_url( 'gallery-1/script.js', __FILE__ ), array('jquery') );
            }
            if ($block['blockName'] == 'kenzap/gallery-2-1') {
                wp_enqueue_script( 'kenzap/gallery-2', plugins_url( 'gallery-2/script.js', __FILE__ ), array('jquery') );
            }
            if ($block['blockName'] == 'kenzap/gallery-3') {
                wp_enqueue_script( 'kenzap/gallery-3', plugins_url( 'gallery-3/script.js', __FILE__ ), array('jquery') );
            }
            if ($block['blockName'] == 'kenzap/gallery-5') {
                wp_enqueue_script( 'kenzap/gallery-5', plugins_url( 'gallery-5/script.js', __FILE__ ), array('jquery') );
            }
            if ($block['blockName'] == 'kenzap/gallery-7') {
                wp_enqueue_script( 'imagesloaded', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.1.8/imagesloaded.pkgd.min.js', array('jquery') );
                wp_enqueue_script( 'kenzap/gallery-7', plugins_url( 'gallery-7/script.js', __FILE__ ), array('jquery') );
            }
            if ($block['blockName'] == 'kenzap/gallery-8') {
                wp_enqueue_script( 'kenzap/gallery-8', plugins_url( 'gallery-8/script.js', __FILE__ ), array('jquery') );
            }
        }
    }
}
add_action( 'the_post', 'kenzap_gallery_add_specific_features' );

