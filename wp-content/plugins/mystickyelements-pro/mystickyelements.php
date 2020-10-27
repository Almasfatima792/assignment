<?php
/*
Plugin Name: myStickyElements Pro
Plugin URI: https://premio.io/
Description: myStickyElements is simple yet very effective plugin. It is perfect to fill out usually unused side space on webpages with some additional messages, videos, social widgets ...
Version: 1.7.8
Author: Premio
Author URI: https://premio.io/
Domain Path: /languages
License: GPLv2 or later
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

define('MYSTICKYELEMENTS_PRO_URL', plugins_url('/', __FILE__));  // Define Plugin URL
define('MYSTICKYELEMENTS_PRO_PATH', plugin_dir_path(__FILE__));  // Define Plugin Directory Path

/*PRO Vars*/
define("PRO_MY_STICKY_ELEMENT_API_URL", "https://go.premio.io/");
define("PRO_MY_STICKY_ELEMENT_ID", "3432");
define("PRO_MY_STICKY_ELEMENT_VERSION", "1.7.8");

/* Checking for updates */
require_once("sticky-element.class.php");
$license_key = get_option("sticky_element_license_key");
new Sticky_element_Plugin_Updater(PRO_MY_STICKY_ELEMENT_API_URL, __FILE__, array(
		'version' => PRO_MY_STICKY_ELEMENT_VERSION,
		'license' => $license_key,
		'item_id' => PRO_MY_STICKY_ELEMENT_ID,
		'item_name' => "My Sticky Elements",
		'author' => 'Premio.io',
		'url' => home_url()
	)
);

/*
 * redirect my sticky element setting page after plugin activated
 */
add_action( 'activated_plugin', 'mystickyelement_activation_redirect_pro' );
function mystickyelement_activation_redirect_pro($plugin){

	if( $plugin == plugin_basename( __FILE__ ) ) {
		wp_redirect( admin_url( 'admin.php?page=my-sticky-elements-settings' ) ) ;
		exit;
	}
}

if ( !function_exists( 'mystickyelement_pro_activate' )) {
	function mystickyelement_pro_activate() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
        $charset_collate = $wpdb->get_charset_collate();

		$contact_lists_table = $wpdb->prefix . 'mystickyelement_contact_lists';
		if ($wpdb->get_var("show tables like '$contact_lists_table'") != $contact_lists_table) {

			$contact_lists_table_sql = "CREATE TABLE $contact_lists_table (
				ID int(11) NOT NULL AUTO_INCREMENT,
				contact_name varchar(255) NULL,
				contact_phone varchar(255) NULL,
				contact_email varchar(255) NULL,
				contact_message text NULL,
				contact_option varchar(255) NULL,
				message_date DATETIME NOT NULL default '0000-00-00 00:00:00',
				PRIMARY KEY  (ID)
			) $charset_collate;";
			dbDelta($contact_lists_table_sql);
		}

		if ( get_option('mystickyelements-contact-form') == false ) {
			$contact_form = array(
								'enable' 		=> 1,
								'name' 			=> 1,
								'name_require' 	=> '',
								'name_value' 	=> '',
								'phone' 		=> 1,
								'phone_require' => 1,
								'phone_value' 	=> '',
								'email' 		=> 1,
								'email_require' => 1,
								'email_value' 	=> '',
								'message' 		=> 1,
								'message_value' => '',
								'dropdown'		=> '',
								'dropdown_require' => '',
								'submit_button_background_color'=> '#7761DF',
								'submit_button_text_color' 		=> '#FFFFFF',
								'submit_button_text' 	=> 'Submit',
								'desktop' 	=> 1,
								'mobile' 	=> 1,
								'direction' 	=> 'LTR',
								'tab_background_color' 	=> '#7761DF',
								'tab_text_color' 		=> '#FFFFFF',
								'headine_text_color' 	=> '#7761DF',
								'text_in_tab' 			=> 'Contact Us',
								'thank_you_message' 	=> 'Your message was sent successfully',
								'send_leads' 			=> 'database',
								'email_subject_line' 	=> 'New lead from MyStickyElements',
								'sent_to_mail' 			=> '',
								'form_css' 				=> '' ,
							);

			update_option( 'mystickyelements-contact-form', $contact_form);
		}

		if ( get_option('mystickyelements-social-channels') == false ) {
			$social_channels = array(
									'enable' 			=> 1,
									'whatsapp' 			=> 1,
									'facebook_messenger'=> 1,
								);

			update_option( 'mystickyelements-social-channels', $social_channels);
		}
		if ( get_option('mystickyelements-social-channels-tabs') == false ) {
			$social_channels_tabs['whatsapp'] = array(
													'text' => "",
													'hover_text' => "WhatsApp",
													'bg_color' => "#26D367",
													'desktop' => 1,
													'mobile' => 1,
												);
			$social_channels_tabs['facebook_messenger'] = array(
													'text' => "",
													'hover_text' => "Facebook Messenger",
													'bg_color' => "#007FF7",
													'desktop' => 1,
													'mobile' => 1,
												);

			update_option( 'mystickyelements-social-channels-tabs', $social_channels_tabs);
		}
		if ( get_option('mystickyelements-general-settings') == false ) {
			$general_settings = array(
									'position' 			=> 'left',
									'position_mobile' 	=> 'left',
									'open_tabs_when' 	=> 'hover',
									'custom_position' 	=> '',
									'tabs_css' 			=> '',
									'minimize_tab'		=> '1',
									'on_load_when'		=> 'open',
									'minimize_tab_background_color'	=> '#000000',
									'page_settings'     => '',
								);

			update_option( 'mystickyelements-general-settings', $general_settings);
		}

		$DS = DIRECTORY_SEPARATOR;
		$dirName = ABSPATH . "wp-content{$DS}plugins{$DS}mystickyelements{$DS}";
		if(is_dir($dirName)) {
			if (is_plugin_active("mystickyelements/mystickyelements.php")) {
				deactivate_plugins("mystickyelements/mystickyelements.php");
			}
			mystickyelement_delete_directory($dirName);
		}
	}
}

