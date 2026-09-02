<?php
/**
 * ACF local field groups for the South City content model.
 *
 * @package SouthCity
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Add a global options page when ACF Pro is available.
 */
function south_city_register_acf_options_pages(): void
{
    if (! function_exists('acf_add_options_page')) {
        return;
    }

    acf_add_options_page([
        'page_title' => __('South City Settings', 'south-city'),
        'menu_title' => __('South City Settings', 'south-city'),
        'menu_slug'  => 'south-city-settings',
        'capability' => 'manage_options',
        'redirect'   => false,
        'position'   => 59,
        'icon_url'   => 'dashicons-admin-home',
    ]);
}
add_action('acf/init', 'south_city_register_acf_options_pages');

/**
 * Register local field groups that mirror the former Sanity schema.
 */
function south_city_register_acf_field_groups(): void
{
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    $icon_choices = [
        'license'   => __('License', 'south-city'),
        'document'  => __('Document', 'south-city'),
        'stamp'     => __('Stamp', 'south-city'),
        'calendar'  => __('Calendar', 'south-city'),
        'landcheck' => __('Land Check', 'south-city'),
        'river'     => __('River', 'south-city'),
        'security'  => __('Security', 'south-city'),
        'check'     => __('Check', 'south-city'),
        'school'    => __('School', 'south-city'),
        'mosque'    => __('Mosque', 'south-city'),
        'health'    => __('Health', 'south-city'),
        'shop'      => __('Shop', 'south-city'),
        'gym'       => __('Gym', 'south-city'),
        'coffee'    => __('Coffee', 'south-city'),
        'trail'     => __('Trail', 'south-city'),
        'park'      => __('Park', 'south-city'),
        'road'      => __('Road', 'south-city'),
        'utility'   => __('Utility', 'south-city'),
        'play'      => __('Play', 'south-city'),
    ];

    acf_add_local_field_group([
        'key' => 'group_south_city_global_settings',
        'title' => __('South City Global Settings', 'south-city'),
        'fields' => [
            [
                'key' => 'field_south_city_company_name_en',
                'label' => __('Company Legal Name - English', 'south-city'),
                'name' => 'company_name_en',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_company_name_bn',
                'label' => __('Company Legal Name - Bangla', 'south-city'),
                'name' => 'company_name_bn',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_phone',
                'label' => __('Sales Phone', 'south-city'),
                'name' => 'phone',
                'type' => 'text',
                'instructions' => __('Use tel format, for example +8801886175263.', 'south-city'),
            ],
            [
                'key' => 'field_south_city_phone_display',
                'label' => __('Phone Display Text', 'south-city'),
                'name' => 'phone_display',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_whatsapp',
                'label' => __('WhatsApp Number', 'south-city'),
                'name' => 'whatsapp',
                'type' => 'text',
                'instructions' => __('Use wa.me format with no plus sign or spaces, for example 8801886175263.', 'south-city'),
            ],
            [
                'key' => 'field_south_city_email',
                'label' => __('Public Email', 'south-city'),
                'name' => 'email',
                'type' => 'email',
            ],
            [
                'key' => 'field_south_city_address_en',
                'label' => __('Office Address - English', 'south-city'),
                'name' => 'address_en',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_address_bn',
                'label' => __('Office Address - Bangla', 'south-city'),
                'name' => 'address_bn',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_facebook',
                'label' => __('Facebook URL', 'south-city'),
                'name' => 'facebook_url',
                'type' => 'url',
            ],
            [
                'key' => 'field_south_city_youtube',
                'label' => __('YouTube URL', 'south-city'),
                'name' => 'youtube_url',
                'type' => 'url',
            ],
            [
                'key' => 'field_south_city_linkedin',
                'label' => __('LinkedIn URL', 'south-city'),
                'name' => 'linkedin_url',
                'type' => 'url',
            ],
            [
                'key' => 'field_south_city_brochure_pdf',
                'label' => __('Brochure PDF', 'south-city'),
                'name' => 'brochure_pdf',
                'type' => 'file',
                'return_format' => 'array',
                'library' => 'all',
                'mime_types' => 'pdf',
            ],
            [
                'key' => 'field_south_city_map_query',
                'label' => __('Google Maps Query', 'south-city'),
                'name' => 'map_query',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_whatsapp_message_en',
                'label' => __('WhatsApp Message - English', 'south-city'),
                'name' => 'whatsapp_message_en',
                'type' => 'textarea',
                'rows' => 3,
            ],
            [
                'key' => 'field_south_city_whatsapp_message_bn',
                'label' => __('WhatsApp Message - Bangla', 'south-city'),
                'name' => 'whatsapp_message_bn',
                'type' => 'textarea',
                'rows' => 3,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'south-city-settings',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ]);

    acf_add_local_field_group([
        'key' => 'group_south_city_homepage_sections',
        'title' => __('South City Homepage Sections', 'south-city'),
        'fields' => [
            [
                'key' => 'field_south_city_hero_headline_en',
                'label' => __('Hero Headline - English', 'south-city'),
                'name' => 'hero_headline_en',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_hero_headline_bn',
                'label' => __('Hero Headline - Bangla', 'south-city'),
                'name' => 'hero_headline_bn',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_hero_subline_en',
                'label' => __('Hero Subline - English', 'south-city'),
                'name' => 'hero_subline_en',
                'type' => 'textarea',
                'rows' => 3,
            ],
            [
                'key' => 'field_south_city_hero_subline_bn',
                'label' => __('Hero Subline - Bangla', 'south-city'),
                'name' => 'hero_subline_bn',
                'type' => 'textarea',
                'rows' => 3,
            ],
            [
                'key' => 'field_south_city_hero_image',
                'label' => __('Hero Background Image', 'south-city'),
                'name' => 'hero_image',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ],
            [
                'key' => 'field_south_city_hero_chips',
                'label' => __('Hero Chips', 'south-city'),
                'name' => 'hero_chips',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => __('Add Chip', 'south-city'),
                'sub_fields' => [
                    [
                        'key' => 'field_south_city_hero_chip_en',
                        'label' => __('Chip - English', 'south-city'),
                        'name' => 'text_en',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_south_city_hero_chip_bn',
                        'label' => __('Chip - Bangla', 'south-city'),
                        'name' => 'text_bn',
                        'type' => 'text',
                    ],
                ],
            ],
            [
                'key' => 'field_south_city_overview_paragraph_en',
                'label' => __('Overview Paragraph - English', 'south-city'),
                'name' => 'overview_paragraph_en',
                'type' => 'textarea',
                'rows' => 5,
            ],
            [
                'key' => 'field_south_city_overview_paragraph_bn',
                'label' => __('Overview Paragraph - Bangla', 'south-city'),
                'name' => 'overview_paragraph_bn',
                'type' => 'textarea',
                'rows' => 5,
            ],
            [
                'key' => 'field_south_city_overview_counters',
                'label' => __('Overview Counters', 'south-city'),
                'name' => 'overview_counters',
                'type' => 'repeater',
                'layout' => 'row',
                'button_label' => __('Add Counter', 'south-city'),
                'max' => 4,
                'sub_fields' => [
                    [
                        'key' => 'field_south_city_counter_end',
                        'label' => __('Number to Count Up To', 'south-city'),
                        'name' => 'end',
                        'type' => 'number',
                    ],
                    [
                        'key' => 'field_south_city_counter_display_en',
                        'label' => __('Final Display - English', 'south-city'),
                        'name' => 'display_en',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_south_city_counter_display_bn',
                        'label' => __('Final Display - Bangla', 'south-city'),
                        'name' => 'display_bn',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_south_city_counter_label_en',
                        'label' => __('Label - English', 'south-city'),
                        'name' => 'label_en',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_south_city_counter_label_bn',
                        'label' => __('Label - Bangla', 'south-city'),
                        'name' => 'label_bn',
                        'type' => 'text',
                    ],
                ],
            ],
            [
                'key' => 'field_south_city_master_plan_image',
                'label' => __('Master Plan Image', 'south-city'),
                'name' => 'master_plan_image',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ],
            [
                'key' => 'field_south_city_master_plan_hotspots',
                'label' => __('Master Plan Hotspots', 'south-city'),
                'name' => 'master_plan_hotspots',
                'type' => 'repeater',
                'layout' => 'row',
                'button_label' => __('Add Hotspot', 'south-city'),
                'sub_fields' => [
                    [
                        'key' => 'field_south_city_hotspot_id',
                        'label' => __('Hotspot ID', 'south-city'),
                        'name' => 'hotspot_id',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_south_city_hotspot_x',
                        'label' => __('X Position (%)', 'south-city'),
                        'name' => 'x',
                        'type' => 'number',
                        'min' => 0,
                        'max' => 100,
                        'step' => '0.1',
                    ],
                    [
                        'key' => 'field_south_city_hotspot_y',
                        'label' => __('Y Position (%)', 'south-city'),
                        'name' => 'y',
                        'type' => 'number',
                        'min' => 0,
                        'max' => 100,
                        'step' => '0.1',
                    ],
                    [
                        'key' => 'field_south_city_hotspot_name_en',
                        'label' => __('Name - English', 'south-city'),
                        'name' => 'name_en',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_south_city_hotspot_name_bn',
                        'label' => __('Name - Bangla', 'south-city'),
                        'name' => 'name_bn',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_south_city_hotspot_desc_en',
                        'label' => __('Description - English', 'south-city'),
                        'name' => 'desc_en',
                        'type' => 'textarea',
                        'rows' => 3,
                    ],
                    [
                        'key' => 'field_south_city_hotspot_desc_bn',
                        'label' => __('Description - Bangla', 'south-city'),
                        'name' => 'desc_bn',
                        'type' => 'textarea',
                        'rows' => 3,
                    ],
                ],
            ],
            [
                'key' => 'field_south_city_distances',
                'label' => __('Location Distances', 'south-city'),
                'name' => 'location_distances',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => __('Add Distance', 'south-city'),
                'sub_fields' => [
                    [
                        'key' => 'field_south_city_distance_place_en',
                        'label' => __('Place - English', 'south-city'),
                        'name' => 'place_en',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_south_city_distance_place_bn',
                        'label' => __('Place - Bangla', 'south-city'),
                        'name' => 'place_bn',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_south_city_distance_value_en',
                        'label' => __('Distance - English', 'south-city'),
                        'name' => 'value_en',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_south_city_distance_value_bn',
                        'label' => __('Distance - Bangla', 'south-city'),
                        'name' => 'value_bn',
                        'type' => 'text',
                    ],
                ],
            ],
            [
                'key' => 'field_south_city_boundaries',
                'label' => __('Project Boundaries', 'south-city'),
                'name' => 'project_boundaries',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => __('Add Boundary', 'south-city'),
                'sub_fields' => [
                    [
                        'key' => 'field_south_city_boundary_side_en',
                        'label' => __('Side - English', 'south-city'),
                        'name' => 'side_en',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_south_city_boundary_side_bn',
                        'label' => __('Side - Bangla', 'south-city'),
                        'name' => 'side_bn',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_south_city_boundary_value_en',
                        'label' => __('Boundary - English', 'south-city'),
                        'name' => 'value_en',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_south_city_boundary_value_bn',
                        'label' => __('Boundary - Bangla', 'south-city'),
                        'name' => 'value_bn',
                        'type' => 'text',
                    ],
                ],
            ],
            [
                'key' => 'field_south_city_web3forms_key',
                'label' => __('Web3Forms Access Key', 'south-city'),
                'name' => 'web3forms_key',
                'type' => 'text',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ],
            ],
        ],
        'menu_order' => 1,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ]);

    acf_add_local_field_group([
        'key' => 'group_south_city_trust_badge_fields',
        'title' => __('Trust Badge Fields', 'south-city'),
        'fields' => [
            [
                'key' => 'field_south_city_badge_icon',
                'label' => __('Icon', 'south-city'),
                'name' => 'icon',
                'type' => 'select',
                'choices' => $icon_choices,
                'ui' => 1,
                'return_format' => 'value',
            ],
            [
                'key' => 'field_south_city_badge_label_en',
                'label' => __('Label - English', 'south-city'),
                'name' => 'label_en',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_badge_label_bn',
                'label' => __('Label - Bangla', 'south-city'),
                'name' => 'label_bn',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_badge_order_rank',
                'label' => __('Sort Order', 'south-city'),
                'name' => 'order_rank',
                'type' => 'number',
                'default_value' => 0,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'southcity_badge',
                ],
            ],
        ],
        'menu_order' => 2,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ]);

    acf_add_local_field_group([
        'key' => 'group_south_city_project_fact_fields',
        'title' => __('Project Fact Fields', 'south-city'),
        'fields' => [
            [
                'key' => 'field_south_city_fact_label_en',
                'label' => __('Label - English', 'south-city'),
                'name' => 'label_en',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_fact_label_bn',
                'label' => __('Label - Bangla', 'south-city'),
                'name' => 'label_bn',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_fact_value_en',
                'label' => __('Value - English', 'south-city'),
                'name' => 'value_en',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_fact_value_bn',
                'label' => __('Value - Bangla', 'south-city'),
                'name' => 'value_bn',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_fact_order_rank',
                'label' => __('Sort Order', 'south-city'),
                'name' => 'order_rank',
                'type' => 'number',
                'default_value' => 0,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'southcity_fact',
                ],
            ],
        ],
        'menu_order' => 3,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ]);

    acf_add_local_field_group([
        'key' => 'group_south_city_plot_fields',
        'title' => __('Plot Fields', 'south-city'),
        'fields' => [
            [
                'key' => 'field_south_city_plot_katha_en',
                'label' => __('Size - English', 'south-city'),
                'name' => 'katha_en',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_plot_katha_bn',
                'label' => __('Size - Bangla', 'south-city'),
                'name' => 'katha_bn',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_plot_zone_en',
                'label' => __('Zone Name - English', 'south-city'),
                'name' => 'zone_en',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_plot_zone_bn',
                'label' => __('Zone Name - Bangla', 'south-city'),
                'name' => 'zone_bn',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_plot_sqft_en',
                'label' => __('Area in Sq Ft - English', 'south-city'),
                'name' => 'sqft_en',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_plot_sqft_bn',
                'label' => __('Area in Sq Ft - Bangla', 'south-city'),
                'name' => 'sqft_bn',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_plot_dimensions_en',
                'label' => __('Approximate Dimensions - English', 'south-city'),
                'name' => 'dimensions_en',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_plot_dimensions_bn',
                'label' => __('Approximate Dimensions - Bangla', 'south-city'),
                'name' => 'dimensions_bn',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_plot_price_en',
                'label' => __('Price - English', 'south-city'),
                'name' => 'price_en',
                'type' => 'text',
                'instructions' => __('Leave empty to show Call for price.', 'south-city'),
            ],
            [
                'key' => 'field_south_city_plot_price_bn',
                'label' => __('Price - Bangla', 'south-city'),
                'name' => 'price_bn',
                'type' => 'text',
                'instructions' => __('Leave empty to show Call for price.', 'south-city'),
            ],
            [
                'key' => 'field_south_city_plot_booking_en',
                'label' => __('Booking Money - English', 'south-city'),
                'name' => 'booking_en',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_plot_booking_bn',
                'label' => __('Booking Money - Bangla', 'south-city'),
                'name' => 'booking_bn',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_plot_installment_en',
                'label' => __('Installment Note - English', 'south-city'),
                'name' => 'installment_en',
                'type' => 'textarea',
                'rows' => 3,
            ],
            [
                'key' => 'field_south_city_plot_installment_bn',
                'label' => __('Installment Note - Bangla', 'south-city'),
                'name' => 'installment_bn',
                'type' => 'textarea',
                'rows' => 3,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'southcity_plot',
                ],
            ],
        ],
        'menu_order' => 4,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ]);

    acf_add_local_field_group([
        'key' => 'group_south_city_amenity_fields',
        'title' => __('Amenity Fields', 'south-city'),
        'fields' => [
            [
                'key' => 'field_south_city_amenity_icon',
                'label' => __('Icon', 'south-city'),
                'name' => 'icon',
                'type' => 'select',
                'choices' => $icon_choices,
                'ui' => 1,
                'return_format' => 'value',
            ],
            [
                'key' => 'field_south_city_amenity_label_en',
                'label' => __('Label - English', 'south-city'),
                'name' => 'label_en',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_amenity_label_bn',
                'label' => __('Label - Bangla', 'south-city'),
                'name' => 'label_bn',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_amenity_order_rank',
                'label' => __('Sort Order', 'south-city'),
                'name' => 'order_rank',
                'type' => 'number',
                'default_value' => 0,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'southcity_amenity',
                ],
            ],
        ],
        'menu_order' => 5,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ]);

    acf_add_local_field_group([
        'key' => 'group_south_city_landmark_fields',
        'title' => __('Neighborhood Tab Fields', 'south-city'),
        'fields' => [
            [
                'key' => 'field_south_city_landmark_label_en',
                'label' => __('Tab Label - English', 'south-city'),
                'name' => 'label_en',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_landmark_label_bn',
                'label' => __('Tab Label - Bangla', 'south-city'),
                'name' => 'label_bn',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_landmark_image_key',
                'label' => __('Image Key', 'south-city'),
                'name' => 'image_key',
                'type' => 'select',
                'choices' => [
                    'connectivity' => __('Connectivity', 'south-city'),
                    'education' => __('Education', 'south-city'),
                    'health' => __('Health', 'south-city'),
                    'daily' => __('Daily Needs', 'south-city'),
                ],
                'ui' => 1,
                'return_format' => 'value',
            ],
            [
                'key' => 'field_south_city_landmark_items',
                'label' => __('Items', 'south-city'),
                'name' => 'items',
                'type' => 'repeater',
                'layout' => 'row',
                'button_label' => __('Add Item', 'south-city'),
                'sub_fields' => [
                    [
                        'key' => 'field_south_city_landmark_item_name_en',
                        'label' => __('Place - English', 'south-city'),
                        'name' => 'name_en',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_south_city_landmark_item_name_bn',
                        'label' => __('Place - Bangla', 'south-city'),
                        'name' => 'name_bn',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_south_city_landmark_item_note_en',
                        'label' => __('Distance / Note - English', 'south-city'),
                        'name' => 'note_en',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_south_city_landmark_item_note_bn',
                        'label' => __('Distance / Note - Bangla', 'south-city'),
                        'name' => 'note_bn',
                        'type' => 'text',
                    ],
                ],
            ],
            [
                'key' => 'field_south_city_landmark_order_rank',
                'label' => __('Sort Order', 'south-city'),
                'name' => 'order_rank',
                'type' => 'number',
                'default_value' => 0,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'southcity_landmark',
                ],
            ],
        ],
        'menu_order' => 6,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ]);

    acf_add_local_field_group([
        'key' => 'group_south_city_gallery_fields',
        'title' => __('Gallery Image Fields', 'south-city'),
        'fields' => [
            [
                'key' => 'field_south_city_gallery_image',
                'label' => __('Image', 'south-city'),
                'name' => 'image',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ],
            [
                'key' => 'field_south_city_gallery_caption_en',
                'label' => __('Caption - English', 'south-city'),
                'name' => 'caption_en',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_gallery_caption_bn',
                'label' => __('Caption - Bangla', 'south-city'),
                'name' => 'caption_bn',
                'type' => 'text',
            ],
            [
                'key' => 'field_south_city_gallery_order_rank',
                'label' => __('Sort Order', 'south-city'),
                'name' => 'order_rank',
                'type' => 'number',
                'default_value' => 0,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'southcity_gallery',
                ],
            ],
        ],
        'menu_order' => 7,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ]);
}
add_action('acf/init', 'south_city_register_acf_field_groups');
