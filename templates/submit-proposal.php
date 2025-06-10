<?php
/**
 * Template Name: Submit Proposal
 *
 * @package     Workreap
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0 http://localhost/www/workreap/create-project/?step=1&page=projects
*/
get_header();
global $post, $current_user,$workreap_settings;
$user_type			= apply_filters('workreap_get_user_type', $current_user->ID );
$proposal_id		= !empty($_GET['id']) ? intval($_GET['id']) : 0;
$project_id        	= (isset($_GET['post_id'])) ? intval($_GET['post_id']) : '';
$post_url			= workreap_get_page_uri('add_project_page');
$hide_fixed_milestone	= !empty($workreap_settings['hide_fixed_milestone']) ? $workreap_settings['hide_fixed_milestone'] : 'no';
$enable_milestone_feature     = !empty($workreap_settings['enable_milestone_feature']) ? $workreap_settings['enable_milestone_feature'] : 'yes';
$allow_project			= false;
$product				= array();
if( !empty($user_type) && $user_type == 'freelancers'){
	$allow_project	= true;	
} else {
	$allow_project	= false;	
}

if( !empty($allow_project) ){
	$proposal_meta		= array();
	$proposal_status	= '';
	if( !empty($proposal_id) ){
		$project_id			= get_post_meta( $proposal_id, 'project_id',true );
		$project_id			= !empty($project_id) ? intval($project_id) : 0;
		$proposal_meta		= get_post_meta( $proposal_id, 'proposal_meta',true );
		$proposal_status	= get_post_status( $proposal_id );
		if(empty($project_id) || (!empty($proposal_status) && !in_array($proposal_status,array('draft','publish','pending') )) ){
			$allow_project	= false;
		}
	}
	
	$proposal_price	= isset($proposal_meta['price']) ? $proposal_meta['price'] : 0;
	$product		= wc_get_product( $project_id );
	$project_meta	= get_post_meta( $project_id, 'wr_project_meta',true);
	$project_type	= !empty($project_meta['project_type']) ? $project_meta['project_type'] : '';
	$commission		= !empty($workreap_settings['admin_commision']) ? esc_html($workreap_settings['admin_commision'].'%') : 0;
	$project_price	= workreap_get_project_price($project_id);
	$price_options	= isset($proposal_price) ? workreap_commission_fee($proposal_price,'return') : array();
	$milestone_image= workreap_add_http_protcol(WORKREAP_DIRECTORY_URI . 'public/images/milestone.jpg');
	$fixed_image	= workreap_add_http_protcol(WORKREAP_DIRECTORY_URI . 'public/images/fixed.png');
	$is_milestone	= !empty($project_meta['is_milestone']) ? $project_meta['is_milestone'] : '';
	$proposal_price	= isset($proposal_price) && $proposal_price > 0 ? $proposal_price : "";	
	
	$checked_fixed_type		= "";
	$checked_milestone_type	= "";
	$checked_fixed_class	= "";
	$checked_milestone_class= "";
	$milestone_content_class= "d-none";
	$all_milestone			= array();
	if( !empty($project_type) && $project_type === 'fixed' ){
		$checked_fixed_type		= "checked";
		$checked_milestone_type	= "";	
		$checked_fixed_class	= "active";
		$checked_milestone_class= "";
		$milestone_content_class= "d-none";
		$proposal_type	= !empty($proposal_meta['proposal_type']) ? $proposal_meta['proposal_type'] : '';
		if( !empty($proposal_type) && $proposal_type === 'fixed' ){
			$checked_fixed_type		= "checked";
			$checked_fixed_class	= "active";
		} else if( !empty($proposal_type) && $proposal_type === 'milestone' ){
			$checked_fixed_type		= "";
			$checked_fixed_class	= "";
			$checked_milestone_type	= "checked";
			$checked_milestone_class= "active";
			$milestone_content_class= "";	
			$all_milestone			= !empty($proposal_meta['milestone']) ? $proposal_meta['milestone'] : array();
		}
	}
}

