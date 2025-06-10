<?php

/**
 * Provide a public-facing funtions
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap_/public/partials
 */

/**
 * Allow user AI feature
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */

 if (!function_exists('workreap_allow_ai_feature')) {
    function workreap_allow_ai_feature($user_id = '')
    {
        global $workreap_settings;
        $task_allowed_ai   = true;
        $package_option =  !empty($workreap_settings['package_option']) && in_array($workreap_settings['package_option'], array('paid', 'employer_free')) ? true : false;
        
        if (!empty($package_option) &&  !empty($workreap_settings['enable_ai_packages']) ) {

            if (class_exists('WooCommerce')) {
                $user_type      = !empty($user_id) ? apply_filters('workreap_get_user_type', $user_id) : 0;
                if(!empty($user_type) && $user_type === 'employers'){
                    $expiry_date    = get_user_meta($user_id, 'employer_package_expriy_date', true);
                } else {
                    $expiry_date    = get_user_meta($user_id, 'package_expriy_date', true);
                }
                
                $today        = date("Y-m-d H:i:s");
                if (strtotime($today) > strtotime($expiry_date)) {
                    $task_allowed_ai  = false;
                }
               
                if ($task_allowed_ai) {
                    if(!empty($user_type) && $user_type === 'employers'){
                        $order_id = get_user_meta($user_id, 'employer_package_order_id', true);
                    } else {
                        $order_id = get_user_meta($user_id, 'package_order_id', true);
                    }
                    
                    if (!$order_id) {
                        $task_allowed_ai  = false;
                    }
                    $order = wc_get_order($order_id);

                    if (!empty($order) && 'completed' == $order->get_status()) {
                        $package_details = get_post_meta($order_id, 'package_details', true);
                        $enable_ai_packages = !empty($package_details['allow_ai']) ? ($package_details['allow_ai']) : 0;


                        if (!empty($enable_ai_packages) && $enable_ai_packages === 'yes') {
                            $task_allowed_ai  = true;
                        } else {
                            $task_allowed_ai  = false;
                        }
                    } else {
                        $task_allowed_ai  = false;
                    }
                }
            }
        } else {
            $task_allowed_ai  = true;
        }
        return apply_filters('workreap_allow_ai_feature', $task_allowed_ai, $user_id);
    }
}

/**
 * Allow user to create task
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */

if (!function_exists('workreap_task_create_allowed')) {
    function workreap_task_create_allowed($user_id = '')
    {
        global $workreap_settings;
        $task_allowed   = true;
        $package_option =  !empty($workreap_settings['package_option']) && in_array($workreap_settings['package_option'], array('paid', 'employer_free')) ? true : false;

        if (!empty($package_option)) {

            if (class_exists('WooCommerce')) {

                $expiry_date  = get_user_meta($user_id, 'package_expriy_date', true);
                $today        = date("Y-m-d H:i:s");
                if (strtotime($today) > strtotime($expiry_date)) {
                    $task_allowed  = false;
                }
                if ($task_allowed) {
                    $order_id = get_user_meta($user_id, 'package_order_id', true);
                    if (!$order_id) {
                        $task_allowed  = false;
                    }
                    $order = wc_get_order($order_id);

                    if (!empty($order) && 'completed' == $order->get_status()) {

                        $package_details = get_post_meta($order_id, 'package_details', true);

                        $number_tasks_allowed = !empty($package_details['number_tasks_allowed']) ? intval($package_details['number_tasks_allowed']) : 0;

                        $task_count = workreap_get_user_tasks($user_id, array('publish'),'',$order_id);

                        if ($number_tasks_allowed > $task_count) {
                            $task_allowed  = true;
                        } else {
                            $task_allowed  = false;
                        }
                    } else {
                        $task_allowed  = false;
                    }
                }
            }
        } else {
            $task_allowed  = true;
        }
        return apply_filters('workreap_task_create_allowed', $task_allowed, $user_id);
    }
}
/**
 * Get package detail
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */

if (!function_exists('workreap_get_package')) {
    function workreap_get_package($user_id = '')
    {
        global $workreap_settings;
        $package_detail  = array();
        $package_option    =  !empty($workreap_settings['package_option']) && in_array($workreap_settings['package_option'], array('paid', 'employer_free')) ? true : false;

        if (!empty($package_option)) {
            $package_detail['type']  = 'paid';
            $order_id = get_user_meta($user_id, 'package_order_id', true);

            if (!empty($order_id) && class_exists('WooCommerce')) {
                $order = wc_get_order($order_id);

                if (!empty($order) && 'completed' == $order->get_status()) {
                    $package_details  = get_user_meta($user_id, 'freelancer_package_details', true);
                    $task_count       = workreap_get_user_tasks($user_id, array('publish'),'',$order_id);
                    $package_detail['package']    = $package_details;
                    $package_detail['task_count'] = $task_count;
                    $package_detail['order_id'] = $order_id;
                }
            }
        } else {
            $package_detail['type']  = 'free';
        }
        return apply_filters('workreap_get_package', $package_detail, $user_id);
    }
}

/**
 * Get user package
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */

if (!function_exists('workreap_get_user_tasks')) {
    function workreap_get_user_tasks($user_id = '', $post_status = 'any', $featured = '',$order_id='')
    {
        $workreap_args = array(
            'post_type'         => 'product',
            'post_status'       => $post_status,
            'numberposts'       => -1,
            'paged'             => 1,
            'author'            => $user_id,
            'orderby'           => 'date',
            'order'             => 'DESC',
            'tax_query'         => array(
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => 'tasks',
                ),
            ),
        );
        if(!empty($order_id)){
            $workreap_args['meta_query']   = array(
                array(
                    'key' 			=> '_package_order_id',
                    'value' 		=> $order_id,
                    'compare' 		=> '='
                )
            ); 
        }
        $task_listings  = get_posts($workreap_args);
        if (!empty($featured)) {
            $count_task = 0;
            if (!empty($task_listings)) {
                foreach ($task_listings as $task) {
                    $product = wc_get_product($task->ID);
                    if ($product->is_featured()) {
                        $count_task++;
                    }
                }
            }
        } else {
            $count_task = !empty($task_listings) && is_array($task_listings) ? count($task_listings) : 0;
        }
        return $count_task;
    }
}

/**
 * Get user employer projects
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */

if (!function_exists('workreap_get_user_projects')) {
    function workreap_get_user_projects($user_id = '', $post_status = 'any', $featured = '')
    {
        $workreap_args = array(
            'post_type'         => 'product',
            'post_status'       => $post_status,
            'numberposts'       => -1,
            'paged'             => 1,
            'author'            => $user_id,
            'orderby'           => 'date',
            'order'             => 'DESC',
            'tax_query'         => array(
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => 'projects',
                ),
            ),
        );

        $task_listings  = get_posts($workreap_args);
        if (!empty($featured)) {
            $count_task = 0;
            if (!empty($task_listings)) {
                foreach ($task_listings as $task) {
                    $product = wc_get_product($task->ID);
                    if ($product->is_featured()) {
                        $count_task++;
                    }
                }
            }
        } else {
            $count_task = !empty($task_listings) && is_array($task_listings) ? count($task_listings) : 0;
        }
        return $count_task;
    }
}


/**
 * Get template
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */

if (!function_exists('workreap_get_template')) {
    function workreap_get_template($template_name = '', $args = array(), $template_path = 'workreap', $default_path = '')
    {
        if (empty($template_name)) {
            return;
        }

        if (!empty($args) && is_array($args)) {
            extract($args);
        }

        $located = workreap_locate_template($template_name, $template_path, $default_path);
        if (!empty($return) && $return === true) {
            return $located;
        } else {
            include($located);
        }
    }
}

