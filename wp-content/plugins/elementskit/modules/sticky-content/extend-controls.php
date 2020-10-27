<?php

namespace Elementor;

class ElementsKit_Extend_Sticky{

    public function __construct() {
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ], 6 );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ], 6 );
	}

	public function register_controls( Controls_Stack $element ) {
		$element->start_controls_section(
			'section_scroll_effect',
			[
				'label' => esc_html__( 'ElementsKit Sticky', 'elementskit' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'ekit_sticky',
			[
				'label' => esc_html__( 'Sticky', 'elementskit' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'elementskit' ),
					'top' => esc_html__( 'Top', 'elementskit' ),
					'bottom' => esc_html__( 'Bottom', 'elementskit' ),
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'ekit_sticky_on',
			[
				'label' => esc_html__( 'Sticky On', 'elementskit' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => 'true',
				'default' => [ 'desktop', 'tablet', 'mobile' ],
				'options' => [
					'desktop' => esc_html__( 'Desktop', 'elementskit' ),
					'tablet' => esc_html__( 'Tablet', 'elementskit' ),
					'mobile' => esc_html__( 'Mobile', 'elementskit' ),
				],
				'condition' => [
					'ekit_sticky!' => '',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'ekit_sticky_offset',
			[
				'label' => esc_html__( 'Sticky Offset', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'required' => true,
				'condition' => [
					'ekit_sticky!' => '',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'ekit_sticky_effect_offset',
			[
				'label' => esc_html__( 'Add "ekit-sticky--effects" Class Offset', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'required' => true,
				'condition' => [
					'ekit_sticky!' => '',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$element->end_controls_section();
	}
}