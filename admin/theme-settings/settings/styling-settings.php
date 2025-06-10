<?php

/**
 * Typograpy Settings
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/Theme_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */
Redux::setSection(
  $opt_name,
  array(
    'title'         => esc_html__('Style settings', 'workreap'),
    'id'            => 'styling_settings',
    'subsection'    => false,
    'icon'          => 'el el-globe',
    'fields'        => array(
	    array(
		    'id'          => 'wr_body_font',
		    'type'        => 'typography',
		    'title'       => __( 'Body font', 'workreap' ),
		    'google'      => true,
		    'font-backup' => false,
		    'font-style'  => false,
		    'font-weight' => false,
		    'font-size'   => false,
		    'subsets'     => false,
		    'line-height' => false,
		    'text-align'  => false,
		    'color'       => false,
		    'preview'     => false,
		    'font_family_clear' => false,
		    'subtitle'    => __( 'Select body font here.', 'workreap' ),
		    'default'     => array(
			    'font-family' => 'Inter'
		    ),
	    ),
	    array(
		    'id'          => 'wr_heading_font',
		    'type'        => 'typography',
		    'title'       => __( 'Heading font', 'workreap' ),
		    'google'      => true,
		    'font-backup' => false,
		    'font-style'  => false,
		    'font-weight' => false,
		    'font-size'   => false,
		    'subsets'     => false,
		    'line-height' => false,
		    'text-align'  => false,
		    'color'       => false,
		    'preview'     => false,
		    'all_styles'  => true,
		    'font_family_clear' => false,
		    'subtitle'    => __( 'Select headings font here.', 'workreap' ),
		    'default'     => array(
			    'font-family' => 'Inter'
		    ),
	    ),
      array(
        'id'        => 'wr_primary_color',
        'type'      => 'color',
        'title'     => esc_html__('Primary color', 'workreap'),
        'subtitle'  => esc_html__('Select primary color here.', 'workreap'),
        'default'   => '#EE4710',
        'transparent' => false,
      ),
      array(
        'id'        => 'wr_secondary_color',
        'type'      => 'color',
        'title'     => esc_html__('Secondary color', 'workreap'),
        'subtitle'  => esc_html__('Select secondary color here.', 'workreap'),
        'default'   => '#0A0F26',
        'transparent' => false,
      ),
      array(
        'id'        => 'wr_tertiary_color',
        'type'      => 'color',
        'title'     => esc_html__('Font color', 'workreap'),
        'subtitle'  => esc_html__('Select font color here.', 'workreap'),
        'default'   => '#1E1E1E',
        'transparent' => false,
      ),
      array(
        'id'        => 'wr_link_color',
        'type'      => 'color',
        'title'     => esc_html__('Hyper link color', 'workreap'),
        'subtitle'  => esc_html__('Select link color here.', 'workreap'),
        'default'   => '#3377FF',
        'transparent' => false,
      ),
      array(
        'id'        => 'wr_button_color',
        'type'      => 'color',
        'title'     => esc_html__('Button text color', 'workreap'),
        'subtitle'  => esc_html__('Select button text color here.', 'workreap'),
        'default'   => '#ffffff',
        'transparent' => false,
      ),
    )
  )
);