/**
 * Locate template
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_locate_template')) {
    function workreap_locate_template($template_name, $template_path = 'workreap', $default_path = '')
    {
        $template = locate_template(
            array(
                trailingslashit($template_path) . $template_name,
            )
        );
        if (!$template && $default_path !== false) {
            $default_path = $default_path ? $default_path : untrailingslashit(plugin_dir_path(dirname(__DIR__))) . '/templates/';
            if (file_exists(trailingslashit($default_path) . $template_name)) {
                $template = trailingslashit($default_path) . $template_name;
            }
        }
        return apply_filters('workreap_locate_template', $template, $template_name, $template_path);
    }
}

/**
 * Plugin template part
 *
 * @return 
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_get_template_part')) {
    function workreap_get_template_part($slug, $name = '', $args = '', $template_path = 'workreap', $default_path = '')
    {
        $template = '';
        if ($name) {
            $template = workreap_locate_template("{$slug}-{$name}.php", $template_path, $default_path);
        }
        if (!$template) {
            $template = workreap_locate_template("{$slug}.php", $template_path, $default_path);
        }
        $template = apply_filters('workreap_get_template_part', $template, $slug, $name, $args);
        if ($template) {
            load_template($template, FALSE, $args);
        }
    }
}

/**
 * Plugin pagination
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_paginate')) {
    function workreap_paginate($workreap_query = '', $class = '')
    {
        if ($workreap_query) {
            $workreap_total = $workreap_query->max_num_pages;
        } else {
            global $wp_query;
            $workreap_total = $wp_query->max_num_pages;
        }

        if ($workreap_total > 1) {
            $wr_number = 999999999;
            if (!empty($class)) { ?>
                <div class="<?php echo esc_attr($class); ?>">
                <?php } ?>
                <div class="wr-pagination">
                    <?php
                    echo paginate_links(array(
                        'base'         => str_replace($wr_number, '%#%', html_entity_decode(get_pagenum_link($wr_number, false))),
                        'total'        => $workreap_total,
                        'current'      => max(1, get_query_var('paged')),
                        'format'       => '?paged=%#%',
                        'show_all'     => false,
                        'type'         => 'list',
                        'end_size'     => 2,
                        'mid_size'     => 1,
                        'prev_next'    => true,
                        'prev_text'    => do_shortcode('<i class="wr-icon-chevron-left"></i>'),
                        'next_text'    => do_shortcode('<i class="wr-icon-chevron-right"></i>'),
                        'add_args'     => false
                    ));
                    ?>
                </div>
                <?php
                if (!empty($class)) { ?>
                </div>
                <?php
                }
            }
        }
    }

/**
 * Get order IDs by product
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_get_order_ids_by_product')) {
    function workreap_get_order_ids_by_product($product_id, $orders_status = array())
    {
        global $wpdb;
        $table_posts = $wpdb->prefix . "posts";
        $table_items = $wpdb->prefix . "woocommerce_order_items";
        $table_itemmeta = $wpdb->prefix . "woocommerce_order_itemmeta";

        if (isset($orders_status) && is_array($orders_status) && count($orders_status) > 0) {
            $orders_statuses = implode(', ', $orders_status);
            $orders_statuses = '"' . $orders_statuses . '"';
        } else {
            $orders_statuses = "'wc-completed', 'wc-pending', 'wc-on-hold', 'wc-cancelled', 'wc-refunded', 'wc-failed', 'wc-processing'";
        }

        $orders_ids = $wpdb->get_col(
            "SELECT $table_items.order_id
        FROM $table_itemmeta, $table_items, $table_posts
        WHERE $table_items.order_item_id = $table_itemmeta.order_item_id
        AND $table_items.order_id = $table_posts.ID
        AND $table_posts.post_status IN ( $orders_statuses )
        AND $table_itemmeta.meta_key LIKE '_product_id'
        AND $table_itemmeta.meta_value LIKE '$product_id'
        ORDER BY $table_items.order_item_id DESC"
        );
        return $orders_ids;
    }
}

/**
 * Template page URL
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_get_page_template_url')) {
    function workreap_get_page_template_url($template_name)
    {
        $pages = query_posts(array(
            'post_type' => 'page',
            'meta_key'  => '_wp_page_template',
            'meta_value' => $template_name
        ));
        $url = 'javascript:void(0);';
        if (isset($pages[0])) {
            $url = get_page_link($pages[0]['id']);
        }
        return $url;
    }
}


/**
 * Verify token
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_verify_token')) {
    function workreap_verify_token($wr_check_security)
    {
        $json   = array();
        if (!wp_verify_nonce($wr_check_security, 'ajax_nonce')) {
            $json['type']               = 'error';
            $json['message']         = esc_html__('Restricted Access', 'workreap');
            $json['message_desc']     = esc_html__('You are not allowed to perform this action.', 'workreap');
            wp_send_json($json);
        }
    }
}

/**
 * Verify admin token
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_verify_admin_token')) {
    function workreap_verify_admin_token($wr_check_security)
    {
        $json   = array();
        if (!wp_verify_nonce($wr_check_security, 'ajax_nonce')) {
            $json['type']               = 'error';
            $json['message']         = esc_html__('Restricted Access', 'workreap');
            $json['message_desc']     = esc_html__('You are not allowed to perform this action.', 'workreap');
            wp_send_json($json);
        }
    }
}

/**
 * Verify post author
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_verify_post_author')) {
    function workreap_verify_post_author($post_id)
    {
        global $current_user;
        $post_author  = !empty($post_id) ? get_post_field('post_author', $post_id) : 0;
        $post_author  = !empty($post_author) ? $post_author : 0;
        $json         = array();
        if (empty($post_author) || $post_author != $current_user->ID) {
            $json['type']               = 'error';
            $json['message']         = esc_html__('Restricted Access', 'workreap');
            $json['message_desc']     = esc_html__('You are not allowed to perform this action.', 'workreap');
            wp_send_json($json);
        }
    }
}

/**
 * User authentication
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_authenticate_user_validation')) {
    function workreap_authenticate_user_validation($user_id, $validate_user = '')
    {
        global $current_user;
        $json   = array();
        if (!is_user_logged_in()) {
            $json['type']               = 'error';
            $json['message']         = esc_html__('Restricted Access', 'workreap');
            $json['message_desc']     = esc_html__('You are not allowed to perform this action.', 'workreap');
            wp_send_json($json);
        }

        if (!empty($validate_user) && $validate_user === 'both') {
            if ($user_id != $current_user->ID) {
                $json['type']               = 'error';
                $json['message']            = esc_html__('Restricted Access', 'workreap');
                $json['message_desc']       = esc_html__('You are not allowed to perform this action.', 'workreap');
                wp_send_json($json);
            }
        }
    }
}

/**
 * @Strong password validation
 * @return
 */
if (!function_exists('workreap_strong_password_validation')) {
    add_action('workreap_strong_password_validation', 'workreap_strong_password_validation', 10, 1);
    function workreap_strong_password_validation($password)
    {
	    global $workreap_settings;
	    $password_strength  =  !empty($workreap_settings['user_password_strength']) ? $workreap_settings['user_password_strength'] : array('length');

        if (!empty($password)) {

	        $choices = array(
		        'length'   			=> esc_html__('Password length should be minimum 6', 'workreap'),
		        'upper'				=> esc_html__('Password must contain one upper case', 'workreap'),
		        'lower'  			=> esc_html__('Password must contain one lower case', 'workreap'),
		        'special_character' => esc_html__('Password must contain one special character', 'workreap'),
		        'number'  			=> esc_html__('Password must contain one number', 'workreap'),
	        );

	        $number 		= preg_match('@[0-9]@', $password);
	        $uppercase 		= preg_match('@[A-Z]@', $password);
	        $lowercase 		= preg_match('@[a-z]@', $password);
	        $specialChars 	= preg_match('@[^\w]@', $password);
	        $password_error = '';

	        foreach($password_strength as $item){
		        if( $item === 'length'){
			        if( strlen($password) < 6 ) {
				        $password_error = $choices[$item];
			        }
		        }else if( $item === 'upper' && !$uppercase ){
			        $password_error = $choices[$item];
		        }else if( $item === 'lower' && !$lowercase ){
			        $password_error = $choices[$item];
		        }else if( $item === 'number' && !$number ){
			        $password_error = $choices[$item];
		        }else if( $item === 'special_character' && !$specialChars ){
			        $password_error = $choices[$item];
		        }
	        }

	        if(!empty($password_error)){
		        $json['type'] 		= 'error';
		        $json['message'] 		= esc_html__('Oops!', 'workreap');
		        $json['message_desc'] 	= $password_error;
		        wp_send_json($json);
	        }
        }
    }
}

