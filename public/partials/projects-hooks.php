<?php

/**
 * Provide a project hooks
 *
 * This file is used to markup the project aspects of the plugin.
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap/public/partials
 */
if (!class_exists('WorkreapProjectFunctions')) {
    class WorkreapProjectFunctions
    {
        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         * @param      string    $workreap      The name of the plugin.
         * @param      string    $version    The version of this plugin.
         */
        public function __construct()
        {
            add_action('workreap_project_sidebar', array($this, 'workreap_project_sidebar'), 10, 2);
            add_action('workreap_freelancer_invitation', array($this, 'workreap_freelancer_invitation'), 10, 2);
            add_action('workreap_custom_taxonomy_dropdown', array($this, 'workreap_custom_taxonomy_dropdown'), 10, 2);
            add_action('workreap_country_dropdown', array($this, 'workreap_country_dropdown_html'), 10, 2);
            add_action('wp_ajax_workreap_save_project', array($this, 'workreap_save_project'));
            add_action('wp_ajax_workreap_project_invitation', array($this, 'workreap_project_invitation'));
            add_action('wp_ajax_workreap_project_hiring', array($this, 'workreap_project_hiring'));
            add_action('wp_ajax_workreap_project_featured', array($this, 'workreap_project_featured'));
            add_action('wp_ajax_workreap_remove_project', array($this, 'workreap_remove_project'));
        }

        /**
         * Remove project
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_remove_project()
        {
            global $current_user;
            $json               = array();
            if (function_exists('workreap_is_demo_site')) {
                workreap_is_demo_site();
            }
            if (function_exists('workreap_verify_token')) {
                workreap_verify_token($_POST['security']);
            }

            workreapRemoveProject($current_user->ID, $_POST);
        }

        /**
         * Project featured
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_project_featured()
        {
            global $current_user;
            $json               = array();
            if (function_exists('workreap_is_demo_site')) {
                workreap_is_demo_site();
            }
            if (function_exists('workreap_verify_token')) {
                workreap_verify_token($_POST['security']);
            }

            workreapProjectFeatured($current_user->ID, $_POST);
        }

        /**
         * Project hiring
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_project_hiring()
        {
            global $current_user;
            $json               = array();
            if (function_exists('workreap_is_demo_site')) {
                workreap_is_demo_site();
            }
            if (function_exists('workreap_verify_token')) {
                workreap_verify_token($_POST['security']);
            }
            $key            = !empty($_POST['key']) ? sanitize_text_field($_POST['key']) : 0;
            $wallet         = !empty($_POST['wallet']) ? sanitize_text_field($_POST['wallet']) : '';
            $proposal_id    = !empty($_POST['id']) ? intval($_POST['id']) : 0;
            workreapProjectHiring($current_user->ID, $proposal_id, $wallet, $key);
        }



        /**
         * Project invitation
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_project_invitation()
        {
            global $current_user;
            $json               = array();
            if (function_exists('workreap_is_demo_site')) {
                workreap_is_demo_site();
            }
            if (function_exists('workreap_verify_token')) {
                workreap_verify_token($_POST['security']);
            }
            $profile_id = !empty($_POST['profile_id']) ? intval($_POST['profile_id']) : 0;
            $project_id = !empty($_POST['project_id']) ? intval($_POST['project_id']) : 0;
            workreapInvitationProject($project_id, $profile_id, $current_user->ID);
        }

        /**
         * Save project
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_save_project()
        {
            global $current_user;
            $json               = array();
            if (function_exists('workreap_is_demo_site')) {
                workreap_is_demo_site();
            }
            if (function_exists('workreap_verify_token')) {
                workreap_verify_token($_POST['security']);
            }

            if (!empty($current_user->ID)){
                $get_user_type	= apply_filters('workreap_get_user_type', $current_user->ID );
                if (!empty($get_user_type) && ($get_user_type == 'freelancers' || $get_user_type == 'employers')){
                    $profile_id  = workreap_get_linked_profile_id($current_user->ID);
                    do_action('workreap_check_user_account_status', $profile_id); //check if user is not blocked or deactive
                    do_action('workreap_check_user_account_identity', $current_user->ID);
                } else {
                    $json['type']           = 'error';
	                $json['message']           = esc_html__('Oops!','workreap');
                    $json['message_desc']   = esc_html__('You are not allowed to perform this action', 'workreap');
                    wp_send_json($json);
                }
            } else{
                $json['type']           = 'error';
                $json['message']           = esc_html__('Oops!','workreap');
                $json['message_desc']   = esc_html__('You must logged in to perform this action.', 'workreap');
                wp_send_json($json);
            }
            $data       = !empty($_POST['data']) ? $_POST['data'] : '';
            parse_str($data, $data);
            $data['step_id']        = !empty($_POST['step_id']) ? intval($_POST['step_id']) : 0;
            $data['project_id']     = !empty($_POST['project_id']) ? intval($_POST['project_id']) : 0;
            workreapSaveProjectData($data);
        }
        /**
         * Project sidebar
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_country_dropdown_html($selected_country = '', $name = 'country')
        {
            $countries  = array();
            if (class_exists('WooCommerce')) {
                $countries_obj   = new WC_Countries();
                $countries   = $countries_obj->get_allowed_countries('countries');
            }
            ob_start();
?>
            <select id="task_location" class="wr-select-cat" name="<?php echo esc_attr($name); ?>" data-placeholderinput="<?php esc_attr_e('Search country', 'workreap'); ?>" data-placeholder="<?php esc_attr_e('Choose country', 'workreap'); ?>">
                <option value="" selected hidden disabled><?php esc_html_e('Choose country', 'workreap'); ?></option>
                <?php if (!empty($countries)) {
                    foreach ($countries as $key => $item) {
                        $selected = '';
                        if (!empty($selected_country) && $selected_country === $key) {
                            $selected = 'selected';
                        }
                ?>
                        <option <?php echo esc_attr($selected); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_html($item); ?></option>
                <?php }
                } ?>
            </select>
        <?php
            echo ob_get_clean();
        }

        /**
         * Project sidebar
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_project_sidebar($step_id = '', $post_id = '')
        {
            global $current_user;
            $tabs           = workreap_list_project_steps($step_id, $post_id);
            $dashboad_page  = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $current_user->ID, true, 'listing');;
            ob_start();
        ?>
            <div class="col-xl-3 col-lg-4 ">
                <aside class="wr-status-holder">
                    <ul class="wr-status-tabs">
                        <?php
                        foreach ($tabs as $key => $value) {
                            $title          = !empty($value['title']) ? $value['title'] : '';
                            $details        = !empty($value['details']) ? $value['details'] : '';
                            $active_class   = !empty($step_id) && $step_id == $key ? 'wr-current-status' : "";
                            if (!empty($step_id) && $step_id > intval($key)) {
                                $page_link  = workreap_get_page_uri('add_project_page') . '?step=' . intval($key) . '&post_id=' . intval($post_id);
                                $details    = '<a href="' . esc_url($page_link) . '">' . esc_attr__('Edit details', 'workreap') . '</a>';
                                $active_class   = $active_class . ' wr-complete-status';
                            }
                        ?>
                            <li class="<?php echo esc_attr($active_class); ?>">
                                <div class="wr-status-tabs_content">
                                    <?php if (!empty($title)) { ?>
                                        <h6><?php echo esc_html($title); ?></h6>
                                    <?php } ?>
                                    <?php if (!empty($details)) { ?>
                                        <p><?php echo do_shortcode($details); ?></p>
                                    <?php } ?>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                    <?php if (!empty($step_id) && $step_id == '4') { ?>
                        <a href="<?php echo esc_url($dashboad_page); ?>" class="wr-btn-solid-lg-lefticon"><?php esc_html_e('Go to project listing', 'workreap'); ?><i class="wr-icon-chevron-right"></i></a>
                    <?php } ?>
                </aside>
            </div>
        <?php
            echo ob_get_clean();
        }

        /**
         * Tasks user ratings
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_freelancer_invitation($project_id = 0, $profile_id = 0)
        {
            $project_meta           = !empty($project_id) ? get_post_meta($project_id, 'wr_project_meta', true) : array();
            $project_meta           = !empty($project_meta) ? $project_meta : array();
            $invitated_freelancers  = !empty($project_meta['invitation']) ? $project_meta['invitation'] : array();
            $bid_class              = !empty($invitated_freelancers) && is_array($invitated_freelancers) && isset($invitated_freelancers[$profile_id]) ? 'wr-invite-sent' : 'wr-invite-bid wr-invite-bidbtn';
            $bid_text               = !empty($invitated_freelancers) && is_array($invitated_freelancers) && isset($invitated_freelancers[$profile_id]) ? esc_html__('Invitation sent', 'workreap') : esc_html__('Invite to bid', 'workreap');
            ob_start();
        ?>
            <div class="wr-bidbtn">
                <a href="<?php echo esc_url(get_permalink($profile_id)); ?>"><?php esc_html_e('View profile', 'workreap'); ?></a>
                <span class="wr-btn-bit <?php echo esc_attr($bid_class); ?>" data-profile_id="<?php echo intval($profile_id); ?>" data-project_id="<?php echo intval($project_id); ?>"><?php echo esc_html($bid_text); ?></span>
            </div>
        <?php
            echo ob_get_clean();
        }
        /**
         * Project sidebar
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_custom_taxonomy_dropdown($term_arg = array(), $post_id = '')
        {
            $args       = array(
                'taxonomy'      => !empty($term_arg['taxonomy']) ? $term_arg['taxonomy'] : '',
                'orderby'       => !empty($term_arg['orderby']) ? $term_arg['orderby'] : 'name',
                'hide_empty'    => false
            );
            $taxonomies     = get_terms($args);
            $name           = !empty($term_arg['name']) ? $term_arg['name'] : '';
            $class          = !empty($term_arg['class']) ? $term_arg['class'] : '';
            ob_start();
        ?>
            <select name="<?php echo esc_attr($name); ?>" class="<?php echo esc_attr($class); ?> wr-select2-cat" multiple="multiple">
                <?php foreach ($taxonomies as $key => $taxonomy) {
                    $selected   = '';
                    if (!empty($term_arg['selected']) && is_array($term_arg['selected'])) {
                        $selected   = in_array($taxonomy->term_id, $term_arg['selected']) ? 'selected'  : '';
                    }
                ?>
                    <option value="<?php echo intval($taxonomy->term_id); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_html($taxonomy->name); ?></option>
                <?php } ?>
            </select>
        <?php
            echo ob_get_clean();
        }
    }
    new WorkreapProjectFunctions();
}

/**
 * List project steps
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_list_project_steps')) {
    function workreap_list_project_steps($step_id = '', $post_id = '')
    {
        $lists  = array(
            '2' => array(
                'title'     => esc_html__('About project', 'workreap'),
                'details'   => esc_html__('Add details to your project that explain well to all freealncers', 'workreap')
            ),
            '3' => array(
                'title'     => esc_html__('Freelancer preferences', 'workreap'),
                'details'   => esc_html__('Select which skills you want in your freelancer', 'workreap')
            ),
            '4' => array(
                'title'     => esc_html__('Recommended freelancers', 'workreap'),
                'details'   => esc_html__('Hire best match for your project', 'workreap')
            )
        );
        $lists  = apply_filters('workreap_filter_list_project_steps', $lists);
        return $lists;
    }
}

/**
 * List project type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_project_type')) {
    function workreap_project_type($type = '')
    {
        $lists  = array(
            'fixed' => array(
                'title'     => esc_html__('Fixed', 'workreap'),
                'details'   => esc_html__('Pay freelancer on fixed milestone rate', 'workreap'),
                'icon'      => 'wr-icon-copy wr-red-icon'
            )
        );

        $lists  = apply_filters('workreap_filter_project_type', $lists);
        if (!empty($type)) {
            $lists   = !empty($lists[$type]) ? $lists[$type] : array();
        }
        return $lists;
    }
}

/**
 * List project location type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_project_location_type')) {
    function workreap_project_location_type($key = '')
    {
        global $workreap_settings;
        $lists  = array(
            'remote'                    => esc_html__('Remote', 'workreap'),
            'partially_remote'          => esc_html__('Partially remote', 'workreap'),
            'location'                  => esc_html__('Onsite', 'workreap'),
        );

        $lists  = apply_filters('workreap_filter_project_location_type', $lists);
        if (!empty($key)) {
            $lists  = !empty($lists[$key]) ? $lists[$key] : '';
        }
        return $lists;
    }
}

/**
 * List project type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_project_recomended_freelancers')) {
    function workreap_project_recomended_freelancers($step_id = '', $post_id = '')
    {
        $lists  = array(
            'skills'                => esc_html__('Skills', 'workreap'),
            'languages'             => esc_html__('Languages', 'workreap'),
        );
        $lists  = apply_filters('workreap_filter_project_recomended_freelancers', $lists);
        return $lists;
    }
}

/**
 * List project type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreapProjectValidations')) {
    function workreapProjectValidations($step_number = '', $key = '')
    {
        $lists  = array(
            2 => array(
                'title'     => esc_html__('Project step 2 validation', 'workreap'),
                'subtitle'   => esc_html__('Select required fields for project creation step 2.', 'workreap'),
                'fields'    => array(
                    'duration'      => esc_html__('Please select duration', 'workreap'),
                    'categories'    => esc_html__('Please select category', 'workreap'),
                    'details'       => esc_html__('Project details is required', 'workreap'),
                ),
                'default'    => array('details', 'categories')
            ),
            3 => array(
                'title'     => esc_html__('Project step 3 validation', 'workreap'),
                'subtitle'   => esc_html__('Select required fields for project creation step 3.', 'workreap'),
                'fields'    => array(
                    'expertise_level'       => esc_html__('Please select expertise level', 'workreap'),
                    'skills'                => esc_html__('Please select skills', 'workreap'),
                    'languages'             => esc_html__('Please select languages', 'workreap'),
                ),
                'default'    => array('skills', 'expertise_level')
            )
        );
        $lists  = apply_filters('workreap_filter_projectValidations', $lists);
        if (!empty($step_number) && !empty($key)) {
            $lists  = !empty($lists[$step_number]['fields'][$key]) ? $lists[$step_number]['fields'][$key] : '';
        }
        return $lists;
    }
}

/**
 * Remove project
 *
 */
if (!function_exists('workreapRemoveProject')) {
    function workreapRemoveProject($user_id = 0, $data = array(), $option_type = '')
    {
        $json                   = array();
        $json['type']           = 'error';
	    $json['message']           = esc_html__('Oops!','workreap');
        $json['message_desc']   = esc_html__('You are not allowed to perform this action', 'workreap');

        if (!empty($data['id'])) {
            $project_id     = !empty($data['id']) ? intval($data['id']) : 0;
            $post_status    = !empty($project_id) ? get_post_status($project_id) : "";
            $post_author    = get_post_field('post_author', $project_id);

            if (!empty($post_author) && $post_author == $user_id && in_array($post_status, array('draft', 'pending', 'publish'))) {
                wp_delete_post($project_id, true);

                //Delete Proposals
                $workreap_args = array(
                    'post_type'         => 'proposals',
                    'post_status'       => array('publish','hired','cancelled','rejected','completed','disputed','refunded'),
                    'posts_per_page'    => -1,
                );

                $workreap_args['meta_query'] = array(
                    array(
                        'key'       => 'project_id',
                        'value'     => $project_id,
                        'compare'   => '=',
                        'type'      => 'NUMERIC',
                    )
                );

                $workreap_query      = get_posts($workreap_args);
                if(!empty($workreap_query)){
                    foreach($workreap_query as $key => $proposal){
                        wp_delete_post($proposal->ID, true);
                    }
                }

                $json['type']           = 'success';
	            $json['message']        = esc_html__( 'Deleted successfully', 'workreap' );
                $json['message_desc']   = esc_html__('Project has been deleted successfully', 'workreap');

                if (empty($option_type)) {
                    wp_send_json($json);
                } else {
                    return $json;
                }
            } else {
                if (empty($option_type)) {
                    wp_send_json($json);
                } else {
                    return $json;
                }
            }
        } else {
            if (empty($option_type)) {
                wp_send_json($json);
            } else {
                return $json;
            }
        }
    }
}

/**
 * Project Invitation
 *
 */
if (!function_exists('workreapProjectFeatured')) {
    function workreapProjectFeatured($user_id = 0, $data = array(), $option_type = '')
    {
        $json                   = array();
        $json['type']           = 'error';
	    $json['message']           = esc_html__('Oops!','workreap');
        $json['message_desc']   = esc_html__('You are not allowed to perform this action', 'workreap');
        if (!empty($data['id']) && !empty($data['value'])) {
            $value      = !empty($data['value']) ? $data['value'] : '';
            $id         = !empty($data['id']) ? intval($data['id']) : '';
            $product    = wc_get_product(absint($id));
            $is_featured    = false;
            if (!empty($value) && $value === 'no') {
                $product->set_featured(false);
                $product->save();
            } else if (!empty($value) && $value === 'yes') {
                $featured_option    = workreapCheckEmployerPackage($user_id, 'featured_projects_allowed', $option_type);
                if (!empty($featured_option)) {
                    $product->set_featured(true);
                    $product->save();
                    workreapUpdateEmployerPackage($user_id, 'featured_projects_allowed', $id);
                    $is_featured    = true;
                }
            }
            update_post_meta($id, '_featured_task', $value);
            update_post_meta($id, 'is_featured', $is_featured);
            $json                   = array();
            $json['type']           = 'success';
	        $json['message'] 		= esc_html__('Woohoo!', 'workreap');
            $json['message_desc']   = esc_html__('You have successfully update featured option for this project', 'workreap');
            if (empty($option_type)) {
                wp_send_json($json);
            } else {
                return $json;
            }
        } else {
            if (empty($option_type)) {
                wp_send_json($json);
            } else {
                return $json;
            }
        }
    }
}

/**
 * Project Invitation
 *
 */
if (!function_exists('workreapProjectHiring')) {
    function workreapProjectHiring($user_id = 0, $proposal_id = 0, $wallet = '', $key = '', $option_type = ''){
        global $current_user,$woocommerce,$workreap_settings;
        $admin_commision_employers     =  !empty($workreap_settings['admin_commision_employers']) ? $workreap_settings['admin_commision_employers'] : 0;
        $commission_text            =  !empty($workreap_settings['commission_text']) ? $workreap_settings['commission_text'] : esc_html__('Processing fee', 'workreap');

        $json                   = array();
        $json['type']           = 'error';
	    $json['message'] 		= esc_html__('Oops!', 'workreap');
        $json['message_desc']   = esc_html__('You are redirecting to the checkout page', 'workreap');

        if (!empty($user_id) && !empty($proposal_id)) {
            $project_id = get_post_meta($proposal_id, 'project_id', true);
            $project_id = !empty($project_id) ? intval($project_id) : 0;
            $post_author = get_post_field('post_author', $project_id);

            if (!empty($post_author) && $post_author != $user_id) {
                if (empty($type)) {
                    wp_send_json($json);
                }
            }

            $project_meta   = get_post_meta($project_id, 'wr_project_meta', true);
            $project_meta   = !empty($project_meta) ? $project_meta : array();
            $project_type   = !empty($project_meta['project_type']) ? $project_meta['project_type'] : '';
            $proposal_meta  = get_post_meta($proposal_id, 'proposal_meta', true);
            $proposal_meta  = !empty($proposal_meta) ? $proposal_meta : array();
            $price          = 0;
            $cart_meta                          = array();

            if (!empty($project_type) && $project_type === 'fixed') {

                if (!empty($proposal_meta['proposal_type']) && $proposal_meta['proposal_type'] === 'milestone') {
                    if (empty($key)) {
                        $json['message_desc']   = esc_html__('You are not allowed to perform this action', 'workreap');
                        if (empty($type)) {
                            wp_send_json($json);
                        }
                    } else {
                        $price                      = isset($proposal_meta['milestone'][$key]['price']) ? $proposal_meta['milestone'][$key]['price'] : 0;
                        $cart_meta['milestone_id']    = $key;
                    }
                } else if (!empty($proposal_meta['proposal_type']) && $proposal_meta['proposal_type'] === 'fixed') {
                    $price  = isset($proposal_meta['price']) ? $proposal_meta['price'] : 0;
                } else {
                    $price  = isset($proposal_meta['price']) ? $proposal_meta['price'] : 0;
                }

                if (class_exists('WooCommerce')) {
                    global $woocommerce;

                    if (!empty($option_type) && $option_type === 'mobile') {
                        check_prerequisites($user_id);
                    }

                    $woocommerce->cart->empty_cart();
                    $service_fee    = workreap_commission_fee($price);
                    $admin_shares   = !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.0;
                    $freelancer_shares  = !empty($service_fee['freelancer_shares']) ? $service_fee['freelancer_shares'] : $price;
                    $freelancer_id      = get_post_field('post_author', $proposal_id);
                    $user_balance   = !empty($user_id) ? get_user_meta($user_id, '_employer_balance', true) : '';
                    $product_id                         = workreap_employer_wallet_create();

                    if (!empty($wallet) && !empty($user_balance) && $user_balance < $price) {
                        $cart_meta['wallet_price']            = $user_balance;
                    }

                    $employer_service_fee		= workreap_processing_fee_calculation('projects',$price);

                    $cart_meta['hiring_product_id']     = $product_id;
                    $cart_meta['product_name']          = esc_html__('Hiring project', 'workreap');
                    $cart_meta['price']                 = $price;
                    $cart_meta['payment_type']          = 'projects';
                    $cart_meta['project_type']          = $project_type;
                    $cart_meta['employer_id']              = $user_id;
                    $cart_meta['freelancer_id']             = $freelancer_id;
                    $cart_meta['admin_shares']          = $admin_shares;
                    $cart_meta['freelancer_shares']         = $freelancer_shares;
                    $cart_meta['project_id']            = $project_id;
                    $cart_meta['proposal_id']           = $proposal_id;
                    $cart_meta['proposal_meta']         = $proposal_meta;
                    $cart_meta['processing_fee']	    = !empty( $employer_service_fee['commission_amount'] ) ? $employer_service_fee['commission_amount'] : 0.0;

                    $cart_data  = array(
                        'hiring_product_id'     => $product_id,
                        'cart_data'             => $cart_meta,
                        'project_type'          => $project_type,
                        'price'                 => $price,
                        'payment_type'          => 'projects',
                        'admin_shares'          => $admin_shares,
                        'freelancer_shares'         => $freelancer_shares,
                        'project_id'            => $project_id,
                        'proposal_id'           => $proposal_id,
                        'employer_id'              => $user_id,
                        'freelancer_id'             => $freelancer_id,
                    );

                    $woocommerce->cart->empty_cart();
                    $cart_item_data = apply_filters('workreap_project_hiring_cart_data', $cart_data);

                    WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);
                    if (!empty($wallet) && !empty($user_balance) && $user_balance >= $price) {
                        $order_id               = workreap_place_order($user_id, 'project-wallet');

                        if (!empty($option_type) && $option_type === 'mobile') {
                            $order_details  = !empty($order_id) ? get_post_meta($order_id, 'cus_woo_product_data', true) : array();
                            workreap_update_project_data($order_id, $order_details);
                            workreap_complete_order($order_id);
                        }

                        $json['checkout_url']    = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $user_id, true, 'activity', $proposal_id);
                        $json['order_id']       = $order_id;
                        $json['type']           = 'success';
	                    $json['message'] 		= esc_html__('Woohoo!', 'workreap');
                        $json['message_desc']   = esc_html__('You have successfully completed this order', 'workreap');

                        if (empty($option_type)) {
                            wp_send_json($json);
                        } else {
                            return $json;
                        }
                    } else if (!empty($option_type) && $option_type === 'mobile') {
                        $linked_profile_id  = workreap_get_linked_profile_id($user_id);
                        if (!empty($linked_profile_id) && !empty($cart_data)) {
                            update_post_meta($linked_profile_id, 'mobile_checkout_data', $cart_data);
                            $mobile_checkout    = workreap_get_page_uri('mobile_checkout');
                            if (!empty($mobile_checkout)) {
                                $json['type']           = 'success';
	                            $json['message'] 		= esc_html__('Woohoo!', 'workreap');
                                $json['message_desc']   = esc_html__('You have successfully completed this order', 'workreap');
                                $json['checkout_url']    = $mobile_checkout . '?post_id=' . $linked_profile_id;
                                return $json;
                            }
                        }
                    } else {
                        $json['checkout_url']       = wc_get_checkout_url();
                        $json['type']               = 'success';
                        if (empty($type)) {
                            wp_send_json($json);
                        }
                    }
                }
            } else {
                do_action('workreap_project_hiring_options', $user_id, $proposal_id, $wallet, $key, $option_type);
            }
        } else {
            if (empty($type)) {
                wp_send_json($json);
            }
        }
    }
}
/**
 * Project Invitation
 *
 */
