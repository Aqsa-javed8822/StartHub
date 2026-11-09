<?php

namespace WCFAddonsPros\compatible;

defined( 'ABSPATH' ) || exit;

function wcf_load_icon_file() {

    // current theme slug (already lowercase, but we normalize anyway)
    $theme      = wp_get_theme();
    $theme_slug = strtolower( $theme->get_stylesheet() );

    // allowed themes (normalized to lowercase)
    $allowed_themes = array_map(
        'strtolower',
        [
            'Merko',
            'Arolax',
            'Axtra',
            'Bilder',
            'Binox',
            'Consult',
            'Hello Animation',
            'Helo',
            'Newsprint',
            'Sassly',
            'TheAI',
            'Vendy',
        ]
    );

    // if not our theme, load the icon styles
    if ( ! in_array( $theme_slug, $allowed_themes, true ) ) {

        wp_enqueue_style(
            'wcf-icon',
            WCF_ADDONS_PRO_URL . 'assets/css/wcf-icon.min.css',
            [],
            '1.0.0'
        );
    }
}

add_action(
    'wp_enqueue_scripts',
    __NAMESPACE__ . '\wcf_load_icon_file'
);