/**
 * Get account settings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_get_account_settings')) {
    function workreap_get_account_settings($type = '')
    {
        $settings = array(
            'freelancers' => array(
                '_deactivate_profile'   => esc_html__('Make my profile photo visible to friends and everyone', 'workreap')
            ),
            'employers' => array(
                '_deactivate_profile'   => esc_html__('Make my profile photo visible to friends and everyone', 'workreap')
            ),
        );
        $settings  = apply_filters('workreap_filters_account_settings', $settings);
        return !empty($type) && !empty($settings[$type]) ? $settings[$type] : $settings;
    }
}

/**
 * Register task product type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (class_exists('WC_Product') && !function_exists('register_tasks_product_type')) {
    function register_tasks_product_type()
    {
        class WC_Product_Tasks extends WC_Product
        {
            protected $product_type;
            public function __construct($product)
            {
                $this->product_type = 'tasks';
                parent::__construct($product);
            }

            public function get_type()
            {
                return 'tasks';
            }

            /**
             * Get video links.
             *
             * @since  1.0.0
             * @param  string $context What the value is for. Valid values are view and edit.
             * @return array
             */
            public function get_video_links($context = 'view')
            {
                $video_files = array();
                $meta_values = array_filter((array) get_post_meta($this->get_id(), 'workreap_video_links_files', true));

                foreach ($meta_values as $file_id => $file) {
                    $video_files[] = array(
                        'id'   => $file_id, // do not cast as int as this is a hash
                        'name' => $file['name'],
                        'file' => $file['file'],
                    );
                }

                return $video_files;
            }
        }
        function product_cat_to_freelancers_post_type()
        {
            register_taxonomy_for_object_type('product_cat', 'freelancers');
        }
        add_action('init', 'product_cat_to_freelancers_post_type');
    }
    add_action('init', 'register_tasks_product_type');
}

/**
 * Register sub task product type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (class_exists('WC_Product') && !function_exists('register_subtasks_product_type')) {
    function register_subtasks_product_type()
    {
        class WC_Product_Subtasks extends WC_Product
        {
            protected $product_type;
            public function __construct($product)
            {
                $this->product_type = 'subtasks';
                parent::__construct($product);
            }

            public function get_type()
            {
                return 'subtasks';
            }
        }
    }
    add_action('init', 'register_subtasks_product_type');
}

/**
 * Register packages product type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (class_exists('WC_Product') && !function_exists('register_packages_product_type')) {
    function register_packages_product_type()
    {
        class WC_Product_Packages extends WC_Product
        {
            protected $product_type;
            public function __construct($product)
            {
                $this->product_type = 'packages';
                parent::__construct($product);
            }

            public function get_type()
            {
                return 'packages';
            }
        }
    }
    add_action('init', 'register_packages_product_type');
}

/**
 * Register employer packages product type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (class_exists('WC_Product') && !function_exists('register_employer_packages_product_type')) {
    function register_employer_packages_product_type()
    {
        class WC_Product_Employer_Packages extends WC_Product
        {
            protected $product_type;
            public function __construct($product)
            {
                $this->product_type = 'employer_packages';
                parent::__construct($product);
            }

            public function get_type()
            {
                return 'employer_packages';
            }
        }
    }
    add_action('init', 'register_employer_packages_product_type');
}

/**
 * Register projects product type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (class_exists('WC_Product') && !function_exists('register_projects_product_type')) {
    function register_projects_product_type()
    {
        class WC_Product_projects extends WC_Product
        {
            protected $product_type;
            public function __construct($product)
            {
                $this->product_type = 'projects';
                parent::__construct($product);
            }
            public function get_type()
            {
                return 'projects';
            }
        }
    }
    add_action('init', 'register_projects_product_type');
}


/**
 * Register funds product type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (class_exists('WC_Product') && !function_exists('register_funds_product_type')) {
    function register_funds_product_type()
    {
        class WC_Product_Funds extends WC_Product
        {
            protected $product_type;
            public function __construct($product)
            {
                $this->product_type = 'funds';
                parent::__construct($product);
            }

            public function get_type()
            {
                return 'funds';
            }
        }
    }
    add_action('init', 'register_funds_product_type');
}

/**
 * User verification check
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_get_username')) {
    function workreap_get_username($profile_id = '')
    {
        global $workreap_settings;
        $shortname_option  =  !empty($workreap_settings['shortname_option']) ? $workreap_settings['shortname_option'] : '';
        $title  = get_the_title($profile_id);

        if (!empty($shortname_option)) {
            $full_name    = explode(' ', $title);
            $first_name    = !empty($full_name[0]) ? ucfirst($full_name[0]) : '';
            $second_name  = !empty($full_name[1]) ? ' ' . strtoupper($full_name[1][0]) : '';
            return esc_html($first_name . $second_name);
        } else {
            return esc_html($title);
        }
    }
    add_filter('workreap_get_username', 'workreap_get_username', 10, 1);
}

/**
 * price format
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_price_format')) {
    function workreap_price_format($price = '', $type = 'echo', $wcprice = '')
    {
        $price  = !empty($price) ? str_replace(',', '', $price) : $price;
        if (class_exists('WooCommerce')) {
            if (function_exists('wmc_get_price') && isset($wcprice)) {
                $price = wc_price(wmc_get_price($price)); //WooCommerce Multi Currency Compatibility
            } else {
                $price = wc_price($price);
            }
        } else {
            $currency   = workreap_get_current_currency();
            $price      = !empty($currency['symbol']) ? $currency['symbol'] . $price : '$';
        }

        if ($type === 'return') {
            return wp_strip_all_tags($price);
        } else {
            echo wp_strip_all_tags($price);
        }
    }
    add_action('workreap_price_format', 'workreap_price_format', 10, 3);
    add_filter('workreap_price_format', 'workreap_price_format', 10, 3);
}

/**
 * Get woocommmerce currency settings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_get_current_currency')) {
    function workreap_get_current_currency()
    {
        $currency  = array();
        if (class_exists('WooCommerce')) {
            $currency['code']  = get_woocommerce_currency();
            $currency['symbol']  = get_woocommerce_currency_symbol();
        } else {
            $currency['code']  = 'USD';
            $currency['symbol']  = '$';
        }
        return $currency;
    }
}

/**
 * Update unique key with post type
 *
 * @return
 * @throws error
 */
if (!function_exists('workreap_wp_after_insert_post')) {
    add_action('wp_insert_post', 'workreap_wp_after_insert_post', 5, 3);
    function workreap_wp_after_insert_post($post_id = '', $postdata = array(), $update = false)
    {
        // do some login
        if (
            $update === false
            && !empty($postdata->post_type)
            && ($postdata->post_type === 'freelancers'
                || $postdata->post_type === 'employers'
                || $postdata->post_type === 'product')
        ) {
            $key = workreap_unique_increment(10);
            update_post_meta($post_id, 'unique_key', $key);
        }
    }
}

/**
 * @workreap Unique Increment
 * @return {}
 */