if (!function_exists('workreapInvitationProject')) {
    function workreapInvitationProject($project_id = 0, $profile_id = 0, $user_id = 0, $type = '')
    {
        $json                   = array();
        $json['type']           = 'error';
	    $json['message']           = esc_html__('Oops!','workreap');
        $json['message_desc']   = esc_html__('You are not allowed to perform this action', 'workreap');
        if (!empty($profile_id) && !empty($project_id) && !empty($user_id)) {
            $post_author    = !empty($project_id) ? intval($project_id) : 0;
            if (!empty($post_author) && $project_id == $post_author) {
                $gmt_time                = current_time('mysql', 1);
                $project_status         = get_post_status($project_id);
                $post_status            = get_post_meta($project_id, '_post_project_status', true);
                $project_meta           = !empty($project_id) ? get_post_meta($project_id, 'wr_project_meta', true) : array();
                $update_invitation      = array(
                    'invitated_date'    => '',
                    'created_date'      => $gmt_time
                );
                $json['message_desc']   = esc_html__('Invitation sent', 'workreap');
                if (!empty($post_status) && in_array($post_status, array('requested', 'rejected', 'pending', 'publish')) && !empty($project_status) && in_array($project_status, array('draft', 'pending', 'rejected'))) {
                    $update_invitation['status']        = 'pending';
                    $json['type']                       = 'success';
	                $json['message'] 		= esc_html__('Woohoo!', 'workreap');
                } else if (!empty($post_status) && $post_status === 'publish' && !empty($project_status) && $project_status === 'publish') {
                    $update_invitation['status']            = 'publish';
                    $update_invitation['invitated_date']    = $gmt_time;
                    $json['type']                           = 'success';
	                $json['message'] 		= esc_html__('Woohoo!', 'workreap');
                    workreapFreelancerProjectInvitation($project_id, $profile_id);
                }
                $project_meta['invitation'][$profile_id] = $update_invitation;
                update_post_meta($project_id, 'wr_project_meta', $project_meta);
            }
        }
        if (empty($type)) {
            wp_send_json($json);
        } else {
            return $json;
        }
    }
}

