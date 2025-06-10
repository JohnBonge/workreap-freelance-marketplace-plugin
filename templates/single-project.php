<?php

/**
 *
 * The template used for displaying task detail
 *
 * @package     Workreap
 * @subpackage  Workreap/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $post, $thumbnail,$current_user;
do_action('workreap_post_views', $post->ID,'workreap_project_views');
get_header();
while (have_posts()) : the_post();
	$product 				= wc_get_product( $post->ID );
	$product_cat 			= wp_get_post_terms( $post->ID, 'product_cat', array( 'fields' => 'ids' ) );
	$post_status			= get_post_status( $post->ID );
	$post_author			= get_post_field( 'post_author', $post->ID );
	$allow_user				= true;
	$linked_profile			= "";

	$description    = !empty($product) ? $product->get_description() : "";
	$user_id    	= get_post_field( 'post_author', $product->get_id() );
	$user_id    	= !empty($user_id) ? intval($user_id) : 0;
	
	//check if proposal is submitted
	if( is_user_logged_in() ){
		$workreap_user_proposal  = 0;
		$proposal_args = array(
			'post_type' 	    => 'proposals',
			'post_status'       => 'any',
			'posts_per_page'    => -1,
			'author'            => $current_user->ID,
			'meta_query'        => array(
				array(
					'key'       => 'project_id',
					'value'     => intval($post->ID),
					'compare'   => '=',
					'type'      => 'NUMERIC'
				)
			)
		);

		$proposals                  = get_posts( $proposal_args );
		$workreap_user_proposal      = !empty($proposals) && is_array($proposals) ? count($proposals) : 0;
		$proposal_id = isset($proposals[0]->ID) ? $proposals[0]->ID : 0;
		$proposal_status	= get_post_status( $proposal_id );
		$proposal_edit_link = !empty($proposals) ? workreap_get_page_uri('submit_proposal_page').'?id='.intval($proposals[0]->ID) : '';
	}

	if(!empty($post_status) && in_array($post_status,array('draft','rejected','pending','refunded','disputed','hired'))){
		if( !is_user_logged_in( ) ){
			$allow_user			= false;
		} else {
			if( is_user_logged_in() && (current_user_can('administrator') || $current_user->ID == $post_author) ){
				$allow_user		= true;
			} else {
				$allow_user		= false;
				if(!empty($workreap_user_proposal)){
					$allow_user		= true;
				}
			}
		}
	}	

	$download_class	= '';
	$submint_class	= '';
	$page_url		= '';
	if( !is_user_logged_in( ) ){
		$download_class	= 'wr-login-freelancer';
		$submint_class	= 'wr-login-freelancer';
	} else {
		if( is_user_logged_in() && (current_user_can('administrator') || $current_user->ID == $post_author) ){
			$download_class	= 'wr_download_files';
			$submint_class	= 'wr-login-freelancer';
		} else {
			$user_type  		= apply_filters('workreap_get_user_type', $current_user->ID );
			$linked_profile     = workreap_get_linked_profile_id($current_user->ID, '', $user_type);
			
			if( !empty($user_type) && $user_type === 'freelancers' ){
				$download_class	= 'wr_download_files';
				$submint_class	= 'wr-page-link';
				$page_url		= !empty($post->ID) ?workreap_get_page_uri('submit_proposal_page').'?post_id='.intval($post->ID) : '';
			} else if( !empty($user_type) && $user_type === 'employers' ){
				$download_class	= 'wr-authorization-required';
				$submint_class	= 'wr-redirect-url';
			}
		}
	}
		
	$profile_id 	= !empty($user_id) ? workreap_get_linked_profile_id($user_id, '', 'employers') : 0;
	$user_name  	= !empty($profile_id) ? workreap_get_username($profile_id) : '';
	$product_data   = get_post_meta($product->get_id(), 'wr_project_meta', true);
	$downloadable  	= get_post_meta( $product->get_id(), '_downloadable',true );
	$vid_url		= !empty($product_data['video_url']) ? esc_url($product_data['video_url']) : '';
		
	?>
	<section class="wr-main-section overflow-hidden wr-main-bg">
		<div class="container">
			<?php
				if( empty($allow_user) ){
					do_action( 'workreap_notification', esc_html__('Restricted access','workreap'), esc_html__('Oops! you are not allowed to access this page','workreap') );
				} else {?>
				<div class="row gy-4">
					<div class="col-lg-7 col-xl-8">
						<div class="wr-projectbox">
							<?php do_action( 'workreap_featured_item', $product,'featured_project' );?>
							<div class="wr-project-box">
								<div class="wr-servicedetailtitle">
									<h3><?php echo esc_html($product->get_name());?></h3>
									<ul class="wr-blogviewdates">
										<?php do_action( 'workreap_posted_date_html', $product );?>
										<?php do_action( 'workreap_location_html', $product );?>
									</ul>
								</div>
							</div>
							<div class="wr-project-box">
								<?php if( !empty($vid_url) ){?>
									<div class="wr-project-holder">
										<?php
											$vid_width		= 780;
											$vid_height		= 402;
											$url 			= parse_url( $vid_url );
											$video_html		= '';
											if ($url['host'] == 'vimeo.com' || $url['host'] == 'player.vimeo.com') {
												$video_html	.= '<figure class="wr-projectdetail-img">';
												$content_exp  = explode("/" , $vid_url);
												$content_vimo = array_pop($content_exp);
												$video_html	.= '<iframe width="' . esc_attr( $vid_width ) . '" height="' . esc_attr( $vid_height ) . '" src="https://player.vimeo.com/video/' . $content_vimo . '" 
											></iframe>';
												$video_html	.= '</figure>';
											} else if($url['host'] == 'youtu.be') {
												$video_html	.= '<figure class="wr-projectdetail-img">';
												$video_html	.= preg_replace(
													"/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
													"<iframe width='" . esc_attr( $vid_width ) ."' height='" . esc_attr( $vid_height ) . "' src=\"//www.youtube.com/embed/$2\" frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>",
													$vid_url
												);
												$video_html	.= '</figure>';
											} else if($url['host'] == 'dai.ly') {
												$path		= str_replace('/','',$url['path']);
												$content	= str_replace('dai.ly','dailymotion.com/embed/video/',$vid_url);
												$video_html	.= '<figure class="wr-projectdetail-img">';
													$video_html	.= '<iframe width="' . esc_attr( $vid_width ) . '" height="' . esc_attr( $vid_height ) . '" src="' . esc_url( $content ) . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
												$video_html	.= '</figure>';
											}else {
												$video_html	.= '<figure class="wr-projectdetail-img">';
												$content = str_replace(array (
													'watch?v=' ,
													'http://www.dailymotion.com/' ) , array (
													'embed/' ,
													'//www.dailymotion.com/embed/' ) , $vid_url);
												$content	= str_replace('.com/video/','.com/embed/video/',$content);
												$video_html	.= '<iframe width="' . esc_attr( $vid_width ) . '" height="' . esc_attr( $vid_height ) . '" src="' . esc_url( $content ) . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
												$video_html	.= '</figure>';
											}
											if( !empty($video_html) ){
												echo do_shortcode( $video_html );
											}
										?>
									</div>
								<?php } ?>
								<?php if( !empty($description) ){?>
									<div class="wr-project-holder wr-project-description">
										<div class="wr-project-title">
											<h4><?php esc_html_e('Job description','workreap');?></h4>
										</div>
										<div class="wr-jobdescription">
											<?php echo do_shortcode(nl2br ($description) );?>
										</div>
									</div>
								<?php } ?>
								<?php do_action( 'workreap_term_tags_html', $product->get_id(),'skills',esc_html__('Skills required','workreap') );?>
								<?php if( !empty($downloadable) && $downloadable === 'yes' ){?>
									<div class="wr-project-holder">
										<div class="wr-betaversion-wrap">
											<div class="wr-betaversion-info">
												<h5><?php esc_html_e('Attachments available to download','workreap');?></h5>
												<p><?php echo sprintf(esc_html__('Download project helping material provided by “%s”','workreap'),$user_name);?></p>
											</div>
											<div class="wr-downloadbtn">
												<span class="wr-btn-solid-lefticon <?php echo esc_attr($download_class);?>" data-id="<?php echo intval($product->get_id());?>" data-order_id=""><?php esc_html_e('Download files','workreap');?> <i class="wr-icon-download"></i></span>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="col-lg-5 col-xl-4">
                        <aside>
                            <div class="wr-projectbox">
                                <div class="wr-project-box wr-projectprice">
                                    <div class="wr-sidebar-title">
                                        <?php do_action( 'workreap_project_type_tag', $product->get_id() );?>
                                        <?php do_action( 'workreap_get_project_price_html', $product->get_id() );?>
										<?php do_action( 'workreap_project_estimation_html', $product->get_id() );?>
                                    </div>
                                    <div class="wr-sidebarpkg__btn">
										<?php
										if( intval($current_user->ID) !== intval( $post_author ) ){
                                            if(!empty($workreap_user_proposal) ){
	                                            if(isset($current_user->ID) && isset($proposal_status) && $proposal_status === 'hired' ){ ?>
                                                    <span><a class="wr-btn-solid-lg-lefticon wr-page-link" href="<?php echo esc_url(Workreap_Profile_Menu::workreap_profile_menu_link('projects', $current_user->ID, true, 'activity',$proposal_id));?>"><?php esc_html_e('Project activity','workreap');?></a></span>
	                                            <?php }else if( !empty($post_status) && !in_array($post_status,array('draft','rejected','pending','refunded','disputed','hired','completed'))){ ?>
                                                    <span><a href="<?php echo esc_url($proposal_edit_link);?>" class="wr-btn-solid-lg-lefticon wr-page-link"><?php esc_html_e('Edit proposal','workreap');?></a></span>
                                                <?php }
                                            }else{
                                                if( !empty($post_status) && $post_status === 'publish') {?>
                                                    <span class="wr-btn-solid-lg-lefticon <?php echo esc_attr($submint_class);?>" data-url="<?php echo esc_url($page_url);?>"><?php esc_html_e('Apply to this project','workreap');?></span>
                                                <?php }
                                                }
                                        } ?>
                                        <?php do_action( 'workreap_project_saved_item', $product->get_id(), '', '_saved_projects' ); ?>
                                    </div>
                                </div>
                                <div class="wr-project-box">
                                    <div class="wr-sidebar-title">
                                        <h5><?php esc_html_e('Project requirements','workreap');?></h5>
                                    </div>
                                    <ul class="wr-project-requirement">
                                       <?php do_action( 'workreap_total_hiring_freelancer_html', $product->get_id() );?>
									   <?php do_action( 'workreap_texnomies_html', $product->get_id(),'expertise_level',esc_html__('Expertise','workreap'),'wr-icon-briefcase wr-darkred-icon' );?>
                                       <?php do_action( 'workreap_texnomies_html', $product->get_id(),'languages',esc_html__('Languages','workreap'),'wr-icon-book-open wr-yellow-icon' );?>
                                       <?php do_action( 'workreap_texnomies_html', $product->get_id(),'duration',esc_html__('Project duration','workreap'),'wr-icon-calendar wr-green-icon' );?>
									   <?php do_action( 'workreap_after_project_requirements', $product->get_id());?>
                                    </ul>
                                </div>
                            </div>
							<?php do_action( 'workreap_project_freelancer_basic', $product->get_id() );?>
                        </aside>
                    </div>
                </div>
				<?php workreap_get_template('dashboard/post-project/related-projects.php',array('project_id' => $product->get_id()));?>
			<?php } ?>
		</div>
	</section>
<?php
endwhile;

$script = "WorkreapShowMore('.description-with-more');";
wp_add_inline_script( 'workreap', $script, 'after' );

get_footer();

