<?php
/**
 * Shortcode for latest posted jobs
 *
 *
 * @package    Workreap
 * @subpackage Workreap/admin
 * @author     Amentotech <theamentotech@gmail.com>
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( !class_exists('Workreap_Featured_Jobs') ){
	class Workreap_Featured_Jobs extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_latest_featured_projects';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Latest featured projects', 'workreap' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-sort-amount-desc';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      category of shortcode
		 */
		public function get_categories() {
			return [ 'workreap-ele' ];
		}

		/**
		 * Register category controls.
		 * @since    1.0.0
		 * @access   protected
		 */
		protected function register_controls() {
			$categories	= workreap_elementor_get_taxonomies('product','product_cat');
			$categories	= !empty($categories) ? $categories : array();

			//Content
			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__( 'Content', 'workreap' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__( 'Add Title', 'workreap' ),
					'description' 	=> esc_html__('Add title or leave it empty to hide.', 'workreap'),
				]
			);

			$this->add_control(
				'desc',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label' 		=> esc_html__( 'Add Description', 'workreap' ),
					'description' 	=> esc_html__('Add description or leave it empty to hide.', 'workreap'),
				]
			);
			
			$this->add_control(
				'link_target',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label' => esc_html__('Select listing type', 'workreap'),
					'desc'			=> esc_html__('Do you want to list by categories or Project ids?', 'workreap'),
					'options' 		=> [
										'categories' => esc_html__('Categories', 'workreap'),
										'project_ids' => esc_html__('Project IDs', 'workreap'),
										],
					'default' 		=> 'project_ids',
				]
			);
			$this->add_control(
				'project_ids',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Project ID\'s', 'workreap'),
					'description' 	=> esc_html__('Add Project ID\'s with comma(,) separated e.g(15,21). Leave it empty to show latest projects.', 'workreap'),
					'condition' => [
						'link_target' => 'project_ids',
					],
				]
			);

			$this->add_control(
				'categories',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label'			=> esc_html__('Categories?', 'workreap'),
					'desc' 			=> esc_html__('Select categories to display.', 'workreap'),
					'options'   	=> $categories,
					'multiple' 		=> true,
					'label_block' 	=> true,
					'condition' => [
						'link_target' => 'categories',
					],
				]
			);

			$this->add_control(
				'show_posts',
				[
					'label' => __( 'Number of posts', 'workreap' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'projects' ],
					'range' => [
						'projects' => [
							'min' => 1,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'projects',
						'size' => 9,
					]
				]
			);

			$this->add_control(
				'order',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label'     	=> esc_html__('Order','workreap' ),
					'description'   => esc_html__('Select posts Order.', 'workreap' ),
					'default' 		=> 'DESC',
					'options' 		=> [
						'ASC' 	=> esc_html__('ASC', 'workreap'),
						'DESC' 	=> esc_html__('DESC', 'workreap'),
					],
				]
			);
			
			$this->add_control(
				'orderby',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label'     	=> esc_html__('Post Order','workreap' ),
					'description'   => esc_html__('View Posts By.', 'workreap' ),
					'default' 		=> 'ID',
					'options' 		=> [
						'ID' 		=> esc_html__('Order by post id', 'workreap'),
						'author' 	=> esc_html__('Order by author', 'workreap'),
						'title' 	=> esc_html__('Order by title', 'workreap'),
						'name' 		=> esc_html__('Order by post name', 'workreap'),
						'date' 		=> esc_html__('Order by date', 'workreap'),
						'rand' 		=> esc_html__('Random order', 'workreap'),
						'comment_count' => esc_html__('Order by number of comments', 'workreap'),
					],
				]
			);
			
			
			$this->add_control(
				'btn_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__( 'Add Button Title', 'workreap' ),
					'description' 	=> esc_html__('Add button or leave it empty to hide.', 'workreap'),
				]
			);

			$this->add_control(
				'btn_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__( 'Add Button Link', 'workreap' ),
					'description' 	=> esc_html__('Add button link, or default will be #.', 'workreap'),
				]
			);

			$this->end_controls_section();
		}

		/**
		 * Render shortcode
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		protected function render() {
			$settings = $this->get_settings_for_display();

			$pg_page  = get_query_var('page') ? get_query_var('page') : 1;
			$pg_paged = get_query_var('paged') ? get_query_var('paged') : 1;
			$paged    	= max($pg_page, $pg_paged);

			$show_posts = !empty($settings['show_posts']['size']) ? $settings['show_posts']['size'] : -1;
			$order 		= !empty($settings['order']) ? $settings['order'] : 'ASC';
			$orderby 	= !empty($settings['orderby']) ? $settings['orderby'] : 'ID';

			$title     = !empty($settings['title']) ? $settings['title'] : '';
			$desc      = !empty($settings['desc']) ? $settings['desc'] : '';
			$btn_title = !empty($settings['btn_title']) ? $settings['btn_title'] : '';
			$btn_link  = !empty($settings['btn_link']) ? $settings['btn_link'] : '';

			$link_target  = !empty($settings['link_target']) ? $settings['link_target'] : '';
			
			$query_args = array(
				'posts_per_page' 	  => $show_posts,
				'post_type' 	 	  => 'product',
				'orderby' 	 	  	  => $orderby,
				'order' 	 	  	  => $order,
				'paged' 		 	  => $paged,
				'post_status' 	 	  => array( 'publish'),
				'ignore_sticky_posts' => 1
			);
			$tax_queries	= array();
			$product_type_tax_args[] = array(
				'taxonomy' => 'product_type',
				'field'    => 'slug',
				'terms'    => 'projects',
			  );
			$tax_queries = array_merge($tax_queries,$product_type_tax_args);
			$categories  = !empty($settings['categories']) ? $settings['categories'] : array();
			if( !empty($link_target) && $link_target == 'categories' && !empty($categories) ){
				
				$product_cat_tax_args[] = array(
					'taxonomy'  => 'product_cat',
					'terms'     => $categories,
					'field'     => 'term_id',
					'operator'  => 'IN',
				);
			
				// append product_cat taxonomy args in $tax_queries array
				$tax_queries = array_merge($tax_queries, $product_cat_tax_args);
				
			} else if( !empty($link_target) && $link_target == 'project_ids' ){
				$project_ids  = !empty($settings['project_ids']) ? explode(',',$settings['project_ids']) : array();
				if( !empty($project_ids) ){
					$query_args['post__in']	= $project_ids;
				}

			}
			
			$query_args['tax_query']	= $tax_queries;
			$project_posts = new \WP_Query($query_args); 
			$total_posts   = $project_posts->found_posts;

			?>
			<div class="wt-featuredjobs-section">
				<div class="container-fluid">
					<div class="row justify-content-center">
						<?php if( !empty($title) || !empty($desc) ){ ?>
							<div class="col-12 col-md-10 col-xl-6">
								<div class="wt-sectiontitletwo text-center">
									<?php if( !empty($title) ) { ?><h2><?php echo esc_html($title);?></h2><?php } ?>
									<?php if( !empty($desc) ) { ?><?php echo do_shortcode($desc);?><?php } ?>
								</div>
							</div>
						<?php } ?>
						<?php if ($project_posts->have_posts()) { ?>
							<div class="wt-projectwrap">
								<?php 
									while($project_posts->have_posts()) {
										$project_posts->the_post();
										global $post; 
										$product 		 = wc_get_product( $post->ID );
										$author_id 		 = get_the_author_meta( 'ID' );  
										$linked_profile  = workreap_get_linked_profile_id($author_id);
										$employer_title  = workreap_get_username( $author_id );	
										$employer_avatar = apply_filters(
											'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 100, 'height' => 100), $linked_profile), array('width' => 100, 'height' => 100)
										);
										$duration           = !empty($post) ? get_the_terms(  $post->ID , 'duration' ) : array();
										$selected_duration  = !empty($duration[0]->name) ? $duration[0]->name : 0;
									?>
									<div class="col-12 col-md-6 col-lg-4 col-xl-3">
										<div class="wt-latestproject">
											<?php if( !empty($employer_avatar) ) {?>
												<figure class="wt-projectuser-img">
													<img src="<?php echo esc_url($employer_avatar);?>" alt="<?php echo esc_attr($employer_title);?>">
												</figure>
											<?php } ?>
											<div class="wt-usertags">
												<?php do_action( 'workreap_project_type_tag', $post->ID );?>
											</div>
											<div class="wt-project_title">
												<h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
												<a href="<?php echo esc_url(get_the_permalink( $linked_profile ));?>"><?php echo esc_html($employer_title);?></a>
											</div>
											<ul class="wt-projectinfo">
												<li class="wt-projectinfo-budget">
													<?php do_action( 'workreap_get_project_price_html', $post->ID );?>
												</li>
												<?php if( !empty($selected_duration) ){?>
													<li>
														<span><?php esc_html_e('Duration','workreap');?>:</span>
														<strong><?php echo esc_html($selected_duration);?></strong>
													</li>
												<?php } ?>
												<?php do_action( 'workreap_location_html', $product );?>
											</ul>
											<?php do_action( 'workreap_term_tags', $post->ID,'product_cat','',5,'project' );?>
										</div>
									</div>
								<?php
									} 
									wp_reset_postdata();
								?>
								<?php if( !empty($btn_title) ){?>
									<div class="col-12">
										<div class="wt-projectbtn">
											<a href="<?php echo esc_url($btn_link);?>" class="wt-btnthree"><?php echo esc_html($btn_title);?></a>
										</div>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php
		}
	}

	Plugin::instance()->widgets_manager->register( new Workreap_Featured_Jobs ); 
}