<?php

/*
 * enqueue child css
 *
 */
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_style( 'brk-child-style', get_template_directory_uri() . '/style.css', ['brk-style'] );
});

?>