<?php
/**
 * Plugin Name: General Service App
 * Description: Integrates with the A.A. General Service App.
 * Version: 1.0.3
 * Author: General Service App
 * Author URI: https://generalservice.app
 */

$version = '1.0.3';

add_shortcode('general_service_app', function ($atts) use ($version) {

    $atts = shortcode_atts([
        'language' => 'en',
    ], $atts);

    wp_enqueue_script('general-service-app', plugins_url('general-service-app.js', __FILE__), [], $version);
    wp_enqueue_style('general-service-app', plugins_url('general-service-app.css', __FILE__), [], $version);

    return '<section id="general-service-app" data-language="' . esc_attr($atts['language']) . '"></section>';
});