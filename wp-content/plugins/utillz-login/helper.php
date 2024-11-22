<?php
/**
 * Display notification message when plugin options are saved
 */
function the_champ_settings_saved_notification(){
	if(isset($_GET['settings-updated']) && sanitize_text_field($_GET['settings-updated']) == 'true'){
		return '<div class="notice notice-success is-dismissible"><p><strong>'.__('Settings saved', 'utillz-login').'</strong></p></div>';
	}
}

/**
 * Display Social Login notifications
 */
function the_champ_login_notifications($loginOptions){
	$errorHtml = '';
	$google_key = get_option('rz_google_client_id');
	$google_secret = get_option('rz_google_client_secret');
	$fb_app_id = get_option('rz_facebook_app_id');
	$fb_secret = get_option('rz_facebook_app_secret');
	if(isset($loginOptions['enable']) && isset($loginOptions['providers'])){

		if(in_array('facebook', $loginOptions['providers']) && (!isset($fb_app_id) || $fb_app_id == '' || !isset($fb_secret) || $fb_secret == '')){
			$errorHtml .= the_champ_error_message('Specify Facebook App ID and Secret in the <strong>Super Socializer</strong> > <strong>Social Login</strong> section in the admin panel for Facebook Login to work');
		}
		
		if((in_array('google', $loginOptions['providers']) || in_array('youtube', $loginOptions['providers'])) && (!isset($google_key) || $google_key == '' || !isset($google_secret) || $google_secret == '')){
			$errorHtml .= the_champ_error_message('Specify Google Client ID and Secret in the <strong>Utillz login</strong> > <strong>Social Login</strong> section in the admin panel for Google and Youtube Login to work');
		}
	}
	return $errorHtml;
}

/**
 * General options page of plugin in admin area
 */
function the_champ_general_options_page(){
	// facebook options
	global $theChampGeneralOptions, $heateorSsAllowedTags;
	// message on saving options
	echo wp_kses(the_champ_settings_saved_notification(), $heateorSsAllowedTags);
	require 'admin/general_options.php';
}

/**
 * Facebook option page of plugin in WP admin.
 */
function the_champ_facebook_page(){
	// facebook options
	global $theChampFacebookOptions, $heateorSsAllowedTags;
	// message on saving options
	echo wp_kses(the_champ_settings_saved_notification(), $heateorSsAllowedTags);
	require 'admin/social_commenting.php';
}

/**
 * Social Login page of plugin in WP admin
 */
function the_champ_social_login_page(){
	// social login options
	global $theChampLoginOptions, $theChampFacebookOptions, $theChampIsBpActive, $heateorSsAllowedTags;
	// message on saving options
	echo wp_kses(the_champ_settings_saved_notification(), $heateorSsAllowedTags);
	echo wp_kses(the_champ_login_notifications($theChampLoginOptions), $heateorSsAllowedTags);
	require 'admin/social_login.php';
}


/** 
 * Validate plugin options.
 *
 * IMPROVEMENT: complexity can be reduced (this function is called on each option page validation and "if($k == 'providers'){"
 * condition is being checked every time)
 */ 
function the_champ_validate_options($theChampOptions){
	foreach($theChampOptions as $k => $v){
		if(is_string($v)){
			$theChampOptions[$k] = esc_attr(trim($v));
		}
	}
	return $theChampOptions;
}

/**
 * Register plugin settings and its sanitization callback
 */
function the_champ_options_init(){
	register_setting('the_champ_facebook_options', 'the_champ_facebook', 'the_champ_validate_options');
	register_setting('the_champ_login_options', 'the_champ_login', 'the_champ_validate_options');
	register_setting('the_champ_sharing_options', 'the_champ_sharing', 'the_champ_validate_options');
	register_setting('the_champ_counter_options', 'the_champ_counter', 'the_champ_validate_options');
	register_setting('the_champ_general_options', 'the_champ_general', 'the_champ_validate_options');
	
}
add_action('admin_init', 'the_champ_options_init');

/**
 * Include javascript files in admin
 */	
function the_champ_admin_scripts(){
	?>
	<script>var theChampWebsiteUrl = '<?php echo esc_url(home_url()) ?>', theChampHelpBubbleTitle = "<?php echo __('Click to toggle help', 'utillz-login') ?>"; </script>
	<?php
	wp_enqueue_script('the_champ_admin_script', plugins_url('js/admin/admin.js', __FILE__), array('jquery', 'jquery-ui-tabs'));
}

/**
 * Include Javascript SDK in admin
 */	
function the_champ_fb_sdk_script(){
	wp_enqueue_script('the_champ_fb_sdk_script', plugins_url('js/admin/fb_sdk.js', __FILE__), false);
}


/**
 * Include CSS files in admin.
 */	
function the_champ_admin_style(){
	wp_enqueue_style('the_champ_admin_style', plugins_url('css/admin.css', __FILE__), false);
}


/**
 * Update CSS file
 */
