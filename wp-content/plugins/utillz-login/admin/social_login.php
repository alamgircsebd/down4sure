<?php defined('ABSPATH') or die("Cheating........Uh!!"); ?>
<div id="fb-root"></div>
	
	<div class="metabox-holder">
		<form action="options.php" method="post" id="utillz_login_form">
		<?php settings_fields('the_champ_login_options'); ?>

		<div class="stuffbox" style="width:98.7%">
			<h3><label><?php _e('Master Control', 'utillz-login');?></label></h3>
			<div class="inside" style="padding:5px;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table">
					<tr>
						<th>
						<label for="the_champ_login_enable"><?php _e("Enable Social Login", 'utillz-login'); ?></label>
						</th>
						<td>
						    <?php $google_enable= get_option('rz_enable_google_auth');
						    	  $fb_enable= get_option('rz_enable_facebook_auth'); 

						    ?>
						<input id="the_champ_login_enable" name="the_champ_login[enable]" type="checkbox" <?php  if($google_enable == '1' or $fb_enable == 1 ){ echo 'checked'; } else{ echo ''; }?> value="1" />
						</td>
					</tr>
					
					<tr class="the_champ_help_content" id="the_champ_sl_enable_help_cont">
						<td colspan="2">
						<div>
						<?php _e('Master control for Social Login. It must be checked to enable Social Login functionality', 'utillz-login') ?>
						</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
