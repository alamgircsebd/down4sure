<?php
//if uninstall not called from WordPress, exit
if(!defined('WP_UNINSTALL_PLUGIN')){
	exit();
}
$theChampGeneralOptions = get_option('the_champ_general');
if(isset($theChampGeneralOptions['delete_options'])){
	global $wpdb;
	$theChampOptions = array(
		'the_champ_login',
		'the_champ_general',
		'the_champ_ss_version',
		'widget_thechamplogin',
		'heateor_ss_fb_access_token'
	);
	// For Multisite
	if(function_exists('is_multisite') && is_multisite()){
		// For Multisite
		$theChampBlogIds = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
		$theChampOriginalBlogId = $wpdb->blogid;
		foreach($theChampBlogIds as $blogId){
			switch_to_blog($blogId);
			foreach($theChampOptions as $option){
				delete_site_option($option);
			}
		}
		switch_to_blog($theChampOriginalBlogId);
	}else{
		foreach($theChampOptions as $option){
			delete_option( $option );
		}
	}
}