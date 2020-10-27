<?php
namespace Elementor;

use \ElementsKit\Elementskit_Widget_Facebook_Feed_Handler as Handler;
use \ElementsKit\Modules\Controls\Controls_Manager as ElementsKit_Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Elementskit_Widget_Facebook_Feed extends Widget_Base {


	public $base;

    public function get_name() {
        return Handler::get_name();
    }

    public function get_title() {
        return Handler::get_title();
    }

    public function get_icon() {
        return Handler::get_icon();
    }

    public function get_categories() {
        return Handler::get_categories();
    }


	protected function _register_controls() {

        $setting = new \Ekit_facebook_settings();


		/*Layout Settings*/
		$this->start_controls_section(
            'ekit_facebook_feed_layout_settings', [
                'label' => esc_html__( 'Layout Settings ', 'elementskit' ),
            ]
		);

		$this->add_control(
            'ekit_facebook_feed_layout_style',
            [
                'label' => esc_html__( 'Grid Style', 'elementskit' ),
                'type' =>  Controls_Manager::SELECT,
                'default' => 'ekit-layout-masonary',
                'options' => [
                    'ekit-layout-list'  => esc_html__( 'List', 'elementskit' ),
                    'ekit-layout-grid' => esc_html__( 'Grid', 'elementskit' ),
                    'ekit-layout-masonary' => esc_html__( 'Masonary', 'elementskit' ),
                ],
            ]
        );

		$this->add_control(
            'ekit_facebook_feed_column_grid',
            [
                'label' => esc_html__( 'Columns Grid', 'elementskit' ),
                'type' =>  Controls_Manager::SELECT,
                'default' => 'ekit-fb-col-4',
                'options' => [
                    'ekit-fb-col-6'  => esc_html__( '2 Columns', 'elementskit' ),
                    'ekit-fb-col-4' => esc_html__( '3 Columns', 'elementskit' ),
                    'ekit-fb-col-3' => esc_html__( '4 Columns', 'elementskit' ),
                ],
				'condition' => ['ekit_facebook_feed_layout_style!' => 'ekit-layout-list'],
            ]
		);

		$this->add_control(
			'ekit_facebook_feed_style_choose',
			[
				'label' => esc_html__( 'Feed Style', 'elementskit' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'classic',
				'options' => [
					'classic'  => esc_html__( 'Classic', 'elementskit' ),
					'photos' => esc_html__( 'Photos', 'elementskit' ),
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
            'ekit_facebook_feed_show_post',
            [
                'label'         => esc_html__('Show Post', 'elementskit'),
                'type'          => Controls_Manager::NUMBER,
                'default' 		=> 9,
				'min' 			=> 1,
				'max' 			=> 100,
				'step' 			=> 1,

            ]
        );

		$this->end_controls_section();
		/* End layout settings*/

		/*Display Settings*/
		$this->start_controls_section(
            'ekit_facebook_feed_display_settings', [
				'label' => esc_html__( 'Display Settings ', 'elementskit' ),
				'condition' => [
					'ekit_facebook_feed_style_choose' => 'classic'
				]
            ]
        );


		$this->add_control(
            'ekit_facebook_feed_show_author',
            [
                'label' => esc_html__( 'Show Author', 'elementskit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'elementskit' ),
                'label_off' => esc_html__( 'Hide', 'elementskit' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
		$this->add_control(
            'ekit_facebook_feed_author_type',
            [
                'label' => esc_html__( 'Author Settings', 'elementskit' ),
                'type' =>  Controls_Manager::SELECT,
                'default' => 'both',
                'options' => [
                    'only-profile'  => esc_html__( 'Only Profile Image', 'elementskit' ),
                    'only-name' => esc_html__( 'Only Name', 'elementskit' ),
                    'both' => esc_html__( 'Both', 'elementskit' ),
                ],
				'condition' => [
					'ekit_facebook_feed_show_author' => 'yes',
				]
            ]
        );
		$this->add_control(
            'ekit_facebook_feed_author_style',
            [
                'label' => esc_html__( 'Author Style', 'elementskit' ),
                'type' =>  Controls_Manager::SELECT,
                'default' => 'circle',
                'options' => [
                    'circle'  => esc_html__( 'Circle', 'elementskit' ),
                    'square' => esc_html__( 'Square', 'elementskit' ),
                ],
				'condition' => [
					'ekit_facebook_feed_author_type!' => 'only-name',
				]
            ]
        );
		$this->add_control(
            'ekit_facebook_feed_show_post_date',
            [
                'label' => esc_html__( 'Show Date', 'elementskit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'elementskit' ),
                'label_off' => esc_html__( 'Hide', 'elementskit' ),
                'return_value' => 'yes',
				'default' => 'yes',
            ]
        );


		$this->add_control(
            'ekit_facebook_feed_show_fotter',
            [
                'label' => esc_html__( 'Show Fotter', 'elementskit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'elementskit' ),
                'label_off' => esc_html__( 'Hide', 'elementskit' ),
                'return_value' => 'yes',
				'default' => 'yes',
            ]
        );

		$this->add_control(
            'ekit_facebook_feed_show_comments',
            [
                'label' => esc_html__( 'Show Comments', 'elementskit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'elementskit' ),
                'label_off' => esc_html__( 'Hide', 'elementskit' ),
                'return_value' => 'yes',
				'default' => 'no',
            ]
        );

		$this->end_controls_section();
		/* End display settings*/

		/* Start style settings */
		$this->start_controls_section(
			'ekit_facebook_feed_layout_style_tab',
			[
				'label' => esc_html__( 'Layout', 'elementskit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_layout_gutter',
			[
				'label' => esc_html__( 'Gutter', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-single-fb-feed-holder' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .ekit-layout-grid ' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ekit_facebook_feed_layout_style!' => 'ekit-layout-masonary'
				]
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_layout_gutter_masnory',
			[
				'label' => esc_html__( 'Gutter', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-layout-masonary' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ekit_facebook_feed_layout_style' => 'ekit-layout-masonary'
				]
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_layout_margin_bottom',
			[
				'label' => esc_html__( 'Margin Bottom', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-single-fb-feed' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/** start container style */
		$this->start_controls_section(
			'ekit_facebook_feed_container_style_tab',
			[
				'label' => esc_html__( 'Container', 'elementskit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_facebook_feed_container_border',
				'label' => esc_html__( 'Border', 'elementskit' ),
				'selector' => '{{WRAPPER}} .ekit-single-fb-feed',
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_container_padding',
			[
				'label' => esc_html__( 'Padding', 'elementskit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-single-fb-feed' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'ekit_facebook_feed_style_choose' => 'classic'
				]
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_container_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementskit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-single-fb-feed' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'ekit_facebook_feed_container_normal_and_hover_tabs' );

		$this->start_controls_tab(
			'ekit_facebook_feed_container_normal_tab',
			[
				'label' =>esc_html__( 'Normal', 'elementskit' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'ekit_facebook_feed_container_normal_background',
				'label' => esc_html__( 'Background', 'elementskit' ),
				'types' => [ 'classic',],
				'selector' => '{{WRAPPER}} .ekit-single-fb-feed',
				'exclude' => [
					'image'
				],
				'default' => '#FFFFFF',
				'condition' => [
					'ekit_facebook_feed_style_choose' => 'classic'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ekit_facebook_feed_container_normal_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'elementskit' ),
				'selector' => '{{WRAPPER}} .ekit-single-fb-feed',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ekit_facebook_feed_container_hover_tab',
			[
				'label' =>esc_html__( 'Hover', 'elementskit' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'ekit_facebook_feed_container_hover_background',
				'label' => esc_html__( 'Background', 'elementskit' ),
				'types' => [ 'classic', ],
				'selector' => '{{WRAPPER}} .ekit-single-fb-feed:hover',
				'exclude' => [
					'image'
				],
				'default' => '#FFFFFF',
				'condition' => [
					'ekit_facebook_feed_style_choose' => 'classic'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ekit_facebook_feed_container_hover_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'elementskit' ),
				'selector' => '{{WRAPPER}} .ekit-single-fb-feed:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
		/** end container */

		/** start header */
		$this->start_controls_section(
			'ekit_facebook_feed_header_style_tab',
			[
				'label' => esc_html__( 'Header', 'elementskit' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'ekit_facebook_feed_style_choose' => 'classic'
				]
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_header_margin',
			[
				'label' => esc_html__( 'Header Margin', 'elementskit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-feed-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_header_padding',
			[
				'label' => esc_html__( 'Header Padding', 'elementskit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-feed-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_header_date_color',
			[
				'label' => esc_html__( 'Date Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#616770',
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-feed-header .ekit-fb-post-publish-date' => 'color: {{VALUE}}',
				],
			]
		);

		$this->start_controls_tabs( 'ekit_facebook_feed_header_normal_and_hover_tabs' );

		$this->start_controls_tab(
			'ekit_facebook_feed_header_normal_tab',
			[
				'label' =>esc_html__( 'Normal', 'elementskit' ),
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_header_normal_color',
			[
				'label' => esc_html__( 'User Name', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#365899',
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-feed-header .user-name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ekit_facebook_feed_header_hover_tab',
			[
				'label' =>esc_html__( 'Hover', 'elementskit' ),
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_header_hover_color',
			[
				'label' => esc_html__( 'User Name', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#365899',
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-feed-header .user-name:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'ekit_facebook_feed_header_heading',
			[
				'label' => esc_html__( 'Content', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_header_content_color',
			[
				'label' => esc_html__( 'Content Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-feed-status' => 'color: {{VALUE}}',
				],
			]
		);

		$this->start_controls_tabs( 'ekit_facebook_feed_header_content_action_normal_and_hover_tabs' );

		$this->start_controls_tab(
			'ekit_facebook_feed_header_content_action_normal_tab',
			[
				'label' =>esc_html__( 'Normal', 'elementskit' ),
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_header_content_action_normal_color',
			[
				'label' => esc_html__( 'User Name', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#365899',
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-feed-status > a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ekit_facebook_feed_header_content_action_hover_tab',
			[
				'label' =>esc_html__( 'Hover', 'elementskit' ),
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_header_content_action_hover_color',
			[
				'label' => esc_html__( 'User Name', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#365899',
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-feed-status > a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
		/** end header style */

		/** style media */
		$this->start_controls_section(
			'ekit_facebook_feed_media_style_tab',
			[
				'label' => esc_html__( 'Media', 'elementskit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_media_image_height',
			[
				'label' => esc_html__( 'Height', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
				'range' => [
					'px' => [
						'min' => .5,
                        'max' => 2.5,
                        'step' => .01,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => .6,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-layout-grid .ekit_fb_photo_gallery .ekit_fb_photo_link' => 'padding-bottom: calc(100% * {{SIZE}});',
				],
				'condition' => [
					'ekit_facebook_feed_style_choose' => 'photos',
					'ekit_facebook_feed_layout_style' => 'ekit-layout-grid'
				]
			]
		);

		$this->add_control(
			'ekit_facebook_feed_media_image_show_overlay',
			[
				'label' => esc_html__( 'Overlay', 'elementskit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementskit' ),
				'label_off' => esc_html__( 'Hide', 'elementskit' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'ekit_facebook_feed_style_choose' => 'photos'
				]
			]
		);

		$this->start_controls_tabs(
			'ekit_facebook_feed_header_image_gallery_and_hover_tabs',
			[
				'condition' => [
					'ekit_facebook_feed_style_choose' => 'photos'
				]
			]
		);

		$this->start_controls_tab(
			'ekit_facebook_feed_header_image_gallery_tab',
			[
				'label' =>esc_html__( 'Normal', 'elementskit' ),
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_header_image_gallery_scale',
			[
				'label' => esc_html__( 'Scale', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px',],
				'range' => [
					'px' => [
						'min' => .5,
						'max' => 1.5,
						'step' => .01,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit_fb_photo_gallery .ekit_fb_photo_link .ekit_fb_photo' => 'transform: scale({{SIZE}});',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_header_image_gallery_opaicty',
			[
				'label' => esc_html__( 'Opacity', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px',],
				'range' => [
					'px' => [
						'min' => .5,
						'max' => 1,
						'step' => .01,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit_fb_photo_gallery .ekit_fb_photo_link .ekit_fb_photo' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ekit_facebook_feed_header_photo_gallery_tab',
			[
				'label' =>esc_html__( 'Hover', 'elementskit' ),
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_hover_photo_gallery_scale',
			[
				'label' => esc_html__( 'Scale', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px',],
				'range' => [
					'px' => [
						'min' => .5,
						'max' => 1.5,
						'step' => .01,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1.02,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit_fb_photo_gallery .ekit_fb_photo_link:hover .ekit_fb_photo' => 'transform: scale({{SIZE}});',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_hover_photo_gallery_opacity',
			[
				'label' => esc_html__( 'Opacity', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px',],
				'range' => [
					'px' => [
						'min' => .5,
						'max' => 1,
						'step' => .01,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => .9,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit_fb_photo_gallery .ekit_fb_photo_link:hover .ekit_fb_photo' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'ekit_facebook_feed_media_margin',
			[
				'label' => esc_html__( 'Margin', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-single-fb-feed:not(.ekit_fb_photo_gallery) .ekit-fb-feed-media' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ekit_facebook_feed_style_choose' => 'classic'
				]
			]
		);

		$this->start_controls_tabs(
			'ekit_facebook_feed_header_content_play_and_hover_tabs',
			[
				'condition' => [
					'ekit_facebook_feed_style_choose' => 'classic'
				]
			]
		);

		$this->start_controls_tab(
			'ekit_facebook_feed_header_content_play_tab',
			[
				'label' =>esc_html__( 'Normal', 'elementskit' ),
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_header_content_play_color',
			[
				'label' => esc_html__( 'Play Icon', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-video-play-button svg path' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_header_content_play_scale',
			[
				'label' => esc_html__( 'Scale', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px',],
				'range' => [
					'px' => [
						'min' => .9,
						'max' => 2,
						'step' => .1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => .9,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-video-play-button svg' => 'transform: scale({{SIZE}});',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ekit_facebook_feed_header_play_hover_tab',
			[
				'label' =>esc_html__( 'Hover', 'elementskit' ),
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_header_play_hover_color',
			[
				'label' => esc_html__( 'Play Icon', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#365899',
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-video-post:hover .ekit-fb-video-play-button svg path' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_hover_play_scale',
			[
				'label' => esc_html__( 'Scale', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px',],
				'range' => [
					'px' => [
						'min' => .9,
						'max' => 2,
						'step' => .1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-video-post:hover .ekit-fb-video-play-button svg' => 'transform: scale({{SIZE}});',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
		/** end media */

		$this->start_controls_section(
			'ekit_facebook_feed_link_style_tab',
			[
				'label' => esc_html__( 'Link', 'elementskit' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'ekit_facebook_feed_style_choose' => 'classic'
				]
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_link_spacing',
			[
				'label' => esc_html__( 'Padding', 'elementskit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-link-type-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'ekit_facebook_feed_link_background',
				'label' => esc_html__( 'Background', 'elementskit' ),
				'types' => [ 'classic', ],
				'default' => '#f2f3f5',
				'selector' => '{{WRAPPER}} .ekit-fb-link-type-footer',
				'exclude' => [
					'image'
				]
			]
		);

		$this->add_control(
			'ekit_facebook_feed_link_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_link_source_name',
			[
				'label' => esc_html__( 'Source Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#606770',
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-link-type-footer .ekit-fb-source-name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_link_caption_name',
			[
				'label' => esc_html__( 'Caption Name Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#1d2129',
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-link-type-footer .ekit-fb-caption-name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_link_caption',
			[
				'label' => esc_html__( 'Caption Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#606770',
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-link-type-footer .ekit-fb-caption' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		/** start footer style */
		$this->start_controls_section(
			'ekit_facebook_feed_footer_style_tab',
			[
				'label' => esc_html__( 'Footer', 'elementskit' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'ekit_facebook_feed_style_choose' => 'classic'
				]
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_footer_text_color',
			[
				'label' => esc_html__( 'Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#606770',
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-fotter-section .ekit-facebook-like' => 'color: {{VALUE}}',
					'{{WRAPPER}} .ekit-fb-fotter-section .ekit-facebook-comments' => 'color: {{VALUE}}',
					'{{WRAPPER}} .ekit-fb-fotter-section .ekit-facebook-retweet' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_facebook_feed_footer_text_color_hover',
			[
				'label' => esc_html__( 'Color Hover', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#365899',
				'selectors' => [
					'{{WRAPPER}} .ekit-fb-fotter-section .ekit-facebook-like:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .ekit-fb-fotter-section .ekit-facebook-comments:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .ekit-fb-fotter-section .ekit-facebook-retweet:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
		/** end footer style */
		/* End style settings */
	}
	public function str_check($textData = ''){
        $stringText = '';
        if(strlen($textData) > 5){
            $explodeText = explode(' ', trim($textData));
             for($st = 0 ; $st < count($explodeText) ; $st++){
                $pos = stripos(trim($explodeText[$st]), '#');
                $pos1 = stripos(trim($explodeText[$st]), '@');
				$poshttp = stripos(trim($explodeText[$st]), 'http');
				$poshttps = stripos(trim($explodeText[$st]), 'https');

                if($pos !== false){
                    $stringText .= '<a href="https://facebook.com/hashtag/'.str_replace('#', '', $explodeText[$st]).'?source=feed_text" target="_blank"> '.$explodeText[$st].' </a>';
                }else if($pos1 !== false){
                    $stringText .= '<a href="https://facebook.com/'.$explodeText[$st].'/" target="_blank"> '. $explodeText[$st].' </a>';
                }else if($poshttp !== false || $poshttps !== false){
                    $stringText .= '<a href="'.$explodeText[$st].'" target="_blank"> '. $explodeText[$st].' </a>';
                }else{
                    $stringText .= ' '.$explodeText[$st];
                }
            }
        }

        return $stringText;
	}

	protected function unit_converter($unit) {
		$convert_reaction = 0;
		$reaction_suffix = '';

		if ($unit >= 0 && $unit < 10000) {
			$convert_reaction = number_format($unit);
		}else if ($unit >= 10000 && $unit < 1000000) {
			$convert_reaction = round(floor($unit / 1000), 1);
			$reaction_suffix = 'K';
		}else if ($unit >= 1000000 && $unit < 100000000) {
			$convert_reaction = round(($unit / 1000000), 1);
			$reaction_suffix = 'M';
		}else if ($unit >= 100000000 && $unit < 1000000000) {
			$convert_reaction = round(floor($unit / 100000000), 1);
			$reaction_suffix = 'B';
		}else if($unit >= 1000000000){
			$convert_reaction = round(floor($unit / 1000000000), 1);
			$reaction_suffix = 'T';
		}

		return $convert_reaction.''.$reaction_suffix;
	}

	protected function render( ) {
        echo '<div class="ekit-wid-con" >';
            $this->render_raw();
        echo '</div>';
    }

    protected function render_raw( ) {
		   $settings = $this->get_settings();
		   extract($settings);

		    $config 			  = Handler::get_data();

			$setting = new \Ekit_facebook_settings(  );
			$setting->setup($config);

			$response = $setting->get_feed('', $ekit_facebook_feed_show_post);
			$styleLayout = $ekit_facebook_feed_layout_style;
			$columnFeed = 'ekit-fb-col-12';
            if($styleLayout != 'ekit-layout-list' && $styleLayout != 'ekit-layout-masonary'){
                $columnFeed = $ekit_facebook_feed_column_grid;
            }
            $masnory_column = '';
            if($styleLayout == 'ekit-layout-masonary'){
                $masnory_column = $ekit_facebook_feed_column_grid;
            }
		   ?>
			<div class="ekit-facebook-feed">
				<div class="ekit-fb-row <?php echo esc_attr($masnory_column.' '.$styleLayout); ?>">
				<?php
				if(isset($response->error) ){
					echo esc_html($response->error->message);
					return '';
				}
				if(isset($response->data)):
					$profileImage = '';
					foreach($response->data AS $fbjson):
						$fb_id 		= isset($fbjson->id) ? $fbjson->id : '';
						$fb_message = isset($fbjson->message) ? $fbjson->message : '';
						$fb_message_tags = isset($fbjson->message_tags) ? $fbjson->message_tags : [];
						$fb_type 	= isset($fbjson->type) ? $fbjson->type : 'status';
						$fb_status_type = isset($fbjson->status_type) ? $fbjson->status_type : 'status';
						$fb_created_time = isset($fbjson->created_time) ? $fbjson->created_time : '';
						$updated_time 	 = isset($fbjson->updated_time) ? $fbjson->updated_time : '';
						$fb_picture = isset($fbjson->picture) ? $fbjson->picture : '';
						$fb_full_picture = isset($fbjson->full_picture) ? $fbjson->full_picture : '';
						$fb_permalink_url = isset($fbjson->permalink_url) ? $fbjson->permalink_url : '';

						// from user
						$fb_from_user = isset($fbjson->from) ? $fbjson->from : [];
						$fb_to_user = isset($fbjson->to->data) ? $fbjson->to->data : [];
						// like
						$fb_likes = isset($fbjson->likes->summary->total_count) ? $fbjson->likes->summary->total_count : 0;
						// reactions
						$fb_reactions = isset($fbjson->reactions->summary->total_count) ? $fbjson->reactions->summary->total_count : 0;

						$convert_reaction = $this->unit_converter($fb_reactions);
						// comments
						$fb_comments = isset($fbjson->comments->summary->total_count) ? $fbjson->comments->summary->total_count : 0;
						$convert_comment = $this->unit_converter($fb_comments);
						// shares
						$fb_shares = isset($fbjson->shares->count) ? $fbjson->shares->count : 0;
						$convert_shares = $this->unit_converter($fb_shares);

						// link share data
						$fb_link = isset($fbjson->link) ? $fbjson->link : '';
						$fb_name = isset($fbjson->name) ? $fbjson->name : '';
						$fb_caption = isset($fbjson->caption) ? $fbjson->caption : '';
						$fb_description = isset($fbjson->description) ? $fbjson->description : '';
						$fb_icon = isset($fbjson->icon) ? $fbjson->icon : '';

						$userName = '';
						$profileName = '';
						if(isset($fbjson->from)){
							$userName = $fb_from_user->username;
							$profileName = $fb_from_user->name;
							$profileImage = $fb_from_user->picture->data->url;
						}
						$pic_created_time = date("F j, Y", strtotime($fb_created_time . " +1 days"));

						$photos_class = '';
						if ($ekit_facebook_feed_style_choose == 'photos') {
							$photos_class = 'ekit_fb_photo_gallery';
						}

				?>
						<?php if($ekit_facebook_feed_style_choose == 'classic') : ?>
						<div class="ekit-single-fb-feed-holder <?php echo esc_attr($columnFeed); ?>">
							<div class="ekit-single-fb-feed">
							<?php if($ekit_facebook_feed_show_author == 'yes'):?>
								<!--Start Profile header -->
								<div class="ekit-fb-feed-header">
									<div class="ekit-fb-feed-profile-thumb">
										<?php if( in_array($ekit_facebook_feed_author_type, ['both', 'only-profile']) ):?>
											<a href="https://www.facebook.com/<?php echo esc_html($userName);?>" target="_blank" class="<?php echo esc_attr($ekit_facebook_feed_author_style); ?>">
												<img src="<?php echo esc_url($profileImage);?>" alt="<?php esc_html($userName);?>">
											</a>
										<?php endif; ?>
									</div>
									<div class="ekit-fb-feed-profile-info">
										<?php if( in_array($ekit_facebook_feed_author_type, ['both', 'only-name']) ): ?>
										<a href="https://www.facebook.com/<?php echo esc_html($userName);?>" target="_blank" class="user-name">
											<?php echo esc_html($profileName);?>
										</a>
										<?php endif;
										if( $ekit_facebook_feed_show_post_date == 'yes' ):
										?>
										<span class="ekit-fb-post-publish-date"><?php echo esc_attr($pic_created_time) ;?></span>
										<?php endif; ?>
									</div>
								</div>
								<!--End Profile header -->
							<?php endif;?>
							<!--Start Body -->
							<p class="ekit-fb-feed-status">
								<?php echo \ElementsKit\Utils::kses($this->str_check($fb_message));?>
							</p>
							<!--End Body -->
							<?php if(!empty($fb_full_picture)): ?>
							<!--Start Media -->

							<div class="ekit-fb-feed-media">
								<?php if($fb_type == 'link'){?>
									<a href="<?php echo esc_url($fb_link);?>" class="ekit-fb-feed-link-type" target="_blank">
										<img class="ekit_fb_photo" src="<?php echo esc_url($fb_full_picture);?>" alt="<?php echo esc_html($fb_name);?>" />
										<div class="ekit-fb-link-type-footer">
											<p class="ekit-fb-source-name"> <?php echo esc_html($fb_caption);?> </p>
											<p class="ekit-fb-caption-name">
											<?php echo esc_html($fb_name);?>
											</p>
											<p class="ekit-fb-caption"><?php echo wp_trim_words($fb_description, 10, '...'); ?></p>
										</div>
									</a>
								<?php } elseif($fb_type == 'video') { ?>
									<a href="<?php echo esc_url($fb_permalink_url);?>" class="ekit-fb-video-post" target="_blank">
										<img class="ekit_fb_photo" src="<?php echo esc_url($fb_full_picture);?>" alt="<?php echo esc_html($fb_name);?>" />
										<div class="ekit-fb-hover-content">
											<div class="ekit-fb-video-play-button">
												<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M354.2 247.4L219.1 155c-4.2-3.1-15.4-3.1-16.3 8.6v184.8c1 11.7 12.4 11.9 16.3 8.6l135.1-92.4c3.5-2.1 8.3-10.7 0-17.2zm-130.5 81.3V183.3L329.8 256l-106.1 72.7z"/><path d="M256 11C120.9 11 11 120.9 11 256s109.9 245 245 245 245-109.9 245-245S391.1 11 256 11zm0 469.1C132.4 480.1 31.9 379.6 31.9 256S132.4 31.9 256 31.9 480.1 132.4 480.1 256 379.6 480.1 256 480.1z"/></svg>
											</div>
										</div>
									</a>
								<?php } else {?>
										<a href="<?php echo esc_url($fb_permalink_url);?>" target="_blank">
											<img class="ekit_fb_photo" src="<?php echo esc_url($fb_full_picture);?>" alt="<?php echo esc_html($fb_name);?>" />
										</a>
								<?php }?>
							</div>
							<!--End Media -->
							<?php
							endif;
							if($ekit_facebook_feed_show_fotter == 'yes'):
							?>
							<div class="ekit-fb-fotter-section">
								<div class="ekit-fb-reaction-left">
									<a href="<?php echo esc_url($fb_permalink_url);?>" target="_blank" title="Like" class="ekit-facebook-like"> <strong><?php echo esc_html($convert_reaction);?></strong> </a>
								</div>
								<div class="ekit-fb-reaction-right">
									<a href="<?php echo esc_url($fb_permalink_url);?>" target="_blank" title="Comments" class="ekit-facebook-comments">
										<i class="icon icon-comments"></i>
										<strong><?php echo esc_html($convert_comment);?> <?php esc_html_e('Comments', 'elementskit');?></strong>
									</a>
									<a href="<?php echo esc_url('https://www.facebook.com/sharer/sharer.php?u=' . $fb_permalink_url);?>&display=popup&ref=plugin&src=post" title="Share" target="_blank" class="ekit-facebook-retweet">
										<i class="icon icon-share"></i>
										<strong><?php echo esc_html($convert_shares);?> <?php esc_html_e('Shares', 'elementskit');?></strong>
									</a>
								</div>
							</div>
							<?php
							endif;
							if($ekit_facebook_feed_show_comments == 'yes'):
							?>
								<div class="comments-show">
									<?php if(isset($fb_comments->data) && sizeof($fb_comments->data) > 0):
										foreach($fb_comments->data AS $commentsData):
									?>

									<?php endforeach;
										endif;?>
								</div>
							<?php endif;?>

							</div>
						</div>
						<?php else : ?>
						<?php if($fb_type == 'photo') : ?>
						<div class="ekit-single-fb-feed-holder <?php echo esc_attr($columnFeed); ?>">
							<div class="ekit-single-fb-feed <?php echo \ElementsKit\Utils::render($photos_class); ?>">
								<div class="ekit-fb-feed-media">
									<a class="ekit_fb_photo_link" href="<?php echo esc_url($fb_permalink_url);?>" target="_blank">
										<img class="ekit_fb_photo" src="<?php echo esc_url($fb_full_picture);?>" alt="<?php echo esc_html($fb_name);?>" />
									</a>
								</div>
								<!--End Media -->
							</div>
						</div>
						<?php endif; ?>
						<?php endif; ?>
				<?php
					endforeach;
				endif;
				?>
				</div>
			</div>
		   <?php

	}

	protected function _content_template() { }

}