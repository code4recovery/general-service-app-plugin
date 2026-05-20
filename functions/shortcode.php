<?php

add_action('wp_enqueue_scripts', function () {
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, GENERAL_SERVICE_APP_SHORTCODE)) {
        wp_enqueue_style(GENERAL_SERVICE_APP_SLUG, plugins_url('general-service-app.css', dirname(__FILE__)), [], GENERAL_SERVICE_APP_VERSION);
    }
});

add_shortcode(GENERAL_SERVICE_APP_SHORTCODE, function ($atts) {

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
            implode('', array_map(fn($paragraph) => '<p>' . $paragraph . '</p>', array_filter(explode("\n", $story['description']))))
            . general_service_app_buttons($story['buttons']) . '
            </article>',
            array_filter($option['stories'], fn($story) => $story['language'] === $language && $story['type'] === $key)
        );

        if (count($stories)) {
            $output .= "<h2>$type</h2>" . implode('', $stories);
        }
    }

    return '<section id="general-service-app">' . $output . '</section>';
});

function general_service_app_buttons($buttons)
{
    if (!is_array($buttons) || !count($buttons)) {
        return '';
    }

    $buttons = array_map(function ($button) {
        if ($button['type'] === 'calendar') {
            $format = 'Ymd\THis\Z';

            $dates = array_map(
                function ($date) use ($button, $format) {
                    $date = new DateTime($date, new DateTimeZone($button['timezone']));
                    return $date->setTimezone(new DateTimeZone('UTC'))->format($format);
                },
                [$button['start'], $button['end']]
            );

            $params = [
                'action' => 'TEMPLATE',
                'dates' => implode('/', $dates),
                'text' => $button['event_title'],
                'details' => implode("\n", array_filter([$button['conference_url'], $button['notes']])),
                'location' => $button['formatted_address'],
                'trp' => false,
                'ctz' => $button['timezone'],
                'sprop' => 'website:' . get_home_url(),
            ];
            $button['link'] = 'https://www.google.com/calendar/render?' . http_build_query($params);
        }
        return '<li><a href="' . $button['link'] . '" target="_blank" rel="noopener">' . $button['title'] . '</a></li>';
    }, $buttons);

    return '<ul>' . implode('', $buttons) . '</ul>';
}