function the_champ_update_css($replace_color_option, $logo_color_option, $css_file){	
	global $theChampSharingOptions;
	if($theChampSharingOptions[$replace_color_option] != $theChampSharingOptions[$logo_color_option]){
		$path = plugin_dir_url(__FILE__).'css/'.$css_file.'.css';
		try{
			$content = file($path );
			if($content !== false){
				$handle = fopen(dirname(__FILE__).'/css/'.$css_file.'.css','w');
				if($handle !== false){
					foreach ($content as $value){
					    fwrite($handle, str_replace( str_replace('#', '%23', $theChampSharingOptions[$replace_color_option]), str_replace('#', '%23', $theChampSharingOptions[$logo_color_option]), $value));
					}
					fclose($handle);
					$theChampSharingOptions[$replace_color_option] = $theChampSharingOptions[$logo_color_option];
					update_option('the_champ_sharing', $theChampSharingOptions);
					return true;
				}
			}
		}catch(Exception $e){
			return false;
		}
	}
	return false;
}


/**
 * Return ajax response
 */
function the_champ_ajax_response($response){
	$response = apply_filters('the_champ_ajax_response_filter', $response);
	die(json_encode($response));
}

/**
 * Show notification in popup
 */
function the_champ_notify(){
	if(isset($_GET['message'])){
		?>
		<div><?php echo esc_html($_GET['message']) ?></div>
		<?php
	}
	die;
}
add_action('wp_ajax_nopriv_the_champ_notify', 'the_champ_notify');

/**
 * Check if Social Login is enabled.
 */
function the_champ_social_login_enabled(){
    $google_enable= get_option('rz_enable_google_auth');
	$fb_enable= get_option('rz_enable_facebook_auth');
	global $theChampLoginOptions;
	if($fb_enable == 1 or $google_enable == 1 ){
		return true;
	}else{
		return false;
	}
}

function the_champ_facebook_commenting_enabled(){
	global $theChampFacebookOptions;
	if(isset($theChampFacebookOptions['enable_fbcomments'])){
		return true;
	}else{
		return false;
	}
}

/**
 * Check if Social Login from particular provider is enabled.
 */
function the_champ_social_login_provider_enabled($provider){
	global $theChampLoginOptions;
	if(the_champ_social_login_enabled() && isset($theChampLoginOptions['providers']) && in_array($provider, $theChampLoginOptions['providers'])){
		return true;
	}else{
		return false;
	}
}

/**
 * Check if Social commenting is enabled
 */
function the_champ_social_commenting_enabled(){
	global $theChampFacebookOptions;
	if(isset($theChampFacebookOptions['enable_commenting'])){
		return true;
	}else{
		return false;
	}
}

/**
 * Check if any Facebook plugin is enabled
 */
function the_champ_facebook_plugin_enabled(){
	global $theChampFacebookOptions, $theChampCounterOptions;
	if((the_champ_social_commenting_enabled() && the_champ_facebook_commenting_enabled()) || the_champ_facebook_like_rec_enabled()){
		return true;
	}else{
		return false;
	}
}

/**
 * Log errors/exceptions
 */
function the_champ_log_error($error){
	error_log(PHP_EOL.'['.date('m/d/Y h:i:s a', time()).'] '.$error, 3, plugin_dir_path(__FILE__).'log.txt');
}

/**
 * Return error message HTML
 */
function the_champ_error_message($error, $heading = false){
	$html = "";
	$html .= "<div class='the_champ_error'>";
	if($heading){
		$html .= "<p style='color: black'><strong>Utillz login: </strong></p>";
	}
	$html .= "<p style ='color:red; margin: 0'>".__($error, 'utillz-login')."</p></div>";
	return $html;
}

// if multisite is enabled and this is the main website
if(is_multisite() && is_main_site()){
	/**
	 * replicate the options to the new blog created
	 */
	function the_champ_replicate_settings($blogId){
		global $theChampFacebookOptions, $theChampLoginOptions, $theChampSharingOptions;
		add_blog_option($blogId, 'the_champ_facebook', $theChampFacebookOptions);
		add_blog_option($blogId, 'the_champ_login', $theChampLoginOptions);
		add_blog_option($blogId, 'the_champ_sharing', $theChampSharingOptions);
	}
	add_action('wpmu_new_blog', 'the_champ_replicate_settings');
	
	/**
	 * update the social login options in all the old blogs
	 */
	function the_champ_update_old_blogs($oldConfig){
		$optionParts = explode('_', current_filter());
		$option = $optionParts[2].'_'.$optionParts[3].'_'.$optionParts[4];
		$newConfig = get_option($option);
		if(isset($newConfig['config_multisite']) && $newConfig['config_multisite'] == 1){
			$blogs = get_blog_list(0, 'all');
			foreach($blogs as $blog){
				update_blog_option($blog['blog_id'], $option, $newConfig);
			}
		}
	}
    add_action('update_option_the_champ_login', 'the_champ_update_old_blogs');
	add_action('update_option_the_champ_facebook', 'the_champ_update_old_blogs');
	add_action('update_option_the_champ_sharing', 'the_champ_update_old_blogs');
}

