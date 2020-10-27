<?php

if (!class_exists('MyStickyElementsFrontPage_pro')) {

    class MyStickyElementsFrontPage_pro
    {

        public function __construct()
        {

            add_action('wp_enqueue_scripts', array($this, 'mystickyelements_enqueue_script'), 99);
            add_action('wp_footer', array($this, 'mystickyelement_element_footer'));

            add_action('wp_ajax_mystickyelements_contact_form', array($this, 'mystickyelements_contact_form'));
            add_action('wp_ajax_nopriv_mystickyelements_contact_form', array($this, 'mystickyelements_contact_form'));
        }
		public function mystickyelements_google_fonts_url() {
			$elements_widgets = get_option( 'mystickyelements-widgets' );
			if ( empty($elements_widgets) || $elements_widgets == '' ){
				$elements_widgets[] = 'default';
			}

			$default_fonts = array('Arial', 'Tahoma', 'Verdana', 'Helvetica', 'Times New Roman', 'Trebuchet MS', 'Georgia', 'Open Sans Hebrew');
			$fonts_url        = '';
			$fonts            = array();
			$font_args        = array();
			$base_url         =  "https://fonts.googleapis.com/css";



			foreach ( $elements_widgets as $key=>$value ) {
				$element_widget_no = '';
				if ($key != 0 ) {
					$element_widget_no = '-' . $key;
				}
				$general_settings = get_option( 'mystickyelements-general-settings' . $element_widget_no );
				if ( isset($general_settings['font_family']) && $general_settings['font_family'] != '' && !in_array( $general_settings['font_family'], $default_fonts) ) {
					$fonts['family'][$general_settings['font_family']] = $general_settings['font_family'] . ':400,500,600,700';
				} else {
					$fonts['family']['Poppins'] = 'Poppins:400,500,600,700';
				}
			}

			/* Prepapre URL if font family defined. */
			if( !empty( $fonts['family'] ) ) {

				/* format family to string */
				if( is_array($fonts['family']) ){
					$fonts['family'] = implode( '|', $fonts['family'] );
				}

				$font_args['family'] = urlencode( trim( $fonts['family'] ) );

				if( !empty( $fonts['subsets'] ) ){

					/* format subsets to string */
					if( is_array( $fonts['subsets'] ) ){
						$fonts['subsets'] = implode( ',', $fonts['subsets'] );
					}

					$font_args['subsets'] = urlencode( trim( $fonts['subsets'] ) );
				}

				$fonts_url = add_query_arg( $font_args, $base_url );
			}
			return esc_url_raw( $fonts_url );

		}
        public function mystickyelements_enqueue_script()
        {
			$contact_form = get_option('mystickyelements-contact-form');
            $general_settings = get_option('mystickyelements-general-settings');

			wp_enqueue_style( 'mystickyelements-google-fonts', $this->mystickyelements_google_fonts_url(),array(), PRO_MY_STICKY_ELEMENT_VERSION );

            wp_enqueue_style('font-awesome-css', plugins_url('/css/font-awesome.min.css', __FILE__), array(), PRO_MY_STICKY_ELEMENT_VERSION );
            wp_enqueue_style('mystickyelements-front-css', plugins_url('/css/mystickyelements-front.css', __FILE__), array(), PRO_MY_STICKY_ELEMENT_VERSION);


            wp_enqueue_script('mystickyelements-cookie-js', plugins_url('/js/jquery.cookie.js', __FILE__), array('jquery'), PRO_MY_STICKY_ELEMENT_VERSION, true);
            wp_enqueue_script('mystickyelements-fronted-js', plugins_url('/js/mystickyelements-fronted.js', __FILE__), array('jquery'), PRO_MY_STICKY_ELEMENT_VERSION, true);

            $locale_settings = array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('mystickyelements'),
				'google_analytics'	=> (isset($general_settings['google_analytics']) && $general_settings['google_analytics'] == 1)? true : false,
            );
            wp_localize_script('mystickyelements-fronted-js', 'mystickyelements', $locale_settings);
        }

        public function mystickyelement_element_footer()
        {
			$elements_widgets = get_option( 'mystickyelements-widgets' );
			if ( empty($elements_widgets) || $elements_widgets == '' ){
				$elements_widgets[] = 'default';
			}
            $social_channels_lists = mystickyelements_social_channels();

			$page_options = array();
			if ( !empty($elements_widgets)):
				foreach( $elements_widgets as $ekey=>$evalue):
					$element_widget_no = '';
					if ($ekey != 0 ) {
						$element_widget_no = '-' . $ekey;
					}
					$general_settings = get_option('mystickyelements-general-settings' . $element_widget_no );
					$page_rule_options = (isset($general_settings['page_settings'])) ? $general_settings['page_settings'] : array();
					$page_rule_flag = 1;       // for page Rule contain
					$page_options[$ekey] = 1;


					/* Unset Page rule when value empty */
					if ( !empty($page_rule_options) && is_array($page_rule_options) ) {
						foreach( $page_rule_options as $key=>$value ) {
							if ( trim($value['value']) == '' ) {
								unset($page_rule_options[$key]);
							}
						}
					}

					/* checking for page visibility settings */
					if (!empty($page_rule_options) && is_array($page_rule_options)) {
						$url = strtolower($_SERVER['REQUEST_URI']);
						$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" .$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
						$site_url = site_url("/");
						$request_url = substr($link, strlen($site_url));
						$url = trim($request_url, "/");
						$page_rule_flag = 0;
						$page_options[$ekey] = 0;
						$total_option = count($page_rule_options);
						$options = 0;
						/* checking for each page options */
						foreach ($page_rule_options as $option) {
							$key = $option['option'];
							$value = trim(strtolower($option['value']));
							if ($key != '' && $value != '') {
								if($option['shown_on'] == "show_on") {
									$value = trim($value, "/");
									switch ($key) {
										case 'page_contains':
											$index = strpos($url, $value);
											if($index !== false) {
												$page_rule_flag = 1;
												$page_options[$ekey] = 1;
											}
											break;
										case 'page_has_url':
											if ($url === $value) {
												$page_rule_flag = 1;
												$page_options[$ekey] = 1;
											}
											break;
										case 'page_start_with':
											$length = strlen($value);
											$result = substr($url, 0, $length);
											if ($result == $value) {
												$page_rule_flag = 1;
												$page_options[$ekey] = 1;
											}
											break;
										case 'page_end_with':
											$length = strlen($value);
											$result = substr($url, (-1) * $length);
											if ($result == $value) {
												$page_rule_flag = 1;
												$page_options[$ekey] = 1;
											}
											break;
									}
								} else {
									$options++;
								}
							}
						}
						if($total_option == $options) {
							$page_rule_flag = 1;
							$page_options[$ekey] = 1;
						}
						foreach ($page_rule_options as $option) {
							$key = $option['option'];
							$value = trim(strtolower($option['value']));
							if ($key != '' && $option['shown_on'] == "not_show_on" && $value != '') {
								$value = trim($value, "/");
								switch ($key) {
									case 'page_contains':
										$index = strpos($url, $value);
										if($index !== false) {
											 $page_rule_flag = 0;
											 $page_options[$ekey] = 0;
										}
										break;
									case 'page_has_url':
										if ($url === $value) {
											$page_rule_flag = 0;
											$page_options[$ekey] = 0;
										}
										break;
									case 'page_start_with':
										$length = strlen($value);
										$result = substr($url, 0, $length);
										if ($result == $value) {
											$page_rule_flag = 0;
											$page_options[$ekey] = 0;
										}
										break;
									case 'page_end_with':
										$length = strlen($value);
										$result = substr($url, (-1) * $length);
										if ($result == $value) {
											$page_rule_flag = 0;
											$page_options[$ekey] = 0;
										}
										break;
								}
							}
						}
					}
				endforeach;/* */
			endif; /* */

			/*echo "<pre>";
			print_r($page_options);
			print_r($elements_widgets);
			echo "</pre>";
			*/
			$element_widgetno = '';
			$widget_name = '';
			if ( !empty($page_options)) {
				$element_widget_no = '';
				foreach($page_options as $key=>$value ) {
					if ( $value == 1) {
						if ( $key != 0 ) {
							$element_widget_no = "-" . $key;
							$widget_name = $elements_widgets[$key];
						}
						$page_rule_flag = 1;
						$element_widgetno = 1;
						break;
					}
				}
			}
			if ( $element_widgetno == '' ) {
				return;
			}
			$contact_form = get_option('mystickyelements-contact-form' . $element_widget_no );
            $social_channels = get_option('mystickyelements-social-channels' . $element_widget_no );
            $social_channels_tabs = get_option('mystickyelements-social-channels-tabs' . $element_widget_no );
            $general_settings = get_option('mystickyelements-general-settings' . $element_widget_no );
			if( $page_rule_flag == 1 ) {
				if (!isset($contact_form['enable']) && !isset($social_channels['enable'])) {
					return;
				}

				$contact_field = get_option( 'mystickyelements-contact-field' . $element_widget_no );
				if ( empty( $contact_field ) ) {
					$contact_field = array( 'name', 'phone', 'email', 'message', 'dropdown' );
				}

				$contact_form_class = '';
				if (isset($contact_form['desktop']) && $contact_form['desktop'] == 1) {
					$contact_form_class .= ' element-desktop-on';
				}
				if (isset($contact_form['mobile']) && $contact_form['mobile'] == 1) {
					$contact_form_class .= ' element-mobile-on';
				}

				if ( !isset($general_settings['position_mobile']) ) {
					$general_settings['position_mobile'] = 'left';
				}

				$minimize_class = '';
				if ( isset($general_settings['minimize_tab']) && $general_settings['minimize_tab'] == 1 ) {
					if ( !isset($_COOKIE['minimize_desktop']) && isset($general_settings['minimize_desktop']) && $general_settings['minimize_desktop'] == 'desktop' && !wp_is_mobile() ) {
						$minimize_class = 'element-minimize';
					} elseif ( !isset($_COOKIE['minimize_mobile']) && isset($general_settings['minimize_mobile']) && $general_settings['minimize_mobile'] == 'mobile' && wp_is_mobile() ) {
						$minimize_class = 'element-minimize';
					} else if ( isset($_COOKIE['minimize_desktop']) && $_COOKIE['minimize_desktop'] == 'minimize' && !wp_is_mobile() ) {
						$minimize_class = 'element-minimize';
					} elseif (isset($_COOKIE['minimize_mobile']) && $_COOKIE['minimize_mobile'] == 'minimize' && wp_is_mobile()) {
						$minimize_class = 'element-minimize';
					}
				}else {
					$minimize_class = 'no-minimize';
				}

				/* Change Open Tabs click to hover on Mobile device */
				if ( $general_settings['open_tabs_when'] == 'click' && wp_is_mobile() ) {
					$general_settings['open_tabs_when'] = 'hover';
				}
				$general_settings['widget-size'] = (isset($general_settings['widget-size']) && $general_settings['widget-size']!= '') ? $general_settings['widget-size'] : 'medium';

				$general_settings['mobile-widget-size'] = (isset($general_settings['mobile-widget-size']) && $general_settings['mobile-widget-size']!= '') ? $general_settings['mobile-widget-size'] : 'medium';

				$general_settings['entry-effect'] = (isset($general_settings['entry-effect']) && $general_settings['entry-effect']!= '') ? $general_settings['entry-effect'] : 'slide-in';
				$general_settings['templates'] = (isset($general_settings['templates']) && $general_settings['templates']!= '') ? $general_settings['templates'] : 'default';
				$mystickyelements_class[] = 'mystickyelements-fixed';
				$mystickyelements_class[] = 'mystickyelements-position-' . $general_settings['position'];
				$mystickyelements_class[] = 'mystickyelements-position-mobile-' . $general_settings['position_mobile'];
				$mystickyelements_class[] = 'mystickyelements-on-' . $general_settings['open_tabs_when'];
				$mystickyelements_class[] = 'mystickyelements-size-' . $general_settings['widget-size'];
				$mystickyelements_class[] = 'mystickyelements-mobile-size-' . $general_settings['mobile-widget-size'];
				$mystickyelements_class[] = 'mystickyelements-entry-effect-' . $general_settings['entry-effect'];
				$mystickyelements_class[] = 'mystickyelements-templates-' . $general_settings['templates'];

				$mystickyelements_classes = join( ' ', $mystickyelements_class );
				?>
				<div class="<?php echo esc_attr($mystickyelements_classes);?>" <?php if (isset($contact_form['direction']) && $contact_form['direction'] == 'RTL') : ?> dir="rtl" <?php endif; ?> data-custom-position="<?php echo $general_settings['custom_position'] ?>">
					<div class="mystickyelement-lists-wrap">
						<ul class="mystickyelements-lists <?php echo esc_attr('mysticky' . $minimize_class);?>">
							<?php if ( isset($general_settings['minimize_tab']) && $general_settings['minimize_tab'] == 1 ):?>
								<li class="mystickyelements-minimize <?php echo esc_attr($minimize_class);?>">
									<span class="mystickyelements-minimize minimize-position-<?php echo esc_attr($general_settings['position'])?> minimize-position-mobile-<?php echo esc_attr($general_settings['position_mobile'])?>" <?php if (isset($general_settings['minimize_tab_background_color']) && $general_settings['minimize_tab_background_color'] != ''): ?>style="background: <?php echo esc_attr($general_settings['minimize_tab_background_color']); ?>" <?php endif;
									?>>
									<?php
									if ( !isset($_COOKIE['minimize_desktop']) && isset($general_settings['minimize_desktop']) && $general_settings['minimize_desktop'] == 'desktop' && !wp_is_mobile() ) :
										echo "<i class='fas fa-envelope'></i>";
									elseif ( !isset($_COOKIE['minimize_mobile']) && isset($general_settings['minimize_mobile']) && $general_settings['minimize_mobile'] == 'mobile' && wp_is_mobile() ) :
										echo "<i class='fas fa-envelope'></i>";
									elseif ( $general_settings['position'] == 'left' && !wp_is_mobile() ) :
										echo  ($minimize_class == "" ) ? "&larr;" : "&rarr;";
									elseif ( $general_settings['position'] == 'right' && !wp_is_mobile() ) :
										echo  ($minimize_class == "" ) ? "&rarr;" : "&larr;";
									elseif ( $general_settings['position'] == 'bottom' && !wp_is_mobile() ) :
										echo  ($minimize_class == "" ) ? "&darr;" : "&uarr;";
									elseif ( $general_settings['position_mobile'] == 'left' && wp_is_mobile() ) :
										echo  ($minimize_class == "" ) ? "&larr;" : "&rarr;" ;
									elseif ( $general_settings['position_mobile'] == 'right' && wp_is_mobile() ) :
										echo  ($minimize_class == "" ) ? "&rarr;" : "&larr;";
									elseif ( $general_settings['position_mobile'] == 'bottom' && wp_is_mobile() ) :
										echo  ($minimize_class == "" ) ? "&darr;" : "&uarr;";
									elseif ( $general_settings['position_mobile'] == 'top' && wp_is_mobile() ) :
										echo  ($minimize_class == "" ) ? "&uarr;" : "&darr;";
									endif;
									?>
									</span>
								</li>
							<?php endif;?>

							<?php if (isset($contact_form['enable']) && $contact_form['enable'] == 1): ?>
								<li id="mystickyelements-contact-form" class="mystickyelements-contact-form <?php echo esc_attr($contact_form_class); ?>" <?php if (isset($contact_form['direction']) && $contact_form['direction'] == 'RTL') : ?> dir="rtl" <?php endif; ?>>
								<?php
								$contact_form_text_class = '';
								if ($contact_form['text_in_tab'] == '') {
									$contact_form_text_class = "mystickyelements-contact-notext";
								}?>
									<span class="mystickyelements-social-icon <?php echo $contact_form_text_class?>"
										  style="background-color: <?php echo esc_attr($contact_form['tab_background_color']); ?>; color: <?php echo esc_attr($contact_form['tab_text_color']); ?>;"><i
											class="far fa-envelope"></i><?php echo esc_html($contact_form['text_in_tab']); ?></span>


									<?php
									$submit_button_text = ($contact_form['submit_button_text'] != '') ? $contact_form['submit_button_text'] : 'Submit';
									$submit_button_style = ($contact_form['submit_button_background_color'] != '') ? "background-color: " . $contact_form['submit_button_background_color'] . ";" : '';
									$submit_button_style .= ($contact_form['submit_button_text_color'] != '') ? "color:" . $contact_form['submit_button_text_color'] . ";" : '';

									$heading_color = ( isset($contact_form['headine_text_color']) && $contact_form['headine_text_color'] != '') ? "color: " . $contact_form['headine_text_color'] . ";" : ( ($contact_form['submit_button_background_color'] != '') ? "color: " . $contact_form['submit_button_background_color'] . ";" : 'color:#7761DF;' );

									$contact_form['name_value'] = ($contact_form['name_value'] != '') ? $contact_form['name_value'] : esc_html__('Name', 'mystickyelements');
									$contact_form['phone_value'] = ($contact_form['phone_value'] != '') ? $contact_form['phone_value'] : esc_html__('Phone', 'mystickyelements');
									$contact_form['email_value'] = ($contact_form['email_value'] != '') ? $contact_form['email_value'] : esc_html__('Email', 'mystickyelements');
									$contact_form['message_value'] = ($contact_form['message_value'] != '') ? $contact_form['message_value'] : esc_html__('Message', 'mystickyelements');

									?>
									<div class="element-contact-form">
										<h3 style="<?php echo esc_attr($heading_color); ?>">
											<?php echo esc_html($contact_form['text_in_tab']); ?>
											<a href="javascript:void(0);" class="element-contact-close"><i class="fas fa-times"></i></a>
										</h3>

										<form id="stickyelements-form" action="" method="post" autocomplete="off">

											<?php foreach ( $contact_field as $key=>$value ) :
												$val = $value;
												if ( !is_numeric($key) && $key == 'custom_fields' ) {
													$val = 'custom_fields';
												}
												if ( isset($value['custom_fields']) && is_array($value['custom_fields']) ) {
													$val = 'custom_fields';
													$value = $value['custom_fields'];
												}
												switch ( $val ) {
													case 'name' :

											if (isset($contact_form['name']) && $contact_form['name'] == 1): ?>
												<input
													class="<?php if (isset($contact_form['name_require']) && $contact_form['name_require'] == 1): ?> required<?php endif; ?>"
													type="text" id="contact-form-name" name="contact-form-name" value=""
													placeholder="<?php echo esc_attr($contact_form['name_value']); ?>"  <?php if (isset($contact_form['name_require']) && $contact_form['name_require'] == 1): ?> required<?php endif; ?> autocomplete="off"/>
											<?php endif;
													break;
												case 'phone' :

											if (isset($contact_form['phone']) && $contact_form['phone'] == 1): ?>
												<input
													class="<?php if (isset($contact_form['phone_require']) && $contact_form['phone_require'] == 1): ?> required<?php endif; ?>"
													type="tel" id="contact-form-phone" name="contact-form-phone" value=""
													placeholder="<?php echo esc_attr($contact_form['phone_value']); ?>" <?php if (isset($contact_form['phone_require']) && $contact_form['phone_require'] == 1): ?> required <?php endif; ?> autocomplete="off"/>
											<?php endif;
													break;
												case 'email' :

											if (isset($contact_form['email']) && $contact_form['email'] == 1): ?>
												<input
													class="email <?php if (isset($contact_form['email_require']) && $contact_form['email_require'] == 1): ?> required<?php endif; ?>"
													type="email" id="contact-form-email" name="contact-form-email" value=""
													placeholder="<?php echo esc_attr($contact_form['email_value']); ?>" <?php if (isset($contact_form['email_require']) && $contact_form['email_require'] == 1): ?> required <?php endif; ?> autocomplete="off"/>
											<?php endif;
													break;
												case 'message' :

											if (isset($contact_form['message']) && $contact_form['message'] == 1): ?>
												<textarea
													class="<?php if (isset($contact_form['message_require']) && $contact_form['message_require'] == 1): ?> required<?php endif; ?>"
													id="contact-form-message" name="contact-form-message"
													placeholder="<?php echo esc_attr($contact_form['message_value']); ?>" <?php if (isset($contact_form['message_require']) && $contact_form['message_require'] == 1): ?> required <?php endif; ?>></textarea>
											<?php endif;
													break;
												case 'dropdown' :
												if (isset($contact_form['dropdown']) && $contact_form['dropdown'] == 1): ?>
												<select id="contact-form-dropdown" name="contact-form-dropdown" class="<?php if (isset($contact_form['dropdown_require']) && $contact_form['dropdown_require'] == 1): ?> required<?php endif; ?>" <?php if (isset($contact_form['dropdown_require']) && $contact_form['dropdown_require'] == 1): ?> required <?php endif; ?>>

													<option value=""><?php echo esc_html( $contact_form['dropdown-placeholder'] );?></option>
													<?php foreach( $contact_form['dropdown-option'] as $option ):
														if ( $option == '' ) {
															continue;
														}
														?>
														<option value="<?php echo esc_html($option);?>"><?php echo esc_html($option);?></option>
													<?php endforeach;?>
												</select>

											<?php endif;
													break;
												case 'custom_fields':
													foreach ( $value as $cutom_field ) {
														$cutom_field_value = $contact_form['custom_fields'][$cutom_field];
														$custom_field_name = sanitize_title($cutom_field_value['custom_field_name']);

														if ( isset($cutom_field_value['custom_field']) && $cutom_field_value['custom_field'] == 1) {
															$field_name = ($cutom_field_value['custom_field_value'] != '' )? $cutom_field_value['custom_field_value'] : $cutom_field_value['custom_field_name'];
														?>
															<input type="text" id="contact-form-<?php echo esc_attr($custom_field_name)?>" name="contact-form[custom_field][<?php echo esc_attr($cutom_field)?>]" value="" placeholder="<?php echo  esc_attr($field_name);?>" <?php if (isset($cutom_field_value['custom_field_require']) && $cutom_field_value['custom_field_require'] == 1): ?> required <?php endif; ?>  class="<?php if (isset($cutom_field_value['custom_field_require']) && $cutom_field_value['custom_field_require'] == 1): ?> required<?php endif; ?>"  autocomplete="off">
														<?php
														}

													}
													break;
												} /* End Switch case */
											endforeach;
											if ( isset( $contact_form['consent_checkbox'] ) && $contact_form['consent_checkbox'] == 'yes') : ?>
												<p id="contact-form-consent-fields" class="contact-form-consent-fields">
													<input type="checkbox" name="contact-form-consent-fields" value="1" <?php if (isset($contact_form['consent_text_require']) && $contact_form['consent_text_require'] == 1): ?> required <?php endif; ?> />
													<span class="contact_form_consent_txt"><?php echo stripslashes($contact_form['consent_text']);?></span>
												</p>
											<?php endif;?>
											<input id="stickyelements-submit-form" type="submit" name="contact-form-submit"
												   value="<?php echo esc_html($submit_button_text); ?>"
												   style="<?php echo esc_attr($submit_button_style); ?>"/>
											<?php $unique_id = uniqid() . time() . uniqid(); ?>
											<input type="hidden" name="nonce" value="<?php echo $unique_id ?>">
											<input type="hidden" name="widget_name" value="<?php echo esc_attr($widget_name); ?>">
											<input type="hidden" name="widget_number" value="<?php echo esc_attr($element_widget_no); ?>">
											<input type="hidden" name="form_id"
												   value="<?php echo wp_create_nonce($unique_id) ?>">
											<input type="hidden" id="stickyelements-page-link" name="stickyelements-page-link" value="<?php echo esc_url(get_permalink())?>" />
										</form>
										<p class="mse-form-success-message" id="mse-form-error" style="display:none;"></p>
									</div>
								</li>
							<?php endif; /* Contact Form */


							if (!empty($social_channels_tabs) && isset($social_channels['enable']) && $social_channels['enable'] == 1) :
								$protocols = array('http', 'https', 'mailto', 'tel', 'sms', 'javascript','viber','skype');
								foreach ($social_channels_tabs as $key => $value):
									$link_target = 1;
									$social_channels_list = $social_channels_lists[$key];
									$element_class = '';
									if (isset($value['desktop']) && $value['desktop'] == 1) {
										$element_class .= ' element-desktop-on';
									}
									if (isset($value['mobile']) && $value['mobile'] == 1) {
										$element_class .= ' element-mobile-on';
									}


									$hover_text = ($value['hover_text'] != '') ? $value['hover_text'] : $social_channels_list['hover_text'];
									$social_link = '';
									switch ($key) {
										case 'whatsapp':

											$value['text'] = str_replace( array('http://', 'https://') , array('','') , $value['text']);
											if ( isset($value['pre_set_message']) && $value['pre_set_message'] != '' ) {
												$social_link = 'https://api.whatsapp.com/send?phone=' .str_replace('+', '', $value['text']) . '&text=' . $value['pre_set_message'];
											} else {
												$social_link = 'https://api.whatsapp.com/send?phone=' . str_replace('+', '', $value['text']);
											}
											if ( wp_is_mobile()) {
												$link_target = 0;
											}
											break;
										case 'phone':
											$value['text'] = str_replace( array('http://', 'https://') , array('','') , $value['text']);


											if (strpos($value['text'], 'tel:') == false) {
												$social_link = "tel:".$value['text'];
											} else {
												$social_link = $value['text'];
											}
											$link_target = 0;
											break;
										case 'email':
											if (strpos($value['text'], 'mailto:') == false) {
												$social_link = "mailto:".$value['text'];
											} else {
												$social_link = $value['text'];
											}
											$link_target = 0;
											break;
										case 'wechat':
											$social_link = '';
											break;
										case 'facebook_messenger';
											$value['text'] = str_replace( array('http://', 'https://') , array('','') , $value['text']);
											$value_dash_count = substr_count ($value['text'], '-');
											if( $value_dash_count > 0 ) {
												$split_value = explode( '-', $value['text'] );
												$value_final = $split_value[count($split_value)-1];
											} else {
												$value_final = $value['text'];
											}
											$social_link = 'https://m.me/' . $value_final;
											if ( wp_is_mobile()) {
												$link_target = 0;
											}
											break;
										case 'address':
											$social_link = '';
											$value['text'] = str_replace( array('http://', 'https://') , array('','') , $value['text']);
											if ($value['text'] != '') {
												$hover_text .= ': ' . $value['text'];
											}
											break;
										case 'business_hours':
											$social_link = '';
											$value['text'] = str_replace( array('http://', 'https://') , array('','') , $value['text']);
											if ($value['text'] != '') {
												$hover_text .= ': ' . $value['text'];
											}
											break;
										case 'vk' :
											$social_link = 'https://vk.me/' . $value['text'];
											break;
										case 'viber' :
											$social_link = "viber://chat?number=" . $value['text'];
											if ( wp_is_mobile()) {
												$link_target = 0;
											}
											break;
										case 'snapchat' :
											 $social_link = "https://www.snapchat.com/add/" . $value['text'];
											break;
										case 'skype' :
											$social_link = "skype:" . $value['text'] . "?chat";
											$link_target = 0;
											break;
										case 'SMS' :
											$social_link = "sms:" . $value['text'];
											$link_target = 0;
											break;
										case 'qq':
											$social_link = '';
											$value['text'] = str_replace( array('http://', 'https://') , array('','') , $value['text']);
											if ($value['text'] != '') {
												$hover_text .= ': ' . $value['text'];
											}
											break;
										default:
											$social_link = $value['text'];
											break;
									}
									if ( isset($social_channels_list['custom_html']) && $social_channels_list['custom_html'] == 1) {
										$social_link = '';
										$element_class .= ' mystickyelements-custom-html-main';
									}
									if(preg_match('/^<iframe /',$value['text'])){
										$element_class .=" mystickyelements-custom-html-iframe";
									}

									if( isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1 ) {
										if( isset($value['open_newtab']) && $value['open_newtab'] == 1 ) {
											$link_target = 1;
										} else {
											$link_target = 0;
										}
									}
									?>
									<li id="mystickyelements-social-<?php echo esc_attr($key);?>"
										class="mystickyelements-social-<?php echo esc_attr($key);?> <?php echo esc_attr($element_class);?>">
										<?php
										/*diamond template css*/
										if ( isset($value['bg_color']) && $value['bg_color'] != '' ) {
											?>
											<style>
												<?php
												if( $general_settings['templates'] == 'diamond' ) {
												?>
													.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
														background: <?php echo $value['bg_color']; ?>;
													}
													@media only screen and (min-width: 1025px) {
														.mystickyelements-position-left.mystickyelements-on-click.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form).elements-active span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after,
														.mystickyelements-position-left.mystickyelements-on-hover.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form):hover span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after	{
															background-color: <?php echo $value['bg_color']; ?>;
														}
														.mystickyelements-position-right.mystickyelements-on-click.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form).elements-active span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after,
														.mystickyelements-position-right.mystickyelements-on-hover.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form):hover span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after {
															background-color: <?php echo $value['bg_color']; ?>;
														}
														.mystickyelements-position-left.mystickyelements-templates-diamond .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
															border-left-color: <?php echo $value['bg_color']; ?>;
														}
														.mystickyelements-position-right.mystickyelements-templates-diamond .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
															border-right-color: <?php echo $value['bg_color']; ?>;
														}
													}
													@media only screen and (max-width: 1024px) {
														.mystickyelements-position-mobile-left.mystickyelements-on-click.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form).elements-active span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after,
														.mystickyelements-position-mobile-left.mystickyelements-on-hover.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form):hover span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after	{
															background-color: <?php echo $value['bg_color']; ?>;
														}
														.mystickyelements-position-mobile-right.mystickyelements-on-click.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form).elements-active span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after,
														.mystickyelements-position-mobile-right.mystickyelements-on-hover.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form):hover span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after {
															background-color: <?php echo $value['bg_color']; ?>;
														}
														.mystickyelements-position-mobile-left.mystickyelements-templates-diamond .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
															border-left-color: <?php echo $value['bg_color']; ?>;
														}
														.mystickyelements-position-mobile-right.mystickyelements-templates-diamond .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
															border-right-color: <?php echo $value['bg_color']; ?>;
														}
													}
												<?php
												}
												if( $general_settings['templates'] == 'arrow' ) {
												?>
													<?php if( $key == 'insagram' ) { ?>
													.mystickyelements-position-left.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
													.mystickyelements-position-left.mystickyelements-templates-arrow .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
														background: <?php echo $value['bg_color']; ?>;
													}
													<?php } ?>
													@media only screen and (min-width: 1025px) {
														.mystickyelements-position-left.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
														.mystickyelements-position-left.mystickyelements-templates-arrow .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
															border-left-color: <?php echo $value['bg_color']; ?>;
														}
														.mystickyelements-position-right.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
														.mystickyelements-position-right.mystickyelements-templates-arrow .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
															border-right-color: <?php echo $value['bg_color']; ?>;
														}
														.mystickyelements-position-bottom.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
														.mystickyelements-position-bottom.mystickyelements-templates-arrow .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
															border-bottom-color: <?php echo $value['bg_color']; ?>;
														}
													}
													@media only screen and (max-width: 1024px) {
														.mystickyelements-position-mobile-left.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
														.mystickyelements-position-mobile-left.mystickyelements-templates-arrow .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
															border-left-color: <?php echo $value['bg_color']; ?>;
														}
														.mystickyelements-position-mobile-right.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
														.mystickyelements-position-mobile-right.mystickyelements-templates-arrow .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
															border-right-color: <?php echo $value['bg_color']; ?>;
														}
													}
												<?php
												}
												if( $general_settings['templates'] == 'triangle' ) {
												?>
													.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
													.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after {
														background: <?php echo $value['bg_color']; ?>;
													}
													@media only screen and (min-width: 1025px) {
														.mystickyelements-position-left.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) .social-<?php echo esc_attr($key);?> + span.mystickyelements-social-text::before {
															border-left-color: <?php echo $value['bg_color']; ?>;
														}
														.mystickyelements-position-right.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) .social-<?php echo esc_attr($key);?> + span.mystickyelements-social-text::before {
															border-right-color: <?php echo $value['bg_color']; ?>;
														}
														.mystickyelements-position-bottom.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
														.mystickyelements-position-bottom.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) .social-<?php echo esc_attr($key);?> + span.mystickyelements-social-text::before {
															border-bottom-color: <?php echo $value['bg_color']; ?>;
														}
														.mystickyelements-position-bottom.mystickyelements-on-click.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form).elements-active span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
														.mystickyelements-position-bottom.mystickyelements-on-hover.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form):hover span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
															background-color: <?php echo $value['bg_color']; ?>;
														}
													}
													@media only screen and (max-width: 1024px) {
														.mystickyelements-position-mobile-left.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) .social-<?php echo esc_attr($key);?> + span.mystickyelements-social-text::before {
															border-left-color: <?php echo $value['bg_color']; ?>;
														}
														.mystickyelements-position-mobile-right.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) .social-<?php echo esc_attr($key);?> + span.mystickyelements-social-text::before {
															border-right-color: <?php echo $value['bg_color']; ?>;
														}
														.mystickyelements-position-mobile-left.mystickyelements-on-click.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form).elements-active span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
														.mystickyelements-position-mobile-left.mystickyelements-on-hover.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form):hover span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before	{
															background-color: <?php echo $value['bg_color']; ?>;
														}
														.mystickyelements-position-mobile-right.mystickyelements-on-click.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form).elements-active span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
														.mystickyelements-position-mobile-right.mystickyelements-on-hover.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form):hover span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
															background-color: <?php echo $value['bg_color']; ?>;
														}
													}
												<?php
												}
												?>
											</style>
											<?php
										}
										?>
										<span class="mystickyelements-social-icon social-<?php echo esc_attr($key);?>"
											  <?php if (isset($value['bg_color']) && $value['bg_color'] != ''): ?>style="background: <?php echo esc_attr($value['bg_color']); ?>" <?php endif;
										?>>
											<?php if ($social_link != '' && $general_settings['open_tabs_when'] == 'hover' ):	?>
												<a href="<?php echo esc_url($social_link, $protocols); ?>"  <?php if ( $link_target == 1 ):?> target="_blank" rel="noopener" <?php endif;?>>
													<?php endif;

													if (isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1 && $value['custom_icon'] != '' &&  $value['fontawesome_icon'] == ''): ?>
														<img src="<?php echo esc_url($value['custom_icon']); ?>"/>
													<?php else:
														if ( isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1 && $value['fontawesome_icon'] != '' ) {
															$social_channels_list['class'] = $value['fontawesome_icon'];
														}
														if ( isset($social_channels_list['custom_svg_icon']) && $social_channels_list['custom_svg_icon'] != '' ) :
															echo $social_channels_list['custom_svg_icon'];
														else: ?>
														<i class="<?php echo esc_attr($social_channels_list['class']); ?>" <?php if ( isset($value['icon_color']) && $value['icon_color'] != '') : echo "style='color:" . $value['icon_color'] . "'"; endif; ?>></i>
													<?php endif;
													endif;
													if ( isset($value['icon_text']) && $value['icon_text'] != '' && isset($general_settings['templates']) && $general_settings['templates'] == 'default' ) {
														$icon_text_size = '';
														if ( isset($value['icon_text_size']) && $value['icon_text_size'] != '') {
															$icon_text_size = "font-size: " . $value['icon_text_size'] . "px";
														}
														echo "<span class='mystickyelements-icon-below-text' style='".$icon_text_size."'>" . esc_html($value['icon_text']) . "</span>";
													}
													if ($social_link != '' && $general_settings['open_tabs_when'] == 'hover'): ?>
												</a>
											<?php endif;

											if ( $key == 'line') {
												echo "<style>.mystickyelements-social-icon.social-". $key ." svg .fil1{ fill:" .$value['icon_color']. "}</style>";
											}
											if ( $key == 'qzone') {
												echo "<style>.mystickyelements-social-icon.social-". $key ." svg .fil2{ fill:" . $value['icon_color'] . "}</style>";
											}
											?>
										</span>

									<?php if ( isset($social_channels_list['custom_html']) && $social_channels_list['custom_html'] == 1  ) :?>
										<div class="mystickyelements-custom-html">
											<div class="mystickyelements-custom-html-wrap">
												<?php echo do_shortcode( str_replace('\"', '"', stripslashes($value['text'])));?>
											</div>
										</div>
									<?php else :
										$icon_bg_color = $icon_text_color = '';
										if (isset($value['bg_color']) && $value['bg_color'] != '') {
											$icon_bg_color = "background: " . esc_attr($value['bg_color']) . ";";
										}
										if (isset($value['icon_color']) && $value['icon_color'] != '') {
											$icon_text_color = "color: " . esc_attr($value['icon_color']) . ";";
										}
									?>
										<span class="mystickyelements-social-text <?php echo ($social_link == '') ? 'mystickyelements-social-no-link' : '';?>" style= "<?php echo $icon_bg_color.$icon_text_color ?>">
											<?php if ($social_link != ''): ?>
											<a href="<?php echo esc_url($social_link, $protocols); ?>" <?php if ( $link_target == 1 ):?> target="_blank" rel="noopener" <?php endif;?> <?php if ( isset($value['icon_color']) && $value['icon_color'] != '') : echo "style='color:" . $value['icon_color'] . "'"; endif; ?>>
											<?php endif;
												if ($key == 'wechat') {
													echo esc_html($hover_text . ': ' . $value['text']);
												} else {
													echo esc_html($hover_text);
												}?>
												<?php if ($social_link != ''): ?>
											</a>
										<?php endif; ?>
										</span>
									<?php endif;?>
									</li>

								<?php endforeach;
							endif;
							?>
						</ul>

						<?php if (!isset($contact_form['remove_widget']) || $contact_form['remove_widget'] != 1) { ?>
							<div class="mystickyelement-credit">
								<a href="https://premio.io/downloads/mystickyelements/?utm_source=<?php echo site_url(); ?>"
								   target="_blank" rel="noopener" style="font-size: 9px !important;text-decoration: none !important;color: #000000 !important;display: inline-block !important;vertical-align: top !important;margin: 5px 0 0 0 !important;">
									<?php esc_html_e('Get Widget', 'mystickyelements'); ?>
								</a>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php
				/* Include Custom CSS */
				// Add Themme custom CSS
				if (  isset($contact_form['form_css']) || isset($general_settings['tabs_css']) || ( isset($general_settings['font_family']) && $general_settings['font_family'] != '') ) {
					$custom_css = '';
					if ( isset($general_settings['font_family']) && $general_settings['font_family'] != '' ) {
						$custom_css .= '.mystickyelements-fixed,
										form#stickyelements-form select,
										form#stickyelements-form input,
										form#stickyelements-form textarea,
										.element-contact-form h3 {
											font-family: "' . $general_settings['font_family'] . '";
										}';
						$custom_css .= '.mystickyelements-contact-form[dir="rtl"],
										.mystickyelements-contact-form[dir="rtl"] .element-contact-form h3,
										.mystickyelements-contact-form[dir="rtl"] form#stickyelements-form input,
										.mystickyelements-contact-form[dir="rtl"] form#stickyelements-form textarea,
										.mystickyelements-fixed[dir="rtl"] .mystickyelements-social-icon,
										.mystickyelements-fixed[dir="rtl"] .mystickyelements-social-text,
										html[dir="rtl"] .mystickyelements-contact-form,
										html[dir="rtl"] .mystickyelements-contact-form .element-contact-form h3,
										html[dir="rtl"] .mystickyelements-contact-form form#stickyelements-form input,
										html[dir="rtl"] .mystickyelements-contact-form form#stickyelements-form textarea,
										html[dir="rtl"] .mystickyelements-fixed .mystickyelements-social-icon,
										html[dir="rtl"] .mystickyelements-fixed .mystickyelements-social-text {
											font-family: "' . $general_settings['font_family'] . '";
										}';
					}
					if (isset($general_settings['custom_position']) && $general_settings['custom_position'] != '') {
						$custom_css .= '.mystickyelements-fixed {
										bottom: ' . $general_settings['custom_position'] . 'px;
										top: auto;
										-webkit-transform: translateY(0);
										-moz-transform: translateY(0);
										transform: translateY(0);
									}';
						$custom_css .= '.mystickyelements-fixed.mystickyelements-custom-html-iframe-open {
										top: auto;
										bottom: ' . $general_settings['custom_position'] . 'px;
									}';
						$custom_css .= '.mystickyelements-fixed ul {
											position: relative;
										}';
						$custom_css .= '.mystickyelements-custom-html-iframe .mystickyelements-custom-html {
											top: auto;
											bottom: 0;
											-webkit-transform: rotateY(90deg) translateY(0);
											-moz-transform: rotateY(90deg) translateY(0);
											transform: rotateY(90deg) translateY(0);
										}';
						$custom_css .= '.mystickyelements-on-click.mystickyelements-fixed ul li.mystickyelements-custom-html-main.mystickyelements-custom-html-iframe.elements-active .mystickyelements-custom-html, 	.mystickyelements-on-hover.mystickyelements-fixed ul li.mystickyelements-custom-html-main.mystickyelements-custom-html-iframe:hover .mystickyelements-custom-html {
											-webkit-transform: rotateY(0deg) translateY(0);
											-moz-transform: rotateY(0deg) translateY(0);
											transform: rotateY(0deg) translateY(0);
										}';
					}
					if (isset($contact_form['form_css']) && $contact_form['form_css'] !='' ) {
						$custom_css .= trim(strip_tags($contact_form['form_css']));
					}
					if (isset($general_settings['tabs_css']) && $general_settings['tabs_css'] !='' ) {
						$custom_css .= trim(strip_tags($general_settings['tabs_css']));
					}
					if (!empty($custom_css)) {
						?>
						<style>
							<?php echo $custom_css; ?>
						</style>
						<?php
					}
				}
				/* END Include custom css*/
			}
		}

        public function mystickyelements_contact_form()
        {

            global $wpdb;
            check_ajax_referer('mystickyelements', 'security');

            $errors = array();
			$element_widget_no = $_POST['widget_number'];
			$element_widget_name = ( isset($_POST['widget_name']) && $_POST['widget_name'] != '' ) ? $_POST['widget_name'] : 'default';

            $contact_form = get_option('mystickyelements-contact-form' . $element_widget_no );
            if (isset($contact_form['name']) && $contact_form['name'] == 1) {
                if (isset($contact_form['name_require']) && $contact_form['name_require'] == 1 && (!isset($_POST['contact-form-name']) || empty($_POST['contact-form-name']))) {
                    $error = array(
                        'key' => "contact-form-name",
                        'message' => "This field is required"
                    );
                    $errors[] = $error;
                }
            }
            if (isset($contact_form['email']) && $contact_form['email'] == 1) {
                if (isset($contact_form['email_require']) && $contact_form['email_require'] == 1 && (!isset($_POST['contact-form-email']) || empty($_POST['contact-form-email']))) {
                    $error = array(
                        'key' => "contact-form-email",
                        'message' => "This field is required"
                    );
                    $errors[] = $error;
                } else if ( isset($contact_form['email_require']) && $contact_form['email_require'] == 1 && isset($_POST['contact-form-email']) && !filter_var($_POST['contact-form-email'], FILTER_VALIDATE_EMAIL)) {
                    $error = array(
                        'key' => "contact-form-email",
                        'message' => "Email address is not valid"
                    );
                    $errors[] = $error;
                }
            }

            if (isset($contact_form['message']) && $contact_form['message'] == 1) {
                if (isset($contact_form['message_require']) && $contact_form['message_require'] == 1 && (!isset($_POST['contact-form-message']) || empty($_POST['contact-form-message']))) {
                    $error = array(
                        'key' => "contact-form-message",
                        'message' => "This field is required"
                    );
                    $errors[] = $error;
                }
            }

            if (isset($contact_form['phone']) && $contact_form['phone'] == 1) {
                if (isset($contact_form['phone_require']) && $contact_form['phone_require'] == 1 && (!isset($_POST['contact-form-phone']) || empty($_POST['contact-form-phone']))) {
                    $error = array(
                        'key' => "contact-form-phone",
                        'message' => "This field is required"
                    );
                    $errors[] = $error;
                }
            }
			if (isset($contact_form['dropdown']) && $contact_form['dropdown'] == 1) {
                if (isset($contact_form['dropdown_require']) && $contact_form['dropdown_require'] == 1 && (!isset($_POST['contact-form-dropdown']) || empty($_POST['contact-form-dropdown']))) {
                    $error = array(
                        'key' => "contact-form-dropdown",
                        'message' => "This field is required"
                    );
                    $errors[] = $error;
                }
            }

			/* Custom Field validation */
			if ( isset($_POST['contact-form']['custom_field']) && !empty($_POST['contact-form']['custom_field'])) {
				foreach($_POST['contact-form']['custom_field'] as $key=>$value) {

					if ( isset($contact_form['custom_fields'][$key]['custom_field_require']) && $contact_form['custom_fields'][$key]['custom_field_require'] == 1 && ( $value == '' || empty($value))  ) {

						$custom_field_name = sanitize_title($contact_form['custom_fields'][$key]['custom_field_name']);
						$error = array(
							'key' => "contact-form-" . $custom_field_name,
							'message' => "This field is required"
						);
						$errors[] = $error;
					}
					if(isset($contact_form['custom_fields'][$key]['custom_field']) && $contact_form['custom_fields'][$key]['custom_field'] == 1 &&  $value != '' ) {
						$custom_fields_value[$contact_form['custom_fields'][$key]['custom_field_name']] = $value;
					}
				}
			}

            $message = "There is error. We are not able to complete your request";

            if (empty($errors)) {
                if (!isset($_POST['nonce']) || empty($_POST['nonce'])) {
                    $error = array(
                        'key' => "mse-form-error",
                        'message' => "There is error. We are not able to complete your request"
                    );
                    $errors[] = $error;
                } else if (!isset($_POST['form_id']) || empty($_POST['form_id'])) {
                    $error = array(
                        'key' => "mse-form-error",
                        'message' => "There is error. We are not able to complete your request"
                    );
                    $errors[] = $error;
                } else if (!wp_verify_nonce($_POST['form_id'], $_POST['nonce'])) {
                    $error = array(
                        'key' => "mse-form-error",
                        'message' => "There is error. We are not able to complete your request"
                    );
                    $errors[] = $error;
                }
                if (!empty($errors)) {
                    echo json_encode(array("status" => 0, "error" => 1, "errors" => $errors, "message" => $message));
                    die;
                }
            } else {
                echo json_encode(array("status" => 0, "error" => 1, "errors" => $errors, "message" => $message));
                die;
            }

			/* Check redirct Link set */
			$redirect_link = '';
			if ( ( isset($contact_form['redirect']) && $contact_form['redirect'] == 1 ) && ( isset($contact_form['redirect_link']) && $contact_form['redirect_link'] != '' ) ) {
				$redirect_link = $contact_form['redirect_link'];
			}
			$open_new_tab = '';
			if ( ( isset($contact_form['open_new_tab']) && $contact_form['open_new_tab'] == 1 ) ) {
				$open_new_tab = $contact_form['open_new_tab'];
			}

             if (isset($_POST['contact-form-email']) || isset($_POST['contact-form-name']) || isset($_POST['contact-form-phone']) || isset($_POST['contact-form-message']) ) {
				$flg = false;
				if ( !is_array( $contact_form['send_leads'])) {
					$contact_form['send_leads'] = explode(', ', $contact_form['send_leads']);
				}

				/* Send Contact form Data by email. */
                if ( in_array( 'mail', $contact_form['send_leads'] )  ) {


                    $send_mail = (isset($contact_form['sent_to_mail']) && $contact_form['sent_to_mail'] != '') ? $contact_form['sent_to_mail'] : get_option('admin_email');

					$email_subject_line = ( isset($contact_form['email_subject_line']) && $contact_form['email_subject_line'] != '' ) ? $contact_form['email_subject_line'] : 'New lead from MyStickyElements';
                    $subject = $email_subject_line ." - " . $_POST['contact-form-name'];
                    $message = "" ;

                    if (isset($_POST['contact-form-name']) && $_POST['contact-form-name'] != '') {
                        $message .= "<p>Name: " . sanitize_text_field($_POST['contact-form-name']) . "<p>\r\n";
                    }
                    if (isset($_POST['contact-form-phone']) && $_POST['contact-form-phone'] != '') {
                        $message .= "<p>Phone: " . sanitize_text_field($_POST['contact-form-phone']) . "</p>\r\n";
                    }
                    if (isset($_POST['contact-form-email']) && $_POST['contact-form-email'] != '') {
                        $message .= "<p>Email: " . sanitize_email($_POST['contact-form-email']) . "</p>\r\n";
                    }
					if (isset($_POST['contact-form-dropdown']) && $_POST['contact-form-dropdown'] != '') {
                        $message .= "<p>" . $contact_form['dropdown-placeholder'] . ": " . sanitize_text_field($_POST['contact-form-dropdown']) . "</p>\r\n";
                    }

					if ( !empty($custom_fields_value) && $custom_fields_value != '') {
						foreach( $custom_fields_value as $key=>$value ){

							$message .= "<p>" . $key ." : " . sanitize_text_field($value) . "</p>\r\n";
						}
                    }

                    if (isset($_POST['contact-form-message']) && $_POST['contact-form-message'] != '') {
                        $message .= "<p>Message: " . sanitize_text_field(stripslashes($_POST['contact-form-message'])) . "</p>\r\n\r\n";
                    }
					if ( $element_widget_name != '') {
                        $message .= "<p>Widget element Name: " . sanitize_text_field(stripslashes($element_widget_name)) . "</p>\r\n";
                    }
                    $message .= "<p>Submission URL: " . sanitize_text_field($_POST['stickyelements-page-link']) . "</p>\r\n\r\n";
					$contact_form_consent_fields = "False";
					if ( isset( $_POST['contact-form-consent-fields'] ) ) {
                        $contact_form_consent_fields = "True";
                    }
                    $message .= "<p>Consent Checkbox: " . $contact_form_consent_fields . "</p>\r\n\r\n";





                    //$message .= "<p>Thank You" . "</p>\r\n";
                    $message .= "<p>" . get_bloginfo('name') . "</p>\r\n";

                    $blog_name = get_bloginfo('name');
                    $blog_email = get_bloginfo('admin_email');

                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                    $headers .= 'From: ' . $blog_name . ' <' . $blog_email . '>' . "\r\n";
                    $headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";
					if (isset($_POST['contact-form-email']) && $_POST['contact-form-email'] != '') {
						$headers .= "Reply-To: " . sanitize_text_field($_POST['contact-form-name']) ." <" . sanitize_email($_POST['contact-form-email']) . ">\r\n";
					}

                    if (wp_mail($send_mail, $subject, $message, $headers)) {
						$flg = true;
                    } else {
						$flg = false ;
                    }
                }

				/* Saved Data into Database */
				if ( in_array( 'database', $contact_form['send_leads'] ) ) {
                    $resultss = $wpdb->insert(
                        $wpdb->prefix . 'mystickyelement_contact_lists',
                        array(
                            'contact_name' 		=> isset($_POST['contact-form-name']) ? esc_sql(sanitize_text_field($_POST['contact-form-name'])) : '',
                            'contact_phone' 	=> isset($_POST['contact-form-phone']) ? esc_sql(sanitize_text_field($_POST['contact-form-phone'])) : '',
                            'contact_email' 	=> isset($_POST['contact-form-email']) ? esc_sql(sanitize_email($_POST['contact-form-email'])) : '',
                            'contact_message' 	=> isset($_POST['contact-form-message']) ? sanitize_textarea_field(stripslashes($_POST['contact-form-message'])) : '',
							'contact_option' 	=> (isset($_POST['contact-form-dropdown'])) ? esc_sql(sanitize_textarea_field($_POST['contact-form-dropdown'])) : '',
							'message_date' 		=> date('Y-m-d H:i:s'),
							'widget_element_name'	=> $element_widget_name,
							//'custom_fields'	=> json_encode($_POST['contact-form']['custom_field']),
							'custom_fields'		=> ( !empty($custom_fields_value) && $custom_fields_value != '' ) ? json_encode($custom_fields_value) : '',
							'page_link' 		=> esc_sql(sanitize_text_field($_POST['stickyelements-page-link'])),
							'consent_checkbox' 	=> isset($_POST['contact-form-consent-fields']) ? true : false,
                        )
                    );
					$flg = true;
                }

				if ( $flg == true ) {
					$thank_you_message = ( isset($contact_form['thank_you_message']) && $contact_form['thank_you_message'] != '' ) ? $contact_form['thank_you_message'] : esc_html__('Your message was sent successfully', 'mystickyelements');

					$message = $thank_you_message;
					echo json_encode(array("status" => 1, "error" => 0, "errors" => array(), "message" => $message , "redirect_link" => $redirect_link, "open_new_tab" => $open_new_tab));
					die;
				} else {
					$message = esc_html__('Something went wrong. Please contact site administrator', 'mystickyelements');
					echo json_encode(array("status" => 0, "error" => 0, "errors" => array(), "message" => $message));
					die;
				}

            }

            wp_die();
        }
    }

}
global $front_settings_page;
$front_settings_page = new MyStickyElementsFrontPage_pro();