<?php $google_enable= get_option('rz_enable_google_auth');
						    	  $fb_enable= get_option('rz_enable_facebook_auth'); 

						    ?>
		<div class="menu_div" id="tabs" <?php echo (isset($google_enable) || isset($fb_enable)) ? '' : 'style="display:none"' ?>>

			<h2 class="nav-tab-wrapper" style="height:34px">
			<ul>
				<li style="margin-left:9px"><a style="margin:0; line-height:auto !important; height:23px" class="nav-tab" href="#tabs-1"><?php _e('Basic Configuration', 'utillz-login') ?></a></li>
				<li style="margin-left:9px"><a style="margin:0; line-height:auto !important; height:23px" class="nav-tab" href="#tabs-2"><?php _e('Advanced Configuration', 'utillz-login') ?></a></li>
			
				<?php if($theChampIsBpActive){ ?>
				<li style="margin-left:9px"><a style="margin:0; line-height:auto !important; height:23px" class="nav-tab" href="#tabs-4"><?php _e('XProfile Integration', 'utillz-login') ?></a></li>
				<?php } ?>
			</ul>
			</h2>
			<div class="menu_containt_div" id="tabs-1">
				<div class="clear"></div>
				<div class="the_champ_left_column">
				<div class="stuffbox">
					<h3 class="hndle"><label><?php _e('Basic Configuration', 'utillz-login');?></label></h3>
					<div class="inside">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table">
						
					
						<tr>
							<th>
							<label><?php _e("Select Social Networks", 'utillz-login'); ?></label>
							</th>
							<td>
							<div class="theChampHorizontalSharingProviderContainer">
								<?php $fb_auth= get_option('rz_enable_facebook_auth'); ?>
							<input id="the_champ_login_facebook" name="the_champ_login[providers][]" type="checkbox" <?php if($fb_auth == 1) {echo 'checked';}else{echo '';} ?> value="facebook" />
							<label for="the_champ_login_facebook"><?php _e("Facebook", 'utillz-login'); ?></label>
							</div>
							
							<div class="theChampHorizontalSharingProviderContainer">
							    <?php $google_auth= get_option('rz_enable_google_auth'); ?>
							<input id="the_champ_login_google" name="the_champ_login[providers][]" type="checkbox" <?php if($google_auth == 1) {echo 'checked';}else{echo '';} ?> value="google" />
							<label for="the_champ_login_google"><?php _e("Google", 'utillz-login'); ?></label>
							</div>
							
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_providers_help_cont">
							<td colspan="2">
							<div>
							<?php _e('Select Social ID provider to enable in Social Login', 'utillz-login') ?>
							</div>
							</td>
						</tr>
						<tbody <?php echo isset($theChampLoginOptions['providers']) && in_array('facebook', $theChampLoginOptions['providers']) ? '' : 'style="display:table-row-group"'; ?>>
						<tr>
							<th>
							<label for="the_champ_fblogin_key"><?php _e("Facebook App ID", 'utillz-login'); ?></label>
							</th>
							<td>
								<?php $fb_app_id = get_option('rz_facebook_app_id'); ?>
							<input id="the_champ_fblogin_key" name="the_champ_login[fb_key]" type="text" value="<?php echo $fb_app_id; ?>" />
							</td>
						</tr>

						<tr>
							<th>
							<label for="the_champ_fblogin_secret"><?php _e("Facebook App Secret", 'utillz-login'); ?></label>
							</th>
							<td>
								<?php $fb_secret = get_option('rz_facebook_app_secret'); ?>
							<input id="the_champ_fblogin_secret" name="the_champ_login[fb_secret]" type="text" value="<?php echo $fb_secret; ?>" />
							</td>
						</tr>
						
						
						</tbody>
						
						<tbody  <?php echo isset($theChampLoginOptions['providers']) && (in_array('google', $theChampLoginOptions['providers']) || in_array('youtube', $theChampLoginOptions['providers'])) ? '' : 'style="display:table-row-group"'; ?>>
						<tr>
							<th>
							<label for="the_champ_gplogin_key"><?php _e("Google Client ID", 'utillz-login'); ?></label>
							</th>
							<td>
							    <?php $google_key = get_option('rz_google_client_id'); ?>
							<input id="the_champ_gplogin_key" name="the_champ_login[google_key]" type="text" value="<?php echo $google_key; ?>" />
							</td>
						</tr>
						<tr>
							<th>
							<label for="the_champ_gplogin_secret"><?php _e("Google Client Secret", 'utillz-login'); ?></label>
							</th>
							<td>
							    <?php $google_secret = get_option('rz_google_client_secret'); ?>
							<input id="the_champ_gplogin_secret" name="the_champ_login[google_secret]" type="text" value="<?php echo $google_secret; ?>" />
							</td>
						</tr>
						
					
						</tbody>

						
					</table>
					</div>
				</div>
				</div>
			</div>
			
			<div class="menu_containt_div" id="tabs-2">
				<div class="clear"></div>
				<div class="the_champ_left_column">
				<div class="stuffbox">
					<h3 class="hndle"><label><?php _e('Social Login Options', 'utillz-login');?></label></h3>
					<div class="inside">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table">
						<tr>
							<th>
							<label for="the_champ_fblogin_title"><?php _e("Title", 'utillz-login'); ?></label><img id="the_champ_sl_title_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_fblogin_title" name="the_champ_login[title]" type="text" value="<?php echo isset($theChampLoginOptions['title']) ? $theChampLoginOptions['title'] : '' ?>" />
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_title_help_cont">
							<td colspan="2">
							<div>
							<?php _e('Text to display above the Social Login interface', 'utillz-login') ?>
							</div>
							<img src="<?php echo plugins_url('../images/snaps/title.png', __FILE__); ?>" />
							</td>
						</tr>
						
						<tr>
							<th>
							<label for="the_champ_sl_same_tab"><?php _e("Trigger social login in the same browser tab", 'utillz-login'); ?></label><img id="the_champ_sl_same_tab_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_sl_same_tab" name="the_champ_login[same_tab_login]" type="checkbox" <?php echo isset($theChampLoginOptions['same_tab_login']) ? 'checked' : '';?> value="1" />
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_same_tab_help_cont">
							<td colspan="2">
							<div>
							<?php _e('Trigger social login in the same browser tab instead of a popup window', 'utillz-login') ?>
							</div>
							</td>
						</tr>

						<tr>
							<th>
							<label for="the_champ_sl_align"><?php _e("Center align icons", 'utillz-login'); ?></label><img id="the_champ_sl_align_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_sl_align" name="the_champ_login[center_align]" type="checkbox" <?php echo isset($theChampLoginOptions['center_align']) ? 'checked' : '';?> value="1" />
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_align_help_cont">
							<td colspan="2">
							<div>
							<?php _e('Center align social login icons', 'utillz-login') ?>
							</div>
							</td>
						</tr>

						<tr>
							<th>
							<label for="the_champ_fblogin_enableAtLogin"><?php _e("Enable at login page", 'utillz-login'); ?></label><img id="the_champ_sl_loginpage_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_fblogin_enableAtLogin" name="the_champ_login[enableAtLogin]" type="checkbox" <?php echo isset($theChampLoginOptions['enableAtLogin']) ? 'checked' : '';?> value="1" />
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_loginpage_help_cont">
							<td colspan="2">
							<div>
							<?php _e('Social Login interface will get enabled at the login page of your website', 'utillz-login') ?>
							</div>
							</td>
						</tr>
						
						<tr>
							<th>
							<label for="the_champ_fblogin_enableAtRegister"><?php _e("Enable at register page", 'utillz-login'); ?></label><img id="the_champ_sl_regpage_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_fblogin_enableAtRegister" name="the_champ_login[enableAtRegister]" type="checkbox" <?php echo isset($theChampLoginOptions['enableAtRegister']) ? 'checked' : '';?> value="1" />
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_regpage_help_cont">
							<td colspan="2">
							<div>
							<?php _e('Social Login interface will get enabled at the registration page of your website', 'utillz-login') ?>
							</div>
							</td>
						</tr>
						
						<tr>
							<th>
							<label for="the_champ_fblogin_enableAtComment"><?php _e("Enable at comment form", 'utillz-login'); ?></label><img id="the_champ_sl_cmntform_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_fblogin_enableAtComment" name="the_champ_login[enableAtComment]" type="checkbox" <?php echo isset($theChampLoginOptions['enableAtComment']) ? 'checked' : '';?> value="1" />
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_cmntform_help_cont">
							<td colspan="2">
							<div>
							<?php _e('Social Login interface will get enabled at your Wordpress Comment form', 'utillz-login') ?>
							</div>
							<img src="<?php echo plugins_url('../images/snaps/sl_wpcomment.png', __FILE__); ?>" />
							</td>
						</tr>

						<?php
						/**
						 * Check if WooCommerce is active
						 **/
						if ( utillz_ul_is_plugin_active('woocommerce/woocommerce.php') ) {
						    ?>
						    <tr>
								<th>
								<label for="the_champ_sl_wc_before_form"><?php _e("Enable before WooCommerce Customer Login Form", 'utillz-login'); ?></label><img id="the_champ_sl_wc_before_form_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
								</th>
								<td>
								<input id="the_champ_sl_wc_before_form" name="the_champ_login[enable_before_wc]" type="checkbox" <?php echo isset($theChampLoginOptions['enable_before_wc']) ? 'checked' : '';?> value="1" />
								</td>
							</tr>
							
							<tr class="the_champ_help_content" id="the_champ_sl_wc_before_form_help_cont">
								<td colspan="2">
								<div>
								<?php _e('Social Login Interface will get enabled before the customer login form at WooCommerce My Account page', 'utillz-login') ?>
								</div>
								</td>
							</tr>

							<tr>
								<th>
								<label for="the_champ_sl_wc_after_form"><?php _e("Enable at WooCommerce Customer Login Form", 'utillz-login'); ?></label><img id="the_champ_sl_wc_after_form_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
								</th>
								<td>
								<input id="the_champ_sl_wc_after_form" name="the_champ_login[enable_after_wc]" type="checkbox" <?php echo isset($theChampLoginOptions['enable_after_wc']) ? 'checked' : '';?> value="1" />
								</td>
							</tr>
							
							<tr class="the_champ_help_content" id="the_champ_sl_wc_after_form_help_cont">
								<td colspan="2">
								<div>
								<?php _e('Integrate Social Login Interface with the customer login form at WooCommerce My Account page', 'utillz-login') ?>
								</div>
								</td>
							</tr>

							<tr>
								<th>
								<label for="the_champ_sl_wc_register_form"><?php _e("Enable at WooCommerce Customer Register Form", 'utillz-login'); ?></label><img id="the_champ_sl_wc_register_form_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
								</th>
								<td>
								<input id="the_champ_sl_wc_register_form" name="the_champ_login[enable_register_wc]" type="checkbox" <?php echo isset($theChampLoginOptions['enable_register_wc']) ? 'checked' : '';?> value="1" />
								</td>
							</tr>
							
							<tr class="the_champ_help_content" id="the_champ_sl_wc_after_form_help_cont">
								<td colspan="2">
								<div>
								<?php _e('Integrate Social Login Interface with the customer register form at WooCommerce My Account page', 'utillz-login') ?>
								</div>
								</td>
							</tr>

							<tr>
								<th>
								<label for="the_champ_sl_wc_checkout"><?php _e("Enable at WooCommerce checkout page", 'utillz-login'); ?></label><img id="the_champ_sl_wc_checkout_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
								</th>
								<td>
								<input id="the_champ_sl_wc_checkout" name="the_champ_login[enable_wc_checkout]" type="checkbox" <?php echo isset($theChampLoginOptions['enable_wc_checkout']) ? 'checked' : '';?> value="1" />
								</td>
							</tr>
							
							<tr class="the_champ_help_content" id="the_champ_sl_wc_checkout_help_cont">
								<td colspan="2">
								<div>
								<?php _e('Social Login Interface will get enabled at WooCommerce checkout page', 'utillz-login') ?>
								</div>
								</td>
							</tr>
						    <?php
						}
						if(!isset($theChampFacebookOptions['force_fb_comment'])){
							?>
							<tr>
								<th>
								<label for="the_champ_approve_comment"><?php _e("Auto-approve comments made by Social Login users", 'utillz-login'); ?></label><img id="the_champ_approve_comment_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
								</th>
								<td>
								<input id="the_champ_approve_comment" name="the_champ_login[autoApproveComment]" type="checkbox" <?php echo isset($theChampLoginOptions['autoApproveComment']) ? 'checked' : '';?> value="1" />
								</td>
							</tr>
							
							<tr class="the_champ_help_content" id="the_champ_approve_comment_help_cont">
								<td colspan="2">
								<div>
								<?php _e('If this option is enabled, and WordPress comment is made by Social Login user, comment will get approved immediately without keeping in moderation.', 'utillz-login') ?><br/>
								<strong><?php _e('Note: This is not related to Facebook comments', 'utillz-login') ?></strong>
								</div>
								</td>
							</tr>
							<?php
						}
						?>
						<tr>
							<th>
							<label for="the_champ_login_avatar"><?php _e("Enable social avatar", 'utillz-login'); ?></label><img id="the_champ_sl_avatar_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_login_avatar" onclick="if(this.checked){jQuery('#the_champ_avatar_options').css('display', 'table-row-group')}else{ jQuery('#the_champ_avatar_options').css('display', 'none') }" name="the_champ_login[avatar]" type="checkbox" <?php echo isset($theChampLoginOptions['avatar']) ? 'checked' : '';?> value="1" />
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_avatar_help_cont">
							<td colspan="2">
							<div>
							<?php _e('Social profile pictures of the logged in user will be displayed as profile avatar', 'utillz-login') ?>
							</div>
							<img src="<?php echo plugins_url('../images/snaps/sl_wpavatar.png', __FILE__); ?>" />
							<img src="<?php echo plugins_url('../images/snaps/sl_wpavatar2.png', __FILE__); ?>" />
							</td>
						</tr>
						<tbody id="the_champ_avatar_options" <?php echo !isset($theChampLoginOptions['avatar']) ? 'style = "display: none"' : '';?> >
						<tr>
							<th>
							<label><?php _e("Avatar quality", 'utillz-login'); ?></label><img id="the_champ_sl_avatar_quality_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_login_average_avatar" name="the_champ_login[avatar_quality]" type="radio" <?php echo !isset($theChampLoginOptions['avatar_quality']) || $theChampLoginOptions['avatar_quality'] == 'average' ? 'checked' : '';?> value="average" /> <label for="the_champ_login_average_avatar"><?php _e("Average", 'utillz-login'); ?></label><br/>
							<input id="the_champ_login_better_avatar" name="the_champ_login[avatar_quality]" type="radio" <?php echo isset($theChampLoginOptions['avatar_quality']) && $theChampLoginOptions['avatar_quality'] == 'better' ? 'checked' : '';?> value="better" /> <label for="the_champ_login_better_avatar"><?php _e("Best", 'utillz-login'); ?></label>
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_avatar_quality_help_cont">
							<td colspan="2">
							<div>
							<?php _e('Choose avatar quality', 'utillz-login') ?>
							</div>
							</td>
						</tr>

						<?php if($theChampIsBpActive){ ?>
						<tr>
							<th>
							<label for="the_champ_sl_avatar_options"><?php _e("Show option for users to update social avatar at BuddyPress profile page", 'utillz-login'); ?></label><img id="the_champ_sl_avatar_options_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_sl_avatar_options" name="the_champ_login[avatar_options]" type="checkbox" <?php echo isset($theChampLoginOptions['avatar_options']) ? 'checked' : '';?> value="1" />
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_avatar_options_help_cont">
							<td colspan="2">
							<div>
							<?php _e('If enabled, users would be able to update their social avatar from "Profile photo" section in BuddyPress profile at front-end', 'utillz-login') ?>
							</div>
							</td>
						</tr>
						<?php } ?>

						</tbody>
						
						<tr>
							<th>
							<label for="the_champ_login_email_required"><?php _e("Email required", 'utillz-login'); ?></label><img id="the_champ_sl_emailreq_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input onclick="theChampEmailPopupOptions(this)" id="the_champ_login_email_required" name="the_champ_login[email_required]" type="checkbox" <?php echo isset($theChampLoginOptions['email_required']) ? 'checked' : '';?> value="1" />
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_emailreq_help_cont">
							<td colspan="2">
							<div>
							<?php _e('If enabled and Social ID provider does not provide user\'s email address on login, user will be asked to provide his/her email address. Otherwise, a dummy email will be generated', 'utillz-login') ?>
							</div>
							<img src="<?php echo plugins_url('../images/snaps/sl_email_required.png', __FILE__); ?>" />
							</td>
						</tr>
						
						<tr>
							<th>
							<label for="the_champ_password_email"><?php _e("Send post-registration email to user to set account password", 'utillz-login'); ?></label><img id="the_champ_sl_postreg_email_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_password_email" name="the_champ_login[password_email]" type="checkbox" <?php echo isset($theChampLoginOptions['password_email']) ? 'checked' : '';?> value="1" />
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_postreg_email_help_cont">
							<td colspan="2">
							<div>
							<?php _e('If enabled, an email will be sent to user after registration through Social Login, regarding his/her login credentials (username-password to be able to login via traditional login form)', 'utillz-login') ?>
							</div>
							</td>
						</tr>

						<tr>
							<th>
							<label for="the_champ_sl_postreg_admin_email"><?php _e("Send new user registration notification email to admin", 'utillz-login'); ?></label><img id="the_champ_sl_postreg_admin_email_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_sl_postreg_admin_email" name="the_champ_login[new_user_admin_email]" type="checkbox" <?php echo isset($theChampLoginOptions['new_user_admin_email']) ? 'checked' : '';?> value="1" />
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_postreg_admin_email_help_cont">
							<td colspan="2">
							<div>
							<?php _e('If enabled, an email will be sent to admin after new user registers through Social Login, notifying admin about the new user registration', 'utillz-login') ?>
							</div>
							</td>
						</tr>

						<tr>
							<th>
							<label><?php _e("Login redirection", 'utillz-login'); ?></label><img id="the_champ_sl_loginredirect_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td id="the_champ_login_redirection_column">
							<input id="the_champ_login_redirection_same" name="the_champ_login[login_redirection]" type="radio" <?php echo !isset($theChampLoginOptions['login_redirection']) || $theChampLoginOptions['login_redirection'] == 'same' ? 'checked' : '';?> value="same" />
							<label for="the_champ_login_redirection_same"><?php _e('Same page where user logged in', 'utillz-login') ?></label><br/>
							<input id="the_champ_login_redirection_home" name="the_champ_login[login_redirection]" type="radio" <?php echo isset($theChampLoginOptions['login_redirection']) && $theChampLoginOptions['login_redirection'] == 'homepage' ? 'checked' : '';?> value="homepage" />
							<label for="the_champ_login_redirection_home"><?php _e('Homepage', 'utillz-login') ?></label><br/>
							<input id="the_champ_login_redirection_account" name="the_champ_login[login_redirection]" type="radio" <?php echo isset($theChampLoginOptions['login_redirection']) && $theChampLoginOptions['login_redirection'] == 'account' ? 'checked' : '';?> value="account" />
							<label for="the_champ_login_redirection_account"><?php _e('Account dashboard', 'utillz-login') ?></label><br/>
							<?php if($theChampIsBpActive){ ?>
								<input id="the_champ_login_redirection_bp" name="the_champ_login[login_redirection]" type="radio" <?php echo isset($theChampLoginOptions['login_redirection']) && $theChampLoginOptions['login_redirection'] == 'bp_profile' ? 'checked' : '';?> value="bp_profile" />
								<label for="the_champ_login_redirection_bp"><?php _e('BuddyPress profile page', 'utillz-login') ?></label><br/>
							<?php } ?>
							<input id="the_champ_login_redirection_custom" name="the_champ_login[login_redirection]" type="radio" <?php echo isset($theChampLoginOptions['login_redirection']) && $theChampLoginOptions['login_redirection'] == 'custom' ? 'checked' : '';?> value="custom" />
							<label for="the_champ_login_redirection_custom"><?php _e('Custom Url', 'utillz-login') ?></label><br/>
							<input id="the_champ_login_redirection_url" name="the_champ_login[login_redirection_url]" type="text" value="<?php echo isset($theChampLoginOptions['login_redirection_url']) ? $theChampLoginOptions['login_redirection_url'] : '' ?>" />
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_loginredirect_help_cont">
							<td colspan="2">
							<div>
							<?php _e('User will be redirected to the selected page after Social Login', 'utillz-login') ?>
							</div>
							</td>
						</tr>
						
						<tr>
							<th>
							<label><?php _e("Registration redirection", 'utillz-login'); ?></label><img id="the_champ_sl_register_redirect_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td id="the_champ_register_redirection_column">
							<input id="the_champ_register_redirection_same" name="the_champ_login[register_redirection]" type="radio" <?php echo !isset($theChampLoginOptions['register_redirection']) || $theChampLoginOptions['register_redirection'] == 'same' ? 'checked' : '';?> value="same" />
							<label for="the_champ_register_redirection_same"><?php _e('Same page from where user registered', 'utillz-login') ?></label><br/>
							<input id="the_champ_register_redirection_home" name="the_champ_login[register_redirection]" type="radio" <?php echo isset($theChampLoginOptions['register_redirection']) && $theChampLoginOptions['register_redirection'] == 'homepage' ? 'checked' : '';?> value="homepage" />
							<label for="the_champ_register_redirection_home"><?php _e('Homepage', 'utillz-login') ?></label><br/>
							<input id="the_champ_register_redirection_account" name="the_champ_login[register_redirection]" type="radio" <?php echo isset($theChampLoginOptions['register_redirection']) && $theChampLoginOptions['register_redirection'] == 'account' ? 'checked' : '';?> value="account" />
							<label for="the_champ_register_redirection_account"><?php _e('Account dashboard', 'utillz-login') ?></label><br/>
							<?php if($theChampIsBpActive){ ?>
								<input id="the_champ_register_redirection_bp" name="the_champ_login[register_redirection]" type="radio" <?php echo isset($theChampLoginOptions['register_redirection']) && $theChampLoginOptions['register_redirection'] == 'bp_profile' ? 'checked' : '';?> value="bp_profile" />
								<label for="the_champ_register_redirection_bp"><?php _e('BuddyPress profile page', 'utillz-login') ?></label><br/>
							<?php } ?>
							<input id="the_champ_register_redirection_custom" name="the_champ_login[register_redirection]" type="radio" <?php echo isset($theChampLoginOptions['register_redirection']) && $theChampLoginOptions['register_redirection'] == 'custom' ? 'checked' : '';?> value="custom" />
							<label for="the_champ_register_redirection_custom"><?php _e('Custom Url', 'utillz-login') ?></label><br/>
							<input id="the_champ_register_redirection_url" name="the_champ_login[register_redirection_url]" type="text" value="<?php echo isset($theChampLoginOptions['register_redirection_url']) ? $theChampLoginOptions['register_redirection_url'] : '' ?>" />
							</td>
						</tr>
						<tr>
							<th>
							<label><?php _e("Username Separator", 'utillz-login'); ?></label><img id="the_champ_sl_username_separator_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_sl_username_separator_dash" name="the_champ_login[username_separator]" type="radio" <?php echo !isset($theChampLoginOptions['username_separator']) || $theChampLoginOptions['username_separator'] == 'dash' ? 'checked' : '';?> value="dash" />
							<label for="the_champ_sl_username_separator_dash"><?php _e('Dash (-)', 'utillz-login') ?></label><br/>
							<input id="the_champ_sl_username_separator_underscore" name="the_champ_login[username_separator]" type="radio" <?php echo isset($theChampLoginOptions['username_separator']) && $theChampLoginOptions['username_separator'] == 'underscore' ? 'checked' : '';?> value="underscore" />
							<label for="the_champ_sl_username_separator_underscore"><?php _e('Underscore (_)', 'utillz-login') ?></label><br/>
							<input id="the_champ_sl_username_separator_dot" name="the_champ_login[username_separator]" type="radio" <?php echo isset($theChampLoginOptions['username_separator']) && $theChampLoginOptions['username_separator'] == 'dot' ? 'checked' : '';?> value="dot" />
							<label for="the_champ_sl_username_separator_dot"><?php _e('Dot (.)', 'utillz-login') ?></label><br/>
							<input id="the_champ_sl_username_separator_none" name="the_champ_login[username_separator]" type="radio" <?php echo isset($theChampLoginOptions['username_separator']) && $theChampLoginOptions['username_separator'] == 'none' ? 'checked' : '';?> value="none" />
							<label for="the_champ_sl_username_separator_none"><?php _e('None', 'utillz-login') ?></label><br/>
							
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_username_separator_help_cont">
							<td colspan="2">
							<div>
							<?php _e('Choose one of the underscore, dot or dash to use to join first and last names in the usernames of the new users', 'utillz-login') ?>
							</div>
							</td>
						</tr>

						<tr>
							<th>
							<label for="the_champ_sl_username_email"><?php _e("Generate Username from Email address", 'utillz-login'); ?></label><img id="the_champ_sl_username_email_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_sl_username_email" name="the_champ_login[email_username]" type="checkbox" <?php echo isset($theChampLoginOptions['email_username']) ? 'checked' : '';?> value="1" />
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_username_email_help_cont">
							<td colspan="2">
							<div>
							<?php _e('Username of the new user will be the part before the @ in their email address', 'utillz-login') ?>
							</div>
							</td>
						</tr>

						<tr>
							<th>
							<label><?php _e("Allow cyrillic characters in the name", 'utillz-login'); ?></label><img id="the_champ_sl_cyrillic_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_sl_cyrillic" name="the_champ_login[allow_cyrillic][]" type="checkbox" <?php echo isset($theChampLoginOptions['allow_cyrillic']) && is_array($theChampLoginOptions['allow_cyrillic']) && in_array('cyrillic', $theChampLoginOptions['allow_cyrillic']) ? 'checked' : '';?> value="cyrillic" />
							<label for="the_champ_sl_cyrillic"><?php _e('Allow cyrillic', 'utillz-login') ?></label><br/>
							<input id="the_champ_sl_cyrillic_arabic" name="the_champ_login[allow_cyrillic][]" type="checkbox" <?php echo isset($theChampLoginOptions['allow_cyrillic']) && is_array($theChampLoginOptions['allow_cyrillic']) && in_array('arabic', $theChampLoginOptions['allow_cyrillic']) ? 'checked' : ''; ?> value="arabic" />
							<label for="the_champ_sl_cyrillic_arabic"><?php _e('Allow Arabic', 'utillz-login') ?></label><br/>
							<input id="the_champ_sl_cyrillic_chinese" name="the_champ_login[allow_cyrillic][]" type="checkbox" <?php echo isset($theChampLoginOptions['allow_cyrillic']) && is_array($theChampLoginOptions['allow_cyrillic']) && in_array('chinese', $theChampLoginOptions['allow_cyrillic']) ? 'checked' : '';?> value="chinese" />
							<label for="the_champ_sl_cyrillic_chinese"><?php _e('Allow Chinese', 'utillz-login') ?></label>
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_cyrillic_help_cont">
							<td colspan="2">
							<div>
							<?php _e('Allow cyrillic, Arabic and Chinese characters in the names of the new users registering via social login', 'utillz-login') ?>
							</div>
							</td>
						</tr>
					</table>
					</div>
				</div>

				<div class="stuffbox">
					<h3 class="hndle"><label><?php _e('Social Account Linking Options', 'utillz-login');?></label></h3>
					<div class="inside">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table">
						<tr>
							<th>
							<label for="the_champ_scl_title"><?php _e("Title", 'utillz-login'); ?></label><img id="the_champ_scl_title_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_scl_title" name="the_champ_login[scl_title]" type="text" value="<?php echo isset($theChampLoginOptions['scl_title']) ? $theChampLoginOptions['scl_title'] : '' ?>" />
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_scl_title_help_cont">
							<td colspan="2">
							<div>
							<?php _e('Text to display above the Social Account Linking interface', 'utillz-login') ?>
							</div>
							</td>
						</tr>

						<tr>
							<th>
							<label for="the_champ_sl_linking"><?php _e("Link social account to already existing account, if email address matches", 'utillz-login'); ?></label><img id="the_champ_sl_linking_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_sl_linking" name="the_champ_login[link_account]" type="checkbox" <?php echo isset($theChampLoginOptions['link_account']) ? 'checked' : '';?> value="1" />
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_linking_help_cont">
							<td colspan="2">
							<div>
							<?php _e('If email address of the user\'s Social Account matches with an already existing account at your website, that social account will be linked to existing account. User would be able to manage this from Social Account Linking interface at their profile page.', 'utillz-login') ?>
							</div>
							</td>
						</tr>

						<?php if($theChampIsBpActive){ ?>
						<tr>
							<th>
							<label for="the_champ_sl_bp_linking"><?php _e("Enable social account linking at BuddyPress profile page", 'utillz-login'); ?></label><img id="the_champ_sl_bp_linking_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_sl_bp_linking" name="the_champ_login[bp_linking]" type="checkbox" <?php echo isset($theChampLoginOptions['bp_linking']) ? 'checked' : '';?> value="1" />
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_bp_linking_help_cont">
							<td colspan="2">
							<div>
							<?php _e('Enable this option to show social account linking interface at BuddyPress profile page', 'utillz-login') ?>
							</div>
							</td>
						</tr>
						<?php } ?>
						
					</table>
					</div>
				</div>

					<div class="stuffbox" <?php echo !isset($theChampLoginOptions['email_required']) ? 'style="display: none"' : '' ?> id="the_champ_email_popup_options">
					<h3 class="hndle"><label><?php _e('Email popup options', 'utillz-login');?></label></h3>
					<div class="inside">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table">
						<tr>
							<th>
							<label for="the_champ_login_email_required_text"><?php _e("Text on 'Email required' popup", 'utillz-login'); ?></label><img id="the_champ_sl_emailreq_text_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<textarea rows="4" cols="40" id="the_champ_login_email_required_text" name="the_champ_login[email_popup_text]"><?php echo isset($theChampLoginOptions['email_popup_text']) ? $theChampLoginOptions['email_popup_text'] : '' ?></textarea>
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_emailreq_text_help_cont">
							<td colspan="2">
							<div>
							<?php _e('This text will be displayed on email required popup. Leave empty if not required.', 'utillz-login') ?>
							</div>
							<img width="550" src="<?php echo plugins_url('../images/snaps/sl_email_popup_message.png', __FILE__); ?>" />
							</td>
						</tr>
						
						<tr>
							<th>
							<label for="the_champ_login_email_required_error"><?php _e("Error message for 'Email required' popup", 'utillz-login'); ?></label><img id="the_champ_sl_emailreq_error_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<textarea rows="4" cols="40" id="the_champ_login_email_required_error" name="the_champ_login[email_error_message]"><?php echo isset($theChampLoginOptions['email_error_message']) ? $theChampLoginOptions['email_error_message'] : '' ?></textarea>
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_emailreq_error_help_cont">
							<td colspan="2">
							<div>
							<?php _e('This message will be displayed to user if it provides invalid or already registered email', 'utillz-login') ?>
							</div>
							<img width="550" src="<?php echo plugins_url('../images/snaps/sl_emailreq_message.png', __FILE__); ?>" />
							</td>
						</tr>
						
						<tr>
							<th>
							<label for="the_champ_email_popup_height"><?php _e("Email popup height", 'utillz-login'); ?></label><img id="the_champ_email_popup_height_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input style="width: 100px" id="the_champ_email_popup_height" name="the_champ_login[popup_height]" type="text" value="<?php echo isset($theChampLoginOptions['popup_height']) ? $theChampLoginOptions['popup_height'] : '' ?>" />px
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_email_popup_height_help_cont">
							<td colspan="2">
							<div>
							<?php _e('If you are seeing vertical scrollbar in the "Email required" popup, you can increase the height of popup by specifying in this option. Leave empty for default.', 'utillz-login') ?>
							</div>
							</td>
						</tr>
						
						<tr>
							<th>
							<label for="the_champ_password_email_verification"><?php _e("Enable email verification", 'utillz-login'); ?></label><img id="the_champ_sl_emailver_help" class="the_champ_help_bubble" src="<?php echo plugins_url('../images/info.png', __FILE__) ?>" />
							</th>
							<td>
							<input id="the_champ_password_email_verification" name="the_champ_login[email_verification]" type="checkbox" <?php echo isset($theChampLoginOptions['email_verification']) ? 'checked' : '';?> value="1" />
							</td>
						</tr>
						
						<tr class="the_champ_help_content" id="the_champ_sl_emailver_help_cont">
							<td colspan="2">
							<div>
							<?php _e('If enabled, email provided by the user will be verified by sending a confirmation link to that email. User would not be able to login without verifying his/her email', 'utillz-login') ?>
							</div>
							</td>
						</tr>
					</table>
					</div>
					</div>
				</div>
				
			</div>

			<?php if($theChampIsBpActive){
				$profileFields = array(
					'Social ID' => 'id',
					'Social Network' => 'provider',
					'Email' => 'email',
					'Name' => 'name',
					'Username' => 'user_name',
					'First Name' => 'first_name',
					'Last Name' => 'last_name',
					'Bio' => 'bio',
					'Social Profile Url' => 'link',
					'Social Avatar Url' => 'avatar',
					'Large Social Avatar Url' => 'large_avatar'
				);
			?>
			<div class="menu_containt_div" id="tabs-4">
				<div class="clear"></div>
				<div class="the_champ_left_column">
				<div class="stuffbox">
					<h3><label><?php _e('XProfile Integration', 'utillz-login');?></label></h3>
					<div class="inside">
					<?php
					global $wpdb;
					$xprofileFields = $wpdb-> get_results("SELECT * FROM " . $wpdb-> prefix . "bp_xprofile_fields");
					if($xprofileFields){
						?>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table editcomment menu_content_table">
						<?php
						foreach($xprofileFields as $field){
							if($field-> id == 1){
								continue;
							}	
							?>
							<tr>
								<td>
									<label><?php _e($field-> name, 'utillz-login'); ?></label>
								</td>
								<td>
									<select name="the_champ_login[xprofile_mapping][<?php echo $field-> name ?>]">
										<option value="">--<?php _e('Select', 'utillz-login') ?>--</option>
										<?php
										foreach($profileFields as $key => $val){
											?>
											<option <?php echo isset($theChampLoginOptions['xprofile_mapping'][$field-> name]) && $theChampLoginOptions['xprofile_mapping'][$field-> name] == $val ? 'selected' : '' ?> value="<?php echo $val ?>"><?php echo ucfirst($key) ?></option>
											<?php
										}
										?>
									</select>
								</td>
							</tr>
							<?php
						}
						?>
						</table>
						<?php
					}
					?>
					</div>
				</div>
				</div>
				
			</div>
			<?php } ?>
			
		</div>

		<div class="the_champ_clear"></div>
		<p class="submit">
			<input id="utillz_login_btn" style="margin-left:8px" type="submit" name="save" class="button button-primary" value="<?php _e("Save Changes", 'utillz-login'); ?>" />
		</p>
		
		</form>
		<div class="clear"></div>
	
	</div>