function the_champ_account_linking(){
	if(is_user_logged_in()){
		global $theChampFacebookOptions, $theChampLoginOptions, $user_ID;
		if(!utillz_ul_check_if_admin($user_ID)){
			wp_enqueue_style('the-champ-frontend-css', plugins_url('css/front.css', __FILE__), false);
			$twitterRedirect = urlencode(the_champ_get_valid_url(the_champ_get_http().$_SERVER["HTTP_HOST"].remove_query_arg(array('linked'))));
			$currentPageUrl = urldecode($twitterRedirect);
			$html = '<script>function theChampLoadEvent(e){var t=window.onload;if(typeof window.onload!="function"){window.onload=e}else{window.onload=function(){t();e()}}} var theChampCloseIconPath="'. plugins_url('images/close.png', __FILE__) .'";</script>';
			// general (required) scripts
			wp_enqueue_script('the_champ_ss_general_scripts', plugins_url('js/front/social_login/general.js', __FILE__), false);
			$websiteUrl = esc_url(home_url());
			$html .= '<script>var theChampLinkingRedirection="'. the_champ_get_http().$_SERVER["HTTP_HOST"].remove_query_arg(array('linked')). '",theChampSameTabLogin=' . ( isset( $theChampLoginOptions["same_tab_login"] ) ? 1 : 0 ) . ';var theChampSiteUrl="'. $websiteUrl .'";var theChampVerified = 0;var theChampAjaxUrl="' .admin_url(). 'admin-ajax.php"; var theChampPopupTitle = ""; var theChampEmailPopup = 0; var theChampEmailAjaxUrl = "'. admin_url() .'/admin-ajax.php"; var theChampEmailPopupTitle = ""; var theChampEmailPopupErrorMsg = ""; var theChampEmailPopupUniqueId = ""; var theChampEmailPopupVerifyMessage = ""; var theChampCurrentPageUrl = "'. $twitterRedirect .'";</script>';
			// scripts used for common Social Login functionality
			if(the_champ_social_login_enabled()){
				$loadingImagePath = plugins_url('images/ajax_loader.gif', __FILE__);
				$theChampAjaxUrl = get_admin_url().'admin-ajax.php';
				$redirectionUrl = the_champ_get_login_redirection_url();
				$regRedirectionUrl = the_champ_get_login_redirection_url('', true);
				global $theChampSteamLogin;
				$html .= '<style type="text/css">#ss_openid{border:1px solid gray;display:inline;font-family:"Trebuchet MS";font-size:12px;width:98%;padding:.35em .325em .75em;margin-bottom:20px}#ss_openid form{margin-top:25px;margin-left:0;padding:0;background:transparent;-webkit-box-shadow:none;box-shadow:none}#ss_openid input{font-family:"Trebuchet MS";font-size:12px;width:100px;float:left}#ss_openid input[type=submit]{background:#767676;padding:.75em 2em;border:0;-webkit-border-radius:2px;border-radius:2px;-webkit-box-shadow:none;box-shadow:none;color:#fff;cursor:pointer;display:inline-block;font-weight:800;line-height:1;text-shadow:none;-webkit-transition:background .2s;transition:background .2s}#ss_openid legend{color:#FF6200;float:left;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:table;max-width:100%;padding:0;white-space:normal}#ss_openid input.openid_login{background-color:#fff;background-position:0 50%;color:#000;width:220px;margin-right:10px;height:30px;margin-bottom:5px;background:#fff;background-image:-webkit-linear-gradient(rgba(255,255,255,0),rgba(255,255,255,0));border:1px solid #bbb;-webkit-border-radius:3px;border-radius:3px;display:block;padding:.7em;line-height:1.5}#ss_openid a{color:silver}#ss_openid a:hover{color:#5e5e5e}</style>';
				$html .= '<script>var theChampLoadingImgPath = "' .$loadingImagePath. '"; var theChampAjaxUrl = "' .$theChampAjaxUrl. '"; var theChampRedirectionUrl = "' .$redirectionUrl. '"; var theChampRegRedirectionUrl = "' .$regRedirectionUrl. '", theChampSteamAuthUrl = "' .($theChampSteamLogin ? $theChampSteamLogin->url( esc_url(home_url()).'?SuperSocializerSteamAuth='.$twitterRedirect ) : ''). '"; var heateorMSEnabled = 0, theChampTwitterAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Twitter&super_socializer_redirect_to=" + theChampCurrentPageUrl, theChampLineAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Line&super_socializer_redirect_to=" + theChampCurrentPageUrl, theChampLiveAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Live&super_socializer_redirect_to=" + theChampCurrentPageUrl, theChampFacebookAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Facebook&super_socializer_redirect_to=" + theChampCurrentPageUrl, theChampYahooAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Yahoo&super_socializer_redirect_to=" + theChampCurrentPageUrl, theChampGoogleAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Google&super_socializer_redirect_to=" + theChampCurrentPageUrl, theChampYoutubeAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Youtube&super_socializer_redirect_to=" + theChampCurrentPageUrl, theChampVkontakteAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Vkontakte&super_socializer_redirect_to=" + theChampCurrentPageUrl, theChampLinkedinAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Linkedin&super_socializer_redirect_to=" + theChampCurrentPageUrl, theChampInstagramAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Instagram&super_socializer_redirect_to=" + theChampCurrentPageUrl, theChampWordpressAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Wordpress&super_socializer_redirect_to=" + theChampCurrentPageUrl, theChampDribbbleAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Dribbble&super_socializer_redirect_to=" + theChampCurrentPageUrl, theChampGithubAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Github&super_socializer_redirect_to=" + theChampCurrentPageUrl, theChampSpotifyAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Spotify&super_socializer_redirect_to=" + theChampCurrentPageUrl, theChampKakaoAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Kakao&super_socializer_redirect_to=" + theChampCurrentPageUrl,theChampTwitchAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Twitch&super_socializer_redirect_to=" + theChampCurrentPageUrl,theChampDropboxAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Dropbox&super_socializer_redirect_to=" + theChampCurrentPageUrl,theChampRedditAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Reddit&super_socializer_redirect_to=" + theChampCurrentPageUrl,theChampFoursquareAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Foursquare&super_socializer_redirect_to=" + theChampCurrentPageUrl,theChampDisqusAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Disqus&super_socializer_redirect_to=" + theChampCurrentPageUrl,theChampAmazonAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Amazon&super_socializer_redirect_to=" + theChampCurrentPageUrl,theChampStackoverflowAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Stackoverflow&super_socializer_redirect_to=" + theChampCurrentPageUrl,theChampDiscordAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Discord&super_socializer_redirect_to=" + theChampCurrentPageUrl, theChampMailruAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Mailru&super_socializer_redirect_to=" + theChampCurrentPageUrl</script>';
				$userVerified = false;
				$ajaxUrl = 'admin-ajax.php';
				$notification = '';
				wp_enqueue_script('the_champ_sl_common', plugins_url('js/front/social_login/common.js', __FILE__), array('jquery'));
			}
			// linking functions
			wp_enqueue_script('the_champ_ss_linking_script', plugins_url('js/front/social_login/linking.js', __FILE__), array('jquery'));
			$html .= '<style type="text/css">table.heateor-ss-table td{padding: 10px;}div.utillz_ul_sl_optin_container a{color:blue}div.utillz_ul_sl_optin_container label{font-size:11px;font-weight:normal}input.utillz_ul_social_login_optin{vertical-align:middle}@media screen and (max-width:783px){div.utillz_ul_sl_optin_container{margin-top:10px}div.utillz-login-linking-container{width:99%}div.utillz-login-linking-container table td{display:table-cell}}</style>';

			$html .= '<div class="metabox-holder columns-2 utillz-login-linking-container" id="post-body">
	            <div class="stuffbox" style="padding-bottom:10px">
	                <div class="inside" style="padding:0">
	                    <table class="form-table editcomment heateor-ss-table">
	                        <tbody>';
	                        if(isset($_GET['linked'])){
	                        	if(intval($_GET['linked']) == 1){
		                        	$html .= '<tr>
		                        		<td colspan="2" style="color: green">'.__('Account linked successfully', 'utillz-login').'</td>
		                        	</tr>';
	                        	}elseif(intval($_GET['linked']) == 0){
		                        	$html .= '<tr>
		                        		<td colspan="2" style="color: red">'.__('Account already exists or linked', 'utillz-login').'</td>
		                        	</tr>';
	                        	}
	                        }
	                        $icons_container = '<div class="the_champ_login_container">';
	                        if(isset($theChampLoginOptions['gdpr_enable'])){
								$gdprOptIn = '<div class="utillz_ul_sl_optin_container"><label><input type="checkbox" class="utillz_ul_social_login_optin" value="1" />'. str_replace(array($theChampLoginOptions['ppu_placeholder'], $theChampLoginOptions['tc_placeholder']), array('<a href="'.$theChampLoginOptions['privacy_policy_url'] .'" target="_blank">'.$theChampLoginOptions['ppu_placeholder'] .'</a>', '<a href="'.$theChampLoginOptions['tc_url'] .'" target="_blank">'.$theChampLoginOptions['tc_placeholder'] .'</a>'), wp_strip_all_tags($theChampLoginOptions['privacy_policy_optin_text'])).'</label></div>';
							}
							if(isset($theChampLoginOptions['gdpr_enable']) && $theChampLoginOptions['gdpr_placement'] == 'above'){
								$icons_container .= $gdprOptIn;
							}
	                        $icons_container .= '<ul class="the_champ_login_ul">';
							$existingProviders = array();
							$primarySocialNetwork = get_user_meta($user_ID, 'thechamp_provider', true);
							$existingProviders[] = $primarySocialNetwork;
							$linkedAccounts = get_user_meta($user_ID, 'thechamp_linked_accounts', true);
							if($linkedAccounts){
								$linkedAccounts = maybe_unserialize($linkedAccounts);
								$linkedProviders = array_keys($linkedAccounts);
								$existingProviders = array_merge($existingProviders, $linkedProviders);
							}
							
							if(isset($theChampLoginOptions['providers'])){
								$existingProviders = array_diff($theChampLoginOptions['providers'], $existingProviders);
	                        }
							if(count($existingProviders) > 0){
	                        $html .= '<tr>
	                            <td colspan="2"><strong>'.$theChampLoginOptions['scl_title'].'</strong><br/>';
								foreach($existingProviders as $provider){
									$icons_container .= '<li><i ';
									// id
									if($provider == 'google'){
										$icons_container .= 'id="theChamp'. ucfirst($provider).'Button" ';
									}

									// class
									$icons_container .= 'class="theChampLogin theChamp'. ucfirst($provider).'Background theChamp'. ucfirst($provider).'Login" ';
									$icons_container .= 'alt="'.__('Login with', 'utillz-login').' ';
									$icons_container .= ucfirst($provider);
									$icons_container .= '" title="'.__('Login with', 'utillz-login').' ';
									$icons_container .= ucfirst($provider);
									if(current_filter() == 'comment_form_top'){
										$icons_container .= '" onclick="theChampCommentFormLogin = true; theChampInitiateLogin(this, \''. $provider .'\')" >';
									}else{
										$icons_container .= '" onclick="theChampInitiateLogin(this, \''. $provider .'\')" >';
									}
									if($provider == 'facebook'){
										$icons_container .= '<div class="theChampFacebookLogoContainer">';
									}
									$icons_container .= '<div class="theChampLoginSvg theChamp'. ucfirst($provider). 'LoginSvg"></div>';
									if($provider == 'facebook'){
										$icons_container .= '</div>';
									}
									$icons_container .= '</i></li>';
								}
								$icons_container .= '</ul>';
								if(isset($theChampLoginOptions['gdpr_enable']) && $theChampLoginOptions['gdpr_placement'] == 'below'){
									$icons_container .= '<div style="clear:both"></div>';
									$icons_container .= $gdprOptIn;
								}
								$icons_container .= '</div>';
								$html .= $icons_container;
		                        $html .= '</td>
		                        </tr>';
		                    }
	                        $html .= '<tr>
	                            <td colspan="2">';
	                            	if(is_array($linkedAccounts) || $primarySocialNetwork){
	                            		$html .= '<table>
	                            		<tbody>';
	                            		$primarySocialId = get_user_meta($user_ID, 'thechamp_social_id', true);
	                            		if($primarySocialNetwork && $primarySocialId){
	                            			$current = get_user_meta($user_ID, 'thechamp_current_id', true) == get_user_meta($user_ID, 'thechamp_social_id', true);
		                            		$html .= '<tr>
		                            		<td style="padding: 0">'. ($current ? '<strong>'.__('Currently', 'utillz-login').' </strong>' : '').__('Connected with', 'utillz-login').' <strong>'. ucfirst($primarySocialNetwork).'</strong></td><td><input type="button" onclick="theChampUnlink(this, \''.$primarySocialNetwork .'\')" value="'.__('Remove', 'utillz-login').'" /></td></tr>';
	                            		}
	                            		if(is_array($linkedAccounts) && count($linkedAccounts) > 0){
	                            			foreach($linkedAccounts as $key => $value){
		                            			$current = get_user_meta($user_ID, 'thechamp_current_id', true) == $value;
		                            			$html .= '<tr>
		                            			<td style="padding: 0">'. ($current ? '<strong>'.__('Currently', 'utillz-login').' </strong>' : '').__('Connected with', 'utillz-login').' <strong>'. ucfirst($key).'</strong></td><td><input type="button" onclick="theChampUnlink(this, \''.$key .'\')" value="'.__('Remove', 'utillz-login').'" /></td></tr>';
		                            		}
	                            		}
	                            		$html .= '</tbody>
	                            		</table>';
	                            	}
	                            $html .= '</td>
	                        </tr>
	                    	</tbody>
	                    </table>
	                </div>
	            </div>
	        </div>';
	        return $html;
	    }
	}
	return '';
}