/**
 * Freelancer notification for project invitation
 *
 */
function workreapFreelancerProjectInvitation($project_id = 0, $profile_id = 0)
{
    global $workreap_settings;
    $invitation_email_switch        = !empty($workreap_settings['email_project_invitation_freelancer']) ? $workreap_settings['email_project_invitation_freelancer'] : true;
    // Notification and email to employer for task publish
    $freelancer_id                          = get_post_field('post_author', $profile_id);
    $employer_id                           = get_post_field('post_author', $project_id);
    $employer_profile_id                   = !empty($employer_id) ? workreap_get_linked_profile_id($employer_id, '', 'employers') : '';
    $freelancer_profile_id                  = !empty($freelancer_id) ? workreap_get_linked_profile_id($freelancer_id, '', 'freelancers') : '';
    $notifyDetails                      = array();
    $notifyDetails['project_id']          = $project_id;
    $notifyDetails['employer_id']          = $employer_profile_id;
    $notifyData['post_data']            = $notifyDetails;
    $notifyData['type']                    = 'project_inviation';
    $notifyData['receiver_id']            = $freelancer_id;
    $notifyData['linked_profile']        = $profile_id;
    $notifyData['user_type']            = 'employers';
    do_action('workreap_notification_message', $notifyData);
    // Add project invitation Email
    if (class_exists('Workreap_Email_helper') && !empty($invitation_email_switch)) {
        $emailData                      = array();
        $emailData['freelancer_email']      = get_userdata($freelancer_id)->user_email;
        $emailData['employer_name']        = workreap_get_username($employer_profile_id);
        $emailData['freelancer_name']       = workreap_get_username($freelancer_profile_id);
        $emailData['project_title']     = get_the_title($project_id);
        $emailData['project_link']      = get_the_permalink($project_id);
        if (class_exists('WorkreapProjectCreation')) {
            $email_helper = new WorkreapProjectCreation();
            $email_helper->invitation_project_freelancer_email($emailData);
        }
    }
}

/**
 * Project update
 *
 */
