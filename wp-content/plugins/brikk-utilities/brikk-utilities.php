<?php

/**
 * The plugin bootstrap file
 *
 * Plugin Name:       Brikk Utilities
 * Plugin URI:        n/a
 * Description:       Provides additional utilities for the Brikk theme
 * Version:           1.5.3
 * Author:            Ivaylo Zahariev
 * Author URI:        n/a
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       brikk-utilities
 * Domain Path:       /languages
 */

if( ! defined( 'ABSPATH' ) ) {
    return;
}

/*
 * human readable dump
 *
 */
if( ! function_exists('dd') ) {
    function dd( $what = '' ) {
        print '<pre class="rz-dump">';
        print_r( $what );
        print '</pre>';
    }
}

/*
 * textdomain
 *
 */
if( ! function_exists('brikk_utilities_load_textdomain') ) {
    function brikk_utilities_load_textdomain() {
    	load_plugin_textdomain( 'brikk-utilities', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }
    add_action( 'init', 'brikk_utilities_load_textdomain' );
}

define( 'BRK_UTS_PATH', wp_normalize_path( plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR ) );
define( 'BRK_UTS_URI', plugin_dir_url( __FILE__ ) );
define( 'BRK_UTS_VERSION', '1.5.1' );

if( ! function_exists('brk_utilities_enqueue_scripts') ) {
    function brk_utilities_enqueue_scripts() {
        wp_enqueue_style( 'brk-utilities-base', BRK_UTS_URI . 'assets/dist/css/base.css', [], BRK_UTS_VERSION );
    }
    add_action( 'wp_enqueue_scripts', 'brk_utilities_enqueue_scripts' );
}

if( is_admin() ) {

    // metabox
    add_action( 'add_meta_boxes' , 'br_utilities_register_meta_boxes' );

    // author wonership fix dropdown bug
    add_filter( 'wp_dropdown_users', 'br_author_override' );

}

if( ! function_exists('br_utilities_register_meta_boxes') ) {
    function br_utilities_register_meta_boxes() {

        if( ! function_exists('routiz') or ! function_exists('Brk') ) {
            return;
        }

        // page
        add_meta_box(
            'rz-page-options',
            _x( 'Page', 'Pages options in wp-admin', 'brikk-utilities' ),
            'br_utilities_meta_boxes_page',
            'page'
        );

        // post
        add_meta_box(
            'rz-post-options',
            _x( 'Page', 'Post options in wp-admin', 'brikk-utilities' ),
            'br_utilities_meta_boxes_post',
            'post'
        );

    }
}

if( ! function_exists('br_utilities_meta_boxes_page') ) {
    function br_utilities_meta_boxes_page( $post ) {
        Brk()->the_template('admin/metabox/page');
    }
}

if( ! function_exists('br_utilities_meta_boxes_post') ) {
    function br_utilities_meta_boxes_post( $post ) {
        Brk()->the_template('admin/metabox/post');
    }
}

if( ! function_exists('br_author_override') ) {
    function br_author_override( $output ) {

    	global $post, $user_ID;

    	// return if this isn't the theme author override dropdown
    	if( ! preg_match('/post_author_override/', $output) ) {
            return $output;
        }

    	// return if we've already replaced the list (end recursion)
    	if( preg_match( '/post_author_override_replaced/', $output ) ) {
            return $output;
        }

    	// replacement call to wp_dropdown_users
    	$output = wp_dropdown_users([
    		'echo' => 0,
    		'name' => 'post_author_override_replaced',
    		'selected' => empty( $post->ID ) ? $user_ID : $post->post_author,
    		'include_selected' => true
    	]);

    	// put the original name back
    	$output = preg_replace( '/post_author_override_replaced/', 'post_author_override', $output );

    	return $output;

    }
}

/*
 * Yoast SEO - add og image
 *
 */
if( ! function_exists('br_add_images') ) {
    function br_add_images( $object ) {

        if( ! function_exists('routiz') or ! function_exists('Brk') ) {
            return;
        }

        if( is_single() and is_main_query() and get_post_type() == 'rz_listing' ) {

            $obj = get_queried_object();
            if( ! $obj->ID  ) {
                return;
            }

            $listing = new \Routiz\Inc\Src\Listing\Listing( $obj->ID );

            if( ! $listing->id ) {
                return;
            }

            $gallery = $listing->get_gallery('rz_listing');

            if( isset( $gallery[0] ) ) {
                $object->add_image([
                    'url' => esc_url( $gallery[0] ),
                ]);
            }

        }

    }
    add_action( 'wpseo_add_opengraph_images', 'br_add_images' );
}

include BRK_UTS_PATH . 'includes/class.elementor.php';

/*
 * add iframe to wp post kses
 *
 */
if( ! function_exists('br_wpkses_post_tags') ) {
    function br_wpkses_post_tags( $tags, $context ) {
    	if ( 'post' === $context ) {
    		$tags['iframe'] = [
    			'src'             => true,
    			'height'          => true,
    			'width'           => true,
    			'frameborder'     => true,
    			'allowfullscreen' => true,
    		];
    	}
    	return $tags;
    }
    add_filter( 'wp_kses_allowed_html', 'br_wpkses_post_tags', 10, 2 );
}

/*
 * push pre-defined keys
 *
 */
if( ! function_exists('br_pre_defined') ) {
    function br_pre_defined( $pre_defined ) {
    	$pre_defined['rz_embed_cover'] = esc_html__('Embed Cover', 'brikk-utilities');
    	return $pre_defined;
    }
    add_filter( 'routiz/key/pre-defined', 'br_pre_defined' );
}

if( ! function_exists('br_admin_listing_filter') ) {
    function br_admin_listing_filter() {
        if( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'rz_listing' ) {

            $listing_types = [];
            $post_types = get_posts([
                'post_type' => 'rz_listing_type',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_query' => []
            ]);

            foreach( $post_types as $post_type ) {
                $listing_types[ $post_type->ID ] = $post_type->post_title;
            }

            ?>
            <select name="ulz_filter_listing_type">
                <option value=""><?php esc_html_e('All listing types', 'brikk-utilities'); ?></option>
                <?php
                    $current_v = isset( $_GET['ulz_filter_listing_type'] ) ? $_GET['ulz_filter_listing_type'] : '';
                    foreach( $listing_types as $listing_type_id => $listing_type_name ) {
                        printf('<option value="%s"%s>%s</option>',
                            $listing_type_id,
                            $listing_type_id == $current_v ? ' selected="selected"' : '',
                            $listing_type_name
                        );
                    }
                ?>
            </select>
            <?php

        }

    }
    add_action( 'restrict_manage_posts', 'br_admin_listing_filter' );
}

if( ! function_exists('br_posts_filter') ) {
    function br_posts_filter( $query ) {
        global $pagenow;

        $type = 'rz_listing';

        if( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'rz_listing' ) {
            if( is_admin() && $pagenow == 'edit.php' && isset( $_GET['ulz_filter_listing_type'] ) && $_GET['ulz_filter_listing_type'] !== '' && $query->is_main_query() ) {
                $query->query_vars['meta_key'] = 'rz_listing_type';
                $query->query_vars['meta_value'] = (int) $_GET['ulz_filter_listing_type'];
            }
        }
    }
    add_filter( 'parse_query', 'br_posts_filter' );
}