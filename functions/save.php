<?php

add_action(
    'admin_post_' . GENERAL_SERVICE_APP_SLUG,
    function () {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized user');
        }

        check_admin_referer(GENERAL_SERVICE_APP_NONCE);

        $entity_id = sanitize_text_field($_POST['entity_id'] ?? '');

        general_service_app_refresh($entity_id);

        wp_redirect(admin_url('admin.php?page=' . GENERAL_SERVICE_APP_SLUG));
    }
);