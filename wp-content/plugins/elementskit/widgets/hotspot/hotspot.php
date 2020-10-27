<?php
namespace Elementor;

use \ElementsKit\Elementskit_Widget_hotspot_Handler as Handler;
use \ElementsKit\Modules\Controls\Controls_Manager as ElementsKit_Controls_Manager;

if (! defined( 'ABSPATH' ) ) exit;

class Elementskit_Widget_hotspot extends Widget_Base {

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
        $this->start_controls_section(
            'section_tab', [
                'label' =>esc_html__( 'Hotspot', 'elementskit' ),
            ]
        );

        $this->add_control(
            'ekit_hotspot_background_image', [
                'label'		 => esc_html__( 'Background Map Image', 'elementskit' ),
                'type'		 => Controls_Manager::MEDIA,
                'default'	 => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        // Hotspot point

        $hotspot_point = new \Elementor\Repeater();

        $hotspot_point->add_control(
            'ekit_hotspot_title', [
                'label' => esc_html__( 'Title', 'elementskit' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'South Carolina Data Center' , 'elementskit' ),
                'label_block' => true,
            ]
        );

        $hotspot_point->add_control(
            'ekit_hotspot_address',
            [
                'label' => esc_html__( 'Address', 'elementskit' ),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 10,
                'placeholder' => esc_html__( 'Address', 'elementskit' ),
            ]
        );

        $hotspot_point->add_control(
            'ekit_hotspot_logo',
            [
                'label' => esc_html__( 'Choose Image', 'elementskit' ),
                'type' => Controls_Manager::MEDIA,
            ]
        );


        $hotspot_point->add_responsive_control(
            'ekit_hotspot_point_left_pos',
            [
                'label'		 => esc_html__( 'Left', 'elementskit' ),
                'type'		 => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'		 => [
                    'px' => [
                        'min'	 => 0,
                        'max'	 => 100,
                        'step'	 => 1,
                    ],
                    '%'	 => [
                        'min'	 => 0,
                        'max'	 => 100,
                        'step'	 => 1,
                    ],
                ],
                'selectors'	 => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'ekit_hotspot_point_show_right_bottom_pos!' => 'yes'
                ]
            ]
        );
        $hotspot_point->add_responsive_control(
            'ekit_hotspot_point_top_pos',
            [
                'label'		 => esc_html__( 'Top', 'elementskit' ),
                'type'		 => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'		 => [
                    'px' => [
                        'min'	 => 0,
                        'max'	 => 100,
                        'step'	 => 1,
                    ],
                    '%'	 => [
                        'min'	 => 0,
                        'max'	 => 100,
                        'step'	 => 1,
                    ],
                ],
                'selectors'	 => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'ekit_hotspot_point_show_right_bottom_pos!' => 'yes'
                ]
            ]
        );

        $hotspot_point->add_control(
			'ekit_hotspot_point_show_right_bottom_pos',
			[
				'label' => esc_html__( 'Right and bottom?', 'elementskit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementskit' ),
				'label_off' => esc_html__( 'Hide', 'elementskit' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

        $hotspot_point->add_responsive_control(
            'ekit_hotspot_point_right_pos',
            [
                'label'		 => esc_html__( 'Right', 'elementskit' ),
                'type'		 => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'		 => [
                    'px' => [
                        'min'	 => 0,
                        'max'	 => 100,
                        'step'	 => 1,
                    ],
                    '%'	 => [
                        'min'	 => 0,
                        'max'	 => 100,
                        'step'	 => 1,
                    ],
                ],
                'selectors'	 => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'ekit_hotspot_point_show_right_bottom_pos' => 'yes'
                ]
            ]
        );
        $hotspot_point->add_responsive_control(
            'ekit_hotspot_point_bottom_pos',
            [
                'label'		 => esc_html__( 'Bottom', 'elementskit' ),
                'type'		 => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'		 => [
                    'px' => [
                        'min'	 => 0,
                        'max'	 => 100,
                        'step'	 => 1,
                    ],
                    '%'	 => [
                        'min'	 => 0,
                        'max'	 => 100,
                        'step'	 => 1,
                    ],
                ],
                'selectors'	 => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'ekit_hotspot_point_show_right_bottom_pos' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'ekit_location_repeater',
            [
                'label' => esc_html__( 'Repeater List', 'elementskit' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $hotspot_point->get_controls(),
                'default' => [
                    [
                        // repeater 1
                        'ekit_hotspot_title' => esc_html__( 'South Carolina Data Center', 'elementskit' ),
                        'ekit_hotspot_address' => esc_html__( 'Item content. Click the edit button to change this text.', 'elementskit' ),
                        'ekit_hotspot_point_left_pos' => [
                                                            'unit'	 => '%',
                                                            'size'	 => 18,
                                                         ],
                        'ekit_hotspot_point_top_pos' => [
                                                            'unit'	 => '%',
                                                            'size'	 => 29,
                                                       ],
                    ],
                    [
                        // repeater 2
                        'ekit_hotspot_title' => esc_html__( 'South Carolina Data Center', 'elementskit' ),
                        'ekit_hotspot_address' => esc_html__( 'Item content. Click the edit button to change this text.', 'elementskit' ),
                        'ekit_hotspot_point_left_pos' => [
                            'unit'	 => '%',
                            'size'	 => 49,
                        ],
                        'ekit_hotspot_point_top_pos' => [
                            'unit'	 => '%',
                            'size'	 => 30,
                        ],
                    ],
                    [
                       // repeter 3
                        'ekit_hotspot_title' => esc_html__( 'South Carolina Data Center', 'elementskit' ),
                        'ekit_hotspot_address' => esc_html__( 'Item content. Click the edit button to change this text.', 'elementskit' ),
                        'ekit_hotspot_point_right_pos' => [
                            'unit'	 => '%',
                            'size'	 => 28,
                        ],
                        'ekit_hotspot_point_top_pos' => [
                            'unit'	 => '%',
                            'size'	 => 7,
                        ],
                    ],
                    [
                        // repeter 4
                        'ekit_hotspot_title' => esc_html__( 'South Carolina Data Center', 'elementskit' ),
                        'ekit_hotspot_address' => esc_html__( 'Item content. Click the edit button to change this text.', 'elementskit' ),
                        'ekit_hotspot_point_left_pos' => [
                            'unit'	 => '%',
                            'size'	 => 36,
                        ],
                        'ekit_hotspot_point_top_pos' => [
                            'unit'	 => '%',
                            'size'	 => 4,
                        ],
                    ],
                    [
                        // repeter 5
                        'ekit_hotspot_title' => esc_html__( 'South Carolina Data Center', 'elementskit' ),
                        'ekit_hotspot_address' => esc_html__( 'Item content. Click the edit button to change this text.', 'elementskit' ),
                        'ekit_hotspot_point_left_pos' => [
                            'unit'	 => '%',
                            'size'	 => 39,
                        ],
                        'ekit_hotspot_point_bottom_pos' => [
                            'unit'	 => '%',
                            'size'	 => 29,
                        ],
                    ],
                    [
                        // repeter 6
                        'ekit_hotspot_title' => esc_html__( 'South Carolina Data Center', 'elementskit' ),
                        'ekit_hotspot_address' => esc_html__( 'Item content. Click the edit button to change this text.', 'elementskit' ),
                        'ekit_hotspot_point_right_pos' => [
                            'unit'	 => '%',
                            'size'	 => 9,
                        ],
                        'ekit_hotspot_point_top_pos' => [
                            'unit'	 => '%',
                            'size'	 => 17,
                        ],
                    ],
                    [
                        // repeter 7
                        'ekit_hotspot_title' => esc_html__( 'South Carolina Data Center', 'elementskit' ),
                        'ekit_hotspot_address' => esc_html__( 'Item content. Click the edit button to change this text.', 'elementskit' ),
                        'ekit_hotspot_point_right_pos' => [
                            'unit'	 => '%',
                            'size'	 => 28,
                        ],
                        'ekit_hotspot_point_top_pos' => [
                            'unit'	 => '%',
                            'size'	 => 50,
                        ],
                    ],
                    [
                        // repeter 8
                        'ekit_hotspot_title' => esc_html__( 'South Carolina Data Center', 'elementskit' ),
                        'ekit_hotspot_address' => esc_html__( 'Item content. Click the edit button to change this text.', 'elementskit' ),
                        'ekit_hotspot_point_right_pos' => [
                            'unit'	 => '%',
                            'size'	 => 88,
                        ],
                        'ekit_hotspot_point_bottom_pos' => [
                            'unit'	 => '%',
                            'size'	 => 13,
                        ],
                    ],

                ],
                'title_field' => '{{{ ekit_hotspot_title }}}',
            ]
        );


        $this->end_controls_section();

        /**
         *
         * Content Style
         *
         */
        $this->start_controls_section(
            'ekit_hotspot_content_section', [
                'label'	 => esc_html__( 'Content', 'elementskit' ),
                'tab'	 => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'ekit_hotspot_color', [
                'label'		 => esc_html__( 'Text Color', 'elementskit' ),
                'type'		 => Controls_Manager::COLOR,
                'default'	 => '#000',
                'selectors'	 => [
                    '{{WRAPPER}} .ekit-location-des'  => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'ekit_hotspot_content_bg',
                'label' => esc_html__( 'Background', 'elementskit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .media',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ekit_hotspot_indicatior_section', [
                'label'	 => esc_html__( 'Indicatior', 'elementskit' ),
                'tab'	 => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ekit_hotspot_indicator_background', [
                'label'		 => esc_html__( 'Background Color', 'elementskit' ),
                'type'		 => Controls_Manager::COLOR,
                'default'	 => '',
                'selectors'	 => [
                    '{{WRAPPER}} .ekit-location_indicator'  => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'ekit_hotspot_indicatorpoint_background', [
                'label'		 => esc_html__( 'Point Color', 'elementskit' ),
                'type'		 => Controls_Manager::COLOR,
                'default'	 => '',
                'selectors'	 => [
                    '{{WRAPPER}} .ekit-location_indicator:after'  => 'background: {{VALUE}};',
                ],
            ]
        );
        // $this->add_group_control(
		// 	Group_Control_Box_Shadow::get_type(),
		// 	[
		// 		'name' => 'box_shadow',
		// 		'label' => esc_html__( 'Glow Color One', 'elementskit' ),
		// 		'selector' => '{{WRAPPER}} .ekit-location_indicator',
		// 	]
        // );

        $this->add_control(
            'ekit_hotspot_indicatorpoint_color', [
                'label'		 => esc_html__( 'Point Color', 'elementskit' ),
                'type'		 => Controls_Manager::COLOR,
                'default'	 => '',
                'selectors'	 => [
                    '{{WRAPPER}} .ekit-location_indicator'  => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render( ) {
        echo '<div class="ekit-wid-con" >';
        $this->render_raw();
        echo '</div>';
    }

    protected function render_raw( ) {

        $settings = $this->get_settings_for_display();

        extract($settings);

      ?>
        <div class="ekit-location-groups">
            <div class="ekit-map-image text-center">
                <img src="<?php echo esc_url($ekit_hotspot_background_image['url']); ?>" alt="map">
            </div>

            <div class="ekit-location-wraper clearfix">

                <?php

                if($ekit_location_repeater != '') :

                foreach ($ekit_location_repeater as $key => $location) :  ?>

                <div class="ekit-location elementor-repeater-item-<?php echo esc_attr( $location[ '_id' ] ); ?>">
                    <div class="media ekit-location_inner">
                        <div class="ekit_hotspot_arrow"></div>
                      <?php

                           if($location['ekit_hotspot_logo']['id'] !='') :
                           $image_id = $location['ekit_hotspot_logo']['id'];
                           $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);

                        ?>
                           <div class="ekit_hotspot_image">
                                <img src='<?php echo esc_url($location['ekit_hotspot_logo']['url']); ?>' class='mr-3' alt='<?php echo esc_attr($image_alt); ?>'>
                           </div>
                        <?php endif;

                         if($location['ekit_hotspot_address']) :
                        ?>
                            <div class='media-body'>
                                <?php if ($location['ekit_hotspot_title'] != '') : ?>
                                <h3 class="ekit-hotspot-title"><?php echo esc_html($location['ekit_hotspot_title'], 'elementskit')?></h3>
                                <?php endif; ?>
                                <p class='ekit-location-des'><?php echo \ElementsKit\Utils::kses($location['ekit_hotspot_address']); ?></p>
                            </div>
                        <?php endif; ?>

                    </div>
                    <div class="ekit-location_indicator">
                        <div class="ekit_hotspot_pulse_1"></div>
                        <div class="ekit_hotspot_pulse_2"></div>
                    </div>
                </div>

             <?php endforeach; endif; ?>

            </div><!-- .location-wraper END -->
        </div>
    <?php }
    protected function _content_template() { }
}