register_activation_hook( __FILE__, 'mystickyelement_pro_activate' );



function mystickyelement_delete_directory($dirname) {
	if (is_dir($dirname))
		$dir_handle = opendir($dirname);
	if (!$dir_handle)
		return false;
	while($file = readdir($dir_handle)) {
		if ($file != "." && $file != "..") {
			if (!is_dir($dirname."/".$file))
				unlink($dirname."/".$file);
			else
				mystickyelement_delete_directory($dirname.'/'.$file);
		}
	}
	closedir($dir_handle);
	rmdir($dirname);
	return true;
}

if ( !function_exists('mystickyelements_social_channels')) {

	function mystickyelements_social_channels() {
		$social_channels = array(
							'facebook' => array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Facebook",
											'background_color' => "#4267B2",
											'placeholder'	=> 'https://www.facebook.com/facebook-test-page',
											'class' => "fab fa-facebook-f",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'twitter'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Twitter",
											'background_color' => "#1C9DEB",
											'placeholder'	=> 'https://twitter.com/',
											'class' => "fab fa-twitter",
											'tooltip'	=> '',
											'icon_color' => 1
										),							
							'insagram'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Instagram",
											'background_color' => "",
											'placeholder'	=> 'https://instagram.com/',
											'class' => "fab fa-instagram",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'pinterest'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Pinterest",
											'background_color' => "#E85F65",
											'placeholder'	=> 'https://pinterest.com',
											'class' => "fab fa-pinterest-p",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'whatsapp'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "WhatsApp",
											'background_color' => "#26D367",
											'placeholder'	=> 'Enter your whatsapp number',
											'class' => "fab fa-whatsapp",
											'tooltip'	=> '',
											'is_pre_set_message' => 1,
											'icon_color' => 1
										),
							'youtube'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "YouTube",
											'background_color' => "#F54E4E",
											'placeholder'	=> 'https://youtube.com/channel-link',
											'class' => "fab fa-youtube",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'phone'		=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Phone",
											'background_color' => "#26D37C",
											'placeholder'	=> '0123456789',
											'class' => "fa fa-phone",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'facebook_messenger'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Facebook Messenger",
											'background_color' => "#007FF7",
											'placeholder'	=> 'Enter the last part of your page’s URL (e.g. Coca-Cola)',
											'class' => "fab fa-facebook-messenger",
											'tooltip'	=> '<ul>
<li>1. go to <a href="" target="_blank">Facebook.com</a></li>
<li>2. Click on your name tab</li>
<li>3. Copy the last part of the URL <img src="' . MYSTICKYELEMENTS_PRO_URL .'images/facebook-image.png" /></li>
											</ul>',
											'icon_color' => 1
										),
							'email'		=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Email",
											'background_color' => "#DC483C",
											'placeholder'	=> 'example@example.com',
											'class' => "far fa-envelope",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'address'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Address",
											'background_color' => "#23D28C",
											'placeholder'	=> 'Enter you address',
											'class' => "fas fa-map-marker-alt",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'business_hours'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Open Hours",
											'background_color' => "#E85F65",
											'placeholder'	=> 'Enter your opening hours',
											'class' => "fas fa-calendar-alt",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'wechat'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "WeChat",
											'background_color' => "#00AD19",
											'placeholder'	=> 'Enter your wechat User ID',
											'class' => "fab fa-weixin",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'telegram'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Telegram",
											'background_color' => "#2CA5E0",
											'placeholder'	=> 'http://telegram.com/channel-link',
											'class' => "fab fa-telegram-plane",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'linkedin'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Linkedin",
											'background_color' => "#0077b5",
											'placeholder'	=> 'https://www.linkedin.com/',
											'class' => "fab fa-linkedin-in",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'vimeo'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Vimeo",
											'background_color' => "#1ab7ea",
											'placeholder'	=> 'https://vimeo.com/channel-link',
											'class' => "fab fa-vimeo-v",
											'tooltip'	=> '',
											'icon_color' => 1,
										),
							'spotify'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Spotify",
											'background_color' => "#ff5500",
											'placeholder'	=> 'https://www.spotify.com/channel-link',
											'class' => "fab fa-spotify",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'itunes'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Itunes",
											'background_color' => "#495057",
											'placeholder'	=> 'https://www.apple.com/us/itunes/channel-link',
											'class' => "fab fa-itunes-note",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'soundcloud'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "SoundCloud",
											'background_color' => "#ff5500",
											'placeholder'	=> 'https://soundcloud.com/channel-link',
											'class' => "fab fa-soundcloud",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'vk'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Vkontakte",
											'background_color' => "#4a76a8",
											'placeholder'	=> 'Enter your VK Username',
											'class' => "fab fa-vk",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'viber'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Viber",
											'background_color' => "#59267c",
											'placeholder'	=> '+1507854875',
											'class' => "fab fa-viber",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'snapchat'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Snapchat",
											'background_color' => "#fffc00",
											'placeholder'	=> 'Enter your Snapchat Username',
											'class' => "fab fa-snapchat-ghost",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'skype'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Skype",
											'background_color' => "#00aff0",
											'placeholder'	=> 'Enter your Skype Username',
											'class' => "fab fa-skype",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'line'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Line",
											'background_color' => "#00c300",
											'placeholder'	=> 'http://line.me/ti/p/2a-s5A2B8B',
											'class' => "mystickyelement_line_icon",
											'tooltip'	=> '',
											'icon_color' => 1,
											'custom_svg_icon'	=> file_get_contents( MYSTICKYELEMENTS_PRO_PATH . '/images/line-logo.svg')
										),
							'SMS'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "SMS",
											'background_color' => "#ff549c",
											'placeholder'	=> '+1507854875',
											'class' => "fas fa-sms",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'tumblr'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Tumblr",
											'background_color' => "#35465d",
											'placeholder'	=> 'https://www.tumblr.com/channel-link',
											'class' => "fab fa-tumblr",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'qzone'		=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Qzone",
											'background_color' => "#1a87da",
											'placeholder'	=> 'https://qzone.qq.com/channel-link',
											'class' => "mystickyelement_qzone_icon",
											'tooltip'	=> '',
											'icon_color' => 1,
											'custom_svg_icon'	=> file_get_contents( MYSTICKYELEMENTS_PRO_PATH . '/images/qzone-logo.svg')
										),
							'qq'		=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "QQ",
											'background_color' => "#212529",
											'placeholder'	=> 'Enter your QQ Username',
											'class' => "fab fa-qq",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'behance'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Behance",
											'background_color' => "#131418",
											'placeholder'	=> 'https://www.behance.net/channel-link',
											'class' => "fab fa-behance",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'dribbble'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Dribbble",
											'background_color' => "#ea4c89",
											'placeholder'	=> 'https://dribbble.com/channel-link',
											'class' => "fab fa-dribbble",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'quora'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Quora",
											'background_color' => "#aa2200",
											'placeholder'	=> 'https://www.quora.com/channel-link',
											'class' => "fab fa-quora",
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'custom_one'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Custom One",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your custom social link',
											'class' => "fas fa-cloud-upload-alt",
											'custom'	=> 1,
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'custom_two'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Custom Two",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your custom social link',
											'class' => "fas fa-cloud-upload-alt",
											'is_locked'	=> 0,
											'custom'	=> 1,
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'custom_three'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Custom Three",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your custom social link',
											'class' => "fas fa-cloud-upload-alt",
											'is_locked'	=> 0,
											'custom'	=> 1,
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'custom_four'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your shortcode or custom IFRAME/HTML code',
											'class' => "fas fa-code",
											'custom'	=> 1,
											'custom_html'	=> 1,
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'custom_five'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Custom Five",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your shortcode or custom IFRAME/HTML code',
											'class' => "fas fa-code",
											'is_locked'	=> 0,
											'custom'	=> 1,
											'custom_html'	=> 1,
											'icon_color' => 1,
											'tooltip'	=> ''
										),
							'custom_six'	=> array(
											'text' => "",
											'icon_text' => "",
											'hover_text' => "Custom Six",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your shortcode or custom IFRAME/HTML code',
											'class' => "fas fa-code",
											'is_locked'	=> 0,
											'custom'	=> 1,
											'custom_html'	=> 1,
											'tooltip'	=> '',
											'icon_color' => 1
										),
						);

		return apply_filters( 'mystickyelements_social_channels_info',  $social_channels);
	}
}
add_action( 'admin_init' , 'mystickyelements_pro_admin_init' );
function mystickyelements_pro_admin_init() {
	global $wpdb, $pagenow;
	
	if ( $pagenow == 'plugins.php'  || ( isset($_GET['page']) && $_GET['page'] == 'my-sticky-elements-settings' ) ) {
		/* add Contact Option field */
		$field_check = $wpdb->get_var( "SHOW COLUMNS FROM {$wpdb->prefix}mystickyelement_contact_lists LIKE 'contact_option'" );
		if ( 'contact_option' != $field_check ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}mystickyelement_contact_lists ADD contact_option VARCHAR(255) NULL DEFAULT NULL" );
		}
		
		/* add Contact Message date field */
		$field_check = $wpdb->get_var( "SHOW COLUMNS FROM {$wpdb->prefix}mystickyelement_contact_lists LIKE 'message_date'" );	
		if ( 'message_date' != $field_check ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}mystickyelement_contact_lists ADD message_date DATETIME NOT NULL default '0000-00-00 00:00:00'" );
		}
		
		/* add Contact Widget Name field */
		$field_check = $wpdb->get_var( "SHOW COLUMNS FROM {$wpdb->prefix}mystickyelement_contact_lists LIKE 'widget_element_name'" );	
		if ( 'widget_element_name' != $field_check ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}mystickyelement_contact_lists ADD widget_element_name VARCHAR(255) NULL DEFAULT 'default'" );
		}
		
		/* add Contact Custom Fields field */
		$field_check = $wpdb->get_var( "SHOW COLUMNS FROM {$wpdb->prefix}mystickyelement_contact_lists LIKE 'custom_fields'" );	
		if ( 'custom_fields' != $field_check ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}mystickyelement_contact_lists ADD custom_fields longtext" );
		}
		
		/* add Page Link field */
		$field_check = $wpdb->get_var( "SHOW COLUMNS FROM {$wpdb->prefix}mystickyelement_contact_lists LIKE 'page_link'" );
		if ( 'page_link' != $field_check ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}mystickyelement_contact_lists ADD page_link TEXT NULL DEFAULT NULL" );
		}
		
		/* add consent checkbox */
		$field_check = $wpdb->get_var( "SHOW COLUMNS FROM {$wpdb->prefix}mystickyelement_contact_lists LIKE 'consent_checkbox'" );
		if ( 'consent_checkbox' != $field_check ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}mystickyelement_contact_lists ADD consent_checkbox BOOLEAN NULL DEFAULT false" );
		}
	}
}

