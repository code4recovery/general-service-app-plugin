<?php
/**
 * Plugin Name: General Service App
 * Description: Displays a news feed from the General Service App.
 * Author: General Service App
 * Author URI: https://generalservice.app
 * Version: 1.1.2
 */

const GENERAL_SERVICE_APP_LANGUAGES = ['en', 'es', 'fr'];
const GENERAL_SERVICE_APP_NONCE = 'general_service_app_nonce';
const GENERAL_SERVICE_APP_OPTIONS_KEY = 'general_service_app_options';
const GENERAL_SERVICE_APP_REFRESH = 'general_service_app_refresh';
const GENERAL_SERVICE_APP_SHORTCODE = 'general_service_app';
const GENERAL_SERVICE_APP_SLUG = 'general-service-app-plugin';
const GENERAL_SERVICE_APP_URL = 'https://generalservice.app';
const GENERAL_SERVICE_APP_VERSION = '1.1.2';

require_once plugin_dir_path(__FILE__) . 'functions/refresh.php';
require_once plugin_dir_path(__FILE__) . 'functions/save.php';
require_once plugin_dir_path(__FILE__) . 'functions/settings.php';
require_once plugin_dir_path(__FILE__) . 'functions/shortcode.php';

register_activation_hook(__FILE__, function () {
    if (!wp_next_scheduled(GENERAL_SERVICE_APP_REFRESH)) {
        wp_schedule_event(time(), 'hourly', GENERAL_SERVICE_APP_REFRESH);
    }
});

register_deactivation_hook(
    __FILE__,
    function () {
        wp_clear_scheduled_hook(GENERAL_SERVICE_APP_REFRESH);
    }
);

