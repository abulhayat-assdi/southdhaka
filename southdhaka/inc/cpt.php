<?php
/**
 * Custom post types and taxonomies for the South City content model.
 *
 * @package SouthCity
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Register all repeatable content records formerly managed as Sanity documents.
 */
function south_city_register_post_types(): void
{
    register_post_type('southcity_plot', [
        'labels' => [
            'name'                  => __('Plots', 'south-city'),
            'singular_name'         => __('Plot', 'south-city'),
            'menu_name'             => __('Plots', 'south-city'),
            'name_admin_bar'        => __('Plot', 'south-city'),
            'add_new'               => __('Add New', 'south-city'),
            'add_new_item'          => __('Add New Plot', 'south-city'),
            'edit_item'             => __('Edit Plot', 'south-city'),
            'new_item'              => __('New Plot', 'south-city'),
            'view_item'             => __('View Plot', 'south-city'),
            'search_items'          => __('Search Plots', 'south-city'),
            'not_found'             => __('No plots found.', 'south-city'),
            'not_found_in_trash'    => __('No plots found in Trash.', 'south-city'),
            'all_items'             => __('All Plots', 'south-city'),
            'archives'              => __('Plot Archives', 'south-city'),
            'attributes'            => __('Plot Attributes', 'south-city'),
            'insert_into_item'      => __('Insert into plot', 'south-city'),
            'uploaded_to_this_item' => __('Uploaded to this plot', 'south-city'),
        ],
        'public'       => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-location-alt',
        'show_in_rest' => false,
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'],
    ]);

    register_post_type('southcity_badge', [
        'labels' => [
            'name'               => __('Trust Badges', 'south-city'),
            'singular_name'      => __('Trust Badge', 'south-city'),
            'menu_name'          => __('Trust Badges', 'south-city'),
            'add_new_item'       => __('Add New Trust Badge', 'south-city'),
            'edit_item'          => __('Edit Trust Badge', 'south-city'),
            'new_item'           => __('New Trust Badge', 'south-city'),
            'view_item'          => __('View Trust Badge', 'south-city'),
            'search_items'       => __('Search Trust Badges', 'south-city'),
            'not_found'          => __('No trust badges found.', 'south-city'),
            'not_found_in_trash' => __('No trust badges found in Trash.', 'south-city'),
            'all_items'          => __('All Trust Badges', 'south-city'),
        ],
        'public'              => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'exclude_from_search' => true,
        'has_archive'         => false,
        'menu_icon'           => 'dashicons-shield-alt',
        'show_in_rest'        => false,
        'supports'            => ['title', 'page-attributes'],
    ]);

    register_post_type('southcity_fact', [
        'labels' => [
            'name'               => __('Project Facts', 'south-city'),
            'singular_name'      => __('Project Fact', 'south-city'),
            'menu_name'          => __('Project Facts', 'south-city'),
            'add_new_item'       => __('Add New Project Fact', 'south-city'),
            'edit_item'          => __('Edit Project Fact', 'south-city'),
            'new_item'           => __('New Project Fact', 'south-city'),
            'view_item'          => __('View Project Fact', 'south-city'),
            'search_items'       => __('Search Project Facts', 'south-city'),
            'not_found'          => __('No project facts found.', 'south-city'),
            'not_found_in_trash' => __('No project facts found in Trash.', 'south-city'),
            'all_items'          => __('All Project Facts', 'south-city'),
        ],
        'public'              => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'exclude_from_search' => true,
        'has_archive'         => false,
        'menu_icon'           => 'dashicons-info-outline',
        'show_in_rest'        => false,
        'supports'            => ['title', 'page-attributes'],
    ]);

    register_post_type('southcity_amenity', [
        'labels' => [
            'name'               => __('Amenities', 'south-city'),
            'singular_name'      => __('Amenity', 'south-city'),
            'menu_name'          => __('Amenities', 'south-city'),
            'add_new_item'       => __('Add New Amenity', 'south-city'),
            'edit_item'          => __('Edit Amenity', 'south-city'),
            'new_item'           => __('New Amenity', 'south-city'),
            'view_item'          => __('View Amenity', 'south-city'),
            'search_items'       => __('Search Amenities', 'south-city'),
            'not_found'          => __('No amenities found.', 'south-city'),
            'not_found_in_trash' => __('No amenities found in Trash.', 'south-city'),
            'all_items'          => __('All Amenities', 'south-city'),
            'archives'           => __('Amenity Archives', 'south-city'),
        ],
        'public'       => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-palmtree',
        'show_in_rest' => false,
        'supports'     => ['title', 'editor', 'thumbnail', 'page-attributes'],
    ]);

    register_post_type('southcity_landmark', [
        'labels' => [
            'name'               => __('Neighborhood Tabs', 'south-city'),
            'singular_name'      => __('Neighborhood Tab', 'south-city'),
            'menu_name'          => __('Neighborhood', 'south-city'),
            'add_new_item'       => __('Add New Neighborhood Tab', 'south-city'),
            'edit_item'          => __('Edit Neighborhood Tab', 'south-city'),
            'new_item'           => __('New Neighborhood Tab', 'south-city'),
            'view_item'          => __('View Neighborhood Tab', 'south-city'),
            'search_items'       => __('Search Neighborhood Tabs', 'south-city'),
            'not_found'          => __('No neighborhood tabs found.', 'south-city'),
            'not_found_in_trash' => __('No neighborhood tabs found in Trash.', 'south-city'),
            'all_items'          => __('All Neighborhood Tabs', 'south-city'),
            'archives'           => __('Neighborhood Archives', 'south-city'),
        ],
        'public'       => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-location',
        'show_in_rest' => false,
        'supports'     => ['title', 'editor', 'thumbnail', 'page-attributes'],
    ]);

    register_post_type('southcity_location', [
        'labels' => [
            'name'               => __('Locations', 'south-city'),
            'singular_name'      => __('Location', 'south-city'),
            'menu_name'          => __('Location Manager', 'south-city'),
            'add_new_item'       => __('Add New Location', 'south-city'),
            'edit_item'          => __('Edit Location', 'south-city'),
            'new_item'           => __('New Location', 'south-city'),
            'view_item'          => __('View Location', 'south-city'),
            'search_items'       => __('Search Locations', 'south-city'),
            'not_found'          => __('No locations found.', 'south-city'),
            'not_found_in_trash' => __('No locations found in Trash.', 'south-city'),
            'all_items'          => __('All Locations', 'south-city'),
            'archives'           => __('Location Archives', 'south-city'),
        ],
        'public'              => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'exclude_from_search' => true,
        'has_archive'         => false,
        'menu_icon'           => 'dashicons-location-alt',
        'show_in_rest'        => false,
        'supports'            => ['title', 'page-attributes'],
    ]);

    register_post_type('southcity_gallery', [
        'labels' => [
            'name'               => __('Gallery Images', 'south-city'),
            'singular_name'      => __('Gallery Image', 'south-city'),
            'menu_name'          => __('Gallery', 'south-city'),
            'add_new_item'       => __('Add New Gallery Image', 'south-city'),
            'edit_item'          => __('Edit Gallery Image', 'south-city'),
            'new_item'           => __('New Gallery Image', 'south-city'),
            'view_item'          => __('View Gallery Image', 'south-city'),
            'search_items'       => __('Search Gallery Images', 'south-city'),
            'not_found'          => __('No gallery images found.', 'south-city'),
            'not_found_in_trash' => __('No gallery images found in Trash.', 'south-city'),
            'all_items'          => __('All Gallery Images', 'south-city'),
            'archives'           => __('Gallery Archives', 'south-city'),
        ],
        'public'       => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-format-gallery',
        'show_in_rest' => false,
        'supports'     => ['title', 'editor', 'thumbnail', 'page-attributes'],
    ]);
}
add_action('init', 'south_city_register_post_types');