if (!function_exists('workreap_unique_increment')) {

    function workreap_unique_increment($length = 5)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}


/**
 * List date format
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_list_tasks_status')) {
    function workreap_list_tasks_status($type = '')
    {
        global $workreap_settings;
        $remove_cancel_order  =  !empty($workreap_settings['remove_cancel_order']) ? $workreap_settings['remove_cancel_order'] : 'no';

        $list = array(
            'any'       => esc_html__('All orders', 'workreap'),
            'hired'     => esc_html__('Ongoing', 'workreap'),
            'completed' => esc_html__('Complete now', 'workreap'),
            'cancelled' => esc_html__('Cancel now', 'workreap'),
        );
        $list = apply_filters('workreap_filters_list_tasks_status', $list);

        if(!empty($remove_cancel_order) && $remove_cancel_order === 'yes'){
            unset($list['cancelled']);
        }

        return $list;
    }
    add_filter('workreap_list_tasks_status', 'workreap_list_tasks_status', 10, 1);
}


/**
 * List date format
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_list_tasks_order_status_filter')) {
    function workreap_list_tasks_order_status_filter($type = '')
    {
        $list = array(
            'any'       => esc_html__('All orders', 'workreap'),
            'hired'     => esc_html__('Ongoing', 'workreap'),
            'completed' => _x('Completed', 'Title for order status', 'workreap' ),
            'cancelled' => esc_html__('Cancelled', 'workreap'),
        );
        $list = apply_filters('workreap_filters_list_tasks_order_status_filter_by', $list);
        return $list;
    }
    add_filter('workreap_list_tasks_order_status_filter', 'workreap_list_tasks_order_status_filter', 10, 1);
}

/**
 * List date format
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_list_tasks_status_filter')) {
    add_filter('workreap_list_tasks_status_filter', 'workreap_list_tasks_status_filter', 10, 1);
    function workreap_list_tasks_status_filter($type = '')
    {
        global $workreap_settings;
        $list = array(
            'any'       => esc_html__('All tasks', 'workreap'),
            'draft'     => esc_html__('Drafted', 'workreap'),
            'pending'   => esc_html__('Pending', 'workreap'),
            'publish'   => esc_html__('Published', 'workreap'),
            'rejected' => esc_html__('Rejected', 'workreap'),
        );

        $service_status             = !empty($workreap_settings['service_status']) ? $workreap_settings['service_status'] : $list;

        if( !empty($service_status) && $service_status === 'pending'){
            $list['publish']    = esc_html__('Approved', 'workreap');
        }

        return $list;
    }
}
/**
 * Date format
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_date_format_fix')) {
    add_filter('workreap_date_format_fix', 'workreap_date_format_fix', 10, 1);
    function workreap_date_format_fix($dateStr)
    {
        if (empty($dateStr)) {
            return '';
        }

        global $workreap_settings;
        $calendar_format  =  !empty($workreap_settings['dateformat']) ? $workreap_settings['dateformat'] : 'Y-m-d';

        if (!empty($calendar_format) && $calendar_format === 'd-m-Y') {
            $dateStr  = str_replace('/', '-', $dateStr);
            $parts   = explode("-", $dateStr);
            $_date  = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
            return $_date;
        } else if (!empty($calendar_format) && $calendar_format === 'd/m/Y') {
            $dateStr    = str_replace('/', '-', $dateStr);
            $parts      = explode("-", $dateStr);
            $_date      = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
            return $_date;
        } else {
            return $dateStr;
        }
    }
}

/**
 * single profile page
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('wr_profile_single_template')) {
    function wr_profile_single_template($single = '')
    {
        global $post;
        if ($post->post_type == 'freelancers') {
            return workreap_load_template('templates/single-freelancer');
        } else if ($post->post_type == 'employers') {
            return workreap_get_template('single-employer.php', array('return' => true));
        }
        return $single;
    }
    add_filter('single_template', 'wr_profile_single_template');
}

/**
 * User address
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_user_address')) {
    function workreap_user_address($post_id = '')
    {
        global $workreap_settings;
        $enable_state	= !empty($workreap_settings['enable_state']) ? $workreap_settings['enable_state'] : false;
        $address_format = !empty($workreap_settings['address_format']) ? $workreap_settings['address_format'] : 'state_country';
        $address        = '';
        $location       = !empty($post_id) ? get_post_meta($post_id, 'location', true) : array();
        $address        = !empty($location['country']['long_name']) ? $location['country']['long_name'] : '';
        $countries      = array();
        if (class_exists('WooCommerce')) {
            $countries_obj   = new WC_Countries();
            $countries   = $countries_obj->get_allowed_countries('countries');
        }
        if (!empty($address_format) && $address_format == 'state_country') {
            $state  = !empty($location['administrative_area_level_1']['long_name']) ? $location['administrative_area_level_1']['long_name'] : '';

            if (!empty($state)) {
                $address    = $state . ', ' . $address;
            }
        } else if (!empty($address_format) && $address_format == 'city_country') {
            $city  = !empty($location['locality']['long_name']) ? $location['locality']['long_name'] : '';

            if (!empty($city)) {
                $address    = $city . ', ' . $address;
            }
        } else if (!empty($address_format) && $address_format == 'city_state_country') {
            $state  = !empty($location['administrative_area_level_1']['long_name']) ? $location['administrative_area_level_1']['long_name'] : '';
            $city   = !empty($location['locality']['long_name']) ? $location['locality']['long_name'] : '';

            if (!empty($state)) {
                $address    = $state . ', ' . $address;
            }

            if (!empty($city)) {
                $address    = $city . ', ' . $address;
            }
        }

        if (empty($workreap_settings['enable_zipcode'])) {
            $country       = !empty($post_id) ? get_post_meta($post_id, 'country', true) : '';
            $address       = $country;
            if (class_exists('WooCommerce')) {
                $countries_obj   = new WC_Countries();
                $countries      = $countries_obj->get_allowed_countries('countries');
                if (!empty($countries[$address])) {
                    $address       = $countries[$address];
                }
                if (!empty($address_format) && ($address_format == 'state_country' || $address_format == 'city_state_country') && !empty($enable_state) ) {
                    $state     = get_post_meta($post_id, 'state', true) ;
                    if( !empty($state) ){
                        $state_list = $countries_obj->get_states( $country );
                        if( !empty($state_list[$state])){
                            $address    = $state_list[$state].', '.$address;
                        }
                    }
                }
            }
        }

        if (empty($address)) {
            $country        = !empty($post_id) ? get_post_meta($post_id, 'country', true) : array();
            if (!empty($country)) {
                $address    = !empty($countries[$country]) ? $countries[$country] : '';
            }
        }
        return $address;
    }
    add_filter('workreap_user_address', 'workreap_user_address', 99, 1);
}

/**
 * Canonical
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_canonical')) {
    add_filter('redirect_canonical', 'workreap_canonical');
    function workreap_canonical($redirect_url)
    {
        if (is_paged() && (is_singular('freelancers') || is_singular('employers'))) {
            $redirect_url = false;
        }
        return $redirect_url;
    }
}

/**
 * get total service companies
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_get_post_count_by_meta')) {
    function workreap_get_post_count_by_meta($post_type = '', $status = array(), $meta_array = array(), $return = 'count', $count = '-1')
    {
        $args = array(
            'post_type'     => $post_type,
            'posts_per_page' => $count,
            'post_status'   => $status
        );

        if (!empty($meta_array)) {
            foreach ($meta_array as $meta) {
                $args['meta_query'][]  = $meta;
            }
        }
        $post_data   = get_posts($args);
        if ($return === 'count') {
            $post_data  = !empty($post_data) ? count($post_data) : 0;
        }
        return $post_data;
    }
}

/**
 * Task order statuses
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_get_list_date_format')) {
    function workreap_get_list_date_format($type = '')
    {
        $current_datetime = current_time('timestamp');
        $list = array(
            'F j, Y'    => sprintf(esc_html__('%s (F j, Y)', 'workreap'), date('F j, Y', $current_datetime)),
            'Y-m-d'     => sprintf(esc_html__('%s (Y-m-d)', 'workreap'), date('Y-m-d', $current_datetime)),
            'd-m-Y'     => sprintf(esc_html__('%s (d-m-Y)', 'workreap'), date('d-m-Y', $current_datetime)),
            'm/d/Y'     => sprintf(esc_html__('%s (m/d/Y)', 'workreap'), date('m/d/Y', $current_datetime)),
            'd/m/Y'     => sprintf(esc_html__('%s (d/m/Y)', 'workreap'), date('d/m/Y', $current_datetime))
        );
        $list = apply_filters('workreap_filters_get_list_date_format', $list);
        return $list;
    }
    add_filter('workreap_get_list_date_format', 'workreap_get_list_date_format', 10, 1);
}

/**
 * Workreap user registration types
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_user_types')) {
    function workreap_get_user_types($defult_role = '')
    {
        $list   = array('employers' => esc_html__('Employer', 'workreap'), 'freelancers' => esc_html__('Freelancer', 'workreap'));
        $list   = apply_filters('workreap_filter_get_user_types', $list);
        return $list;
    }
    add_filter('workreap_get_user_types', 'workreap_get_user_types', 10, 1);
}

/**
 * Task ratings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_complete_task_ratings')) {
    function workreap_complete_task_ratings($order_id = '', $task_id = '', $rating = '', $rating_title = '', $rating_details = '', $user_id = '')
    {
        $user_details       = get_user_by('ID', $user_id);
        $user_email         = !empty($user_details->user_email) ? $user_details->user_email : '';
        $freelancer_id          = get_post_meta($order_id, 'freelancer_id', true);
        $user_type          = 'employers';
        $linked_profile     = workreap_get_linked_profile_id($user_id, '', $user_type);
        $user_profiel_name  = workreap_get_username($linked_profile);
        $comment_id = wp_insert_comment(array(
            'comment_post_ID'      => $task_id,
            'comment_author'       => $user_profiel_name,
            'comment_author_email' => $user_email,
            'comment_author_url'   => '',
            'comment_content'      => $rating_details,
            'comment_type'         => 'rating',
            'comment_parent'       => 0,
            'user_id'              => $user_id,
            'comment_date'         => date('Y-m-d H:i:s'),
            'comment_approved'     => 1,
        ));
        update_comment_meta($comment_id, 'rating', intval($rating));
        update_comment_meta($comment_id, '_task_order', intval($order_id));
        update_comment_meta($comment_id, '_rating_title', ($rating_title));
        update_comment_meta($comment_id, 'freelancer_id', intval($freelancer_id));
        update_comment_meta($comment_id, 'verified', 1);
        update_post_meta($order_id, '_rating_id', $comment_id);
        workreap_product_rating($task_id);
        workreap_freelancer_rating($freelancer_id);
    }
}
/**
 * Task ratings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_product_rating')) {
    function workreap_product_rating($task_id = '')
    {
        $args             = array('type' => 'rating', 'post_id' => $task_id);
        $comments         = get_comments($args);
        $total_comments    = !empty($comments) ? count($comments) : 0;
        $wc_rating_count = array();
        $rating_total        = 0;
        if (!empty($comments)) {
            foreach ($comments as $comment) {

                $rating             = !empty($comment->comment_ID) ? get_comment_meta($comment->comment_ID, 'rating', true) : 0;
                $rating                = !empty($rating) ? intval($rating) : 0;
                $rating_total        = $rating_total + $rating;
                $rating_index        = !empty($wc_rating_count[$rating]) ? intval($wc_rating_count[$rating]) : 0;
                $wc_rating_count[$rating]    = $rating_index + 1;
            }
            $wc_average_rating  = !empty($rating_total) ? number_format($rating_total / $total_comments, 2, '.', '') : 0;
            update_post_meta($task_id, '_wc_rating_count', $wc_rating_count);
            update_post_meta($task_id, '_wc_review_count', $total_comments);
            update_post_meta($task_id, '_wc_average_rating', $wc_average_rating);
        }
    }
}
/**
 * Task ratings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_freelancer_rating')) {
    function workreap_freelancer_rating($freelancer_id = '')
    {
        $user_rating    = 0;
        $user_reviews   = 0;
        $args = array(
            'author'        =>  $freelancer_id,
            'post_type'     =>  'product',
            'fields'        =>   'ids',
            'post_status'   =>  array('publish'),
            'numberposts'   =>  -1
        );
        $products       = get_posts($args);
        $product_count  = 0;

        if (!empty($products)) {
            foreach ($products as $product) {
                $rev_product  = wc_get_product($product);
                $rating       = $rev_product->get_average_rating();
                $count        = $rev_product->get_rating_count();
                if (!empty($count)) {
                    $user_rating  = $user_rating + $rating;
                    $product_count++;
                    $user_reviews  = $user_reviews + $count;
                }
            }
        }

        $proposal_args = array(
            'author'        =>  $freelancer_id,
            'post_type'     =>  'proposals',
            //'fields'        =>   'ids',
            'post_status'   =>  array('completed'),
            'numberposts'   =>  -1
        );
        $proposals       = get_posts($proposal_args);
        if (!empty($proposals)) {
            foreach ($proposals as $proposal) {
                $product_count++;
                $rating         = get_post_meta($proposal->ID, '_rating', true);
                $rating         = !empty($rating) ? $rating : 0;
                $user_rating    = $user_rating + $rating;
                $user_reviews   = $user_reviews + 1;
            }
        }

        $linked_freelancers_id  = workreap_get_linked_profile_id($freelancer_id, '', 'freelancers');
        $total_rating       = !empty($user_rating) && !empty($product_count) ? $user_rating / $product_count : 0;
        update_post_meta($linked_freelancers_id, 'wr_review_users', $user_reviews);
        update_post_meta($linked_freelancers_id, 'wr_user_rating', $user_rating);
        update_post_meta($linked_freelancers_id, 'wr_review_posts', $product_count);
        update_post_meta($linked_freelancers_id, 'wr_total_rating', $total_rating);
    }
}
/**
 * Task get user success rate
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_success_rate')) {
    function workreap_success_rate($user_id)
    {
        $completed_arg  = array(
            array(
                'key'    => 'freelancer_id',
                'value'     => $user_id,
                'compare'   => '=',
                'type'     => 'NUMERIC'
            ),
            array(
                'key'    => '_task_status',
                'value'     => 'completed',
                'compare'   => '=',
            )
        );
        $completed_count    = workreap_get_post_count_by_meta('shop_order', array('wc-pending', 'wc-on-hold', 'wc-processing', 'wc-completed'), $completed_arg);
        $cancelled_arg  = array(
            array(
                'key'    => 'freelancer_id',
                'value'     => $user_id,
                'compare'   => '=',
                'type'     => 'NUMERIC'
            ),
            array(
                'key'    => '_task_status',
                'value'     => 'cancelled',
                'compare'   => '=',
            )
        );
        $cancelled_count  = workreap_get_post_count_by_meta('shop_order', array('wc-pending', 'wc-cancelled', 'wc-on-hold', 'wc-processing', 'wc-completed'), $cancelled_arg);
        $completed_count  = !empty($completed_count) ? intval($completed_count) : 0;
        $cancelled_count  = !empty($cancelled_count) ? intval($cancelled_count) : 0;
        $total_count      = $completed_count + $cancelled_count;
        $success_rate     = !empty($completed_count) ? intval(($completed_count / $total_count) * 100) : 0;
        return $success_rate;
    }
}

/**
 * Order refund price
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_order_price')) {
    function workreap_order_price($order_id)
    {
        $subtotal = 0;
        if (class_exists('WooCommerce')) {
            $order          = wc_get_order($order_id);
            $user_type        = !empty($order) ? apply_filters('workreap_get_user_type', $order->get_user_id()) : 0;
            $order_meta         = get_post_meta( $order_id, 'cus_woo_product_data', true );
            $order_meta         = !empty($order_meta) ? $order_meta : array();
            $processing_fee		= !empty($order_meta['processing_fee']) ? $order_meta['processing_fee'] : 0.0;

            // Get and Loop Over Order Items
            if (!empty($order)) {
                foreach ($order->get_items() as $item_id => $item) {
                    $subtotal += (float) $item->get_subtotal();
                }

                if ($user_type == 'employers') {
                    $subtotal += (float) $order->get_total_tax();
                    $subtotal += (float) $processing_fee;
                }
            }
        }
        return $subtotal;
    }
}

/**
 * Dispute status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_dispute_status')) {
    function workreap_dispute_status($dispute_id)
    {
        $status = get_post_status($dispute_id);
        $dispute_status = '';
        switch ($status) {
            case "publish":
                $dispute_status = esc_html__('Refund requested', 'workreap');
                break;
            case "declined":
                $dispute_status = esc_html__('Declined', 'workreap');
                break;
            case "refunded":
                $dispute_status = esc_html__('Refunded', 'workreap');
                break;
            case "resolved":
                $dispute_status = esc_html__('Resolved', 'workreap');
                break;
            case "disputed":
                $dispute_status = esc_html__('Disputed', 'workreap');
                break;
            case "processing":
                $dispute_status = esc_html__('Processing', 'workreap');
                break;
            case "cancelled":
                $dispute_status = esc_html__('Cancelled', 'workreap');
                break;
            default:
                $dispute_status = esc_html__('New', 'workreap');
        }
        return $dispute_status;
    }
}

/**
 * Insert comment
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 * 
 */