if (!function_exists('workreapSaveProjectData')) {
    function workreapSaveProjectData($data = array(), $type = '')
    {
        global $workreap_settings;
        $project_id                 = !empty($data['project_id']) ? intval($data['project_id']) : 0;
        $step_id                    = !empty($data['step_id']) ? intval($data['step_id']) : 0;
        $enable_state			    = !empty($workreap_settings['enable_state']) ? $workreap_settings['enable_state'] : false;
        $user_id                    = !empty($type) && $type === 'mobile' ? $data['user_id'] : get_current_user_id();
        $json                       = array();
        $json['step_id']            = $step_id;

        if (!empty($step_id) && $step_id == 2) {
            $enable_zipcode     = !empty($workreap_settings['enable_zipcode']) ? $workreap_settings['enable_zipcode'] : "";
            $required_fields    = array(
                'title'             => esc_html__('Project title is required', 'workreap'),
                'location'          => esc_html__('Please select location', 'workreap'),
                'project_type'      => esc_html__('Please select project type', 'workreap'),
            );

            $project_type   = !empty($data['project_type']) ? $data['project_type'] : '';
            if (!empty($project_type) && $project_type === 'fixed') {
                $required_fields['min_price']   = esc_html__('Minimum price is required', 'workreap');
                $required_fields['max_price']   = esc_html__('Maximum price is required', 'workreap');
            }

            $required_fields        = apply_filters('workreap_project_validation_step2', $required_fields, $data);
            $workreap_validation     = !empty($workreap_settings['project_val_step2']) ? $workreap_settings['project_val_step2'] : array();

            if (!empty($workreap_validation)) {
                foreach ($workreap_validation as $value) {
                    $required_fields[$value]   = workreapProjectValidations($step_id, $value);
                }
            }

            if (!empty($data['location']) && $data['location'] === 'location') {
                if (!empty($enable_zipcode)) {
                    $required_fields['zipcode']   = esc_html__('Please add the zipcode', 'workreap');
                }
                $required_fields['country']   = esc_html__('Country field is required', 'workreap');
                if( !empty($enable_state) && !empty($data['country']) ){
                    if (class_exists('WooCommerce')) {
                        $countries_obj   	= new WC_Countries();
                        $states			 	= $countries_obj->get_states( $data['country'] );
                        if( !empty($states) ){
                            $required_fields['state']   = esc_html__('State field is required', 'workreap');
                        }
                    }

                }
            }

	        $json['message']           = esc_html__('Oops!','workreap');
            if (!empty($required_fields)) {
                foreach ($required_fields as $key => $value) {
                    if (empty($data[$key])) {
                        $json['type']           = 'error';
                        $json['message_desc']   = $value;
                        if (empty($type)) {
                            wp_send_json($json);
                        } else {
                            return $json;
                        }
                    } else if (!empty($project_type) && $project_type === 'fixed' && !empty($key) && $key === 'max_price') {
                        if ($data['max_price'] <= $data['min_price']) {
                            $json['type']           = 'error';
                            $json['message_desc']   = esc_html__('Please add valid maximum price value', 'workreap');
                            if (empty($type)) {
                                wp_send_json($json);
                            } else {
                                return $json;
                            }
                        }
                    } else if (!empty($project_type) && $project_type === 'fixed' && !empty($key) && $key === 'min_price') {
                        $projectmin_price = !empty($workreap_settings['fixed_projectmin_price']) ? $workreap_settings['fixed_projectmin_price'] : 5;
                        if ($data['min_price'] <= $projectmin_price) {
                            $json['type']           = 'error';
                            $json['message_desc']   = sprintf(esc_html__('Please add minimum price is greater then %s', 'workreap'), workreap_price_format($projectmin_price, 'return'));
                            if (empty($type)) {
                                wp_send_json($json);
                            } else {
                                return $json;
                            }
                        }
                    }
                }
            }

            $default_attribs = array(
                'id' => array(),
                'class' => array(),
                'title' => array(),
                'style' => array(),
                'data' => array(),
            );

            $allowed_tags   = array(
                'a' => array_merge($default_attribs, array(
                    'href' => array(),
                    'title' => array()
                )),
                'h1'        => array(),
                'h2'        => array(),
                'h3'        => array(),
                'h4'        => array(),
                'h5'        => array(),
                'h6'        => array(),
                'u'             =>  $default_attribs,
                'i'             =>  $default_attribs,
                'q'             =>  $default_attribs,
                'b'             =>  $default_attribs,
                'ul'            => $default_attribs,
                'ol'            => $default_attribs,
                'li'            => $default_attribs,
                'br'            => $default_attribs,
                'hr'            => $default_attribs,
                'strong'        => $default_attribs,
                'blockquote'    => $default_attribs,
                'del'           => $default_attribs,
                'strike'        => $default_attribs,
                'em'            => $default_attribs,
                'code'          => $default_attribs,
            );

            $project_title      = !empty($data['title']) ? sanitize_text_field($data['title']) : "";
            $details            = !empty($data['details']) ? wp_kses($data['details'], $allowed_tags) : "";

            $project_type       = !empty($data['project_type']) ? sanitize_text_field($data['project_type']) : "";
            $min_price          = !empty($data['min_price']) ? sanitize_text_field($data['min_price']) : "";
            $max_price          = !empty($data['max_price']) ? sanitize_text_field($data['max_price']) : "";

            $categories         = !empty($data['categories']) ? intval($data['categories']) : "";
            $duration           = !empty($data['duration']) ? intval($data['duration']) : "";
            $zipcode            = !empty($data['zipcode']) ? sanitize_text_field($data['zipcode']) : "";
            $country            = !empty($data['country']) ? sanitize_text_field($data['country']) : "";
            $state              = !empty($data['state']) ? sanitize_text_field($data['state']) : "";
            $project_id         = !empty($data['project_id']) ? intval($data['project_id']) : "";
            $is_milestone       = !empty($data['is_milestone']) ? sanitize_text_field($data['is_milestone']) : "no";


            $project_location           = !empty($data['location']) ? sanitize_text_field($data['location']) : "";
            if ($type === 'mobile') {
                $files                  = !empty($data['attachments']) ? json_decode(stripslashes($data['attachments']), true) : array(); //old attachments
                $attachment_size        = !empty($data['attachment_size']) ? $data['attachment_size'] : 0;
            } else {
                $downloads                  = !empty($data['attachments']) ? ($data['attachments']) : "";
            }

            $video_url                  = !empty($data['video_url']) ? ($data['video_url']) : '';

            $product_data               = array();

            if (!empty($project_id)) {
                $product_data   = get_post_meta($project_id, 'wr_project_meta', true);
                $product_data   = !empty($product_data) ? $product_data : array();
                $old_zipcode    = get_post_meta($project_id, 'zipcode', true);
                $old_country    = get_post_meta($project_id, '_country', true);
                $old_location   = get_post_meta($project_id, 'location', true);
            }

            $product_data['project_type']  = $project_type;
            if (!empty($project_type) && $project_type === 'fixed') {
                $product_data['min_price']  = $min_price;
                $product_data['max_price']  = $max_price;
            }

            $wr_post_data = array(
                'post_title' => wp_strip_all_tags($project_title),
                'post_content' => $details,
                'post_type'    => 'product',
                'post_author'  => $user_id,
            );

            if (empty($project_id)) {
                $wr_post_data['post_status'] = 'draft';
                // insert the post into the database
                $project_id = wp_insert_post($wr_post_data);
                update_post_meta($project_id, 'wr_product_type', 'projects');
                wp_set_object_terms($project_id, 'projects', 'product_type', true);
                update_post_meta($project_id, '_featured_task', 'no');
                update_post_meta($project_id, 'is_featured', false);
            } else {
                $wr_post_data['ID']         = $project_id;
                $wr_post_data['post_name']  = sanitize_title(get_the_title($project_id));
                wp_update_post($wr_post_data);
            }


            $product_data['name']   = wp_strip_all_tags($project_title);
            if (!empty($project_location) && $project_location === 'location') {
                $response   = array();
                if (!empty($enable_zipcode)) {
                    if ((!empty($old_zipcode) && $old_zipcode != $zipcode && $old_country != $country) || empty($old_zipcode)) {
                        $response   = array();
                        $response   = workreap_process_geocode_info($zipcode, $country);
                    }
                }

                if (empty($enable_zipcode)) {
                    update_post_meta($project_id, 'zipcode', 0);
                    update_post_meta($project_id, 'longitude', 0);
                    update_post_meta($project_id, 'latitude', 0);
                    update_post_meta($project_id, 'country', $country);
                    $product_data['country']        = $country;
                } else if (!empty($response)) {
                    update_post_meta($project_id, 'location', $response);
                    update_post_meta($project_id, 'zipcode', $zipcode);
                    update_post_meta($project_id, 'country', $country);
                    update_post_meta($project_id, 'longitude', $response['lng']);
                    update_post_meta($project_id, 'latitude', $response['lat']);
                    $product_data['country']        = $country;
                    $product_data['latitude']       = $response['lat'];
                    $product_data['longitude']      = $response['lng'];
                    $product_data['zipcode']        = $zipcode;
                }
            }

            if( !empty($enable_state) && !empty($country) ){
                $product_data['state']        = $state;
                update_post_meta($project_id, 'state', $state);
            }

            $product_data['is_milestone']       = $is_milestone;
            $product_data['video_url']          = $video_url;
            update_post_meta($project_id, 'wr_project_meta', $product_data);
            update_post_meta($project_id, '_project_location', $project_location);
            update_post_meta($project_id, 'is_milestone', $is_milestone);
            update_post_meta($project_id, '_order_status', false);
            $project_multilevel_cat         = !empty($workreap_settings['project_multilevel_cat']) ? $workreap_settings['project_multilevel_cat'] : 'disable';

            if( !empty($project_multilevel_cat) && $project_multilevel_cat === 'enable' ){
                update_post_meta($project_id, '_cat', $categories);
                $categories         = array($categories);
                $subcats            = array();
                $category_level2    = '';

                if( !empty($data['workreap_service']['category_level2'])){
                    $categories[]   = $data['workreap_service']['category_level2'];
                    $category_level2    = $data['workreap_service']['category_level2'];
                }

                if( !empty($data['workreap_service']['category_level3'])){
                    if( is_array($data['workreap_service']['category_level3']) ){
                        foreach($data['workreap_service']['category_level3'] as $subcat){
                            $categories[]       = intval($subcat);
                            $subcats[]          = intval($subcat);
                        }
                    } else {
                        $categories[]   = $data['workreap_service']['category_level3'];
                    }
                }
                $subcats_data       = !empty($subcats) && is_array($subcats) ? $subcats : '';
                $category_level2    = !empty($category_level2) ? intval($category_level2) : '';
                update_post_meta($project_id, '_sub_cat', $category_level2);
                update_post_meta($project_id, '_cat_type', $subcats_data);
            }

            wp_set_post_terms($project_id, $categories, 'product_cat');
            wp_set_post_terms($project_id, $duration, 'duration');
            update_post_meta($project_id, 'project_type', $project_type);

            if (!empty($project_type) && $project_type === 'fixed') {
                update_post_meta($project_id, 'min_price', $min_price);
                update_post_meta($project_id, 'max_price', $max_price);
            }

            if (empty($type) && $type != 'mobile') {
                if (!empty($downloads)) {
                    $download_data  = array();
                    foreach ($downloads as $key => $value) {
                        if (!empty($value['id'])) {
                            $uploaded_media                 = array();
                            $uploaded_media['id']           = intval($value['id']);
                            $uploaded_media['file']         = esc_url($value['file']);
                            $uploaded_media['name']         = esc_html($value['name']);
                            $uploaded_media['download_id']  = esc_html($value['id']);
                            $download_data[]                = $uploaded_media;
                        } else {
                            $file_url       = !empty($value) ? esc_url($value) : '';
                            $new_attachemt  = workreap_temp_upload_to_media($file_url, $project_id);
                            $attachment_id  = !empty($new_attachemt['attachment_id']) ? $new_attachemt['attachment_id'] : '';
                            $file           = !empty($new_attachemt['url']) ? $new_attachemt['url'] : '';
                            $name           = !empty($new_attachemt['name']) ? $new_attachemt['name'] : '';
                            $download_data[]    = array(
                                'id'            => $new_attachemt['attachment_id'],
                                'name'          => $name,
                                'file'          => $file,
                                'download_id'   => $attachment_id,
                            );
                        }
                    }

                    update_post_meta($project_id, '_downloadable_files', $download_data);
                    update_post_meta($project_id, '_downloadable', 'yes');
                } else {
                    update_post_meta($project_id, '_downloadable', 'no');
                }
            } else {
                update_post_meta($project_id, '_downloadable', 'no');
                /* media attachemtns for mobile */
                $total_new_project_images = $attachment_size;
                $old_project_images = $files;

                $attachments_files  = $attachment_ids = array();
                if (!empty($_FILES) && $total_new_project_images > 0) {
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    require_once(ABSPATH . 'wp-includes/pluggable.php');
                    require_once(ABSPATH . 'wp-admin/includes/media.php');

                    /* already attached images array from api's */
                    $old_attachments = !empty($old_project_images) ? $old_project_images : array();

                    /* attached gallery images from DB */
                    $db_gallery_attachment_arr      = get_post_meta($project_id, '_downloadable_files', true);
                    $db_gallery_attachment_arr      = !empty($db_gallery_attachment_arr) ? $db_gallery_attachment_arr : array();

                    /* create array of gallery attachment id's that srote in DB */
                    $db_gallery_attachment = array();
                    if (!empty($db_gallery_attachment_arr)) {
                        $db_gallery_attachment     = wp_list_pluck($db_gallery_attachment_arr, 'attachment_id');
                    }

                    /* delete all images if empty array received from api's */
                    if (empty($old_attachments) && !empty($db_gallery_attachment)) {
                        foreach ($db_gallery_attachment as $delete_media) {
                            if (!empty($delete_media)) {
                                wp_delete_attachment($project_id, $delete_media, true);
                            }
                        }
                        delete_post_meta($project_id, '_downloadable_files');
                    }

                    /* upload new docs if exist */
                    $newyUploadGallery = array();
                    if (!empty($total_new_project_images) && $total_new_project_images > 0) {
                        /* count saved data form db for indexing */
                        $new_index    = !empty($db_gallery_attachment_arr) ?  max(array_keys($db_gallery_attachment_arr)) : 0;
                        for ($x = 0; $x < $total_new_project_images; $x++) {
                            $new_index                 = $new_index + 1;
                            $gallery_image_files     = $_FILES['project_image_' . $x];

                            $uploaded_image          = wp_handle_upload($gallery_image_files, array('test_form' => false));
                            $file_name                 = basename($gallery_image_files['name']);
                            $file_type                  = wp_check_filetype($uploaded_image['file']);

                            /* Prepare an array of post data for the attachment. */
                            $attachment_details = array(
                                'guid'                 => $uploaded_image['url'],
                                'post_mime_type'     => $file_type['type'],
                                'post_title'         => preg_replace('/\.[^.]+$/', '', basename($file_name)),
                                'post_content'         => '',
                                'post_status'         => 'inherit'
                            );

                            $attach_id          = wp_insert_attachment($attachment_details, $uploaded_image['file']);
                            $attach_data        = wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
                            wp_update_attachment_metadata($attach_id, $attach_data);

                            $newyUploadGallery[]    = array(
                                'id'            => $attach_id,
                                'name'          => get_the_title($attach_id),
                                'file'          => wp_get_attachment_url($attach_id),
                                'download_id'   => $attachment_id,
                            );
                        }
                    }

                    /* delete some images that not send in request */
                    if (!empty($old_attachments) && !empty($db_gallery_attachment)) {
                        $updateGalleryArr = $newAttachment_ids = array();
                        $db_saved_gallery = !empty($db_gallery_attachment_arr) ? $db_gallery_attachment_arr : array();

                        if (!empty($db_saved_gallery) && !empty($old_attachments)) {
                            foreach ($db_saved_gallery as $galleryVal) {
                                foreach ($old_attachments as $oldAttachmentVal) {
                                    if ($galleryVal['id'] == $oldAttachmentVal['id']) {
                                        $updateGalleryArr[] = array(
                                            'id'                => (int)$galleryVal['id'],
                                            'name'              => $galleryVal['name'],
                                            'file'              => $galleryVal['file'],
                                            'download_id'       => (int)$galleryVal['download_id']
                                        );
                                    }
                                }
                            }
                        }
                        $galleryNew_arr = array_merge($newyUploadGallery, $updateGalleryArr);
                        update_post_meta($project_id, '_downloadable_files', $galleryNew_arr);
                    } else {
                        update_post_meta($project_id, '_downloadable_files', $newyUploadGallery);
                    }
                    update_post_meta($project_id, '_downloadable', 'yes');
                } else {
                    //here deal with old data
                    /* already attached downloads array from api's */
                    $old_attachments = !empty($old_attachments) ? $old_attachments : array();

                    /* attached doc from DB */
                    $db_gallery_attachment_arr      = get_post_meta($project_id, '_downloadable_files', true);
                    $db_gallery_attachment_arr      = !empty($db_gallery_attachment_arr) ? $db_gallery_attachment_arr : array();

                    /* create array of gallery attachment id's that srote in DB */
                    $db_gallery_attachment = array();
                    if (!empty($db_gallery_attachment_arr)) {
                        $db_gallery_attachment     = wp_list_pluck($db_gallery_attachment_arr, 'attachment_id');
                    }

                    /* delete all images if empty array received from api's */
                    if (empty($old_attachments) && !empty($db_gallery_attachment)) {
                        foreach ($db_gallery_attachment as $delete_media) {
                            if (!empty($delete_media)) {
                                wp_delete_attachment($project_id, $delete_media, true);
                            }
                        }
                        delete_post_meta($project_id, '_downloadable_files');
                        update_post_meta($project_id, '_downloadable', 'no');
                    } else {
                        $newDownloadsArr = array();

                        /* delete some attachments that not send in request */
                        if (!empty($old_attachments) && !empty($db_gallery_attachment)) {
                            $db_saved_downloads_ = !empty($db_gallery_attachment_arr) ? $db_gallery_attachment_arr : array();
                            if (!empty($db_saved_downloads_)) {
                                foreach ($db_saved_downloads_ as $downloadVals) {
                                    foreach ($old_attachments as $oldVal) {
                                        if ($downloadVals['id'] == $oldVal['id']) {
                                            $newDownloadsArr[] = array(
                                                'id'                => (int)$downloadVals['id'],
                                                'name'              => $downloadVals['name'],
                                                'file'              => $downloadVals['file'],
                                                'download_id'       =>     (int)$downloadVals['download_id']
                                            );
                                        }
                                    }
                                }
                            }
                        }

                        update_post_meta($project_id, '_downloadable_files', $newDownloadsArr);
                        update_post_meta($project_id, '_downloadable', 'yes');
                    }
                }
            }

            update_post_meta($project_id, '_project_status_type', 'public');
            do_action('workreap_save_project_step2', $project_id, $data);
            workreapUpdateProjectStatus($project_id);
            do_action('workreap_project_step2', $project_id, $data, $type);
            $json['type']               = 'success';
            $json['post_id']            = (int)$project_id;
            $json['step']               = 3;
            $json['redirect']           = workreap_get_page_uri('add_project_page') . '?step=3&post_id=' . $project_id;
            $json['message']             = esc_html__('Woohoo!', 'workreap');
            $json['message_desc']         = esc_html__('Project has been updated', 'workreap');

            if (empty($type)) {
                wp_send_json($json);
            } else {
                return $json;
            }
        } else if (!empty($step_id) && $step_id == 3) {
            $package_option     = workreapCheckEmployerPackage($user_id, 'number_projects_allowed', $project_id);
            $required_fields    = array(
                'no_of_freelancers'     => esc_html__('Select No. of freelancers', 'workreap'),
                'project_id'            => esc_html__('You are not allowed to perform this action', 'workreap')
            );
            $required_fields        = apply_filters('workreap_project_validation_step3', $required_fields);
            $workreap_validation     = !empty($workreap_settings['project_val_step3']) ? $workreap_settings['project_val_step3'] : array();

            if (!empty($workreap_validation)) {
                foreach ($workreap_validation as $value) {
                    $required_fields[$value]   = workreapProjectValidations($step_id, $value);
                }
            }

            $required_fields        = apply_filters('workreap_project_validation_step2', $required_fields);
            if (!empty($required_fields)) {
                foreach ($required_fields as $key => $value) {
                    if (empty($data[$key])) {
                        $json['type']           = 'error';
	                    $json['message']           = esc_html__('Oops!','workreap');
                        $json['message_desc']   = $value;
                        if (empty($type)) {
                            wp_send_json($json);
                        }
                    }
                }
            }

            $skills                     = !empty($data['skills']) ? ($data['skills']) : array();
            $languages                  = !empty($data['languages']) ? ($data['languages']) : array();
            $expertise_level            = !empty($data['expertise_level']) ? intval($data['expertise_level']) : "";
            $no_of_freelancers          = !empty($data['no_of_freelancers']) ? sanitize_text_field($data['no_of_freelancers']) : "";
            wp_set_post_terms($project_id, $skills, 'skills');
            wp_set_post_terms($project_id, $languages, 'languages');
            wp_set_post_terms($project_id, $expertise_level, 'expertise_level');
            update_post_meta($project_id, 'no_of_freelancers', $no_of_freelancers);
            workreapUpdateProjectStatus($project_id);
            // update project publish or requested

            $service_status             = !empty($workreap_settings['project_status']) ? $workreap_settings['project_status'] : '';
            $resubmit_project_status    = !empty($workreap_settings['resubmit_project_status']) ? $workreap_settings['resubmit_project_status'] : 'no';

            $project_status            = get_post_meta($project_id, '_post_project_status', true);
            $project_status            = !empty($project_status) ? $project_status : '';
            $gmt_time                   = current_time('mysql', 1);
            $post_status               = get_post_status($project_id);
            $post_status               = !empty($post_status) ? $post_status : '';
            if (class_exists('Workreap_Email_helper') && !empty($project_id)) {
                $emailData        = array();
                $profile_id     = workreap_get_linked_profile_id($user_id, '', 'employers');
                if (class_exists('WorkreapProjectCreation')) {
                    $emailData['employer_name']                = workreap_get_username($profile_id);
                    $emailData['employer_email']                = get_userdata($user_id)->user_email;
                    $emailData['project_title']                = get_the_title($project_id);
                    $emailData['project_link']                = workreap_get_page_uri('add_project_page') . '?step=4&post_id=' . $project_id;
                    $emailData['sender_id']                 = $user_id; //freelancer id
                    $emailData['receiver_id']               = workreap_get_admin_user_id(); //admin id
                    $email_helper                           = new WorkreapProjectCreation();
                    if (!empty($workreap_settings['email_admin_project_approval']) && $service_status == 'pending') {
                        //$emailData['project_link'] = get_the_permalink($project_id);
                        $emailData['project_link'] = Workreap_Profile_Menu::workreap_profile_admin_menu_link('projects', workreap_get_admin_user_id(), true, 'listing');
                        $email_helper->post_project_approval_admin_email($emailData);
                    }
                }
            }
            $json['message_desc']         = esc_html__('Project has been updated', 'workreap');
            if (!empty($post_status) && $post_status === 'draft' && !empty($service_status) && $service_status === 'publish') {
                // admin email for task creation
                $project_post = array(
                    'ID'            => $project_id,
                    'post_status'   => 'publish',
                );
                wp_update_post($project_post);
                update_post_meta($project_id, '_post_project_status', 'publish');
                update_post_meta($project_id, '_publish_datetime', $gmt_time);
                if (!empty($workreap_settings['email_post_project'])) {
                    $emailData['project_link']  = workreap_get_page_uri('add_project_page') . '?step=4&post_id=' . $project_id;
                    $email_helper->post_project_employer_email($emailData);
                }
                if (!empty($workreap_settings['email_project_approve'])) {
                    $emailData['project_link'] = get_the_permalink($project_id);
                    $email_helper->approved_project_employer_email($emailData);
                }

                // Aproved task notification & email
            } else  if (!empty($post_status) && $post_status === 'draft' && !empty($service_status) && $service_status === 'pending') {
                // admin email for approved task
                $project_post = array(
                    'ID'            => $project_id,
                    'post_status'   => 'pending',
                );
                wp_update_post($project_post);
                update_post_meta($project_id, '_post_project_status', 'requested');
                if (!empty($workreap_settings['email_post_project'])) {
                    $emailData['project_link']  = workreap_get_page_uri('add_project_page') . '?step=4&post_id=' . $project_id;
                    $email_helper->post_project_employer_email($emailData);
                }
                update_post_meta($project_id, '_requested_datetime', $gmt_time);
                $json['message_desc']         = esc_html__('Project has been submitted.', 'workreap');
            } else if (!empty($post_status) && $post_status != 'draft' && !empty($service_status) && $service_status === 'pending') {
                // admin email for resubmit request
                $project_post = array(
                    'ID'            => $project_id,
                    'post_status'   => 'pending',
                );
                wp_update_post($project_post);
                update_post_meta($project_id, '_post_project_status', 'requested');
                update_post_meta($project_id, '_requested_datetime', $gmt_time);
                if (!empty($workreap_settings['email_post_project'])) {
                    $emailData['project_link']  = workreap_get_page_uri('add_project_page') . '?step=4&post_id=' . $project_id;
                    $email_helper->post_project_employer_email($emailData);
                }
            }
            workreapUpdateEmployerPackage($user_id, 'number_projects_allowed', $project_id);
            do_action('workreap_project_step3', $project_id, $data, $type);
            $json['type']               = 'success';
            $json['post_id']            = (int)$project_id;
            $json['step']               = 4;
            $json['redirect']           = workreap_get_page_uri('add_project_page') . '?step=4&post_id=' . $project_id;
            $json['message']             = esc_html__('Woohoo!', 'workreap');

            if (empty($type)) {
                wp_send_json($json);
            } else {
                return $json;
            }
        }
    }
}
/**
 * Duplicate project
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreapDuplicateProject')) {
    function workreapDuplicateProject($post_id = 0, $user_id = 0, $type = '')
    {
        $post_url        = workreap_get_page_uri('add_project_page');
        $post_author    = !empty($post_id) ? get_post_field('post_author', $post_id,) : 0;
        //if( empty($post_id) || empty($post_author) || $post_author != $user_id ){
        if (empty($post_id)) {
            $json['type']           = 'error';
	        $json['message']           = esc_html__('Oops!','workreap');
            $json['message_desc']   = esc_html__('You are not allowed to perfom this action', 'workreap');
            if (empty($type)) {
                wp_send_json($json);
            }
        } else if (!empty($post_id)) {
            $meta_keys  = array();
            $meta_keys  = array(
                '_downloadable', 'location', 'zipcode', 'country', 'longitude', 'latitude', 'wr_project_meta', 'project_type', '_downloadable',
                'no_of_freelancers', '_downloadable_files', '_product_video', '_project_status_type', 'is_milestone', '_project_location', 'name'
            );
            $meta_keys      = apply_filters('workreap_duplicate_job_filter', $meta_keys, $post_id);
            $project_type   = get_post_meta($post_id, 'project_type', true);
            $project_type   = !empty($project_type) ? $project_type : '';
            if (!empty($project_type) && $project_type === 'fixed') {
                if (!empty($meta_keys)) {
                    array_merge($meta_keys, array("min_price", "max_price"));
                } else {
                    $meta_keys  = array("min_price", "max_price");
                }
            }
            $project_title  = get_the_title($post_id);
            $details        = get_post_field('post_content', $post_id);
            $wr_post_data   = array(
                'post_title' => wp_strip_all_tags($project_title),
                'post_content' => $details,
                'post_type'    => 'product',
                'post_author'  => $user_id,
                'post_status'  => 'draft'
            );
            $project_id = wp_insert_post($wr_post_data);
            update_post_meta($project_id, 'wr_product_type', 'projects');
            update_post_meta($project_id, '_post_project_status', 'draft');
            wp_set_object_terms($project_id, 'projects', 'product_type', true);
            $categories = wp_get_post_terms($post_id, 'product_cat', array('fields' => 'ids'));
            $duration   = wp_get_post_terms($post_id, 'duration', array('fields' => 'ids'));
            $skills     = wp_get_post_terms($post_id, 'skills', array('fields' => 'ids'));
            $languages  = wp_get_post_terms($post_id, 'languages', array('fields' => 'ids'));

            $expertise_level  = wp_get_post_terms($post_id, 'expertise_level', array('fields' => 'ids'));

            wp_set_post_terms($project_id, $categories, 'product_cat');
            wp_set_post_terms($project_id, $duration, 'duration');
            wp_set_post_terms($project_id, $skills, 'skills');
            wp_set_post_terms($project_id, $languages, 'languages');
            wp_set_post_terms($project_id, $expertise_level, 'expertise_level');

            foreach ($meta_keys as $meta_key) {
                $key_val    = get_post_meta($post_id, $meta_key, true);
                if ($meta_key === 'wr_project_meta') {
                    $key_val['name']            = '';
                    $key_val['invitation']      = array();
                }
                update_post_meta($project_id, $meta_key, $key_val);
            }
            update_post_meta($project_id, '_featured_task', 'no');
            update_post_meta($project_id, 'is_featured', false);
            if (empty($type)) {
                $json['type']           = 'success';
                $json['redirect_url']   = !empty($post_url) ? $post_url . '?step=2&post_id=' . intval($project_id) : '';
                wp_send_json($json);
            } else if (!empty($type) && $type === 'migration') {
                return $project_id;
            } elseif (!empty($type) && $type === 'mobile') {
                $json['type']               = 'success';
                $json['project_id']         = $project_id;
	            $json['message'] 		= esc_html__('Woohoo!', 'workreap');
                $json['message_desc']       = esc_html__('New project created', 'workreap');
                return $json;
            }
        }
    }
}

/**
 * Price calcuation
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreapPriceCalcuation')) {
    function workreapPriceCalcuation($post_id = 0, $price = 0, $type = '')
    {
        $price_calcuations  = workreap_commission_fee($price);
        $json                               = array();
        $json['type']                       = 'success';
        $json['price']                      = workreap_price_format($price, 'return');
        $json['admin_shares']               = isset($price_calcuations['admin_shares']) ? workreap_price_format($price_calcuations['admin_shares'], 'return') : '';
        $json['user_shares']                = isset($price_calcuations['freelancer_shares']) ? workreap_price_format($price_calcuations['freelancer_shares'], 'return') : '';
        if (empty($type)) {
            wp_send_json($json);
        }
    }
}
/**
 * Task update status
 *
 */