add_action('admin_notices', 'the_champ_user_profile_account_linking');
add_action('bp_setup_nav', 'the_champ_add_linking_tab', 100);

function the_champ_user_profile_account_linking(){
	if(the_champ_social_login_enabled()){
		global $pagenow;
		if($pagenow == 'profile.php'){
			global $heateorSsAllowedTags;
			echo str_replace('&amp;', '&', wp_kses(the_champ_account_linking(), $heateorSsAllowedTags));
		}
	}
}

/**
 * Unlink the social account
 */
function the_champ_unlink(){
	if(isset($_POST['provider'])){
		global $user_ID;
		$linkedAccounts = get_user_meta($user_ID, 'thechamp_linked_accounts', true);
		$primarySocialNetwork = get_user_meta($user_ID, 'thechamp_provider', true);
		if($linkedAccounts || $primarySocialNetwork){
			$socialNetworkToUnlink = sanitize_text_field($_POST['provider']);
			$linkedAccounts = maybe_unserialize($linkedAccounts);
			$currentSocialId = get_user_meta($user_ID, 'thechamp_current_id', true);
			if($primarySocialNetwork == $socialNetworkToUnlink){
				if($currentSocialId == get_user_meta($user_ID, 'thechamp_social_id', true)){
					delete_user_meta($user_ID, 'thechamp_current_id');
				}
				delete_user_meta($user_ID, 'thechamp_social_id');
				delete_user_meta($user_ID, 'thechamp_provider');
				delete_user_meta($user_ID, 'thechamp_large_avatar');
				delete_user_meta($user_ID, 'thechamp_avatar');
			}else{
				if($currentSocialId == $linkedAccounts[$socialNetworkToUnlink]){
					delete_user_meta($user_ID, 'thechamp_current_id');
				}
				unset($linkedAccounts[$socialNetworkToUnlink]);	
				update_user_meta($user_ID, 'thechamp_linked_accounts', maybe_serialize($linkedAccounts));
			}
			the_champ_ajax_response(array('status' => 1, 'message' => ''));
		}
	}
	die;
}
add_action('wp_ajax_the_champ_unlink', 'the_champ_unlink');

