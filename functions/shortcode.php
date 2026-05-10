<?php

add_shortcode('general_service_app', function ($atts) {

    $atts = shortcode_atts([
        'language' => GENERAL_SERVICE_APP_LANGUAGES[0],
    ], $atts);

    $language = $atts['language'];

    $types = ['news' => 'News', 'business' => 'Business', 'resources' => 'Resources'];

    if ($language === 'es') {
        $types = ['news' => 'Noticias', 'business' => 'Negocios', 'resources' => 'Recursos'];
    } elseif ($language === 'fr') {
        $types = ['news' => 'Nouvelles', 'business' => 'Recursos', 'resources' => 'Ressources'];
    }

    $option = get_option(GENERAL_SERVICE_APP_OPTIONS_KEY, [
        'stories' => []
    ]);

    $output = '';

    foreach ($types as $key => $type) {

        $stories = array_map(
            fn($story) => '<article>
            <h3>' . $story['title'] . '</h3>' .
            implode('', array_map(fn($paragraph) => '<p>' . $paragraph . '</p>', explode("\n\n", $story['description'])))
            . (is_array($story['buttons']) && count($story['buttons']) ? '<ul>' .
                implode('', array_map(fn($button) => '<li><a href="' . $button['link'] . '" target="_blank" rel="noopener">' . $button['title'] . '</a></li>', $story['buttons'])) .
                '</ul>' : '') . '
            </article>',
            array_filter($option['stories'], fn($story) => $story['language'] === $language && $story['type'] === $key)
        );

        if (count($stories)) {
            $output .= "<h2>$type</h2>" . implode('', $stories);
        }
    }

    wp_enqueue_style(GENERAL_SERVICE_APP_SLUG, plugins_url('general-service-app.css', dirname(__FILE__)), [], GENERAL_SERVICE_APP_VERSION);

    return '<section id="general-service-app">' . $output . '</section>';
});