if(!empty($hide_fixed_milestone) && $hide_fixed_milestone === 'yes'){
	$checked_milestone_type		= "checked";
	$checked_milestone_class	= "active";
	$milestone_content_class	= "";	
}
$enable_ai      = !empty($workreap_settings['enable_ai']) && !empty($workreap_settings['enable_ai_proposal']) ? true : false;
$ai_classs      = !empty($enable_ai) ? 'wr-input-ai' : '';
?>
<section class="wr-main-section">
	<div class="container">
		<div class="row gy-4">
			<?php if( !empty($allow_project) ){?>
				<div class="col-lg-7 col-xl-8">
					<div class="wr-projectbox">
						<div class="wr-project-box">
							<div class="wr-servicedetailtitle">
								<?php if( !empty($product) ){?>
									<h3><?php echo esc_html($product->get_name());?></h3>
								<?php } ?>
								<ul class="wr-blogviewdates">
									<?php do_action( 'workreap_posted_date_html', $product );?>
									<?php do_action( 'workreap_location_html', $product );?>
								</ul>
							</div>
						</div>
						<div class="wr-project-box">
							<form class="wr-themeform" id="tasbkot-submit-proposal">
								<fieldset>
									<div class="wr-themeform__wrap">
										<?php if( !empty($project_type) && $project_type === 'fixed' ){?>
										<div class="form-group wr-input-price">
											<label class="wr-label"><?php esc_html_e('Your budget working rate','workreap');?></label>
											<div class="wr-placeholderholder">
												<input type="text" value="<?php echo esc_attr($proposal_price);?>" name="price" data-post_id="<?php echo intval($project_id);?>" class="form-control wr_proposal_price wr-themeinput" placeholder="<?php esc_attr_e('Enter your budget working rate','workreap');?>">
											</div>
										</div>
										<div class="form-group">
											<ul class="wr-budgetlist">
												<li>
													<span><?php esc_html_e('Project total fixed budget','workreap');?></span>
													<h6><?php echo do_shortcode($project_price);?></h6> 
												</li>
												<li>
													<span><?php esc_html_e('Your budget working rate','workreap');?></span>
													<h6 id="wr_total_rate"><?php if( isset($proposal_price) ){workreap_price_format($proposal_price);};?></h6>
												</li>
												<li>
													<span><?php echo sprintf( esc_html__('Admin commission fee (%s)','workreap'),$commission);?></span>
													<h6 id="wr_service_fee"><?php if( isset($price_options['admin_shares']) ){workreap_price_format($price_options['admin_shares']);}?></h6>
												</li>
											</ul>
										</div>
										<div class="form-group">
											<div class="wr-totalamout">
												<span><?php esc_html_e("Total amount you'll get","workreap");?></span>
												<h5 id="wr_user_share"><?php if( isset($price_options['freelancer_shares']) ){workreap_price_format($price_options['freelancer_shares']);}?></h5>
											</div>
										</div>
										<?php } else {
											do_action( 'workreap_submit_proposal_form', $project_id,$proposal_id,$project_price,$price_options,$commission );
										} ?>
										
										<?php if( (!empty($is_milestone) && $is_milestone === 'yes') 
										&& (!empty($enable_milestone_feature) && $enable_milestone_feature == 'yes')){?>
											<div class="form-group wr-paid-version">
												<div class="wr-betaversion-wrap">
													<div class="wr-betaversion-info-two">
														<h5><?php esc_html_e("How do you want to be paid","workreap");?></h5>
														<p><?php esc_html_e("Employer is open and happy to work with milestones in this project. Feel free to bid your customized milestones.","workreap");?></p>
													</div>
													<ul class="wr-paid-option">
														<li>
															<div class="wr-projectpaid-list <?php echo esc_attr($checked_milestone_class);?>" data-class_id="wr-fixed-milestone">
																<input <?php echo esc_attr($checked_milestone_type);?> type="radio" id="wr-fixed-milestone" name="proposal_type" value="milestone">
																<lable class="wr-projectprice-option" for="wr-fixed-milestone">
																	<?php if( !empty($fixed_image) ){ ?>
																		<img src="<?php echo esc_attr($milestone_image);?>" alt="<?php esc_attr_e('milestone','workreap');?>">
																	<?php } ?>
																	<h6><?php esc_html_e("Work with milestones","workreap");?></h6>
																	<span><?php esc_html_e("Split your work and get paid partially on milestone completion.","workreap");?></span>
																</lable>
															</div>
														</li>
														<?php if(!empty($hide_fixed_milestone) && $hide_fixed_milestone === 'no'){?>
														<li>
															<div class="wr-projectpaid-list <?php echo esc_attr($checked_fixed_class);?>" data-class_id="wr-fixed">
																<input <?php echo esc_attr($checked_fixed_type);?> type="radio" id="wr-fixed" name="proposal_type" value="fixed">
																<lable class="wr-projectprice-option" for="wr-fixed">
																	<?php if( !empty($fixed_image) ){ ?>
																		<img src="<?php echo esc_attr($fixed_image);?>" alt="<?php esc_attr_e('Fixed','workreap');?>">
																	<?php } ?>
																	<h6><?php esc_html_e("Fixed price project","workreap");?></h6>
																	<span><?php esc_html_e("Complete entire project and get full payment at the end.","workreap");?></span>
																</lable>
															</div>
														</li>
														<?php }?>
													</ul>
													<div class="wr-add-price-slots <?php echo esc_attr($milestone_content_class);?>">
														<label class="wr-label"><?php esc_html_e("How many milestones you want to add?","workreap");?>
															<a href="javascript:void(0)" class="wr-addicon" id="wr-add-milestone"><?php esc_html_e("Add milestone","workreap");?> <i class="wr-icon-plus"></i></a>
														</label>
														<div id="wr-list-milestone">
															<?php if( !empty($all_milestone) ){
																	foreach($all_milestone as $key => $value){
																		$k_val	= !empty($key) ? $key : "";
																		$price	= isset($value['price']) ? $value['price'] : "";
																		$detail	= !empty($value['detail']) ? $value['detail'] : "";
																		$title	= !empty($value['title']) ? $value['title'] : "";
																		$status	= !empty($value['status']) ? $value['status'] : "";
																		?>
																		<div class="wr-milestones-prices" id="workreap-milestone-<?php echo esc_attr($key);?>">
																			<div class="wr-grapinput">
																				<div class="wr-milestones-input">
																					<div class="wr-placeholderholder wr-addslots">
																						<input type="text" value="<?php echo esc_attr($price);?>" name="milestone[<?php echo esc_attr($key);?>][price]" class="form-control wr-themeinput" placeholder="<?php esc_attr_e('Enter price','workreap');?>">
																					</div>
																					<div class="wr-placeholderholder">
																						<input type="text" value="<?php echo esc_attr($title);?>" name="milestone[<?php echo esc_attr($key);?>][title]" class="form-control wr-themeinput" placeholder="<?php esc_attr_e('Enter title','workreap');?>">
																					</div>
																					<a href="javascript:;" data-id="<?php echo esc_attr($key);?>" class="wr-remove-milestone wr-removeicon"><i class="wr-icon-trash-2"></i></a>
																				</div>
																				<div class="wr-placeholderholder">
																					<textarea class="form-control wr-themeinput" name="milestone[<?php echo esc_attr($key);?>][detail]" placeholder="<?php esc_attr_e('Enter description', 'workreap');?>"><?php echo do_shortcode($detail);?></textarea>
																				</div>
																			</div>
																			<input type="hidden" name="milestone[<?php echo esc_attr($key);?>][status]" value="<?php echo esc_attr($status);?>">
																		</div>
																<?php } ?>
															<?php } ?>
														</div>
													</div>
												</div>
											</div>
										<?php } ?>
										<div class="wr-comment-section">
											<div class="form-group <?php echo esc_attr($ai_classs);?>">
												<label class="wr-label"><?php esc_html_e('Add special comments to employer','workreap');?></label>
												<?php 
													if(!empty($enable_ai)){
														do_action( 'workreapAIContent', 'proposal_content-'.$proposal_id,'proposal_content' );
													}
												?>
												<div class="wr-placeholderholder">
													<textarea data-ai_content_id="proposal_content-<?php echo esc_attr($proposal_id);?>" class="form-control wr-themeinput" name="description" placeholder="<?php esc_attr_e('Enter your comments here','workreap');?>"><?php if(!empty($proposal_meta['description']) ){echo do_shortcode( $proposal_meta['description'] );}?></textarea>
												</div>
											</div>
										</div>
									</div>
								</fieldset>
								<?php do_action( 'workreap_after_proposal_form',$project_id,$proposal_id );?>
							</form>
						</div>
					</div>
					<div class="wr-proposal-btn">
						<a href="javascript:void(0)" class="wr-btn-solid-lg-lefticon wr_submit_task" data-type="publish" data-project_id="<?php echo intval($project_id);?>" data-proposal_id="<?php echo intval($proposal_id);?>"><?php esc_html_e('Submit bid now','workreap');?></a>
						<?php if( empty($proposal_status)  || $proposal_status === 'draft'){?>
							<a href="javascript:void(0)" class="wr-btnline wr_submit_task" data-type="draft" data-project_id="<?php echo intval($project_id);?>" data-proposal_id="<?php echo intval($proposal_id);?>"><?php esc_html_e('Save as draft','workreap');?></a>
						<?php } ?>
					</div>
				</div>
				<div class="col-lg-5 col-xl-4">
					<aside>
						<div class="wr-projectbox">
							<div class="wr-project-box wr-projectprice">
								<div class="wr-sidebar-title">
									<?php do_action( 'workreap_project_type_tag', $product->get_id() );?>
									<?php do_action( 'workreap_get_project_price_html', $product->get_id() );?>
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
			<?php } else { 
				do_action( 'workreap_notification', esc_html__('Restricted access','workreap'), esc_html__('Oops! you are not allowed to access this page','workreap') );
			 } ?>
		</div>
	</div>
