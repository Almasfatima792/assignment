<?php
namespace ElementsKit;

class Elementskit_Widget_table_Handler extends Core\Handler_Widget{

    static function get_name() {
        return 'elementskit-table';
    }

    static function get_title() {
        return esc_html__( 'Table', 'elementskit' );
    }

    static function get_icon() {
        return 'eicon-price-table ekit-widget-icon ';
    }

    static function get_categories() {
        return [ 'elementskit' ];
    }

    static function get_dir() {
        return \ElementsKit::widget_dir() . 'table/';
    }

    static function get_url() {
        return \ElementsKit::widget_url() . 'table/';
    }
}