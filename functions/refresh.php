<?php
function general_service_app_refresh($entity_id = null)
{
    if (!$entity_id) {
        $options = get_option(GENERAL_SERVICE_APP_OPTIONS_KEY);
        if (empty($options['entity_id'])) {
            return;
        }
        $entity_id = $options['entity_id'];
    }

    $url = GENERAL_SERVICE_APP_URL . "/api/entities/$entity_id/stories";

    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        wp_die('Failed to fetch data from General Service App');
    }

    $body = wp_remote_retrieve_body($response);

    $stories = json_decode($body, true);

    $last_updated = current_time('Y-m-d H:i:s');

    update_option(GENERAL_SERVICE_APP_OPTIONS_KEY, compact('entity_id', 'stories', 'last_updated'));
}

add_action(GENERAL_SERVICE_APP_REFRESH, GENERAL_SERVICE_APP_REFRESH);
