<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Api Settings
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/Theme_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/

$payout_list 		= workreap_get_payouts_lists();
$listEmpPackages	= workreap_get_packages_lists();

$list_payouts = array(
	array(
		'id' 		=> 'admin_commision',
		'type' 		=> 'slider',
		'title' 	=> esc_html__('Admin commission from freelancers', 'workreap'),
		'subtitle' 	=> esc_html__('Set task and project commission in percentage.', 'workreap'),
		"default" 	=> 0,
		"min" 		=> 0,
		"step" 		=> 1,
		"max" 		=> 100,
		'display_value' => 'label',
	),
	array(
		'id' 		=> 'admin_commision_employers',
		'type' 		=> 'slider',
		'title' 	=> esc_html__('Admin commission from employers', 'workreap'),
		'subtitle' 		=> esc_html__('Set task and project commission in percentage.', 'workreap'),
		"default" 	=> 0,
		"min" 		=> 0,
		"step" 		=> 1,
		"max" 		=> 100,
		'display_value' => 'label',
	),
	array(
		'id'       => 'commission_text',
		'type'     => 'text',
		'title'    => esc_html__('Commission text', 'workreap'),
		'subtitle'     => esc_html__('Add commission text here.', 'workreap'),
		'default'  => esc_html__('Processing fee', 'workreap'),
	),
	array(
		'id'       => 'min_amount',
		'type'     => 'text',
		'title'    => esc_html__('Minimum withdraw amount', 'workreap'),
		'subtitle'     => esc_html__('Set minimum withdraw here.', 'workreap'),
		'default'  => '',
	),
	array(
		'id'        => 'min_wallet_amount',
		'type'      => 'text',
		'title'     => esc_html__('Employer min wallet amount', 'workreap'),
		'default'   => 1,
		'subtitle'      => esc_html__('Set minimum add to wallet amount here.', 'workreap'),
	),

);
$hide_methods	= array();
if (!empty($payout_list)) {
	foreach ($payout_list as $type_key => $val) {
		$fields = array();
		$selected_values  = array();
		if(!empty($val['fields'])){
			foreach($val['fields'] as $f_k =>$f_val){
				$fields[$f_k] = $f_val['title'];
				if(!empty($f_val['required'])){
					$selected_values[]= $f_k;
				}
			}
		}
		$option_val = array(
			'id'            => $val['id'].'_fields',
			'type'          => 'select',
			'multi'         => true,
			'title'         => sprintf(  _x( '%s payout required fields', 'workreap' ),$val['label'] ),
			'options'       => $fields,
			'default'       => $selected_values
		);
		$hide_methods[$val['id']]	= $val['label'];
		$list_payouts[] = $option_val;
		if($val['id'] === 'bank'){
			$list_payouts[]  = array(
				'id'            => $val['id'].'_hide_fields',
				'type'          => 'select',
				'multi'         => true,
				'title'         => sprintf(  _x( '%s payout hide fields', 'workreap' ),$val['label'] ),
				'options'       => $fields,
				'subtitle'     		=> esc_html__('Selected fields must be optional', 'workreap'),
				'default'       => array()
			);
		}
	}
}
$list_payouts[]	= array(
	'id'       => 'payout_item_hide',
	'type'     => 'select',
	'multi'    => true,
	'title'    => esc_html__('Hide payout methods', 'workreap'),
	'subtitle'    => esc_html__('Select payout methods to hide.', 'workreap'),
	'options'  => $hide_methods,
);
Redux::setSection( $opt_name, array(
		'title'            => esc_html__( 'Payment settings ', 'workreap' ),
		'id'               => 'payout_settings',
		'desc'       	   => '',
		'subsection'       => false,
		'icon'			   => 'el el-shopping-cart',
		'fields'           => $list_payouts
	)
);

Redux::setSection( $opt_name, array(
	'title'			=> esc_html__( 'Package Settings', 'workreap' ),
	'id'			=> 'package_setings',
	'icon'			=> 'el el-calendar',
//	'subsection'	=> true,
	'fields'		=>  array(
		array(
			'id'       => 'package_option',
			'type'     => 'select',
			'title'    => esc_html__('Package listing type', 'workreap'),
			'subtitle'     => esc_html__('Select packages listing type here.', 'workreap'),
			'options'  => array(
				'both' 			=> esc_html__('Free listing for both type of users', 'workreap'),
				'paid' 			=> esc_html__('Paid listing for both', 'workreap'),
				'employer_free' 	=> esc_html__('Paid listing for freelancers', 'workreap'),
				'freelancer_free' 	=> esc_html__('Paid listing for employers', 'workreap')
			),
			'default'  => 'both'
		),
		array(
			'id'       => 'emp_trail_pkg',
			'type'     => 'select',
			'data'     => 'posts',
			'args'     => array( 'post_type' =>  array( 'product' ), 'numberposts' => -1,'tax_query' => array(array('taxonomy' => 'product_type','field'=> 'slug','terms'=> 'employer_packages')) ),
			'title'    => esc_html__('Employers trail package', 'workreap'),
			'subtitle'     => esc_html__('Select employers trail package.', 'workreap'),
			'required'  => array('package_option', '=', array('paid','freelancer_free')),
		),
		array(
			'id'       => 'free_trail_pkg',
			'type'     => 'select',
			'data'     => 'posts',
			'args'     => array( 'post_type' =>  array( 'product' ), 'numberposts' => -1,'tax_query' => array(array('taxonomy' => 'product_type','field'=> 'slug','terms'=> 'packages')) ),
			'title'    => esc_html__('Freelancers trail package', 'workreap'),
			'subtitle'     => esc_html__('Select freelancers trail package.', 'workreap'),
			'required'  => array('package_option', '=', array('paid','employer_free')),
		),
		array(
			'id'       => 'pkg_page_title',
			'type'     => 'text',
			'title'    => esc_html__('Price plan title', 'workreap'),
			'subtitle'     => esc_html__('Insert price plan title here.', 'workreap'),
			'default'  => 'We Genuinely Offer',
		),
		array(
			'id'       => 'pkg_page_sub_title',
			'type'     => 'text',
			'title'    => esc_html__('Price plan subtitle', 'workreap'),
			'subtitle'     => esc_html__('Insert price plan subtitle here.', 'workreap'),
			'default'  => 'Affordable price plans',
		),
		array(
			'id'       => 'pkg_page_details',
			'type'     => 'editor',
			'title'    => esc_html__('Price plan description', 'workreap'),
			'subtitle'     => esc_html__('Insert price plan description here.', 'workreap'),
			'default'  => '',
		),
	)
));
