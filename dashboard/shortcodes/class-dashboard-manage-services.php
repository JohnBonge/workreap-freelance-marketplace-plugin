<?php
namespace userserviceslisting;

/**
 *
 * Class 'Workreap_Dashboard_Shortcodes_Manage_Services' service lsiting shortcode
 *
 * @package     Workreap
 * @subpackage  Workreap/Dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
class Workreap_Dashboard_Shortcodes_Manage_Services {

    public $task_allowed = true;
    public $package_type = 'free';
    public $featured_tasks_allowed = 0;
    public $number_tasks_allowed = 0;
    public $package_detail = array();

	/**
	 * services listing shortcode
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {        
	
        add_action( 'wp_ajax_workreap_service_status_update', array($this, 'workreap_service_status_update') );
        add_action( 'wp_ajax_workreap_task_featured_update', array($this, 'workreap_task_featured_update') );
        add_action( 'wp_ajax_workreap_service_delete', array($this, 'workreap_service_delete') );
    }
   

    /**
	 * Dashboard task featured enable/disable
	 *
	 * @since    1.0.0
	 * @access   public
	*/
    public function workreap_featured_tasks(){
        global $current_user;

        if (!class_exists('WooCommerce')) {
            return 0;
        }

        $workreap_args = array(
            'post_type'         => 'product',
            'post_status'       => 'publish',
            'posts_per_page'    => -1,            
            'author'            => $current_user->ID,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                ),
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => 'tasks',
                ),
            ),
        );

        $featured_task = get_posts($workreap_args);
        return count($featured_task);
    }
    
    /**
	 * Dashboard services enable/disable
	 *
	 * @since    1.0.0
	 * @access   public
	*/
    public function workreap_task_featured_update(){
        global $current_user;
        $json       = array();
        $do_check   = check_ajax_referer('ajax_nonce', 'security', false);
        if( function_exists('workreap_is_demo_site') ) { 
            workreap_is_demo_site();
        }
        if ( $do_check == false ) {
            $json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Security checks failed!', 'workreap');
            wp_send_json( $json );
        }

        if ( !class_exists('WooCommerce') ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('WooCommerce plugin needs to be installed.', 'workreap');
			wp_send_json( $json );
		}

        $service_id             = !empty($_POST['service_id']) ?  (int)$_POST['service_id'] : '';	

        if( function_exists('workreap_verify_post_author') ){
            workreap_verify_post_author($service_id);
        }

        $this->task_allowed     = workreap_task_create_allowed($current_user->ID);
        $this->package_detail   = workreap_get_package($current_user->ID);
        $this->package_type    =  !empty($this->package_detail['type']) ? $this->package_detail['type'] : '';

        if($this->package_type == 'paid'){          
            $this->task_plans_allowed       =  !empty($this->package_detail['package']['task_plans_allowed']) ? $this->package_detail['package']['task_plans_allowed'] : 'no';
            $this->featured_tasks_allowed   =  !empty($this->package_detail['package']['featured_tasks_allowed']) ? $this->package_detail['package']['featured_tasks_allowed'] : 0;
            $this->number_tasks_allowed     =  !empty($this->package_detail['package']['number_tasks_allowed']) ? $this->package_detail['package']['number_tasks_allowed'] : 0;
            $this->featured_tasks_duration     =  !empty($this->package_detail['package']['featured_tasks_duration']) ? $this->package_detail['package']['featured_tasks_duration'] : 0;
        }
        
        $service_enable_value   = !empty($_POST['service_enable']) ?  sanitize_text_field($_POST['service_enable']) : 'no';	
        $total_featured_tasks   = (int)$this->workreap_featured_tasks();

        if( $this->package_type == 'paid' && $service_enable_value == 'enable' && ($total_featured_tasks >= $this->featured_tasks_allowed) ){
            $json['type']               = 'error';
            $json['message'] 		    = esc_html__('Oops!', 'workreap');
            $json['message_desc'] 		= esc_html__('You are not allowed to update featured status. Please upgrade your package.', 'workreap');
            wp_send_json($json);
        }

        if($service_id){
           
            if($service_enable_value == 'enable'){
                $featured_status = 'yes';                
            } else {
                $featured_status = 'no';
            }

            $product = wc_get_product( absint( $service_id ) );

            if ( $product ) {
                $product->set_featured( ! $product->get_featured() );
                $product->save();
            }

            $current_date = current_time('mysql');
            $featured_date  = date('Y-m-d H:i:s');
  
            if ( !empty( $this->featured_tasks_duration ) ) {
                $featured_date = strtotime("+" . $this->featured_tasks_duration . " days", strtotime($current_date));
                $featured_date = date('Y-m-d H:i:s', $featured_date);
            }

            $featured_string	= !empty( $featured_date ) ?  strtotime( $featured_date ) : 0;
            
            update_post_meta($service_id, '_featured_task', $featured_status);
            update_post_meta($service_id, '_featured_till',$featured_string );

            $workreap_service_data['ID'] = $service_id;

            wp_update_post( $workreap_service_data );

            do_action('workreap_task_featured_update_activity', $service_id, $_POST);
            
            $json['type']               = 'success';
            $json['message'] 		    = esc_html__('Woohoo!', 'workreap');
            $json['message_desc'] 		= esc_html__('Task featured status updated!', 'workreap');
            wp_send_json($json);
        } else {
            $json['type']               = 'error';
            $json['message'] 		    = esc_html__('Oops!', 'workreap');
            $json['message_desc'] 		= esc_html__('There is error while updating service status in database.', 'workreap');
            wp_send_json($json);
        }

    }


    /**
	 * Dashboard services enable/disable
	 *
	 * @since    1.0.0
	 * @access   public
	*/
    public function workreap_service_status_update(){
        $json 		= array();
        $do_check = check_ajax_referer('ajax_nonce', 'security', false);
        if( function_exists('workreap_is_demo_site') ) { 
            workreap_is_demo_site();
        }
        if ( $do_check == false ) {
            $json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Security checks failed!', 'workreap');
            wp_send_json( $json );
        }

        if ( !class_exists('WooCommerce') ) {
			$json['type']       = 'error';
			$json['message']    = esc_html__('WooCommerce plugin needs to be installed.', 'workreap');
			wp_send_json( $json );
		}

        $service_id  = !empty($_POST['service_id']) ?  (int)$_POST['service_id'] : '';	

        if( function_exists('workreap_verify_post_author') ){
            workreap_verify_post_author($service_id);
        }
        $post_status            = get_post_status($service_id);
        if( !empty($post_status) && $post_status === 'pending' ){
            $json['type']       = 'error';
			$json['message']    = esc_html__('This task need to admin approval.', 'workreap');
			wp_send_json( $json );
        }
        $service_enable_value  = !empty($_POST['service_enable']) ?  sanitize_text_field($_POST['service_enable']) : '';	
        
        if($service_id){

            if($service_enable_value == 'enable' && empty($this->task_allowed)){
                $json['type']               = 'error';
                $json['message'] 		    = esc_html__('Oops!', 'workreap');
                $json['message_desc'] 		= esc_html__('You are not allowed to update status. Please upgrade your package to continue.', 'workreap');
                wp_send_json($json);
            }

            if($service_enable_value == 'enable' && $this->task_allowed){
                $service_status = 'publish';
            } else {
                $service_status = 'private';
            }

            $workreap_service_data = array(
                'post_status'  => esc_html($service_status),
            );
            $workreap_service_data['ID'] = $service_id;
            wp_update_post( $workreap_service_data );
            $json['type']               = 'success';
            $json['message'] 		    = esc_html__('Woohoo!', 'workreap');
            $json['message_desc'] 		= esc_html__('Task status has been updated!.', 'workreap');
            wp_send_json($json);
        } else {
            $json['type']               = 'error';
            $json['message'] 		    = esc_html__('Oops!', 'workreap');
            $json['message_desc'] 		= esc_html__('There is error while updating Task status in database.', 'workreap');
            wp_send_json($json);
        }

    }

    /**
	 * Dashboard services enable/disable
	 *
	 * @since    1.0.0
	 * @access   public
	*/
    public function workreap_service_delete(){
        global $current_user;
        $json 		= array();

        if( function_exists('workreap_is_demo_site') ) { 
            workreap_is_demo_site();
        }
        
        if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
            $json['type']               = 'error';
            $json['message'] 		    = esc_html__('Restricted Access', 'workreap');
            $json['message_desc'] 		= esc_html__('You are not allowed to perform this action.', 'workreap');
            wp_send_json($json);
        }
       
        if ( !class_exists('WooCommerce') ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('WooCommerce plugin needs to be installed.', 'workreap');
			wp_send_json( $json );
		}
        
        $service_id  = !empty($_POST['service_id']) ?  (int)$_POST['service_id'] : 0;

        if( function_exists('workreap_verify_post_author') ){
            workreap_verify_post_author($service_id);
        }

        if($service_id){
            $meta_array	= array(
                array(
                    'key'		=> 'task_product_id',
                    'value'   	=> $service_id,
                    'compare' 	=> '=',
                    'type' 		=> 'NUMERIC'
                ),
                array(
                    'key'		=> '_task_status',
                    'value'   	=> 'hired',
                    'compare' 	=> '=',
                )
            );

            $workreap_order_queues    = workreap_get_post_count_by_meta('shop_order', array('wc-pending', 'wc-on-hold', 'wc-processing','wc-completed'), $meta_array);

            if(!empty($workreap_order_queues)){
                $json['type']               = 'error';
                $json['message'] 		    = esc_html__('Oops!', 'workreap');
                $json['message_desc'] 		= esc_html__('This task has ongoing orders, you can\'t remove this task', 'workreap');
                wp_send_json($json);
            }

            $workreap_delete = wp_delete_post($service_id);

            if( $workreap_delete ){
                $json['type']               = 'success';
                $json['message'] 		    = esc_html__('Woohoo!', 'workreap');
                $json['message_desc'] 		= esc_html__('Task has been deleted!', 'workreap');
                wp_send_json($json);
            } else {
                $json['type']               = 'error';
                $json['message'] 		    = esc_html__('Oops!', 'workreap');
                $json['message_desc'] 		= esc_html__('There is an error while removing the task.', 'workreap');
                wp_send_json($json);
            }

        } else {
                $json['type']               = 'error';
                $json['message'] 		    = esc_html__('Oops!', 'workreap');
                $json['message_desc'] 		= esc_html__('There is an error while removing the task.', 'workreap');
                wp_send_json($json);
        }

    }


}

new Workreap_Dashboard_Shortcodes_Manage_Services();
