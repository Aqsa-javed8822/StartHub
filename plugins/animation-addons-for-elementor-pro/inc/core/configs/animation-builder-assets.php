<?php

/**
 * Animation Builder Device Configurations
 */

defined('ABSPATH') || die();


return [
    'js' => [
        'wcf-scroll-video-animation' => [
            'src' => WCF_ADDONS_PRO_URL . '/assets/build/modules/animation-builder/frontend/presets/scrollVideoFrame.js',
            'editorSrc' => WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/register/preset/rScrollVideoFrame.js',
            'deps' => ['gsap', 'ScrollTrigger'],
            'editorDeps' => [
                'react',
                'react-dom',
                'wp-dom-ready',
                'wp-element',
                'wp-hooks'
            ],
            'version' => WCF_ADDONS_PRO_VERSION,
        ],
        'wcf-horizontal-scroll-animation' => [
            'src' => WCF_ADDONS_PRO_URL . '/assets/build/modules/animation-builder/frontend/presets/horizontalScrollAnim.js',
            'editorSrc' => WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/register/preset/rHorizontalScrollAnim.js',
            'deps' => ['gsap', 'ScrollTrigger'],
            'editorDeps' => [
                'react',
                'react-dom',
                'wp-dom-ready',
                'wp-element',
                'wp-hooks'
            ],
            'version' => WCF_ADDONS_PRO_VERSION,
        ],
        'wcf-cube-scroll-reveal-animation' => [
            'src' => WCF_ADDONS_PRO_URL . '/assets/build/modules/animation-builder/frontend/presets/cubeScrollRevealAnim.js',
            'editorSrc' => WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/register/preset/rCubeScrollRevealAnim.js',
            'deps' => ['gsap', 'ScrollTrigger'],
            'editorDeps' => [
                'react',
                'react-dom',
                'wp-dom-ready',
                'wp-element',
                'wp-hooks'
            ],
            'version' => WCF_ADDONS_PRO_VERSION,
        ],
        'wcf-image-reveal-animation' => [
            'src' =>  WCF_ADDONS_PRO_URL . '/assets/build/modules/animation-builder/frontend/presets/imageRevealAnim.js',
            'editorSrc' => WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/register/preset/rImageRevealAnim.js',
            'deps' => ['gsap', 'ScrollTrigger'],
            'editorDeps' => [
                'react',
                'react-dom',
                'wp-dom-ready',
                'wp-element',
                'wp-hooks'
            ],
            'version' => WCF_ADDONS_PRO_VERSION,
        ],
        'wcf-image-hover-reveal-animation' => [
            'src' =>  WCF_ADDONS_PRO_URL . '/assets/build/modules/animation-builder/frontend/presets/imageHoverRevealAnim.js',
            'editorSrc' => WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/register/preset/rImageHoverRevealAnim.js',
            'deps' => ['gsap', 'ScrollTrigger'],
            'editorDeps' => [
                'react',
                'react-dom',
                'wp-dom-ready',
                'wp-element',
                'wp-hooks'
            ],
            'version' => WCF_ADDONS_PRO_VERSION,
        ],
        'wcf-cursor-hover-reveal-animation' => [
            'src' =>  WCF_ADDONS_PRO_URL . '/assets/build/modules/animation-builder/frontend/presets/cursorHoverRevealAnim.js',
            'editorSrc' => WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/register/preset/rCursorHoverRevealAnim.js',
            'deps' => ['gsap', 'ScrollTrigger'],
            'editorDeps' => [
                'react',
                'react-dom',
                'wp-dom-ready',
                'wp-element',
                'wp-hooks'
            ],
            'version' => WCF_ADDONS_PRO_VERSION,
        ],
        'wcf-cursor-hover-move-animation' => [
            'src' =>  WCF_ADDONS_PRO_URL . '/assets/build/modules/animation-builder/frontend/presets/cursorHoverMoveAnim.js',
            'editorSrc' => WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/register/preset/rCursorHoverMoveAnim.js',
            'deps' => ['gsap', 'ScrollTrigger'],
            'editorDeps' => [
                'react',
                'react-dom',
                'wp-dom-ready',
                'wp-element',
                'wp-hooks'
            ],
            'version' => WCF_ADDONS_PRO_VERSION,
        ],
        'wcf-image-stretch-animation' => [
            'src' =>  WCF_ADDONS_PRO_URL . '/assets/build/modules/animation-builder/frontend/presets/imageStretchAnim.js',
            'editorSrc' => WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/register/preset/rImageStretchAnim.js',
            'deps' => ['gsap', 'ScrollTrigger'],
            'editorDeps' => [
                'react',
                'react-dom',
                'wp-dom-ready',
                'wp-element',
                'wp-hooks'
            ],
            'version' => WCF_ADDONS_PRO_VERSION,
        ],
        'wcf-image-scale-animation' => [
            'src' => WCF_ADDONS_PRO_URL . '/assets/build/modules/animation-builder/frontend/presets/imageScaleAnim.js',
            'editorSrc' => WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/register/preset/rImageScaleAnim.js',
            'deps' => ['gsap', 'ScrollTrigger'],
            'editorDeps' => [
                'react',
                'react-dom',
                'wp-dom-ready',
                'wp-element',
                'wp-hooks'
            ],
            'version' => WCF_ADDONS_PRO_VERSION,
        ],
        'wcf-text-split-animation' => [
            'src' => WCF_ADDONS_PRO_URL . '/assets/build/modules/animation-builder/frontend/presets/textSplitAnim.js',
            'editorSrc' => WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/register/preset/rTextSplitAnim.js',
            'deps' => ['gsap', 'ScrollTrigger'],
            'editorDeps' => [
                'react',
                'react-dom',
                'wp-dom-ready',
                'wp-element',
                'wp-hooks'
            ],
            'version' => WCF_ADDONS_PRO_VERSION,
        ],
        'wcf-text-rotate-animation' => [
            'src' => WCF_ADDONS_PRO_URL . '/assets/build/modules/animation-builder/frontend/presets/textRotateAnim.js',
            'editorSrc' => WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/register/preset/rTextRotateAnim.js',
            'deps' => ['gsap', 'ScrollTrigger'],
            'editorDeps' => [
                'react',
                'react-dom',
                'wp-dom-ready',
                'wp-element',
                'wp-hooks'
            ],
            'version' => WCF_ADDONS_PRO_VERSION,
        ],
        'wcf-text-scale-animation' => [
            'src' => WCF_ADDONS_PRO_URL . '/assets/build/modules/animation-builder/frontend/presets/textScaleAnim.js',
            'editorSrc' => WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/register/preset/rTextScaleAnim.js',
            'deps' => ['gsap', 'ScrollTrigger'],
            'editorDeps' => [
                'react',
                'react-dom',
                'wp-dom-ready',
                'wp-element',
                'wp-hooks'
            ],
            'version' => WCF_ADDONS_PRO_VERSION,
        ],
        'wcf-text-invert-animation' => [
            'src' => WCF_ADDONS_PRO_URL . '/assets/build/modules/animation-builder/frontend/presets/textInvertAnim.js',
            'editorSrc' => WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/register/preset/rTextInvertAnim.js',
            'deps' => ['gsap', 'ScrollTrigger'],
            'editorDeps' => [
                'react',
                'react-dom',
                'wp-dom-ready',
                'wp-element',
                'wp-hooks'
            ],
            'version' => WCF_ADDONS_PRO_VERSION,
        ],
        'wcf-text-spin-animation' => [
            'src' => WCF_ADDONS_PRO_URL . '/assets/build/modules/animation-builder/frontend/presets/textSpinAnim.js',
            'editorSrc' => WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/register/preset/rTextSpinAnim.js',
            'deps' => ['gsap', 'ScrollTrigger'],
            'editorDeps' => [
                'react',
                'react-dom',
                'wp-dom-ready',
                'wp-element',
                'wp-hooks'
            ],
            'version' => WCF_ADDONS_PRO_VERSION,
        ],
        'wcf-popup-media-animation' => [
            'src' => WCF_ADDONS_PRO_URL . '/assets/build/modules/animation-builder/frontend/presets/popupMediaAnim.js',
            'editorSrc' => WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/register/preset/rPopupMediaAnim.js',
            'deps' => ['gsap', 'ScrollTrigger'],
            'editorDeps' => [
                'react',
                'react-dom',
                'wp-dom-ready',
                'wp-element',
                'wp-hooks'
            ],
            'version' => WCF_ADDONS_PRO_VERSION,
        ],
    ]

];
