<?php
defined('ABSPATH') or die("Cheating........Uh!!");

/** 
 * Shortcode for Social Login.
 */ 
function the_champ_login_shortcode($params){
	
		extract(shortcode_atts(array(
			'style' => '',
			'title' => '',
			'redirect_url' => '',
			'show_username' => 'OFF'
		), $params));
		if($show_username == 'ON' && is_user_logged_in()){
			global $user_ID;
			$userInfo = get_userdata($user_ID);
			$html = "<div style='height:80px;width:180px'><div style='width:63px;float:left;'>";
			$html .= @get_avatar($user_ID, 60, $default, $alt);
			$html .= "</div><div style='float:left; margin-left:10px'>";
			$html .= str_replace('-', ' ', $userInfo->user_login);
			//do_action('the_champ_login_widget_hook', $userInfo->user_login);
			$html .= '<br/><a href="' . wp_logout_url(esc_url(home_url())) . '">' .__('Log Out', 'utillz_login') . '</a></div></div>';
		}else{
			$html = '<div ';
			// style 
			if($style != ""){
				$style = esc_attr($style);
				if(strpos($style, 'float') === false){
					$style = 'float: left;' . $style;
				}
				$html .= 'style="'.$style.'"';
			}
			$html .= '>';
			if( !is_user_logged_in() && $title != '' ) {
				$html .= '<div style="font-weight:bold" class="the_champ_social_login_title">' . ucfirst(esc_html($title)) . '</div>';
			}
			$html .= the_champ_login_button(true);
			$html .= '</div><div style="clear:both"></div>';
			if($redirect_url){
				$html .= '<script type="text/javascript">theChampCustomRedirect = "'. esc_url($redirect_url) .'";var theChampSteamAuthUrl = "",theChampTwitterAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Twitter&super_socializer_redirect_to=" + theChampCustomRedirect, theChampLineAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Line&super_socializer_redirect_to=" + theChampCustomRedirect, theChampLiveAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Live&super_socializer_redirect_to=" + theChampCustomRedirect, theChampFacebookAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Facebook&super_socializer_redirect_to=" + theChampCustomRedirect, theChampYahooAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Yahoo&super_socializer_redirect_to=" + theChampCustomRedirect, theChampGoogleAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Google&super_socializer_redirect_to=" + theChampCustomRedirect, theChampYoutubeAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Youtube&super_socializer_redirect_to=" + theChampCustomRedirect, theChampVkontakteAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Vkontakte&super_socializer_redirect_to=" + theChampCustomRedirect, theChampLinkedinAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Linkedin&super_socializer_redirect_to=" + theChampCustomRedirect, theChampInstagramAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Instagram&super_socializer_redirect_to=" + theChampCustomRedirect, theChampWordpressAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Wordpress&super_socializer_redirect_to=" + theChampCustomRedirect, theChampDribbbleAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Dribbble&super_socializer_redirect_to=" + theChampCustomRedirect, theChampGithubAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Github&super_socializer_redirect_to=" + theChampCustomRedirect, theChampSpotifyAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Spotify&super_socializer_redirect_to=" + theChampCustomRedirect, theChampKakaoAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Kakao&super_socializer_redirect_to=" + theChampCustomRedirect, theChampTwitchAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Twitch&super_socializer_redirect_to=" + theChampCustomRedirect, theChampRedditAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Reddit&super_socializer_redirect_to=" + theChampCustomRedirect, theChampDisqusAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Disqus&super_socializer_redirect_to=" + theChampCustomRedirect, theChampDropboxAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Dropbox&super_socializer_redirect_to=" + theChampCustomRedirect,  theChampFoursquareAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Foursquare&super_socializer_redirect_to=" + theChampCustomRedirect,  theChampStackoverflowAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Stackoverflow&super_socializer_redirect_to=" + theChampCustomRedirect,  theChampDiscordAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Discord&super_socializer_redirect_to=" + theChampCustomRedirect, theChampAmazonAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Amazon&super_socializer_redirect_to=" + theChampCustomRedirect, theChampMailruAuthUrl = theChampSiteUrl + "?SuperSocializerAuth=Mailru&super_socializer_redirect_to=" + theChampCustomRedirect;</script>';
			}
		}
		return $html;
	
}
add_shortcode('TheChamp-Login', 'the_champ_login_shortcode');



/** 
 * Shortcode for Social account linking
 */ 
function the_champ_social_linking_shortcode($params){
	if(the_champ_social_login_enabled()){
		extract(shortcode_atts(array(
			'style' => '',
			'title' => ''
		), $params));
		$html = '<div style="'. esc_attr($style) .'">';
		if( $title != '' ) {
			$html .= '<div style="font-weight:bold">' . ucfirst(esc_html($title)) . '</div>';
		}
		global $heateorSsAllowedTags;
		$html .= str_replace('&amp;', '&', wp_kses(the_champ_account_linking(), $heateorSsAllowedTags));
		$html .= '</div>';
		return $html;
	}
	return '<h3>' . __('Enable Social Login from "Basic Configuration" section at "Utillz Login > Social Login" page in admin panel', 'super-socializer') . '</h3>';
}
add_shortcode('TheChamp-Social-Linking', 'the_champ_social_linking_shortcode');