if (!function_exists('workreapUpdateProjectStatus')) {
    function workreapUpdateProjectStatus($project_id = 0)
    {
        global $workreap_settings;
        $service_status             = !empty($workreap_settings['project_status']) ? $workreap_settings['project_status'] : '';
        $resubmit_project_status    = !empty($workreap_settings['resubmit_project_status']) ? $workreap_settings['resubmit_project_status'] : 'no';

        $project_status            = get_post_meta($project_id, '_post_project_status', true);
        $project_status            = !empty($project_status) ? $project_status : '';

        $post_status               = get_post_status($project_id);
        $post_status               = !empty($post_status) ? $post_status : '';

        if (!empty($project_status) && $project_status === 'pending' && !empty($resubmit_project_status) && $resubmit_project_status === 'yes') {
            if (empty($project_status) || $project_status != 'rejected') {
                update_post_meta($project_id, '_post_project_status', 'pending');
                if (!empty($post_status) && $post_status != 'draft') {
                    $project_post = array(
                        'ID'            => $project_id,
                        'post_status'   => $project_status,
                    );
                    wp_update_post($project_post);
                }
            }
        } else if (!empty($project_status) && $project_status === 'publish' && !empty($resubmit_project_status) && $resubmit_project_status === 'no') {
            update_post_meta($project_id, '_post_project_status', 'publish');
        } else if (empty($project_status) && $post_status === 'draft') {
            update_post_meta($project_id, '_post_project_status', 'draft');
        }
    }
}