if (!function_exists('workreap_wp_insert_comment')) {
    function workreap_wp_insert_comment($field = '', $postId = '', $user_id = '', $type = '')
    {

        if (!empty($type) && $type === 'mobile') {
            $current_user = get_user_by('id', $user_id);
        } else {
            $current_user = wp_get_current_user();
        }

        if (comments_open($postId)) {
            $comment_meta  = !empty($field['meta']) ? intval($field['meta']) : array();
            $data = array(
                'comment_post_ID'      => $postId,
                'comment_content'      => $field['comment'],
                'comment_parent'       => $field['comment_parent'],
                'user_id'              => $current_user->ID,
                'comment_author'       => $current_user->user_login,
                'comment_author_email' => $current_user->user_email,
                'comment_author_url'   => $current_user->user_url,
                'comment_type'         => 'dispute_activities',
            );
            if (!empty($comment_meta)) {
                $data['comment_meta'] = $comment_meta;
            }

            $comment_id = wp_insert_comment($data);
            if (!is_wp_error($comment_id)) {
                return $comment_id;
            }
        }
        return false;
    }
}

/**
 * Account details
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_account_details')) {
    function workreap_account_details($user_id, $type, $status, $field_key = 'freelancer_shares')
    {
        $arg  = array(
            array(
                'key'       => 'freelancer_id',
                'value'     => $user_id,
                'compare'   => '=',
                'type'      => 'NUMERIC'
            ),
            array(
                'key'    => '_task_status',
                'value'     => $status,
                'compare'   => '=',
            )
        );

        $posts    = workreap_get_post_count_by_meta('shop_order', $type, $arg, 'array');
        $total_amount = 0;

        if (!empty($posts)) {
            foreach ($posts as $post) {
                $post_id      = $post->ID;
                $freelancer_share = get_post_meta($post_id, $field_key, true);
                $freelancer_share = isset($freelancer_share) ? ($freelancer_share) : 0;
                $total_amount = intval($total_amount) + intval($freelancer_share);
            }
        }
        return $total_amount;
    }
}

/**
 * Account details including withdrawal request(s)
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_account_withdraw_details')) {
    function workreap_account_withdraw_details($user_id = '', $status = array('publish', 'pending'))
    {
        $posts_array = array();
        $total_withdraw_amount = 0;
        $args = array(
            'posts_per_page'      => "-1",
            'author'              =>  $user_id,
            'post_type'           => 'withdraw',
            'order'               => 'DESC',
            'orderby'               => 'ID',
            'post_status'           => $status,
            'ignore_sticky_posts' => 1
        );
        $withdrawal_posts = get_posts($args);

        if (!empty($withdrawal_posts)) {
            foreach ($withdrawal_posts as $post_data) :
                $withdraw_amount  = get_post_meta($post_data->ID, '_withdraw_amount', true);
                $withdraw_amount = isset($withdraw_amount) ? $withdraw_amount : 0;
                $total_withdraw_amount = $total_withdraw_amount + $withdraw_amount;
            endforeach;
        }

        return $total_withdraw_amount;
    }
}

/**
 * Account details withdrawal listing
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_account_withdraw_listing')) {
    function workreap_account_withdraw_listing($user_id, $status = array('publish', 'pending'))
    {
        $posts_array      = array();
        $withdrawal_data  = array();
        $args = array(
            'posts_per_page'      => "-1",
            'author'              =>  $user_id,
            'post_type'             => 'withdraw',
            'order'                   => 'DESC',
            'orderby'                 => 'ID',
            'post_status'           => $status,
            'ignore_sticky_posts' => 1
        );
        $withdrawal_posts = get_posts($args);
        if (!empty($withdrawal_posts)) {
            $withdrawal_data  = array();
            foreach ($withdrawal_posts as $post_data) :
                $date               = !empty($post_data->post_date) ? $post_data->post_date : '';
                $post_id      = !empty($post_data->ID)          ? $post_data->ID : 0;
                $post_author  = !empty($post_data->post_author) ? $post_data->post_author : '';
                $post_status  = !empty($post_data->post_status) ? $post_data->post_status : 'pending';
                $post_date    = !empty($date)                 ? date_i18n('F j, Y', strtotime($date)) : '';
                
                $unique_key         = get_post_meta( $post_id, '_unique_key', true );
                $payment_method     = get_post_meta( $post_id, '_payment_method', true );
                $withdraw_amount    = get_post_meta( $post_id, '_withdraw_amount', true );
                
                $withdraw_amount  = !empty($withdraw_amount) ? $withdraw_amount : '';
                $payment_method   = !empty($payment_method)  ? $payment_method  : '';
                $unique_key       = !empty($unique_key)      ? $unique_key      : $post_id;
                $withdrawal_data[] = array(
                    'post_id'         => $post_id,
                    'post_author'     => $post_author,
                    'post_status'     => $post_status,
                    'withdraw_amount' => $withdraw_amount,
                    'payment_method'  => $payment_method,
                    'post_date'       => $post_date,
                    'unique_key'      => $unique_key,
                );
            endforeach;
        }
        return $withdrawal_data;
    }
}

/**
 * Tasks status list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_tasks_status_list')) {
    function workreap_tasks_status_list($type = '')
    {
        $list = array(
            'any'           => esc_html__('All', 'workreap'),
            'publish'       => esc_html__('Published', 'workreap'),
            'pending'       => esc_html__('Pending', 'workreap'),
            'rejected'      => esc_html__('Rejected', 'workreap'),
        );
        $list = apply_filters('workreap_tasks_status_list', $list);
        return $list;
    }
}

/**
 * Tasks status list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_project_status_list')) {
    function workreap_project_status_list($type = '')
    {
        $list = array(
            'any'           => esc_html__('All', 'workreap'),
            'publish'       => esc_html__('Published', 'workreap'),
            'pending'       => esc_html__('Pending', 'workreap'),
            'draft'         => esc_html__('Drafted', 'workreap'),
            'rejected'      => esc_html__('Rejected', 'workreap'),
        );
        $list = apply_filters('workreap_project_status_list', $list);
        return $list;
    }
}


/**
 * Tasks complete order
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_complete_order')) {
    function workreap_complete_order($order_id)
    {
        if (!$order_id) {
            return;
        }

        if (class_exists('WooCommerce')) {
            $order = wc_get_order($order_id);
            $order->update_status('completed');
        }
    }
}

/**
 * Project dispute messages
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_project_dispute_messages')) {
    function workreap_project_dispute_messages($project_id = 0, $proposal_id = 0, $dispute_id = 0, $user_id = 0)
    {
        $proposal_details   = array();
        $freelancer_id          = (int)get_post_field('post_author', $proposal_id);
        $employer_id           = (int)get_post_field('post_author', $project_id);
        $dispute_author_id  = !empty($dispute_id) ? (int)get_post_field('post_author', $dispute_id) : 0;
        $order_status       = get_post_status($proposal_id);
        $dispute_status     = !empty($dispute_id) ? get_post_status($dispute_id) : '';
        $user_type            = apply_filters('workreap_get_user_type', $user_id);

        /* freelancer user */
        if (!empty($freelancer_id) && $user_type === 'freelancers' && $freelancer_id === $user_id && !empty($dispute_id)) {

            if (!empty($dispute_id) && $order_status == 'disputed' && !empty($dispute_status) && in_array($dispute_status, array('disputed', 'publish'))) {
                if (!empty($dispute_author_id) && $dispute_author_id == $user_id) {
                    $proposal_details[0]['title']       = esc_html__('Dispute created', 'workreap');
                    $proposal_details[0]['message']     = esc_html__('You have created a dispute for this order. You can check the status by clicking the link below.', 'workreap');
                    $proposal_details[0]['type']        = $dispute_status;
                } else {
                    $proposal_details[0]['title']       = esc_html__('Refund requested', 'workreap');
                    $proposal_details[0]['message']     = esc_html__('The employer has created a refund request for this order, you can process or decline this refund request.', 'workreap');
                    $proposal_details[0]['type']        = $dispute_status;
                }
            } elseif (!empty($dispute_id) && ($order_status == 'refunded' || $order_status == 'cancelled')) {
                $proposal_details[0]['title']       = esc_html__('Refunded/Cancelled', 'workreap');
                $proposal_details[0]['message']     = esc_html__('This order was refunded, you can check more detail on the refund and dispute page.', 'workreap');
                $proposal_details[0]['type']        = $order_status;
            } elseif (!empty($dispute_id) && !empty($dispute_status) && $dispute_status === 'declined') {
                $proposal_details[0]['title']       = esc_html__('Refund declined', 'workreap');
                $proposal_details[0]['message']     = esc_html__('You have declined the refund request for this order. You may create the dispute for this order', 'workreap');
                $proposal_details[0]['type']        = $dispute_status;
            } elseif (!empty($dispute_id) && !empty($dispute_status) && $dispute_status === 'declined') {
                $proposal_details[0]['title']       = esc_html__('Refund declined', 'workreap');
                $proposal_details[0]['message']     = esc_html__('You have declined the refund request for this order. You may create the dispute for this order', 'workreap');
                $proposal_details[0]['type']        = $dispute_status;
            } elseif (!empty($dispute_status) && $dispute_status === 'refunded') {
                $winning_party = get_post_meta($dispute_id, 'winning_party', true);
                $resolved_by   = get_post_meta($dispute_id, 'resolved_by', true);
                $resolved_by   = !empty($resolved_by) && $resolved_by === 'freelancers' ? esc_html__('Freelancer', 'workreap') : esc_html__('Admin', 'workreap');
                if (!empty($winning_party) && intval($winning_party) === intval($user_id)) {
                    $proposal_details[0]['title']       = esc_html__('Refund approved', 'workreap');
                    $proposal_details[0]['message']     = sprintf(esc_html__('The %s has approved your refund request, the amount has been added to your wallet. You can use this amount for your next order', 'workreap'), $resolved_by);
                    $proposal_details[0]['type']        = $dispute_status;
                } else {
                    $proposal_details[0]['title']       = esc_html__('Refund', 'workreap');
                    $proposal_details[0]['message']     = esc_html__('This order was refunded, you can check more detail on the refund and dispute page.', 'workreap');
                    $proposal_details[0]['type']        = $dispute_status;
                }
            }
        }

        /* employer user */
        if (!empty($employer_id) && $user_type === 'employers' && $employer_id === $user_id && !empty($dispute_id)) {

            if (!empty($dispute_status) && $dispute_status === 'disputed') {
                if (!empty($dispute_author_id) && intval($dispute_author_id) == intval($user_id)) {
                    $proposal_details[0]['title']       = esc_html__('Dispute created', 'workreap');
                    $proposal_details[0]['message']     = esc_html__('You have created a dispute for this order. You can check the status by clicking the link below.', 'workreap');
                    $proposal_details[0]['type']        = $dispute_status;
                } else {
                    $proposal_details[0]['title']       = esc_html__('Dispute created', 'workreap');
                    $proposal_details[0]['message']     = esc_html__('The freelancer has created a dispute against that order, admin will review the history of this order and make the final decision', 'workreap');
                    $proposal_details[0]['type']        = $dispute_status;
                }
            } elseif (!empty($dispute_status) && $dispute_status === 'publish') {
                $employer_dispute_days    = !empty($workreap_settings['employer_dispute_option'])    ? intval($workreap_settings['employer_dispute_option']) : 5;
                $post_date             = !empty($dispute_id) ? get_post_field('post_date', $dispute_id) : 0;
                $disbuted_time         = !empty($post_date) ? strtotime($post_date . ' + ' . intval($employer_dispute_days) . ' days') : 0;
                $current_time          = current_time('mysql', 1);
                $proposal_details[0]['title']       = esc_html__('Refund request', 'workreap');
                $proposal_details[0]['message']     = esc_html__('You have created a refund request for this order. You can check the status by clicking the link below.', 'workreap');
                $proposal_details[0]['type']        = $dispute_status;
                if (!empty($disbuted_time) && $disbuted_time < $current_time) {
                    $proposal_details[1]['title']       = esc_html__('Create dispute', 'workreap');
                    $proposal_details[1]['message']     = esc_html__('The freelancer has not replied to your refund request, you can now raise a dispute to acknowledge the admin', 'workreap');
                    $proposal_details[1]['type']        = 'create_dispute_admin';
                }
            } elseif (!empty($dispute_status) && $dispute_status === 'declined') {
                $proposal_details[0]['title']       = esc_html__('Refund declined', 'workreap');
                $proposal_details[0]['message']     = esc_html__('The freelancer has declined your refund request, you can now create the dispute for this order.', 'workreap');
                $proposal_details[0]['type']        = $dispute_status;
            } elseif (!empty($dispute_status) && $dispute_status === 'refunded') {
                $winning_party  = get_post_meta($dispute_id, 'winning_party', true);
                if (!empty($winning_party) && intval($winning_party) === intval($user_id)) {
                    $resolved_by    = get_post_meta($dispute_id, 'resolved_by', true);
                    $resolved_by    = !empty($resolved_by) && $resolved_by === 'freelancers' ? esc_html__('freelancer', 'workreap') : esc_html__('admin', 'workreap');
                    $proposal_details[0]['title']       = esc_html__('Refund approved', 'workreap');
                    $proposal_details[0]['message']     = sprintf(esc_html__('The %s has approved your refund request, the amount has been added to your wallet. You can use this amount for your next order', 'workreap'), $resolved_by);
                    $proposal_details[0]['type']        = $dispute_status;
                } else {
                    $proposal_details[0]['title']       = esc_html__('Refund', 'workreap');
                    $proposal_details[0]['message']     = esc_html__('This order was refunded, you can check more detail on the refund and dispute page.', 'workreap');
                    $proposal_details[0]['type']        = $dispute_status;
                }
            }
        }

        return $proposal_details;
    }
}