/**
 * Register taxonomies that make repeatable records easier to organize in admin.
 */
function south_city_register_taxonomies(): void
{
    register_taxonomy('southcity_plot_zone', ['southcity_plot'], [
        'labels' => [
            'name'              => __('Plot Zones', 'south-city'),
            'singular_name'     => __('Plot Zone', 'south-city'),
            'search_items'      => __('Search Plot Zones', 'south-city'),
            'all_items'         => __('All Plot Zones', 'south-city'),
            'parent_item'       => __('Parent Plot Zone', 'south-city'),
            'parent_item_colon' => __('Parent Plot Zone:', 'south-city'),
            'edit_item'         => __('Edit Plot Zone', 'south-city'),
            'update_item'       => __('Update Plot Zone', 'south-city'),
            'add_new_item'      => __('Add New Plot Zone', 'south-city'),
            'new_item_name'     => __('New Plot Zone Name', 'south-city'),
            'menu_name'         => __('Plot Zones', 'south-city'),
        ],
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_rest'     => false,
    ]);

    register_taxonomy('southcity_amenity_group', ['southcity_amenity'], [
        'labels' => [
            'name'                       => __('Amenity Groups', 'south-city'),
            'singular_name'              => __('Amenity Group', 'south-city'),
            'search_items'               => __('Search Amenity Groups', 'south-city'),
            'popular_items'              => __('Popular Amenity Groups', 'south-city'),
            'all_items'                  => __('All Amenity Groups', 'south-city'),
            'edit_item'                  => __('Edit Amenity Group', 'south-city'),
            'update_item'                => __('Update Amenity Group', 'south-city'),
            'add_new_item'               => __('Add New Amenity Group', 'south-city'),
            'new_item_name'              => __('New Amenity Group Name', 'south-city'),
            'separate_items_with_commas' => __('Separate amenity groups with commas', 'south-city'),
            'add_or_remove_items'        => __('Add or remove amenity groups', 'south-city'),
            'choose_from_most_used'      => __('Choose from the most used amenity groups', 'south-city'),
            'menu_name'                  => __('Amenity Groups', 'south-city'),
        ],
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'hierarchical'      => false,
        'show_admin_column' => true,
        'show_in_rest'     => false,
    ]);
}
add_action('init', 'south_city_register_taxonomies');