/* Get The Default fields */
function mystickyelements_pro_widget_default_fields ( $mystickyelements_option ) {
	
	if ( $mystickyelements_option == '') {
		return array();
	}
	if ( $mystickyelements_option == 'contact_form' ) {
		return array(
						'enable' 		=> 1,
						'name' 			=> 1,
						'name_require' 	=> '',
						'name_value' 	=> '',
						'phone' 		=> 1,
						'phone_require' => 1,
						'phone_value' 	=> '',
						'email' 		=> 1,
						'email_require' => 1,
						'email_value' 	=> '',
						'message' 		=> 1,
						'message_value' => '',
						'dropdown'		=> '',
						'dropdown_require' => '',
						'submit_button_background_color'=> '#7761DF',
						'submit_button_text_color' 		=> '#FFFFFF',
						'submit_button_text' 	=> 'Submit',
						'desktop' 	=> 1,
						'mobile' 	=> 1,
						'direction' 	=> 'LTR',
						'tab_background_color' 	=> '#7761DF',
						'tab_text_color' 		=> '#FFFFFF',
						'headine_text_color' 	=> '#7761DF',
						'text_in_tab' 			=> 'Contact Us',
						'thank_you_message' 	=> 'Your message was sent successfully',
						'send_leads' 			=> 'database',
						'email_subject_line' 	=> 'New lead from MyStickyElements',
						'sent_to_mail' 			=> '',
						'form_css' 				=> '' ,
					);
	}
	
	if ( $mystickyelements_option == 'social_channels' ) {
		return array(
							'enable' 			=> 1,
							'whatsapp' 			=> 1,
							'facebook_messenger'=> 1,
						);
	}
	if ( $mystickyelements_option == 'social_channels_tabs' ) {
		$social_channels_tabs['whatsapp'] = array(
												'text' => "",
												'hover_text' => "WhatsApp",
												'bg_color' => "#26D367",
												'desktop' => 1,
												'mobile' => 1,
											);
		$social_channels_tabs['facebook_messenger'] = array(
													'text' => "",
													'hover_text' => "Facebook Messenger",
													'bg_color' => "#007FF7",
													'desktop' => 1,
													'mobile' => 1,
												);
		return $social_channels_tabs;
	}
	
	
	if ( $mystickyelements_option == 'general_settings' ) {
		return array(
							'position' 			=> 'left',
							'position_mobile' 	=> 'left',
							'open_tabs_when' 	=> 'hover',
							'custom_position' 	=> '',
							'tabs_css' 			=> '',
							'minimize_tab'		=> '1',
							'on_load_when'		=> 'open',
							'minimize_tab_background_color'	=> '#000000',
							'page_settings'     => '',
						);
	}	
			
}

require_once MYSTICKYELEMENTS_PRO_PATH . 'mystickyelements-fonts.php';
require_once MYSTICKYELEMENTS_PRO_PATH . 'mystickyelements-fontawesome-icons.php';
require_once MYSTICKYELEMENTS_PRO_PATH . 'mystickyelements-admin.php';
require_once MYSTICKYELEMENTS_PRO_PATH . 'mystickyelements-front.php';