/**
 * Tasks completed count
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_complete_task_count')) {
    function workreap_complete_task_count($user_id = '')
    {
        $workreap_order_completed = 0;
        if (!empty($user_id)) {
            $meta_array = array(
                array(
                    'key' => 'freelancer_id',
                    'value' => $user_id,
                    'compare' => '=',
                    'type' => 'NUMERIC'
                ),
                array(
                    'key' => '_task_status',
                    'value' => 'completed',
                    'compare' => '=',
                ),
                array(
                    'key' => 'payment_type',
                    'value' => 'tasks',
                    'compare' => '=',
                )
            );
            $workreap_order_completed = workreap_get_post_count_by_meta('shop_order', array('wc-completed'), $meta_array);
        }
        return $workreap_order_completed;
    }
}

/**
 * Typography and dynamic styles
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */

if (!function_exists('workreap_add_dynamic_styles')) {
    function workreap_add_dynamic_styles()
    {
        global $workreap_settings;
        $custom_css         =  !empty($workreap_settings['custom_css']) ? $workreap_settings['custom_css'] : '';
        $logo_wide         =  !empty($workreap_settings['logo_wide']) ? $workreap_settings['logo_wide'] : '';

        ob_start();
        if (!empty($custom_css)) {
            echo esc_html($custom_css);
        }

        if (!empty($logo_wide)) {
            echo '.wr-logo > img{width: ' . $logo_wide . 'px !important;}';
	        echo '.wr-logo > svg{width: ' . $logo_wide . 'px !important;}';
        }

        return ob_get_clean();
    }
}