function the_champ_add_linking_tab(){
	if(bp_is_my_profile() && the_champ_social_login_enabled()){
		global $theChampLoginOptions;
		if(isset($theChampLoginOptions['bp_linking'])){
			global $bp, $user_ID;
			if($user_ID){
				bp_core_new_subnav_item( array(
						'name' => __('Social Account Linking', 'utillz-login'),
						'slug' => 'account-linking',
						'parent_url' => trailingslashit( bp_loggedin_user_domain().'profile'),
						'parent_slug' => 'profile',
						'screen_function' => 'the_champ_bp_linking',
						'position' => 50
					)
				);
			}
		}
	}
}

function the_champ_bp_account_linking(){
	global $heateorSsAllowedTags;
	echo str_replace('&amp;', '&', wp_kses(the_champ_account_linking(), $heateorSsAllowedTags));
}

// show social account linking when 'Social Account Linking' tab is clicked
function the_champ_bp_linking(){
	add_action('bp_template_content', 'the_champ_bp_account_linking');
	bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
}

/**
 * Set BP active flag to true
 */
function the_champ_bp_loaded(){
	global $theChampIsBpActive;
	$theChampIsBpActive = true;
}
add_action('bp_include', 'the_champ_bp_loaded');

/**
 * Return the string after capitalizing first letter
 */
