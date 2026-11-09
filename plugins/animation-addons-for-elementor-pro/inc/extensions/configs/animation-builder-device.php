<?php

/**
 * Animation Builder Device Configurations
 */

defined('ABSPATH') || die();

return [
  [
    'key' => 'desktop',
    'title' => 'Desktop',
    'viewWidth' => '1920px',
    'mediaQuery' => '(min-width: 1441px)',
  ],
  [
    'key' => 'laptop',
    'title' => 'Laptop',
    'viewWidth' => '1440px',
    'mediaQuery' => '(min-width: 1200px) and (max-width: 1440px)',
  ],
  [
    'key' => 'tab_land',
    'title' => 'Tablet Landscape',
    'viewWidth' => '1024px',
    'mediaQuery' => '(min-width: 1024px) and (max-width: 1199px)',
  ],
  [
    'key' => 'tab',
    'title' => 'Tablet',
    'viewWidth' => '768px',
    'mediaQuery' => '(min-width: 768px) and (max-width: 1023px)',
  ],
  [
    'key' => 'mobile',
    'title' => 'Mobile',
    'viewWidth' => '375px',
    'mediaQuery' => '(max-width: 767px)',
  ],
];
