<?php
/**
 * Plugin Name: General Service App
 * Description: Integrates with the A.A. General Service App.
 * Version: 1.0
 * Author: General Service App
 * Author URI: https://generalservice.app
 */

add_shortcode('general_service_app', function ($atts) {

    $atts = shortcode_atts([
        'language' => 'en',
    ], $atts);

    wp_enqueue_script('general-service-app', plugins_url('general-service-app.js', __FILE__));
    wp_enqueue_style('general-service-app', plugins_url('general-service-app.css', __FILE__));

    return '<section id="general-service-app" data-language="' . esc_attr($atts['language']) . '"></section>';
});