/**
 * Project type html
 *
 */
if (!function_exists('workreap_project_type_tag')) {
    function workreap_project_type_tag($post_id = '')
    {
        $project_type    = get_post_meta($post_id, 'project_type', true);
        $project_type    = !empty($project_type) ? $project_type : '';
        $type_text       = !empty($project_type) && $project_type === 'fixed' ? esc_html__('Fixed price project', 'workreap') : apply_filters('workreap_filter_project_type_text', $project_type);
        $class           = !empty($project_type) && $project_type === 'fixed' ? 'wr-ongoing' : apply_filters('workreap_filter_project_type_class', $project_type);
        ob_start();
        ?>
        <span class="wr-project-tag <?php echo esc_attr($class); ?>"><?php echo esc_html($type_text); ?></span>
    <?php
        echo ob_get_clean();
    }
    add_action('workreap_project_type_tag', 'workreap_project_type_tag', 10, 1);
}


/**
 * Project type html
 *
 */
if (!function_exists('workreap_project_type_text')) {
    function workreap_project_type_text($project_type = '')
    {
        $type_text  = '';
        if (!empty($project_type) && $project_type === 'fixed') {
            $type_text    = esc_html__('Fixed price project', 'workreap');
        } else {
            $type_text =  apply_filters('workreap_filter_project_type_text', $project_type);
        }
        ob_start();
    ?>
        <span><?php echo esc_html($type_text); ?></span>
        <?php
        echo ob_get_clean();
    }
    add_action('workreap_project_type_text', 'workreap_project_type_text', 10, 1);
}
/**
 * Project type html
 *
 */
