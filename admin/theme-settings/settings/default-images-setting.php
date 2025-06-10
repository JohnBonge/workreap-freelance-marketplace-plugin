<?php
/**
 * Default Images Settings
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/Theme_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/
Redux::setSection( $opt_name, array(
	'title'            => esc_html__( 'Default images', 'workreap' ),
	'id'               => 'default_images_settings',
	'subsection'       => false,
	'icon'			   => 'el el-picture',
	'fields'           => array(
			array(
				'id'       => 'defaul_employers_profile',
				'type'     => 'media',
				'title'    => esc_html__('Employer default profile image', 'workreap'),
            ),
			array(
				'id'       => 'defaul_freelancers_profile',
				'type'     => 'media',
				'title'    => esc_html__('Freelancer default profile image', 'workreap'),
            ),
			array(
				'id'       => 'defaul_site_logo',
				'type'     => 'media',
				'title'    => esc_html__('Dashboard logo', 'workreap'),
            ),
            array(
                'id'       => 'invoice_logo',
                'type'     => 'media',
                'title'    => esc_html__('Invoice logo', 'workreap'),
            ),
			array(
				'id'       => 'empty_listing_image',
				'type'     => 'media',
				'title'    => esc_html__('Empty listing image', 'workreap'),
            )
		)
	)
);
