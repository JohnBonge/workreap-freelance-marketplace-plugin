<?php
/**
 * Elementor Page builder base
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://themeforest.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           Workreap
 *
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die('No kiddies please!');
}

/**
 * @prepare Custom taxonomies array
 * @return array
 */
function workreap_elementor_get_taxonomies($post_type = 'post', $taxonomy = 'category', $hide_empty = 0, $dataType = 'input', $parent='', $arguments=array()) {
	$args = array(
		'type' 			=> $post_type,
		'child_of'  	=> 0,
		'parent' 		=> $parent,
		'hide_empty' 	=> $hide_empty,
		'hierarchical' 	=> 1,
		'exclude' 		=> '',
		'include' 		=> '',
		'number' 		=> '',
		'taxonomy' 		=> $taxonomy,
		'pad_counts' 	=> false
	);

	if( !empty($arguments) ){
		foreach($arguments as $key => $val){
			$args[$key]	= $val;
		}
	}
	$categories = get_categories($args);

	if ($dataType == 'array') {
		return $categories;
	}

	$custom_Cats = array();

	if (!empty($categories)) {
		foreach ($categories as $key => $value) {
			$custom_Cats[$value->term_id] = $value->name;
		}
	}

	return $custom_Cats;
}

/**
 * @prepare Custom menu array
 * @return array
 */
function workreap_elementor_get_menus() {
	$menus			= wp_get_nav_menus();
	$list_items		= array();
	if( !empty($menus)){
		foreach($menus as $menu){
			$list_items[$menu->term_id]	= $menu->name;
		}
	}
	return $list_items;
}
/**
 * @prepare Custom taxonomies array
 * @return array
 */
function workreap_elementor_get_posts($post_type = 'post', $dataType = 'input') {

	$args = array(
        'numberposts'      => -1,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'post_type'        => $post_type,
        'suppress_filters' => true,
    );

	$posts = get_posts($args);

	if ($dataType == 'array') {
		return $posts;
	}

	$custom_posts = array();

	if (!empty($posts)) {
		foreach ($posts as $post) {
			$custom_posts[$post->ID] = $post->post_title;
		}
	}

	return $custom_posts;
}

/**
 * @prepare Social links array
 * @return array
 */
function workreap_social_profiles () {
	$social_profile = array (
		'facebook_link'	=> array (
			'class'	=> 'wr-facebook-hover',
			'icon'	=> 'fab fa-facebook-f',
			'lable' => esc_html__('Facebook','workreap'),
		),
		'twitter_link'	=> array (
			'class'	=> 'wr-twitter-hover',
			'icon'	=> 'fab fa-twitter',
			'lable' => esc_html__('Twitter','workreap'),
		),
		'dribbble_link'	=> array (
			'class'	=> 'wr-dribbble-hover',
			'icon'	=> 'fab fa-dribbble',
			'lable' => esc_html__('dribbble','workreap'),
		),
		'youtube_link'=> array (
			'class'	=> 'wr-youtube-hover',
			'icon'	=> 'fab fa-youtube',
			'lable' => esc_html__('Youtube','workreap'),
		),
	);
	return $social_profile;
}

/**
 * @get post thumbnail
 * @return thumbnail url
 */
if (!function_exists('workreap_prepare_image_source')) {

  function workreap_prepare_image_source($image_id, $width = '300', $height = '300') {
    $thumb_url = wp_get_attachment_image_src($image_id, array($width, $height), true);
    
	if ($thumb_url[1] == $width and $thumb_url[2] == $height) {
      return !empty($thumb_url[0]) ? $thumb_url[0] : '';
    } else {
      $thumb_url = wp_get_attachment_image_src($image_id, 'full', true);
      return !empty($thumb_url[0]) ? $thumb_url[0] : '';
    }
	
  }
}
if (!function_exists('workreap_get_countries')) {
function workreap_get_countries(){
	$countries      = array();
	if (class_exists('WooCommerce')) {
		$countries_obj   = new WC_Countries();
		$countries   = $countries_obj->get_allowed_countries('countries');
	}
	return $countries;
}
}