<?php

add_action('admin_menu', function () {
    add_menu_page(
        'General Service App',
        'General Service App',
        'manage_options',
        GENERAL_SERVICE_APP_SLUG,
        function () {
            $options = get_option(GENERAL_SERVICE_APP_OPTIONS_KEY, [
                'entity_id' => '',
                'stories' => [],
                'last_updated' => '',
            ]);
            $new = empty($options['entity_id']);
            ?>
        <div class="wrap">
            <h1>General Service App</h1>
            <p>The General Service App plugin lets you embed a feed of entity stories using the shortcodes:</p>
            <ul>
                <?php foreach (GENERAL_SERVICE_APP_LANGUAGES as $language) { ?>
                    <li><code>[general_service_app language="<?php echo esc_html($language); ?>"]</code></li>
                <?php } ?>
            </ul>
            <?php if ($new) { ?>
                <p>To get started, please select an entity from the dropdown below:</p>
            <?php } else {
                    $time_diff = human_time_diff(strtotime($options['last_updated']), current_time('timestamp'));
                    ?>
                <p>
                    Your feed was last updated
                    <code><?php echo $time_diff ?></code> ago and contains
                    <code><?php echo count($options['stories']); ?></code> stories.
                </p>
                <p>Your selected entity is:</p>
            <?php } ?>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')) ?>" hidden>
                <?php wp_nonce_field(GENERAL_SERVICE_APP_NONCE) ?>
                <select name="entity_id">
                    <?php if ($new) { ?>
                        <option value=""></option>
                    <?php } ?>
                </select>
                <input type="hidden" name="action" value="<?php echo esc_attr(GENERAL_SERVICE_APP_SLUG) ?>">
                <input type="submit" value="<?php echo $new ? 'Save' : 'Refresh' ?>" class="button button-primary"
                    style="margin:0 0 0 6px;">
            </form>
            <script>
                (() => {
                    const select = document.querySelector('select[name="entity_id"]');
                    const form = select.closest('form');
                    fetch("<?php echo esc_url(GENERAL_SERVICE_APP_URL . '/storage/map.json'); ?>")
                        .then(response => response.json())
                        .then(entities => {
                            entities.forEach(({ id, area, name, districts }) => {
                                const option = document.createElement('option');
                                option.value = id;
                                option.textContent = `Area ${area}: ${name}`;
                                if (id === <?php echo intval($options['entity_id']) ?>) {
                                    option.selected = true;
                                }
                                select.appendChild(option);
                                districts.forEach(({ id, district, name }) => {
                                    const option = document.createElement('option');
                                    option.value = id;
                                    option.textContent = ` - District ${district}${name ? `: ${name}` : ''}`;
                                    if (id === <?php echo intval($options['entity_id']) ?>) {
                                        option.selected = true;
                                    }
                                    select.appendChild(option);
                                });
                            });
                        }).then(() => {
                            form.hidden = false;
                        });
                })();
            </script>

        </div>
        <?php
        },
        'dashicons-admin-generic',
        100
    );
});

