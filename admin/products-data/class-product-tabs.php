<?php
namespace ProductTabs;

/**
 * 
 * Class 'Workreap_Admin_Products_Data_Product_Tabs' defines to remove the product data default tabs
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/products_data
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/

class Workreap_Admin_Products_Data_Product_Tabs {

	/**
	 * Add woocommerce filter 'woocommerce_product_data_tabs' to remove default tabs.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {
		add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false' );
		add_filter( 'woocommerce_product_data_tabs', array($this, 'workreap_remove_proudct_data_default_tabs') );
		add_action( 'woocommerce_product_data_panels', array($this, 'workreap_product_custom_data_fields') );
		add_action( 'woocommerce_product_class', array(&$this, 'workreap_woocommerce_product_class'), 10, 2 );
		add_filter( 'product_type_options', array(&$this, 'workreap_woocommerce_product_types_option_display') );		
		add_action( 'save_post', array($this, 'workreap_products_meta_save') );
		add_action( 'save_post', array($this, 'workreap_products_package_meta_save') );
		add_action( 'save_post', array($this, 'workreap_products_employer_package_meta_save') );
	}

	/**
	* Add to product data custom tabs.
	 * @since    1.0.0
	 * @access   public
	*/
	public function workreap_product_get_data_tabs(){
		$product_data_tabs = array(
			'plans'	=> array(
				'label'		=> esc_html__( 'Plans', 'workreap' ),
				'target'	=> 'workreap_plans_product_data',
				'class'		=> array('show_if_tasks', 'hide_if_subtasks', 'hide_if_projects'),
				'priority'	=> 10,
			),			
			'subtasks'	=> array(
				'label'		=> esc_html__( 'Sub tasks', 'workreap' ),
				'target'	=> 'workreap_subtsks_product_data',
				'class'		=> array('show_if_tasks', 'hide_if_subtasks', 'hide_if_projects'),
				'priority'	=> 20,
			),				
			'faq'	=> array(
				'label'		=> esc_html__( 'FAQ\'S', 'workreap' ),
				'target'	=> 'workreap_faqs_product_data',
				'class'		=> array('show_if_projects', 'show_if_tasks'),
				'priority'	=> 30,
			),					
			'package_fields'	=> array(
				'label'		=> esc_html__( 'Package fields', 'workreap' ),
				'target'	=> 'workreap_package_product_data',
				'class'		=> array('show_if_packages'),
				'priority'	=> 40,
			),					
			'employer_package_fields'	=> array(
				'label'		=> esc_html__( 'Employer package fields', 'workreap' ),
				'target'	=> 'workreap_employer_package_product_data',
				'class'		=> array('show_if_employer_packages'),
				'priority'	=> 40,
			),					

		);

		return apply_filters('workreap_product_get_data_tabs', $product_data_tabs);
	}	

	/**
	* Remove default product data tabs 
	* Set custom tabs
	* @since    1.0.0
	* @access   public
	*/
	public function workreap_remove_proudct_data_default_tabs( $tabs ){
		$product_data_tabs = $this->workreap_product_get_data_tabs();
		$tabs = array_merge($tabs, $product_data_tabs);
		return apply_filters('workreap_product_tabs', $tabs);
	}

	/**
	* Product type body class
	 * @since    1.0.0
	 * @access   public
	*/
	public function workreap_product_type_in_body_class( $classes ){
		global $product;
 		// get the current product in the loop
		$product = wc_get_product();

		if(empty($product)){return;}

		$classes[] = '  product-type-' . $product->get_type();	 
		return $classes;	 
	}

	/**
	* Display Downloadable, Virtual options for custom product types
	* @since    1.0.0
	* @access   public	
	*/
	public function workreap_woocommerce_product_types_option_display($options) {
 
		// Show "Virtual" checkbox for product types
		if( isset( $options[ 'virtual' ]['wrapper_class'] ) ) {
			$options[ 'virtual' ]['wrapper_class'] = 'show_if_tasks show_if_funds show_if_projects show_if_subtasks';
		}

		// Show "Downloadable" checkbox for product types
		if( isset( $options[ 'downloadable' ]['wrapper_class'] ) ) {
			$options[ 'downloadable' ]['wrapper_class'] = 'show_if_tasks show_if_projects show_if_subtasks';
		}		

		return $options;
	}
	
	/**	
	 * Custom product types
	 * @since    1.0.0
	 * @access   public
	*/
	public function workreap_woocommerce_product_class( $classname, $product_type ) {

		if ( $product_type == 'tasks' ) { 
			$classname = 'WC_Product_Tasks';
		} elseif ( $product_type == 'projects' ) {
			$classname = 'WC_Product_Projects';
		} elseif ( $product_type == 'subtasks' ) {
			$classname = 'WC_Product_Subtasks';
		} elseif ( $product_type == 'packages' ) {
			$classname = 'WC_Product_Packages';
		} elseif ( $product_type == 'employer_packages' ) {
			$classname = 'WC_Product_Employer_Packages';
		} elseif ( $product_type == 'funds' ) {
			$classname = 'WC_Product_Funds';
		}

		return $classname;
	}

	/**
	* Add to product data plan custom tab panel.
	* @since    1.0.0
	* @access   public	
	*/
	public function workreap_product_custom_data_fields() {
		global $woocommerce, $post;

		?>
		<div id="workreap_plans_product_data" class="panel woocommerce_options_panel">
			<?php include __DIR__.'/templates/product-data-plans.php'; ?>
		</div>
	
		<div id="workreap_add_subtsks_product_data" class="panel woocommerce_options_panel">
			<?php include __DIR__.'/templates/product-data-add-subtasks.php'; ?>
		</div>
		<div id="workreap_subtsks_product_data" class="panel woocommerce_options_panel">
			<?php include __DIR__.'/templates/product-data-subtasks.php'; ?>
		</div>
		<div id="workreap_faqs_product_data" class="panel woocommerce_options_panel">
			<?php include __DIR__.'/templates/product-data-faq-details.php'; ?>
		</div>
		<div id="workreap_package_product_data" class="panel woocommerce_options_panel">
			<?php include __DIR__.'/templates/product-data-package.php'; ?>
		</div>		
		<div id="workreap_employer_package_product_data" class="panel woocommerce_options_panel">
			<?php include __DIR__.'/templates/product-data-employer-package.php'; ?>
		</div>		
		<?php	
	}

	/**
	* Save product package meta
	* @since    1.0.0
	* @access   public	
	*/
	public function workreap_products_employer_package_meta_save($post_id){
		global $post;
		// Autosave, do nothing
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
			
		// AJAX? Not used here
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}
			
		// Check user permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ){
			return;
		}	
		
		// Only set for post_type
		if ( !isset($post->post_type) ) {
			return;
		}

		// Only set for post_type = post!
		if ( 'product' !== $post->post_type ) {
			return;
		}
		// Return if it's a post revision
		if ( false !== wp_is_post_revision( $post_id ) ){
			return;
		}			

		$product = wc_get_product( $post_id );	
		if(!empty($product) && $product->get_type() !== 'employer_packages'){
			return;
		}

		if(isset($_POST['employer_package_type'])){
			$package_type = isset( $_POST['employer_package_type'] ) ? esc_html( $_POST['employer_package_type'] ) : 'days';
			update_post_meta($post_id, 'package_type', $package_type);
		}

		if(isset($_POST['employer_package_duration'])){
			$package_duration = isset( $_POST['employer_package_duration'] ) ? intval( $_POST['employer_package_duration'] ) : 0;
			update_post_meta($post_id, 'package_duration', $package_duration);
		}

		if(isset($_POST['number_projects_allowed'])){
			$number_projects_allowed = isset( $_POST['number_projects_allowed'] ) ? intval( $_POST['number_projects_allowed'] ) : 0;
			update_post_meta($post_id, 'number_projects_allowed', $number_projects_allowed);
		}

		if(isset($_POST['featured_projects_allowed'])){
			$featured_projects_allowed = isset( $_POST['featured_projects_allowed'] ) ? intval( $_POST['featured_projects_allowed'] ) : 0;
			update_post_meta($post_id, 'featured_projects_allowed', $featured_projects_allowed);
		}

		$featured_projects_duration = isset( $_POST['featured_projects_duration'] ) ? esc_html( $_POST['featured_projects_duration'] ) : 'no';
		update_post_meta($post_id, 'featured_projects_duration', $featured_projects_duration);

		$most_popular_package = isset( $_POST['most_popular_project_package'] ) ? esc_html( $_POST['most_popular_project_package'] ) : 'no';
		update_post_meta($post_id, 'most_popular_project_package', $most_popular_package);
		
		$allow_ai = isset( $_POST['em_allow_ai'] ) ? esc_html( $_POST['em_allow_ai'] ) : 'no';
		update_post_meta($post_id, 'allow_ai', $allow_ai);
	}
	/**
	* Save product package meta
	* @since    1.0.0
	* @access   public	
	*/
	public function workreap_products_package_meta_save($post_id){
		global $post;
		// Autosave, do nothing
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
			
		// AJAX? Not used here
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}
			
		// Check user permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ){
			return;
		}	
		
		// Only set for post_type
		if ( !isset($post->post_type) ) {
			return;
		}

		// Only set for post_type = post!
		if ( 'product' !== $post->post_type ) {
			return;
		}
		// Return if it's a post revision
		if ( false !== wp_is_post_revision( $post_id ) ){
			return;
		}			

		$product = wc_get_product( $post_id );	
		if(!empty($product) && $product->get_type() !== 'packages'){
			return;
		}

		if(isset($_POST['package_type'])){
			$package_type = isset( $_POST['package_type'] ) ? esc_html( $_POST['package_type'] ) : 'days';
			update_post_meta($post_id, 'package_type', $package_type);
		}

		if(isset($_POST['package_duration'])){
			$package_duration = isset( $_POST['package_duration'] ) ? intval( $_POST['package_duration'] ) : 0;
			update_post_meta($post_id, 'package_duration', $package_duration);
		}

		if(isset($_POST['number_tasks_allowed'])){
			$number_tasks_allowed = isset( $_POST['number_tasks_allowed'] ) ? intval( $_POST['number_tasks_allowed'] ) : 0;
			update_post_meta($post_id, 'number_tasks_allowed', $number_tasks_allowed);
		}

		if(isset($_POST['featured_tasks_allowed'])){
			$number_tasks_allowed = isset( $_POST['featured_tasks_allowed'] ) ? intval( $_POST['featured_tasks_allowed'] ) : 0;
			update_post_meta($post_id, 'featured_tasks_allowed', $number_tasks_allowed);
		}

		if(isset($_POST['featured_tasks_duration'])){
			$number_tasks_duration = isset( $_POST['featured_tasks_duration'] ) ? intval( $_POST['featured_tasks_duration'] ) : 0;
			update_post_meta($post_id, 'featured_tasks_duration', $number_tasks_duration);
		}
		

		$task_plans_allowed = isset( $_POST['task_plans_allowed'] ) ? esc_html( $_POST['task_plans_allowed'] ) : 'no';
		update_post_meta($post_id, 'task_plans_allowed', $task_plans_allowed);

		$number_project_credits = isset( $_POST['number_project_credits'] ) ? esc_html( $_POST['number_project_credits'] ) : '0';
		update_post_meta($post_id, 'number_project_credits', $number_project_credits);

		$most_popular_package = isset( $_POST['most_popular_task_package'] ) ? esc_html( $_POST['most_popular_task_package'] ) : 'no';
		update_post_meta($post_id, 'most_popular_task_package', $most_popular_package);

		$allow_ai_freelancer = isset( $_POST['allow_ai'] ) ? esc_html( $_POST['allow_ai'] ) : 'no';
		update_post_meta($post_id, 'allow_ai', $allow_ai_freelancer);

	}

	/**
	* Save product task meta
	* @since    1.0.0
	* @access   public	
	*/
	public function workreap_products_meta_save($post_id){
		global $post;
		// Autosave, do nothing
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
			
		// AJAX? Not used here
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}
			
		// Check user permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ){
			return;
		}	

		// Only set for post_type = post!
		if ( !isset($post->post_type) ) {
			return;
		}

		// Only set for post_type = post!
		if ( isset($post->post_type) && 'product' !== $post->post_type ) {
			return;
		}


		// Return if it's a post revision
		if ( false !== wp_is_post_revision( $post_id ) ){
			return;
		}			

		$product = wc_get_product( $post_id );	

		if(!empty($product) && $product->get_type() !== 'tasks'){
			return;
		}
		

		$author_id = get_post_field ('post_author', $post_id);

		if(isset($_POST['tax_input']['product_cat'])){
			$product_cat	= $_POST['tax_input']['product_cat'];
			$product_cat	= array_filter($product_cat);
			$product_cat	= array_values($product_cat);
			$taxonomy_category_data     = '';
            $category_level2            = '';
            $category_level3            = array();

			$parent_category        = array();
			$subcategory            = array();
			$service_type           = array();
			$categories_term        = array();

			if(!empty($product_cat)){
				for($i=0; $i<count($product_cat); $i++){
					if($i == 0){
						$taxonomy_category_data	= $product_cat['0'];
					} elseif($i == 1){
						$category_level2	= $product_cat['1'];
					} else {
						$category_level3[]	= $product_cat[$i];
					}
				}

				if($taxonomy_category_data){
					$categories_term[]                  = $taxonomy_category_data;
					$category                           = get_term_by('id', $taxonomy_category_data, 'product_cat');
					$categories[$category->slug]        = $category->name;
					$parent_category[$category->slug]   = $category->name;
				}

				if($category_level2){
					$category                           = get_term_by('id', $category_level2, 'product_cat');
					$categories[$category->slug]        = $category->name;
					$subcategory[$category->slug]       = $category->name;
					$categories_term[]                  = $category_level2;
				}

				if($category_level3){
					foreach($category_level3 as $term_id){

						if($term_id){
							$term_id    = intval($term_id);
							$category   = get_term_by('id', $term_id, 'product_cat');
							$categories[$category->slug]        = $category->name;
							$service_type[$category->slug]      = $category->name;
							$categories_term[]                  = $term_id;
						}
					}
				}

				$product_data   = get_post_meta($post_id, 'wr_service_meta', true);
                $product_data   = !empty($product_data) ? $product_data : array();
				$product_data['categories']     = $categories;
				$product_data['category']       = $parent_category;
				$product_data['subcategory']    = $subcategory;
				$product_data['service_type']   = $service_type;			
				update_post_meta($post_id,'wr_service_meta',$product_data);
			}
		}		

		if(isset($_POST['plans'])){
			$workreap_plans	= isset( $_POST['plans'] ) ? wp_unslash( $_POST['plans'] ) : array();
			$task_plans 	= array();
			$min_price  	= 0;
			$max_price  	= 0;
			$featured_package       = !empty($_POST['featured_package']) ? $_POST['featured_package'] :'';
			foreach($workreap_plans as $key=>$plan_pkgs){

				if(!empty($plan_pkgs['title']) && !empty($plan_pkgs['price'])){
					$task_plans[$key]   = $plan_pkgs;
					if(!empty($featured_package) && $featured_package === $key){
						$task_plans[$key]['featured_package'] = 'yes';
					} else {
						$task_plans[$key]['featured_package'] = 'no';
					}

					if(empty($min_price) || ($min_price > $plan_pkgs['price'])){
						$min_price          = $plan_pkgs['price'];
					}

					if(empty($max_price) || ($max_price < $plan_pkgs['price'])){
						$max_price          = $plan_pkgs['price'];
					}
					
				}
			}
			
			if( !empty($featured_package) ){
                update_post_meta( $post_id, '_featured_package',$featured_package );
            }
			
			$workreap_plans = $task_plans;
			update_post_meta( $post_id, '_min_price', intval($min_price) );
            update_post_meta( $post_id, '_max_price', intval($max_price) );

			if(isset($workreap_plans['basic']['delivery_time'])){
                update_post_meta( $post_id, '_delivery_time', intval($workreap_plans['basic']['delivery_time']) );
            }			

			update_post_meta($post_id, 'workreap_product_plans', $workreap_plans);
		}

		if(isset($_POST['subtask'])){		
			$workreap_subtask	= isset( $_POST['subtask']['product_subtask'] ) ? wp_unslash( $_POST['subtask']['product_subtask'] ) : array();
			update_post_meta($post_id, 'workreap_product_subtasks', $workreap_subtask);
		}

		if(isset($_POST['tasks_details'])){
			$workreap_tasks_details	= isset( $_POST['subtask'] ) ? wp_unslash( $_POST['subtask'] ) : array();
			update_post_meta($post_id, 'workreap_product_subtask_details_meta', $workreap_tasks_details);
		}

		if(isset($_POST['_workreap_file_names'])){
			$workreap_video_links     = self::prepare_video_links(
				isset( $_POST['_workreap_file_names'] ) ? wp_unslash( $_POST['_workreap_file_names'] ) : array(), 
				isset( $_POST['_workreap_file_urls'] ) ? wp_unslash( $_POST['_workreap_file_urls'] ) : array(), 
				isset( $_POST['_workreap_file_hashes'] ) ? wp_unslash( $_POST['_workreap_file_hashes'] ) : array() 
			);
			update_post_meta($post_id, 'workreap_video_links_files', $workreap_video_links);
		}
		
		if(isset($_POST['faq'])){		
			$workreap_faqs = isset( $_POST['faq'] ) ? wp_unslash( $_POST['faq'] ) : array();
			update_post_meta($post_id, 'workreap_service_faqs', $workreap_faqs);
		}

		if(isset($_POST['product_image_gallery'])){
			$gallery_images	= array();
			
			if(!empty($_POST['product_image_gallery'])){

				$product_image_gallery	= explode(',', $_POST['product_image_gallery']);
				foreach($product_image_gallery as $attachment_id){
					$gallery_images[]	= array(
						'attachment_id'	=> $attachment_id,
						'url'	=> '',
						'name'	=> get_the_title($attachment_id),
						'size'	=> '',
					);
				}
			}
			
			update_post_meta($post_id, '_product_attachments', $gallery_images);
		}	

		if(isset($_POST['workreap_video_url'])){
			
			$workreap_is_video_links = isset( $_POST['workreap_video_url'] ) ? wp_unslash( $_POST['workreap_video_url']) : '';
			update_post_meta($post_id, '_product_video', $workreap_is_video_links);

			if(isset($_POST['_product_video_attachment_id'])){
				update_post_meta($post_id, '_product_video_attachment_id', intval($_POST['_product_video_attachment_id']));
			}
		}

	}

	/**
	 * Prepare downloads for save.
	 *
	 * @param array $file_names File names.
	 * @param array $file_urls File urls.
	 * @param array $file_hashes File hashes.
	 *
	 * @return array
	 */
	private static function prepare_video_links( $file_names, $file_urls, $file_hashes ) {
		$downloads = array();

		if ( ! empty( $file_urls ) ) {
			$file_url_size = count( $file_urls );

			for ( $i = 0; $i < $file_url_size; $i ++ ) {

				if ( ! empty( $file_urls[ $i ] ) ) {
					$downloads[] = array(
						'name'        => wc_clean( $file_names[ $i ] ),
						'file'        => wp_unslash( trim( $file_urls[ $i ] ) ),
						'download_id' => wc_clean( $file_hashes[ $i ] ),
					);
				}
			}
		}

		return $downloads;
	}

}

new Workreap_Admin_Products_Data_Product_Tabs();