if (!function_exists('workreap_project_status_tag')) {
    function workreap_project_status_tag($product)
    {
        $projecet_status    = get_post_meta($product->get_id(), '_post_project_status', true);
        $projecet_status    = !empty($projecet_status) ? $projecet_status : '';
        $lable              = "";
        $status_class       = "";
        switch ($projecet_status) {
            case 'pending':
                $label          = esc_html__('Pending', 'workreap');
                $status_class   = 'wr-project-tag wr-awaiting';
                break;
            case 'disputed':
                $label          = esc_html__('Disputed', 'workreap');
                $status_class   = 'wr-project-tag wr-awaiting';
                break;
            case 'draft':
                $label          = esc_html__('Drafted', 'workreap');
                $status_class   = 'wr-project-tag wr-drafted';
                break;
            case 'publish':
                $label          = esc_html__('In queue', 'workreap');
                $status_class   = 'wr-project-tag wr-awaiting';
                break;
            case 'refunded':
                $label          = esc_html__('Refunded', 'workreap');
                $status_class   = 'wr-project-tag wr-success-tag';
                break;
            case 'completed':
                $label          = _x('Completed', 'Title for project status', 'workreap');
                $status_class   = 'wr-project-tag wr-success-tag';
                break;
            case 'rejected':
                $label          = esc_html__('Rejected', 'workreap');
                $status_class   = 'wr-project-tag wr-canceled';
                break;
            case 'cancelled':
                $label          = esc_html__('Cancelled', 'workreap');
                $status_class   = 'wr-project-tag wr-canceled';
                break;
            case 'hired':
                $label          = esc_html__('Ongoing', 'workreap');
                $status_class   = 'wr-project-tag wr-ongoing';
                break;
            case 'requested':
                $label          = esc_html__('Under Review', 'workreap');
                $status_class   = 'wr-project-tag wr-requested';
                break;
            default:
                $label          = esc_html__('New', 'workreap');
                $status_class   = 'wr-project-tag';
                break;
        }
        if (!empty($label)) {
            ob_start();
        ?>
            <span class="<?php echo esc_attr($status_class); ?>"><?php echo esc_html($label); ?></span>
        <?php
            echo ob_get_clean();
        }
    }
    add_action('workreap_project_status_tag', 'workreap_project_status_tag', 10, 1);
}
/**
 * Project price html
 *
 */
if (!function_exists('workreap_get_project_price_html')) {
    function workreap_get_project_price_html($post_id = '')
    {
        $project_meta    = get_post_meta($post_id, 'wr_project_meta', true);
        $project_type    = !empty($project_meta['project_type']) ? $project_meta['project_type'] : '';
        $project_price   = '';
        if (!empty($project_type) && $project_type === 'fixed') {
            $min_price  = !empty($project_meta['min_price']) ? $project_meta['min_price'] : 0;
            $max_price  = !empty($project_meta['max_price']) ? $project_meta['max_price'] : 0;
            $project_price  = workreap_price_format($min_price, 'return') . '-' . workreap_price_format($max_price, 'return');
        } else {
            $project_price  = apply_filters('workreap_filter_get_project_price', $post_id);
            $project_price  = workreap_price_format($project_price,'return');
        }

        ob_start();
        ?>
        <h3 class="wr-project-price wr-tag-<?php echo esc_attr($project_type); ?>"><?php echo do_shortcode($project_price); ?></h3>
    <?php
        echo ob_get_clean();
    }
    add_action('workreap_get_project_price_html', 'workreap_get_project_price_html', 10, 1);
}

/**
 * Project price html
 *
 */
if (!function_exists('workreap_get_project_price')) {
    function workreap_get_project_price($post_id = '')
    {
        $project_meta    = get_post_meta($post_id, 'wr_project_meta', true);
        $project_type    = !empty($project_meta['project_type']) ? $project_meta['project_type'] : '';
        $project_price   = '';

        if (!empty($project_type) && $project_type === 'fixed') {
	        $min_price  = !empty($project_meta['min_price']) ? $project_meta['min_price'] : 0;
	        $max_price  = !empty($project_meta['max_price']) ? $project_meta['max_price'] : 0;
            $project_price  = workreap_price_format($min_price, 'return') . '-' . workreap_price_format($max_price, 'return');
        } else {
            $project_price  = apply_filters('workreap_filter_get_project_price', $post_id);
            $project_price  = workreap_price_format($project_price,'return');
        }
        return $project_price;
    }
}
/**
 * No of hiring freelancer
 *
 */
if (!function_exists('workreap_total_hiring_freelancer_html')) {
    function workreap_total_hiring_freelancer_html($post_id = '')
    {
        $no_of_freelancers       = get_post_meta($post_id, 'no_of_freelancers', true);
        $no_of_freelancers       = !empty($no_of_freelancers) ? $no_of_freelancers : '';

        ob_start();
    ?>
        <li>
            <i class="wr-icon-users wr-blue-icon"></i>
            <div class="wr-project-requirement_content">
                <div class="wr-requirement-tags">
                    <span><?php echo sprintf(_n('%s freelancer', '%s freelancers', $no_of_freelancers, 'workreap'), $no_of_freelancers); ?></span>
                </div>
                <em><?php esc_html_e('Hiring capacity', 'workreap'); ?></em>
            </div>
        </li>
        <?php
        echo ob_get_clean();
    }
    add_action('workreap_total_hiring_freelancer_html', 'workreap_total_hiring_freelancer_html', 10, 1);
}

/**
 * Project texnonimies html
 *
 */
if (!function_exists('workreap_texnomies_html')) {
    function workreap_texnomies_html($post_id = '', $term_name = '', $lable = '', $icon_classes = '')
    {
        $product_terms      = !empty($post_id) && !empty($term_name) ? wp_get_post_terms($post_id, $term_name) : array();
        $tag_text           = '';
        if (!empty($product_terms) && !is_wp_error($product_terms)) {
            $terms          = array();
            $extra_terms    = array();
            $counter        = 0;
            foreach ($product_terms as $term) {
                if ($counter < 4) {
                    $terms[]        = esc_html($term->name);
                } else {
                    $extra_terms[]  = esc_html($term->name);
                }
                $counter++;
            }
            $tag_text   =  join(', ', $terms);

            ob_start();
        ?>
            <li>
                <i class="<?php echo esc_attr($icon_classes); ?>"></i>
                <div class="wr-project-requirement_content">
                    <div class="wr-requirement-tags">
                        <span><?php echo esc_html($tag_text); ?></span>
                        <?php
                        if (!empty($extra_terms)) {
                            $title  = wp_sprintf(esc_html__('%d more', 'workreap'), count($extra_terms));
                            do_action('workreap_tooltip_tags', $title, $extra_terms);
                        } ?>
                    </div>
                    <em><?php echo esc_html($lable); ?></em>
                </div>
            </li>
        <?php
            echo ob_get_clean();
        }
    }
    add_action('workreap_texnomies_html', 'workreap_texnomies_html', 10, 4);
}

/**
 * Texnonimies html
 *
 */
if (!function_exists('workreap_texnomies_static_html')) {
    function workreap_texnomies_static_html($post_id = '', $term_name = '', $lable = '')
    {
        $product_terms      = !empty($post_id) && !empty($term_name) ? wp_get_post_terms($post_id, $term_name) : array();
        $tag_text           = '';
        if (!empty($product_terms) && !is_wp_error($product_terms)) {
            $terms          = array();
            $extra_terms    = array();
            $counter        = 0;
            foreach ($product_terms as $term) {
                if ($counter < 4) {
                    $terms[]        = esc_html($term->name);
                } else {
                    $extra_terms[]  = esc_html($term->name);
                }
                $counter++;
            }
            $tag_text   =  join(', ', $terms);

            if (!empty($extra_terms)) {
                $title  = wp_sprintf(esc_html__('%d more', 'workreap'), count($extra_terms));
                do_action('workreap_tooltip_tags', $title, $extra_terms);
            }

            ob_start();
        ?>
            <div class="wr-sidebarcontent">
                <div class="wr-sidebarinnertitle">
                    <h6><?php echo esc_html($lable); ?></h6>
                    <h5><?php echo esc_html($tag_text); ?></h5>
                </div>
            </div>
        <?php
            echo ob_get_clean();
        }
    }
    add_action('workreap_texnomies_static_html', 'workreap_texnomies_static_html', 10, 3);
}

/**
 * Project texnonimies html
 *
 */