/**
 * Only one location can be the homepage map pin at a time, so unset the
 * flag on every other location whenever one is saved as primary.
 */
function south_city_enforce_single_primary_location($post_id): void
{
    // ACF passes "options" (settings forms) or "user_5" here, not only post IDs.
    // Typing this as int made every settings save crash with a TypeError.
    if (! is_numeric($post_id)) {
        return;
    }

    $post_id = (int) $post_id;

    if (get_post_type($post_id) !== 'southcity_location') {
        return;
    }

    if (! function_exists('get_field') || ! get_field('is_primary', $post_id)) {
        return;
    }

    $other_locations = get_posts([
        'post_type'      => 'southcity_location',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'post__not_in'   => [$post_id],
        'fields'         => 'ids',
    ]);

    foreach ($other_locations as $other_id) {
        if (function_exists('update_field')) {
            update_field('is_primary', 0, $other_id);
        }
    }
}
add_action('acf/save_post', 'south_city_enforce_single_primary_location', 20);

/**
 * Get the location currently marked as the homepage map pin, falling back
 * to the oldest location record if none is explicitly marked.
 */
function south_city_get_primary_location(): ?WP_Post
{
    $primary = get_posts([
        'post_type'      => 'southcity_location',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'meta_key'       => 'is_primary',
        'meta_value'     => '1',
        'orderby'        => 'date',
        'order'          => 'ASC',
    ]);

    if (! empty($primary)) {
        return $primary[0];
    }

    $fallback = get_posts([
        'post_type'      => 'southcity_location',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'ASC',
    ]);

    return $fallback[0] ?? null;
}