function the_champ_first_letter_uppercase($word){
	return ucfirst($word);
}


/**
 * Save sharing meta fields
 */
function the_champ_save_sharing_meta($postId){
    // make sure data came from our meta box
    if(!isset($_POST['the_champ_meta_nonce']) || !wp_verify_nonce($_POST['the_champ_meta_nonce'], __FILE__)){
		return $postId;
 	}
    // check user permissions
    if($_POST['post_type'] == 'page'){
        if(!current_user_can('edit_page', $postId)){
			return $postId;
    	}
	}else{
        if(!current_user_can('edit_post', $postId)){
			return $postId;
    	}
	}
    if(isset($_POST['_the_champ_meta'])){
		$newData = array_map("the_champ_sanitize_post_meta", $_POST['_the_champ_meta']);
	}else{
		$newData = array('sharing' => 0, 'vertical_sharing' => 0, 'counter' => 0, 'vertical_counter' => 0, 'fb_comments' => 0);
	}
	update_post_meta($postId, '_the_champ_meta', $newData);
    return $postId;
}

/**
 * Override sanitize_user function to allow cyrillic usernames
 */
function the_champ_sanitize_user($username, $rawUsername, $strict){
	global $theChampLoginOptions;
	if(isset($theChampLoginOptions['allow_cyrillic']) && is_array($theChampLoginOptions['allow_cyrillic']) && count($theChampLoginOptions['allow_cyrillic']) > 0){
		$username = wp_strip_all_tags($rawUsername);
		$username = remove_accents($username);
		$username = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '', $username);
		$username = preg_replace('/&.+?;/', '', $username);
		// If strict, reduce to ASCII and Cyrillic characters for max portability.
		if($strict){
			$regex = '';
			if(in_array('cyrillic', $theChampLoginOptions['allow_cyrillic'])){
				$regex .= '\p{Cyrillic}0-9 _.';
			}
			if(in_array('arabic', $theChampLoginOptions['allow_cyrillic'])){
				$regex .= '\p{Arabic}';
			}
			if(in_array('chinese', $theChampLoginOptions['allow_cyrillic'])){
				$regex .= '\p{Han}0-9 _.';
			}
			$username = preg_replace('|[^a-z'. $regex .'\-@]|iu', '', $username);
		}
		$username = trim($username);
		// Consolidate contiguous whitespace
		$username = preg_replace('|\s+|', ' ', $username);
	}

	return $username;
}
add_filter('sanitize_user', 'the_champ_sanitize_user', 10, 3);