if (!function_exists('workreap_project_freelancer_basic')) {
    function workreap_project_freelancer_basic($post_id = '')
    {
        if (!empty($post_id)) {
            $user_id    = get_post_field('post_author', $post_id);
            $user_id    = !empty($user_id) ? intval($user_id) : 0;
            $profile_id = !empty($user_id) ? workreap_get_linked_profile_id($user_id, '', 'employers') : 0;
            $user_name  = !empty($profile_id) ? workreap_get_username($profile_id) : '';

            $avatar     = apply_filters(
                'workreap_avatar_fallback',
                workreap_get_user_avatar(array('width' => 100, 'height' => 100), $profile_id),
                array('width' => 100, 'height' => 100)
            );

            $userdata       = get_userdata($user_id);
            $registered_on     = !empty($userdata->user_registered) ? $userdata->user_registered : '';

            $posted_date    = !empty($registered_on) ? date_i18n(get_option('date_format'),  strtotime($registered_on)) : '';
            $is_verified    = !empty($profile_id) ? get_post_meta($profile_id, '_is_verified', true) : '';
            $wr_post_meta   = get_post_meta($profile_id, 'wr_post_meta', true);
            $wr_post_meta   = !empty($wr_post_meta) ? $wr_post_meta : array();
            $description    = !empty($wr_post_meta['description']) ? $wr_post_meta['description'] : '';
            $tagline        = !empty($wr_post_meta['tagline']) ? $wr_post_meta['tagline'] : '';
            $address        = apply_filters('workreap_user_address', $profile_id);

            $posted_project_count   = workreap_get_user_projects($user_id);
            $hired_project_count    = workreap_get_user_projects($user_id, 'hired');


            //All author posts
            $page_url    = '';
            if (function_exists('workreap_get_page_uri')) {
                $page_url    = workreap_get_page_uri('project_search_page');
            }

            $query_arg['owner'] = $user_id;
            $page_url = add_query_arg(
                $query_arg,
                esc_url($page_url)
            );

            ob_start();
        ?>
            <div class="wr-projectinfo">
                <div class="wr-project-box">
                    <div class="wr-verified-title">
                        <div class="wr-projectinfo_title">
                            <?php if (!empty($avatar)) { ?>
                                <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($user_name); ?>">
                            <?php } ?>
                            <?php if (!empty($user_name) || !empty($posted_date) || !empty($description)) { ?>
                                <div class="wr-verified-info">
                                    <?php if (!empty($user_name)) { ?>
                                        <h5>
                                            <?php echo esc_html($user_name); ?>
                                            <?php if (!empty($is_verified) && $is_verified === 'yes') { ?>
                                                <i class="wr-icon-check-circle" <?php echo apply_filters('workreap_tooltip_attributes', 'verified_user'); ?>></i>
                                            <?php } ?>
                                        </h5>
                                    <?php } ?>
                                    <?php if (!empty($posted_date)) { ?>
                                        <em><?php echo sprintf(esc_html__('Member since %s', 'workreap'), $posted_date); ?></em>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                        <?php if (!empty($description) || !empty($description)) { ?>
                            <div class="wr-projectinfo_description">
                                <?php if (!empty($tagline)) { ?><h6><?php echo esc_html($tagline); ?></h6><?php } ?>
                                <?php if (!empty($description)) { ?>
                                    <div class="description-with-more">
                                        <p><?php echo do_shortcode(nl2br($description)); ?></p>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if (isset($address) || isset($posted_project_count) || isset($hired_project_count)) { ?>
                        <ul class="wr-checkout-info">
                            <?php if (isset($address)) { ?>
                                <li>
                                    <div class="wr-total-title">
                                        <i class="wr-icon-map-pin"></i>
                                        <em><?php esc_html_e('Located in', 'workreap'); ?></em>
                                    </div>
                                    <span><?php echo esc_html($address); ?></span>
                                </li>
                            <?php } ?>
                            <?php if (isset($posted_project_count)) { ?>
                                <li>
                                    <div class="wr-total-title">
                                        <i class="wr-icon-database"></i>
                                        <em><?php esc_html_e('Total posted projects', 'workreap'); ?></em>
                                    </div>
                                    <span><?php echo intval($posted_project_count); ?></span>
                                </li>
                            <?php } ?>
                            <?php if (isset($hired_project_count)) { ?>
                                <li>
                                    <div class="wr-total-title">
                                        <i class="wr-icon-shopping-bag"></i>
                                        <em><?php esc_html_e('Ongoing projects', 'workreap'); ?></em>
                                    </div>
                                    <span><?php echo intval($hired_project_count); ?></span>
                                </li>
                            <?php } ?>
                            <li>
                                <div class="wr-total-title">
                                    <a href="<?php echo esc_url($page_url); ?>" class="wr-btn"><?php esc_html_e('See all posted projects', 'workreap'); ?><i class="wr-icon-arrow-right"></i></a>
                                </div>
                            </li>
                        </ul>
                    <?php } ?>
                </div>
            </div>
        <?php
            echo ob_get_clean();
        }
    }
    add_action('workreap_project_freelancer_basic', 'workreap_project_freelancer_basic', 10, 1);
}

/**
 * List project filter status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_list_projects_status_filter')) {
    function workreap_list_projects_status_filter($type = '')
    {
        global $workreap_settings;
        $list = array(
            'any'       => esc_html__('All projects', 'workreap'),
            'draft'     => esc_html__('Drafted', 'workreap'),
            'pending'   => esc_html__('Pending', 'workreap'),
            'publish'   => esc_html__('Published', 'workreap'),
            'rejected'  => esc_html__('Rejected', 'workreap'),
            'hired'     => esc_html__('Ongoing', 'workreap'),
            'completed' => _x('Completed', 'Title for project status filter', 'workreap'),
            'disputed'  => esc_html__('Disputed', 'workreap'),
            'refunded'  => esc_html__('Refunded', 'workreap'),
            'cancelled' => esc_html__('Cancelled', 'workreap'),

        );
        $project_status             = !empty($workreap_settings['project_status']) ? $workreap_settings['project_status'] : '';
        if (!empty($project_status) && $project_status === 'pending') {
            $list['publish']    = esc_html__('Approved', 'workreap');
        } else {
            unset($list['pending']);
            unset($list['rejected']);
        }
        $list = apply_filters('workreap_filters_list_projects_status_filter_by', $list);
        return $list;
    }
    add_filter('workreap_list_projects_status_filter', 'workreap_list_projects_status_filter', 10, 1);
}

/**
 * List project filter status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_list_proposal_status_filter')) {
    function workreap_list_proposal_status_filter($type = '')
    {
        global $workreap_settings;
        $list = array(
            'any'       => esc_html__('All projects', 'workreap'),
            'draft'     => esc_html__('Drafted', 'workreap'),
            'pending'   => esc_html__('Pending', 'workreap'),
            'publish'   => esc_html__('Published', 'workreap'),
            'hired'     => esc_html__('Ongoing', 'workreap'),
            'disputed'  => esc_html__('Disputed', 'workreap'),
            'refunded'  => esc_html__('Refunded', 'workreap'),
            'completed' => _x('Completed', 'Title for proposal status filter', 'workreap'),
        );
        $proposal_status             = !empty($workreap_settings['proposal_status']) ? $workreap_settings['proposal_status'] : '';
        if (!empty($proposal_status) && $proposal_status === 'pending') {
            $list['publish']    = esc_html__('Approved', 'workreap');
        } else {
            unset($list['pending']);
            unset($list['rejected']);
        }
        $list = apply_filters('workreap_filters_list_proposal_status_filter_by', $list);
        return $list;
    }
    add_filter('workreap_list_proposal_status_filter', 'workreap_list_proposal_status_filter', 10, 1);
}

/**
 * List saved projects
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_project_saved_item')) {
    add_action('workreap_project_saved_item', 'workreap_project_saved_item', 10, 4);
    function workreap_project_saved_item($post_id = '', $user_post_id = '', $key = '', $type = '')
    {
        global $current_user;
        if (empty($user_post_id)) {
            $user_type      = apply_filters('workreap_get_user_type', $current_user->ID);
            $user_post_id   = workreap_get_linked_profile_id($current_user->ID, '', $user_type);
        }
        $post_type      = !empty($key) && $key === '_saved_tasks' ? 'tasks' : 'projects';
        $saved_items     = get_post_meta($user_post_id, $key, true);
        $saved_class     = !empty($saved_items) && in_array($post_id, $saved_items) ? 'bg-redheart' : 'bg-heart';
        $action          = !empty($saved_items) && in_array($post_id, $saved_items) ? '' : 'saved';

        ob_start();
        if (!empty($type) && $type == 'list') {
            $text           = !empty($saved_items) && in_array($post_id, $saved_items) ? esc_html__('Saved', 'workreap') : esc_html__('Save', 'workreap'); ?>
            <span class="wr_saved_items  <?php echo esc_attr($saved_class); ?>" data-action="<?php echo esc_attr($action); ?>" data-post_id="<?php echo intval($post_id); ?>" data-id="<?php echo intval($current_user->ID); ?>" data-type="<?php echo esc_attr($post_type); ?>"><i class="wr-icon-heart"></i><?php echo esc_html($text); ?></span>
        <?php }elseif (!empty($type) && $type == 'icon') {
	       ?>
            <span class="wr_saved_items  <?php echo esc_attr($saved_class); ?>" data-action="<?php echo esc_attr($action); ?>" data-post_id="<?php echo intval($post_id); ?>" data-id="<?php echo intval($current_user->ID); ?>" data-type="<?php echo esc_attr($post_type); ?>"><i class="wr-icon-heart"></i> </span>
        <?php } else {
            $text           = !empty($saved_items) && in_array($post_id, $saved_items) ? esc_html__('Saved', 'workreap') : esc_html__('Add to saved items', 'workreap'); ?>
            <span class="wr_saved_items wr-btnline <?php echo esc_attr($saved_class); ?>" data-action="<?php echo esc_attr($action); ?>" data-post_id="<?php echo intval($post_id); ?>" data-id="<?php echo intval($current_user->ID); ?>" data-type="<?php echo esc_attr($post_type); ?>"><i class="wr-icon-heart"></i>&nbsp;<?php echo esc_html($text); ?> </span>
        <?php
        }
        echo ob_get_clean();
    }
}

/**
 * Checkout cart project details
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_cart_project_details')) {
    add_action('workreap_cart_project_details', 'workreap_cart_project_details', 10, 1);
    function workreap_cart_project_details($cart_data = array())
    {
        $project_type       = !empty($cart_data['project_type']) ? $cart_data['project_type'] : '';
        $project_id         = !empty($cart_data['project_id']) ? intval($cart_data['project_id']) : '';
        $proposal_meta      = !empty($cart_data['proposal_meta']) ? $cart_data['proposal_meta'] : array();
        ob_start();
        ?>
        <div class="wr-pricing__content">
            <?php if (!empty($project_id)) { ?>
                <h4><?php echo get_the_title($project_id); ?></h4>
            <?php } ?>
            <ul class="wr-pricinglist">
                <?php if (!empty($project_id)) { ?>
                    <li>
                        <div class="wr-pricinglist__content">
                            <span><?php esc_html_e('Project name', 'workreap'); ?></span>
                            <span> <?php echo get_the_title($project_id); ?></span>
                        </div>
                    </li>
                <?php } ?>
                <?php if (!empty($project_type) && $project_type === 'fixed' && !empty($cart_data['milestone_id']) && !empty($proposal_meta['milestone'][$cart_data['milestone_id']]['title'])) { ?>
                    <li>
                        <div class="wr-pricinglist__content">
                            <span><?php esc_html_e('Milestone title', 'workreap'); ?></span>
                            <span> <?php echo esc_html($proposal_meta['milestone'][$cart_data['milestone_id']]['title']); ?></span>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
<?php
        echo ob_get_clean();
    }
}
