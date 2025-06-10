<?php
/**
 *
 * Class 'Workreap_Admin_Hooks' defines to remove the product data default tabs
 *
 * @package     Workreap
 * @subpackage  Workreap/admin
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

class Workreap_Admin_Hooks {

	/**
	 * Add action hooks
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {
		add_action( 'workreap_render_plans_fields', array(&$this, 'workreap_render_plans_html'), 10, 3);
		add_action( 'workreap_render_subtasks_details_fields', array(&$this, 'workreap_render_subtasks_details_fields_html'), 10, 2);
		add_action( 'workreap_render_subtasks_fields', array(&$this, 'workreap_render_subtasks_fields_html'), 10, 2);
		add_action( 'workreap_acf_dynamically_render_fields', array(&$this, 'workreap_acf_dynamically_render_fields'), 10, 4);
		add_filter( 'manage_users_columns', array(&$this, 'workreap_manage_user_columns'));
		add_filter( 'manage_users_custom_column', array(&$this, 'workreap_manage_user_column_row'), 10, 3);
		add_action( 'wp_ajax_workreap_approve_profile', array(&$this, 'workreap_approve_profile') );
		add_action( 'wp_ajax_workreap_update_admin_notification', array(&$this,'workreap_update_admin_notification'));
		add_filter( 'fw_ext_backups_demo_dirs', array(&$this, 'workreap_filter_theme_fw_ext_backups_demos'));
		add_action( 'admin_notices', array(&$this, 'workreap_wp_uppy_pro_admin_notices_list') );

        //Mega Menu
        $theme 		=  wp_get_theme();
        $theme_name = $theme->get( 'Name' );
		$parent_theme_name = $theme->get( 'Template' );
        if($theme->get( 'TextDomain' ) === 'workreap' || $theme_name === 'Workreap' || $parent_theme_name === 'workreap' ){
            add_action('wp_nav_menu_item_custom_fields', array($this, 'workreap_menu_item_custom_fields'), 10, 4);
            add_action('wp_update_nav_menu_item', array($this, 'workreap_menu_save_custom_fields'), 10, 2);
            add_filter('nav_menu_link_attributes', array($this, 'workreap_menu_item_content'), 10, 3);
            add_filter('nav_menu_css_class', array($this, 'workreap_menu_item_classes'), 1, 3);
        }

	}


    /**
     * Add custom field to menu item
     *
     * @param $item_id
     * @param $item
     * @param $depth
     * @param $args
     */
    public function workreap_menu_item_custom_fields($item_id, $item, $depth, $args){

	    if($depth !== 0){
	        return;
        }

        $post_args = array(
            'post_type'      => 'elementor_library',
            'posts_per_page' => -1,
            'tabs_group' => 'library',
            'elementor_library_type' => 'section',
        );

        $elementor_posts = get_posts($post_args);

        $template = get_post_meta( $item_id, '_workreap_megamenu_item_template', true );
        $responsive = get_post_meta( $item_id, '_workreap_megamenu_item_responsive', true );

        ?>
        <div style="clear: both;">
            <input type="hidden" class="nav-menu-id" value="<?php echo $item_id ;?>" />
            <label for="workreap-megamenu-item-template-<?php echo $item_id ;?>" class="workreap-megamenu-item-template"><?php _e( "Megamenu Template", 'workreap' ); ?></label><br />
            <div class="logged-input-holder">
                <select style="width:100%;" name="workreap-megamenu-item-template[<?php echo $item_id ;?>]" id="workreap-megamenu-item-template-<?php echo $item_id ;?>">
                    <option value="none"><?php echo esc_html__('None', 'workreap') ?></option>
                   <?php
                   foreach ($elementor_posts as $post) { ?>
                       <option<?php echo $post->ID == $template ? ' selected="selected"' : '' ?> value="<?php echo esc_attr($post->ID); ?>"><?php echo esc_html($post->post_title) ?></option>
                   <?php } ?>
                </select>
            </div>
            <label for="workreap-megamenu-item-responsive-<?php echo $item_id ;?>" class="workreap-megamenu-item-responsive"><?php _e( "Megamenu on Mobile", 'workreap' ); ?></label><br />
            <div class="logged-input-holder">
                <select style="width:100%;" name="workreap-megamenu-item-responsive[<?php echo $item_id ;?>]" id="workreap-megamenu-item-responsive-<?php echo $item_id ;?>">
                    <option value="hide" selected="selected"><?php echo esc_html__('Hide', 'workreap') ?></option>
                    <option value="show" <?php echo $responsive === 'show' ? 'selected=" selected"' : '' ?>><?php echo esc_html__('Show', 'workreap') ?></option>
                </select>
            </div>
        </div>

        <?php
    }


    /**
     * Save custom field data
     *
     * @param $item_id
     * @param $item
     */
    public function workreap_menu_save_custom_fields($item_id, $item){
        if ( isset( $_POST['workreap-megamenu-item-template'][$item]  ) ) {
            $sanitized_data = sanitize_text_field( $_POST['workreap-megamenu-item-template'][$item] );
            update_post_meta( $item, '_workreap_megamenu_item_template', $sanitized_data );
        } else {
            delete_post_meta( $item, '_workreap_megamenu_item_template' );
        }
        if ( isset( $_POST['workreap-megamenu-item-responsive'][$item]  ) ) {
            $sanitized_data = sanitize_text_field( $_POST['workreap-megamenu-item-responsive'][$item] );
            update_post_meta( $item, '_workreap_megamenu_item_responsive', $sanitized_data );
        } else {
            delete_post_meta( $item, '_workreap_megamenu_item_responsive' );
        }
    }


    /**
     * Add custom field to menu item
     *
     * @param $atts
     * @param $item
     * @param $args
     * @return mixed
     */
    public function workreap_menu_item_content($atts, $item, $args){

        $id = (int) $item->ID;
        $template = get_post_meta( $id, '_workreap_megamenu_item_template', true );
        if( $args->theme_location === 'primary-menu' && !empty($template) && $template !== 'none' ){
            $atts['class'] = 'workreap-megamenu-link';
            $args->after  = '<div class="workreap-megamenu">' . \Elementor\Plugin::instance()->frontend->get_builder_content($template) . '</div>';
        }

        return $atts;
    }


    /**
     * Add nav item classes
     *
     * @param $classes
     * @param $item
     * @param $args
     * @return mixed
     */
    public function workreap_menu_item_classes($classes, $item, $args){

	    $id = $item->ID;
        $template = get_post_meta( $id, '_workreap_megamenu_item_template', true );
        $responsive = get_post_meta( $id, '_workreap_megamenu_item_responsive', true );
        if( $args->theme_location === 'primary-menu' && !empty($template) && $template !== 'none' ){
            $classes[] = 'wr-megamenu-holder';
        }
        if( $args->theme_location === 'primary-menu' && isset($responsive) ){
            $classes[] = 'wr-megamenu-on-responsive-' . $responsive;
        }
	    return $classes;
    }
	

	/**
	 * @Get WP Guppy Pro
	 */
	function workreap_wp_uppy_pro_admin_notices_list() {

		if ( isset( $_GET['dismiss-guppy'] ) && check_admin_referer( 'guppy-dismiss-' . get_current_user_id() ) ) {
			update_user_meta( get_current_user_id(), 'guppy_dismissed_notice', 1 );
		}

		if(!is_plugin_active('wp-guppy/wp-guppy.php') && get_user_meta(get_current_user_id(), 'guppy_dismissed_notice', true) == false){?>
			<div class="notice notice-success wp-guppy-admin-notice">
				<p><strong><?php esc_html_e( 'WP Guppy Pro - A live chat plugin is compatible with Workreap Freelancer Marketplace for the live chat', 'workreap' ); ?></strong></p>
				<p><a class="button button-primary" target="_blank" href="https://codecanyon.net/item/wpguppy-a-live-chat-plugin-for-wordpress/34619534?s_rank=1"><?php esc_html_e( 'Get WP Guppy Pro', 'workreap' ); ?></a>
				<?php echo '<a href="' . esc_url( wp_nonce_url( add_query_arg( 'dismiss-guppy', 'dismiss_admin_notices' ), 'guppy-dismiss-' . get_current_user_id() ) ) . '" class="notice dismiss-notice button-secondary" >'.esc_html__('Dismiss','workreap').'</a>';?>
				</p>
			</div>
			<?php
		}
	}

	/**
	 * Demo content unyson import path
	 *
	 * @since    1.0.0
	*/
	public function workreap_filter_theme_fw_ext_backups_demos($demo_path	= array()){
		if (!defined('FW')) return $demo_path;
		
		$demo_path	= array(
			fw_fix_path(WORKREAP_DIRECTORY) .'/demo-content'
			=>
			WORKREAP_DIRECTORY_URI .'demo-content',
		);
		
		return $demo_path;
	}


	/**
	 * Render product Plan fields hook
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function workreap_render_subtasks_details_fields_html($workreap_product_tasks_fields, $workreap_plans_values){
		$render_fields = new Workreap_Render_Fields();
		foreach($workreap_product_tasks_fields as $key=>$subtask){
			$field 				= array();
			$field['base'] 		= 'tasks_details';
			$id 				= $subtask['id'];
			$type 				= $subtask['type'];
			$field 				= array_merge($field, $subtask);

			if(isset($workreap_plans_values[$id])){
				$field['value'] = $workreap_plans_values[$id];
			} elseif(isset($subtask['default_value'])){
				$field['value'] = $subtask['default_value'];
			}

			$workreap_render_field	= '';
			$field = apply_filters('workreap_product_subtask_details_field', $field);

			switch ($type) {
				case "text":
					$workreap_render_field	= $render_fields->text_field($field);
					break;
				case "post_dropdwon":
					$workreap_render_field	= $render_fields->post_dropdwon($field);
					break;
				case "textarea":
					$workreap_render_field	= $render_fields->textarea_field($field);
					break;
			}

			$workreap_render_field	.= do_action('plan_packages_extra_fields', $field, $subtask);

			echo do_shortcode($workreap_render_field);

		}
	}

	/**
	 * Render product Plan fields hook
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function workreap_render_subtasks_fields_html($workreap_product_tasks_fields, $workreap_plans_values){
		$render_fields = new Workreap_Render_Fields();
		foreach($workreap_product_tasks_fields as $key=>$subtask){
			$field = array();
			$field['base'] = 'subtask';
			$id = $subtask['id'];
			$type = $subtask['type'];
			$field = array_merge($field, $subtask);

			if(!empty($workreap_plans_values)){
				$field['value'] = $workreap_plans_values;
			} else {
				$field['value'] = isset($subtask['default_value']) ? $subtask['default_value'] : '';
			}

			$field = apply_filters('workreap_product_subtask_field', $field);
			$workreap_field_html	= '';

			switch ($type) {
				case "text":
					$workreap_field_html	= $render_fields->text_field($field);
					break;
				case "post_dropdwon":
					$workreap_field_html	= $render_fields->post_dropdwon($field);
					break;
				case "textarea":
					$workreap_field_html	= $render_fields->textarea_field($field);
					break;
			}

			$workreap_field_html	.= do_action('plan_packages_subtask_extra_fields', $field, $subtask);

			echo do_shortcode($workreap_field_html);

		}
	}

	/**
	 * Render product Plan fields hook
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function workreap_render_plans_html($workreap_plans, $workreap_plans_values, $task_plans_allowed = 'yes'){
		$render_fields = new Workreap_Render_Fields();
		$package_counter	= 0;
		$disabled_field	= false;

		foreach($workreap_plans as $key=>$plan_pkgs){
			$package_active_class	= 'wr-package-active';
			$package_counter++;
			$disabled_field	= false;

			if($task_plans_allowed == 'no' && $package_counter>1){
				$package_active_class	= 'wr-package-overlay';
				$disabled_field	= true;
			}

			$field 	= array();
			$field['base'] = 'plans';
			$field['base'] = 'plans';
			$field['plan'] = $key;
			$field['package_counter'] = $package_counter;
			?>
			<div class="wr-pricingtitle <?php echo esc_attr($package_active_class);?>">
				<div class="wr-serviceformwrap">
					<?php foreach($plan_pkgs as $pk_field){
						$type = $pk_field['type'];
						$id = $pk_field['id'];
						$field = array_merge($field, $pk_field);

						if(isset($workreap_plans_values[$key][$id])){
							$field['value'] = $workreap_plans_values[$key][$id];
						} else {
							$field['value'] = isset($pk_field['default_value']) ? $pk_field['default_value'] : '';
						}

						$field['disabled']	= $disabled_field;

						$field = apply_filters('workreap_product_plan_field', $field);

						switch ($type) {
							case "text":
								echo do_shortcode($render_fields->text_field($field));
							  	break;
							case "number":
								echo do_shortcode($render_fields->number_field($field));
							  	break;
							case "email":
								echo do_shortcode($render_fields->email_field($field));
							  	break;
							case "textarea":
								echo do_shortcode($render_fields->textarea_field($field));
	 						  	break;
							case "terms_dropdwon":
								echo do_shortcode($render_fields->terms_dropdwon($field));
	 						  	break;
							case "featured_package":
								echo do_shortcode($render_fields->featured_package($field));
									break;
							case "select":
								echo do_shortcode($render_fields->select_field($field));
									break;
							default:
								echo do_shortcode($render_fields->text_field($field));
						}

						echo do_action('plan_packages_extra_fields', $field, $plan_pkgs);
					}
					?>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Render product plan ACF dynamic fields fields hook
	 *
	 * @since    1.0.0
	 * @access   public
	*/
	public function workreap_acf_dynamically_render_fields($groups, $workreap_plans, $workreap_plans_values, $product_plans_category, $task_plans_allowed=true){
		$render_fields = new Workreap_Render_Fields();
		$html_render_fields = '';
		$label_class = 'wr-hide-acf-label';
		$package_counter	= 0;


		foreach($groups as $group){
			$group_ID = $group['ID'];
			$group_name = $group['name'];
			$group_key = $group['key'];

			if(!empty($group['sub_fields']) && is_array($group['sub_fields']) && count($group['sub_fields'])>0){

				foreach($group['sub_fields'] as $pkg_field){
					$type = $pkg_field['type'];
					$html_render_fields .= '<li id="'.$group_key.'" class="wr-pricing-'.$type.' '.$product_plans_category.' '.$group_name.'"><div class="wr-pricingitems__content">';

					$field = $pkg_field;

					$type = $pkg_field['type'];

					if(isset($field['wrapper']['class'])){
						$field['wrapper']['class'] .= ' '.$product_plans_category;
					} else {
						$wrapper = array('class'=>$product_plans_category);
						$field['wrapper'] = $wrapper;
					}

					$field['id'] = $id = $pkg_field['key'];
					$field['label_class'] = $label_class;

					$field['choices'] = !empty($pkg_field['choices']) ? $pkg_field['choices'] : array();
					$field['value'] = $pkg_field['default_value'];
					$html_render_fields .= ' <div class="wr-pricingtitle"><h6>'.$pkg_field['label'].' <sup class="astrik_red">*</sup>:</h6></div>';

					foreach($workreap_plans as $key=>$pkgs){
						$field['base'] = 'plans';
						$field['plan'] = $key;
						
						if(isset($workreap_plans_values[$key][$id])){
							$field['value'] = $workreap_plans_values[$key][$id];
						} else {
							$field['value'] = $field['default_value'];
						}

						switch ($type) {
							case "text":
								$html_render_fields .= $render_fields->text_field($field);
								break;
							case "email":
								$html_render_fields .= $render_fields->email_field($field);
								break;
							case "number":
								$html_render_fields .= $render_fields->number_field($field);
								break;
							case "textarea":
								$html_render_fields .= $render_fields->textarea_field($field);
								break;
							case "checkbox":
								$html_render_fields .= $render_fields->checkbox_field($field);
								break;
							case "radio":
								$html_render_fields .= $render_fields->radio_field($field);
								break;
							case "select":
								$html_render_fields .= $render_fields->select_field($field);
								break;
							default:
								$html_render_fields .= $render_fields->text_field($field);
								break;
						}

						$html_render_fields .= do_action('plan_packages_acf_extra_fields', $field, $groups);
					}

					$html_render_fields .= '</div></li>';
				}

			} else {

				$html_render_fields .= '<li id="'.$group_key.'" class="wr-pricing-input '.$product_plans_category.' '.$group_name.'"><div class="wr-pricingitems__content">';
				$field = array();
				$field = $group;
				$type = $group['type'];
				$field['label_class'] = $label_class;
				$field['id'] = $id = $group['key'];
				$field['choices'] = !empty($group['choices']) ? $group['choices'] : array();
				$field['value']	= '';
				$field['value'] = $field['default_value'];
				$html_render_fields .= '<div class="wr-pricingtitle"><h6>'.$group['label'].':</h6></div>';
				$package_counter	= 0;
				foreach($workreap_plans as $key=>$pkgs){
					$field['base'] = 'plans';
					$field['plan'] = $key;
					
					if(isset($workreap_plans_values[$key][$id])){
						$field['value'] = $workreap_plans_values[$key][$id];
					} else {
						$field['value'] = $field['default_value'];
					}

					switch ($type) {
						case "text":
							$html_render_fields .= $render_fields->text_field($field);
							break;
						case "email":
							$html_render_fields .= $render_fields->email_field($field);
							break;
						case "number":
							$html_render_fields .= $render_fields->number_field($field);
							break;
						case "textarea":
							$html_render_fields .= $render_fields->textarea_field($field);
							break;
						case "checkbox":
							$html_render_fields .= $render_fields->checkbox_field($field);
							break;
						case "radio":
							$html_render_fields .= $render_fields->radio_field($field);
							break;
						case "select":
							$html_render_fields .= $render_fields->select_field($field);
							break;
						default:
							$html_render_fields .= $render_fields->text_field($field);
							break;
					}

					$html_render_fields .= do_action('plan_packages_acf_extra_fields', $field, $groups);
				}

				$html_render_fields .= '</div></li>';
			}
		}

		echo do_shortcode($html_render_fields);
	}

	/**
	 * ACF render field
	 *
	 * @since    1.0.0
	 * @access   public
	*/
	public function acf_render_field($field){
		$field = apply_filters( "acf/prepare_field", $field );
		add_filter( "acf/prepare_field", array($this, 'prepare_field') );
		acf_render_field_wrap( $field, $el='div', $instruction='label' );
		remove_filter( "acf/prepare_field", array($this, 'prepare_field') );
	}

	/**
	 * ACF render field name
	 *
	 * @since    1.0.0
	 * @access   public
	*/
	public function prepare_field($field){
		$field_name = '';
		if(!empty($field['base'])){

			if(!empty($field['base'])){
				$field_name .= $field['base'];
			}

			if(!empty($field['plan'])){
				$field_name .= '['.$field['plan'].']';
			}

			if(!empty($field['key']) && !empty($field_name)){
				$field_name .= '['.$field['key'].']';
			} else {
				$field_name = $field['id'];
			}

			$field['name'] = $field_name;
		}
		return $field;
	}

	/**
	 * ACF Render the wrapping element for a given field.
	 *
	 * @since    1.0.0
	 * @param	array $field The field array.
	 * @param	string $element The wrapping element type.
	 * @param	string $instruction The instruction render position (label|field).
	 * @return	void
	 */
	public function acf_render_field_wrap( $field, $element = 'div', $instruction = 'label' ) {
		$field = acf_validate_field( $field );
		$field = acf_prepare_field( $field );

		if( !$field ) {
			return;
		}
		$elements = array(
			'div'	=> 'div',
			'tr'	=> 'td',
			'td'	=> 'div',
			'ul'	=> 'li',
			'ol'	=> 'li',
			'li'	=> 'label',
			'dl'	=> 'dt',
		);

		if( isset($elements[$element]) ) {
			$inner_element = $elements[$element];
		} else {
			$element = $inner_element = 'div';
		}
		$wrapper = array(
			'id'		=> '',
			'class'		=> 'acf-field',
			'width'		=> '',
			'style'		=> '',
			'data-name'	=> $field['_name'],
			'data-type'	=> $field['type'],
			'data-key'	=> $field['key'],
		);
		$wrapper['class'] .= " acf-field-{$field['type']}";

		if( $field['key'] ) {
			$wrapper['class'] .= " acf-field-{$field['key']}";
		}

		if( $field['required'] ) {
			$wrapper['class'] .= ' is-required';
			$wrapper['data-required'] = 1;
		}

		$wrapper['class'] = str_replace( '_', '-', $wrapper['class'] );
		$wrapper['class'] = str_replace( 'field-field-', 'field-', $wrapper['class'] );

		if( $field['wrapper'] ) {
			$wrapper = acf_merge_attributes( $wrapper, $field['wrapper'] );
		}

		$width = acf_extract_var( $wrapper, 'width' );
		if( $width ) {
			$width = acf_numval( $width );
			if( $element !== 'tr' && $element !== 'td' ) {
				$wrapper['data-width'] = $width;
				$wrapper['style'] .= " width:{$width}%;";
			}
		}


		$wrapper = array_map( 'trim', $wrapper );
		$wrapper = array_filter( $wrapper );

		$wrapper = apply_filters( 'acf/field_wrapper_attributes', $wrapper, $field );

		if( !empty($field['conditional_logic']) ) {
			$wrapper['data-conditions'] = $field['conditional_logic'];
		}
		if( !empty($field['conditions']) ) {
			$wrapper['data-conditions'] = $field['conditions'];
		}


		$attributes_html = acf_esc_attr( $wrapper );

		echo "<$element $attributes_html>" . "\n";
			if( $element !== 'td' ) {
				echo "<$inner_element class=\"acf-label\">" . "\n";
					acf_render_field_label( $field );
					if( $instruction == 'label' ) {
						acf_render_field_instructions( $field );
					}
				echo "</$inner_element>" . "\n";
			}
			echo "<$inner_element class=\"acf-input\">" . "\n";
				acf_render_field( $field );
				if( $instruction == 'field' ) {
					acf_render_field_instructions( $field );
				}
			echo "</$inner_element>" . "\n";
		echo "</$element>" . "\n";
	}

	/**
	 * Manage user columns
	 *
	 * @since    1.0.0
	 * @return	array
	*/
	public function workreap_manage_user_columns($column) {
		$column['wr_varifiled']		= esc_html__('Verification', 'workreap');
		$column['linked_profile']	= esc_html__('Linked profile', 'workreap');
		return $column;
	}

	/**
	 * Manage users rows columns admin
	 *
	 * @since    1.0.0
	 * @return	string
	 */
	public function workreap_manage_user_column_row($val, $column_name, $user_id) {
		global $workreap_settings;
		switch ($column_name) {
		case 'linked_profile' :
			$linked_profile 		= get_user_meta($user_id, '_linked_profile',true);
			$linked_profile_employer 	= get_user_meta($user_id, '_linked_profile_employer', true);

			$linked_profile 		= !empty($linked_profile) ? intval($linked_profile) : '';
			$linked_profile_employer 		= !empty($linked_profile_employer) ? intval($linked_profile_employer) : '';

			if(!empty($linked_profile) && !empty(get_post_status( $linked_profile ))){
				$val .= "<a class='generate-linked-data ".get_post_status( intval($linked_profile) )." data-post-id-".$linked_profile."' title='".esc_html__('Freelancer profile','workreap')."' href=".get_edit_post_link($linked_profile).">".esc_html__('Freelancer: ').' '.workreap_get_username($linked_profile) ."</a>";
			}else{
				$user_meta	= get_userdata($user_id);
				if ( in_array( 'subscriber', (array) $user_meta->roles ) ) {
					$val .= "<a title='".esc_html__('Generate freelancer profile','workreap')."' data-profile_type='freelancers' class='data-user-id-".$user_id." generate-linked-data generate-and-link' data-id='".$user_id."' href='#'>".esc_html__('Generate freelancer profile','workreap')."</a>";
				}
			}

			if(!empty($linked_profile_employer) && !empty(get_post_status( $linked_profile_employer ))){
				$val .= "<a class='generate-linked-data data-post-id-".$linked_profile_employer."' title='".esc_html__('Employer profile','workreap')."' href=".get_edit_post_link($linked_profile_employer).">".esc_html__('Employer: ').' '.workreap_get_username($linked_profile_employer) ."</a>";
			}else{
				$user_meta	= get_userdata($user_id);
				if ( in_array( 'subscriber', (array) $user_meta->roles ) ) {
					$val .= "<a  title='".esc_html__('Generate employer profile','workreap')."' data-profile_type='employers' class='data-user-id-".$user_id."  generate-linked-data generate-and-link' data-id='".$user_id."' href='#'>".esc_html__('Generate employer profile','workreap')."</a>";
				}
			}
			
			return $val;
			break;
		case 'wr_varifiled' :
			$linked_profile	= workreap_get_linked_profile_id($user_id);
			$is_verified 		= get_post_meta($linked_profile, '_is_verified',true);
			$linked_profile 	= get_post_meta($linked_profile, 'linked_profile',true);

			//for admin only
			$user_meta	= get_userdata($user_id);

			if ( in_array( 'administrator', (array) $user_meta->roles ) ) {
				return;
			}
			$identity_verification	= !empty($workreap_settings['identity_verification']) ? $workreap_settings['identity_verification'] : false;
			$approve_image 			= workreap_add_http_protcol(WORKREAP_DIRECTORY_URI . 'public/images/email_verified_users.png');
			$reject_image 			= workreap_add_http_protcol(WORKREAP_DIRECTORY_URI . 'public/images/email_verified_users.png');
			$verification 			= workreap_add_http_protcol(WORKREAP_DIRECTORY_URI . 'public/images/identity_verification.svg');
			$status					= (isset($is_verified) && $is_verified === 'yes') ? 'reject' : 'approve';
			$status_text			= (isset($is_verified) && $is_verified === 'yes') ? esc_html__('Reject','workreap') : esc_html__('Approve','workreap');

			$val .= "<a title='".ucfirst($status).' '.esc_html__('user','workreap')."'
						class='do_verify_user_confirm dashicons-before " . (!empty($is_verified) && $is_verified === 'yes' ? 'wr-icon-color-green' : 'wr-icon-color-red') . "'
						data-type='".esc_attr($status)."'
						data-id='".intval( $linked_profile )."'
						data-user_id='".intval( $user_id )."'
						href='javascript:void(0);'>
						<span class='dashicons dashicons-admin-users woocommerce-help-tip' data-tip='".esc_attr($status_text)."'></span>
					</a>";
			$val .= '<div id="approve-user-confirm-'.intval( $user_id ).'" class="wr-approve-user" style="display:none;">';
				$val .= '<h4>'.wp_sprintf('%s %s',esc_html__('Are you sure you want to %s user?', 'workreap'), $status).'</h4>';
					$val .= '<div class="wr-action-links">';
						$val .= "<a title='".esc_html__('Approve user','workreap')."'
									class='do_verify_user dashicons-before " . (!empty($is_verified) && $is_verified === 'yes' ? 'wr-icon-color-green' : 'wr-icon-color-red') . "'
									data-type='".esc_attr($status)."'
									data-id='".intval( $linked_profile )."'
									data-user_id='".intval( $user_id )."'
									href='javascript:void(0);'>
										<span class='dashicons dashicons-admin-users woocommerce-help-tip' data-tip='".esc_attr($status_text)."'></span>
										
								</a>";
					$val .= '</div>';
				$val .= '</div>';
			$val .= '</div>';

			if ( in_array( 'subscriber', (array) $user_meta->roles ) ) {
				if(!empty($identity_verification) ){
					$identity_verified  		= get_user_meta($user_id, 'identity_verified', true);
					$verification_attachments   = get_user_meta($user_id, 'verification_attachments', true);
					$identity_status			= !empty($identity_verified) ? 'approved' : 'inprogress';

					$val .= "<a title='".esc_html__('Identity verification','workreap')."' class='do_verify_identity dashicons-before " . ((!empty($identity_verified) ) ? 'wr-icon-color-green' : 'wr-icon-color-red') . " ' data-type='".$identity_status."' data-id='".intval( $user_id )."' href='#' ><span class='dashicons dashicons-id'></span></a>";
					
					if(!empty($verification_attachments)){
						$val .= "<a title='".esc_html__('View user identity verification','workreap')."' data-user='".json_encode($user_id)."' class='do_download_identity' href='#'><span class='dashicons dashicons-visibility'></span></a>";
					}
				}
			}

			return $val;

			break;
		default:
		}
	}

	/**
	 * Update earning
	 *
	 * @throws error
	 * @author Amentotech <theamentotech@gmail.com>
	 * @return
	 */
	public function workreap_update_admin_notification() {
		$type    = !empty($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
		$json               = array();
		$json['message']    = esc_html__('Notification','workreap');
		if( !empty($type) && $type === 'guppy_notification'){
			update_option( 'wr_guppy_notification', 1 );
			$json['type']           = 'success';
			$json['message']   = esc_html__('Woohoo!', 'workreap');
			$json['message_desc']   = esc_html__('You have successfully remove WP Guppy plugin activation notification', 'workreap');
			wp_send_json( $json );
		} else {
			$json['type']           = 'error';
			$json['message']   = esc_html__('Oops!', 'workreap');
			$json['message_desc']   = esc_html__('You are not allowd to perfom this action', 'workreap');
			wp_send_json( $json );
		}
	}
		

	/**
	 * Approve and disapprove users
	 *
	 * @since    1.0.0
	 * @return	void
	 */
	public function workreap_approve_profile(){

		$json = array();
		//security check
		if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Oops!', 'workreap');
			$json['message_desc'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}

		// get post data
		$user_profile_id	= !empty( $_POST['id'] ) ? intval($_POST['id']) : '';
		$type				= !empty( $_POST['type'] ) ? sanitize_text_field($_POST['type']) : '';
		$user_id			= !empty( $_POST['user_id'] ) ? intval($_POST['user_id']) : '';

		// validate post data
		if ( empty( $type ) || empty( $user_id ) ){
			$json['type']    		= 'error';
			$json['message'] 		= esc_html__('Oops!', 'workreap');
			$json['message_desc'] 	= esc_html__('Some data has been lost, please try again', 'workreap');
			wp_send_json($json);
		}

		// validate user
		$user_meta	= get_userdata($user_id);
		if( empty( $user_meta ) ){
			$json['type'] = 'error';
			$json['message'] = esc_html__('Oops!', 'workreap');
			$json['message_desc'] = esc_html__('User not exists', 'workreap');
			wp_send_json($json);
		}

		$is_user_verify = ($type == 'reject' ? 'no' : 'yes');
		update_user_meta($user_id, '_is_verified', $is_user_verify);
		update_user_meta($user_id, 'confirmation_key', '');

		$linked_employer_profile_id   = get_user_meta($user_id, '_linked_profile_employer', true);
		$linked_freelancer_profile_id  = get_user_meta($user_id, '_linked_profile', true);
		$notifyData						= array();
		$notifyDetails					= array();
		$notifyData['receiver_id']		= $user_id;
		if(!empty($type) && $type == 'reject'){
			$notifyData['type']				= 'reject_account_request';
		} else {
			$notifyData['type']				= 'approved_account_request';
		}
		
		$notifyData['post_data']		= $notifyDetails;
		if (!empty($linked_employer_profile_id)){
			update_post_meta($linked_employer_profile_id, '_is_verified', $is_user_verify);
			$notifyData['linked_profile']	= $linked_employer_profile_id;
			$notifyData['user_type']		= 'employers';
		}

		if (!empty($linked_freelancer_profile_id)){
			update_post_meta($linked_freelancer_profile_id, '_is_verified', $is_user_verify);
			$notifyData['linked_profile']	= $linked_freelancer_profile_id;
			$notifyData['user_type']		= 'freelancers';
		}
		do_action('workreap_notification_message', $notifyData );
		
		$full_name  = !empty($user_meta->display_name) ? $user_meta->display_name : 'Subscriber';
		$email      = !empty($user_meta->user_email) ? $user_meta->user_email : '';

		//Send email
		if (class_exists('Workreap_Email_helper') && $is_user_verify == 'yes') {
			$blogname           = get_option('blogname');
			$emailData          = array();
			$emailData['name']  = $full_name;
			$emailData['email'] = $email;
			$emailData['site']  = $blogname;
			if (class_exists('WorkreapRegistrationStatuses')) {
				$email_helper = new WorkreapRegistrationStatuses();
				$email_helper->registration_account_approved_request($emailData);
			}

		}

		$current_state = ($type == 'reject' ? esc_html__('unverified', 'workreap') : esc_html__('approved', 'workreap'));
		$json['type'] 			= 'success';
		$json['message']		= esc_html__('Woohoo!', 'workreap');
		$json['message_desc']	= wp_sprintf(esc_html__('Account has been %s and email has been sent to user.', 'workreap'), $current_state);
		wp_send_json($json);
	}
}
new Workreap_Admin_Hooks();