/**
 * Show options to update social avatar at BuddyPress "Change Avatar" page
 */
function the_champ_social_avatar_options(){
   
	global $user_ID, $theChampLoginOptions;
	if( isset($theChampLoginOptions['avatar']) && isset($theChampLoginOptions['avatar_options'])){
		if(isset($_POST['ss_dontupdate_avatar'])){
			$dontUpdateAvatar = intval($_POST['ss_dontupdate_avatar']);
			update_user_meta($user_ID, 'thechamp_dontupdate_avatar', $dontUpdateAvatar);
		}else{
			$dontUpdateAvatar = get_user_meta($user_ID, 'thechamp_dontupdate_avatar', true);
		}
		if(isset($_POST['ss_small_avatar']) && utillz_ul_validate_url($_POST['ss_small_avatar']) !== false){
			$updatedSmallAvatar = str_replace('http://', '//', esc_url_raw(trim($_POST['ss_small_avatar'])));
			update_user_meta($user_ID, 'thechamp_avatar', $updatedSmallAvatar);
		}
		if(isset($_POST['ss_large_avatar']) && utillz_ul_validate_url($_POST['ss_large_avatar']) !== false){
			$updatedLargeAvatar = str_replace('http://', '//', esc_url_raw(trim($_POST['ss_large_avatar'])));
			update_user_meta($user_ID, 'thechamp_large_avatar', $updatedLargeAvatar);
		}
		?>
		<div class="profile" style="margin-bottom:20px">
			<form action="" method="post" class="standard-form base">
				<h4><?php _e('Social Avatar', 'utillz-login') ?></h4>
				<div class="clear"></div>
				<div class="editfield field_name visibility-public field_type_textbox">
					<label for="ss_dontupdate_avatar_1"><input id="ss_dontupdate_avatar_1" style="margin-right:5px" type="radio" name="ss_dontupdate_avatar" value="1" <?php echo $dontUpdateAvatar ? 'checked' : '' ?> /><?php _e('Do not fetch and update social avatar from my profile, next time I Social Login', 'utillz-login') ?></label>
					<label for="ss_dontupdate_avatar_0"><input id="ss_dontupdate_avatar_0" style="margin-right:5px" type="radio" name="ss_dontupdate_avatar" value="0" <?php echo ! $dontUpdateAvatar ? 'checked' : '' ?> /><?php _e('Update social avatar, next time I Social Login', 'utillz-login') ?></label>
				</div>
				<div class="editfield field_name visibility-public field_type_textbox">
					<label for="ss_small_avatar"><?php _e('Small Avatar', 'utillz-login') ?></label>
					<input id="ss_small_avatar" type="text" name="ss_small_avatar" value="<?php echo esc_attr(isset($updatedSmallAvatar) ? $updatedSmallAvatar : get_user_meta($user_ID, 'thechamp_avatar', true)) ?>" />
				</div>
				<div class="editfield field_name visibility-public field_type_textbox">
					<label for="ss_large_avatar"><?php _e('Large Avatar', 'utillz-login') ?></label>
					<input id="ss_large_avatar" type="text" name="ss_large_avatar" value="<?php echo esc_attr(isset($updatedLargeAvatar) ? $updatedLargeAvatar : get_user_meta($user_ID, 'thechamp_large_avatar', true)) ?>" />
				</div>
				<div class="submit">
					<input type="submit" value="<?php _e('Save Changes', 'utillz-login') ?>" />
				</div>
			</form>
		</div>
		<?php
	}
}
add_action('bp_before_profile_avatar_upload_content', 'the_champ_social_avatar_options');

/**
 * Clear short url cache
 */
function the_champ_clear_shorturl_cache(){
	global $wpdb;
	$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = '_the_champ_ss_bitly_url'");
	die;
}
add_action('wp_ajax_the_champ_clear_shorturl_cache', 'the_champ_clear_shorturl_cache');


/**
 * Stop unverified users from logging in.
 */
function utillz_ul_filter_login($user, $username, $password){
	$tempUser = get_user_by('login', $username);
	if(isset($tempUser->data->ID)){
		$id = $tempUser->data->ID;
		if($id != 1 && get_user_meta($id, 'thechamp_key', true) != ''){
			global $heateorSsLoginAttempt;
			$heateorSsLoginAttempt = 1;
			return null;
		}
	}
	return $user;
}
add_filter('authenticate', 'utillz_ul_filter_login', 40, 3);