/**
 * RTL
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_splide_rtl_check')) {

    function workreap_splide_rtl_check()
    {
        if (is_rtl()) {
            return 'rtl';
        } else {
            return 'ltr';
        }
    }
}

/**
 * Workreap send guppy messenger link
 * @throws error
 * @author Workreap
 * @return 
 */

if (!function_exists('wpguppy_messenger_link')) {
    add_filter('wpguppy_messenger_link', 'wpguppy_messenger_link', 10, 1);
    function wpguppy_messenger_link($url = '')
    {
        global $current_user;
        return Workreap_Profile_Menu::workreap_profile_menu_link('chat', $current_user->ID, true);
    }
}

/**
 * Workreap messenger link param
 * @throws error
 * @author Workreap
 * @return 
 */

if (!function_exists('wpguppy_messenger_link_seprator')) {
    add_filter('wpguppy_messenger_link_seprator', 'wpguppy_messenger_link_seprator', 10, 1);
    function wpguppy_messenger_link_seprator($seprator = '?')
    {
        global $current_user;
        return '&';
    }
}

/**
 * Workreap check page access
 * @throws error
 * @author Workreap
 * @return 
 */

 if (!function_exists('workreap_get_page_access')) {

    function workreap_get_page_access($user_identity,$user_type,$reference,$mode){
        $redirect_url   = '';
        if( !empty($reference) && $reference === 'projects'){
            $id = !empty($_GET['id']) ? intval($_GET['id']) : 0;
            if( !empty($mode) && $mode === 'activity'){
                if( !empty($user_type) && $user_type === 'employers'){
                    $employer_id   = get_post_meta($id, 'employer_id',true);
                    if( !empty($employer_id) && $employer_id != $user_identity ){
                        $redirect_url   = workreap_get_page_uri('dashboard');
                    }
                } else if( !empty($user_type) && $user_type === 'freelancers'){
                    $freelancer_id   = get_post_field('post_author', $id);
                    if( !empty($freelancer_id) && $freelancer_id != $user_identity ){
                        $redirect_url   = workreap_get_page_uri('dashboard');
                    }
                }
            }
        } else if( !empty($reference) && $reference === 'tasks-orders'){
            $id = !empty($_GET['id']) ? intval($_GET['id']) : 0;
            if( !empty($mode) && $mode === 'detail'){
                if( !empty($user_type) && $user_type === 'employers'){
                    $employer_id   = get_post_meta($id, 'employer_id',true);
                    if( !empty($employer_id) && $employer_id != $user_identity ){
                        $redirect_url   = workreap_get_page_uri('dashboard');
                    }
                } else if( !empty($user_type) && $user_type === 'freelancers'){
                    $freelancer_id   = get_post_meta($id, 'freelancer_id',true);
                    if( !empty($freelancer_id) && $freelancer_id != $user_identity ){
                        $redirect_url   = workreap_get_page_uri('dashboard');
                    }
                }
            }
        } else if( !empty($reference) && $reference === 'disputes'){
            $id = !empty($_GET['id']) ? intval($_GET['id']) : 0;
            if( !empty($mode) && $mode === 'detail'){
                if( !empty($user_type) && $user_type === 'employers'){
                    $employer_id   = get_post_meta($id, '_employer_id',true);
                    if( !empty($employer_id) && $employer_id != $user_identity ){
                        $redirect_url   = workreap_get_page_uri('dashboard');
                    }
                } else if( !empty($user_type) && $user_type === 'freelancers'){
                    $freelancer_id   = get_post_meta($id, '_freelancer_id',true);
                    if( !empty($freelancer_id) && $freelancer_id != $user_identity ){
                        $redirect_url   = workreap_get_page_uri('dashboard');
                    }
                }
            }
        } else if( !empty($reference) && $reference === 'invoices'){
            $id             = !empty($_GET['id']) ? intval($_GET['id']) : 0;
            $payment_type   = get_post_meta($id, 'payment_type',true);
            if( !empty($mode) && ($mode === 'detail' || $mode === 'hourly-detail') && in_array($payment_type,array('tasks','tasks','package','wallet','hourly'))){
                if( !empty($user_type) && $user_type === 'employers'){
                    $employer_id   = get_post_meta($id, 'employer_id',true);
                    if( !empty($employer_id) && $employer_id != $user_identity ){
                        $redirect_url   = workreap_get_page_uri('dashboard');
                    }
                } else if( !empty($user_type) && $user_type === 'freelancers'){
                    $freelancer_id   = get_post_meta($id, 'freelancer_id',true);
                    if( !empty($freelancer_id) && $freelancer_id != $user_identity ){
                        $redirect_url   = workreap_get_page_uri('dashboard');
                    }
                }
            }
        }

        return $redirect_url;
    }
 }