</section>
<?php if( (!empty($is_milestone) && $is_milestone === 'yes') && (!empty($enable_milestone_feature) && $enable_milestone_feature == 'yes')){?>
	<script type="text/template" id="tmpl-load-project-milestone">
		<div class="wr-milestones-prices" id="workreap-milestone-{{data.id}}">
			<div class="wr-grapinput">
				<div class="wr-milestones-input">
					<div class="wr-placeholderholder wr-addslots">
						<input type="text" name="milestone[{{data.id}}][price]" class="form-control wr-themeinput" placeholder="<?php esc_attr_e('Enter price','workreap');?>">
					</div>
					<div class="wr-placeholderholder">
						<input type="text" name="milestone[{{data.id}}][title]" class="form-control wr-themeinput" placeholder="<?php esc_attr_e('Enter title','workreap');?>">
					</div>
					<a href="javascript:;" data-id="{{data.id}}" class="wr-remove-milestone wr-removeicon"><i class="wr-icon-trash-2"></i></a>
				</div>
				<div class="wr-placeholderholder">
					<textarea class="form-control wr-themeinput" name="milestone[{{data.id}}][detail]" placeholder="<?php esc_attr_e('Enter description', 'workreap');?>"></textarea>
				</div>
			</div>
			<input type="hidden" name="milestone[{{data.id}}][status]" value="">
		</div>
	</script>
<?php } ?>
<?php
$scripts = " jQuery(document).ready(function () {
	jQuery('.wr-projectpaid-list').on('click',function(){
		let class_id	= jQuery(this).data('class_id');
		jQuery(this).prop( 'checked', false );

		if ( jQuery(this).hasClass('active') ) {
		  jQuery('#'+class_id).prop( 'checked', true );
		} else {
			jQuery('#'+class_id).prop( 'checked', true );
			jQuery('.wr-projectpaid-list').removeClass('active');
			jQuery(this).addClass('active');    
			jQuery('.wr-add-price-slots').toggleClass('d-none');
		}

		if(class_id === 'wr-fixed'){
			jQuery('.wr-add-price-slots').addClass('d-none');
		}else if(class_id === 'wr-fixed-milestone'){
			jQuery('.wr-add-price-slots').removeClass('d-none');
		}
	});

	removeMilestone();
	jQuery('#wr-add-milestone').on('click', function (e) {
		let counter 	            = workreap_unique_increment(10);
		var load_milestone_temp 	= wp.template('load-project-milestone');
		var data 		            = {id: counter};
		load_milestone_temp	        = load_milestone_temp(data);
		jQuery('#wr-list-milestone').append(load_milestone_temp);
		removeMilestone();
	});

	function removeMilestone(){
		jQuery('.wr-remove-milestone').on('click', function (e) {
			jQuery(this).closest('.wr-milestones-prices').remove();
		});
	}
	var wr_sortable = document.getElementById('wr-list-milestone');
	if (wr_sortable !== null) {
		var wr_sortable = Sortable.create(wr_sortable, {
			animation: 350,
		});
	} 
});";
wp_add_inline_script('workreap', $scripts, 'before');
get_footer();