/**
 * Show message, if an unverified user logs in via login form
 */
function utillz_ul_login_error_message($error){
	global $heateorSsLoginAttempt;
	//check if unverified user has attempted to login
	if($heateorSsLoginAttempt == 1){
		$error = __('Please verify your email address to login.', 'utillz-login');
	}
	return $error;
}
add_filter('login_errors', 'utillz_ul_login_error_message');

/**
 * Check if url is in valid format
 */
function utillz_ul_validate_url($url){
	return filter_var(trim($url), FILTER_VALIDATE_URL);
}

/**
 * Check if plugin is active
 */
function utillz_ul_is_plugin_active($pluginFile){
	return in_array($pluginFile, apply_filters('active_plugins', get_option('active_plugins')));
}

/**
 * Add column in the user list to delete social profile data
 */
function utillz_ul_add_custom_column($columns){
	$columns['utillz_ul_delete_profile_data'] = 'Delete Social Profile';
	return $columns;
}
add_filter('manage_users_columns', 'utillz_ul_add_custom_column');

/**
 * Show option to delete social profile in the custom column
 */
function utillz_ul_delete_profile_column($value, $columnName, $userId){
	if('utillz_ul_delete_profile_data' == $columnName){
		global $wpdb;
		$socialUser = $wpdb->get_var($wpdb->prepare('SELECT user_id FROM '.$wpdb->prefix.'usermeta WHERE user_id = %d and meta_key LIKE "thechamp%"', $userId));
		if($socialUser > 0){
			return '<a href="javascript:void(0)" title="'.__('Click to delete social profile data', 'utillz-login').'" alt="'.__('Click to delete social profile data', 'utillz-login').'" onclick="javascript:heateorSsDeleteSocialProfile(this, '.$userId .')">Delete</a>';
		}
	}
}
add_action('manage_users_custom_column', 'utillz_ul_delete_profile_column', 1, 3);

/**
 * Include thickbox js and css
 */
function utillz_ul_include_thickbox(){
	global $parent_file;
	if($parent_file == 'users.php'){
		wp_enqueue_script('jquery');
		wp_enqueue_script('thickbox', null, array('jquery'));
		wp_enqueue_style('thickbox');
	}
}
add_action('admin_enqueue_scripts', 'utillz_ul_include_thickbox');

/**
 * Script to delete social profile
 */
function utillz_ul_delete_social_profile_script(){
	global $parent_file;
	if($parent_file == 'users.php'){
		?>
		<script type="text/javascript">
			function heateorSsDeleteSocialProfile(elem, userId){
               	var parentElement = jQuery(elem).parent();
                jQuery(parentElement).html('<span><?php _e('Deleting', 'utillz-login'); ?>...</span>');
                jQuery.ajax({
                    type: 'GET',
                    url: '<?php echo get_admin_url() ?>admin-ajax.php',
                    data: {
                        action: 'utillz_ul_delete_social_profile',
                        user_id: userId
                    },
                    success: function(data, textStatus, XMLHttpRequest){
                        if(data == 'done'){
                            jQuery(parentElement).html('<?php _e('Deleted', 'utillz-login'); ?>');
                        }else{
                            jQuery(parentElement).html('<?php _e('Something bad happened', 'utillz-login'); ?>');
                        }
                    }
                });
            }
		</script>
		<?php
	}
}
add_action('admin_head', 'utillz_ul_delete_social_profile_script');

/**
 * Delete social profile of the user
 */
function utillz_ul_delete_social_profile(){
	if(isset($_GET['user_id'])){
		$userId = intval(trim($_GET['user_id']));
		global $wpdb;
		$wpdb->query($wpdb->prepare('DELETE FROM '.$wpdb->prefix.'usermeta WHERE user_id = %d and meta_key LIKE "thechamp%"', $userId));
		die('done');
	}
	die;
}
add_action('wp_ajax_utillz_ul_delete_social_profile', 'utillz_ul_delete_social_profile');

/**
 * Add safe styles to the existing list of allowed styles via wp_kses
 */
function utillz_ul_add_safe_styles($styles){
	$styles[] = 'display';
	$styles[] = 'position';
	$styles[] = 'left';
	$styles[] = 'right';
	$styles[] = 'top';
	$styles[] = 'box-shadow';
	$styles[] = 'opacity';
	$styles[] = 'background-repeat';
	$styles[] = 'box-sizing';
	$styles[] = '-webkit-clip-path';
	$styles[] = 'clip';
	$styles[] = 'visibility';

	return $styles;
}
add_filter('safe_style_css', 'utillz_ul_add_safe_styles', 10, 1);

/**
 * Check if current page is an AMP
 */
function the_champ_is_amp_page(){
	if((function_exists('is_amp_endpoint') && is_amp_endpoint()) || (function_exists('ampforwp_is_amp_endpoint') && ampforwp_is_amp_endpoint())){
		return true;
	}
	return false;
}