/**
 * Return Search type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if( !function_exists( 'workreap_get_search_list' ) ) {
	function workreap_get_search_list($is_single='no'){

		$list = array(

			'freelancers_search_page' => array(
				'title' 		=> esc_html__('Freelancers', 'workreap'),
			),
			'project_search_page' => array(
				'title' => esc_html__('Projects', 'workreap'),
			),
			'service_search_page' => array(
				'title' 		=> esc_html__('Tasks', 'workreap'),
			),
		);

		$list = apply_filters('workreap_filter_search_list', $list);

		if( $is_single === 'yes' ){
			$list = workreap_array_column_extract($list, 'title',-1);
		}

		return $list;
	}
}

/**
 * Extract array column
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if( !function_exists( 'workreap_array_column_extract' ) ) {
	function workreap_array_column_extract($array='', $columnkey='', $indexkey = null) {
		$result = array();
		foreach ($array as $subarray => $value) {
			if (array_key_exists($columnkey,$value)) { $val = $array[$subarray][$columnkey]; }
			else if ($columnkey === null) { $val = $value; }
			else { continue; }

			if ($indexkey === null) { $result[] = $val; }
            elseif ($indexkey == -1 || array_key_exists($indexkey,$value)) {
				$result[($indexkey == -1)? $subarray:$array[$subarray][$indexkey]] = $val;
			}
		}
		return $result;
	}
}