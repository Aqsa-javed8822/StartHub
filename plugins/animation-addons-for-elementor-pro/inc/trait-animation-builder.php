<?php

namespace WCFAddonsPro\Inc;


if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait AAE_Animation_Builder_Trait
{
    /**
     * Get keys of all active elements in the animation builder data.
     *
     * @param array $data The decoded form data.
     * @return array Array of active element keys.
     */
    private function get_active_element_keys()
    {
        $data = get_option('aae_anim_builder_settings');
        $data = json_decode($data, true);
        $active_keys = [];
        if (is_array($data) && isset($data['elements'])) {
            foreach ($data['elements'] as $group) {

                if (isset($group['elements']) && is_array($group['elements'])) {
                    foreach ($group['elements'] as $key => $element) {
                        if (!empty($element['is_active'])) {
                            $active_keys[] = $key;
                        }
                    }
                }
            }
        }
        return $active_keys;
    }

    function getActivePresets(array $data, &$custom)
    {
        $presets = [];

        $iterator = function ($array) use (&$iterator, &$presets, &$custom) {
            foreach ($array as $value) {
                if (is_array($value)) {
                    // If it's an animation item
                    if (
                        isset($value['type'], $value['enable'], $value['preset']) &&
                        $value['type'] === 'preset' &&
                        (int)$value['enable'] === 1
                    ) {
                        $presets[] = $value['preset'];
                    }

                    if (
                        isset($value['type'], $value['enable']) &&
                        $value['type'] === 'custom' &&
                        (int)$value['enable'] === 1
                    ) {
                        $custom = true; // now modifies the referenced variable
                    }
                    // Recurse deeper
                    $iterator($value);
                }
            }
        };

        $iterator($data);

        // Remove duplicates and reindex array
        return array_values(array_unique($presets));
    }
}
